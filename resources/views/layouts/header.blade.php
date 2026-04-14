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