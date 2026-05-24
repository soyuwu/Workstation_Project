<?php

namespace App\Providers;

use App\Service\ThongBao;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- THÊM DÒNG NÀY ĐỂ FIX LỖI

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind('thongBaoDauTien', function () {
            return new ThongBao();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
