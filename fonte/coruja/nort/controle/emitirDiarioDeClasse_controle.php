<?php
/*   UC 01.04.00
 *
 * Controlador responsável pela geração do Diário de Classe
 * Suas principais passos são
 * 1 - Utilizar o caso de uso de busca de turmas
 * 2 - Gerar o PDF com a listagem dos alunos
 * 3 - Exibir o documento em uma nova janela
 */
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/nort/classes/diarioDeClasse/DiarioDeClassePDF.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/Inscricao.php";


$acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
if( $acao === NULL)
{
    $acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
    if( $acao === NULL) 
    {
        $acao = 'buscarTurmasFiltro';
    }
}

if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCursoFiltro = "";
}

switch( $acao) 
{
    case 'buscarTurmasFiltro':
    case 'buscarTurmasResultado':

        if(!$login->temPermissao($EMITIR_DIARIO_DE_CLASSE)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
            exit();
        }
        
        //MOSTRA O FILTRO DA SELECÃO DE TURMAS
        $Par_BuscarTurmas_Titulo = 'Lista de Alunos por Turma';
        $Par_BuscarTurmas_ProximaAcao = 'gerarPDF';

        $arrayPeriodoLetivo = NULL;
        $arrayDisciplinas = NULL;
        $arrayProfessores = NULL;
        $estaDesabilitadoPeriodo = 'DISABLED';
        $estaDesabilitado = 'DISABLED';

        $arrayCursos = Curso::obterCursosOrdemPorSigla();
 
        if( $siglaCursoFiltro !== "")
        {
            $siglaCurso = $siglaCursoFiltro;
        }
        else
        {
            
            $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
        }
        
        if( $siglaCurso !== NULL)
        {

            $estaDesabilitadoPeriodo = '';

            $arrayPeriodoLetivo = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso( $siglaCurso);

            if( filter_input( INPUT_POST, "idPeriodoLetivo", FILTER_SANITIZE_NUMBER_INT) !== NULL) 
            {

                $estaDesabilitado = '';

                $con = BD::conectar();
                $arraySiglaNomeDisciplina = array();
                $query = sprintf("".
                    "select distinct ".
                    "    CC.`siglaDisciplina`, CC.`nomeDisciplina` ".
                    "from ".
                    "    Turma T, ComponenteCurricular CC ".
                    "where ".
                    "    T.`siglaCurso` = CC.`siglaCurso` ".
                    "    and T.`idMatriz` = CC.`idMatriz` ".
                    "    and T.`siglaDisciplina` = CC.`siglaDisciplina` ".
                    "    and CC.`siglaCurso` = '%s' ".
                    "    and T.`idPeriodoLetivo` = %d ",
                    mysql_real_escape_string($_POST['siglaCurso']),
                    $_POST['idPeriodoLetivo']);
                $result = mysql_query($query,$con);
                while( $resCC = mysql_fetch_array($result) ) {
                    $arraySiglaNomeDisciplina[$resCC['siglaDisciplina']] = $resCC['nomeDisciplina'];
                }

                // PROFESSORES
                $con = BD::conectar();
                $arrayIdNomeProfessor = array();
                $query = sprintf(
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
                    "    and T.`idPeriodoLetivo` = %d ",
                    mysql_real_escape_string($_POST['siglaCurso']),
                    $_POST['idPeriodoLetivo']);
                $result=mysql_query($query,$con);
                while( $resPro = mysql_fetch_array($result) ) {
                    $arrayIdNomeProfessor[$resPro['idPessoa']] = $resPro['nome'];
                }
            }
        }

        require "$BASE_DIR/nort/formularios/diarioDeClasse/emitirDiarioDeClasse_turmasFiltro.php";
        if ($acao != 'buscarTurmasResultado') {
            require_once "$BASE_DIR/includes/rodape.php";
            break; //caso a ação seja 'buscarTurmasResultado', ele continua executando, e nao para neste break
        }
        
    case 'buscarTurmasResultado':
        
        if (!$login->temPermissao($EMITIR_DIARIO_DE_CLASSE)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
            exit();
        }

        //MOSTRA OS RESULTAODOS PARA QUE O USUÁRIO SELECIONE AS TURMAS
        $con = BD::conectar();

        //FILTROS ESPECIFICOS
        // PERIODO LETIVO
        if ($_POST['idPeriodoLetivo'])
            $filtroPeriodoLetivo = ' AND PL.`idPeriodoLetivo` = ' . mysql_real_escape_string ($_POST['idPeriodoLetivo']) . ' ';
        else
            $filtroPeriodoLetivo = '';

        // CURSO
        if ($_POST['siglaCurso'])
            $filtroCurso = ' AND T.`siglaCurso` = "' . mysql_real_escape_string ($_POST['siglaCurso']) . '" ';
        else
            $filtroCurso = '';

        // DISCIPLINA
        if ($_POST['siglaDisciplina'])
            $filtroDisciplina = ' AND T.`siglaDisciplina` = "' . mysql_real_escape_string ($_POST['siglaDisciplina']) . '" ';
        else
            $filtroDisciplina = '';

        // PROFESSOR
        if ($_POST['idPessoaProfessor'])
            $filtroProfessor = 'AND MP.`idPessoa` = ' . mysql_real_escape_string ($_POST['idPessoaProfessor']) . ' ';
        else
            $filtroProfessor = '';

        //COMANDO SQL FORMADO COM OS FILTROS
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

        $result = mysql_query($SQL_TURMAS);

        if (mysql_num_rows($result) == 0) {
            ?><h4>Nenhuma turma encontrada</h4><?php
        } else {
            $arrayTurmas = array();
            while ($row = mysql_fetch_array($result)) {
                $arrayTurmas[] = $row;
            }
            require "$BASE_DIR/nort/formularios/diarioDeClasse/emitirDiarioDeClasse_turmasResultado.php";
            require_once "$BASE_DIR/includes/rodape.php";
        }
        break;
        
    case 'gerarPDF':
        
        if (!$login->temPermissao($EMITIR_DIARIO_DE_CLASSE)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
            exit();
        }        
        
        $turmas = $_POST['arrayTurmas'];
        if (sizeof($turmas) > 0) {

            //Gera desenha todo o documento e o salva em uma variavel, mas ainda nao o exibe
            $pdf = gerarPDF($turmas);

            //Salva o documento na sessão do usuário
            $_SESSION['FPDF'] = $pdf;

            registrarLog($turmas);

            // MOSTRA PÁGINA DE EMISSÃO DE LISTAGEM DE ALUNOS POR TURMA
            require "$BASE_DIR/nort/formularios/diarioDeClasse/emitirDiarioDeClasse_gerarPDF.php";
            require_once "$BASE_DIR/includes/rodape.php";
        } else {
            echo 'Nenhuma Turma Selecionada';
        }
        break;
    
    case "gerarDiarioProfessor":
        
        $idTurma = $_GET["idTurma"];
        $turma = Turma::getTurmaById($idTurma);
        $professor = $turma->getProfessor();
        // Verifica se o professor é o titular da turma informada
        if( !$turma->isPodeEditarPauta($login) ) {
            echo "Usuário não tem permissão para executar essa ação!";
            exit;
        }
        
        $turmas = array();
        $turmas[] = $idTurma;
        $pdf = gerarPDF($turmas);
        $hoje = new DateTime();
        $nomeRelatorio = "DiarioClasse-" . 
                $turma->getSiglaPeriodoLetivo() . "-" .
                $turma->getSiglaDisciplina() . "-" .
                $turma->getGradeHorario() . "-" .
                $turma->getTurno() . "-" .
                ($hoje->format("d-m-Y") . ".pdf");
        $pdf->Output($nomeRelatorio, "I");
        exit;
        
    case 'exibirPDF':
        /*O comum.php iniciou a sessão antes de ter carregado a Classe do PDF,
         * então devemos fechala e reabrila agora que a classe ja foi carregada
        */
        session_write_close();
        session_start();
        $_SESSION['FPDF']->Output();
        break;
    default:
        //ERRO - USO INESPERADO
        trigger_error("Não foi possível identificar \"$passo\" como o próximo passo da funcionalide de emissão da lista de alunos por turma", E_USER_ERROR);
        break;
}


