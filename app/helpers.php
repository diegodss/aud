<?php

    function setActionColumn($value, $row, $controller ) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $controller . '-index')) {
            $btnShow = "<a href='" . $controller . "/$row->id' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $controller . '-update')) {
            $btneditar = "<a href='" . $controller . "/$row->id/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $controller . '-destroy')) {
            $btnDeletar = "<a href='" . $controller . "/delete/$row->id' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }?>