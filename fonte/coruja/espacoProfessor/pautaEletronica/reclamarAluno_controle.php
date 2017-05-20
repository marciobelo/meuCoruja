<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Usuario.php");
require_once("$BASE_DIR/classes/Turma.php");
require_once("$BASE_DIR/classes/PeriodoLetivo.php");
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/DiaLetivoTurma.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");
require_once("$BASE_DIR/classes/ComponenteCurricular.php");
require_once("$BASE_DIR/classes/Inscricao.php");
require_once("$BASE_DIR/classes/Mensagem.php");
require_once("$BASE_DIR/classes/Util.php");

$acao = $_REQUEST["acao"];
$idTurma = $_REQUEST["idTurma"];
$data = $_REQUEST["data"];

$usuario = $_SESSION["usuario"];

$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();

// Verifica se o professor é o titular da turma informada
if( $professor->getIdPessoa() != $usuario->getIdPessoa() ) {
    trigger_error("Usuário não tem permissão para executar essa ação!",E_USER_ERROR);
    exit;
}

if( !isset ($acao) ) {

    if( isset ($_SESSION["msgsErro"]) ) {
        $msgsErro = $_SESSION["msgsErro"];
        unset ($_SESSION["msgsErro"]);
    }
    
    include "$BASE_DIR/espacoProfessor/pautaEletronica/formReclamarAluno.php";
    exit;
} else {
    switch($acao) {
    case "buscarAlunoPorMatricula":
        
        $numMatriculaAluno = $_GET["numMatriculaAluno"];
        
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
        
        if( $matriculaAluno == null ) {
            Header('Content-type: text/xml');
            echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
                <matriculaAluno>
                <codigoRetorno>2</codigoRetorno>
            </matriculaAluno>";
            exit;
            
        } else {
        
            $nome = $matriculaAluno->getAluno()->getNome();
            $situacaoMatricula = $matriculaAluno->getSituacaoMatricula();
            $idPessoa = $matriculaAluno->getIdPessoa();

            $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
                <matriculaAluno>
                <codigoRetorno>1</codigoRetorno>
                <idPessoa>$idPessoa</idPessoa>
                <nomeAluno>$nome</nomeAluno>
                <situacaoMatricula>$situacaoMatricula</situacaoMatricula>
            </matriculaAluno>";
            Header('Content-type: text/xml');
            echo $xml;
            exit;
        }
    case "reclamarAluno":
        
        $numMatriculaAluno = $_POST["numMatriculaAluno"];
        
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
        if( $matriculaAluno != null) {
            if( $matriculaAluno->isAtiva() ) {
                
                if( !Inscricao::alunoJaRequereuInscricaoTurma($idTurma, $numMatriculaAluno) ) {
                    $cc = $turma->getComponenteCurricular();
                    $quitacaoTO = $cc->obterQuitacao($matriculaAluno);
                    if( $quitacaoTO == null ) {
                        $con = BD::conectar();
                        try {
                            mysql_query("BEGIN", $con); // Inicia transação

                            Inscricao::requererInscricao($idTurma, $numMatriculaAluno,
                                    Inscricao::RECLAMADO_PELO_PROFESSOR, $con);

                            $strLog = sprintf("Aluno %s, matrícula %s, reclamado pelo professor %s na pauta da turma da disciplina %s, grade %s, turno %s, período letivo %s, do curso %s.", 
                                    $matriculaAluno->getAluno()->getNome(),
                                    $matriculaAluno->getMatriculaAluno(),
                                    $professor->getNome(),
                                    $turma->getSiglaDisciplina(),
                                    $turma->getGradeHorario(),
                                    $turma->getTurno(),
                                    $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(),
                                    $turma->getSiglaCurso()
                                    );
                            global $RECLAMAR_ALUNO_PAUTA_TURMA;
                            $usuario->incluirLog($RECLAMAR_ALUNO_PAUTA_TURMA,  $strLog, $con);

                            $arrIdPessoa = array();
                            $arrIdPessoa[] = Config::SECRETARIA_ID_PESSOA;
                            Mensagem::depositarMensagem("Aluno reclamado por professor em pauta", $strLog, $arrIdPessoa, $con);

                            mysql_query("COMMIT", $con);
                            $msgsErro = array();
                            $msgsErro[] = sprintf("Aluno(a) %s reclamado com sucesso!", $matriculaAluno->getAluno()->getNome());
                            $_SESSION["msgsErro"] = $msgsErro;
                        } catch(Exception $exc) {
                            $msgsErro = array();
                            $msgsErro[] = "Erro inesperado! Não pode ser inscrito!";
                            $_SESSION["msgsErro"] = $msgsErro;
                            mysql_query("ROLLBACK", $con);
                        }
                    } else {
                        $msgsErro = array();
                        $msgsErro[] = sprintf("Aluno de matrícula %s já cumpriu essa disciplina! Não pode ser inscrito!", $numMatriculaAluno);
                        $_SESSION["msgsErro"] = $msgsErro;
                    }
                } else {
                    $inscricao = Inscricao::getInscricao($idTurma, $numMatriculaAluno);
                    if( $inscricao->getSituacaoInscricao() == Inscricao::NEG ) {
                        $msgsErro = array();
                        $msgsErro[] = sprintf("O aluno %s (%s) teve anteriormente seu pedido de inscrição indeferido e por isso não ser reclamado. Encaminhe-o para a secretaria.", $matriculaAluno->getAluno()->getNome(),$numMatriculaAluno);
                        $_SESSION["msgsErro"] = $msgsErro;
                    } else {
                        $msgsErro = array();
                        $msgsErro[] = sprintf("Aluno de matrícula %s já requereu ou está nesta turma!", $numMatriculaAluno);
                        $_SESSION["msgsErro"] = $msgsErro;
                    }
                }
            } else {
                    $msgsErro = array();
                    $msgsErro[] = sprintf("A matrícula %s não é ativa! Encaminhe-o para a secretaria!", $numMatriculaAluno);
                    $_SESSION["msgsErro"] = $msgsErro;
            }
        } else {
            $msgsErro = array();
            $msgsErro[] = sprintf("Aluno de matrícula %s inexistente!", $numMatriculaAluno);
            $_SESSION["msgsErro"] = $msgsErro;
        }
        
        Header("Location: /coruja/espacoProfessor/pautaEletronica/reclamarAluno_controle.php?idTurma=$idTurma&data=$data");
        break;
    }
}
?>