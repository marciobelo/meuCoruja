<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/Inscricao.php";

// Verifica Permiss�o
if(!$usuario->temPermissao($EDITAR_NOTAS_E_SITUACAO_DO_ALUNO_EM_TURMA)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit();
}

//A��o Inicial Padr�o
if ($_REQUEST["acao"] == NULL) {
    $_REQUEST["acao"] = 'editarNota';
}

$acao = $_REQUEST["acao"];

switch ($acao) {
    case 'editarNota':
        $turma = Turma::getTurmaById($_POST['idTurma']);
        $aluno = Aluno::getAlunoByNumMatricula($_POST['numMatriculaAluno']);
        $inscricao = Inscricao::getInscricao($turma->getIdTurma(),$_POST['numMatriculaAluno']);
        require "$BASE_DIR/nort/formularios/editarNotas/editarNotas_principal.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;
    case 'salvarDadosAJAX':
        header("Content-Type: text/html;  charset=ISO-8859-1",true);

        //Chave
        $idTurma = $_POST['idTurma'];
        $numMatriculaAluno = $_POST['numMatriculaAluno'];
        //Dados
        $insc_mediaFinal = str_replace(",", ".", $_POST['insc_mediaFinal']);
        $insc_totalFaltas = $_POST['insc_totalFaltas'];
        $insc_situacao = $_POST['insc_situacao'];
        $insc_parecer = utf8_decode($_POST['insc_parecer']); //Corrige codifica��o (acentua��o)

        //Objetos
        $turma = Turma::getTurmaById($idTurma);
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);

        //Objetos utilizadas pelo log que ser�o carregadas quando necess�rias
        $inscricaoEstadoAntigo = NULL;
        $inscricaoEstadoNovo = NULL;

        // Valida preenchimento das notas e total de faltas para alunos em situa��o de AP, RM, RF
        If($insc_situacao == Inscricao::AP | $insc_situacao == Inscricao::RM | $insc_situacao == Inscricao::RF) {
            if(!is_numeric($insc_mediaFinal)){
                exit('Prencha adequadamente a m�dia final');
            }
            if(!is_numeric($insc_totalFaltas)) {
                exit('Prencha adequadamente o total de faltas');
            }
        }

        //Alunos aprovados devem ter nota igual ou superior a 5.0
        If($insc_situacao == Inscricao::AP) {
            if(floatval($insc_mediaFinal) < 5) {
                exit('Aluno com m�dia final menor que 5,0 n�o pode ser aprovado');
            }
        }

        //Alunos reprovados por m�dia devem ter nota inferior a 5.0
        If($insc_situacao == Inscricao::RM) {
            if(floatval($insc_mediaFinal) >= 5){
                exit('Aluno com m�dia final maior ou igual � 5,0 n�o pode ser reprovado por m�dia');
            }
        }

        //Para ser reprovado por falta, o total de faltas precisa ser igual ou superior a 25%
        If($insc_situacao == Inscricao::RF) {
            $cargaHoraria = $turma->getComponenteCurricular()->getCargaHoraria();
            if(floatval($insc_totalFaltas) <= (floatval($cargaHoraria)*0.25)) {
                exit('O total de faltas deste aluno n�o � suficiente para reprova��o por falta');
            }
        }

        //Um aluno com + de 25% de faltas n�o pode ser aprovado ou reprovado por m�dia, ele deve ser reprovado por faltas
        If($insc_situacao == Inscricao::AP | $insc_situacao == Inscricao::RM) {
            $cargaHoraria = $turma->getComponenteCurricular()->getCargaHoraria();
            if(floatval($insc_totalFaltas) > (floatval($cargaHoraria)*0.25)) {
                exit('O total de faltas deste aluno foi supeior a 25% da carga horaria, sua situa��o deve ser reprovada por falta');
            }
        }

        //Alunos reprovados por falta devem ter m�dia 0,0
        If($insc_situacao == Inscricao::RF) {
            if(floatval($insc_mediaFinal) != 0){
                exit('Alunos reprovados por falta devem ter m�dia 0,0');
            }
        }

        //Captura o estado antigo da inscri��o para ser utilizado no Log
        $inscricaoEstadoAntigo = Inscricao::getInscricao($idTurma, $numMatriculaAluno);
        
        $con = BD::conectar();
        
        $escaped_parecerInscricao;

        if($insc_situacao == Inscricao::ID ) {
            $escaped_mediaFinal = "NULL";
            $escaped_totalFaltas = "NULL";
            $escaped_parecerInscricao = "'".mysql_real_escape_string($insc_parecer)."'";
        } else {
            $escaped_mediaFinal = Util::tratarNumeroNullSQL($insc_mediaFinal);
            $escaped_totalFaltas = Util::tratarNumeroNullSQL($insc_totalFaltas);
            $escaped_parecerInscricao = "NULL";
        }

        $query = sprintf("" .
                        " update" .
                        "     Inscricao " .
                        " set".
                        "     `situacaoInscricao` = '%s', " . // #1
                        "     `mediaFinal` = %s, " .          // #2
                        "     `totalFaltas` = %s, " .         // #3
                        "     `parecerInscricao` = %s " .     // #4
                        " where " .
                        "     `idTurma` = %d".                // #5
                        "     and `matriculaAluno` = '%s'",   // #6

                        mysql_real_escape_string($insc_situacao),    // #1
                        $escaped_mediaFinal,                         // #2
                        $escaped_totalFaltas,                        // #3
                        $escaped_parecerInscricao,                   // #4
                        mysql_real_escape_string($idTurma),          // #5
                        mysql_real_escape_string($numMatriculaAluno) // #6
        );
        mysql_query($query, $con);
        if (mysql_errno($con) != 0) {
            exit("Erro MySql: ".mysql_errno($con)." - ".mysql_error($con));
        }

        //Captura o novo estado da inscri��o para ser utilizado no Log
        $inscricaoEstadoNovo = Inscricao::getInscricao($idTurma, $numMatriculaAluno);

        registrarLog($turma, $matriculaAluno, $inscricaoEstadoAntigo, $inscricaoEstadoNovo);

        echo 'OK'; // Caso 'OK' seja enviado, significa que tudo ocorreu corretamente
        break;
    default :
        //ERRO - USO INESPERADO
        trigger_error("N�o foi poss�vel identificar \"$passo\" como uma a��o da funcionalide de editar notas", E_USER_ERROR);
        break;
}

