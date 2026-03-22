<div class="logIn-container">
    <h2>Đăng nhập</h2>
    
    <form action="{{ url('/logIn') }}" method="POST">
        @csrf 
        <div>
            <input type="email" name="email" placeholder="Email">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div>
            <input type="password" name="password" placeholder="Mật khẩu">
        </div>
        <button type="submit">Dang nhap</button>
    </form>
</div>
