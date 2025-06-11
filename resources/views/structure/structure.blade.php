<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('structure.header')

<main class="container py-4">
    @yield('content')
</main>

@include('structure.footer')
</body>
</html>
