<div class="forget-password">
    <h2>Đặt lại password</h2>
    @if(Session::has('reset_token'))
    <form action="{{route('')}}" method="POST">
        @csrf
        <div>
            <div>
                <input type="password" name="password" placeholder="Mật khẩu">
            </div>
            <div>
                <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu">
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        <button type="submit">Đổi mật khẩu</button>
    </form>
    @else
    <form action="{{url('/forget-password')}}" method="post">
        <div>
            <input type="email" name="email" placeholder="Nhập email đăng ký để reset mật khẩu">
        </div>
        <div>
            <button type="submit">Xác nhận</button>
        </div>
    </form>
    @endif
</div>