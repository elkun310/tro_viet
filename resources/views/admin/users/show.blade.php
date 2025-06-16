@extends('adminlte::page')

@section('title', 'Chi tiết người dùng')

@section('content_header')
    <h1>Chi tiết người dùng</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $user->id }}</p>
                    <p><strong>Tên:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $user->phone }}</p>
                    <p><strong>Vai trò:</strong> {{ ucfirst($user->role) }}</p>
                    <p><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Trạng thái:</strong> {{ $user->trashed() ? 'Đã xóa' : 'Hoạt động' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
            @if (!$user->trashed())
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Sửa</a>
            @endif
        </div>
    </div>
@endsection 