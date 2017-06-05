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
class MataKuliah extends Model
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
     * `Dosen` model.
     *
     * @see \App\Models\Dosen
     * @var string
     */
    protected static $dosenModel = 'App\Models\Dosen';
	
    /**
     * `Jurusan` model.
     *
     * @see \App\Models\Jurusan
     * @var string
     */
    protected static $jurusanModel = 'App\Models\Jurusan';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode_mk',
        'jurusan_kode',
        'kd_dosen',
        'nama_matakuliah',
        'jumlah_sks',
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

    /**
     * Relasi table `Jurusan`.
     *
     * <code>
     * $matakuliah = new \App\Models\MataKuliah();
     * $findBy = $matakuliah->find(1);
     * echo json_encode($findBy->jurusans->all());
     *
     * atau
     * $matakuliah = new \App\Models\MataKuliah();
     * $findBy = $matakuliah->with('jurusans')->get();
     * echo json_encode($findBy);
     * </code>
     *
     * @see \App\Models\Jurusan::mahasiswa
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jurusans()
    {
        /*(:nama_key : pada_table_yang_dituju, :nama_foreign_key_table_ini )*/
        return $this->hasOne(static::$jurusanModel, 'kode_jurusan', 'jurusan_kode');
    }

    /**
     * Relasi table `Dosen`.
     *
     * <code>
     * $matakuliah = new \App\Models\MataKuliah();
     * $findBy = $matakuliah->find(1);
     * echo json_encode($findBy->dosens->all());
     *
     * atau
     * $matakuliah = new \App\Models\MataKuliah();
     * $findBy = $matakuliah->with('dosens')->get();
     * echo json_encode($findBy);
     * </code>
     *
     * @see \App\Models\Jurusan::matakuliah
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dosens()
    {
        /*(:nama_key : pada_table_yang_dituju, :nama_foreign_key_table_ini )*/
        return $this->hasOne(static::$dosenModel, 'nip', 'kd_dosen');
    }
}
