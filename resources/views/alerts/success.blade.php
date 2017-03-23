<!-- @if (Session::has('message'))
<div class="alert alert-success alert-dismissiable" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
{{Session::get('message') }}
</div>
@endif


@if ($message = Session::get('success'))
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-success">
            <p>a{{ $message }}</p>
        </div>
    </div>
</div>
@endif
-->
<!-- return View::make('usuario.edit', $returnData)->withSuccess($mensage_success);  -->
@if (!empty($success))
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-success">
            <p>{{ $success }}</p>

        </div>
    </div>
</div>
@endif
<!-- Works with return redirect()->route('role.edit', $id)->with('message', $mensage_success);  -->
@if(session()->has('message'))
<div class="row">
    <div class="col-xs-12">
        @if(session()->has('controller'))
        @can('userAction', session()->get('controller') . '-create')
        <a href="{{url('/'.session()->get('controller').'/create')}}" class="btn btn-success" >Agregar Nuevo</a>
        @endcan
        @endif
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        &nbsp;
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-success" >
            {{ session()->get('message') }}
        </div>
    </div>
</div>
@endif