<?php

require_once "../../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";
require_once "$BASE_DIR/mmc_gpl/matrizCurricular/imprimirMatriz/imprimirMatrizPDF.php";

if(!$usuario->temPermissao("UC11.01.04")) {
    require_once "../../sem_permissao.php";
    exit;
}
        
$siglaCurso = $_POST['siglaCurso'];
$isProposta = $_POST['isProposta'];
$idMatriz = $_POST['idMatriz'];
$nomeCurso = Curso::obterCurso($siglaCurso)->getNomeCurso();

$dataVigencia = '';
$componentesPossuemPosicaoPeriodo = true;
if ($isProposta === 'true') {
    $matrizProposta = MatrizCurricularProposta::obterPorSiglaCuros($siglaCurso);
    $componentesPropostos = $matrizProposta->obterComponentes();
    
    $qtdePeriodos = 0; 
    foreach($componentesPropostos as $componente) {
        $periodoDoComponente = $componente->getPeriodo();
        $qtdePeriodos = ($periodoDoComponente > $qtdePeriodos) ? $periodoDoComponente : $qtdePeriodos;
    }
    $componentes = $componentesPropostos;
    
    //ordenando Componentes no periodo
    $componentesPorPeriodos = array();
    foreach ($componentes as $ccp) {
        $componentesPorPeriodos[$ccp->getPeriodo()][] = $ccp; 
    }
    
    $CHTotal = 0;
    $maiorQtdeComponentesNumPeriodo = 0;
    foreach($componentesPorPeriodos as $key => $componentesDoPeriodo) {
        $posicoesNoperiodo = array();
        $creditosDoPeriodo = 0;
        foreach($componentesDoPeriodo as $componete) {
            $posicoesNoperiodo[] = $componete->getPosicaoPeriodo();
            $creditosDoPeriodo += $componete->getCreditos();
            $CHTotalComponentes += $componete->getCargaHoraria();
        }
        
        array_multisort($posicoesNoperiodo, $componentesDoPeriodo);
        $componentesPorPeriodos[$key] = $componentesDoPeriodo;

        $maiorQtdeComponentesNumPeriodo = (count($posicoesNoperiodo) > $maiorQtdeComponentesNumPeriodo) ? 
                                            count($posicoesNoperiodo) : $maiorQtdeComponentesNumPeriodo;
        
        $qtdeCreditosPorPeriodo[$key] = $creditosDoPeriodo;
    }
    
    ksort($componentesPorPeriodos);
    ksort($qtdeCreditosPorPeriodo);
    //fim da ordenacao

} else {
    $matriz = MatrizCurricular::obterMatrizCurricular($siglaCurso, $idMatriz);
    $componentes = $matriz->obterComponentesCurriculares();
    $dataVigencia = Util::dataSQLParaBr($matriz->getDataInicioVigencia());
    
    $maiorPosicaoPeriodo = 0;
    $qtdePeriodos = 0; 
    foreach($componentes as $componente) {
        $periodoDoComponente = $componente->getPeriodo();
        $qtdePeriodos = ($periodoDoComponente > $qtdePeriodos) ? $periodoDoComponente : $qtdePeriodos;
        $maiorPosicaoPeriodo = ($componente->getPosicaoPeriodo() > $maiorPosicaoPeriodo) ? $componente->getPosicaoPeriodo() : $maiorPosicaoPeriodo;
    }
    
    $componentesPorPeriodos = array();
    foreach ($componentes as $ccp) {
        $componentesPorPeriodos[$ccp->getPeriodo()][] = $ccp; 
    }
    
    $CHTotal = 0;
    $maiorQtdeComponentesNumPeriodo = 0;
    foreach($componentesPorPeriodos as $key => $componentesDoPeriodo) {
        $posicoesNoperiodo = array();
        $creditosDoPeriodo = 0;
        foreach($componentesDoPeriodo as $posicaoNoPriodo => $componente) {
            // componentes antigos não possuem posicao periodo
            
            if ((int)$maiorPosicaoPeriodo === 0) {
                $componente->definirPosicaoPeriodo($posicaoNoPriodo);
                $componente->setPosicaoPeriodo($posicaoNoPriodo);
            } else {
                $posicoesNoperiodo[] = $componente->getPosicaoPeriodo();
            }
                
            $creditosDoPeriodo += $componente->getCreditos();
            $CHTotalComponentes += $componente->getCargaHoraria();
        }
        
        array_multisort($posicoesNoperiodo, $componentesDoPeriodo);
        $componentesPorPeriodos[$key] = $componentesDoPeriodo;

        $maiorQtdeComponentesNumPeriodo = (count($posicoesNoperiodo) > $maiorQtdeComponentesNumPeriodo) ? 
                                            count($posicoesNoperiodo) : $maiorQtdeComponentesNumPeriodo;
        
        $qtdeCreditosPorPeriodo[$key] = $creditosDoPeriodo;
    }
    
    ksort($componentesPorPeriodos);
    ksort($qtdeCreditosPorPeriodo);
}

$ultimaPosicao = array_keys(array_slice( $componentesPorPeriodos, -1, 1, TRUE ));
$maiorPeriodo = $ultimaPosicao[0];

$tituloPeriodos = array('1'=>'1o.', 
                        '2'=>'2o.',
                        '3'=>'3o.',
                        '4'=>'4o.',
                        '5'=>'5o.',
                        '6'=>'6o.',
                        '7'=>'7o.',
                        '8'=>'8o.',
                        '9'=>'9o.',
                        '10'=> '10o.');

//transformar num metodo da classe ImprimirMatrizPDF
require_once "$BASE_DIR/mmc_gpl/matrizCurricular/imprimirMatriz/gerarImagemMatriz_controle.php";

$pdf = new ImprimirMatrizPDF();

$pdf->setComponentesCurriculares($componentes);

$pdf->AddPage('P', 'A4');

$pdf->montaCabecalho($dataVigencia, $nomeCurso);


if ($maiorPeriodo < 5) {
    $larguraDaImgem = $maiorPeriodo * 38;
    $margemAdicional = (190 - $larguraDaImgem)/2;
    
    $pdf->GDImage($img, 10 + $margemAdicional, 54, $larguraDaImgem, 75);
} else {
    $pdf->GDImage($img, 10, 54, 190, 75);
}

$pdf->insereQuebraDeLinha(37);

$pdf->montaTabelaDisciplinas($CHTotalComponentes);

$pdf->Output();

//log
$con = BD::conectar();
$strLog = "Impressa a Matriz Curricular Proposta do curso " . $siglaCurso;
if ($isProposta !== 'true') {
    $strLog = "Impressa a Matriz Curricular com data inicial de vig&ecirc;ncia de " . $dataVigencia . " do curso " . $siglaCurso;
}
$usuario->incluirLog('UC11.01.04', $strLog, $con);
