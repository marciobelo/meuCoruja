<?php

    require_once "../../includes/comum.php";
    require_once "$BASE_DIR/classes/Login.php";
    require_once "$BASE_DIR/classes/Funcao.php";
    
    if (isset($_REQUEST['mensagem']) && !empty($_REQUEST)) {
        $mensagem = $_REQUEST['mensagem'];
    }
    
    $acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : 'consultar'; 
    
    if ($acao == 'consultar') {
        if(!$login->temPermissao("UC09.02.00")) {
            require_once "../sem_permissao.php";
            exit;
        } else {
            $con = BD::conectar();
            $strLog = "Consultados os Grupos de Permiss&otilde;es do sistema";
            $login->incluirLog('UC09.02.00', $strLog, $con);
        }

        $gruposDeFuncao = GrupoFuncao::obterTodos();
        
        
        foreach ($gruposDeFuncao as $grupoDeFuncao) {
            foreach ($grupoDeFuncao->getFuncoes() as $funcao) {
                $funcoesDosGruposComDescricao[$grupoDeFuncao->getId()][] = $funcao->getIdCasoUso() . " - " . $funcao->getDescricao();
            }
        }
        
        require("$BASE_DIR/mmc_gpl/manterPermissao/grupoPermissoesListarForm.php");
        
    } elseif($acao == 'incluir') {        
        if(!$login->temPermissao("UC09.02.01")) {
            require_once "../sem_permissao.php";
            exit;
        }
        
        $funcoesToView = Funcao::obterTodasFuncoes();
        $gruposDeFuncao = GrupoFuncao::obterTodos();
        $nomeGruposExistentes = '';
        
        foreach ($gruposDeFuncao as $grupoDeFuncao) {
            $nomeGruposExistentes .=  $grupoDeFuncao->getNome() . ',';
        }
        
        require("$BASE_DIR/mmc_gpl/manterPermissao/grupoPermissoesCriarForm.php");
        exit;
        
    } elseif ($acao == 'alterar') {
        if(!$login->temPermissao("UC09.02.03")) {
            require_once "../sem_permissao.php";
            exit;
        }

        $idGrupo = $_REQUEST['idGrupo'];
        
        $grupoFuncao = GrupoFuncao::obterPorId($idGrupo);
        $nomeDoGrupo = $grupoFuncao->getNome();
        $funcoesDoGrupo = $grupoFuncao->getFuncoes();
        
        $funcoesToView  = Funcao::obterTodasFuncoes();

        foreach ($funcoesDoGrupo as $funcao) {
            $idsFuncoesDogrupo[] = $funcao->getIdCasoUso();
        }
        
        foreach ($funcoesToView as $val) {
            if (in_array($val->getIdCasoUso(), $idsFuncoesDogrupo)) {
                $funcoesToCheck[] = $val->getIdCasoUso();
            }
        }

        require("$BASE_DIR/mmc_gpl/manterPermissao/grupoPermissoesEditarForm.php");
        
    } elseif ($acao == 'salvar') {
        //create
        $grupo = GrupoFuncao::obterPorId($_REQUEST['idGrupo']);
        
        if( !isset($_REQUEST['idGrupo']) ) {
            if(!$login->temPermissao("UC09.02.01")) {
                require_once "../sem_permissao.php";
                exit;
            }
        
            $nome = $_REQUEST['nomeDoNovoGrupo'];
            $idPermissoes = $_REQUEST['permissoesDoNovoGrupo'];

            if(GrupoFuncao::criar($nome, $idPermissoes)){
                $mensagem = "Grupo de permissões " . $nome . " criado com sucesso";
            }
            
            Header("Location: grupoPermissoes_controle.php?mensagem=$mensagem");
        //update;    
        } else {
            if(!$login->temPermissao("UC09.02.03")) {
                require_once "../sem_permissao.php";
                exit;
            }
            
            $idGrupo = $_REQUEST['idGrupo'];
            $nomeGrupo = $_REQUEST['novoNomeDoGrupo'];
            $nomeAntigo = $_REQUEST['nomeAntigo'];
            $idFuncoes = $_REQUEST['funcoesDoGrupo'];
            
            $grupoFuncao = GrupoFuncao::obterPorId($idGrupo);
            
            if($grupoFuncao->editar($nomeGrupo, $nomeAntigo, $idFuncoes)) {
               $mensagem = "Grupo de permissões $nomeGrupo alterado com sucesso"; 
            }
            
            Header("Location: grupoPermissoes_controle.php?mensagem=$mensagem");
        }
        
    } elseIf ($acao == 'remover') {
        if(!$login->temPermissao("UC09.02.02")) {
            require_once "../sem_permissao.php";
            exit;
        }
        
        $idGrupo = $_REQUEST['idGrupo'];
        $grupoFuncao = GrupoFuncao::obterPorId($idGrupo);
        $nomeDoGrupo = $grupoFuncao->getNome();
        
        if($grupoFuncao->deletar()) {
            $mensagem = "Grupo de permissões " . $nomeDoGrupo . " excluído com sucesso";
        }

        Header("Location: grupoPermissoes_controle.php?mensagem=$mensagem");
    }