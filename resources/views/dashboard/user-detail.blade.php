@extends('layout.app')


@section('title')
Edit Profile | Bridge Note ID
@endsection

@section('header')
@include('dashboard.header')
@endsection


@section('content')
{{-- status messages --}}
@if (session('failed'))
<div class="alert alert-danger">
    {{ session('failed') }}
</div>
@endif
<div class="container">
    <form method="POST" @isset($userDetail) accept="{{route('dashboard.admin-user-update', $userDetail->id)}}" @endisset
        @isset($user) accept="{{route('dashboard.admin-user-add', $user->id)}}" @endisset>
        @isset($userDetail)
        @method('PUT')
        @endisset
        @csrf
        <div class="card mt-4">
            <article class="card-body">
                <h4 class="card-title mb-4 mt-1">Add or Edit User Detail</h4>
                <form>
                    <div class="form-group">
                        <label>User status</label>
                        <select class="form-control" name="status" aria-label="Default select example">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @if ($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>User position</label>
                        <input name="position" class="form-control" placeholder="Position" type="name"
                            @isset($userDetail) value="{{$userDetail->position}}" @endisset>
                        @if ($errors->has('position'))
                        <span class="text-danger">{{ $errors->first('position') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Submit </button>
                    </div>
                </form>
            </article>
        </div>
    </form>
</div>
</div>
@endsection