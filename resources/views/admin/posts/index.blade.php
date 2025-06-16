@extends('adminlte::page')

@section('content_header')
    <h1>Danh sách bài đăng</h1>
@stop

@section('content')
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary mb-2">Tạo bài đăng</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Tiêu đề</th>
                <th>Giá</th>
                <th>Diện tích</th>
                <th>Phường</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->code }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ number_format($post->price * 1000) }}đ</td>
                    <td>{{ $post->area }} m²</td>
                    <td>
                        {{ $post->ward->name ?? '' }},
                        {{ $post->ward->district->name ?? '' }},
                        {{ $post->ward->district->province->name ?? '' }}
                    </td>
                    <td>{{ $post->status }}</td>
                    <td>
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Xoá?')">Xoá</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->appends(request()->query())->links('pagination::bootstrap-4') }}
@stop
