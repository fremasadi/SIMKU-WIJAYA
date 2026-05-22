<?php

namespace App\Providers;

use App\Models\BahanBaku;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.navbar', function ($view) {
            $batasStokMenipis = BahanBaku::BATAS_STOK_MENIPIS;
            $bahanBakuMenipis = collect();
            $jumlahBahanBakuMenipis = 0;

            if (auth()->check() && auth()->user()->role !== 'kasir') {
                $query = BahanBaku::stokMenipis()
                    ->orderBy('stok')
                    ->orderBy('nama_bahan');

                $jumlahBahanBakuMenipis = (clone $query)->count();
                $bahanBakuMenipis = $query->limit(5)->get();
            }

            $view->with(compact(
                'batasStokMenipis',
                'bahanBakuMenipis',
                'jumlahBahanBakuMenipis'
            ));
        });
    }
}
