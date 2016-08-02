<div class="items-page pull-right">
    &nbsp; {{ trans('message.registro_por_pagina') }}&nbsp;&nbsp;
</div>
<div class="input-group pull-right">
    {!! Form::open(['method' => 'GET','route'=>[ $controller.'.index'], 'id'=>'formItems']) !!}
    {!! Form::select('itemsPage',$itemsPageRange, $itemsPage, array('id'=>'itemsPage', 'class'=>'form-control') ) !!}
    {!! Form::close() !!}
</div>
<script>
    $(document).ready(function () {
        $('#itemsPage').on('change', function (e) {
            $("#formItems").submit();
        });
    });
</script>
