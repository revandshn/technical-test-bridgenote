@extends('layout.app')

@section('title')
Bridge Note ID
@endsection

@section('header')
@include('layout.header')
@endsection

@section('content')
<div class="container">
    <form method="POST" accept="{{route('auth.register')}}" enctype="multipart/form-data">
        @csrf
        <div class="card mt-4 mb-4">
            <article class="card-body">
                <a href="{{route('auth.index')}}" class="float-right btn btn-outline-primary">Login</a>
                <h4 class="card-title mb-4 mt-1">Register</h4>
                <form>
                    <div class="form-group">
                        <label>Your name</label>
                        <input name="name" class="form-control" placeholder="Name" type="name">
                        @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Your email</label>
                        <input name="email" class="form-control" placeholder="Email" type="email">
                        @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Your password</label>
                        <input name="password" class="form-control" placeholder="******" type="password">
                        @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Your confirmation password</label>
                        <input name="password_confirmation" class="form-control" placeholder="******" type="password">
                        @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Your phone number</label>
                        <input name="phone_number" class="form-control" min="0" placeholder="08****" type="number">
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
                        <button type="submit" class="btn btn-primary btn-block"> Register </button>
                    </div>
                </form>
            </article>
        </div>
    </form>
</div>
@endsection