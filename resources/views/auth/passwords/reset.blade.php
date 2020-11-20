@extends('layouts.auth')

@section('content')

    <div class="page-content--bge5">
        <div class="container">
            <div class="login-wrap">
                <div class="login-content">
                    <div class="login-logo">
                        <strong><h1>Aristotle</h1></strong>
                    </div>
                    <div class="login-form">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input class="au-input au-input--full @error('email') is-invalid @enderror" type="email"
                                       name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                                       autofocus
                                       placeholder="Email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input class="au-input au-input--full @error('password') is-invalid @enderror"
                                       type="password"
                                       name="password" required autocomplete="new-password"
                                       placeholder="Password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input class="au-input au-input--full" type="password"
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="Confirm Password">
                            </div>

                            <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
