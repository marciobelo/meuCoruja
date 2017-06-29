<?php
$BASE_DIR = __DIR__ . "/..";
require_once("$BASE_DIR/config.php");
require_once("$BASE_DIR/meu_coruja/valida_sessao.php");
require_once("$BASE_DIR/classes/Aluno.php");

$usuario = $_SESSION["usuario"];
$idPessoa = $usuario->getIdPessoa();

$aluno = Aluno::getAlunoByIdPessoa( $idPessoa);

$dadosUsuario= new stdClass();
$dadosUsuario->nome = $aluno->getNome();
$dadosUsuario->curso = "Um Curso Qualquer";
$dadosUsuario->matricula = "123456789";

$jsonDadosUsuario = json_encode($dadosUsuario);

echo $jsonDadosUsuario;
?>