<?php
$BASE_DIR = __DIR__ . "/..";
require_once("$BASE_DIR/config.php");
require_once("$BASE_DIR/meu_coruja/valida_sessao.php");
require_once("$BASE_DIR/classes/Aluno.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");
require_once("$BASE_DIR/classes/MatrizCurricular.php");
require_once("$BASE_DIR/classes/Curso.php");

$usuario = $_SESSION["usuario"];
$idPessoa = $usuario->getIdPessoa();
$numMatriculaAluno = $usuario->getNomeAcesso();
$matriculaAluno = MatriculaAluno::obterMatriculaAluno( $numMatriculaAluno);
$mc = $matriculaAluno->getMatrizCurricular();
$curso = $mc->getCurso();

$aluno = Aluno::getAlunoByIdPessoa( $idPessoa);


$dadosUsuario= new stdClass();
$dadosUsuario->nome = $aluno->getNome();
$dadosUsuario->curso = $curso->getNomeCurso();
$dadosUsuario->matricula = $matriculaAluno->getMatriculaAluno();

$jsonDadosUsuario = json_encode($dadosUsuario,JSON_UNESCAPED_UNICODE);


echo $jsonDadosUsuario;
?>