<?php
$dadosUsuario= new stdClass();
$dadosUsuario->nome = "Um nome Qualquer"; //Pegar nome do usuário logado: SELECT nome FROM usuario='?'
$dadosUsuario->curso = "Um Curso Qualquer";
$dadosUsuario->matricula = "123456789";

$jsonDadosUsuario = json_encode($dadosUsuario);

echo $jsonDadosUsuario;
?>