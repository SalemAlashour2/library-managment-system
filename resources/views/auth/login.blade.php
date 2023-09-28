@extends('auth.layout')

@section('title','Login Page')

@section('content')

<body>
    <link rel="stylesheet" href={{ asset('css/register.css') }}>
    <div class="container">
            <div class="login form">
                <header>Login</header>
          
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <input type="email" placeholder="Enter your email" name="email">
                    <input type="password" placeholder="Enter your password" name="password">
                    <a href="#">Forgot password?</a>
                    <input type="submit" class="button" value="Login">
                </form>
                <div class="signup">
                    <span class="signup">Don't have an account?
                        <a href="{{ route('registerPage') }}">Sign up</a>
                    </span>
                </div>
            </div>
    </div>
</body>
@endsection