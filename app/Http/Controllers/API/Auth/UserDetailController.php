<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserDetailController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id)
    {
        $valid = Validator::make($request->all(), [
            'status' => 'required|string',
            'position' => 'required|string',
        ]);

        // Check validation
        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), 400);
        }

        try {
            $user = User::find($user_id);

            if (!$user)
                return $this->errorResponse('User not found', 404);

            $userDetail = UserDetail::firstOrCreate([
                'user_id' => $user_id,
                'status' => $request->status,
                'position' => $request->position
            ]);

            if (!$userDetail)
                return $this->errorResponse('User detail failed', 404);

            $data = [
                'user' => $user,
                'detail' => $userDetail
            ];

            return $this->successResponse($data, 'User detail accepted', 201);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 404);
        }
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

            $userDetail = UserDetail::where('user_id', $user->id)->first();

            if (!$userDetail)
                return $this->errorResponse('User detail not found', 404);

            return $this->successResponse($userDetail, 'User detail', 200);
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
    public function update(Request $request, $user_id)
    {
        $valid = Validator::make($request->all(), [
            'status' => 'required|string',
            'position' => 'required|string',
        ]);

        // Check validation
        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), 400);
        }

        try {
            $user = User::find($user_id);

            if (!$user)
                return $this->errorResponse('User not found', 404);

            $userDetail = UserDetail::where('user_id', $user->id)->first();

            if (!$userDetail)
                return $this->errorResponse('User not found', 404);

            $userDetail->status = $request->status;
            $userDetail->position = $request->position;

            if (!$userDetail->save())
                return $this->errorResponse('User detail update failed', 400);

            $data = [
                'user' => $user,
                'detail' => $userDetail
            ];

            return $this->successResponse($data, 'User detail updated', 202);
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
                return $this->errorResponse('User not found', 404);

            $userDetail = UserDetail::where('user_id', $user->id)->first();

            if (!$userDetail)
                return $this->errorResponse('User not found', 404);

            $userDetail->delete();
            if ($userDetail->trashed())
                return $this->successResponse('Delete successful', 200);
            return $this->errorResponse('Delete failed', 404);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 404);
        }
    }
}
