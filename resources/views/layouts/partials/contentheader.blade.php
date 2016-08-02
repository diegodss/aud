<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>

        <?php
        if (!isset($title))
            $title = "Sistema de Auditoria";
        if (!isset($subtitle))
            $subtitle = "";
        ?>
        {{ $title }}
        <small>{{ $subtitle }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{ trans('message.level') }}</a></li>
        <li class="active">{{ $subtitle }}</li>
    </ol>
</section>