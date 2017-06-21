<?php
$myObj= new stdClass();
$myObj->nome = "Um nome Qualquer"; //Pegar nome do usuário logado: SELECT nome FROM usuario='?'
$myObj->curso = "Um Curso Qualquer";
$myObj->matricula = "123456789";

$myJSON = json_encode($myObj);

echo $myJSON;
?>