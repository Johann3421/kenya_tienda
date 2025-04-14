<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barras;
use Auth;
use DB;

class BarrasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.barras.index');
    }
}
