<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * [MASTER]
 *
 * Class Dosen
 *
 * @package App\Models
 * @version    1.0.0
 * @author     Pribumi Technology
 * @license    MIT
 * @copyright  (c) 2015 - 2016, Pribumi Technology
 * @link       http://pribumitech.com
 */
class Dosen extends Model
{
    use SoftDeletes;

    /**
     * Nama Primary Key yang digunakan oleh table
     *
     * @var string
     */
    protected $primaryKey = 'nip';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dosen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_dosen',
        'jenis_kelamin',
        'alamat',
        'tempat_lahir',
        'tgl_lahir',
        'profile_img',
		'created_at',
		'updated_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	/**
	 * Temporary Permission Cache
	 */
	protected $to_check_cache = null;
}
