<?php

$BASE_DIR = __DIR__ . "/..";
require_once("$BASE_DIR/config.php");
require_once("$BASE_DIR/meu_coruja/valida_sessao.php");
require_once("$BASE_DIR/classes/Aluno.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");
require_once("$BASE_DIR/classes/MatrizCurricular.php");
require_once("$BASE_DIR/classes/Curso.php");
require_once("$BASE_DIR/classes/Aloca.php");
require_once("$BASE_DIR/classes/TempoSemanal.php");
require_once("$BASE_DIR/classes/Espaco.php");

error_reporting(0);

function isNull($str) {

    if ($str == null) {
        return "-";
    } else {
        return $str;
    }
}

function verificaSituacao($siglaSituacao) {
    if ($siglaSituacao == "RF") {
        return "Reprovado por falta";
    } elseif ($siglaSituacao == "RM") {
        return "Reprovado por média";
    } elseif ($siglaSituacao == "AP") {
        return "Aprovado";
    } elseif ($siglaSituacao == "ID") {
        return "Isento de disciplina";
    }
}

/////USUARIO

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

/////
//BOLETIM//
/////

$inscricoes = $matriculaAluno->obterInscricoesCursando();

$boletim = new stdClass();
$disciplinas = array();

foreach ($inscricoes as $inscricao) {
    //$linha = array();
    $disciplina = new stdClass();
    
    //informações de cada disciplina
    $infoDisciplina = new stdClass();
    $infoDisciplina->siglaDisciplina = isNull($inscricao->getTurma()->getSiglaDisciplina());
    $infoDisciplina ->nomeProfessor = isNull($inscricao->getTurma()->getProfessor()->getNome());
    $infoDisciplina->mediaFinal = isNull($inscricao->getMediaFinal());
    $infoDisciplina->faltas = isNull($inscricao->getTotalFaltas());
    $infoDisciplina->limiteFaltas = isNull($inscricao->getTurma()->getComponenteCurricular()->getLimiteFaltas());
    
    $boletim->siglaPeriodoLetivo = isNull($inscricao->getTurma()->getPeriodoLetivo()->getSiglaPeriodoLetivo());
    
    $disciplina->info = $infoDisciplina;
    
    //Avaliações de cada disciplina
    $avaliacoes = array();
    $itens = $inscricao->obterItensCriterioAvaliacaoInscricaoNota();
    foreach ($itens as $item) {

        $avaliacao = new stdClass();

        $avaliacao->rotulo = isNull($item->getItemCriterioAvaliacao()->getRotulo());
        $avaliacao->nota = isNull($item->getNota());
        
        array_push($avaliacoes, $avaliacao);
    }
    $disciplina->avaliacoes = $avaliacoes;
    
    //Detalhamento de faltas de cada disciplina
    $detalhamentoFaltas = array();
    $diasLetivoDateTime = $inscricao->getTurma()->obterDatasDiaLetivo();
    //var_dump($diasLetivoDateTime);
    foreach ($diasLetivoDateTime as $diaLetivoTurma) {
        $diaLetivo = new DiaLetivoTurma($inscricao->getTurma(), $diaLetivoTurma);
        $str = $inscricao->obterResumoApontamentoDiaLetivo($diaLetivo);
        
        if ($diaLetivo->getDataLiberacao() !== null) {
            $qtdeFaltas = substr_count($str, "F");
            
            $detalhamentoFalta = new stdClass();
            $data = date_parse($diaLetivo->getData()->date);
            $detalhamentoFalta->data = $data['hour'].":".$data['minute']." ".$data['day']."/".$data['month']."/".$data['year'];
            $detalhamentoFalta->qtdeFaltas = $qtdeFaltas;
            $detalhamentoFalta->siglaPeriodo = isNull($inscricao->getTurma()->getPeriodoLetivo()->getSiglaPeriodoLetivo());
            
            array_push($detalhamentoFaltas, $detalhamentoFalta);
            
        }
    }
    $disciplina->detalhamentoFaltas = $detalhamentoFaltas;
    
    //Grade Horária da disciplina
    
    $minhaGrade = array();

    $grades =Aloca::getListAlocaByIdTurma($inscricao->getIdTurma());
    
    foreach($grades as $grade){
        $gradeDisciplina = new stdClass();
        $tempo = TempoSemanal::getTempoSemanalById($grade->getIdTempoSemanal());
        $espaco = Espaco::obterEspacoPorId($grade->getIdEspaco());
        $gradeDisciplina->sala = $espaco->getNome();
        $gradeDisciplina->diaDaSemana = $tempo->getTempoSemanalById($tempo->getIdTempoSemanal())->getDiaSemana();
        
        $horaInicio = date_parse($tempo->getTempoSemanalById($tempo->getIdTempoSemanal())->getHoraInicio());
        $horaFim = date_parse($tempo->getTempoSemanalById($tempo->getIdTempoSemanal())->getHoraFim());
        $gradeDisciplina->horario = $horaInicio['hour'].":".$horaInicio['minute']." - ".$horaFim['hour'].":".$horaFim['minute'];
        
        $gradeDisciplina->siglaDisciplina = $inscricao->getTurma()->getSiglaDisciplina();
        $gradeDisciplina->professor = $inscricao->getTurma()->getProfessor()->getNome();
        
        array_push($minhaGrade, $gradeDisciplina);
        
    }
    $disciplina->minhaGrade = $minhaGrade;

    array_push($disciplinas,$disciplina);
}
$boletim->disciplinas = $disciplinas;





