<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Config;
use Validator;
use Response;
use App\Models\Jurusan;
use App\Http\Requests;
use Redis;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Repositories\EloquentJurusanRepository;

class JurusanController extends Controller
{
	/**
     * The jurusan repository implementation.
     *
     * @var EloquentJurusanRepository
     */
    protected $jurusan;

	/**
	 * [Implementasi Depedency Injection]
     * Create a new controller instance.
     *
     * @param  EloquentJurusanRepository  $jurusan
     * @return void
     */
    public function __construct(EloquentJurusanRepository $jurusan)
    {
        $this->jurusan = $jurusan;
    }

    /**
     * Listing data jurusan
	 * dan gunakan Cache Redis
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
		$id='1';
		
		$search_term = $request->input('nama_jurusan') ? $request->input('nama_jurusan') : '';
		$limit = $request->input('limit') ? $request->input('limit') : 10;
		$sort = $request->input('sort') ? $request->input('sort') : 'jurusan.created_at';
		$order = $request->input('order') ? $request->input('order') : 'DESC';
		$page = $request->input('page') ? $request->input('page') : 1;
		
		$conditions = '1 = 1';

		$user = Cache::get('jurusan:search:'.$search_term.':limit:'.$limit.':sort:'.$sort.':order:'.$order.':page:'.$page,0);
		$paging = array();
		
		if($user){

			$cache = $user;
			
			return Response::json($cache, 200);
				
		}else{

			if (!empty($search_term)) {
				$conditions .= " AND nama_jurusan LIKE '%" . $search_term . "%'";
			}
			try {
				
				$data = $this->jurusan->select('*')
					->whereRaw($conditions)
					->limit($limit)
					->orderBy($sort, $order);

				/*$data = \Kuliah::getJurusan()->select('*')
					->whereRaw($conditions)
					->orderBy($sort, $order)
					->limit($limit);*/
				
				if (!empty($data)) {

					$cacheData = $data->paginate($limit)->toArray();
					
					$paging['total'] = $cacheData['total'];
					$paging['per_page'] = $cacheData['per_page'];
					$paging['current_page'] = $cacheData['current_page'];
					$paging['last_page'] = $cacheData['last_page'];
					$paging['next_page_url'] = $cacheData['next_page_url'];
					$paging['prev_page_url'] = $cacheData['prev_page_url'];
					$paging['from'] = $cacheData['from'];
					$paging['to'] = $cacheData['to'];
					
					$cache = array(
						'jurusan' => array(
							'status' => array('code' => 200, 'message' => 'Success'),
							'pageinfo' =>$paging,
							'results' => $data->get()
						));

					Cache::store('redis')->put('jurusan:search:'.$search_term.':limit:'.$limit.':sort:'.$sort.':order:'.$order.':page:'.$page, $cache, 10);
			
					return Response::json([
						'jurusan' => array(
							'status' => array('code' => 200, 'message' => 'Success'),
							'pageinfo' => $paging,
							'results' => $data->get()
						)
					], 200);
					
				}else{

					$paging['total'] = 0;
					$paging['per_page'] = 0;
					$paging['current_page'] = 0;
					$paging['last_page'] = null;
					$paging['next_page_url'] = null;
					$paging['prev_page_url'] = null;
					$paging['from'] = 0;
					$paging['to'] = 0;
					
					return Response::json([
						'jurusan' => array(
							'status' => array('code' => 200, 'message' => 'Success'),
							'pageinfo' => $paging,
							'results' => array()
						)
					], 200);						
				}
				
			} catch (\Exception $e) {
				return Response::json([
					'jurusan' => array(
						'status' => array('code' => 500, 'message' => $e->getMessage()),
						'metadata' => array()
					)
				], 500);
			}
		}
    }
}
