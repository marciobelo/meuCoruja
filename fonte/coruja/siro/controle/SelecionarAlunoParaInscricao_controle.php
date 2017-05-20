<?php
require_once("../../includes/comum.php");
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/siro/classes/buscaAluno.php";

require_once "$BASE_DIR/includes/topo.php";

// MENU HORIZONTAL
echo '<div id="menuprincipal">';
    require_once "$BASE_DIR/includes/menu_horizontal.php";
echo '</div>';

// Recupera o usu?rio logado da sess?o
$usuario = $_SESSION["usuario"];
$idPessoa = $usuario->getIdPessoa();

//VERIFICA SE E ALUNO
if(!$usuario->temPermissao($SELECIONAR_ALUNO_PARA_INSCRICAO)){
    require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
    exit;
}

//CONTEUDO
echo '<div id="conteudo">';

$action = filter_input( INPUT_GET, "action", FILTER_SANITIZE_STRING);

if($action =="selecionaAluno" || $action == "") 
{

    require_once "$BASE_DIR/siro/formularios/pesquisarAluno.php";
}
elseif($action === "exibir") 
{
    $tipoBusca = filter_input( INPUT_POST, "tipoBusca", FILTER_SANITIZE_STRING);
    $nome = filter_input( INPUT_POST, "nome", FILTER_SANITIZE_STRING);
    $matricula = filter_input( INPUT_POST, "matricula", FILTER_SANITIZE_STRING);
    
    $classeBuscaAluno = new buscaAluno();

    if($tipoBusca == "nome"){
        $busca = $classeBuscaAluno->buscaAlunoByNome( $nome);
    }
    elseif($tipoBusca == "matricula"){
        $busca = $classeBuscaAluno->buscaAlunoByMatricula( $matricula);
    }

    if(empty($busca)){//EXIBE A MENSAGEM E CONTINUA NA MESMA TELA

        echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
        echo "<font>Nenhum aluno encontrado com os par&acirc;metros informados!</font>";
        echo "</fieldset></form>";

        require_once "$BASE_DIR/siro/formularios/pesquisarAluno.php";

    }
    else{
        require_once "$BASE_DIR/siro/formularios/selecionarAluno.php";
    }
    
}

echo '</div>';

// RODAPÉ DA PÁGINA
include_once "$BASE_DIR/includes/rodape.php";