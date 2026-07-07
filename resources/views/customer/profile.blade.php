@extends('layouts.shop')

@section('content')

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header">

            <h3>My Profile</h3>

        </div>

        <div class="card-body">

            <h5>Name : {{ $customer->name }}</h5>

            <h5>Email : {{ $customer->email }}</h5>

            <h5>Mobile : {{ $customer->mobile }}</h5>

        </div>

    </div>

</div>

@endsection