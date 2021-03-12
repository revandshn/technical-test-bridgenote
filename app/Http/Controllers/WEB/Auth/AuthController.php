<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class AuthController extends Controller
{

    public function index()
    {
        if (!Auth::check())
            return view('login');

        switch (Auth::user()->role) {
            case 0:
                return redirect()->route('dashboard.user-index');
                break;
            case 1:
                return redirect()->route('dashboard.admin-index');
            default:
                return redirect()->back();
        }
    }

    public function create()
    {
        if (!Auth::check())
            return view('register');

        switch (Auth::user()->role) {
            case 0:
                return redirect()->route('dashboard.user-index');
                break;
            case 1:
                return redirect()->route('dashboard.admin-index');
            default:
                return redirect()->back();
        }
    }

    public function signup(Request $request)
    {
        // Validate
        $request->validate([
            'role' => 'digits_between:1,2',
            'name' => 'required|string|between:3,255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|between:8,255|confirmed',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users',
            'avatar' => 'image|mimes:jpeg,png,jpg'
        ]);

        // Default role for user(customer)
        $role = 0;
        if ($request->filled('role')) {
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
    }

    public function signin(Request $request)
    {
        // Validate
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|between:8,255'
        ]);

        // Auth attempt
        $credential = $request->only('email', 'password');
        if (!Auth::attempt($credential))
            return redirect('/')->with('status', 'Login Failed');

        $user = User::where('email', $request->email)->first();
        switch ($user->role) {
            case 0:
                return redirect()->route('dashboard.user-index');
                break;
            case 1:
                return redirect()->route('dashboard.admin-index');
            default:
                return redirect()->back();
        }
    }

    public function signout(Request $request)
    {
        Auth::logout();

        return redirect()->route('auth.index');
    }
}
