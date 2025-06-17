@extends('adminlte::page')

@section('title', 'Danh sách người dùng')

@section('content_header')
    <h1>Người dùng</h1>
@endsection

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Thêm người dùng</a>
        <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
        <div class="form-row">
            <div class="col-md-4 mb-2">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc email"
                    value="{{ request('keyword') }}">
            </div>

            <div class="col-md-3 mb-2">
                <select name="role" class="form-control">
                    <option value="">-- Tất cả vai trò --</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="host" {{ request('role') == 'host' ? 'selected' : '' }}>Host</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>

            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
            </div>
        </div>
    </form>

    @if (session('success'))
        <x-adminlte-alert theme="success" dismissable>{{ session('success') }}</x-adminlte-alert>
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
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
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

    {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
@endsection
