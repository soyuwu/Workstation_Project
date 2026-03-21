<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workstation - @yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>

    <header class="header-top">
        <div class="container">
            <h1 class="project-title">WORKSTATION</h1>
        </div>
        <div class="SignInLogInBar">
            <a id ="SignIn" href="{{route('register')}}">Sign In</a>
            <a id ="LogIn" href="">Log In</a>
        </div>
    </header>

    <nav class="nav-bar">
        <div class="container">
            <ul class="nav-list">
                <li class="nav-item"><a href="/seats">GIỚI THIỆU</a></li>
                <li class="nav-item"><a href="/seats">BẢN ĐỒ</a></li>
                <li class="nav-item"><a href="/single">SINGLE</a></li>
                <li class="nav-item"><a href="/group">GROUP</a></li>
                <li class="nav-item"><a href="/services">Dịch vụ khác</a></li>
            </ul>
        </div>
    </nav>

    <main class="container content-area">
        @yield('content')
    </main>

</body>
</html>