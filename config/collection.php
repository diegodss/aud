<?php

// config/constants.php

return [
    'criticidad' => array(
        "" => "Seleccione"
        , "Alta" => "Alta"
        , "Media" => "Media"
        , "Baja" => "Baja")
    , 'nomenclatura' => array(
        "PMG" => "PMG"
        , "NO PMG" => "NO PMG"
        , "REPROG." => "REPROG."
        , "Contraloría General de la República" => "Contraloría General de la República"
    ), 'estado' => array(
        "REPROGRAMADO" => "REPROGRAMADO"
        , "FINALIZADO" => "FINALIZADO"
        , "VENCIDO" => "VENCIDO"
        , "VIGENTE" => "VIGENTE"
        , "EN SUSCRIPCION" => "EN SUSCRIPCION"
    ), 'condicion' => array(
        "No evaluado" => "No evaluado"
        , "Cumplida Parcial" => "Cumplida Parcial"
        , "No Cumplida" => "No Cumplida"
        , "Cumplida" => "Cumplida"
        , "Asume riesgo" => "Asume riesgo"
        , "Reprogramado" => "Reprogramado"
    /*
      , 'condicion' => array(
      "Reprogramado" => "Reprogramado"
      , "En Proceso" => "En Proceso"
      , "Cumplida Parcial" => "Cumplida Parcial"
      , "No Cumplida" => "No Cumplida"
      , "Cumplida" => "Cumplida" */
// --------- Proceso Auditado --------------
    ), 'objetivo_auditoria' => array(
        "Gubernamental" => "Gubernamental"
        , "Ministerial" => "Ministerial"
        , "Institucional" => "Institucional"
    ), 'actividad_auditoria' => array(
        "Auditoria Interna" => "Auditoria Interna"
        , "Auditoria Externa-Publico" => "Auditoria Externa-Publico"
        , "Auditoría Externa-Privado" => "Auditoría Externa-Privado"
        , "Contraloría General de la República" => "Contraloría General de la República"
        , "Otro" => "Otro"
    ), 'tipo_auditoria' => array(
        "Planificada" => "Planificada"
        , "No Planificada" => "No Planificada"
    ), 'numero_informe_unidad' => array(
        "UAI" => "UAI"
        , "UAE" => "UAE"
        , "UAS" => "UAS"
        , "DAM" => "DAM"
    ), 'tipo_informe' => array(
        "Informe Final" => "Informe Final"
        , "Informe de Seguimiento" => "Informe de Seguimiento"
        , "Informe Especial" => "Informe Especial"
    ), 'tipo_centro_responsabilidad' => array(
        "division" => "Division"
        , "gabinete" => "Gabinete"
        , "seremi" => "Seremi"
    /*  ), '' => array(
      ), '' => array(
      ), '' => array(
      ), '' => array(
      ), '' => array(
      ), '' => array( */
    ),
    'option' => 'ejemplo',
];
