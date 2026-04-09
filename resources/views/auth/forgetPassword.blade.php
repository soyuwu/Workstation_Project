<div class="forget-password">
    <h2>Đặt lại password</h2>

    <form action="{{ url('') }}" method="POST">
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
</div>