<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Config;
use Validator;
use Response;
use App\Models\Mahasiswa;
use App\Repositories\EloquentMahasiswaRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use URL;
use Kuliah;
use DB;

class MahasiswaController extends Controller
{
	/**
     * The mahasiswa repository implementation.
     *
     * @var EloquentMahasiswaRepository
     */
    protected $mahasiswa;

	/**
	 * [Implementasi Depedency Injection]
     * Create a new controller instance.
     *
     * @param  EloquentMahasiswaRepository  $mahasiswa
     * @return void
     */
    public function __construct(EloquentMahasiswaRepository $mahasiswa)
    {
        $this->mahasiswa = $mahasiswa;
    }

    public function postInsert(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' 			=> 'required|max:50|unique:mahasiswa,nama',
			'tempat_lahir' 	=> 'required',
			'tanggal_lahir' => 'required|date',
			'jenis_kelamin' => 'required|in:pria,wanita',
			'alamat' 		=> 'required',
			'jurusan_kode' 	=> 'required'
        ]);
		
		if ($validate->fails()) {
            return Response::json([
                'mahasiswa' => array(
                    'status' => array('code' => 422, 'message' => 'error'),
                    'results' => array('field_errors' => $validate->errors())
                )
            ], 200);
        }

		try {
			
            $nama = $request->input('nama');
            $tempat_lahir = $request->input('tempat_lahir');
            $tanggal_lahir = $request->input('tanggal_lahir');
            $jenis_kelamin = $request->input('jenis_kelamin');
            $alamat = $request->input('alamat');
			$jurusan_kode = $request->input('jurusan_kode');

			$data = array(
				'nama' 			=> $nama,
				'jurusan_kode'	=> $jurusan_kode,
				'tempat_lahir' 	=> $tempat_lahir,
				'tanggal_lahir' => date('Y-m-d', strtotime($tanggal_lahir)),
				'jenis_kelamin' => $jenis_kelamin,
				'alamat' 		=> $alamat,
				'deleted_at'	=> null,
			);
			
			$insert = Kuliah::createMahasiswa($data);
			
		} catch (\Exception $e) {
			
            return Response::json([
				'cities' => array(
					'status' => array('code' => 500, 'message' => $e->getMessage()),
                    'data' => array())
				], 200);
		}
        
		return Response::json([
			'mahasiswa' => array(
				'status' => array('code' => 200, 'message' => 'Success'),
				'results' => array()
				)
		], 200);		
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $search_term = $request->input('nama');
		$nama_jurusan = $request->input('jurusan');
		$root = $request->input('root');
        $limit = $request->input('limit') ? $request->input('limit') : 10;
        $sort = $request->input('sort') ? $request->input('sort') : 'mahasiswa.created_at';
        $order = $request->input('order') ? $request->input('order') : 'DESC';
        $conditions = '1 = 1';
		
        if (!empty($nama_jurusan)) {
            $conditions .= " AND jurusan.nama_jurusan LIKE '%" . $nama_jurusan . "%'";
        }
		
        if (!empty($search_term)) {
            $conditions .= " AND nama LIKE '%" . $search_term . "%'";
        }

		try {
			
			$data = $this->mahasiswa->select('mahasiswa.*')
				->join('jurusan', 'mahasiswa.jurusan_kode', '=', 'jurusan.kode_jurusan')
				->whereRaw($conditions)
				->limit($limit)
				->orderBy($sort, $order);

			$mahasiswa = array();
			
			$paging = array();
			
			if (!empty($data)) {
				$infoPage = $data->paginate($limit)->toArray();
				$paging['total'] = $infoPage['total'];
				$paging['per_page'] = $infoPage['per_page'];
				$paging['current_page'] = $infoPage['current_page'];
				$paging['last_page'] = $infoPage['last_page'];
				$paging['next_page_url'] = $infoPage['next_page_url'];
				$paging['prev_page_url'] = $infoPage['prev_page_url'];
				$paging['from'] = $infoPage['from'];
				$paging['to'] = $infoPage['to'];
				foreach ($data->get() as $idx => $dt) {
					$img_random_pria = rand(6,10);
					$img_random_wanita = rand(1,5);
					$mahasiswa[$idx] = $dt;
					if($mahasiswa[$idx]['jenis_kelamin'] === 'wanita'){
						$mahasiswa[$idx]['images'] = array(
									'thumb'	=> URL::asset('kuliah/img/mahasiswa/team'. $img_random_wanita . '.jpg'),
									'avatar' => URL::asset('kuliah/img/mahasiswa/avatar/team'. $img_random_wanita . '.jpg'),
								);
					}else{
						$mahasiswa[$idx]['images'] = array(
									'thumb'	=> URL::asset('kuliah/img/mahasiswa/team'. $img_random_pria . '.jpg'),
									'avatar' => URL::asset('kuliah/img/mahasiswa/avatar/team'. $img_random_pria . '.jpg'),
								);
					}
					$mahasiswa[$idx]['jurusan'] = $dt->jurusan;
				}
			}

			if (!empty($root) && $root=="true") {
				return Response::json([
					'mahasiswa' => array(
						'status' => array('code' => 200, 'message' => 'Success'),
						'pageinfo' => $paging,
						'results' => $mahasiswa,
						)
					], 200);
			}else if (!empty($root) && $root=="false") {
				return Response::json([
					'status' => array('code' => 200, 'message' => 'Success'),
					'pageinfo' => $paging,
					'results' => $mahasiswa
				], 200);
			}else{
				return Response::json([
					'status' => array('code' => 200, 'message' => 'Success'),
					'pageinfo' => $paging,
					'results' => $mahasiswa
				], 200);
			}
		} catch (\Exception $e) {
			return Response::json([
				'status' => array('code' => 500, 'message' => 'Failed'),
				'pageinfo' => $paging,
				'results' => $mahasiswa
			], 500);
		}
    }
}
