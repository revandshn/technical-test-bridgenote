@extends('layout.app')

@section('title')
Bridge Note ID
@endsection

@section('header')
@include('layout.header')
@endsection

@section('content')
<div class="container">
    <form method="POST" accept="{{route('auth.login')}}">
        @csrf
        <div class="card mt-4">
            <article class="card-body">
                <a href="{{route('auth.create')}}" class="float-right btn btn-outline-primary">Register</a>
                <h4 class="card-title mb-4 mt-1">Login</h4>
                <form>
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
                        <button type="submit" class="btn btn-primary btn-block"> Login </button>
                    </div>
                </form>
            </article>
        </div>
    </form>
</div>
@endsection

@section('footer')
@include('layout.footer')
@endsection