<?php 
    /*	L� defini��o das classes
        Aten��o: a defini��o das classes devem ser carregadas antes da recupera��o dos dados da sess�o
        BUG documentado em http://www.webdeveloper.com/forum/showthread.php?t=144267
    */
    require_once "../includes/comum.php";
    require_once "$BASE_DIR/classes/Usuario.php";
    require_once "$BASE_DIR/classes/Login.php";
    require_once "$BASE_DIR/classes/Pessoa.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";
    
    // Obtem a a��o
    $acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
    if( $acao === null)
    {
        $acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
    }
    
    if( $acao === "sair" ) {
        session_start();
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time()-42000, '/');
        }
        session_destroy();
        require("$BASE_DIR/autenticar/loginForm.php");
        exit;

    } 
    else if( $acao === "autenticar" ) 
    {
        $nomeAcesso = filter_input(INPUT_POST, "nomeAcesso");
        $senha = filter_input(INPUT_POST, "senha");
        $perfil = filter_input(INPUT_POST, "perfil");

        // Cria objeto de sess�o novo
        try 
        {
            $sucesso = Usuario::autenticar($nomeAcesso, $senha, $perfil);
        } 
        catch (Exception $ex) 
        {
            $erro = $ex->getMessage();
            require("$BASE_DIR/autenticar/loginForm.php");
            exit;            
        }
        
        if(!$sucesso) 
        {
            $tentativa = $_SESSION[$nomeAcesso]["tentativa"];
            if( $tentativa>5 ) 
            {
                Login::bloquear($nomeAcesso, "Login bloqueado pois excedeu o limite de tentativas de autentica��o.");
                $erro = "Seu usu�rio foi bloqueado por exceder o n�mero m�ximo " .
                    "de tentativas de autentica��o! " .
                    "Contacte o administrador do sistema.";
                $_SESSION[$nomeAcesso]["tentativa"]=0;
            } else {
                $erro="N�o foi poss�vel conectar usando os dados informados. " .
                "Tente novamente!";
            }
            require("$BASE_DIR/autenticar/loginForm.php");
            exit;
        }
        else
        { // Se conseguiu autenticar o usu�rio

            // Recupera o usu�rio logado da sess�o
            $usuario = $_SESSION["usuario"];

            // Verifica se h� filtro de curso configura
            $siglaCursoFiltro = filter_input( INPUT_COOKIE, "siglaCursoFiltro", FILTER_SANITIZE_STRING);
            if( $siglaCursoFiltro !== null)
            {
                $_SESSION["siglaCursoFiltro"] = $siglaCursoFiltro;
            }
            
            // Se n�o houver registro de log a conferir, desvia logo para p�gina principal
            $listaLogNaoConferidos=$usuario->getLogsNaoConferidos();

            $idPessoa=$usuario->getIdPessoa();

            // Salva dados de autenti��o em cookie
            setcookie("perfil", $usuario->getPerfil(), time()+60*60*24*30 );

            if(empty($listaLogNaoConferidos)) {
                encaminharPaginaInicialPerfil( $usuario->getPerfil() );
            } else {
                require("$BASE_DIR/autenticar/validarLogForm.php");
                exit;
            }
        }
    } else if($acao == "validarLog") {

            // Restaura o usu�rio logado na sess�o
            $usuario = $_SESSION["usuario"];
            if(!isset($usuario)) {
                    require("$BASE_DIR/autenticar/loginForm.php");
                    exit;
            }

            // Obtem a lista de registros de logs conferidos pelo usu�rio preenchidos no formul�rio
            $listaConfere=$_POST["confere"];

            // Registra como aceito todos os registros de log conferidos pelo usu�rio
            if( isset($listaConfere) ) {
                    foreach($listaConfere as $idLog) {
                            $idLogParte=split(";",$idLog,2);
                            $idCasoUso=$idLogParte[0];
                            $idDataHora=$idLogParte[1];
                            $usuario->aceitarLog($idCasoUso,$idDataHora);
                    }
            }

            // Verifica se usu�rio aceitou todos os logs realizados na conta dele
            $listaLogNaoConferidos=$usuario->getLogsNaoConferidos();
            if(!empty($listaLogNaoConferidos)) { // Se o usu�rio ainda n�o aceitou todos os logs
                    $erro="Voc� deve aceitar todos os registros para continuar!";
                    require("$BASE_DIR/autenticar/validarLogForm.php");
                    exit;
            } else { // Usu�rio aceitou todos os logs.
                encaminharPaginaInicialPerfil( $usuario->getPerfil() );
            }

    } else if($acao == "prepararRecuperarSenha") {
        require_once "$BASE_DIR/autenticar/recuperarSenhaForm.php";
        exit;

    } else if($acao=="recuperarSenhaConfirmarEmail") {
        $nomeAcesso = $_POST["nomeAcesso"];
        $dataNascimento = $_POST["dataNascimento"];

        try {
            $usuario = Usuario::obterUsuarioPorNomeAcesso($nomeAcesso);
            $idPessoa = $usuario->getIdPessoa();
            $pessoa = Pessoa::obterPessoaPorId($idPessoa);
            $email = $pessoa->getEmail();
            if($pessoa->getDataNascimento()!=Util::dataBrParaSQL($dataNascimento)) {
                throw new Exception("Nome de acesso e/ou data de nascimento n�o conferem.
                Se voc� tem certeza que est� digitando corretamente seus dados,
                procure a secretaria para providenciar as corre��es.");
            }
            if( $usuario->isBloqueado() ) {
                throw new Exception( sprintf("Usu�rio '%s' est� bloqueado. Procure o administrador do sistema.", 
                        $usuario->getNomeAcesso() ) );
            }
        } catch(Exception $ex) {
            $erro = $ex->getMessage();
            require_once "$BASE_DIR/autenticar/recuperarSenhaForm.php";
            exit;
        }

        require_once "$BASE_DIR/autenticar/recuperarSenhaConfirmaEmail.php";
        exit;

    } else if($acao=="recuperarSenha") {
        $idPessoa = $_POST["idPessoa"];
        $nomeAcesso = $_POST["nomeAcesso"];
        $dataNascimento = $_POST["dataNascimento"];
        $email = $_POST["email"];
        
        try {
            Login::recuperarSenha($idPessoa,$nomeAcesso);
        } catch(Exception $ex) {
            $msg = $ex->getMessage();
            $erro = "Erro na opera��o. Mensagem: $msg";
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
        if( $perfil == Usuario::PROFESSOR ) {
            header("Location: /coruja/espacoProfessor/index_controle.php?acao=exibirIndex");
        } else {
            // Encaminha para a p�gina �ndice do Coruja
            header("Location: /coruja/baseCoruja/index.php");
        }
    }
?>