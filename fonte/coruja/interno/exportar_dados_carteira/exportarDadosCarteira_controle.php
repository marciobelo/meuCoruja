<?php
require_once("../../includes/comum.php");
require_once "$BASE_DIR/classes/MatriculaAluno.php";

// Verifica Permissão
$usuario = $_SESSION["usuario"];
if(!$usuario->temPermissao($EXPORTAR_DADOS_CARTEIRA)) {
    require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
    exit;
} else {
    $colecao = MatriculaAluno::obterDadosCarteirinha();
    $usuario->incluirLog($EXPORTAR_DADOS_CARTEIRA, "Exportado os dados dos alunos para emissão de carteirinha escolar");
    require_once("$BASE_DIR/interno/exportar_dados_carteira/planilha_carteira.php");
}
?>