@extends('adminlte::page')

@section('title', 'Danh sách người dùng')

@section('content_header')
    <h1>Người dùng</h1>
@endsection

@section('content')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">+ Thêm người dùng</a>

    @if (session('success'))
        <x-adminlte-alert theme="success" style="display: flex">{{ session('success') }}</x-adminlte-alert>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        @if ($user->trashed())
                            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}"
                                style="display:inline-block;">
                                @csrf
                                <button class="btn btn-success btn-sm">Khôi phục</button>
                            </form>
                        @else
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xoá người dùng này?')">Xoá</button>
                            </form>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links('pagination::bootstrap-4') }}
@endsection
