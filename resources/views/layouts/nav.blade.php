  <nav class="ws-nav fixed top-0 w-full z-50 bg-transparent transition-all duration-500">
    <div class="flex justify-between items-center px-8 lg:px-12 py-5 w-full max-w-1440px mx-auto">
      <a href="/" class="project-title">WORKSTATION</a>
      <div class="hidden md:flex gap-10 items-center font-headline font-medium text-sm">
        <a class="ws-nav-link relative pb-1 text-white hover:text-blue-200 transition-colors nav-link after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:bg-white after:rounded-full after:transition-all after:duration-300 after:w-0 hover:after:w-full" href="/">Trang chủ</a>
        <a class="ws-nav-link relative pb-1 text-white hover:text-blue-200 transition-colors nav-link after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:bg-white after:rounded-full after:transition-all after:duration-300 after:w-0 hover:after:w-full" href="#about">Giới thiệu</a>
        <div class="relative group">
          <a href="#services" class="ws-nav-link relative flex items-center gap-1 text-white hover:text-blue-200 transition-colors py-2 nav-link after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:bg-white after:rounded-full after:transition-all after:duration-300 after:w-0 hover:after:w-full">
            Dịch vụ
            <span class="material-symbols-outlined text-[18px]">expand_more</span>
          </a>
          <div class="dropdown-menu absolute top-full left-0 w-52 bg-white/95 backdrop-blur-lg shadow-ambient py-2 rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
            <a class="block px-5 py-2.5 text-sm text-slate-700 hover:bg-surface-container-low hover:text-primary transition-colors" href="{{ route('khongGian') }}">Không gian làm việc</a>
            <a class="block px-5 py-2.5 text-sm text-slate-700 hover:bg-surface-container-low hover:text-primary transition-colors" href="{{ route('dichVu') }}">Danh mục dịch vụ</a>
          </div>
        </div>
        <a class="ws-nav-link relative pb-1 text-white hover:text-blue-200 transition-colors nav-link after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:bg-white after:rounded-full after:transition-all after:duration-300 after:w-0 hover:after:w-full" href="#reviews">Đánh giá</a>
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
        <a class="hidden md:inline-block font-headline font-medium tracking-tight text-sm text-white/80 hover:text-white transition-all active:scale-95 duration-200" href="{{route('logIn')}}">Đăng nhập</a>
        <a class="hidden md:inline-block font-headline font-medium tracking-tight text-sm text-white/80 hover:text-white transition-all active:scale-95 duration-200" href="{{route('register')}}">Đăng ký</a>
        @endif
        <a class="hidden md:inline-block bg-white text-primary px-6 py-2.5 font-headline text-sm font-semibold hover:bg-slate-100 transition-all active:scale-95 rounded-lg shadow-lg" href="#">Đặt chỗ ngay</a>
        
        <button id="menu-toggle" class="md:hidden hover:cursor-pointer text-white" onclick="document.getElementById('menu').classList.toggle('hidden')">
          <span class="material-symbols-outlined text-2xl">menu</span>
        </button>

        <aside id="menu" class="hidden md:hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-xl shadow-lg border-t border-slate-100">
          <nav class="flex flex-col p-4 space-y-1">
            <a href="/" class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Trang chủ</a>
            <a href="#about" class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Giới thiệu</a>
            <a href="#services" class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Dịch vụ</a>
            <a href="#reviews" class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Đánh giá</a>
            
            <hr class="my-2 border-slate-200">
    
            <a href="{{route('logIn')}}" class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors">Đăng nhập</a>
            <a href="{{route('register')}}" class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors">Đăng ký</a>
            <a href="#" class="block mt-2 px-4 py-3 bg-primary text-white text-center font-semibold rounded-lg hover:opacity-90 transition-opacity">Đặt chỗ ngay</a>
          </nav>
        </aside>
      </div>
    </div>
  </nav>