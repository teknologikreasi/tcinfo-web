@extends('admin.layouts.template')

@section('css')
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.16/datatables.min.css"/>
@endsection

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
			<div class="panel panel-default clearfix">
				<div class="panel-heading">
					<button data-toggle="modal" data-target="#tambahInfo" class="btn btn-warning"><i class="fa fa-fw fa-edit"></i> Tambah Info</button>
					<div class="btn-group">
						<a href="{{ route('admininfohmtc') }}" class="btn btn-default" role="button">All Tags</a>
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="" data-toggle="modal" data-target="#editTag">Edit Tag ...</a></li>
							@foreach ($tags as $tag)
								<li><a href="{{ route('admininfohmtc.tag', $tag->id) }}">{{ $tag->name }}</a></li>
							@endforeach
						</ul>
					</div>
				</div>

				<!-- popup modal tambah tag -->
				<div class="modal fade" id="editTag" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Edit Tag</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<form class="form-horizontal" action="{{ route('tag.store') }}" method="POST" enctype="multipart/form-data">
											{{ csrf_field() }}
											<div class="row">
												<label for="tagName" class="col-md-2 control-label">New Tag</label>
												<div class="col-md-10">
													<div class="input-group">
														<input id="tagName" type="text" name="tagName" class="form-control" placeholder="Tag Name" required>
														<span class="input-group-btn">
															<button class="btn btn-warning">Save Tag</button>
														</span>
													</div>
												</div>
											</div>
										</form>
										<br>
										<table width="100%" class="table table-striped table-bordered table-hover">
											<thead>
												<th>Tag</th>
												<th>Action</th>
											</thead>
											<tbody>
												@foreach ($tags as $tag)
													<tr>
														<td>{{ $tag->name }}</td>
														<td>
															<div class="row" style="text-align: center">
																<form enctype="multipart/form-data" method="POST" action="{{ route('tag.destroy', $tag->id) }}">
																	{{ csrf_field() }}
																	{{ method_field('DELETE') }}
																	<button class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Delete</button>
																</form>
															</div>
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- popup modal tambah info -->
				<div class="modal fade" id="tambahInfo" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Tambah Info</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<form action="{{ route('admininfohmtc.store') }}" method="POST" enctype="multipart/form-data">
											<div class="row">
												<div class="col-md-12">
													{!! Form::textarea('caption',null, array('class' => 'form-control','placeholder'=>'Caption','required'=>'required')) !!}
												</div>
											</div>
											<br>
											<div class="row">
												<label class="control-label col-md-3">Upload image</label>
												<div class="col-md-9">
													{!! Form::file('foto') !!}
												</div>
											</div>
											{{ csrf_field() }}
											<br>
											<button class="form-control btn btn-warning">Post</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- popup -->

				<div class="panel-body">
					<table width="100%" id="table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>Caption</th>
								<th>Image</th>
								<th>Tags</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($posts as $post)
								<tr @if ($post->status == "0") style="background-color: red;" @endif >
									<td>{{ $post->caption }}</td>
									<td>
										@if ($post->foto != "")
											<a href="{{ route('admininfohmtc.foto', $post->id) }}" target="_blank">Cek Image</a>
										@else
											Tidak ada gambar
										@endif
									</td>
									<td>
										<?php $tagged = App\Infohmtc::find($post->id)->tag; ?>
										@foreach ($tagged as $tag)
											<i>{{ $tag->name }}@if(!$loop->last),@endif</i>
										@endforeach
									</td>
									<td>
										<div class="row" style="text-align: center;">
											@if ($post->status == "1")
												<a href="{{ route('admininfohmtc.stop', $post->id) }}" class="btn btn-danger btn-xs" role="button"><i class="fa fa-pencil-square"></i> Stop</a>
											@else
												<a href="{{ route('admininfohmtc.start', $post->id) }}" class="btn btn-warning btn-xs" role="button"><i class="fa fa-pencil-square"></i> Start</a>
											@endif
											<button data-toggle="modal" data-target="#editInfo{{ $post->id }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i> Edit</button>
											<div class="btn-group">
												<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
													Add Tag <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
													@foreach ($tags as $tag)
														<li><a href="{{ route('admininfohmtc.addtag', ['post_id' => $post->id, 'tag_id' => $tag->id]) }}">{{ $tag->name }}</a></li>
													@endforeach
												</ul>
											</div>
										</div>
									</td>
								</tr>

								<!-- popup modal edit info -->
								<div class="modal fade" id="editInfo{{ $post->id }}" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Edit Info</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-md-12">
														<form enctype="multipart/form-data" method="POST" action="{{ route('admininfohmtc.destroy', $post->id) }}">
															{{ csrf_field() }}
															{{ method_field('DELETE') }}
															<button class="form-control btn btn-danger"><i class="fa fa-times"></i> Delete</button>
														</form>
														<br><br>
														<form action="{{ route('admininfohmtc.update', $post->id) }}" method="POST" enctype="multipart/form-data">
															{{ csrf_field() }}
															{{ method_field('PUT') }}
															<div class="row">
																<div class="col-md-12">
																	{!! Form::textarea('caption', $post->caption, array('class' => 'form-control','placeholder'=>'Caption','required'=>'required')) !!}
																</div>
															</div>
															<br>
															<div class="row">
																<label class="control-label col-md-3">Upload image</label>
																<div class="col-md-9">
																	{!! Form::file('gambar') !!}
																</div>
															</div>
															<br>
															<button class="form-control btn btn-warning">Post</button>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div> <!-- popup -->
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('js')
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.16/datatables.min.js"></script>

	<script>
		$(document).ready(function(){
		    $('#table').DataTable();
		});
	</script>
@endsection