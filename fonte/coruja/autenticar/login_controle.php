<?php 
    /*	Lê definição das classes
        Atenção: a definição das classes devem ser carregadas antes da recuperação dos dados da sessão
        BUG documentado em http://www.webdeveloper.com/forum/showthread.php?t=144267
    */
    require_once "../includes/comum.php";
    require_once "$BASE_DIR/classes/Login.php";
    require_once "$BASE_DIR/classes/Log.php";
    require_once "$BASE_DIR/classes/Pessoa.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";
    
    // Obtem a ação
    $acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
    if( $acao === null)
    {
        $acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
    }
    
    if( $acao === "sair" ) {
        session_start();
        $_SESSION = array();
        if (isset( $_COOKIE[session_name()])) 
        {
                setcookie( session_name(), '', time()-42000, '/');
        }
        session_destroy();
        require("$BASE_DIR/autenticar/loginForm.php");
        exit;
    } 
    else if( $acao === "autenticar" ) 
    {
        // Garante que uma nova sessão será iniciada
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id();
        
        $nomeAcesso = filter_input(INPUT_POST, "nomeAcesso");
        $senha = filter_input(INPUT_POST, "senha");
        $perfil = filter_input(INPUT_POST, "perfil");

        // Cria objeto de sessão novo
        try 
        {
            $_SESSION["login"] = Login::autenticar( $nomeAcesso, $senha, $perfil);
        } 
        catch (Exception $ex) 
        {
            $erro = $ex->getMessage();
            require("$BASE_DIR/autenticar/loginForm.php");
            exit;            
        }

        // Recupera o login da sessão
        $login = $_SESSION["login"];

        // Verifica se há filtro de curso configura
        $siglaCursoFiltro = filter_input( INPUT_COOKIE, "siglaCursoFiltro", 
                FILTER_SANITIZE_STRING);
        if( $siglaCursoFiltro !== null)
        {
            $_SESSION["siglaCursoFiltro"] = $siglaCursoFiltro;
        }

        // Se não houver registro de log a conferir, desvia logo para página principal
        $listaLogNaoConferidos = $login->getLogsNaoConferidos();

        $idPessoa = $login->getIdPessoa();

        // Salva dados de autentição em cookie
        setcookie( "perfil", $login->getPerfil(), time()+60*60*24*30 );

        if( empty( $listaLogNaoConferidos)) 
        {
            encaminharPaginaInicialPerfil( $login->getPerfil() );
        } 
        else 
        {
            require("$BASE_DIR/autenticar/validarLogForm.php");
            exit;
        }
    } 
    else if( $acao === "validarLog") 
    {
        // Restaura o usuário logado na sessão
        $login = $_SESSION["login"];
        if( !isset( $login))
        {
            require("$BASE_DIR/autenticar/loginForm.php");
            exit;
        }

        // Obtem a lista de registros de logs conferidos pelo usuário preenchidos no formulário
        $listaConfere = $_POST[ "confere"];

        // Registra como aceito todos os registros de log conferidos pelo usuário
        if( isset($listaConfere) ) 
        {
            foreach( $listaConfere as $idLog) 
            {
                    $idLogParte = split(";",$idLog,2);
                    $idCasoUso = $idLogParte[0];
                    $dataHora = $idLogParte[1];
                    $login->aceitarLog( $idCasoUso, $dataHora);
            }
        }

        // Verifica se usuário aceitou todos os logs realizados na conta dele
        $listaLogNaoConferidos = $login->getLogsNaoConferidos();
        if( !empty($listaLogNaoConferidos)) // Se o usuário ainda não aceitou todos os logs
        { 
                $erro = "Você deve aceitar todos os registros para continuar!";
                require("$BASE_DIR/autenticar/validarLogForm.php");
                exit;
        }
        else 
        { // Usuário aceitou todos os logs.
            encaminharPaginaInicialPerfil( $login->getPerfil() );
        }
    } 
    else if( $acao === "prepararRecuperarSenha") 
    {
        require_once "$BASE_DIR/autenticar/recuperarSenhaForm.php";
        exit;

    } 
    else if( $acao === "recuperarSenhaConfirmarEmail") 
    {
        $nomeAcesso = filter_input( INPUT_POST, "nomeAcesso");
        $dataNascimento = filter_input( INPUT_POST, "dataNascimento");

        try 
        {
            $login = Login::obterLoginPorNomeAcesso( $nomeAcesso);
            $pessoa = $login->getPessoa();
            
            $email = $login->getEmail();
            if( $pessoa->getDataNascimento() !== Util::dataBrParaSQL($dataNascimento)) 
            {
                throw new Exception("Nome de acesso e/ou data de nascimento não conferem.
                Se você tem certeza que está digitando corretamente seus dados,
                procure a secretaria para providenciar as correções.");
            }
            if( $login->isBloqueado() ) 
            {
                throw new Exception( sprintf("Usuário '%s' está bloqueado. Procure o administrador do sistema.", 
                        $login->getNomeAcesso() ) );
            }
        } 
        catch(Exception $ex) 
        {
            $erro = $ex->getMessage();
            require_once "$BASE_DIR/autenticar/recuperarSenhaForm.php";
            exit;
        }
        require_once "$BASE_DIR/autenticar/recuperarSenhaConfirmaEmail.php";
        exit;
    } 
    else if($acao=="recuperarSenha") {
        $idPessoa = $_POST["idPessoa"];
        $nomeAcesso = $_POST["nomeAcesso"];
        $dataNascimento = $_POST["dataNascimento"];
        $email = $_POST["email"];
        
        try {
            Login::recuperarSenha($idPessoa,$nomeAcesso);
        } catch(Exception $ex) {
            $msg = $ex->getMessage();
            $erro = "Erro na operação. Mensagem: $msg";
            require_once "$BASE_DIR/autenticar/recuperarSenhaConfirmaEmail.php";
            exit;
        }

        $msg = "Sua senha foi resetada com sucesso. Verifique seu e-mail pela nova senha.";
        require("$BASE_DIR/autenticar/loginForm.php");

        exit;

    } else {
        unset ($msg);
        require("$BASE_DIR/autenticar/loginForm.php");
        exit;
    }

    function encaminharPaginaInicialPerfil( $perfil ) {
        if( $perfil == Login::PROFESSOR ) {
            header("Location: /coruja/espacoProfessor/index_controle.php?acao=exibirIndex");
        } else {
            // Encaminha para a página índice do Coruja
            header("Location: /coruja/baseCoruja/index.php");
        }
    }