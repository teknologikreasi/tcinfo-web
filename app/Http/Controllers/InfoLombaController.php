<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lomba;

class InfoLombaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$lombas = DB::table('lombas')->orderBy('deadline','desc')->get();

    	return view('infolomba', compact('lombas'));
    }
}
