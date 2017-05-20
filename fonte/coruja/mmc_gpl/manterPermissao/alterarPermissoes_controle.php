<?php
    require_once "../../includes/comum.php";
    require_once "$BASE_DIR/classes/BD.php";
    require_once "$BASE_DIR/classes/Login.php";
    
    $idPessoa      = filter_input( INPUT_POST, "idPessoa", FILTER_SANITIZE_NUMBER_INT);
    $idPermissao   = filter_input( INPUT_POST, "idPermissao", FILTER_SANITIZE_STRING);
    $acao          = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
    $usuario       = $_SESSION['usuario'];
    $con           = BD::conectar();
    $loginPessoa   = Login::obterLoginPorIdPessoa($idPessoa);

    if($acao == 'Atribuir') {
        $idPermissaoVerificar = 'UC09.01.01';
    } else {
        $idPermissaoVerificar = 'UC09.01.02';
    }

    if(!$usuario->temPermissao($idPermissaoVerificar)) {
        echo "Sem Permissao";
        exit;
    }
    
    try {
        mysql_query("BEGIN", $con); 
        
        if ($acao == "Atribuir") {
            $funcao = Funcao::obterPorId($idPermissao);
            $funcao->atribuirPermissao($loginPessoa);
            
            $acaoLog              = 'Atribu&iacute;da';
            $conectivo            = 'ao';
        } else {
            $funcao = Funcao::obterPorId($idPermissao);
            $funcao->removerPermissao($loginPessoa);
            
            $acaoLog              = 'Removida';
            $conectivo            = 'do';
        }
        
        $strLog = $acaoLog ." a permiss&atilde;o " . $funcao->getDescricao() . " " . $conectivo . " Usu&aacute;rio " . $loginPessoa->getPessoa()->getNome() . ", Nome de Acesso " .  $loginPessoa->getNomeAcesso();
        $usuario->incluirLog($idPermissaoVerificar, $strLog, $con);

        mysql_query("COMMIT", $con);
        
    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);

        trigger_error($ex->getMessage(), E_USER_ERROR);
        exit;
    }
    
    echo "Sucesso";
    exit;    