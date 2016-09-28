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

        //linkPaginacaoRetorno("A");
        /* 28/09 Este metodo fue creado con el objectivo de resolver 2 problemas:
          1. Al grabar un form mostramos la misma pagina con un mensaje de confirmacion.
          con el metodo URL::previous() al clicar volver, la pagina devulete para el form, sin el mensaje, o sea, la pagina anterior.
          Queriamos con esa funcion hacer con que al volver, automativamente el form se devolva al listado anterior. (Proceso Auditado > Hallazgo)
          2. Al navegar en proceso_Auditado, pagina 2, entrar en un proceso y volver, queremos volver a pagina 2, y no a primera pagina.
         * Pero la funcion no funciono como esperado, despues de perder una tarde enterera, decidimos dejar en standby y usar el camino especifico en el boton volver.

         *
         *  */

        case "A":

            $session = Session::get('LinkPaginacaoRetorno');
            if (isset($session["actual"])) {
                $session_actual = $session["actual"];
            } else {
                $session_actual = "empty";
            }

            $paginaAtual["actual"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $paginaAtual["anterior"] = $session_actual;
            Session::set('LinkPaginacaoRetorno', $paginaAtual);
            $linkPaginacaoRetorno = "abrio sessao";
            break;
        case "R":
            $session = Session::get('LinkPaginacaoRetorno');
            $linkPaginacaoRetorno = $session["anterior"]
                    . "<BR>________________________________________________Actual: " . $session["actual"];
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
