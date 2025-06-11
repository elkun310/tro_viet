<header>
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <a href="/" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40">
            </a>
{{--            <nav class="d-flex gap-3 align-items-center">--}}
{{--                <a href="{{ route('login') }}">Đăng nhập</a>--}}
{{--                <a href="{{ route('register') }}">Đăng ký</a>--}}
{{--                <a href="{{ route('posts.create') }}" class="btn btn-warning">Đăng tin</a>--}}
{{--            </nav>--}}
        </div>
    </div>

    <div class="search-bar bg-light py-3 border-top border-bottom">
        <div class="container">
{{--            <form action="{{ route('search') }}" method="GET" class="d-flex gap-3">--}}
{{--                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo khu vực...">--}}
{{--                <button class="btn btn-primary">Tìm</button>--}}
{{--            </form>--}}
        </div>
    </div>
</header>
