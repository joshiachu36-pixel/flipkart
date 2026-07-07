@extends('layouts.shop')

@section('content')

<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card shadow-lg border-0">

                <div class="card-header bg-primary text-white text-center py-3">

                    <h3>Create Your Account</h3>

                    <p class="mb-0">
                        Join us and start shopping today!
                    </p>

                </div>

                <div class="card-body p-4">

                    <form method="POST"
                          action="{{ route('customer.register.store') }}">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">

                                Full Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="Enter your full name">

                            @error('name')

                                <small class="text-danger">

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Email Address

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="Enter your email">

                            @error('email')

                                <small class="text-danger">

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Mobile Number

                            </label>

                            <input
                                type="text"
                                name="mobile"
                                class="form-control"
                                value="{{ old('mobile') }}"
                                placeholder="Enter 10-digit mobile number"
                                maxlength="10">
                            @error('mobile')

                                <small class="text-danger">

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Password

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter password">

                            @error('password')

                                <small class="text-danger">

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Confirm Password

                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Confirm password">

                        </div>

                        <div class="form-check mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="terms">

                            <label
                                class="form-check-label"
                                for="terms">

                                I agree to the Terms & Conditions

                            </label>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-2">

                            Create Account

                        </button>

                        <div class="text-center mt-4">

                            Already have an account?

                            <a href="{{ route('customer.login') }}">

                                Login

                            </a>

                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection