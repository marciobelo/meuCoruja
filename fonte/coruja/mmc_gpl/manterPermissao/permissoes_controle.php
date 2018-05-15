<?php
    require_once "../../includes/comum.php";
    require_once "$BASE_DIR/classes/Login.php";
    
    $idPessoa = $_REQUEST['idPessoa'];
    $nomePessoa = $_REQUEST['nomePessoa'];
    $loginPessoa = Login::obterLoginPorIdPessoa($idPessoa);
    
    if(!$login->temPermissao("UC09.01.00")) {
        require_once "../sem_permissao.php";
        exit;
    } else {
        $con = BD::conectar();
        $strLog = "Consultadas as permiss&otilde;es do Usu&aacute;rio " . $loginPessoa->getPessoa()->getNome() . ", Nome de Acesso " . $loginPessoa->getNomeAcesso();
        $login->incluirLog('UC09.01.00', $strLog, $con);
    }
    
    $funcoesToView = Funcao::obterTodasFuncoes();
    $gruposToView = GrupoFuncao::obterTodos();
    
    $login = Login::obterLoginPorIdPessoa($idPessoa);
    $funcoesPessoa = $login->getPermissoes();
    $gruposPessoa = $login->getGruposFuncao();
    
    foreach ($funcoesPessoa as $funcaoPessoa) {
        $funcoesToCheck[] = $funcaoPessoa->getFuncao()->getIdCasouso();   
    }
    
    foreach ($gruposPessoa as $grupoPessoa) {
        $gruposToCheck[] = $grupoPessoa->getId();
    }

    foreach ($gruposToView as $grupo) {
        foreach ($grupo->getFuncoes() as $funcao) {
            $funcoesDosGrupos[$grupo->getId()][] = $funcao->getIdCasoUso();  
            $funcoesDosGruposComDescricao[$grupo->getId()][] = $funcao->getIdCasoUso() . " - " . $funcao->getDescricao();
        }
    }
        
    require("$BASE_DIR/mmc_gpl/manterPermissao/permissoesForm.php");