function registrarLog($turma, $matAluno, $inscricaoEstadoAntigo, $inscricaoEstadoNovo) {

    $nomeAluno = $matAluno->getAluno()->getNome();
    $numMatriculaAluno = $matAluno->getMatriculaAluno();

    $siglaCurso = $turma->getSiglaCurso();
    $nomeCurso = $turma->getCurso()->getNomeCurso();
    $siglaPeriodoLetivo = $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo();
    $turno = $turma->getTurno();
    $gradeHorario = $turma->getGradeHorario();
    $siglaDisciplina = $turma->getSiglaDisciplina();
    $nomeDisciplina = $turma->getComponenteCurricular()->getNomeDisciplina();

    if($turma->getProfessor()){
        $nomeProfessor = $turma->getProfessor()->getNome();
    } else {
        $nomeProfessor = "Sem Professor";
    }

    $traco = utf8_decode("-");

    //Copia os dados das inscri��es para variaveis para que sejam melhor manipulados
    $medAnt = $inscricaoEstadoAntigo->getMediaFinal();
    $falAnt = $inscricaoEstadoAntigo->getTotalFaltas();
    $sitAnt = $inscricaoEstadoAntigo->getSituacaoInscricao();
    $parAnt = $inscricaoEstadoAntigo->getParecerInscricao();
    // --
    $medNov = $inscricaoEstadoNovo->getMediaFinal();
    $falNov = $inscricaoEstadoNovo->getTotalFaltas();
    $sitNov = $inscricaoEstadoNovo->getSituacaoInscricao();
    $parNov = $inscricaoEstadoNovo->getParecerInscricao();

    //Dados Antigos
    if($medAnt == NULL) {
        $medAnt = 'Ausente';
    } else {
        $medAnt = sprintf("%01.1f", $medAnt);
    }
    if($falAnt == NULL) {
        $falAnt = 'Ausente';
    } else {
        $falAnt = sprintf("%01.1f", $falAnt);
    }
    if($parAnt == NULL) {
        $parAnt = 'Ausente';
    }

    //Novos dados
    if($medNov == NULL) {
        $medNov = 'Ausente';
    } else {
        $medNov = sprintf("%01.1f", $medNov);
    }
    if($falNov == NULL) {
        $falNov = 'Ausente';
    } else {
        $falNov = sprintf("%01.1f", $falNov);
    }
    if($parNov == NULL) {
        $parNov = 'Ausente';
    }

    $mensagem = "Alterada a nota e/ou situa��o do aluno $nomeAluno, matr�cula $numMatriculaAluno da turma do curso ";
    $mensagem .= "$siglaCurso $traco $nomeCurso, Per�odo Letivo $siglaPeriodoLetivo, ";
    $mensagem .= "Turno $turno, Grade $gradeHorario, da disciplina $siglaDisciplina $traco $nomeDisciplina, ";
    $mensagem .= "Professor $nomeProfessor, ";
    $mensagem .= "de m�dia final $medAnt para $medNov, ";
    $mensagem .= "total de faltas de $falAnt para $falNov, ";
    $mensagem .= "e situa��o de $sitAnt para $sitNov, ";
    $mensagem .= "parecer de '$parAnt' para '$parNov'.";

    global $EDITAR_NOTAS_E_SITUACAO_DO_ALUNO_EM_TURMA;
    $_SESSION["usuario"]->incluirLog($EDITAR_NOTAS_E_SITUACAO_DO_ALUNO_EM_TURMA, $mensagem);
}
?>