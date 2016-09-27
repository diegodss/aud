<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function dateDifference($date_1, $date_2, $differenceFormat = '%a') {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);
}

function linkPaginacaoRetorno($a) {

    //'a = A -> Avanço
    //'a = R -> Retorno

    switch ($a) {
        case "A":
            $paginaAtual = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            Session::set('LinkPaginacaoRetorno', $paginaAtual);
            $linkPaginacaoRetorno = "abrio sessao";
            break;
        case "R":
            $linkPaginacaoRetorno = Session::get('LinkPaginacaoRetorno');
            break;
    }
    return $linkPaginacaoRetorno;
}

function volver() {

    $paginaAtual = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $referer = "";
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
    }

    $volver_session = linkPaginacaoRetorno("R");

    if ($paginaAtual != $referer) {
        $volver_return = $referer . "/A";
    } else if (isset($volver_session)) {
        $volver_return = $volver_session . "/B";
    } else {
        $volver_return = URL::previous() . "/C";
    }
    return $volver_return;
}
