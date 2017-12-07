<?php

$BASE_DIR = __DIR__ . "/..";
require_once("$BASE_DIR/config.php");
require_once("$BASE_DIR/meu_coruja/valida_sessao.php");
require_once("$BASE_DIR/classes/Aluno.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");
require_once("$BASE_DIR/classes/MatrizCurricular.php");
require_once("$BASE_DIR/classes/Curso.php");

function isNull($str){
    
    if($str == null){
        return "-";
    }else{
        return $str;
    }
}

function verificaSituacao($siglaSituacao){
    if ($siglaSituacao == "RF"){
        return "Reprovado por falta";
    }elseif ($siglaSituacao == "RM"){
        return "Reprovado por média";
    }elseif($siglaSituacao == "AP"){
        return "Aprovado";
    }elseif($siglaSituacao == "ID"){
        return "Isento de disciplina";
    }
}

/////USUARIO

$meuCoruja = array();
$usuario = $_SESSION["usuario"];
$idPessoa = $usuario->getIdPessoa();
$numMatriculaAluno = $usuario->getNomeAcesso();
$matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
$mc = $matriculaAluno->getMatrizCurricular();
$curso = $mc->getCurso();

$aluno = Aluno::getAlunoByIdPessoa($idPessoa);


$u = new stdClass();
$u->nomeUsuario = $aluno->getNome();
$u->nomeCurso = $curso->getNomeCurso();
$u->matricula = $matriculaAluno->getMatriculaAluno();

array_push($meuCoruja, $u);

/////
//BOLETIM//
/////

$inscricoes = $matriculaAluno->obterInscricoesCursando();

$boletim = array();



foreach ($inscricoes as $inscricao) {
    $linha = array();
    $avaliacoes = array();
    $dadosUsuario = new stdClass();
    $dadosUsuario->siglaDisciplina = isNull($inscricao->getTurma()->getSiglaDisciplina());
    $dadosUsuario->nomeDisciplina = isNull($inscricao->getTurma()->getComponenteCurricular()->getNomeDisciplina());
    $dadosUsuario->nomeProfessor = isNull($inscricao->getTurma()->getProfessor()->getNome());
    $dadosUsuario->mediaFinal = isNull($inscricao->getMediaFinal());
    $dadosUsuario->faltas = isNull($inscricao->getTotalFaltas());
    $dadosUsuario->limiteFaltas = isNull($inscricao->getTurma()->getComponenteCurricular()->getLimiteFaltas());
    $dadosUsuario->siglaPeriodoLetivo = isNull($inscricao->getTurma()->getPeriodoLetivo()->getSiglaPeriodoLetivo());

    $itens = $inscricao->obterItensCriterioAvaliacaoInscricaoNota();


    foreach ($itens as $item) {

        $avaliacao = new stdClass();

        $avaliacao->nota = isNull($item->getNota());
        $avaliacao->rotulo = isNull($item->getItemCriterioAvaliacao()->getRotulo());
        array_push($avaliacoes, $avaliacao);
    }

    array_push($linha, $dadosUsuario);
    array_push($linha, $avaliacoes);


    array_push($boletim, $linha);
}
array_push($meuCoruja, $boletim);





/////HISTÓRICO////////////////////////////////////////////////

$inscricoes = $matriculaAluno->obterInscricoesConcluidas();
$historico = array();

foreach ($inscricoes as $inscricao) {
    $dadosUsuario = new stdClass();
    
    $dadosUsuario->siglaDisciplina = isNull($inscricao->getTurma()->getSiglaDisciplina());
    $dadosUsuario->nomeDisciplina = isNull($inscricao->getTurma()->getComponenteCurricular()->getNomeDisciplina());
    $dadosUsuario->situacao = verificaSituacao(isNull($inscricao->getSituacaoInscricao()));
    $dadosUsuario->mediaFinal = isNull($inscricao->getMediaFinal());
    $dadosUsuario->faltas = isNull($inscricao->getTotalFaltas());
    $dadosUsuario->siglaPeriodoLetivo = isNull($inscricao->getTurma()->getPeriodoLetivo()->getSiglaPeriodoLetivo());
    
    $dadosUsuario->cr = $matriculaAluno->calcularCR();
    
    
    array_push($historico, $dadosUsuario);
}
array_push($meuCoruja, $historico);




///////PENDÊNCIAS////////////////////

$inscricoes = $matriculaAluno->obterComponentesCurricularPendentes();

$pendencias = array();

foreach ($inscricoes as $inscricao) {
    $pendencia = new stdClass();
    $pendencia->siglaDisciplina = isNull($inscricao->getSiglaDisciplina());
    $pendencia->nomeDisciplina = isNull($inscricao->getNomeDisciplina());
    $pendencia->periodo = isNull($inscricao->getPeriodo());
    $pendencia->cargaHoraria = isNull($inscricao->getCargaHoraria());
    array_push($pendencias, $pendencia);
}

array_push($meuCoruja, $pendencias);


$jsonMeuCoruja = json_encode($meuCoruja, JSON_UNESCAPED_UNICODE);
echo $jsonMeuCoruja;

