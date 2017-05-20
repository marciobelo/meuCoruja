<?php


require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";
require_once "$BASE_DIR/classes/Curso.php";

if(!$usuario->temPermissao("UC11.01.00")) {
    require_once "../sem_permissao.php";
    exit;
} else {
    $con = BD::conectar();
    $strLog = "Consultadas as Matrizes Curriculares Propostas do sistema";
    $usuario->incluirLog("UC11.01.00", $strLog, $con);
}

$mensagemValidacao = (isset($_POST['mensagemValidacao']) && !empty($_POST['mensagemValidacao'])) ? $_POST['mensagemValidacao'] : '';

$cursos = Curso::obterListaCurso();
$siglaMatrizesExistentes = array();
$siglaTodasMatrizes = array();

foreach ($cursos as $curso) {
    $matrizesPorSiglaCurso = MatrizCurricular::obterListaMatrizCurricularPorSiglaCurso($curso->getSiglaCurso());
    
    $matrizMaisRecente = $matrizesPorSiglaCurso[0];
    $matrizesToView[] = $matrizMaisRecente;
    
    $matrizProposta = MatrizCurricularProposta::obter($matrizMaisRecente->getSiglaCurso(), $matrizMaisRecente->getIdMatriz());
    if (!is_null($matrizProposta)) {
        $siglaMatrizesExistentes[] = $matrizMaisRecente->getSiglaCurso();
    }
    
    $siglaTodasMatrizes[] = $matrizMaisRecente->getSiglaCurso();
}

require_once "$BASE_DIR/mmc_gpl/matrizCurricular/listaMatrizCurricularPropostaForm.php";
