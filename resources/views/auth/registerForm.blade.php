<div class="register-container">
    <h2>Đăng ký tài khoản Workstation</h2>

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div>
            <input type="text" name="name" placeholder="Họ và tên" value="{{ old('name') }}">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <input type="password" name="password" placeholder="Mật khẩu">
        </div>
        <div>
            <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu">
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>
        <button type="submit">Đăng ký</button>
    </form>
</div>