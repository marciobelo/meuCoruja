<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Professor.php";

$acao=$_REQUEST["acao"];
$controleDestino = $_REQUEST["controleDestino"];
$acaoControleDestino = $_REQUEST["acaoControleDestino"];
$controleDestinoTitulo = $_REQUEST["controleDestinoTitulo"];

if($acao=="exibirFiltroPesquisa") {
    require_once "$BASE_DIR/interno/selecionar_matricula_professor/telaFiltroPesquisa.php";

} else if($acao=="exibirResultado") {
    require_once "$BASE_DIR/classes/MatriculaProfessor.php";
   
    $tipoBusca = $_REQUEST["tipoBusca"];
    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($MANTER_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    
    if($tipoBusca=="matricula") {
        $matricula = $_REQUEST["matricula"];
        $professor = Professor::obterProfessorPorMatricula($matricula);
        $criterio='Matr&iacute;cula';
        
        if($professor == null) {
            $msgsErro=array();
            array_push($msgsErro, "Nenhum registro encontrado. Informe novos parâmetros.");
            require_once "$BASE_DIR/interno/selecionar_matricula_professor/exibe_tela_procura_cadastro.php";
            exit;
        }else {
            $listaMatriculasProfessor = array();
            array_push($listaMatriculasProfessor, $professor);
            require_once "$BASE_DIR/interno/selecionar_matricula_professor/telaResultado.php";
            exit;
        }

    } else { 
        $nome = $_REQUEST["nome"];
        $listaMatriculasProfessor=Professor::obterProfessoresPorNome($nome);
         $criterio='Nome';
        if(count($listaMatriculasProfessor)==0) {
            $msgsErro=array();
            array_push($msgsErro, "Nenhum registro encontrado. Informe novos parâmetros.");
            require_once "$BASE_DIR/interno/selecionar_matricula_professor/exibe_tela_procura_cadastro.php";
            exit;
        } else {
            require_once "$BASE_DIR/interno/selecionar_matricula_professor/telaResultado.php";
            exit;
        }
    }

} else {
    trigger_error("Ação não identificada.",E_USER_ERROR);
}
?>
