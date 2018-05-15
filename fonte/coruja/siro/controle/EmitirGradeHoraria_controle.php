<?php
// REQUIRE DO ARQUIVO COMUM
require_once("../../includes/comum.php");
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/Aloca.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/TempoSemanal.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";

// CSS
echo"<link href='../estilos/tabelas.css' rel='stylesheet' type='text/css' />";
echo"<link href='../estilos/botoes.css' rel='stylesheet' type='text/css' />";

// TOPO DA PÁGINA
include_once "$BASE_DIR/includes/topo.php";

//VERIFICA SE E ALUNO
if(!MatriculaAluno::existeAluno($login->getIdPessoa())){

    $idPessoa=$_POST['idPessoa'];
    // MENU HORIZONTAL COMPLETO
    echo '<div id="menuprincipal">';
    include_once "$BASE_DIR/includes/menu_horizontal.php";
    echo '</div>';

    // Verifica Permissao
    if(!$login->temPermissao($EMITIR_PROTOCOLO_GRADE_HORARIA)) {
        require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
        exit;
    }

}
else
{
    $idPessoa = $login->getIdPessoa();
    // MENU HORIZONTAL DO ALUNO
    echo '<div id="menuprincipal">';
    include_once "$BASE_DIR/includes/menu_horizontal.php";
    echo '</div>';
}

//  OBTEM A MATRICULA DO ALUNO LOGADO
$aMatriculasAluno = MatriculaAluno::obterMatriculasAlunoPorIdPessoa( $idPessoa);
$matriculaAluno = $aMatriculasAluno[0];
        
// OBTEM O PERIODO LETIVO ATUAL
try {
    $periodoLetivo = Periodoletivo::obterPeriodoLetivoAtual($matriculaAluno->getSiglaCurso());
} catch (Exception $ex) {
    echo $ex->getMessage();
    include_once "$BASE_DIR/includes/rodape.php";
    exit;

}


//CONTE?DO
echo '<div id="conteudo">';

echo $_SESSION['a'];
$action = $_GET['action'];
// TÍTULO DA PAGINA


echo "<form name='imprimir' id='imprimir' action='../classes/EmitirGradeHorariaPDF.php' method='post'>";

$perLetivo = PeriodoLetivo::obterPeriodoLetivo($periodoLetivo->getIdPeriodoLetivo());

$classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
$aluno = Aluno::getAlunoByIdPessoa($matriculaAluno->getIdPessoa());

echo "<fieldset id='fieldsetGeral'>";
echo "<legend>Emitir Grade Hor&aacute;ria do Per&iacute;odo Letivo Vigente</legend>";
echo"<b>Curso: ".$classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso();
echo "<br>Per&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().") <br />";
echo "Aluno: ".$matriculaAluno->getMatriculaAluno()." - ".$aluno->getNome()." - Turno: ".$matriculaAluno->getTurnoIngresso()." </b><br />";
echo"</fieldset>";

$identificao .="\nCurso: ".$classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso();
$identificao .="\nPer&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().") \n";
$identificao .="Aluno: ".$matriculaAluno->getMatriculaAluno()." - ".$aluno->getNome()." - Turno: ".$matriculaAluno->getTurnoIngresso()." \n";

require "$BASE_DIR/siro/formularios/gradeHoraria/gradeHorariaDoPeriodo.php";

$descricao = "Emitida a Grade Hor&aacute;ria do Aluno ".$matriculaAluno->getMatriculaAluno()." - ".$aluno->getNome().
            " do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso().") ".
            "no Per&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().")";

//insere o log
$login->incluirLog($EMITIR_PROTOCOLO_GRADE_HORARIA,$descricao);

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