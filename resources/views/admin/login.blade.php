@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'Đăng nhập Admin')

@section('auth_body')
    <form action="{{ route('admin.login') }}" method="POST">
        @csrf

        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
    </form>
@endsection
