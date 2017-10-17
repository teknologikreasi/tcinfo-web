<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Lomba;
use Session;

class AdminInfoLombaController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = "Info Lomba";
        $pageDesc = "Ini halaman info lomba";
        $lombas = DB::table('lombas')->orderBy('deadline','desc')->get();
        
        return view('admin.infolomba', compact('pageTitle', 'pageDesc', 'lombas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_lomba' => 'required',
            'deskripsi' => 'required',
            'web_lomba' => 'required',
            'deadline' => 'date',
            'mulai_lomba' => 'date',
            'selesai_lomba' => 'date',
            'foto' => 'required|image',
            ]);

        $foto = $request->file('foto');
        $ext = $foto->getClientOriginalExtension();
        if ($ext == "jpg" || $ext == "png") {
            $ext = "jpg";
            $foto_name = date('YmdHis').".$ext";
            $lomba = new Lomba();
            $lomba->judul = $request->nama_lomba;
            $lomba->deskripsi = $request->deskripsi;
            $lomba->web = $request->web_lomba;
            $deadline = date("Y-m-d", strtotime($request->deadline));
            $mulai = date("Y-m-d", strtotime($request->mulai_lomba));
            $selesai = date("Y-m-d", strtotime($request->selesai_lomba));
            $lomba->deadline = $deadline;
            $lomba->mulai_lomba = $mulai;
            $lomba->selesai_lomba = $selesai;
            $lomba->foto = $foto_name;
            $lomba->status = '1';
            $lomba->added_by = Auth::user()->id;
            $lomba->save();
            $foto->move('images/', $foto_name);
            Session::flash('success', 'Info telah disimpan!');

            return redirect(route('lomba.index'));
        } else {
            Session::flash('failed', 'Gambar harus dalam ekstensi jpg atau png');
            return redirect(route('lomba.index'));
        }
    }

    public function stop($id)
    {
        $lomba = Lomba::find($id);
        if ($lomba->status == "1") {
            $lomba->status = "0";
            $lomba->save();
        }

        return redirect(route('lomba.index'));
    }

    public function start($id)
    {
        $lomba = Lomba::find($id);
        if ($lomba->status == "0") {
            $lomba->status = "1";
            $lomba->save();
        }

        return redirect(route('lomba.index'));
    }

    public function foto($id)
    {
        $gambar = Lomba::find($id);

        return view('admin.foto', compact('gambar'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_lomba' => 'required',
            'deskripsi' => 'required',
            'web_lomba' => 'required',
            'deadline' => 'required',
            'mulai_lomba' => 'required',
            'selesai_lomba' => 'required',
            'foto' => 'image',
            ]);

        $lomba = Lomba::find($id);
        if ($request->file('foto') != "") {
            $foto = $request->file('foto');
            $ext = $foto->getClientOriginalExtension();
            if ($ext == "jpg" || $ext == "png") {
                $ext = "jpg";
                if ($lomba->foto != "") {
                    $filepath = public_path().'/images/'.$lomba->foto;
                    unlink($filepath);
                }
                $foto_name = date('YmdHis').".$ext";
                $lomba->judul = $request->nama_lomba;
                $lomba->deskripsi = $request->deskripsi;
                $lomba->web = $request->web_lomba;
                $deadline = date("Y-m-d", strtotime($request->deadline));
                $mulai = date("Y-m-d", strtotime($request->mulai_lomba));
                $selesai = date("Y-m-d", strtotime($request->selesai_lomba));
                $lomba->deadline = $deadline;
                $lomba->mulai_lomba = $mulai;
                $lomba->selesai_lomba = $selesai;
                $lomba->foto = $foto_name;
                $lomba->update();
                $foto->move('images/', $foto_name);
                Session::flash('success', 'Info telah disimpan!');

                return redirect(route('lomba.index'));
            } else {
                Session::flash('failed', 'Gambar harus dalam ekstensi jpg dan png');
                return redirect(route('lomba.index'));
            }
        } else {
            $lomba->judul = $request->nama_lomba;
            $lomba->deskripsi = $request->deskripsi;
            $lomba->web = $request->web_lomba;
            $deadline = date("Y-m-d", strtotime($request->deadline));
            $mulai = date("Y-m-d", strtotime($request->mulai_lomba));
            $selesai = date("Y-m-d", strtotime($request->selesai_lomba));
            $lomba->deadline = $deadline;
            $lomba->mulai_lomba = $mulai;
            $lomba->selesai_lomba = $selesai;
            $lomba->update();
            Session::flash('success', 'Info telah diperbarui!');

            return redirect(route('lomba.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lomba = Lomba::find($id);
        $filepath = public_path().'/images/'.$lomba->foto;
        unlink($filepath);
        $lomba->delete();
        Session::flash('success', 'Info telah dihapus!');

        return redirect(route('lomba.index'));
    }
}