function registrarLog($turmas) {

    $con = BD::conectar();

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
        $result = mysql_query($SQL_PERIODO_LETIVO);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $descPeriodoLetivo = $row['siglaPeriodoLetivo'];
        }

        /* Sigla Curso */
        $SQL_PERIODO = 'select Cu.`siglaCurso`, Cu.`nomeCurso` '
                . 'from Curso Cu, Turma T '
                . 'where T.idTurma = ' . $idTurma . ' '
                . 'and Cu.`siglaCurso` = T.`siglaCurso`';

        $result = mysql_query($SQL_PERIODO);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
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
                        . "   from matriculaprofessor MP "
                        . "   where MP.`matriculaProfessor` in "
                        . "   ( "
                        . "      select T.`matriculaProfessor` "
                        . "      from Turma T "
                        . "      where T.`idturma` = '%s' "
                        . "   ) "
                        . ") ", mysql_real_escape_string($idTurma));
        $result = mysql_query($SQL_NOME_DO_PROFESSOR);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $nomeDoProfessor = $row['nome'];
        }

        /* Disciplina Da Turma */
        $SQL_NOME_DISCIPLINA = 'select CC.`siglaDisciplina`, CC.`nomeDisciplina` '
                . 'from ComponenteCurricular CC, turma T '
                . 'where T.`idTurma` = ' . $idTurma . ' '
                . 'and T.siglaDisciplina = CC.`siglaDisciplina`';
        $result = mysql_query($SQL_NOME_DISCIPLINA);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $siglaDaDisciplina = $row['siglaDisciplina'];
            $nomeDaDisciplina = $row['nomeDisciplina'];
        }


        /* Turno da turma e Grade de Horário */
        $SQL_TURNO_DA_TURMA = 'select turno, gradeHorario from Turma where `idTurma` = ' . $idTurma;


        $result = mysql_query($SQL_TURNO_DA_TURMA);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $turno = $row['turno'];
            $gradeHorario = $row['gradeHorario'];
        }

        if ($mensagem != ""){
            $mensagem = $mensagem . '<br><br>' . Chr(10); //Quebra a linha para a próxima mensagem
        }

        if($nomeDoProfessor == NULL){
            $semProfessor = " sem";
        }

        $mensagem = $mensagem . "Emitido o diário de classe da turma do curso " . $siglaCurso . utf8_decode(" - ") . $nomeCurso .
        " período letivo " . $descPeriodoLetivo . "," .
        " turno " . $turno . ", grade " . $gradeHorario . "," .
        " da disciplina $siglaDaDisciplina " . utf8_decode(" - ") . " $nomeDaDisciplina," .
        $semProfessor.
        " professor $nomeDoProfessor";

    }//Fim do Loop que percorre a lista com ID's das turmas
    $_SESSION["login"]->incluirLog('UC01.04.00', $mensagem);
}

