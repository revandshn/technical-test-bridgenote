<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = User::paginate(10);
            if ($user->isEmpty())
                return $this->errorResponse('User is empty', 404);

            return $this->successResponse($user, 'Users list', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        try {
            $user = User::find($user_id);

            if (!$user)
                return $this->errorResponse('User not found',  404);

            $user->avatar = Storage::disk('s3')->url($user->avatar);

            // Check User Detail
            $userDetail = UserDetail::where('user_id', $user->id)->first();

            if (!$userDetail)
                return $this->successResponse($user, 'User profile', 200);

            $data = [
                'user' => $user,
                'detail' => $userDetail
            ];

            return $this->successResponse($data, 'User profile', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required|string|between:3,255',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'avatar' => 'image|mimes:jpeg,png,jpg'
        ]);

        // Check validation
        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), 400);
        }

        try {
            $user = User::find(Auth::user()->id);

            if (!$user)
                return $this->errorResponse('No User', 404);

            if ($user->email !== $request->email || $user->phone_number !== $request->phone_number) {
                $check = User::where('email', $request->email)
                    ->orWhere('phone_number', $request->phone_number)->count();

                if ($check >= 1)
                    return $this->errorResponse('Email or Phone Number already taken', 400);
            }

            // Update data
            $user->name = $request->name;
            $user->email = strtolower($request->email);
            $user->phone_number = $request->phone_number;

            if ($request->has('avatar')) {
                if (Storage::disk('s3')->exists($user->avatar) && $user->avatar != 'avatars/default.png') {
                    // Delete current avatar
                    Storage::disk('s3')->delete($user->avatar);
                }

                // Upload new Avatar
                $avatarName = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                $img = Image::make($request->file('avatar')->getRealPath())->resize(1000, 900);
                $resource = $img->stream();

                $imgObj = Storage::disk('s3')->put(
                    'avatars/' . $avatarName,
                    $resource
                );

                if (!$imgObj) {
                    $avatarName = 'avatars/default.png';
                    return $this->errorResponse('Failed upload img', 404);
                }

                $user->avatar = 'avatars/' . $avatarName;
            }

            if (!$user->save())
                return $this->errorResponse('Update failed', 406);


            $user->avatar = Storage::disk('s3')->url($user->avatar);

            return $this->successResponse($user, 'User updated', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        try {
            $user = User::find($user_id);

            if (!$user)
                return $this->errorResponse('User not found',  404);

            $userDetail = UserDetail::where('user_id', $user->id)->first();

            if (!$userDetail) {
                $user->delete();

                if ($user->trashed())
                    return $this->successResponse('Delete successful', 200);
                return $this->errorResponse('Delete failed', 404);
            }

            $user->delete();
            $userDetail->delete();

            if ($user->trashed() && $userDetail->trashed())
                return $this->successResponse('Delete successful', 200);
            return $this->errorResponse('Delete failed', 404);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 404);
        }
    }

    public function restore(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // Check validation
        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), 400);
        }

        try {
            $user = User::withTrashed()->where('email', $request->email)->restore();

            if (!$user)
                return $this->errorResponse('User not found',  404);

            return $this->successResponse($user, 'User restored', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 404);
        }
    }

    public function profile()
    {
        try {
            $user = User::find(Auth::user()->id);
            if (!$user)
                return $this->errorResponse('User not found',  404);

            if (Storage::disk('s3')->missing($user->avatar)) {
                $avatar = 'avatars/default.png';

                $user->avatar = $avatar;
                $user->save();

                // Storage URL
                $user->avatar = Storage::disk('s3')->url($avatar);
                return $this->successResponse($user, 'User profile', 200);
            }

            $user->avatar = Storage::disk('s3')->url($user->avatar);

            // Check User Detail
            $userDetail = UserDetail::where('user_id', $user->id)->first();

            if (!$userDetail)
                return $this->successResponse($user, 'User profile', 200);

            $data = [
                'user' => $user,
                'detail' => $userDetail
            ];

            return $this->successResponse($data, 'User profile', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 404);
        }
    }
}
