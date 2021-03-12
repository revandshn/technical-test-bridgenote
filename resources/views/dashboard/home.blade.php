@extends('layout.app')

@section('title')
Dashboard | Bridge Note ID
@endsection

@section('header')
@include('dashboard.header')
@endsection

@section('content')
<div class="container">

    {{-- status messages --}}
    @if (session('failed'))
    <div class="alert alert-danger">
        {{ session('failed') }}
    </div>
    @endif

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Hello {{$user->name}} . . .</h5>
            <p class="card-text">Welcome to Bridge Note</p>
            <a href="{{route('dashboard.my-profile')}}" class="btn btn-dark">See profile</a>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('layout.footer')
@endsection