function gerarPDF($turmas) {

    $con = BD::conectar();

    if(sizeof($turmas) > 0) {

        $con = BD::conectar();

        //instancia o PDF
        $hoje = new DateTime();
        $pdf = new DiarioDeClassePDF();

        foreach($turmas as $idTurma) {

            //Dados do cabeçalho
            $nomeDoProfessor = null;
            $anoDaTurma = 0;
            $semestreDaTurma = 0;
            $turno = null;
            $siglaDaDisciplina = null;
            $nomeDaDisciplina = null;
            $periodoDaDisciplina = null;
            $quantidadeDeAulas = 0;
            $gradeHorario = null;

            //dados da lista
            $matriculasAluno;

            $SQL_TURMA_NOME_DISCIPLINA_E_QTD_AULAS;

            //Obtendo os dados do cabeçalho (todos os dados exceto os alunos)

            /* Turno da turma e Grade de Horário*/
            $SQL_TURNO_DA_TURMA = 'select turno, gradeHorario from Turma where `idTurma` = '.$idTurma;


            $result = mysql_query($SQL_TURNO_DA_TURMA);
            while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                $turno = $row['turno'];
                $gradeHorario = $row['gradeHorario'];
            }

            /* Nome do professor */
            $SQL_NOME_DO_PROFESSOR = sprintf("select P.`nome` "
            ."from Pessoa P "
            ."where "
            ."P.`idPessoa` in "
            ."( "
            ."   select MP.`idPessoa` "
            ."   from MatriculaProfessor MP "
            ."   where MP.`matriculaProfessor` in "
            ."   ( "
            ."      select T.`matriculaProfessor` "
            ."      from Turma T "
            ."      where T.`idturma` = '%s' "
            ."   ) "
            .") ", mysql_real_escape_string($idTurma));
            $result = mysql_query($SQL_NOME_DO_PROFESSOR);
            while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                $nomeDoProfessor = $row['nome'];
            }

            /* Ano e Semestre da turma */
            $SQL_PERIODO_LETIVO = 'select PL.`siglaPeriodoLetivo`
from PeriodoLetivo PL, Turma T
where T.`idTurma` = '.$idTurma.'
and PL.`idPeriodoLetivo` = T.`idPeriodoLetivo`';

            $result = mysql_query($SQL_PERIODO_LETIVO);
            while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {

                $descPeriodoLetivo = $row['siglaPeriodoLetivo'];
                $descPeriodoLetivoArray = explode(".", $descPeriodoLetivo);
                $anoDaTurma = $descPeriodoLetivoArray[0];
                $semestreDaTurma = $descPeriodoLetivoArray[1];

            }

            /* Disciplina Da Turma */
            $SQL_NOME_DISCIPLINA = 'select CC.`siglaDisciplina`, CC.`nomeDisciplina`
from ComponenteCurricular CC, Turma T
where T.`idTurma` = '.$idTurma.'
and T.siglaCurso = CC.`siglaCurso`
and T.idMatriz = CC.`idMatriz`
and T.siglaDisciplina = CC.`siglaDisciplina`';


            $result = mysql_query($SQL_NOME_DISCIPLINA);
            while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                $nomeDaDisciplina = $row['nomeDisciplina'];
                $siglaDaDisciplina = $row['siglaDisciplina'];
            }

            /* Periodo da Disciplina, Quantidade de Aulas e Grade de Horário*/
            $SQL_CREDITOS = 'select CC.periodo, CC.cargaHoraria, T.gradeHorario
