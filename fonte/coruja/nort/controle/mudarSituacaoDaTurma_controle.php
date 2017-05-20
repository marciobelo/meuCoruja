<?php
/*
 * UC01.03.03 - Editar Turma
 *
 * Em suma, a objetivos deste caso de uso � apenas alterar a situa��o da turma.
 * Obviamente respeitando o diagrama de estado e respeitando regras de neg�cio
 */
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/MatriculaProfessor.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";

// Verifica Permiss�o
if(!$usuario->temPermissao($MUDAR_SITUACAO_DA_TURMA)) {
        require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
        exit();
}

//A��o Inicial Padr�o
if ($_REQUEST["acao"] == NULL){
    $_REQUEST["acao"] = 'principal';
}

$acao = $_REQUEST["acao"];

switch ($acao) {
    case 'principal':
        $dadosDaTurma = consultarTurma($_POST['idTurma']);
        $turma = Turma::getTurmaById($_POST['idTurma']);

        include "$BASE_DIR/nort/formularios/mudarSituacaoDaTurma/mudarSituacaoDaTurma_principal.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;
case 'alterarSituacaoAJAX':
    header("Content-Type: text/html;  charset=ISO-8859-1",true);
        //INICIA TRANSA��O
        //EXECUTA VALIDA��ES
        //ALTERA REGISTROS
        //REALIZA COMMIT

        //Corrige codifica��o (acentua��o)
        $idTurma = $_POST['idTurma'];
        $novaSituacao = utf8_decode($_POST['novaSituacao']);
        $turma = Turma::getTurmaById($idTurma);
        $dadosDaTurma = consultarTurma($_POST['idTurma']); //Utilizado apenas para obter os dados para o log

        $con = BD::conectar();

        try{
            mysql_query("BEGIN", $con);

            // VALIDA A EXISTENCIA DE SOLICITA��ES N�O RESPONDIDAS PARA UMA TURMA
            if($novaSituacao == 'CONFIRMADA'){
                if (count($turma->getAlunosBySituacao('REQ')) > 0){
                    throw new Exception("Existem solicita��es n�o respondidas para esta turma");
                }
            }
            
            if($novaSituacao == 'CONFIRMADA'){
                if( ($turma->getAlunosBySituacao('DEF')==null) &&
                    ($turma->getAlunosBySituacao('CUR')==null) ) {
                    throw new Exception("N�o existem inscri��es para esta turma");
                }
            }
            
            if($novaSituacao == 'CONFIRMADA'){
                if ($turma->getMatriculaProfessor() == null){
                    throw new Exception("N�o existe professor alocado para esta turma");
                }
            }

            if($novaSituacao == 'FINALIZADA'){
                $qntAlunosSemNota = verificaNotasFaltasSituacoes($idTurma);
                if ($qntAlunosSemNota != 0){
                    throw new Exception("Existem $qntAlunosSemNota aluno(s) com o lan�amento de notas e situa��o incompleto");
                }
            }


            // ALTERA SITUA��ES DE DEFERIDO PARA CURSANDO
            if($novaSituacao == 'CONFIRMADA'){
                $query = sprintf("" .
                                "update Inscricao " .
                                "set `situacaoInscricao` = 'CUR' " .
                                "where " .
                                "situacaoInscricao = 'DEF' " .
                                "and idTurma = %d ",
                                mysql_real_escape_string($idTurma)
                );
                mysql_query($query, $con);
                if (mysql_errno($con) != 0) {
                    //Ocorreu erro, rollback
                    throw new Exception("Erro MySql: ".mysql_errno($con)." - ".mysql_error($con));
                }
            }


            // MODIFICA SITUA��O DA TURMA
            $query = sprintf("" .
                            "update Turma " .
                            "set `tipoSituacaoTurma` = '%s' " .
                            "where " .
                            "idTurma = %d",
                            mysql_real_escape_string($novaSituacao),
                            mysql_real_escape_string($idTurma)
            );
            mysql_query($query, $con);
            if (mysql_errno($con) != 0) {
                //Ocorreu erro, rollback
                throw new Exception("Erro MySql: ".mysql_errno($con)." - ".mysql_error($con));
            }

            //------------
            mysql_query("COMMIT", $con);

            registrarLog($dadosDaTurma['siglaCurso'],
                    $dadosDaTurma['nomeCurso'],
                    $dadosDaTurma['siglaPeriodoLetivo'],
                    $dadosDaTurma['siglaDisciplina'],
                    $dadosDaTurma['nomeDisciplina'],
                    $dadosDaTurma['turno'],
                    $dadosDaTurma['gradeHorario'],
                    $dadosDaTurma['tipoSituacaoTurma'],
                    $novaSituacao);
            if($novaSituacao == 'FINALIZADA') {
                echo 'OK_FINALIZADA'; // Caso 'OK' seja enviado, significa que tudo ocorreu corretamente
            } else {
                echo 'OK'; // Caso 'OK' seja enviado, significa que tudo ocorreu corretamente
            }
            
        } catch (Exception $ex){
            //log_event("EXECUTOU CATCH (rollback)");
            mysql_query("ROLLBACK", $con);
            echo $ex->getMessage();
        }

        break;
    default:
        //ERRO - USO INESPERADO
        trigger_error("N�o foi poss�vel identificar \"$passo\" como o pr�ximo passo da funcionalide de mudar situa��o da turma", E_USER_ERROR);
        break;
}

