@extends('adminlte::page')

@section('title', 'Sửa người dùng')

@section('content_header')
    <h1>Sửa người dùng</h1>
@endsection

@section('content')
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf @method('PUT')

        <x-adminlte-input name="name" label="Tên" value="{{ old('name', $user->name) }}" required />
        <x-adminlte-input name="email" label="Email" type="email" value="{{ old('email', $user->email) }}" required />
        <x-adminlte-input name="password" label="Mật khẩu mới" type="password" />
        <x-adminlte-input name="password_confirmation" label="Xác nhận mật khẩu mới" type="password" />
        <x-adminlte-select name="role" label="Vai trò" required>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="host" {{ old('role', $user->role) == 'host' ? 'selected' : '' }}>Host</option>
            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
        </x-adminlte-select>


        <x-adminlte-button type="submit" theme="primary" label="Cập nhật" />
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">Hủy</a>
    </form>
@endsection
