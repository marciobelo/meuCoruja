<?php
/**
 * Gera um PDF com o Resultado da Solicitação em Inscrição
 */
require_once("../../includes/comum.php");
require_once('fpdf/FpdfSiro.php');

// INCLUDE DA CLASSE DE ALOCA
include_once "$BASE_DIR/classes/Aloca.php";

// INCLUDE DA CLASSE DE TURMA
include_once "$BASE_DIR/classes/Turma.php";

// INCLUDE DA CLASSE MATRICULA
include_once "$BASE_DIR/classes/MatriculaAluno.php";

// INCLUDE DA CLASSE TEMPOSEMANAL
include_once "$BASE_DIR/classes/TempoSemanal.php";

class EmitirGradeHorariaPDF extends FpdfSiro {

    private $listaHorarioDasAulas;
    private $listaDisciplinaSegunda;
    private $listaDisciplinaTerca;
    private $listaDisciplinaQuarta;
    private $listaDisciplinaQuinta;
    private $listaDisciplinaSexta;
    private $listaDisciplinaSabado;
    private $identificao;
    private $listaHorarioDasAulasSabado;
    
    //variáveis do ambiente
    private $larguraMaxima;
    private $debug = 0;

    // $debug é usada para mostrar ou ocultar as bordas das tabelas
    //para fins de desenvolvimento

    function EmitirGradeHorariaPDF($listaHorarioDasAulas,$listaDisciplinaSegunda,$listaDisciplinaTerca,
            $listaDisciplinaQuarta,$listaDisciplinaQuinta,$listaDisciplinaSexta,$listaDisciplinaSabado, $identificao, $listaHorarioDasAulasSabado) {

            
        parent::FPDF('P'); //Ajusta página Vertical(Normal / Padrao)
        $this->AliasNbPages( '{total}' );
        $this->periodoLetivo = $periodoLetivo;
        $this->siglaCurso = $siglaCurso;
        $this->identificao = $identificao;
        
        $this->AddPage();
                
        $this->listaHorarioDasAulas = $listaHorarioDasAulas;
        $this->listaDisciplinaSegunda = $listaDisciplinaSegunda;;
        $this->listaDisciplinaTerca = $listaDisciplinaTerca;
        $this->listaDisciplinaQuarta= $listaDisciplinaQuarta;
        $this->listaDisciplinaQuinta = $listaDisciplinaQuinta;
        $this->listaDisciplinaSexta = $listaDisciplinaSexta;
        $this->listaDisciplinaSabado = $listaDisciplinaSabado;
        $this->listaHorarioDasAulasSabado=$listaHorarioDasAulasSabado;
        
        //Ajusta as variáveis de ambiente
        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);

    }

    function gerarCabecalho() {

        //Logo do IST e Nome do Instituto (Primeiras 4 linhas)
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

        $this->SetFont('Arial','',$tamFonteMedia);
        
        $txt="Grade Horária do Período Letivo em Vigor ".$this->identificao;
        
        $this->SetY(36);
        $this->MultiCell($tamHorizontalDoCabecalho,4.5,$txt, $this->debug,'L');
        $this->SetY(58);

        $espacamentoHorizontal = 5;       
    }

    function desenharResultado() {
        
        $this->SetY($this->GetY());
        //Desce um pouco o cursor

        $tamanGomoN1 = 35;

        $tamanGomoN2 = 25;
        $tamanGomoN3 = 25;
        $tamanGomoN4 = 25;
        
        $tamanGomoN5 = 25;
        $tamanGomoN6 = 25;
                
        $fonteMedia = 9;
        $espacamentoHorizontalMedio = 2.5;
        $fonteGrande = 9;
        $espacamentoHorizontalGrande = 4.5;

        $this->SetFont('Arial','B',$fonteGrande);

        $txt='Horários das Aulas';
        $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Segunda';
        $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Terça';
        $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Quarta';
        $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Quinta';
        $this->Cell($tamanGomoN5, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Sexta';
        $this->Cell($tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');
        
        $this->SetFont('Arial','',$fonteMedia);

        $this->SetWidths(array($tamanGomoN1,$tamanGomoN2,$tamanGomoN3,$tamanGomoN4,$tamanGomoN5,$tamanGomoN6));//CADA VALOR DESTE ARRAY SERÁ A LARGURA DE CADA COLUNA

        srand(microtime()*1000000);

  
        //MONTANDO AS GRADES HORARIAS
        for($i=0;$i<sizeof($this->listaHorarioDasAulas);$i++) {

            $horario = $this->listaHorarioDasAulas[$i];
            $segunda= $this->listaDisciplinaSegunda[$i];
            $terca = $this->listaDisciplinaTerca[$i];
            $quarta =  $this->listaDisciplinaQuarta[$i];
            $quinta = $this->listaDisciplinaQuinta[$i];
            $sexta = $this->listaDisciplinaSexta[$i];
            
            $this->Row(array("$horario","$segunda","$terca","$quarta","$quinta","$sexta"));
        }

        $txt= "\n";
        $this->MultiCell($tamHorizontalDoCabecalho,4.5,$txt, $this->debug,'L');

        $this->SetFont('Arial','B',$fonteGrande);

        $txt='Horários das Aulas';
        $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Sábado';
        $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');

        $this->SetFont('Arial','',$fonteMedia);
        
        //MONTANDO A GRADE HORARIA DE SABADO
        for($i=0;$i<sizeof($this->listaHorarioDasAulasSabado);$i++) {

            $horario = $this->listaHorarioDasAulasSabado[$i];
            $sabado = $this->listaDisciplinaSabado[$i];

            $this->Row(array("$horario","$sabado"));
        }     

    }
    function gerarFooter() {
        $tamanho = 1;
        
        $this->SetY(-1 * ($this->bMargin + $tamanho)); //posiciona o cursor
        $this->SetFont('Arial','',12);

        $this->Cell($this->larguraMaxima, 0, 'Coruja', $this->debug,1,'L');
        $this->Cell($this->larguraMaxima, 0, 'Emitido em: '.date("d/m/Y"), $this->debug,1,'C');
        $this->Cell($this->larguraMaxima, 0, 'Página: '.$this->PageNo().' de {total}', $this->debug,1,'R');
        
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

 
if($_POST){
    $listaHorarioDasAulas = $_POST['listaHorarioDasAulas'];
    $listaDisciplinaSegunda = $_POST['listaDisciplinaSegunda'];
    $listaDisciplinaTerca = $_POST['listaDisciplinaTerca'];
    $listaDisciplinaQuarta= $_POST['listaDisciplinaQuarta'];
    $listaDisciplinaQuinta = $_POST['listaDisciplinaQuinta'];
    $listaDisciplinaSexta = $_POST['listaDisciplinaSexta'];
    $listaDisciplinaSabado = $_POST['listaDisciplinaSabado'];
    $identificao = $_POST['identificao'];
    $listaHorarioDasAulasSabado = $_POST['listaHorarioDasAulasSabado'];
    
    $pdf = new EmitirGradeHorariaPDF($listaHorarioDasAulas,$listaDisciplinaSegunda,$listaDisciplinaTerca,
            $listaDisciplinaQuarta,$listaDisciplinaQuinta,$listaDisciplinaSexta,$listaDisciplinaSabado, $identificao, $listaHorarioDasAulasSabado);

    $pdf->desenharResultado();
    $pdf->output();
    
} else {
    echo '<h4>USO INCORRETO</h4>';
}
?>