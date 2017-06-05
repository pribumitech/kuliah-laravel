<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Stoplite;
use App\Models\MemberCustomFields;

class MainController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('kostauth.admin');
        //$this->middleware('sentry.member:Admins');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        return \View::make('welcome');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \View::make('welcome');
    }
}
