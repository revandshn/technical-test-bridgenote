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
    <form method="POST" accept="{{route('dashboard.update-profile', Auth::user()->id)}}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card mt-4">
            <article class="card-body">
                <h4 class="card-title mb-4 mt-1">Edit Profile</h4>
                <form>
                    <div class="form-group">
                        <label>Your name</label>
                        <input name="name" class="form-control" placeholder="Name" type="name" value="{{$user->name}}">
                        @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Your email</label>
                        <input name="email" class="form-control" placeholder="Email" type="email"
                            value="{{$user->email}}">
                        @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Your phone number</label>
                        <input name="phone_number" class="form-control" min="0" placeholder="08****" type="number"
                            value="{{$user->phone_number}}">
                        @if ($errors->has('phone_number'))
                        <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Your avatar</label>
                        <input name="avatar" class="form-control" type="file">
                        @if ($errors->has('avatar'))
                        <span class="text-danger">{{ $errors->first('avatar') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Update </button>
                    </div>
                </form>
            </article>
        </div>
    </form>
</div>
</div>
@endsection