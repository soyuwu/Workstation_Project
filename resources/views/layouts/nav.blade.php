  <nav class="ws-nav fixed top-0 w-full z-50 bg-transparent backdrop-blur-xl transition-colors duration-500">
    <div class="flex justify-between items-center px-8 lg:px-12 py-5 w-full max-w-[1440px] mx-auto">
      <a href="" class="project-title">WORKSTATION</a>
      <div class="hidden md:flex gap-10 items-center font-headline font-medium text-sm">
        <a class="ws-nav-link relative text-slate-600 hover:text-primary transition-colors nav-link" href="">Trang chủ</a>
        <a class="ws-nav-link relative text-slate-600 hover:text-primary transition-colors nav-link" href="">Giới thiệu</a>
        <div class="relative group">
          <button class="flex items-center gap-1 text-slate-600 hover:text-primary transition-colors py-2 ">
            Dịch vụ
            <span class="material-symbols-outlined text-[18px]">expand_more</span>
          </button>
          <div class="dropdown-menu absolute top-full left-0 w-52 bg-white/95 backdrop-blur-lg shadow-ambient py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
            <a class="block px-5 py-2.5 text-sm text-slate-700 hover:bg-surface-container-low hover:text-primary transition-colors" href="">Không gian làm việc</a>
            <a class="block px-5 py-2.5 text-sm text-slate-700 hover:bg-surface-container-low hover:text-primary transition-colors" href="">Danh mục dịch vụ</a>
          </div>
        </div>
        <a class="ws-nav-link relative text-slate-600 hover:text-primary transition-colors nav-link" href="">Đánh giá</a>
      </div>
      <div class="flex items-center gap-6">
        @if(Session::has('user_id'))
                <span style="color: #6b7280; margin-right: 15px;">Chào, User ID: {{ Session::get('user_id') }}</span>
                <span style="color: #6b7280; margin-right: 15px;">Role: {{ Session::get('user_role') }}</span>
                <form action="{{route('logOut') }}" method="GET" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: red; cursor: pointer;">LogOut</button>
                </form>
        @else
        <a class="hidden md:inline-block font-headline font-medium tracking-tight text-sm text-slate-600 hover:text-slate-900 transition-all active:scale-95 duration-200" href="{{route('logIn')}}">Đăng nhập</a>
        <a class="hidden md:inline-block font-headline font-medium tracking-tight text-sm text-slate-600 hover:text-slate-900 transition-all active:scale-95 duration-200" href="{{route('register')}}">Đăng ký</a>
        @endif
        <a class="hidden md:inline-block bg-primary text-white px-6 py-2.5 font-headline text-sm font-semibold hover:opacity-90 transition-all active:scale-95 rounded-sm" href="spaces.html">Đặt chỗ ngay</a>
        <button id="menu-toggle" class="md:hidden">
          <span class="material-symbols-outlined text-2xl">menu</span>
        </button>
      </div>
    </div>
  </nav>