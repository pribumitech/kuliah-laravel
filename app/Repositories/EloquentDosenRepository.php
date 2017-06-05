<?php

namespace App\Repositories;

use Illuminate\Contracts\Foundation\Application;
use App\Contracts\DosenRepository;
use App\Models\Dosen;

/**
 * Class EloquentDosenRepository
 *
 * Ini Class Encapsulasi agar model dapat dipanggil dari luar package
 * Note : Tambahkan fungsi disini...
 *
 * 
 * Langkah Ke-4 :
 * @see \App\Providers\AppServiceProvider::registerCustomUser
 * 
 * 
 * @package App\Repositories
 * @version    1.0.0
 * @author     Pribumi Technology
 * @license    MIT
 * @copyright  (c) 2015 - 2016, Pribumi Technology
 * @link       http://pribumitech.com
 */
class EloquentDosenRepository extends AbstractEloquentRepository implements DosenRepository
{
    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \App\Models\Dosen $model
     */
    public function __construct(Application $app, Dosen $model)
    {
        parent::__construct($app, $model);
    }

    /**
     * Dynamic copy `method-method` pada `Model` yang dituju,
     * tujuannya agar class ini tidak menambahkan secara
     * terus menerus `method-method` yang terdapat pada `Model`.
     * 
     * method ini tidak perlu dipanggil dimanapun, karena otomatis
     * saat class ini terpanggil method jalan dengan sendiri-nya
     * 
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
		if (is_callable(array($this->model, $method))) {
			return call_user_func_array([$this->model, $method], $parameters);
		} else {
			return false;
		}
    }
}