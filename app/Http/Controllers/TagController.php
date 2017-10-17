<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Infohmtctag;
use Session;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'tagName' => 'required',
    	]);

    	$tag = new Infohmtctag;
    	$tag->name = $request->tagName;
    	$tag->save();

    	return redirect(route('admininfohmtc'));
    }

    public function destroy($id)
    {
        Infohmtctag::destroy($id);
        Session::flash('success', 'Tag telah dihapus!');

        return redirect(route('admininfohmtc'));
    }
}
