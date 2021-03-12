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
    <div class="alert alert-danger mt-3">
        {{ session('failed') }}
    </div>
    @endif

    <table class="table mt-4 mb-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Action</th>
                <th scope="col">Profile</th>
            </tr>
        </thead>
        <tbody>
            @isset($user)
            @foreach ($user as $item)
            <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td>{{$item->name}}</td>
                <td>{{$item->email}}</td>
                <td>{{$item->phone_number}}</td>
                <td>
                    <a href="{{route('dashboard.admin-user-add', $item->id)}}" class="badge badge-primary">Add</a>
                    <a href="{{route('dashboard.admin-user-edit', $item->id)}}" class="badge badge-warning">Edit</a>
                    <a href="" class="badge badge-danger">Delete</a>
                </td>
                <td>
                    <a href="{{route('dashboard.admin-user-show', $item->id)}}" class="badge badge-dark">Show</a></a>
                </td>
            </tr>
            @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endsection

@section('footer')
@include('layout.footer')
@endsection