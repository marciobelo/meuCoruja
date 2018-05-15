<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/MatriculaProfessor.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/Espaco.php";
require_once "$BASE_DIR/nort/includes/manterTurmas_obterMatrizAlocacoes.php";
require_once "$BASE_DIR/nort/includes/manterTurmas_validacoes.php";

// Verifica Permiss�o
if(!$login->temPermissao($CRIAR_TURMA)) {
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

//A��o Inicial Padr�o
if ($_REQUEST["acao"] == NULL){
    $_REQUEST["acao"] = 'principal';
}

$acao = $_REQUEST["acao"];

switch ($acao) {

    case 'principal':

        //Informa��es que ser�o necess�rias independente da a��o
        $curso = Curso::obterCurso($_POST['siglaCurso']);
        $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($_POST['idPeriodo']);
        $matrizCurricularAtual = MatrizCurricular::obterMatrizCurricularAtual($_POST['siglaCurso']);
        
        if( $matrizCurricularAtual == null ) {
            $listaDeCursos = Curso::obterCursosOrdemPorSigla();
            $siglaCurso = $_POST['siglaCurso'];
            $listaDePeriodosLetivos = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso($siglaCurso);
            $listaDeTurnos = array('TODOS','MANH�','TARDE','NOITE');
            $msgs = array();
            $msgs[] = "O curso n�o tem matriz curricular vigente";
            include "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_buscarTurmas.php";
            require_once "$BASE_DIR/includes/rodape.php";
        break;            
        }
        
        $listaDeMatrizCurricular = MatrizCurricular::obterListaMatrizCurricularPorSiglaCurso($curso->getSiglaCurso()); 
        $listaDeDisciplinas = $matrizCurricularAtual->obterComponentesCurriculares();
        $listaDeTurnos = array('MANH�','TARDE','NOITE');
        $listaDeGrades = array('A','B','C','D','E');
        $listaDeMatProfessores = MatriculaProfessor::obterTodasMatriculasProfessorVigentes();
        $listaDeEspacos = Espaco::obterEspacos();

        require "$BASE_DIR/nort/formularios/criarTurma/criarTurma_obterDados.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;
    case 'listaComponentesCurricularesAJAX':
        header("Content-Type: text/html;  charset=ISO-8859-1",true);
        $auxMC = MatrizCurricular::obterMatrizCurricular($_POST['siglaCurso'], $_POST['idMatriz']);
        $listaDeDisciplinas = $auxMC->obterComponentesCurriculares();
        echo '<option></option>';
        foreach ($listaDeDisciplinas as $disciplina) {
            echo '<option value="' . $disciplina->getSiglaDisciplina() . '" >' . $disciplina->getSiglaDisciplina() . ' - ' . $disciplina->getNomeDisciplina() . '</option>';
        }
        break;
    case 'gradeDeHorarioAJAX':
        header("Content-Type: text/html;  charset=ISO-8859-1",true);
        /*
         * Observa��o importante.
         * existem dois periodos, o periodo letivo e o periodo do componente curricular
         */

        $siglaCurso = $_POST['siglaCurso'];
        $cc = ComponenteCurricular::obterComponenteCurricular($siglaCurso, $_POST['idMatriz'], $_POST['siglaDisciplina']);

        $matrizTempos = obterMatrizAlocacoes( $siglaCurso, $_POST['idPeriodoLetivo'], $cc->getPeriodo(), utf8_decode( $_POST['turno'] ), $_POST['gradeHorario'] );

        foreach ($matrizTempos as $diaDaSemana => $auxValue) {
            foreach ($matrizTempos[$diaDaSemana] as $tempo => $auxValue2) {
                if (!$matrizTempos[$diaDaSemana][$tempo]['siglaDisciplina']){ //Se n�o houver disciplina, entao este tempo esta livre
                    $matrizTempos[$diaDaSemana][$tempo]['espacosLivres'] = obterEspacosDisponiveis($matrizTempos[$diaDaSemana][$tempo]['idTempoSemanal'], $_POST['idPeriodoLetivo']);
                }
            }
        }

        require "$BASE_DIR/nort/formularios/criarTurma/criarTurma_gradeHorario.php";

        break;


    case "inserirRegistroAJAX":
        header("Content-Type: text/html;  charset=ISO-8859-1",true);
        // Verifica se os espa�os escolhidos estao disponiveis naquele determinado tempo semanal
        // Verifica se o tempo semanal j� foi ocupado por alguma outra disciplina de mesmo periodo
        // Cria a turma
        // Cria suas aloca��es
        // Registra Log
       
        //$siglaCurso = mysql_real_escape_string($_POST['siglaCurso']);
        $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
        $idMatriz = filter_input( INPUT_POST, "idMatriz", FILTER_SANITIZE_NUMBER_INT);
        $siglaDisciplina = filter_input( INPUT_POST, "siglaDisciplina", FILTER_SANITIZE_STRING);
        $gradeHorario = filter_input( INPUT_POST, "gradeHorario", FILTER_SANITIZE_STRING);
        $turno = utf8_decode( filter_input( INPUT_POST, "turno", FILTER_SANITIZE_STRING));
        //mysql_real_escape_string(utf8_decode($_POST['turno'])); // Corrige codifica��o (acentua��o)
        $idPeriodoLetivo = filter_input( INPUT_POST, "idPeriodoLetivo", FILTER_SANITIZE_NUMBER_INT);
        $qtdeTotal = filter_input( INPUT_POST, "qtdeTotal", FILTER_SANITIZE_NUMBER_INT);
        
        // Valida se j� n�o h� turma, n�o cancelada, na mesma grade
        if( Turma::existeTurmaNaoCancelada($siglaCurso,$idMatriz,
                $siglaDisciplina,$gradeHorario,$turno,$idPeriodoLetivo) ) {
            echo "JA_EXISTE_TURMA_NAO_CANCELADA_NA_GRADE"; // resposta FLAG para o javascript em criarTurma_obterDados
            exit;
        }
        
        $con = BD::conectar();
        try {
            //Abre Transa��o
            mysql_query("BEGIN", $con);

            //O clique(aspas simples) � ajustado fora do SQL (NULL / 'matricula')
            if ($_POST['matriculaProfessor'] == "") { //Sem professor
                $matProf = "NULL";
            } else { //Com professor
                $matProf = "'" . mysql_real_escape_string($_POST['matriculaProfessor']) . "'";
            }
            $query = sprintf("" .
                            "insert into Turma " .
                            "(`siglaCurso`,`idMatriz`,`siglaDisciplina`, " .
                            "`gradeHorario`,`idPeriodoLetivo`,`matriculaProfessor`, " .
                            "`turno`,`tipoSituacaoTurma`,`qtdeTotal`) " .
                            "values " .
                            "('%s',%d,'%s'," .
                            "'%s',%d,%s," .
                            "'%s','PLANEJADA',%d)"
                            , $siglaCurso, $idMatriz, $siglaDisciplina,
                            $gradeHorario, $idPeriodoLetivo, $matProf,
                            $turno, $qtdeTotal );

            // INSERE A TURMA
            $ok = mysql_query($query, $con);
            if(!$ok) throw new Exception("Erro ao inserir nova turma");
            $novoIdTurma = mysql_insert_id($con);

            foreach ($_POST as $nameForm => $valueForm) {
                if (substr($nameForm, 0, 13) == 'tempoSemanal-') {
                    if ($valueForm != "") {
                        $idTempoSemanal = substr($nameForm, 13);
                        $idEspaco = $valueForm;

                        //VALIDA CONFLITO DE HOR�RIO DISPON�VEL DO PROFESSOR (1 professor nao pode dar 2 aulas ao mesmo tempo)
                        if ($_POST['matriculaProfessor'] != ""){
                            if ( ! professorEstaDisponivel($_POST['matriculaProfessor'], $idTempoSemanal, $_POST['idPeriodoLetivo'])){
                                throw new Exception("Conflito de aloca��o de professor no tempo:$idTempoSemanal");
                            }
                        }
                        
                        // QUERY DE INSER��O
                        $query = sprintf("" .
                                        "insert into Aloca (idTurma, idTempoSemanal, idEspaco) " .
                                        "values (%d,%d,%d)",
                                        mysql_real_escape_string($novoIdTurma),
                                        mysql_real_escape_string($idTempoSemanal),
                                        mysql_real_escape_string($idEspaco)
                        );
                        // INSERE AS ALOCA��ES DA TURMA
                        $ok = mysql_query($query, $con);
                        if(!$ok) throw new Exception("Erro ao inserir aloca��o de turma");
                    }
                }
            }

            registrarLog(Turma::getTurmaById($novoIdTurma));
            
            mysql_query("COMMIT", $con);

            echo 'OK';
        } catch (Exception $ex){
            mysql_query("ROLLBACK", $con);
            echo $ex->getMessage();
        }

        break;
    default:
        //ERRO - USO INESPERADO
        trigger_error("N�o foi poss�vel identificar \"$passo\" como o pr�ximo passo da funcionalide de cria��o de turma", E_USER_ERROR);
        break;
}

function registrarLog($turma){

// Exemplo
//    Criada a turma do curso TASI (Tecnologia em Sistemas Informatizados),
//            no Per�odo Letivo 2011.1, disciplina AL1 ? Algoritmos e Linguagens
//            de Programa��o I na matriz curricular vigente desde 01/01/2006,
//            Turno NOITE, Grade A, Professor Sem Professor Alocado,
//            com as aloca��es (SEG 21 Sala1, SEG 3 Sala 1,...)
    
            
    $siglaCurso = $turma->getSiglaCurso();
    $nomeCurso = $turma->getCurso()->getNomeCurso();
    $siglaPeriodoLetivo = $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo();
    $siglaDisciplina = $turma->getSiglaDisciplina();
    $nomeDisciplina = $turma->getComponenteCurricular()->getNomeDisciplina();
    $dataInicioMatriz = Util::dataSQLParaBr(MatrizCurricular::obterMatrizCurricular($turma->getSiglaCurso(), $turma->getIdMatriz())->getDataInicioVigencia());
    $turno = $turma->getTurno();
    $gradeHorario = $turma->getGradeHorario();
    
    $alocacoes = $turma->getAlocacoes();

    if($turma->getProfessor()){
        $nomeProfessor = $turma->getProfessor()->getNome();
    } else {
        $nomeProfessor = "Sem Professor Alocado";
    }

    $traco = utf8_decode("-");

    $mensagem = "Criada a turma do curso $siglaCurso $traco $nomeCurso, ";
    $mensagem .= "no per�odo letivo $siglaPeriodoLetivo, ";
    $mensagem .= "disciplina $siglaDisciplina $traco $nomeDisciplina, ";
    $mensagem .= "na matriz curricular vigente desde $dataInicioMatriz, ";
    $mensagem .= "Turno $turno, Grade $gradeHorario, ";
    $mensagem .= "Professor $nomeProfessor, ";
    
    $mensagem .= "com as aloca��es (";
    
    foreach ($alocacoes as $aloca) {
        $mensagem .= $aloca->getTempoSemanal()->getDiaSemana().' ';
        $mensagem .= $aloca->getTempoSemanal()->obterTempoOrdinalDoTurno().' ';
        $mensagem .= $aloca->getEspaco()->getNome().', ';
    }
    
    $mensagem .= ")";

    global $CRIAR_TURMA;
    $_SESSION["login"]->incluirLog($CRIAR_TURMA, $mensagem);
}
