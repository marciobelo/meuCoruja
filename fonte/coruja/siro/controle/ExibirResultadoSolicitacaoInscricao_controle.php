<?php
require_once("../../includes/comum.php");
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Aloca.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/siro/classes/buscaAluno.php";
	
// Recupera o usuário logado da sessão
$usuario = $_SESSION["usuario"];

$act = filter_input( INPUT_GET, "act");
$acao = filter_input( INPUT_POST, "acao");
$passo = filter_input( INPUT_POST, "passo");

if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCursoFiltro = "";
}

// TOPO DA PÁGINA
include_once "$BASE_DIR/includes/topo.php";

// MENU HORIZONTAL
echo '<div id="menuprincipal">';
    include_once "$BASE_DIR/includes/menu_horizontal.php";
echo '</div>';

// CONTEÚDO DA PÁGINA - ARQUIVO QUE TEM A FUNÇÃO DE TRATAR AS REQUISIÇÕES    
echo '<div id="conteudo">';
    
$classeInscricao = new Inscricao();
$classeTurma = new turma();
$classeComponenteCurricular = new ComponenteCurricular();
$classeMatAluno = new MatriculaAluno();
    
if( $act === "main") 
{
    // acao para exibir a pagina de filtro de curso

    // Verifica Permissão
    if(!$usuario->temPermissao($EXIBIR_RESULTADO_SOLICITACAO_INSCRICAO)) {
        require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
        exit;
    }

    $collection = Curso::obterCursosOrdemPorSigla();
    require "$BASE_DIR/siro/formularios/exibirResultadoSolicitacaoInscricaoListar.php";
} 
elseif( $acao == "verSolicitacao") 
{
    $siglaCurso = $_POST['siglaCurso'];
    $idPeriodoLetivo = $_POST['idPeriodoLetivo'];

    if($siglaCurso=='' || $idPeriodoLetivo=='')
    {

        echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
        echo "<font>Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!!!</font>";
        echo "</fieldset></form>";

        $collection = Curso::obterCursosOrdemPorSigla();
        require "$BASE_DIR/siro/formularios/exibirResultadoSolicitacaoInscricaoListar.php";
    } 
    else 
    {
        $perLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodoLetivo);
        $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
        $listaSolicitacoes = $classeInscricao->buscaSolicitantes($siglaCurso,$idPeriodoLetivo);
        require "$BASE_DIR/siro/formularios/exibirResultadoSolicitacaoInscricao.php";
    }
}
echo '</div>';
include_once "$BASE_DIR/includes/rodape.php";