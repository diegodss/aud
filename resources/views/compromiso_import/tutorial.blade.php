@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.errors')

<div class="row">
    <div class="col-xs-12">
        <h3>1. El archivo deve ser en formado XLS o XLSX</h3>
        <h3>2. Atención con el nombre en las hojas del archivo a ser importado.</h3>
        <p><img src="{{ asset('img/tutorial/compromiso_import/sheet_name.png') }}"  class="img-responsive img-center" /></p>
        <ul><li>a. La hoja para importacion de compromiso debe tener el nombre: <b>Minsal</b></li>
        </ul>
        <h3>3. La hoja <b>Minsal</b> debe tener los siguientes campos:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td><b>Columna</b></td>
                    <td><b>Descripción</b></td>
                    <td><b>Ejemplo</b></td>
                </tr>
            </thead>
            <tr>
                <td>correlativo_interno</td>
                <td>Identificación del compromiso en el Excel&nbsp;</td>
                <td>1</td>
            </tr>
            <tr>
                <td>nomenclatura</td>
                <td>Información del nuevo proceso</td>
                <td>PMG, NO PMG&nbsp;</td>
            </tr>
        </table>
        <h3>5. Archivo de ejemplo</h3>
        <p><a href="{{ url('import/modelo_para_import_update_pmg.xlsx') }}">Clique aqui </a>para hacer download de un archivo de ejemplo.</p>
    </div>
</div>
<p>&nbsp;</p>
<div class="form-group">
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
</div>
@include('layouts.boxbottom')
@endsection