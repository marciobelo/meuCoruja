<?php
    require_once "../../includes/comum.php";
    require_once "$BASE_DIR/classes/BD.php";
    require_once "$BASE_DIR/classes/Login.php";
    
    $idPessoa    = filter_input( INPUT_POST, "idPessoa", FILTER_SANITIZE_NUMBER_INT);
    $idGrupo     = filter_input( INPUT_POST, "idGrupo", FILTER_SANITIZE_NUMBER_INT);
    $acao        = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
    $con         = BD::conectar();
    $loginPessoa = Login::obterLoginPorIdPessoa($idPessoa); 
    $grupo       = GrupoFuncao::obterPorId($idGrupo);

    if($acao == 'Atribuir') {
        $idPermissaoVerificar = 'UC09.01.03';
    } else {
        $idPermissaoVerificar = 'UC09.01.04';
    }

    if(!$login->temPermissao($idPermissaoVerificar)) {
        echo "Sem Permissao";
        exit;
    }
    
    $idsPermissoesPessoa = array();
    foreach($loginPessoa->getPermissoes() as $permissaoPessoa) {
        $idsPermissoesPessoa[] = $permissaoPessoa->getFuncao()->getIdCasoUso();
    }
    
    foreach($grupo->getFuncoes() as $funcao) {
        $idsFuncoesGrupo[] = $funcao->getIdCasoUso();
    }

    try {
        mysql_query("BEGIN", $con);
        if ($acao == 'Atribuir') {
            if ( count($idsPermissoesPessoa) > 0) {
                $idPermissoes = array_diff($idsFuncoesGrupo, $idsPermissoesPessoa);
            } else {
                $idPermissoes = $idsFuncoesGrupo;
            }
              
            foreach ($idPermissoes as $idPermissao) {
                $funcao = Funcao::obterPorId($idPermissao);
                $funcao->atribuirPermissao($loginPessoa);
            }
            
            $acaoLog = 'Atribu&iacute;do';
            $conectivo = 'ao';
        } else {
            $idPermissoes = $idsFuncoesGrupo;
            foreach ($idPermissoes as $idPermissao) {
                $funcao = Funcao::obterPorId($idPermissao);
                $funcao->removerPermissao($loginPessoa);
            }
            
            $acaoLog = 'Removido';
            $conectivo = 'do';
        }

        $strLog = $acaoLog . " o Grupo de permiss&otilde;es " . $grupo->getNome() . " " . $conectivo . " Usu&aacute;rio " . $loginPessoa->getPessoa()->getNome(). ", Nome de Acesso " . $loginPessoa->getNomeAcesso();
        $login->incluirLog($idPermissaoVerificar, $strLog, $con);

        mysql_query("COMMIT", $con);
        
    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);
        trigger_error($ex->getMessage(), E_USER_ERROR);
        exit;
    }
        
    echo"Sucesso";
    exit;