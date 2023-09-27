@extends('auth.layout')

@section('title','Registeration Page')

@section('content')

<body>
    <link rel="stylesheet" href={{ asset('css/register.css') }}>
    <div class="container">
        <div class="registration form">

            <form action="{{ route('registerUser') }}" method="POST" class="ms-auto me-auto mt-auto">
                @csrf
                <header class="ms-auto me-auto mt-auto">Signup</header>
                @error('username')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input type="text" placeholder="Enter your Username" name="username"
                    class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" value="{{ old('username') }}">
                @error('firstname')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input type="text" placeholder="Enter your first name" name="firstname"
                    class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}"
                    value="{{ old('firstname') }}">
                @error('lastname')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input type="text" placeholder="Enter your second name" name="lastname"
                    class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}"
                    value="{{ old('lastname') }}">
                @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input type="email" placeholder="Enter your email" name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input type="password" placeholder="Create a password" name="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    value="{{ old('password') }}">
                @error('confirmPassword')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input type="password" placeholder="Confirm your password" name="confirmPassword"
                    class="form-control {{ $errors->has('confirmPassword') ? 'is-invalid' : '' }}"
                    value="{{ old('confirmPassword') }}">
                <input type="submit" class="button" value="Signup">
            </form>
            <div class="signup">
                <span class="signup">Already have an account?
                    <a href="{{ route('login') }}">Login</a>
                </span>
            </div>
        </div>

    </div>
</body>
@endsection