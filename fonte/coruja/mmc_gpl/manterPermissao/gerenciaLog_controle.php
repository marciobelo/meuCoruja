<?php

    require_once "../../includes/comum.php";
    require_once "$BASE_DIR/classes/BD.php";
    require_once "$BASE_DIR/classes/Login.php";
    require_once "$BASE_DIR/classes/Log.php";
    
    $acao = $_REQUEST['acao'];
    $paginaAtual = (isset($_REQUEST['paginaAtual']) && !empty($_REQUEST['paginaAtual'])) ? $_REQUEST['paginaAtual'] : 1;
    $paginaDigitada = (isset($_REQUEST['paginaDigitada']) && !empty($_REQUEST['paginaDigitada'])) ? $_REQUEST['paginaDigitada'] : '';
    
    if ($acao == 'buscar') {
        $arrWhere = array();

        $arrIdsNome = array();
        $camposLog = '';
        if (strLen($_REQUEST['nome']) > 0) {
            $valorDaBusca = $_REQUEST['nome'];                        
            
            $camposLog .= ' - Nome: ' . $valorDaBusca;
            
            $funcionarios = Login::obterLoginsPorNome($_REQUEST['nome']);
            foreach ($funcionarios as $funcionario) {
                $funcIds[] = $funcionario->getPessoa()->getidPessoa();
            }
            
            $funcIds = implode($funcIds, "','");

            $arrWhere[] = "log.idPessoa IN ('%s')";
            $arrParamSprintf[] = $funcIds;
        }

        if (strLen($_REQUEST['nomeAcesso']) > 0) {
            $login = Login::obterLoginPorNomeAcesso($_REQUEST['nomeAcesso']);
            $camposLog .= '<br/> - Nome de Acesso: ' . $_REQUEST['nomeAcesso'];
            
            $idPessoa = (is_null($login)) ? -1 : $login->pessoa->getIdPessoa();

            $arrWhere[] = "log.idPessoa = '%d'";
            $arrParamSprintf[] = $idPessoa; 
        }

        if (strLen($_REQUEST['casoUso']) > 0) {
            $camposLog .= '<br/> - Permiss&atilde;o: ' . $_REQUEST['casoUso'];
            
            $arrWhere[] = "log.idCasoUso = '%s'";
            $arrParamSprintf[] = $_REQUEST['casoUso']; 
        }
        
        if (strLen($_REQUEST['parteLog']) > 0) {
            $camposLog .= '<br/> - Parte do Log: ' . $_REQUEST['parteLog'];
            
            $arrWhere[] = "log.descricao LIKE '%%%s%%'";
            $arrParamSprintf[] = mysql_escape_string($_REQUEST['parteLog']);
        }
        
        if(!$_SESSION['usuario']->temPermissao("UC10.01.00")) {
            require_once "../sem_permissao.php";
            exit;
        } else {
            $con = BD::conectar();
            $strLog = "Consultados os registros de Log de <br/>".  $camposLog;
            $_SESSION['usuario']->incluirLog('UC10.01.00', $strLog, $con);
        }
        
        $registrosPorPagina = 50;
        $infosPaginacao = Log::obterInformacoesPaginacao($arrWhere, $arrParamSprintf, $paginaAtual, $registrosPorPagina);
        $logsToView = Log::select($arrWhere, $arrParamSprintf, $infosPaginacao['primeiroRegistro']-1, $registrosPorPagina);
   }        
    
    //Obtendo caso de uso para Select
    foreach(Funcao::obterTodasFuncoes() as $casoUso){
        $casosUso[] = $casoUso->getIdCasoUso();
    }

    //valores selecionados
    $nome = isset($_REQUEST['nome']) ?  $_REQUEST['nome'] : '';
    $nomeAcesso = isset($_REQUEST['nomeAcesso']) ?  $_REQUEST['nomeAcesso'] : '';
    $parteLog = isset($_REQUEST['parteLog']) ?  $_REQUEST['parteLog'] : '';
    $casoUsoSelecionado = isset($_REQUEST['casoUso']) ?  $_REQUEST['casoUso'] : '';
    
    
    $arrLogkeys = array ( 
        'dataHora'  => 'Data',
        'idPessoa'  => 'ID Pessoa',
        'nomeAcesso'=> 'Nome de acesso',
        'nome'      => 'Nome',
        'idCasoUso' => 'ID Caso de Uso',
        'descricao' => 'Descri&ccedil;&atilde;o',
    );
    
    $arrayTamanhoColuna = array(
        'dataHora'  => '10%',
        'idPessoa'  => '6%',
        'nomeAcesso'=> '10%',
        'nome'      => '8%',
        'idCasoUso' => '6%',
        'descricao' => '20%',
    );
                
    require_once "$BASE_DIR/mmc_gpl/manterPermissao/gerenciaLogForm.php";
    
    


