<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/nort/classes/alunosPorTurma/AlunosPorTurmaPDF.php";

// Verifica Permissão
if(!$usuario->temPermissao($EMITIR_LISTAGEM_DE_ALUNOS_POR_TURMA)) 
{
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

$acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
if( $acao === NULL)
{
    $acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
    if( $acao === NULL)
    {
        $acao = 'buscarTurmasFiltro';
    }
}
$siglaCursoFiltro = Util::obterFiltroSiglaCurso();

switch ($acao) 
{
    case 'buscarTurmasFiltro':
    case 'buscarTurmasResultado':

        $arrayPeriodoLetivo = NULL;
        $arrayDisciplinas = NULL;
        $arrayProfessores = NULL;
        $estaDesabilitadoPeriodo = 'DISABLED';
        $estaDesabilitado = 'DISABLED';

        $arrayCursos = Curso::obterCursosOrdemPorSigla();
        if( $siglaCursoFiltro === "")
        {
            $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
        }
        else
        {
            $siglaCurso = $siglaCursoFiltro;
        }

        if( $siglaCurso !== NULL)
        {

            $estaDesabilitadoPeriodo = '';

            $arrayPeriodoLetivo = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso( $siglaCurso);

            $idPeriodoLetivo = filter_input( INPUT_POST, "idPeriodoLetivo", FILTER_SANITIZE_NUMBER_INT);
            $siglaDisciplina = filter_input( INPUT_POST, "siglaDisciplina", FILTER_SANITIZE_STRING);
            $idPessoaProfessor = filter_input( INPUT_POST, "idPessoaProfessor", FILTER_SANITIZE_NUMBER_INT);

            if( $idPeriodoLetivo !== NULL) 
            {
                $estaDesabilitado = '';

                // DISCIPLINAS
                $conDisciplinas = BD::conectar();
                $arraySiglaNomeDisciplina = array();
                $queryDisciplinas = sprintf("".
                    "select distinct ".
                    "    CC.`siglaDisciplina`, CC.`nomeDisciplina` ".
                    "from ".
                    "    Turma T, ComponenteCurricular CC ".
                    "where ".
                    "    T.`siglaCurso` = CC.`siglaCurso` ".
                    "    and T.`idMatriz` = CC.`idMatriz` ".
                    "    and T.`siglaDisciplina` = CC.`siglaDisciplina` ".
                    "    and CC.`siglaCurso` = '%s' ".
                    "    and T.`idPeriodoLetivo` = %d "
                        . "order by CC.siglaDisciplina",
                    mysql_real_escape_string($siglaCurso),
                    $idPeriodoLetivo);
                $resultDisciplinas = mysql_query( $queryDisciplinas, $conDisciplinas);
                while( $resCC = mysql_fetch_array( $resultDisciplinas) ) 
                {
                    $arraySiglaNomeDisciplina[$resCC['siglaDisciplina']] = $resCC['nomeDisciplina'];
                }

                // PROFESSORES
                $conProfessores = BD::conectar();
                $arrayIdNomeProfessor = array();
                $queryProfessores = sprintf(
                    "select distinct ".
                    "    P.`idPessoa`, P.nome ".
                    "from ".
                    "    Pessoa P ".
                    "inner join ".
                    "    MatriculaProfessor MP ".
                    "on ".
                    "    P.`idPessoa` = MP.`idPessoa` ".
                    "inner join ".
                    "    Turma T ".
                    "on ".
                    "    T.`matriculaProfessor` = MP.`matriculaProfessor` ".
                    "where ".
                    "    T.`siglaCurso` = '%s' ".
                    "    and T.`idPeriodoLetivo` = %d "
                        . "order by P.nome",
                    mysql_real_escape_string( $siglaCurso),
                    $idPeriodoLetivo);
                $resultProfessores = mysql_query( $queryProfessores, $conProfessores);
                while( $resPro = mysql_fetch_array( $resultProfessores) ) 
                {
                    $arrayIdNomeProfessor[$resPro['idPessoa']] = $resPro['nome'];
                }
            }
        }
        require "$BASE_DIR/nort/formularios/alunosPorTurma/emitirListaDeAlunosPorTurma_turmasFiltro.php";

        if ($acao !== "buscarTurmasResultado") 
        {
            require_once "$BASE_DIR/includes/rodape.php";
            break; //caso a ação seja 'buscarTurmasResultado', ele continua executando, e nao para neste break
        }

    case 'buscarTurmasResultado':
        
        $con = BD::conectar();

        $filtroPeriodoLetivo = " AND PL.`idPeriodoLetivo` = " . mysql_real_escape_string ($idPeriodoLetivo) . " ";
        $filtroCurso = " AND T.`siglaCurso` = '" . mysql_real_escape_string ($siglaCurso) . "' ";

        if( !empty( $siglaDisciplina))
        {
            $filtroDisciplina = " AND T.`siglaDisciplina` = '" . mysql_real_escape_string ( $siglaDisciplina) . "' ";
        }
        else
        {
            $filtroDisciplina = "";
        }

        if( !empty( $idPessoaProfessor))
        {
            $filtroProfessor = "AND MP.`idPessoa` = " . $idPessoaProfessor . " ";
        }
        else
        {
            $filtroProfessor = "";
        }

        $SQL_TURMAS =
                'SELECT DISTINCT T.`idTurma` , T.`siglaDisciplina` , CC.`nomeDisciplina`, P.`nome` ,  '
                . 'T.`turno`, T.`gradeHorario`, T.`tipoSituacaoTurma` , PL.`siglaPeriodoLetivo` '
                . 'FROM `ComponenteCurricular` CC, `PeriodoLetivo` PL, `Turma` T '
                . 'left join `MatriculaProfessor` MP '
                . 'on MP.`matriculaProfessor` = T.`matriculaProfessor` '
                . 'left join `Pessoa` P '
                . 'on MP.`idPessoa` = P.`idPessoa` '
                . 'WHERE PL.`idPeriodoLetivo` = T.`idPeriodoLetivo` '
                . 'AND CC.`siglaCurso` = T.`siglaCurso` '
                . 'AND CC.`idMatriz` = T.`idMatriz` '
                . 'AND CC.`siglaDisciplina` = T.`siglaDisciplina` '
                . "AND T.`tipoSituacaoTurma` in ('CONFIRMADA','FINALIZADA') "
                . $filtroPeriodoLetivo . chr(10)
                . $filtroCurso . chr(10)
                . $filtroDisciplina . chr(10)
                . $filtroProfessor . chr(10)
                . 'ORDER BY T.`tipoSituacaoTurma`, T.`siglaDisciplina`, T.`gradeHorario`, T.`turno` ASC';


        $result = mysql_query( $SQL_TURMAS);

        if (mysql_num_rows($result) == 0) 
        {
            ?><h4>Nenhuma turma encontrada</h4><?php
        } 
        else 
        {
            $arrayTurmas = array();
            while ($row = mysql_fetch_array($result)) 
            {
                $arrayTurmas[] = $row;
            }
            require "$BASE_DIR/nort/formularios/alunosPorTurma/emitirListaDeAlunosPorTurma_turmasResultado.php";
            require_once "$BASE_DIR/includes/rodape.php";
        }
        
        break;
        
    case 'gerarPDF':
        
        $turmas = filter_input( INPUT_POST, "arrayTurmas", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if( sizeof($turmas) > 0) 
        {
            registrarLog( $turmas);
            $pdf = gerarPDF($turmas);
            $pdf->Output();
            exit;
        } 
        else 
        {
            echo 'Nenhuma Turma Selecionada';
        }
        break;       
    default:
        //ERRO - USO INESPERADO
        trigger_error("Não foi possível identificar \"$passo\" como o próximo passo da funcionalide de emissão da lista de alunos por turma", E_USER_ERROR);
        break;
}

function registrarLog( $turmas) 
{

    $mensagem = "";

    foreach ($turmas as $idTurma) {


        $siglaCurso = null;
        $nomeCurso = null;
        $nomeDoProfessor = null;
        $descPeriodoLetivo = null;
        $siglaDaDisciplina = null;
        $nomeDaDisciplina = null;
        $turno = null;
        $gradeHorario = null;

        /* Semestre da turma */
        $SQL_PERIODO_LETIVO = 'select PL.`siglaPeriodoLetivo` '
                . 'from PeriodoLetivo PL, Turma T '
                . 'where T.`idTurma` = ' . $idTurma . ' '
                . ' and PL.`idPeriodoLetivo` = T.`idPeriodoLetivo`';
        $resultPeriodoLetivo = mysql_query($SQL_PERIODO_LETIVO);
        while ($row = mysql_fetch_array( $resultPeriodoLetivo, MYSQL_ASSOC)) 
        {
            $descPeriodoLetivo = $row['siglaPeriodoLetivo'];
        }

        /* Sigla Curso */
        $SQL_PERIODO = 'select Cu.`siglaCurso`, Cu.`nomeCurso` '
                . 'from Curso Cu, Turma T '
                . 'where T.idTurma = ' . $idTurma . ' '
                . 'and Cu.`siglaCurso` = T.`siglaCurso`';

        $resultSiglaCurso = mysql_query($SQL_PERIODO);
        while ($row = mysql_fetch_array( $resultSiglaCurso, MYSQL_ASSOC)) 
        {
            $siglaCurso = $row['siglaCurso'];
            $nomeCurso = $row['nomeCurso'];
        }

        /* Nome do professor */
        $SQL_NOME_DO_PROFESSOR = sprintf("select P.`nome` "
                        . "from Pessoa P "
                        . "where "
                        . "P.`idPessoa` in "
                        . "( "
                        . "   select MP.`idPessoa` "
                        . "   from MatriculaProfessor MP "
                        . "   where MP.`matriculaProfessor` in "
                        . "   ( "
                        . "      select T.`matriculaProfessor` "
                        . "      from Turma T "
                        . "      where T.`idturma` = '%s' "
                        . "   ) "
                        . ") ", mysql_real_escape_string($idTurma));
        $resultNomeProfessor = mysql_query($SQL_NOME_DO_PROFESSOR);
        while ($row = mysql_fetch_array( $resultNomeProfessor, MYSQL_ASSOC)) 
        {
            $nomeDoProfessor = $row['nome'];
        }

        /* Disciplina Da Turma */
        $SQL_NOME_DISCIPLINA = 'select CC.`siglaDisciplina`, CC.`nomeDisciplina` '
                . 'from ComponenteCurricular CC, Turma T '
                . 'where T.`idTurma` = ' . $idTurma . ' '
                . 'and T.siglaDisciplina = CC.`siglaDisciplina`';
        $resultDisciplina = mysql_query($SQL_NOME_DISCIPLINA);
        while ($row = mysql_fetch_array( $resultDisciplina, MYSQL_ASSOC)) {
            $siglaDaDisciplina = $row['siglaDisciplina'];
            $nomeDaDisciplina = $row['nomeDisciplina'];
        }

        /* Turno da turma e Grade de Horário */
        $SQL_TURNO_DA_TURMA = 'select turno, gradeHorario from Turma where `idTurma` = ' . $idTurma;
        $resultTurnoGrade = mysql_query($SQL_TURNO_DA_TURMA);
        while ($row = mysql_fetch_array( $resultTurnoGrade, MYSQL_ASSOC)) {
            $turno = $row['turno'];
            $gradeHorario = $row['gradeHorario'];
        }

        if ($mensagem != ""){
            $mensagem = $mensagem . '<br><br>' . Chr(10); //Quebra a linha para a próxima mensagem
        }

        if($nomeDoProfessor == NULL)
        {
            $semProfessor = " sem";
        }

        $mensagem = $mensagem . "Emitida a listagem de alunos da turma do curso " . $siglaCurso . utf8_decode(" - ") . $nomeCurso .
        " período letivo " . $descPeriodoLetivo . "," .
        " turno " . $turno . ", grade " . $gradeHorario . "," .
        " da disciplina $siglaDaDisciplina - $nomeDaDisciplina," .
        $semProfessor.
        " professor $nomeDoProfessor";

    }//Fim do Loop que percorre a lista com ID's das turmas
    $_SESSION["usuario"]->incluirLog('UC01.07.00', $mensagem);
}

function gerarPDF($turmas) {

    //instancia o PDF
    $pdf = new AlunosNaTurmaPDF();

    foreach ($turmas as $idTurma) 
    {
        $nomeDoProfessor = null;
        $anoDaTurma = 0;
        $semestreDaTurma = 0;
        $turno = null;
        $siglaDaDisciplina = null;
        $nomeDaDisciplina = null;
        $periodoDaDisciplina = null;
        $gradeHorario = null;

        //Obtendo os dados do cabeçalho (todos os dados exceto os alunos)

        /* Turno da turma e Grade de Horário */
        $SQL_TURNO_DA_TURMA = 'select turno, gradeHorario from Turma where `idTurma` = ' . $idTurma;

        $resultTurnoGrade = mysql_query($SQL_TURNO_DA_TURMA);
        while ($row = mysql_fetch_array( $resultTurnoGrade, MYSQL_ASSOC)) {
            $turno = $row['turno'];
            $gradeHorario = $row['gradeHorario'];
        }

        /* Nome do professor */
        $SQL_NOME_DO_PROFESSOR = sprintf("select P.`nome` "
                        . "from Pessoa P "
                        . "where "
                        . "P.`idPessoa` in "
                        . "( "
                        . "   select MP.`idPessoa` "
                        . "   from MatriculaProfessor MP "
                        . "   where MP.`matriculaProfessor` in "
                        . "   ( "
                        . "      select T.`matriculaProfessor` "
                        . "      from Turma T "
                        . "      where T.`idturma` = '%s' "
                        . "   ) "
                        . ") ", mysql_real_escape_string($idTurma));
        $resultNomeProf = mysql_query($SQL_NOME_DO_PROFESSOR);
        while ($row = mysql_fetch_array( $resultNomeProf, MYSQL_ASSOC)) {
            $nomeDoProfessor = $row['nome'];
        }

        /* Ano e Semestre da turma */
        $SQL_PERIODO_LETIVO = 'select PL.`siglaPeriodoLetivo` '
                . 'from PeriodoLetivo PL, Turma T '
                . 'where T.`idTurma` = ' . $idTurma . ' '
                . ' and PL.`idPeriodoLetivo` = T.`idPeriodoLetivo`';
        $resultPeriodoLetivo = mysql_query($SQL_PERIODO_LETIVO);
        while ($row = mysql_fetch_array( $resultPeriodoLetivo, MYSQL_ASSOC)) 
        {
            $descPeriodoLetivo = $row['siglaPeriodoLetivo'];
            $descPeriodoLetivoArray = explode(".", $descPeriodoLetivo);
            $anoDaTurma = $descPeriodoLetivoArray[0];
            $semestreDaTurma = $descPeriodoLetivoArray[1];
        }

        /* Disciplina Da Turma */
        $SQL_NOME_DISCIPLINA = 'select CC.`siglaDisciplina`, CC.`nomeDisciplina` '
                . 'from ComponenteCurricular CC, Turma T '
                . 'where T.`idTurma` = ' . $idTurma . ' '
                . 'and T.siglaDisciplina = CC.`siglaDisciplina`';
        $resultNomeDisciplina = mysql_query($SQL_NOME_DISCIPLINA);
        while ($row = mysql_fetch_array( $resultNomeDisciplina, MYSQL_ASSOC)) 
        {
            $siglaDaDisciplina = $row['siglaDisciplina'];
            $nomeDaDisciplina = $row['nomeDisciplina'];
        }

        /* Periodo da Disciplina, Quantidade de Aulas e Grade de Horário */
        $SQL_PERIODO = 'select CC.`siglaCurso`, CC.periodo, CC.cargaHoraria, T.gradeHorario '
                . 'from ComponenteCurricular CC, Turma T '
                . 'where T.idTurma = ' . $idTurma . ' '
                . 'and CC.`siglaCurso` = T.`siglaCurso` '
                . 'and CC.`idMatriz` = T.`idMatriz` '
                . 'and CC.`siglaDisciplina` = T.`siglaDisciplina`';

        $resultPeriodoDisciplina = mysql_query($SQL_PERIODO);
        while ($row = mysql_fetch_array( $resultPeriodoDisciplina, MYSQL_ASSOC)) 
        {
            $periodoDaDisciplina = $row['periodo'];
        }

        $pdf->redefinirDadosDoCabecalho($siglaDaDisciplina . ' - ' . $nomeDaDisciplina, $nomeDoProfessor,
                $anoDaTurma, $semestreDaTurma, $turno, $gradeHorario, $periodoDaDisciplina);
        $pdf->gerarCabecalho();

        //Obtendo os Alunos
        $SQL_ALUNOS_NA_TURMA =
                sprintf('
SELECT
    MA.`matriculaAluno`, P.`nome` as `nomeAluno`,
    I.`mediaFinal`, I.`totalFaltas`, I.`situacaoInscricao`
FROM
    Pessoa P, MatriculaAluno MA, Inscricao I
WHERE
    MA.`idPessoa` = P.`idPessoa`
    AND MA.`matriculaAluno` = I.`matriculaAluno`
    AND I.`idTurma` = %d
    AND I.`situacaoInscricao` IN ("ID","CUR","AP","RM","RF")
ORDER BY
    P.`nome`, MA.`matriculaAluno`',
                        mysql_real_escape_string($idTurma));
        $result = mysql_query($SQL_ALUNOS_NA_TURMA);
        $listaDeAlunos = array();
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            //$listaDeAlunos[] = new Aluno(0, $row['matriculaAluno'], $row['nomeAluno']);
            $insc = new Inscricao();
            $insc->setNomeAluno($row['nomeAluno']);
            $insc->setMatriculaAluno($row['matriculaAluno']);
            $insc->setMediaFinal($row['mediaFinal']);
            $insc->setTotalFaltas($row['totalFaltas']);
            $insc->setSituacaoInscricao($row['situacaoInscricao']);
            $listaDeAlunos[] = $insc;
        }
        $pdf->gerarLista($listaDeAlunos);
    }//Fim do loop da turma
    return $pdf;
}