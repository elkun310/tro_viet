@extends('adminlte::page')

@section('title', 'Tạo người dùng')

@section('content_header')
    <h1>Tạo người dùng</h1>
@endsection

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <x-adminlte-input name="name" label="Tên" value="{{ old('name') }}" required />
        <x-adminlte-input name="email" label="Email" type="email" value="{{ old('email') }}" required />
        <x-adminlte-input name="password" label="Mật khẩu" type="password" required />
        <x-adminlte-input name="password_confirmation" label="Xác nhận mật khẩu" type="password" required />
        <x-adminlte-select name="role" label="Vai trò" required>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="host" {{ old('role') == 'host' ? 'selected' : '' }}>Host</option>
            <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>User</option>
        </x-adminlte-select>

        <x-adminlte-button type="submit" theme="primary" label="Tạo" />
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">Hủy</a>
    </form>
@endsection
