<?php
header("Content-type:image/jpg");
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Login.php";

$idPessoa = $_REQUEST["idPessoa"];
$login = Login::obterLoginPorIdPessoa($idPessoa);
       
if( $login==null || $login->getFoto() == null ) {
    readfile("$BASE_DIR/imagens/sem_foto.jpg");
} else {
    echo $login->getFoto();
}