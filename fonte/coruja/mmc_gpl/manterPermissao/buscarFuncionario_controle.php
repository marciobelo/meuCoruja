<?php
require_once "../../includes/comum.php";
    $acao = $_REQUEST["acao"];    

    if ($acao == "buscar") {
        $funcionariosToView = array();
        $busca = $_REQUEST['busca'];
        
        $funcionariosPorNome = Login::obterLoginsPorNome($busca);
        
        $funcionarioPorNomeAcesso = Login::obterLoginPorNomeAcesso($busca);
        
        foreach ($funcionariosPorNome as $funcionario) {
            $funcionariosToView[] = $funcionario;
        }
        
        if (!empty($funcionarioPorNomeAcesso)) {
            $funcionariosToView[] = $funcionarioPorNomeAcesso;
        }
        $funcionariosToView = array_map("unserialize", array_unique(array_map("serialize", $funcionariosToView)));
        
        
    } elseif ($acao == "editar") {
        $idPessoa = $_REQUEST['idPessoa'];
        $nomePessoa = $_REQUEST['nomePessoa'];
        
        Header("Location: permissoes_controle.php?idPessoa=$idPessoa&nomePessoa=$nomePessoa");
    }
require("$BASE_DIR/mmc_gpl/manterPermissao/buscarFuncionarioForm.php");