<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\EloquentDosenRepository;
use App\Models\Dosen;

use App\Repositories\EloquentMahasiswaRepository;
use App\Models\Mahasiswa;

use App\Repositories\EloquentJurusanRepository;
use App\Models\Jurusan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
	
    /**
     * @return array
     */
    public function provides()
    {
        return [
            'kuliah',
            'kuliah.dosen',
			'kuliah.mahasiswa',
            'kuliah.facade',
        ];
    }
	
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		$this->app->bind('App\Contracts\Dosen', 'App\Models\Dosen');
		
        /**
         * @see \App\Providers\AppServiceProvider::registerFacades
         */
        $this->app->singleton('kuliah.dosen', function ($app) {
            return new EloquentDosenRepository($app, new Dosen());
        });
		
        /**
         * @see \App\Providers\AppServiceProvider::registerFacades
         */
        $this->app->singleton('kuliah.mahasiswa', function ($app) {
            return new EloquentMahasiswaRepository($app, new Mahasiswa());
        });

        /**
         * @see \App\Providers\AppServiceProvider::registerFacades
         */
        $this->app->singleton('kuliah.jurusan', function ($app) {
            return new EloquentJurusanRepository($app, new Jurusan());
        });
		
		$this->registerFacades();
    }

    /**
     * Register Core BeyondAuth
     * Ini agar mempermudah programmer untuk menggunakannya
     * dikarenakan Method Seluruhnya berupa `Singleton`.
     *
     * @see \Pribumi\BeyondAuth\BeyondAuth::__construct
     *
     * @return \Pribumi\BeyondAuth\BeyondAuth
     */
    protected function registerFacades()
    {
        $this->app->singleton('kuliah.facade', function ($app) {
            return new \App\Kuliah($app,
                $app['kuliah.dosen'],
				$app['kuliah.mahasiswa'],
				$app['kuliah.jurusan']);
        });
    }
}
