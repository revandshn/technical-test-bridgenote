<?php

namespace App\Http\Controllers\WEB\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id)
    {
        $user = User::find($user_id);
        if (!$user)
            return view('dashboard.show-profile')->with('failed', 'No user');

        return view('dashboard.edit-profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        $request->validate([
            'name' => 'required|string|between:3,255',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'avatar' => 'mimes:jpeg,png,jpg'
        ]);

        $user = User::find($user_id);
        if (!$user)
            return view('dashboard.show-profile')->with('failed', 'No user');

        if ($user->email !== $request->email || $user->phone_number !== $request->phone_number) {
            $check = User::where('email', $request->email)
                ->orWhere('phone_number', $request->phone_number)->count();

            if ($check >= 1)
                return view('dashboard.show-profile')->with('failed', 'Email or Phone Number already taken');
        }

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
                return view('dashboard.show-profile')->with('failed', 'Update avatar failed');
            }

            $user->avatar = 'avatars/' . $avatarName;
        }

        if (!$user->save())
            return view('dashboard.show-profile')->with('failed', 'Update failed');

        return redirect()->route('dashboard.my-profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        //
    }

    public function profile()
    {
        $user = User::find(Auth::user()->id);

        if (!$user)
            return view('dashboard.profile')->with('failed', 'No user');

        $userDetail = UserDetail::where('user_id', Auth::user()->id)->first();

        if (!$userDetail)
            return view('dashboard.profile', compact('user'));


        return view('dashboard.profile', compact('user', 'userDetail'));
    }
}
