<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/TipoCurso.php";
require_once "$BASE_DIR/interno/manter_tipocurso/ManterTpcursoForm.php";

$acao=$_REQUEST["acao"];

if($acao=="listar") {
   
    if(!$usuario->temPermissao($MANTER_TIPOCURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $tipocursos = TipoCurso::obterTipoCursos();

    require_once "$BASE_DIR/interno/manter_tipocurso/telaListarTpcursos.php";
    
} else if($acao=="prepararAlterar") {

    if(!$usuario->temPermissao($ALTERAR_TIPOCURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $idTipoCurso = $_REQUEST["idTipoCurso"];
    $tipocurso = TipoCurso::obterTipoCursoPorId($idTipoCurso);
    $formTipoCurso = new ManterTpcursoForm();
    $formTipoCurso->atualizarDadosTipoCurso($tipocurso);

    require_once "$BASE_DIR/interno/manter_tipocurso/telaEditaTpcursos.php";
} else if($acao=="alterar") {
    
    if(!$usuario->temPermissao($ALTERAR_TIPOCURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $formTipoCurso = new ManterTpcursoForm();
    $formTipoCurso->atualizarDadosForm();

    $msgsErro=$formTipoCurso->validar();
    if(count($msgsErro)>0) {
        require_once "$BASE_DIR/interno/manter_tipocurso/telaEditaTpcursos.php";
        exit;
    }

    $TipoCursoAntes=TipoCurso::obterTipoCursoPorId($formTipoCurso->getIdTipoCurso());

    $TipoCursoAlt = TipoCurso::obterTipoCursoPorId($formTipoCurso->getIdTipoCurso());

        $con=BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transa��o
            TipoCurso::alterarTipoCurso($formTipoCurso->getIdTipoCurso(),
                $formTipoCurso->getDescricao(),$con);

            $descricao="Alterado o Tipo de curso, do nome " .
                $TipoCursoAntes->getDescricao() . " para " .
                $formTipoCurso->getDescricao();

            $usuario->incluirLog($ALTERAR_TIPOCURSO, $descricao,$con);

            mysql_query("COMMIT", $con);
            $tipocursos = TipoCurso::obterTipoCursos();
            $msgsErro=array();
            array_push($msgsErro,"Tipo de Curso alterado com sucesso.");
            require_once "$BASE_DIR/interno/manter_tipocurso/telaListarTpcursos.php";
        } catch (Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            $tipocursos = TipoCurso::obterTipoCursos();
            require_once "$BASE_DIR/interno/manter_tipocurso/telaListarTpcursos.php";
        }

} else if($acao=="prepararExcluir") {

    if(!$usuario->temPermissao($EXCLUIR_TIPOCURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $idTipoCurso=$_REQUEST["idTipoCurso"];
    $tipocursos=TipoCurso::obterTipoCursoPorId($idTipoCurso);
    $formTipoCurso = new ManterTpcursoForm();
    $formTipoCurso->atualizarDadosTipoCurso($tipocursos);

 
    require_once "$BASE_DIR/interno/manter_tipocurso/telaExcluiTpcursos.php";


} else if($acao=="excluir") {

    if(!$usuario->temPermissao($EXCLUIR_TIPOCURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $formTipoCurso = new ManterTpcursoForm();
    $formTipoCurso->atualizarDadosForm();

    $tipocursos=TipoCurso::obterTipoCursoPorId($formTipoCurso->getIdTipoCurso());

    $con=BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transa��o
        TipoCurso::excluirTipoCurso($tipocursos,$con);

        $descricao="Exclu�do o Tipo de curso " .
        $tipocursos->getDescricao();

        $usuario->incluirLog($EXCLUIR_TIPOCURSO, $descricao,$con);

        mysql_query("COMMIT", $con);

        // Prepara dados para exibi��o da vis�o
        $tipocursos = TipoCurso::obterTipoCursos();

        $msgsErro=array();
        array_push($msgsErro,"Tipo de Curso exclu�do com sucesso.");
        require_once "$BASE_DIR/interno/manter_tipocurso/telaListarTpcursos.php";
    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);

        // Prepara dados para exibi��o da vis�o
        $tipocursos = TipoCurso::obterTipoCursos();
        
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_tipocurso/telaListarTpcursos.php";
    }

} else if($acao=="prepararIncluir") {
     if(!$usuario->temPermissao($INCLUIR_TIPOCURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;    
    }

     require_once "$BASE_DIR/interno/manter_tipocurso/telaIncluirTpcursos.php";
} else if($acao=="incluir") {

    if(!$usuario->temPermissao($INCLUIR_TIPOCURSO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $formTipoCurso = new ManterTpcursoForm();
    $formTipoCurso->atualizarDadosForm();
    $msgsErro=$formTipoCurso->validar();
    if( count($msgsErro)>0) {
        require_once "$BASE_DIR/interno/manter_tipocurso/telaIncluirTpcursos.php";
        exit;
    }

    $con=BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transa��o
        TipoCurso::incluirTipoCurso($formTipoCurso->getDescricao(),$con);

        $descricao="Inclu�do o Tipo de Curso " .
            $formTipoCurso->getDescricao();

        $usuario->incluirLog($INCLUIR_TIPOCURSO, $descricao,$con);

        mysql_query("COMMIT", $con);

        // Prepara dados para exibi��o da vis�o
        $tipocursos = TipoCurso::obterTipoCursos();
        $msgsErro=array();
        array_push($msgsErro,"Tipo de Curso inclu�do com sucesso.");
        require_once "$BASE_DIR/interno/manter_tipocurso/telaListarTpcursos.php";
    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);

        // Prepara dados para exibi��o da vis�o
        $tipocurso = TipoCurso::obterTipoCursos();

        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_tipocurso/telaListarTpcursos.php";
    }

} else {
    trigger_error("A��o n�o identificada.",E_USER_ERROR);
}
?>
