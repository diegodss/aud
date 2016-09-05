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
@if (!empty($success))
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-success">
            <p>{{ $success }}</p>
        </div>
    </div>
</div>
@endif