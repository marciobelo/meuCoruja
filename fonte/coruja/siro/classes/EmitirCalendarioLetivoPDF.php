<?php
/**
 * Gera um PDF com o Calendario Letivo
 *
 * A maior parte do documento é gerada utilizando Cells (Tabela), tratando uma linha
 * por vez
 *
 * @author: Dyego Silva, Camila Areal
 * @name: EmitirCalendarioLetivoPDF.php
 * @version: 1.0
 * @since: versão 1.0
 */

require_once("../../includes/comum.php");
require_once('fpdf/fpdf.php');
include_once "$BASE_DIR/classes/PeriodoLetivo.php";
include_once "$BASE_DIR/classes/Curso.php";


class EmitirCalendarioLetivoPDF extends FPDF 
{
    private $collectionAnos;
    private $collectionMeses;
    private $collectionDias;
    private $collectionDescricoes;
    private $idPeriodoLetivo;

    private $qntDePaginas;

    //variáveis do ambiente
    private $larguraMaxima;
    private $debug = 0;
    // $debug é usada para mostrar ou ocultar as bordas das tabelas
    //para fins de desenvolvimento

    function EmitirCalendarioLetivoPDF(
            $collectionAnos,
            $collectionMeses,
            $collectionDias,
            $collectionDescricoes,
            $idPeriodoLetivo) {

        parent::FPDF('P'); //Ajusta página Vertical(Normal / Padrao)
        $this->AliasNbPages( '{total}' );
        
        $this->idPeriodoLetivo=$idPeriodoLetivo;
        
        $this->AddPage();
        
        $this->collectionAnos = $collectionAnos;
        $this->collectionMeses = $collectionMeses;
        $this->collectionDias = $collectionDias;
        $this->collectionDescricoes = $collectionDescricoes;
        $this->idPeriodoLetivo=$idPeriodoLetivo;
        //Ajusta as variáveis de ambiente
        $this->qntDePaginas = 1;
        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);

    }

    function gerarCabecalho() {

        $margemDoCabecalho = 32;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;
        
        $this->Image("../../imagens/logorj.jpg",$this->lMargin,$this->tMargin,20);

        $this->SetX($margemDoCabecalho);
        $this->SetFont('Arial','',$tamFonteMedia);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO;               
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        $espacamentoHorizontal = 5;
        $this->SetFont('Arial','',$tamFonteGrande);

        $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo( $this->idPeriodoLetivo );

        $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());

        $txt="Calendário Letivo para o Período Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio().
                " - ".$periodoLetivo->getDataFim().")";
        
        $txt.="\nCurso: ".$classeCurso->getSiglaCurso();
        $txt.=" - ".$classeCurso->getNomeCurso();
        $this->SetXY(32,30);
        $this->MultiCell($tamHorizontalDoCabecalho,5,$txt, $this->debug,'L');

        $this->SetY(43);
        $this->SetX(125);

        $espacamentoHorizontal = 5;

    }

    function desenharResultado() {
        $this->SetY($this->GetY()+7);
        $this->SetX($this->GetX()+10);
        //Desce um pouco o cursor

        $tamanhoAno = 10;
        $tamanhoMes = 20;
        $tamanhoDia = 10;
        $tamanhoAtiv = 132;

        $fonteMedia = 9;
        $fonteGrande = 9;
        $espacamentoHorizontalGrande = 4.5;

        $this->SetFont('Arial','B',$fonteGrande);
        $this->Cell($tamanhoAno, $espacamentoHorizontalGrande, 'ANO', 1, 0, 'C');
        $this->Cell($tamanhoMes, $espacamentoHorizontalGrande, 'MÊS', 1, 0, 'C');
        $this->Cell($tamanhoDia, $espacamentoHorizontalGrande, 'DIA', 1, 0, 'C');
        $this->Cell($tamanhoAtiv, $espacamentoHorizontalGrande, 'ATIVIDADE', 1, 1, 'C');
        $this->SetFont('Arial','',$fonteMedia);

        for($row=0; $row<sizeof($this->collectionMeses); $row++) 
        {
            if($this->GetY() > 270)
            {
                $this->AddPage();
		$this->Ln();
		$this->Cell(10);
            }
            else
            {
                $this->SetX($this->GetX()+10);
            }
            
            $ano = $this->collectionAnos[ $row];
            $mes = $this->collectionMeses[$row];
            $dia = $this->collectionDias[$row];
            $atividade = $this->collectionDescricoes[$row];

            $this->Cell($tamanhoAno, $espacamentoHorizontalGrande, $ano, 1, 0, 'C');
            $this->Cell($tamanhoMes, $espacamentoHorizontalGrande, $mes, 1, 0, 'C');
            $this->Cell($tamanhoDia, $espacamentoHorizontalGrande, $dia, 1, 0, 'C');
            $this->Cell($tamanhoAtiv, $espacamentoHorizontalGrande, $atividade, 1, 1, 'L');
        }

    }
    function gerarFooter() {
        $tamanho = 1;

        $this->SetY(-1 * ($this->bMargin + $tamanho)); //posiciona o cursor
        $this->SetFont('Arial','',12);

        $this->Cell($this->larguraMaxima, 0, 'Coruja', $this->debug,1,'L');
        $this->Cell($this->larguraMaxima, 0, 'Emitido em '.date("d/m/Y"), $this->debug,1,'C');
        $this->Cell($this->larguraMaxima, 0, 'Página '.$this->PageNo().' de {total}', $this->debug,1,'R');

        $this->SetFont('Arial','',10);

        $tamanho = 0;
        $this->SetY(-1 * ($this->bMargin + $tamanho));
        $this->Ln(30);
    }

    function  Header() {
        $this->gerarCabecalho();
    }
    function  Footer() {
        $this->gerarFooter();
    }
}

$collectionAnos = $_POST['collectionAnos'];
$collectionMeses = $_POST['collectionMeses'];
$collectionDias = $_POST['collectionDias'];
$collectionDescricoes = $_POST['collectionDescricoes'];
$idPeriodoLetivo = $_POST['idPeriodoLetivo'];

$pdf = new EmitirCalendarioLetivoPDF($collectionAnos, $collectionMeses,
        $collectionDias,$collectionDescricoes,$idPeriodoLetivo);

$pdf->desenharResultado();
$pdf->output();