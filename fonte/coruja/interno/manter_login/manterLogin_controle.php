<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Login.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Mensagem.php";
require_once "$BASE_DIR/interno/manter_login/ManterLoginForm.php";

$acao = $_REQUEST["acao"];

if( $acao == "exibirTrocaSenha") 
{
    require_once "$BASE_DIR/interno/manter_login/telaTrocaSenha.php";
} 
else if( $acao === "trocarSenha") 
{
    $senhaAtual = $_REQUEST["senhaAtual"];
    $novaSenha = $_REQUEST["novaSenha"];
    $confirmaSenha = $_REQUEST["confirmaSenha"];

    if($novaSenha === $confirmaSenha) 
    {
        try 
        {
            $login->trocarSenha($senhaAtual,$novaSenha);
            $msgsErro=array();
            array_push($msgsErro, "Senha alterada com sucesso.");
        } 
        catch(Exception $ex) 
        {
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
        }
    } 
    else 
    {
        $msgsErro=array();
        array_push($msgsErro, "As senha não conferem");
    }

    require_once "$BASE_DIR/interno/manter_login/telaTrocaSenha.php";
} 
else if( $acao === "prepararCriarLogin") 
{
    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($CRIAR_LOGIN)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    $idPessoa = filter_input(INPUT_GET, "idPessoa", FILTER_SANITIZE_NUMBER_INT);
    $pessoa = Pessoa::obterPessoaPorId( $idPessoa);
    $formLogin = ManterLoginForm::criarPorPessoa( $pessoa);

    // Obter sugestão de nome de acesso
    $ultimaMatricula = MatriculaAluno::obterUltimaMatriculaPorPessoa($pessoa);
    if($ultimaMatricula!=null) {
        $formLogin->setNomeAcesso( $ultimaMatricula->getMatriculaAluno());
    }
    require_once "$BASE_DIR/interno/manter_login/telaCriarLogin.php";
} 
else if($acao==="criarLogin") 
{
    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($CRIAR_LOGIN)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $formLogin = ManterLoginForm::criarPeloRequest();
    $idPessoa = $formLogin->getIdPessoa();
    $pessoa = Pessoa::obterPessoaPorId( $idPessoa);
    $formLogin->setNome( $pessoa->getNome());

    $dataNascNormal = date("d",strtotime($pessoa->getDataNascimento())) .
            date("m",strtotime($pessoa->getDataNascimento())) .
            date("Y",strtotime($pessoa->getDataNascimento()));
    $formLogin->setSenha(str_shuffle($dataNascNormal));

    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação

        Login::criarLogin($formLogin->getIdPessoa(),$formLogin->getNomeAcesso(),
                $formLogin->getSenha(),$con);

        $strLog=sprintf("Criado usuário com login %s para a pessoa %s.",
                $formLogin->getNomeAcesso(),
                $pessoa->getNome());
        $login->incluirLog($CRIAR_LOGIN,$strLog,$con);

        mysql_query("COMMIT", $con);

        $msgsErro=array();
        array_push($msgsErro, "Criado login com a senha " . $formLogin->getSenha());

        require_once "$BASE_DIR/interno/manter_login/telaAlterarFoto.php";
    } 
    catch (Exception $ex) 
    {
        mysql_query("ROLLBACK", $con);
        $msgsErro = array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_login/telaCriarLogin.php";
    }
} 
else if($acao === "prepararAlterarFotoLogin") 
{
    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($ALTERAR_FOTO_LOGIN)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    // Prepara para para a visão
    $formLogin = ManterLoginForm::criarPeloRequest();
    $idPessoa = $formLogin->getIdPessoa();
    $login = Login::obterLoginPorIdPessoa( $idPessoa);
    $pessoa = $login->getPessoa();
    $formLogin->setNome( $pessoa->getNome());
    $formLogin->setNomeAcesso( $login->getNomeAcesso());
    
    require_once "$BASE_DIR/interno/manter_login/telaAlterarFoto.php";
} 
else if($acao === "alterarFotoLogin") 
{
    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($ALTERAR_FOTO_LOGIN)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    
    // Transforma o arquivo recebido por HTTP Upload e transforma em array de bytes
    $arquivoRemoto = $_FILES["foto"]["tmp_name"];
    $tamanho = $_FILES["foto"]["size"];
    $formLogin = ManterLoginForm::criarPeloRequest();
    $pessoa = Pessoa::obterPessoaPorId($formLogin->getIdPessoa());
    $formLogin->setNome($pessoa->getNome());

    if($tamanho == 0 || $tamanho > 307200) { // Se arquivo vazio ou maior de 300Kb
        $msgsErro=array();
        array_push($msgsErro, "Nenhum arquivo enviado ou excede o tamanho máximo permitido.");
        require_once "$BASE_DIR/interno/manter_login/telaAlterarFoto.php";
        exit;
    }

    // Carrega o arquivo
    $fp      = fopen($arquivoRemoto, 'r');
    $byteArr = fread($fp, filesize($arquivoRemoto));
    fclose($fp);
    $formLogin->setFoto($byteArr);

    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação

        Login::atualizarFoto($formLogin->getIdPessoa(),$formLogin->getFoto(),
                $con);

        $strLog=sprintf("Alterada foto do login %s para a pessoa %s.",
                $formLogin->getNomeAcesso(),
                $pessoa->getNome());
        $login->incluirLog($ALTERAR_FOTO_LOGIN,$strLog,$con);

        mysql_query("COMMIT", $con);

        $msgsErro=array();
        array_push($msgsErro, "Foto alterada com sucesso " . $formLogin->getSenha());

        require_once "$BASE_DIR/interno/manter_login/telaAlterarFoto.php";
    } 
    catch (Exception $ex) 
    {
        mysql_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_login/telaCriarLogin.php";
    }
} 
else if( $acao === "resetarSenha")
{
    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($RESETAR_SENHA_LOGIN)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $formLogin = ManterLoginForm::criarPeloRequest();
    $idPessoa = $formLogin->getIdPessoa();
    $login = Login::obterLoginPorIdPessoa($idPessoa);
    $pessoa = $login->getPessoa();
    $formLogin->setNome( $pessoa->getNome());

    $dataNascNormal = date("d",strtotime($pessoa->getDataNascimento())) .
            date("m",strtotime($pessoa->getDataNascimento())) .
            date("Y",strtotime($pessoa->getDataNascimento()));
    $formLogin->setSenha(str_shuffle($dataNascNormal));

    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação

        Login::alterarSenhaLogin($idPessoa,$login->getNomeAcesso(),
                $formLogin->getSenha(),$con);

        $strLog = sprintf("Resetada a senha e desbloqueado o login %s para a pessoa %s.",
                $login->getNomeAcesso(),
                $pessoa->getNome());
        $login->incluirLog($RESETAR_SENHA_LOGIN,$strLog,$con);

        mysql_query("COMMIT", $con);

        $msgsErro=array();
        array_push($msgsErro, "Resetada a senha e desbloqueado o login para " . $formLogin->getSenha());

        require_once "$BASE_DIR/interno/manter_login/telaAlterarFoto.php";
    } 
    catch (Exception $ex) 
    {
        mysql_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_login/telaCriarLogin.php";
    }
}
else if( $acao === "exibirLogin")
{
    // Verifica antes se usuário tem permissão
    if( !$login->temPermissao( "UC03.09.00")) // MANTER_LOGIN
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    
    $idPessoa = filter_input(INPUT_GET, "idPessoa", FILTER_SANITIZE_NUMBER_INT);

    $login = Login::obterLoginPorIdPessoa( $idPessoa);
    
    $formLogin = ManterLoginForm::criarPorLogin( $login);
    
    require_once "$BASE_DIR/interno/manter_login/telaExibirLogin.php";
}
else if( $acao === "desbloquearLogin")
{
    // Verifica antes se usuário tem permissão
    $DESBLOQUEAR_LOGIN = "UC03.09.05";
    if( !$login->temPermissao( $DESBLOQUEAR_LOGIN))
    {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    
    $idPessoa = filter_input(INPUT_POST, "idPessoa", FILTER_SANITIZE_NUMBER_INT);

    $login = Login::obterLoginPorIdPessoa( $idPessoa);
    $formLogin = ManterLoginForm::criarPorLogin( $login);
    $pessoa = $login->getPessoa();
    
    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação
        $login->desbloquear( $login->getNomeAcesso(), $con);
        $strLog = sprintf("Desbloqueado o login %s para a pessoa %s.",
                $login->getNomeAcesso(),
                $pessoa->getNome());
        $login->incluirLog( $DESBLOQUEAR_LOGIN, $strLog, $con);
        $formLogin->setBloqueado( false);
        $formLogin->setMotivoBloqueio( "");
        
        $mensagemParaAluno = "Prezado(a),". $pessoa->getNome() ."\n\n" .
                            "Sua conta " . $login->getNomeAcesso() . " no Coruja foi desbloqueada";
        $aIdPessoa = array();
        $aIdPessoa[] = $idPessoa;
        Mensagem::depositarMensagem("Conta Coruja Desbloqueada", $mensagemParaAluno, $aIdPessoa);
        mysql_query("COMMIT", $con);

        $msgsErro = array();
        array_push($msgsErro, "Desbloqueado o login para " . $login->getNomeAcesso());
        require_once "$BASE_DIR/interno/manter_login/telaExibirLogin.php";
    } 
    catch (Exception $ex) 
    {
        mysql_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once "$BASE_DIR/interno/manter_login/telaExibirLogin.php";
    }
}
else 
{
    trigger_error("Ação não identificada.",E_USER_ERROR);
}