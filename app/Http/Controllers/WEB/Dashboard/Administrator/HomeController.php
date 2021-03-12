<?php

namespace App\Http\Controllers\WEB\Dashboard\Administrator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        if (!$user)
            return view('dashboard.home')->with('failed', 'No user');

        return view('dashboard.home', compact('user'));
    }

    public function users()
    {
        $user = User::where('role', 0)->get();
        if ($user->isEmpty())
            return redirect()->back()->with('failed', 'No user');
        return view('dashboard.user-list', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id)
    {
        $user = User::find($user_id);

        if (!$user)
            return redirect()->back() > with('failed', 'No user');

        $userDetail = UserDetail::where('user_id', $user->id)->count();
        if ($userDetail >= 1)
            return redirect()->back()->with('failed', 'User already has detail, You can still edit');


        return view('dashboard.user-detail', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'status' => 'required|string',
            'position' => 'required|string',
        ]);

        $user = User::find($user_id);

        if (!$user)
            return redirect()->back()->with('failed', 'No user');

        $userDetail = UserDetail::firstOrCreate([
            'user_id' => $user_id,
            'status' => $request->status,
            'position' => $request->position
        ]);

        if (!$userDetail)
            return redirect()->back()->with('failed', 'User detail failed create');

        return view('dashboard.profile', compact('user', 'userDetail'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $user = User::find($user_id);

        if (!$user)
            return view('dashboard.profile')->with('failed', 'No user');

        $userDetail = UserDetail::where('user_id', $user_id)->first();

        if (!$userDetail)
            return view('dashboard.profile', compact('user'));


        return view('dashboard.profile', compact('user', 'userDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id)
    {
        $userDetail = UserDetail::where('user_id', $user_id)->first();

        if (!$userDetail)
            return redirect()->back()->with('failed', 'No user');

        return view('dashboard.user-detail', compact('userDetail'));
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
            'status' => 'required|string',
            'position' => 'required|string',
        ]);

        $user = User::find($user_id);
        if (!$user)
            return redirect()->back()->with('failed', 'No user');

        $userDetail = UserDetail::where('user_id', $user->id)->first();

        if (!$userDetail)
            return redirect()->back()->with('failed', 'No user detail');

        $userDetail->status = $request->status;
        $userDetail->position = $request->position;

        if (!$userDetail->save())
            return redirect()->back()->with('failed', 'User detail failed updated');

        return view('dashboard.profile', compact('user', 'userDetail'));
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
}
