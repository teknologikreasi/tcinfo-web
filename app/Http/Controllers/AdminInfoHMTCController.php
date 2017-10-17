<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Infohmtc;
use App\Infohmtctag;
use Session;

class AdminInfoHMTCController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index($tag = null)
    {
        $pageTitle = "Info HMTC";
        if ($tag == null) {
            $posts = Infohmtc::all();
        } else {
            $posts = Infohmtctag::find($tag)->post;
        }
        $tags = Infohmtctag::all();

        return view('admin.infohmtc', compact('pageTitle', 'posts', 'tags'));

        // $post = Infohmtctag::find(1)->post;

        // return $post;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'caption' => 'required',
            'foto' => 'image',
        ]);

        $post = new Infohmtc;
        $post->caption = $request->caption;
        $post->added_by = Auth::user()->id;
        $post->status = "1";

        if ($request->file('foto') != "") {
            $gambar = $request->file('foto');
            $ext = $gambar->getClientOriginalExtension();
            if ($ext == "jpg" || $ext == "png") {
                $ext = "jpg";
                $gambar_name = date('YmdHis').".$ext";
                $post->foto = $gambar_name;
                $post->save();
                $gambar->move('images/', $gambar_name);

                Session::flash('success', 'Info telah disimpan!');
            } else {
                Session::flash('failed', 'Gambar harus dalam ekstensi jpg atau png');
            }
        } else {
            $post->foto = "";
            $post->save();

            Session::flash('success', 'Info telah disimpan!');
        }

        return redirect(route('admininfohmtc'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'caption' => 'required',
            'foto' => 'image',
        ]);

        $post = Infohmtc::find($id);
        $post->caption = $request->caption;
        
        if ($request->file('foto') != "") {
            $gambar = $request->file('foto');
            $ext = $gambar->getClientOriginalExtension();
            if ($ext == "jpg" || $ext == "png") {
                $ext = "jpg";
                if ($post->foto != "") {
                    $filepath = public_path().'/images/'.$post->foto;
                    unlink($filepath);
                }
                $gambar_name = date('YmdHis').".$ext";
                $post->foto = $gambar_name;
                $post->update();
                $gambar->move('images/', $gambar_name);

                Session::flash('success', 'Info telah diupdate!');
            } else {
                Session::flash('failed', 'Gambar harus dalam ekstensi jpg atau png');
            }
        } else {
            $post->update();

            Session::flash('success', 'Info telah diupdate!');            
        }

        return redirect(route('admininfohmtc'));
    }

    public function foto($id)
    {
        $gambar = Infohmtc::find($id);

        return view('admin.foto', compact('gambar'));
    }

    public function stop($id)
    {
        $post = Infohmtc::find($id);
        if ($post->status == "1") {
            $post->status = "0";
            $post->update();
        }

        return redirect(route('admininfohmtc'));
    }

    public function start($id)
    {
        $post = Infohmtc::find($id);
        if ($post->status == "0") {
            $post->status = "1";
            $post->update();
        }

        return redirect(route('admininfohmtc'));
    }

    public function destroy($id)
    {
        $post = Infohmtc::find($id);
        if ($post->foto != "") {
            $filepath = public_path().'/images/'.$post->foto;
            unlink($filepath);
        }
        $post->delete();
        Session::flash('success', 'Info telah dihapus!');

        return redirect(route('admininfohmtc'));
    }

    public function addTag($post_id, $tag_id)
    {
        $post = Infohmtc::find($post_id);

        if ($post->tag()->where('infohmtctag_id', $tag_id)->exists())
        {
            $post->tag()->detach($tag_id);

            Session::flash('success', 'Tag telah di-detach!');
        } else {
            $post->tag()->attach($tag_id);

            Session::flash('success', 'Tag telah di-attach!');
        }

        return redirect(route('admininfohmtc'));
    }
}
