<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Curso.php";

$acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
$controleDestino = filter_input( INPUT_GET, "controleDestino", FILTER_SANITIZE_STRING);
$acaoControleDestino = filter_input( INPUT_GET, "acaoControleDestino", FILTER_SANITIZE_STRING);
$controleDestinoTitulo = filter_input( INPUT_GET, "controleDestinoTitulo", FILTER_SANITIZE_STRING);
if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCursoFiltro = "";
}

if($acao=="selecionarCurso" && $siglaCursoFiltro === "")
{
    $cursos = Curso::obterCursosOrdemPorSigla();
    require_once "$BASE_DIR/interno/selecionar_matricula_aluno/telaSelecionarCurso.php";

} 
else if($acao=="exibirFiltroPesquisa" || ($acao==="selecionarCurso" && $siglaCursoFiltro !== "")) 
{   
    if( filter_input( INPUT_GET, "siglaCurso") != NULL)
    {
        $siglaCurso = filter_input( INPUT_GET, "siglaCurso", FILTER_SANITIZE_STRING);
    }
    else
    {
        if( $siglaCursoFiltro !== "")
        {
            $siglaCurso = $_SESSION["siglaCursoFiltro"];
        }
    }
    if( filter_input( INPUT_GET, "tipoBusca") != NULL)
    {
        $tipoBusca = filter_input( INPUT_GET, "tipoBusca", FILTER_SANITIZE_STRING);
    }
    else
    {
        $tipoBusca = "nome";
    }

    if($siglaCurso=='') {
        $msgsErro=array();
        array_push($msgsErro, "É obrigatório informar um curso.");
        $cursos = Curso::obterCursosOrdemPorSigla();
        require_once "$BASE_DIR/interno/selecionar_matricula_aluno/telaSelecionarCurso.php";
        exit;
    }

    require_once "$BASE_DIR/interno/selecionar_matricula_aluno/telaFiltroPesquisaMatricula.php";

} else if($acao=="exibirResultado") {
    require_once "$BASE_DIR/classes/MatriculaAluno.php";

    $siglaCurso = $_REQUEST["siglaCurso"];
    $tipoBusca = $_REQUEST["tipoBusca"];
    if($tipoBusca=="matricula") {
        $matriculaAluno = $_REQUEST["matricula"];
        $listaMatriculasCurso = MatriculaAluno::obterListaMatriculaPorSiglaCursoMatricula($siglaCurso, $matriculaAluno);
    } else { // tipoBusca=="nome"
        $nome = $_REQUEST["nome"];
        $listaMatriculasCurso=MatriculaAluno::obterListaMatriculaPorSiglaCursoNomeAluno($siglaCurso, $nome);
    }

    if( count($listaMatriculasCurso)==0 ) {
        $msgsErro=array();
        array_push($msgsErro, "Nenhum registro encontrado. Informe novos parâmetros.");
        require_once "$BASE_DIR/interno/selecionar_matricula_aluno/telaFiltroPesquisaMatricula.php";
        exit;
    }

        require_once "$BASE_DIR/interno/selecionar_matricula_aluno/telaResultadoMatricula.php";
} else {
        trigger_error("Ação não identificada.",E_USER_ERROR);
}
?>
