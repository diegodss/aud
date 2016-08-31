@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            <label for="Role" class="col-sm-2 control-label">Role</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="Role" placeholder={{$comuna->Role}} readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
</div>
<?php echo $__env->make('layouts.boxbottom', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

@include('layouts.boxbottom')
@endsection
