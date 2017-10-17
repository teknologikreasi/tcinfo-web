@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="list-group">
				@foreach ($lombas as $lomba)
					<div class="list-group-item clearfix" style="padding: 24px">
						<img style="width: 180px; margin-right: 16px;" class="pull-left" src="{{ asset("images/$lomba->foto") }}">
						<h3 class="list-group-item-heading" style="color: #C9302C"><strong>{{ $lomba->judul }}</strong></h3>
						<a href="{{ $lomba->web }}" style="color: #787878;" target="_blank">{{ $lomba->web }} <i class="fa fa-external-link" style="margin-bottom: 16px"></i></a>
						<br>
						<p class="list-group-item-text">{{ $lomba->deskripsi }}</p>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection