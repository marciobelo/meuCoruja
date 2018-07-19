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
require_once("$BASE_DIR/classes/Mensagem.php");


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
        return "Reprovado por media";
    } elseif ($siglaSituacao == "AP") {
        return "Aprovado";
    } elseif ($siglaSituacao == "ID") {
        return "Isento de disciplina";
    }
}
date_default_timezone_set("America/Sao_Paulo");

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
$u->nomeCurso = utf8_encode($curso->getNomeCurso());
$u->matricula = $matriculaAluno->getMatriculaAluno();
$login = Login::obterLoginPorIdPessoa($idPessoa);
$u->hashLogin = $login->obterHashSenha($numMatriculaAluno);

$u->foto =  base64_encode(Login::obterLoginPorIdPessoa($idPessoa)->getFoto());

/////
//BOLETIM//
/////

$inscricoes = $matriculaAluno->obterInscricoesCursando();

$boletim = new stdClass();
$disciplinas = array();

foreach ($inscricoes as $inscricao) {
    $disciplina = new stdClass();
    
    //informa��es de cada disciplina
    $infoDisciplina = new stdClass();
    $infoDisciplina->siglaDisciplina = isNull(utf8_encode(($inscricao->getTurma()->getSiglaDisciplina())));
    $infoDisciplina->nomeProfessor = isNull(utf8_encode($inscricao->getTurma()->getProfessor()->getNome()));
    $infoDisciplina->emailProfessor = utf8_encode(Pessoa::obterPessoaPorId($inscricao->getTurma()->getProfessor()->getIdPessoa())->getEmail());
    $infoDisciplina->mediaFinal = isNull($inscricao->getMediaFinal());
    $infoDisciplina->faltas = isNull(ceil($inscricao->obterFaltasLancadas()));
    $infoDisciplina->limiteFaltas = isNull(ceil($inscricao->getTurma()->getComponenteCurricular()->getLimiteFaltas()));
    $infoDisciplina->idCriterioAvaliacao = isNull($inscricao->getTurma()->getCriterioAvaliacao()->getIdCriterioAvalicao());

    
    $boletim->siglaPeriodoLetivo = utf8_encode(isNull($inscricao->getTurma()->getPeriodoLetivo()->getSiglaPeriodoLetivo()));
    
    $disciplina->info = $infoDisciplina;
    
    //Avalia��es de cada disciplina
    $avaliacoes = array();
    $itens = $inscricao->obterItensCriterioAvaliacaoInscricaoNota();
    foreach ($itens as $item) {

        $avaliacao = new stdClass();

        $avaliacao->idCriterioAvaliacao = $item->getItemCriterioAvaliacao()->getIdItemCriterioAvaliacao();
        $avaliacao->rotulo = isNull(utf8_encode($item->getItemCriterioAvaliacao()->getRotulo()));
        $avaliacao->nota = isNull($item->getNota());
        
        array_push($avaliacoes, $avaliacao);
    }
    $disciplina->avaliacoes = $avaliacoes;
    
    //Detalhamento de faltas de cada disciplina
    $detalhamentoFaltas = array();
    $diasLetivoDateTime = $inscricao->getTurma()->obterDatasDiaLetivo();

    foreach ($diasLetivoDateTime as $diaLetivoTurma) {
        $diaLetivo = new DiaLetivoTurma($inscricao->getTurma(), $diaLetivoTurma);
        
        $str = $inscricao->obterResumoApontamentoDiaLetivo($diaLetivo);
        
        if ($diaLetivo->getDataLiberacao() !== null) {
            $qtdeFaltas = substr_count($str, "F");
            
            $detalhamentoFalta = new stdClass();
            //$data = date_parse($diaLetivo->getData()->date);
            //$detalhamentoFalta->data = $data['hour'].":".$data['minute']." ".$data['day']."/".$data['month']."/".$data['year'];
            $data = date_format(date_create($diaLetivo->getData()->date), 'H:i d/m/Y');
            
            $detalhamentoFalta->qtdeFaltas = $qtdeFaltas;
            $detalhamentoFalta->siglaPeriodo = isNull($inscricao->getTurma()->getPeriodoLetivo()->getSiglaPeriodoLetivo());
            
            array_push($detalhamentoFaltas, $detalhamentoFalta);
        }
    }
    $disciplina->detalhamentoFaltas = $detalhamentoFaltas;
    
    //Grade Hor�ria da disciplina
    
    $minhaGrade = array();

    $grades = Aloca::getListAlocaByIdTurma($inscricao->getIdTurma());
    
    foreach($grades as $grade){
        $gradeDisciplina = new stdClass();
        $tempo = TempoSemanal::getTempoSemanalById($grade->getIdTempoSemanal());
        $espaco = Espaco::obterEspacoPorId($grade->getIdEspaco());
        $gradeDisciplina->sala = utf8_encode($espaco->getNome());
        $gradeDisciplina->diaDaSemana = $tempo->getTempoSemanalById($tempo->getIdTempoSemanal())->getDiaSemana();
        
        //$horaInicio = date_parse($tempo->getTempoSemanalById($tempo->getIdTempoSemanal())->getHoraInicio());
        $horaInicio = date_create($tempo->getTempoSemanalById($tempo->getIdTempoSemanal())->getHoraInicio());
        $horaFim = date_create($tempo->getTempoSemanalById($tempo->getIdTempoSemanal())->getHoraFim());
      
        $horaInicio = date_format($horaInicio, 'H:i');
        $horaFim = date_format($horaFim, 'H:i');
        
        $gradeDisciplina->horario = $horaInicio." - ".$horaFim;
                
        $gradeDisciplina->siglaDisciplina = utf8_encode($inscricao->getTurma()->getSiglaDisciplina());
        $gradeDisciplina->professor = utf8_encode($inscricao->getTurma()->getProfessor()->getNome());
        
        array_push($minhaGrade, $gradeDisciplina);
        
    }
    $disciplina->minhaGrade = $minhaGrade;

    array_push($disciplinas,$disciplina);
}
 
