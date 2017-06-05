<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use App\Contracts\DosenRepository;
use App\Contracts\MahasiswaRepository;
use App\Contracts\JurusanRepository;

/**
 * Class Kuliah Facade
 *
 * Logic tidak berada disini, lebih baik logic
 * tetap di `Class` masing-masing agar maintenis lebih mudah dilakukan.
 *
 * Perlakukan `Class` ini seperti `Route` di AngularJS atau Laravel.
 * Maksudnya disini adalah seluruh keperluan penting `Model` dibuat shortcut kode
 * pada `Class` ini
 *
 * @see : http://docs.odenktools/laravelcleancode
 *
 * @package App
 * @version    1.0.0
 * @author     Pribumi Technology
 * @license    MIT
 * @copyright  (c) 2015 - 2016, Pribumi Technology
 * @link       http://pribumitech.com
 */
class Kuliah
{
    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Dosen Repository
     *
     * @var \App\Contracts\DosenRepository
     */
    public $dosenRepository;
	
    /**
     * Domain Repository
     *
     * @var \App\Contracts\MahasiswaRepository
     */
    public $mahasiswaRepository;
	
    /**
     * Jurusan Repository
     *
     * @var \App\Contracts\JurusanRepository
     */
    public $jurusanRepository;

    /**
     * BeyondAuth constructor.
     *
     * Dependency Injection(DI), Harus Selalu pergunakan interface (dari \App\Contracts)
     *
     * @param $app Laravel application
     * @param DosenRepository $dosenRepository Dosen repository
	 * @param MahasiswaRepository $mahasiswaRepository Mahasiswa repository
     */
    public function __construct(
        $app,
        DosenRepository $dosenRepository,
		MahasiswaRepository $mahasiswaRepository,
		JurusanRepository $jurusanRepository
    ) {
        $this->app = $app;
        $this->dosenRepository = $dosenRepository;
		$this->mahasiswaRepository = $mahasiswaRepository;
		$this->jurusanRepository = $jurusanRepository;
    }

    /**
     * Calling `Dosen Repository` From This Class
     *
     * @see \App\Providers\AppServiceProvider::registerCustomUser()
     * @return \App\Contracts\DosenRepository
     */
    public function getDosens()
    {
        return $this->app['kuliah.dosen'];
    }

    /**
     * Calling `Dosen Repository` From This Class
     *
     * @see \App\Providers\AppServiceProvider::registerCustomUser()
     * @return \App\Contracts\DosenRepository
     */
    public function getDosen()
    {
        return $this->dosenRepository;
    }
	
    /**
     * Calling `Mahasiswa Repository` From This Class
     *
     * @see \App\Providers\AppServiceProvider::registerCustomUser()
     * @return \App\Contracts\MahasiswaRepository
     */
    public function getMahasiswa()
    {
        return $this->mahasiswaRepository;
    }
	
    /**
     * `create()` For Mahasiswa Model
     *
     * @param array $data
     * @return \App\Repositories\EloquentDosenRepository
     */
    public function createMahasiswa($data)
    {
        if (!is_null($this->getMahasiswa())) {
            return $this->getMahasiswa()->create($data);
        } else {
            return new MethodNotExist();
        }
    }
	
    /**
     * Calling `Jurusan Repository` From This Class
     *
     * @see \App\Providers\AppServiceProvider::registerCustomUser()
     * @return \App\Contracts\JurusanRepository
     */
    public function getJurusan()
    {
        return $this->jurusanRepository;
    }
	
    /**
     * `create()` For Dosen Model
     *
     * @param array $data
     * @return \App\Repositories\EloquentDosenRepository
     */
    public function createDosen($data)
    {
        if (!is_null($this->getDosen())) {
            return $this->getDosen()->create($data);
        } else {
            return new MethodNotExist();
        }
    }
}