from ComponenteCurricular CC, Turma T
where T.idTurma = '.$idTurma.'
and CC.`siglaCurso` = T.`siglaCurso`
and CC.`idMatriz` = T.`idMatriz`
and CC.`siglaDisciplina` = T.`siglaDisciplina`';

            $result = mysql_query($SQL_CREDITOS);
            while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                $quantidadeDeAulas = $row['cargaHoraria'];
                $periodoDaDisciplina = $row['periodo'];
            }

            //Obtendo os Alunos
            $SQL_ALUNOS_NA_TURMA =
                sprintf("
SELECT
    MA.`matriculaAluno`, P.`nome` as `nomeAluno`,
    I.`mediaFinal`, I.`totalFaltas`, I.`situacaoInscricao`
FROM
    Pessoa P, MatriculaAluno MA, Inscricao I
WHERE
    MA.`idPessoa` = P.`idPessoa`
    AND MA.`matriculaAluno` = I.`matriculaAluno`
    AND I.`idTurma` = %d
    AND (I.`situacaoInscricao` IN ('CUR','AP','RM','RF')
        or (I.`situacaoInscricao`= 'REQ' AND I.`parecerInscricao`='%s') )
ORDER BY
    P.`nome`, MA.`matriculaAluno`",
            mysql_real_escape_string($idTurma),
            Inscricao::RECLAMADO_PELO_PROFESSOR);
            $result = mysql_query($SQL_ALUNOS_NA_TURMA);
            $matriculasAluno = Array();
            while( $row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                //$listaDeAlunos[] = new Aluno(0,$row['matriculaAluno'],Util::formataNome($row['nomeAluno']));
                //$listaDeAlunos[] = Aluno::getAlunoByNumMatricula( $row['matriculaAluno']);
                $matriculasAluno[] = MatriculaAluno::obterMatriculaAluno( $row['matriculaAluno']);
            }
            
            $turma = Turma::getTurmaById($idTurma);
            $datasDiaLetivo = $turma->obterDatasDiaLetivo();
            $diasLetivoTurma = array();          
            foreach($datasDiaLetivo as $dataDiaLetivo) {
                $diaLetivoTurma = $turma->obterDiaLetivoTurmaPorData($dataDiaLetivo);
                if( $diaLetivoTurma->getDataLiberacao() != null ) {
                    $diasLetivoTurma[] = $diaLetivoTurma;
                }
            }
            
            $qtdeTempos = 0;
            $diasLetivoTurmaPagina = array();
            $qntPaginasCoberturaTempos = calcularQntPaginasCoberturaTempos($diasLetivoTurma, DiarioDeClassePDF::QTDE_COLUNAS_TEMPO);
            foreach($diasLetivoTurma as $diaLetivoTurma) {
                if( ($qtdeTempos + $diaLetivoTurma->getQtdeTempos()) > DiarioDeClassePDF::QTDE_COLUNAS_TEMPO ) {
                    $pdf->redefinirDadosDoCabecalho($siglaDaDisciplina, $nomeDaDisciplina,$nomeDoProfessor,
                        $anoDaTurma, $semestreDaTurma, $turno, $gradeHorario, $periodoDaDisciplina, $quantidadeDeAulas, 
                            $diasLetivoTurma, $diasLetivoTurmaPagina, $qntPaginasCoberturaTempos);
                    $pdf->gerarLista($matriculasAluno, $turma, $diasLetivoTurmaPagina);
                    $diasLetivoTurmaPagina = array();
                    $qtdeTempos = 0;
                }
                $diasLetivoTurmaPagina[] = $diaLetivoTurma;
                $qtdeTempos += $diaLetivoTurma->getQtdeTempos();
            }
            $pdf->redefinirDadosDoCabecalho($siglaDaDisciplina, $nomeDaDisciplina,$nomeDoProfessor,
                $anoDaTurma, $semestreDaTurma, $turno, $gradeHorario, $periodoDaDisciplina, $quantidadeDeAulas, 
                    $diasLetivoTurma, $diasLetivoTurmaPagina, $qntPaginasCoberturaTempos);
            $pdf->gerarLista($matriculasAluno, $turma, $diasLetivoTurmaPagina);
            $pdf->gerarFolhaNotas( $turma );
            $pdf->gerarFolhaConteudo($diasLetivoTurma);
        } 

        return $pdf;
    } else {
        echo 'Nenhuma Turma Selecionada';
    }    
}

function calcularQntPaginasCoberturaTempos(array $diasLetivoTurma, int $temposPorPagina) {
    $qtdePaginas = 1;
    $qtdeTempos = 0;
    foreach($diasLetivoTurma as $diaLetivoTurma) {
        if( ($qtdeTempos + $diaLetivoTurma->getQtdeTempos()) > DiarioDeClassePDF::QTDE_COLUNAS_TEMPO ) {
            $qtdeTempos = 0;
            $qtdePaginas++;
        }
        $qtdeTempos += $diaLetivoTurma->getQtdeTempos();
    }
    return $qtdePaginas;
}
?>