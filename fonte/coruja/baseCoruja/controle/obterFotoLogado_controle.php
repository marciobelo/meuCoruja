<?php
header("Content-type:image/png");
require_once("../../includes/comum.php");

$login = $_SESSION["login"];

if( $login->getFoto() == null) 
{
    readfile("$BASE_DIR/imagens/sem_foto.jpg");
} 
else 
{
    echo $login->getFoto();
}