/////HISTÓRICO///////

$inscricoes = $matriculaAluno->obterInscricoesConcluidas();
$dadosHistorico = array();
$historico = new stdClass();
foreach ($inscricoes as $inscricao) {
    $disciplinaHistorico = new stdClass();
    $disciplinaHistorico->siglaDisciplina = isNull($inscricao->getTurma()->getSiglaDisciplina());
    $disciplinaHistorico->nomeDisciplina = isNull($inscricao->getTurma()->getComponenteCurricular()->getNomeDisciplina());
    $disciplinaHistorico->situacao = verificaSituacao(isNull($inscricao->getSituacaoInscricao()));
    $disciplinaHistorico->mediaFinal = isNull($inscricao->getMediaFinal());
    $disciplinaHistorico->faltas = isNull($inscricao->getTotalFaltas());
    $disciplinaHistorico->siglaPeriodoLetivo = isNull($inscricao->getTurma()->getPeriodoLetivo()->getSiglaPeriodoLetivo());
    $disciplinaHistorico->cr = isNull($matriculaAluno->calcularCR());

    array_push($dadosHistorico, $disciplinaHistorico);
}
$historico->disciplinas = $dadosHistorico;
$historico->cr = $disciplinaHistorico->cr;
//array_push($meuCoruja, $historico);




///////PENDÊNCIAS//////

$inscricoes = $matriculaAluno->obterComponentesCurricularPendentes();

$disciplinasPendentes= new stdClass();
$pendencias = array();

foreach ($inscricoes as $inscricao) {
    $pendencia = new stdClass();
    $pendencia->siglaDisciplina = isNull($inscricao->getSiglaDisciplina());
    $pendencia->nomeDisciplina = isNull($inscricao->getNomeDisciplina());
    $pendencia->periodo = isNull($inscricao->getPeriodo());
    $pendencia->cargaHoraria = isNull($inscricao->getCargaHoraria());
    array_push($pendencias, $pendencia);
}
$disciplinasPendentes->disciplinas = $pendencias;


$meuCoruja = new stdClass();
$meuCoruja->usuario=$u;
$meuCoruja->boletim=$boletim;
$meuCoruja->historico=$historico;
$meuCoruja->pendencias=$disciplinasPendentes;

$jsonMeuCoruja = json_encode($meuCoruja, JSON_UNESCAPED_UNICODE);
echo $jsonMeuCoruja;


