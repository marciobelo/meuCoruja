<?php
    $RAIZ_CORUJA = "/coruja"; // diretório a partir da raiz do servidor http
    $BASE_DIR = __DIR__ . "/.."; // dir. absoluto no sistema de arquivos
    header( "Content-Type: text/html; charset=ISO-8859-1");
    
    /**
    * Tratador de erro genérico
    */
    function tratadorGenericoErro($errno, $errstr, $errfile, $errline)
    {
        switch ($errno) {
        case E_USER_ERROR:
            echo "<html><head>\n";
            echo "<title>Coruja - Erro Fatal</title>";
            echo "</head><body>\n";
            echo "<h1>Ocorreu um erro inesperado</h1>\n";
            echo "<table><tr><td>";
            echo "<b>C&oacute;digo:</b>\n";
            echo "</td><td>\n";
            echo $errno . "\n";
            echo "</td></tr><tr><td>\n";
            echo "<b>Descri&ccedil;&atilde;o:</b>\n";
            echo "</td><td>\n";
            echo $errstr;
            echo "</td></tr><tr><td>\n";
            echo "<b>Arquivo-fonte:</b>\n";
            echo "</td><td>\n";
            echo $errfile;
            echo "</td></tr><tr><td>\n";
            echo "<b>Linha:</b>\n";
            echo "</td><td>\n";
            echo $errline;
            echo "</td></tr><tr><td>\n";
            echo "<b>Ambiente:</b>\n";
            echo "</td><td>\n";
            echo "PHP " . PHP_VERSION . " (" . PHP_OS . ")";
            echo "</td></tr></table>\n";
            echo "<p>Por favor comunique este erro pela Central de Atendimento (http://www.faeterj-rio.edu.br/atendimento)</p>";
            echo "</body></html>";
            exit(1);
            break;
        }
        /* Don't execute PHP internal error handler */
        return true;
    }

    // set to the user defined error handler
    set_error_handler("tratadorGenericoErro");

    // Lê as configurações do sistema
    require "$BASE_DIR/config.php";
    
    /*	Lê definição das classes
            Atenção: a definição das classes devem ser carregadas antes da recuperação dos dados da sessão
            BUG documentado em http://www.webdeveloper.com/forum/showthread.php?t=144267
    */
    require_once "$BASE_DIR/classes/Login.php";
	
    // Inicia o gerenciamento de sessão
    session_start();

    // Caso não seja a tela de login, verifica a autenticação
    if( $_SERVER['PHP_SELF'] != "/coruja/autenticar/login_controle.php" ) {

        /** Verifica se existe usuário autenticado em sessão, caso contrário, desvia para página de login */
        $login = $_SESSION["login"];
        if( (!isset($login)) || (!$login->getValidouLog()) ) {
                // Encaminha para a página de autenticação
                header("Location: /coruja/index.php");
                exit;
        }
    }
    require_once "$BASE_DIR/classes/Util.php";