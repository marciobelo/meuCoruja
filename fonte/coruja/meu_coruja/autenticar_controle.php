<?php
    $BASE_DIR = __DIR__ . "/..";
    require_once("$BASE_DIR/config.php");
    require_once("$BASE_DIR/classes/Usuario.php");
    $nomeAcesso = filter_input(INPUT_POST, "nomeAcesso");
    $senha = filter_input( INPUT_POST, "senha");

    session_start();
    try
    {
        Usuario::autenticar($nomeAcesso, $senha, "ALUNO");
        if( isset($_SESSION["usuario"]) ) 
        {
            $usuario = $_SESSION["usuario"];
            $usuario->setValidouLog(true);
            //echo 'Usuario autenticado com sucesso';
            http_response_code( 200 );
            header('location:boletim.html');
            exit;
        }else{
            return http_response_code(401);
            exit;
        }
    } 
    catch (Exception $ex) 
    {
        http_response_code( 404 );
        echo 'erro';
        exit;
    }
    //http_response_code( 401 );
    //echo 'Usuario ou senha incorretos';
    //header("Refresh:2; url=index.html");
?>

