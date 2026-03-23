<div class="logIn-container">
    <h2>Đăng nhập</h2>
    
    <form action="{{ url('/logIn') }}" method="POST" autocomplete="off">
        @csrf 
        <div>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <input type="password" name="password" placeholder="Mật khẩu" autocomplete="new-password">
        </div>
        @error('password') <span class="error">{{ $message }}</span> @enderror
        <button type="submit">Dang nhap</button>
    </form>
</div>
