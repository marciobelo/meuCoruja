<?php
    require_once("$BASE_DIR/classes/Usuario.php");
	session_start();
    if( !isset( $_SESSION["usuario"]) ) 
    {
        http_response_code( 401 );
        exit;
    }