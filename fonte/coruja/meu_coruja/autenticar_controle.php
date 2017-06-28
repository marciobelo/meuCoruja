<?php
$BASE_DIR = __DIR__ . "/..";
require_once("$BASE_DIR/config.php");
require_once("$BASE_DIR/classes/Usuario.php");

$nomeAcesso = filter_input(INPUT_GET, "nomeAcesso");
$senha = filter_input( INPUT_GET, "senha");

session_start();
try
{
    Usuario::autenticar($nomeAcesso, $senha, "ALUNO");
    if( isset( $_SESSION["usuario"]) ) 
    {
        $usuario = $_SESSION["usuario"];
        $usuario->setValidouLog( true);
        http_response_code( 200 );
        exit;
    }
} 
catch (Exception $ex) 
{
    http_response_code( 401 );
    exit;
}
http_response_code( 401 );