$boletim->disciplinas = $disciplinas;

/////HIST�RICO///////

$inscricoes = $matriculaAluno->obterInscricoesConcluidas();
$dadosHistorico = array();
$historico = new stdClass();
foreach ($inscricoes as $inscricao) {
    $disciplinaHistorico = new stdClass();
    $disciplinaHistorico->siglaDisciplina = isNull(utf8_encode($inscricao->getTurma()->getSiglaDisciplina()));
    $disciplinaHistorico->nomeDisciplina = isNull(utf8_encode($inscricao->getTurma()->getComponenteCurricular()->getNomeDisciplina()));
    $disciplinaHistorico->situacao = isNull(utf8_encode(verificaSituacao($inscricao->getSituacaoInscricao())));
    //  var_dump(verificaSituacao($inscricao->getSituacaoInscricao()));
    $disciplinaHistorico->mediaFinal = isNull($inscricao->getMediaFinal());
    $disciplinaHistorico->faltas = isNull($inscricao->getTotalFaltas());
    $disciplinaHistorico->siglaPeriodoLetivo = isNull($inscricao->getTurma()->getPeriodoLetivo()->getSiglaPeriodoLetivo());
    $disciplinaHistorico->cr = isNull($matriculaAluno->calcularCR());

    array_push($dadosHistorico, $disciplinaHistorico);
}
$historico->disciplinas = $dadosHistorico;
$historico->cr = number_format($disciplinaHistorico->cr, 1, ',', '');




///////PEND�NCIAS//////

$inscricoes = $matriculaAluno->obterComponentesCurricularPendentes();

$disciplinasPendentes= new stdClass();
$pendencias = array();

foreach ($inscricoes as $inscricao) {
    $pendencia = new stdClass();
    $pendencia->siglaDisciplina = isNull(utf8_encode($inscricao->getSiglaDisciplina()));
    $pendencia->nomeDisciplina = isNull(utf8_encode($inscricao->getNomeDisciplina()));
    $pendencia->periodo = isNull($inscricao->getPeriodo());
    $pendencia->cargaHoraria = isNull($inscricao->getCargaHoraria());
    array_push($pendencias, $pendencia);
}
$disciplinasPendentes->disciplinas = $pendencias;


////Controle////

$controle = new stdClass();

$criteriosAvaliacao = array();
$idsCriteriosAvaliacao = CriterioAvaliacao::obterIdCriteriosAvaliacao();
foreach ($idsCriteriosAvaliacao as $idCriterioAvaliacao){
    if(in_array($idCriterioAvaliacao ,CriterioAvaliacao::obterIdCriteriosAvaliacaoCursando($matriculaAluno->getMatriculaAluno()))){
        
        $itensCriterioAvaliacao = ItemCriterioAvaliacao::obterItensPorIdCriterioAvaliacao($idCriterioAvaliacao);

        $criterioAvaliacao = new stdClass();
        $criterioAvaliacao->idCriterioAvaliacao = $idCriterioAvaliacao;
        $itens = array();
        foreach ($itensCriterioAvaliacao as $itemCriterioAvaliacao){
            $item = new stdClass();
            $item->idItemCriterioAvaliacao = $itemCriterioAvaliacao->getIdItemCriterioAvaliacao();
            $item->rotulo = utf8_encode($itemCriterioAvaliacao->getRotulo());
            $item->descricao = utf8_encode($itemCriterioAvaliacao->getDescricao());
            array_push($itens, $item);
        }
        $criterioAvaliacao->itensCriterioAvaliacao = $itens;

        array_push($criteriosAvaliacao, $criterioAvaliacao);
    }
}


$mensagens = Array();
$msgControle = new stdClass();

$qtdMsgs = 50;
$arrayMsg = Mensagem::obterUltimasMensagensQtd((int)$idPessoa, $qtdMsgs + 1);
$possuiMaisMensagens = false;
if (count($arrayMsg) > $qtdMsgs){
    $possuiMaisMensagens = true;
}
$msgControle->flgMensagens = $possuiMaisMensagens;

foreach($arrayMsg as $msg){
    $mensagem = new stdClass();
    $mensagem->idMensagem = $msg->getIdMensagem();
    $mensagem->assunto = utf8_encode($msg->getAssunto());
    $mensagem->texto = utf8_encode($msg->getTexto());
    $mensagem->data = $msg->getDataMensagem()->format('d-m-Y');
    array_push($mensagens, $mensagem);
}
$msgControle->mensagens = $mensagens;

$controle->msgControle = $msgControle;
$controle->criteriosAvaliacao = $criteriosAvaliacao;
$controle->dataAtualizacao = date("d/m/y - H:i");

$meuCoruja = new stdClass();
$meuCoruja->usuario = $u;
$meuCoruja->boletim = $boletim;
$meuCoruja->historico = $historico;
$meuCoruja->pendencias = $disciplinasPendentes;
$meuCoruja->controle = $controle;

$jsonMeuCoruja = json_encode($meuCoruja, JSON_PARTIAL_OUTPUT_ON_ERROR);

echo $jsonMeuCoruja;



?>