function consultarTurma($idTurma) {

    $con = BD::conectar();

    $query = sprintf(
        'SELECT T.`idTurma` , T.`siglaDisciplina` , CC.`nomeDisciplina`, T.`matriculaProfessor`, P.`nome` as `nomeProfessor`,  '
        . 'T.`turno`, T.`gradeHorario`, T.`tipoSituacaoTurma` , PL.`siglaPeriodoLetivo`, '
        . 'CUR.`siglaCurso`, CUR.`nomeCurso`, MC.`dataInicioVigencia`, CC.`tipoComponenteCurricular`, '
        . 'CC.`creditos`, CC.`cargaHoraria`, CC.`periodo`, T.`qtdeTotal`, T.`idPeriodoLetivo`, T.`idMatriz` '
        . 'FROM `Curso` CUR, `MatrizCurricular` MC, `ComponenteCurricular` CC, `PeriodoLetivo` PL, '
        . '`Turma` T '
        . 'left join `MatriculaProfessor` MP '
        . 'on MP.`matriculaProfessor` = T.`matriculaProfessor` '
        . 'left join `Pessoa` P '
        . 'on MP.`idPessoa` = P.`idPessoa` '
        . 'WHERE T.`idTurma` = %d '
        . 'AND MC.`siglaCurso` = T.`siglaCurso` '
        . 'AND MC.`idMatriz` = T.`idMatriz` '
        . 'AND PL.`idPeriodoLetivo` = T.`idPeriodoLetivo` '
        . 'AND CUR.`siglaCurso` = T.`siglaCurso` '
        . 'AND CC.`siglaCurso` = T.`siglaCurso` '
        . 'AND CC.`idMatriz` = T.`idMatriz` '
        . 'AND CC.`siglaDisciplina` = T.`siglaDisciplina` '
        . 'ORDER BY T.`tipoSituacaoTurma`, T.`turno`, T.`gradeHorario`, T.`siglaDisciplina` ASC'
        ,  mysql_real_escape_string($idTurma));

    $result = mysql_query($query);

    $infoTurma = mysql_fetch_array($result);

    return $infoTurma;
}


function obterQntInscricoesReq($idTurma) {
    //Obsoleto, substituido por count($turma->getAlunosBySituacao('REQ'))
    $con = BD::conectar();
    
    $query = sprintf("select count(*) as QNT from Inscricao ".
        "where `situacaoInscricao` = 'REQ' ".
        "and `idTurma` = %d ",
        mysql_real_escape_string($idTurma));
    
    $result = mysql_query($query);

    $res = mysql_fetch_array($result);

    return $res['QNT'];
}

function obterQntInscricoesDef($idTurma) {
    //Obsoleto, substituido por count($turma->getAlunosBySituacao('DEF'))
    $con = BD::conectar();

    $query = sprintf("select count(*) as QNT from Inscricao ".
        "where `situacaoInscricao` = 'REQ' ".
        "and `idTurma` = %d ",
        mysql_real_escape_string($idTurma));

    $result = mysql_query($query);

    $res = mysql_fetch_array($result);

    return $res['QNT'];
}

function verificaNotasFaltasSituacoes($idTurma) {
    $con = BD::conectar();

    $query = sprintf(
            "select count(*) as QNT ".
            "from Inscricao ".
            "where `mediaFinal` = null ".
            "or `totalFaltas` = null ".
            "or `situacaoInscricao` in ('REQ', 'DEF', 'CUR') ".
            "and `idTurma` = %d ",
        mysql_real_escape_string($idTurma));

    $result = mysql_query($query);

    $res = mysql_fetch_array($result);

    return $res['QNT'];
}

function registrarLog($siglaCurso, $nomeCurso, $siglaPeiodoLetivo, $siglaDisciplina, $nomeDisciplina, $turno, $grade, $situacaoAntiga, $situacaoNova) {
    $mensagem = "Alterada a situa��o da turma do curso $siglaCurso($nomeCurso), Per�odo Letivo $siglaPeiodoLetivo, ";
    $mensagem .= "disciplina $siglaDisciplina - $nomeDisciplina, Turno $turno, Grade $grade, da situa��o $situacaoAntiga para $situacaoNova";
    $_SESSION["usuario"]->incluirLog('UC01.03.03', $mensagem);
}
?>