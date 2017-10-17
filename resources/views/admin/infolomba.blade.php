@extends('admin.layouts.template')

@section('content')
	@if(Session::has('failed'))
		<div class="alert alert-danger" role="alert">
			<strong>Failed: </strong>{{Session::get('failed')}}
		</div>
	@elseif(Session::has('success'))
		<div class="alert alert-success" role="alert">
			<strong>Success: </strong>{{Session::get('success')}}
		</div>
	@endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<button data-toggle="modal" data-target="#tambahLomba" class="form-control btn btn-warning"><i class="fa fa-fw fa-edit"></i> Tambah Info Lomba</button>
				</div>

				<!-- popup modal tambah lomba -->
				<div class="modal fade" id="tambahLomba" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Tambah data</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form enctype="multipart/form-data" method="POST" action="{{ route('lomba.store') }}">
                                        <div class="row">
                                            <label class="control-label col-md-4">Nama Lomba</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="nama_lomba" placeholder="Contoh: GEMASTIK 10" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="control-label col-md-4">Deskripsi Lomba</label>
                                            <div class="col-md-12">
                                            {!! Form::textarea('deskripsi',null, array('class' => 'form-control','placeholder'=>'DESKRIPSI','required'=>'required')) !!}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <label class="control-label col-sm-4">Deadline Pendaftaran</label>
                                            <div class="col-sm-8">
                                                <input class="form-control datepicker" id="date" name="deadline" placeholder="DD-MM-YYYY" type="text" required />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <label class="control-label col-sm-4">Mulai Lomba</label>
                                            <div class="col-sm-8">
                                                <input class="form-control datepicker" id="date" name="mulai_lomba" placeholder="DD-MM-YYYY" type="text" required />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <label class="control-label col-sm-4">Selesai Lomba</label>
                                            <div class="col-sm-8">
                                                <input class="form-control datepicker" id="date" name="selesai_lomba" placeholder="DD-MM-YYYY" type="text" required />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <label class="control-label col-sm-4">Web Lomba</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-max-width" name="web_lomba" placeholder="Contoh: https://www.gemastik.ui.ac.id" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <label class="control-label col-sm-4">Foto</label>
                                            <div class="col-sm-8">
                                                {!! Form::file('foto',array('required'=>'required')) !!}
                                            </div>
                                        </div>
                                        {{ csrf_field() }}
                                        <br>

                                        {!! Form::button('<i class="fa fa-times"></i>'. ' Reset', array('type' => 'reset', 'class' => 'btn btn-danger form-control'))!!}
                                        <br><br>
                                        <button class="form-control btn btn-warning">Simpan Data</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> <!-- modal fade -->

                <div class="panel-body table-responsive">
                	<table width="100%" class="table table-striped table-bordered table-hover">
                		<thead>
                			<tr>
                				<th>Nama</th>
                				<th>Deadline Pendaftaran</th>
                				<th>Jangka Waktu</th>
                                <th>Mulai Lomba</th>
                                <th>Selesai Lomba</th>
                				<th>Web</th>
                				<th>Deskripsi</th>
                				<th>Foto</th>
                				<th>Action</th>
                			</tr>
                		</thead>
                        <tbody>
                            @foreach($lombas as $lomba)
                            @if($lomba->status=="0")
                            <tr style="background-color: red;">
                            @else
                            <tr>
                            @endif
                                <td>{{$lomba->judul}}</td>
                                <td>{{date("d-m-Y", strtotime($lomba->deadline))}}</td>
                                @if(Carbon\Carbon::parse($lomba->deadline)<(Carbon\Carbon::now()))
                                    <td>berakhir</td>
                                @else
                                    <td>{{ Carbon\Carbon::parse($lomba->deadline)->diffInDays(Carbon\Carbon::now()) }} hari</td>
                                @endif
                                {{-- <td>{{ Carbon\Carbon::parse($lomba->deadline)->diffInDays(Carbon\Carbon::now()) }} hari</td> --}}
                                <td>{{ date("d-m-Y", strtotime($lomba->mulai_lomba)) }}</td>
                                <td>{{ date("d-m-Y", strtotime($lomba->selesai_lomba)) }}</td>
                                <td><a href="{{ $lomba->web }}" target="_blank">{{ $lomba->web }} <i class="fa fa-external-link"></i></a></td>
                                <td>{{ $lomba->deskripsi }}</a></td>
                                <td><a href="{{ route('lomba.foto', $lomba->id) }}" target="_blank">Cek Foto</a></td>
                                <td>
                                    @if ($lomba->status == '1')
                                        <div style="text-align: center;"><a href="{{ route('lomba.stop', $lomba->id) }}" class="btn btn-warning btn-xs" role="button"><i class="fa fa-pencil-square"></i> STOP </a></div>
                                    @else
                                        <div style="text-align: center;"><a href="{{ route('lomba.start', $lomba->id) }}" class="btn btn-success btn-xs" role="button"><i class="fa fa-pencil-square"></i> START </a></div>
                                    @endif
                                    <button data-toggle="modal" data-target="#editLomba{{ $lomba->id }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i> EDIT</button>
                                </td>
                            </tr>

                            <!-- popup modal edit lomba -->
                            <div class="modal fade" id="editLomba{{ $lomba->id }}" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Edit data</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form enctype="multipart/form-data" method="POST" action="{{ route('lomba.destroy', $lomba->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button class="form-control btn btn-danger"><i class="fa fa-times"></i> Delete</button>
                                                    </form>
                                                    <br><br>
                                                    <form enctype="multipart/form-data" method="POST" action="{{ route('lomba.update', $lomba->id) }}">
                                                    <div class="row">
                                                        <label class="control-label col-md-4">Nama Lomba</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control form-max-width" name="nama_lomba" placeholder="Contoh: GEMASTIK 10" required value="{{ $lomba->judul }}">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label class="control-label col-md-4">Deskripsi Lomba</label>
                                                        <div class="col-md-12">
                                                        {!! Form::textarea('deskripsi', $lomba->deskripsi, array('class' => 'form-control','placeholder'=>'DESKRIPSI','required'=>'required')) !!}
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <label class="control-label col-sm-4">Deadline Pendaftaran</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control datepicker" id="date" name="deadline" placeholder="DD-MM-YYYY" type="text" required value="{{ date("d-m-Y", strtotime($lomba->deadline)) }}" />
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <label class="control-label col-sm-4">Mulai Lomba</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control datepicker" id="date" name="mulai_lomba" placeholder="DD-MM-YYYY" type="text" required value="{{ date("d-m-Y", strtotime($lomba->mulai_lomba)) }}" />
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <label class="control-label col-sm-4">Selesai Lomba</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control datepicker" id="date" name="selesai_lomba" placeholder="DD-MM-YYYY" type="text" required value="{{ date("d-m-Y", strtotime($lomba->selesai_lomba)) }}" />
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <label class="control-label col-sm-4">Web Lomba</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control form-max-width" name="web_lomba" placeholder="Contoh: https://www.gemastik.ui.ac.id" required value="{{ $lomba->web }}">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <label class="control-label col-sm-4">Foto</label>
                                                        <div class="col-sm-8">
                                                            {!! Form::file('foto') !!}
                                                        </div>
                                                    </div>
                                                    {{ csrf_field() }}
                                                    {{ method_field('PUT') }}
                                                    <br>

                                                    {!! Form::button('<i class="fa fa-times"></i>'. ' Reset', array('type' => 'reset', 'class' => 'btn btn-danger form-control'))!!}
                                                    <br><br>
                                                    <button class="form-control btn btn-warning">Simpan Data</button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div> <!-- modal fade -->

                            @endforeach
                        </tbody>
                	</table>
                </div>

			</div>
		</div>
	</div>
@endsection