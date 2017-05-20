<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Espaco.php";
require_once "$BASE_DIR/interno/manter_espaco/ManterEspacoForm.php";

$acao=$_REQUEST["acao"];

if($acao=="listar") {
   
    if(!$usuario->temPermissao($MANTER_ESPACO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $espacos = Espaco::obterEspacos();

    require_once "$BASE_DIR/interno/manter_espaco/telaListarEspacos.php";
    
} else if($acao=="prepararAlterar") {

    if(!$usuario->temPermissao($ALTERAR_ESPACO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $idEspaco=$_REQUEST["idEspaco"];
    $espaco=Espaco::obterEspacoPorId($idEspaco);
    $formEspaco = new ManterEspacoForm();
    $formEspaco->atualizarDadosEspaco($espaco);

    require_once "$BASE_DIR/interno/manter_espaco/telaEditaEspaco.php";
} else if($acao=="alterar") {
    
    if(!$usuario->temPermissao($ALTERAR_ESPACO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $formEspaco = new ManterEspacoForm();
    $formEspaco->atualizarDadosForm();

    $msgsErro=$formEspaco->validar();
    if(count($msgsErro)>0) {
        require_once "$BASE_DIR/interno/manter_espaco/telaEditaEspaco.php";
        exit;
    }

    $espacoAntes=Espaco::obterEspacoPorId($formEspaco->getIdEspaco());

    //$espacoAlt = new Espaco();
    $espacoAlt = Espaco::obterEspacoPorId($formEspaco->getIdEspaco());

    if($espacoAlt->novaCapacidadeAdequada($formEspaco->getCapacidade())) {
        $con=BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            Espaco::alterarEspaco($formEspaco->getIdEspaco(),
                $formEspaco->getNome(),$formEspaco->getCapacidade(),$con);

            $descricao="Alterado o espaço, do nome " .
                $espacoAntes->getNome() . " para " .
                $formEspaco->getNome() .", com capacidade de " .
                $espacoAntes->getCapacidade() . " para " .
                $formEspaco->getCapacidade() . " alunos";

            $usuario->incluirLog($ALTERAR_ESPACO, $descricao,$con);

            mysql_query("COMMIT", $con);

            $espacos = Espaco::obterEspacos();
            $msgsErro=array();
            array_push($msgsErro,"Espaço alterado com sucesso.");
            require_once "$BASE_DIR/interno/manter_espaco/telaListarEspacos.php";
        } catch (Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            $espacos = Espaco::obterEspacos();
            require_once "$BASE_DIR/interno/manter_espaco/telaListarEspacos.php";
        }
  } else {
      $espacos = Espaco::obterEspacos();
      $msgsErro=array();
      array_push($msgsErro,"Existem turmas alocadas neste espaço e a capacidade configurada é menor que a atual");
      require_once "$BASE_DIR/interno/manter_espaco/telaListarEspacos.php";
  }
} else if($acao=="prepararExcluir") {

    if(!$usuario->temPermissao($EXCLUIR_ESPACO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $idEspaco=$_REQUEST["idEspaco"];
    $espaco=Espaco::obterEspacoPorId($idEspaco);
    $formEspaco = new ManterEspacoForm();
    $formEspaco->atualizarDadosEspaco($espaco);


    $msgsErro=array();
    array_push($msgsErro, "A exclusão do atual espaço, implicará na anulação da alocação de espaços de mais de uma turma!");
    require_once "$BASE_DIR/interno/manter_espaco/telaExcluiEspaco.php";


} else if($acao=="excluir") {

    if(!$usuario->temPermissao($EXCLUIR_ESPACO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $formEspaco = new ManterEspacoForm();
    $formEspaco->atualizarDadosForm();

    $espaco=Espaco::obterEspacoPorId($formEspaco->getIdEspaco());

    $con=BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação
        Espaco::excluirEspaco($espaco,$con);

        $descricao="Excluído o espaço, do nome " .
            $espaco->getNome() ." , com capacidade de " .
            $espaco->getCapacidade() . " alunos";

        $usuario->incluirLog($EXCLUIR_ESPACO, $descricao,$con);

        mysql_query("COMMIT", $con);

        // Prepara dados para exibição da visão
        $espacos = Espaco::obterEspacos();

        $msgsErro=array();
        array_push($msgsErro,"Espaço excluído com sucesso.");
        require_once "$BASE_DIR/interno/manter_espaco/telaListarEspacos.php";
    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);

        // Prepara dados para exibição da visão
        $espacos = Espaco::obterEspacos();
        
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_espaco/telaListarEspacos.php";
    }

} else if($acao=="prepararIncluir") {
     if(!$usuario->temPermissao($INCLUIR_ESPACO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;    
    }

     require_once "$BASE_DIR/interno/manter_espaco/telaIncluirEspaco.php";
} else if($acao=="incluir") {

    if(!$usuario->temPermissao($INCLUIR_ESPACO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $formEspaco = new ManterEspacoForm();
    $formEspaco->atualizarDadosForm();
    $msgsErro=$formEspaco->validar();
    if( count($msgsErro)>0) {
        require_once "$BASE_DIR/interno/manter_espaco/telaIncluirEspaco.php";
        exit;
    }

    $con=BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação
        Espaco::incluirEspaco($formEspaco->getNome(),$formEspaco->getCapacidade(),$con);

        $descricao="Incluído o espaço, do nome " .
            $_REQUEST['nome']." , com capacidade de " .
            $_REQUEST['capacidade']. " alunos";

        $usuario->incluirLog($INCLUIR_ESPACO, $descricao,$con);

        mysql_query("COMMIT", $con);

        // Prepara dados para exibição da visão
        $espacos = Espaco::obterEspacos();
        $msgsErro=array();
        array_push($msgsErro,"Espaço incluído com sucesso.");
        require_once "$BASE_DIR/interno/manter_espaco/telaListarEspacos.php";
    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);

        // Prepara dados para exibição da visão
        $espacos = Espaco::obterEspacos();

        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_espaco/telaListarEspacos.php";
    }

} else {
    trigger_error("Ação não identificada.",E_USER_ERROR);
}
?>
