<?php

require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";

$idMatriz = $_POST['idMatriz'];
$siglaCurso = $_POST['siglaCurso'];
$acao = $_POST['acao'];

if ($acao === 'obter') {
    $matriz = MatrizCurricular::obterMatrizCurricular($siglaCurso, $idMatriz);
    $componentes = $matriz->obterComponentesCurriculares();

    $litagemComponentesSemValidacaoCompleta = array();
    foreach ($componentes as $componente) {
        $informacoesEquivalencia = $componente->obterInformacoesEquivalenciasPropostas();
        if($informacoesEquivalencia) {
            if($informacoesEquivalencia['informacoesCc']['estadoEquivalencia'] !== 'total') {
                $litagemComponentesSemValidacaoCompleta[] = $componente->getSiglaDisciplina();
            }
        }
    }

    echo json_encode($litagemComponentesSemValidacaoCompleta);
    exit;
} else if ($acao === 'efetivar') {
    if(!$usuario->temPermissao("UC11.01.02.04")) {
        echo "semPermissao";
        exit;
    }
    
    $dataInicialVigenciaProposta = Util::dataBrParaSQL($_POST['dataInicialVigencia']);
    
    $matrizAterior = MatrizCurricular::obterMatrizCurricular($siglaCurso, $idMatriz);
    $dataInicialVigenciaAnterior = $matrizAterior->getDataInicioVigencia();
    
    if (strtotime($dataInicialVigenciaAnterior) >= strtotime($dataInicialVigenciaProposta)) {
        echo "dataInvalida";
        exit;
    }
    
    $matrizProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatriz);
    $componentesPropostos = $matrizProposta->obterComponentes();
    
    $idNovaMatriz = $idMatriz + 1;
    
    $novaMatriz = new MatrizCurricular($siglaCurso, $idNovaMatriz);
    $novaMatriz->setDataInicioVigencia($dataInicialVigenciaProposta);
    $novaMatriz->criar();
    
    //criando componentes
    foreach ($componentesPropostos as $componenteProposto) {
        $componenteCurricular = new ComponenteCurricular($siglaCurso, $idNovaMatriz, $componenteProposto->getSiglaDisciplina());
        $componenteCurricular->setNomeDisciplina($componenteProposto->getNomeDisciplina());
        $componenteCurricular->setTipoComponenteCurricular($componenteProposto->getTipoComponenteCurricular());
        $componenteCurricular->setPeriodo($componenteProposto->getPeriodo());
        $componenteCurricular->setCargaHoraria($componenteProposto->getCargaHoraria());
        $componenteCurricular->setCreditos($componenteProposto->getCreditos());
        $componenteCurricular->setPosicaoPeriodo($componenteProposto->getPosicaoPeriodo());
        
        $componenteCurricular->criar();
    }
    
    $componentesCurriculares = $novaMatriz->obterComponentesCurriculares();
    
    foreach ($componentesCurriculares as $componenteCurricular) {
        $componenteProposto = ComponenteCurricularProposto::obterComponeteCurricular($siglaCurso, $idMatriz, $componenteCurricular->getSiglaDisciplina());
        $preRequisitosPropostos = $componenteProposto->obterPreRequisitos();        
        $componenteCurricular->definirPreRequisitos($preRequisitosPropostos);
    }
    
    $matrizProposta->deletar();
    
    $con = BD::conectar();
    $strLog = "Validada a Matriz Curricular Porposta do Curso " . $siglaCurso . " Com data inicial de vig&ecirc;ncia para " . $_POST['dataInicialVigencia'];
    $usuario->incluirLog("UC11.01.04", $strLog, $con);
    echo "sucesso";
}
