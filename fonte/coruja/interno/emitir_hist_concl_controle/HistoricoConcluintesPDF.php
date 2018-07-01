<?php

require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/interno/class/HistoricoConcluintesPDF.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";

ini_set("memory_limit", "800000M");


if ($_REQUEST["acao"]=="exibirPDF") 
{
    $PDF = new HistoricoConcluintesPDF();
    $PDF->setExibeCR( $_REQUEST['exibeCR']=='S');
    if($PDF->verificaArquivo($_REQUEST['mat'])==false){
        $PDF->criarXML($_REQUEST['dtini'], $_REQUEST['dtfim'], $_REQUEST['estabelecimentoVestibular'], $_REQUEST['chtda'], $_REQUEST['ches'], $_REQUEST['chaec'], $_REQUEST['tchc'],
         $_REQUEST['titulo'], $_REQUEST['dtdefesa'], $_REQUEST['ntcc'], $_REQUEST['enade'], $_REQUEST['dtcolacao'], $_REQUEST['dtExpedicaoDiploma'], $_REQUEST['dtemissao'], 
                $_REQUEST['observacao'], $_REQUEST['exclusivoRegistro']);
    }
    $PDF->AddPage('P', 'A4');
    $PDF->gerarCabecalho();
    $PDF->obterDescricaoDoAluno($_REQUEST['mat']);
    $PDF->obterDadosGradeCurricular($_REQUEST['mat']);
    $PDF->Output();
}