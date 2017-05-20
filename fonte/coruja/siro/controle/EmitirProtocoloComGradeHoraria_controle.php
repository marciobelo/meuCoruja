<?php
// REQUIRE DO ARQUIVO COMUM
require_once("../../includes/comum.php");

// INCLUDE DA CLASSE DE INSCRICAO
include_once "$BASE_DIR/classes/Inscricao.php";

// INCLUDE DA CLASSE DE COMPONENTE CURRICULAR
include_once "$BASE_DIR/classes/ComponenteCurricular.php";

// INCLUDE DA CLASSE DE ALOCA
include_once "$BASE_DIR/classes/Aloca.php";

// INCLUDE DA CLASSE DE TURMA
include_once "$BASE_DIR/classes/Turma.php";

// INCLUDE DA CLASSE MATRICULA
include_once "$BASE_DIR/classes/MatriculaAluno.php";

// INCLUDE DA CLASSE TEMPOSEMANAL
include_once "$BASE_DIR/classes/TempoSemanal.php";

// INCLUDE DA CLASSE PERIODOLETIVO
include_once "$BASE_DIR/classes/PeriodoLetivo.php";

// INCLUDE DA CLASSE EVENTOPERIODOLETIVO
include_once "$BASE_DIR/classes/EventoPeriodoLetivo.php";

// CSS
echo"<link href='../estilos/tabelas.css' rel='stylesheet' type='text/css' />";
echo"<link href='../estilos/botoes.css' rel='stylesheet' type='text/css' />";


// TOPO DA PÁGINA
include_once "$BASE_DIR/includes/topo.php";


// Recupera o usuário logado da sessão
$usuario = $_SESSION["usuario"];



//VERIFICA SE E ALUNO
if(!MatriculaAluno::existeAluno($usuario->getIdPessoa())){

    $idPessoa=$_POST['idPessoa'];
    // MENU HORIZONTAL COMPLETO
    echo '<div id="menuprincipal">';
    include_once "$BASE_DIR/includes/menu_horizontal.php";
    echo '</div>';

    // Verifica Permissao
    if(!$usuario->temPermissao($EMITIR_PROTOCOLO_GRADE_HORARIA)) {
        require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
        exit;
    }

}
else{
    $idPessoa=$usuario->getIdPessoa();
    // MENU HORIZONTAL DO ALUNO
    echo '<div id="menuprincipal">';
    include_once "$BASE_DIR/includes/menu_horizontal.php";
    echo '</div>';
}

//  OBTEM A MATRICULA DO ALUNO LOGADO
$aMatriculasAluno = MatriculaAluno::obterMatriculasAlunoPorIdPessoa( $idPessoa);
$matriculaAluno = $aMatriculasAluno[0];

// OBTEM O PERIODO LETIVO ATUAL
$periodoLetivo = Periodoletivo::obterPeriodoLetivoAtual($matriculaAluno->getSiglaCurso());

//VERIFICA SE ESTA NO PERIODO DE SOLICITACAO
if(!EventoPeriodoLetivo::verificaEncerramentoInscricoes($periodoLetivo->getIdPeriodoLetivo())){
            require_once "$BASE_DIR/siro/formularios/permissao/periodoSolicitacoesEncerrado.php";
            include_once "$BASE_DIR/includes/rodape.php";
            exit();
}

// OBTEM AS TURMAS LIBERADAS
$condicaoTurmasLiberadas = "' and tipoSituacaoTurma = 'LIBERADA' and idPeriodoLetivo=".$periodoLetivo->getIdPeriodoLetivo()."";
$turmasLiberadas=Turma::obterTurmas("siglaCurso= '".$matriculaAluno->getSiglaCurso().$condicaoTurmasLiberadas);

// CRIA UM OBJETO DE INSCRICAO
$inscricao = new Inscricao();

//OBT?M AS INSCRICOES SOLICITADAS
$turmasSolicitadas=Inscricao::obterTurmasInscricoesAluno($matriculaAluno->getMatriculaAluno(), "REQ");

//CRIA UM OBJETO DE ALOCA
$aloca = new Aloca();

//OBT?M TODOS OS TEMPOS SEMANAIS, HORARIOS
$tempoS=TempoSemanal::lista_temposemanal("diaSemana = 'SEG' ");


//CONTE?DO
echo '<div id="conteudo">';

echo $_SESSION['a'];
$action = $_GET['action'];
// TÍTULO DA PAGINA


echo "<form name='imprimir' id='imprimir' action='../classes/EmitirProtocoloComGradeHorariaPDF.php' method='post'>";

$perLetivo = PeriodoLetivo::obterPeriodoLetivo($periodoLetivo->getIdPeriodoLetivo());

$classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
$aluno = Aluno::getAlunoByIdPessoa($matriculaAluno->getIdPessoa());

echo "<fieldset id='fieldsetGeral'>";
echo "<legend>Emitir Protocolo com Grade Horária</legend>";
echo"<b>Curso: ".$classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso();
echo "<br>Per&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().") <br />";
echo "Aluno: ".$matriculaAluno->getMatriculaAluno()." - ".$aluno->getNome()." - Turno: ".$matriculaAluno->getTurnoIngresso()." </b><br />";
echo"</fieldset>";

$identificao .="\nCurso: ".$classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso();
$identificao .="\nPer&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().") \n";
$identificao .="Aluno: ".$matriculaAluno->getMatriculaAluno()." - ".$aluno->getNome()." - Turno: ".$matriculaAluno->getTurnoIngresso()." \n";

require "$BASE_DIR/siro/formularios/gradeHoraria/gradeHorariaNovo.php";


$usuario = $_SESSION["usuario"];

$descricao = "Emitido o Protocolo com Grade Horária do Aluno ".$matriculaAluno->getMatriculaAluno()." - ".$aluno->getNome().
            " do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso().") ".
            "no Per&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().")";

//insere o log
$usuario->incluirLog($EMITIR_PROTOCOLO_GRADE_HORARIA,$descricao);

echo "<script>".
     "function imprimirGrade(){".
         "document.imprimir.target='_blank';".
         "document.imprimir.submit();".
    "}".
    "</script>";

echo"<br>";
echo "<input type='hidden' name='identificao' value='".$identificao."'>";
echo "<center><input class='confirmar' type='button' onclick = 'imprimirGrade();' align='center' value='  Imprimir  '  ></center>";
echo "</form>";

echo '</div>';


// RODAPÉ DA PÁGINA
include_once "$BASE_DIR/includes/rodape.php";

?>