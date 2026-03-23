<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workstation - @yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('css/topBar.css') }}">
</head>
<body>
    @include('layouts.notifications')
    <header class="header-top">
        <div class="container">
            <h1 class="project-title">WORKSTATION</h1>
        </div>
        <div class="SignInLogInBar">
            @if(Session::has('user_id'))
                <span style="color: #6b7280; margin-right: 15px;">Chào, User ID: {{ Session::get('user_id') }}</span>
                <span style="color: #6b7280; margin-right: 15px;">Role: {{ Session::get('user_role') }}</span>
                <form action="{{route('logOut') }}" method="GET" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: red; cursor: pointer;">LogOut</button>
                </form>
            @else
                <a id ="SignIn" href="{{route('register')}}">Sign In</a>
                <a id ="LogIn" href="{{route('logIn')   }}">Log In</a>    
            @endif      
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