<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/TipoCurso.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/interno/manter_curso/ManterCursoForm.php";

$acao = $_REQUEST["acao"];

if($acao === "listar") 
{
   
    if(!$login->temPermissao($MANTER_CURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $cursos = Curso::obterListaCurso();

    require_once "$BASE_DIR/interno/manter_curso/telaListarCursos.php";
    
} 
else if( $acao === "prepararAlterar") 
{
    if(!$login->temPermissao($ALTERAR_CURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }
  
    $Curso=Curso::obterCurso($_REQUEST["siglaCurso"]);
    $formCurso = new ManterCursoForm();
    $formCurso->atualizarDadosCurso($Curso);

    $tiposCurso= TipoCurso::obterTipoCursos();

    require_once "$BASE_DIR/interno/manter_curso/telaEditaCursos.php";
} 
else if( $acao === "alterar") 
{
    if(!$login->temPermissao($ALTERAR_CURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }
    $formCurso = new ManterCursoForm();
    $formCurso->atualizarDadosForm();

    $tiposCurso = TipoCurso::obterTipoCursos();

    $msgsErro = $formCurso->validar();
    if(count($msgsErro)>0) {
        require_once "$BASE_DIR/interno/manter_curso/telaEditaCursos.php";
        exit;
    }

    $cursoAntes=Curso::obterCurso($formCurso->getSiglaCursoAntes());
    
    $con=BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transa??o
        Curso::alterarCurso($formCurso->getSiglaCursoAntes(),
            $formCurso->getSiglaCursoDepois(),
            $formCurso->getNomeCurso(),$formCurso->getIdTipoCurso(),$con);

        $descricao="Alterado o curso, do nome ".$cursoAntes->getNomeCurso() .
                " para " .$formCurso->getNomeCurso();

        $login->incluirLog($ALTERAR_CURSO, $descricao,$con);

        mysql_query("COMMIT", $con);
        $cursos = Curso::obterListaCurso();
        $msgsErro = array();
        array_push($msgsErro,"Curso alterado com sucesso.");
        require_once "$BASE_DIR/interno/manter_curso/telaListarCursos.php";
    } 
    catch (Exception $ex) 
    {
        mysql_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_curso/telaEditaCursos.php";
    }
} 
else if( $acao === "prepararExcluir") 
{
    if( !$login->temPermissao($EXCLUIR_CURSO)) 
    {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $curso = Curso::obterCurso($_REQUEST["siglaCurso"]);
    $formCurso = new ManterCursoForm();
    $formCurso->atualizarDadosCurso($curso);

    require_once "$BASE_DIR/interno/manter_curso/telaExcluiCursos.php";
} 
else if($acao === "excluir") 
{
    if( !$login->temPermissao($EXCLUIR_CURSO)) 
    {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }
    $formCurso = new ManterCursoForm();
    $formCurso->atualizarDadosForm();

    $curso = Curso::obterCurso($formCurso->getSiglaCursoAntes());

    $con=BD::conectar();
    try 
    {
        mysql_query("BEGIN", $con); // Inicia transão
        Curso::excluirCurso($curso,$con);

        $descricao="Excluído o Curso ".
        $curso->getNomeCurso();

        $login->incluirLog($EXCLUIR_CURSO, $descricao,$con);

        mysql_query("COMMIT", $con);

        $msgsErro = array();
        array_push($msgsErro,"Curso excluído com sucesso.");
        
        $cursos = Curso::obterListaCurso();
        require_once "$BASE_DIR/interno/manter_curso/telaListarCursos.php";
    } 
    catch (Exception $ex) 
    {
        mysql_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        $cursos = Curso::obterListaCurso();
        require_once "$BASE_DIR/interno/manter_curso/telaListarCursos.php";
    }

} 
else if( $acao === "prepararIncluir") 
{
     if(!$login->temPermissao($INCLUIR_CURSO)) 
    {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;    
    }
     $boxcursos= TipoCurso::obterTipoCursos();
     require_once "$BASE_DIR/interno/manter_curso/telaIncluirCursos.php";
} 
else if( $acao === "incluir") 
{
    if( !$login->temPermissao($INCLUIR_CURSO)) 
    {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }
    $boxcursos = TipoCurso::obterTipoCursos();
    $formCurso = new ManterCursoForm();
    $formCurso->atualizarDadosForm();
    $msgsErro = $formCurso->validarIncluir();
    if( count($msgsErro)>0) {
        require_once "$BASE_DIR/interno/manter_curso/telaIncluirCursos.php";
        exit;
    }
    $con=BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transa??o
        Curso::incluirCurso($formCurso->getSiglaCurso(), $formCurso->getNomeCurso(),
                $formCurso->getIdTipoCurso(),$con);

        $descricao="Incluído o Curso " .
            $formCurso->getNomeCurso();

        $login->incluirLog($INCLUIR_CURSO, $descricao,$con);

        mysql_query("COMMIT", $con);

        $cursos = Curso::obterListaCurso();
        $msgsErro = array();
        array_push($msgsErro,"Curso incluído com sucesso.");
        require_once "$BASE_DIR/interno/manter_curso/telaListarCursos.php";
    } 
    catch (Exception $ex) 
    {
        mysql_query("ROLLBACK", $con);

        $Curso = Curso::obterListaCurso();

        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_curso/telaListarCursos.php";
    }
} 
else if( $acao === "obterCursosAJAX") 
{
    $cursos = Curso::obterListaCurso();
    $siglasCursos = array_map( function( $curso) { return $curso->getSiglaCurso(); }, $cursos);
    echo json_encode( $siglasCursos);
    exit;
}
else if( $acao === "selecionarFiltroCursoAJAX") 
{
    $siglaFiltroCurso = filter_input(INPUT_POST, "siglaCursoFiltro", FILTER_SANITIZE_STRING);
    $_SESSION["siglaCursoFiltro"] = $siglaFiltroCurso;
    // explica o magic number a seguir
    // http://stackoverflow.com/questions/3290424/set-a-cookie-to-never-expire
    setcookie( "siglaCursoFiltro", $siglaFiltroCurso, 2147483647, "/coruja/");
    exit;
}
else 
{
    trigger_error("Ação não identificada.",E_USER_ERROR);
}
