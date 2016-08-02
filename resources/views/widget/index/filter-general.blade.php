{!! $filter->open !!}
<div class="input-group custom-search-form">
    {!! $filter->field('src') !!}
    <span class="input-group-btn">
        <button class="btn btn-default" type="submit">
            <span class="glyphicon glyphicon-search"></span>
        </button>
        <a href="<?php echo e(url('/' . $controller)); ?>" class="btn btn-default">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
    </span>
</div>
{!! $filter->close !!}