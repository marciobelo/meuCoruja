<?php

$larguraDeUmPeriodo = 130;
$alturaDoTituloDoPeriodo = 30;

//medidas da imagem principal
$larguraImagem = $qtdePeriodos * $larguraDeUmPeriodo + 1;
$alturaImagem = 450;
$alturaCreditos = 30;
if ($maiorQtdeComponentesNumPeriodo > 8) {
    $alturaImagem = $alturaDoTituloDoPeriodo + $maiorQtdeComponentesNumPeriodo * 30 + $maiorQtdeComponentesNumPeriodo * 10 + $alturaCreditos;
}

$img = imagecreatetruecolor($larguraImagem, $alturaImagem); 

$white = imagecolorallocate($img, 255, 255, 255);
$red = imagecolorallocate($img, 255, 0, 0);
$black = imagecolorallocate($img, 0, 0, 0);

imagefill($img, 0, 0, $white);

$periodoAtual = (int)$qtdePeriodos;

//Informacoes de Periodo
$x2Periodo = $qtdePeriodos * $larguraDeUmPeriodo -1;
$x1Periodo = $x2Periodo - $larguraDeUmPeriodo;
$y1Periodo = 0;
$y2Periodo = $alturaImagem -1;

//Informacoes de componentes
$margemTopoComponente = 10;
$margemLateralComponente = 20;
$larguraDeUmComponente = $x2Periodo - 2 * $margemLateralComponente;
$alturaDeUmComponente = 30;

while ($periodoAtual > 0) {
    
    //periodo
    imagerectangle($img, $x1Periodo + 0.5, $y1Periodo, $x2Periodo, $y2Periodo, $black);
    
    //titulo periodo
    imagerectangle($img, $x1Periodo + 0.5, $y1Periodo, $x2Periodo, $alturaDoTituloDoPeriodo, $black);
    
    $string = $tituloPeriodos[$periodoAtual] . ' Periodo';
    imagestring ( $img, 4, $x1Periodo + 10, $y1Periodo+5, $string, $black);
    
    $x2Periodo -= $larguraDeUmPeriodo;
    $x1Periodo -= $larguraDeUmPeriodo;
    
    //evitando que a margem esquerda da matriz se perca
    if ($periodoAtual === 2) {
        $x1Periodo += 1;
    }
    
    $periodoAtual--;
}

$periodoAtual = (int)$qtdePeriodos;

//Informacoes de Periodo
$x2Periodo = $qtdePeriodos * $larguraDeUmPeriodo -1;
$x1Periodo = $x2Periodo - $larguraDeUmPeriodo;
$y1Periodo = 0;
$y2Periodo = 447;
$alturaDoTituloDoPeriodo = 30;

//Informacoes de componentes
$margemTopoComponente = 10;
$margemLateralComponente = 20;
$larguraDeUmComponente = $larguraDeUmPeriodo - 2 * $margemLateralComponente;

$alturaDeUmComponente = 30;

//Componentes
$x1Componente = $x1Periodo + $margemLateralComponente;
$y1Componente = $y1Periodo + $alturaDoTituloDoPeriodo + $margemTopoComponente;
$x2Componente = $x2Periodo - 20;
$y2Componente = $y1Componente + $alturaDeUmComponente;

while ($periodoAtual > 0) {
    if(!is_null($componentesPorPeriodos[$periodoAtual])) {
        foreach ($componentesPorPeriodos[$periodoAtual] as $componente) {
            if ((int)$componente->getPeriodo() > 1) {
                if ($componente->obterPreRequisitos()) {
                    foreach($componente->obterPreRequisitos() as $preRequisito) {
                        
                        //largura entre componentes
                        $periodosCompletosDeDiferenca = ((int)$componente->getPeriodo() - (int)$preRequisito->getPeriodo()) - 1;

                        //adicionando margens entre componentes e periodo
                        $distanciaEntreComponentes = $periodosCompletosDeDiferenca * $larguraDeUmPeriodo + 20;

                        //altura do preRequisito
                        $somatorioAlturaComponentesPeriodoPreRequisito = ((int)$preRequisito->getPosicaoPeriodo()+1) * $alturaDeUmComponente;
                        $somatorioAlturaMargensPeriodoPreRequisito = ((int)$preRequisito->getPosicaoPeriodo()+1) * $margemTopoComponente;

                        //y1
                        $posicaoSetinhaNoPreRequisito = $somatorioAlturaComponentesPeriodoPreRequisito + $somatorioAlturaMargensPeriodoPreRequisito + $alturaDeUmComponente/2;
                        //y2
                        $posicaoSetinhaNoComponente = $y1Componente + $alturaDeUmComponente/2;
                        
                        imageline ( $img , $x1Componente - $distanciaEntreComponentes -20 , $posicaoSetinhaNoPreRequisito , $x1Componente-6 , $posicaoSetinhaNoComponente , $black );
                        
                        
                        //setinha preenchida
                        $values = array(
                                        $x1Componente - 5 ,  $posicaoSetinhaNoComponente - 10,
                                        $x1Componente, $posicaoSetinhaNoComponente,
                                        $x1Componente - 5, $posicaoSetinhaNoComponente + 10
                                    );
                        
                        imagefilledpolygon($img, $values, 3, $black);
                    }
                }
            }
            //retangulo Componente
            imagefilledrectangle($img, $x1Componente, $y1Componente, $x2Componente, $y2Componente, $white);
            imagerectangle($img, $x1Componente, $y1Componente, $x2Componente, $y2Componente, $black);

            //sigla componente
            $x1SglaComponente = 0;
            $siglaDisciplina = $componente->getSiglaDisciplina();
            $margemFixa = $x1Componente + 40;
            $x1SglaComponente = $margemFixa;
            $reducaoMargem = 0;
            $tamanhoSigla = strLen($siglaDisciplina);
            
            if ((int)$tamanhoSigla === 1) {
                $x1SglaComponente += 10;
            } else if ((int)$tamanhoSigla < 3) {
                $x1SglaComponente += $tamanhoSigla * 3;
                
            } else if ((int)$tamanhoSigla > 3 && (int)$tamanhoSigla < 6) {
                $x1SglaComponente -= ($tamanhoSigla - 3) * 5;
            } else {
                $x1SglaComponente -= ($tamanhoSigla - 3) * 4;
            }
            
            imagestring ($img, 5, $x1SglaComponente-10, $y1Componente+5, $siglaDisciplina, $black);

            $y1Componente +=  40;
            $y2Componente =  $y1Componente + 30; 
        }
    }
    
    $qtdeCreditosPeriodo = (isset($qtdeCreditosPorPeriodo[$periodoAtual])) ? $qtdeCreditosPorPeriodo[$periodoAtual] : 0;
    imagestring ($img, 4, $x1Componente - 5, $alturaImagem - 20, 'Créditos: ' . $qtdeCreditosPeriodo , $black);
        
    $x1Componente -= 130;
    $x2Componente -= 130;
    $y1Componente =  40;
    $y2Componente =  $y1Componente + 30;
    
    $periodoAtual--;
}

//header('Content-Type: image/png');
//
//imagepng($img);exit;