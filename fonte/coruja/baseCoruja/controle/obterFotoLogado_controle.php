<?php
header("Content-type:image/png");
require_once("../../includes/comum.php");

$usuario = $_SESSION["usuario"];

if($usuario->getFoto()==null) {
    readfile("$BASE_DIR/imagens/sem_foto.jpg");
} else {
    echo $usuario->getFoto();
}
?>