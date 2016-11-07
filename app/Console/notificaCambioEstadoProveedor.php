<?php
/*
 * @autor: Alex Reimilla C.
 * @fecha: 20/04/2016
 * @version: v2.0
 * @descripcion: WS de notificaCambioEstado
 */
    ini_set( "display_errors", "on" );
    require_once($_SERVER['DOCUMENT_ROOT'].'/minsal/trunk/ws_iisa/definitions/config.php');
    require_once (SYSTEM_PATH . "ConexionBD.php");
    require_once (WS_PATH . "Util/_packageUtil.php");
    require_once (WS_PATH . "DAO/DAOWebservice.php");
    require_once (WS_PATH . "validacion/_packageValidation.php");
    require_once (WS_PATH . 'functionGlobal/functionGlobal.php');
    require_once (WS_PATH . "functionGlobal/ConsumidorEE.php");

    function getNotificaCambioEstado($arrWS) {

        $daoWebservice    = new DAOWebservice();    

        $webservice   = "notificaCambioEstadoProveedor";
        $wsConsumoEE  = "notificaCambioEstadoConsumidor"; 
        $tagRespuesta = ""; 
        $idProyecto   = "1";//Informe Sanitario            
        
        $daoWebservice->setWebservice($webservice);
        $daoWebservice->setArrWS($arrWS);
        $daoWebservice->setIdProyecto($idProyecto);       

        $idTransaccion 	= $daoWebservice->preProcesaDocumento();  
        $idTramite      = $arrWS["documentoContent"]["idTramite"];

        $procesaWS   = new ProcesaWS(); 
        $retornoEE   = $procesaWS->procesaDocumento($arrWS,$wsConsumoEE,$idProyecto); 
        $retorno     = $procesaWS->getRetornoASD($arrWS,$retornoEE);
        $idTransaccionConsumidor = $retornoEE["cabecera"]["idTransaccion"]; 
 
		 // 11-05 Modificacion 04
        //$daoWebservice->updLogProveedor($retornoEE, $webservice, $idTransaccion, $idTramite, $estadoOperacion, $glosaOperacion , $idProyecto );
		$daoWebservice->updLogProveedor($retorno, $webservice, $idTransaccion, $idTramite, $estadoOperacion, $glosaOperacion , $idProyecto );
        $daoWebservice->insertMacroTransaccion($idTransaccion, $idTransaccionConsumidor); 
        
        return $retorno;
    }
        
    // Genera WS Server nusoap
    $server = new soap_server();
    $server->debug_flag = SOAP_SERVER_DEBUG_MODE;
    $server->configureWSDL(SOAP_SERVER_NAME, SOAP_SERVER_NAMESPACE);
    $server->wsdl->schemaTargetNamespace = SOAP_SERVER_NAMESPACE;
    $server->soap_defencoding = SOAP_SERVER_ENCODING; 
    
    require_once 'definition/definitionsRequest.php';	
    require_once 'definition/definitionsResponse.php';
    require_once 'definition/procesaWS.php';

    $server->register(
        'getNotificaCambioEstado',
        array('notificaCambioEstadoConsulta'=> 'tns:notificaEERequest'),
        array('return' => 'tns:acuseReciboRespuesta'),  
        SOAP_SERVER_NAMESPACE,
        SOAP_SERVER_NAMESPACE,
        'rpc',
        'literal', 
        'Interoperatibidad ASD IISA' 
    );    

    $HTTP_RAW_POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
    $server->service($HTTP_RAW_POST_DATA);
        
        

?>