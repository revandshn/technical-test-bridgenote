<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;


class AuthController extends Controller
{
    public function signup(Request $request)
    {
        // Validate
        $valid = Validator::make($request->all(), [
            'role' => 'digits_between:1,2',
            'name' => 'required|string|between:3,255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|between:8,255|confirmed',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users',
            'avatar' => 'image|mimes:jpeg,png,jpg'

        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), 400);
        }

        try {
            // Default role for user(customer)
            $role = 0;
            if ($request->filled('role')) {
                // User (customer)
                $role = $request->role;
            }

            // Avatar check
            $avatar = 'avatars/default.png';
            if ($request->has('avatar')) {
                $avatarName = time() . '.' . $request->file('avatar')->getClientOriginalExtension();

                $img = Image::make($request->file('avatar')->getRealPath())->resize(1000, 900);
                $resource = $img->stream();

                $imgObj = Storage::disk('s3')->put(
                    'avatars/' . $avatarName,
                    $resource
                );

                if (!$imgObj) {
                    // make default
                    $avatar = 'avatars/default.png';
                    return $this->errorResponse('Failed upload img', 404);
                }

                // Avatar URL
                $avatar = 'avatars/' . $avatarName;
            }

            User::firstOrCreate([
                'role' => $role,
                'name' => $request->name,
                'email' => strtolower($request->email),
                'password' => bcrypt($request->password),
                'phone_number' => $request->phone_number,
                'avatar' => $avatar
            ]);

            return $this->signin($request);
        } catch (QueryException  $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == '1062') {
                return $this->errorResponse("Email is already used", 400);
            }

            return $this->errorResponse($e, 400);
        }
    }

    public function signin(Request $request)
    {
        // Validate
        $valid = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|between:8,255'
        ]);

        if ($valid->fails())
            return $this->errorResponse($valid->errors(), 400);

        // Auth attempt
        $credential = $request->only('email', 'password');
        if (!Auth::attempt($credential))
            return $this->errorResponse("Email or Password doesn't match", 404);

        $access_token = Auth::user()->createToken('authToken')->accessToken;
        $user = Auth::user();

        $data = [
            'user' => $user,
            'access_token' => $access_token
        ];

        return $this->successResponse($data, "Sign in successful", 200);
    }

    public function signout(Request $request)
    {
        if ($request->user()->token()->revoke())
            return $this->successResponse('Sign out successful', 200);

        return $this->errorResponse('Sign out failed, please check your token', 404);
    }
}
