<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * [MASTER]
 *
 * Class Mahasiswa
 *
 * @package App\Models
 * @version    1.0.0
 * @author     Pribumi Technology
 * @license    MIT
 * @copyright  (c) 2015 - 2016, Pribumi Technology
 * @link       http://pribumitech.com
 */
class Mahasiswa extends Model
{
    use SoftDeletes;

    /**
     * Nama Primary Key yang digunakan oleh table
     *
     * @var string
     */
    protected $primaryKey = 'nim';
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mahasiswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jurusan_kode',
		'nama',
        'jenis_kelamin',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
		'created_at',
		'updated_at',
		'deleted_at'
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

    /**
     * Eloquent `Jurusan` model.
     *
     * @see \App\Models\Jurusan
     * @var string
     */
    protected static $jurusanModel = 'App\Models\Jurusan';
	
    /**
     * Relasi table `Jurusan`.
     *
     * <code>
     * $mahasiswa = new \App\Models\Mahasiswa();
     * $findBy = $mahasiswa->find(1);
     * echo json_encode($findBy->jurusans->all());
     *
     * atau
     * $mahasiswa = new \App\Models\Mahasiswa();
     * $findBy = $mahasiswa->with('jurusans')->get();
     * echo json_encode($findBy);
     * </code>
     *
     * @see \App\Models\Jurusan::mahasiswa
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jurusan()
    {
        /*(:nama_key : pada_table_yang_dituju, :nama_foreign_key_table_ini )*/
        return $this->hasOne(static::$jurusanModel, 'kode_jurusan', 'jurusan_kode');
    }
}
