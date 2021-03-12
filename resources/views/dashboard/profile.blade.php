@extends('layout.app')


@section('title')
Profile | Bridge Note ID
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
    <div class="card mt-4">
        <img src="{{Storage::disk('s3')->url($user->avatar)}}" class="card-img-top" alt="avatar"
            style="max-width: 20%;">
        <div class="card-body">
            <h5 class="card-title font-weight-bold">Profile</h5>
            <p class="card-text">{{$user->name}}</p>
        </div>
        <ul class="list-group list-group-flush">
            <table class="list-group-item">
                <tr>
                    <td class="font-weight-bold">Email</td>
                    <td class="pl-4 pr-3 font-weight-bold">:</td>
                    <td>{{$user->email}}</td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Phone</td>
                    <td class="pl-4 pr-3 font-weight-bold">:</td>
                    <td>{{$user->phone_number}}</td>
                </tr>

                @isset($userDetail)
                <tr>
                    <td class="font-weight-bold">Status</td>
                    <td class="pl-4 pr-3 font-weight-bold">:</td>
                    <td>{{$userDetail->status}}</td>
                </tr>

                <tr>
                    <td class="font-weight-bold">Position</td>
                    <td class="pl-4 pr-3 font-weight-bold">:</td>
                    <td>{{$userDetail->position}}</td>
                </tr>
                @endisset

            </table>
        </ul>
    </div>
</div>
</div>
@endsection