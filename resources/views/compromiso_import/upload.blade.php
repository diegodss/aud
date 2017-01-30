@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.errors')
@if (!is_null($errorMsg))
<div class="alert alert-danger">
    <strong>Whoops!</strong> {{ trans('message.error.header') }}<br><br>
    <ul>
        @if (is_array($errorMsg))
        @foreach ($errorMsg as $error)
        <li>{{ $error }}</li>
        @endforeach
        @else
        <li>{{ $errorMsg }}</li>
        @endif
    </ul>
</div>
@endif

@if (isset($total))
<div class="alert alert-success">
    <p>{{ $total }} Informes fueran actualizados con exito.</p>
</div>
@endif


{!! Form::open(['url' => '/compromiso_import/upload','files'=>true]) !!}
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('Excel de Datos') !!}
            {!! Form::file('documento_adjunto[]', ['multiple' => 'multiple']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <div class='alert alert-warning'>
            <h4><i class='icon fa fa-warning'></i> Tienes duda de como debe ser el formato del archivo?</h4>
            <a href="{{ url('compromiso_import/tutorial') }}">Clique para ver el formato del archivo para importación</a>
        </div>
    </div>
</div>
<div class="form-group">
    {!! Form::submit('Upload', ['class' => 'btn btn-success']) !!}
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
</div>
@include('layouts.boxbottom')
<h4>Archivos disponibles </h4>
@include('layouts.boxtop')
<div class="form-group">
    <table class="table table-bordered">
        <tr>
            <td>Archivo</td>
            <td align="center"><b>Download</b></td>
            <td align="center"><b>Importar</b></td>
        </tr>
        @foreach ($files as $file)
        <?php $fileData = pathinfo($file); ?>
        <tr>
            <td>{{ $fileData["basename"] }}</td>
            <td align="center"><a href='{{ url( 'import/upload/' . $fileData["basename"] ) }}' class='btn'> <i class='fa fa-download'></i> Download </a></td>
            <td align="center"><a href='{{ url('/compromiso_import/read/' .$fileData["basename"] ) }}' class='btn'> <i class='fa fa-chevron-circle-down'></i> Import </a></td>
        </tr>
        @endforeach
    </table>
</div>
{!! Form::close() !!}
@include('layouts.boxbottom')
@endsection