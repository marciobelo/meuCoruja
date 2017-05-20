<?php
/**
 * Gera um PDF com o Resultado da Solicitação em Inscrição
 *
 * A maior parte do documento é gerada utilizando Cells (Tabela), tratando uma linha
 * por vez
 *
 * @author: Camila Santana
 * @name: EmitirResultadoSolicitacaoInscricaoPDF.php
 * @version: 1.0
 * @since: versão 1.0
 */

require_once("../../includes/comum.php");
require_once('fpdf/FpdfSiro.php');
include_once "$BASE_DIR/classes/Curso.php";

class EmitirResultadoSolicitacaoInscricaoPDF extends FpdfSiro {

    private $listaDeMatriculaAluno;
    private $listaDeNomeAluno;
    private $listaDeGradeHorarios;
    private $listaDeDisciplinas;
    private $listaDeTurnos;
    private $listaDeSituacoes;
    private $listaDeParecerInscricao;
    private $periodoLetivo;
    private $siglaCurso;

    //variáveis do ambiente
    private $larguraMaxima;
    private $debug = 0;

    // $debug é usada para mostrar ou ocultar as bordas das tabelas
    //para fins de desenvolvimento

    function EmitirResultadoSolicitacaoInscricaoPDF(
            $listaDeMatriculaAluno,
            $listaDeNomeAluno,
            $listaDeGradeHorarios,
            $listaDeDisciplinas,
            $listaDeTurnos,
            $listaDeSituacoes,
            $listaDeParecerInscricao,
            $periodoLetivo,
            $siglaCurso) {

            
        parent::FPDF('P'); //Ajusta página Vertical(Normal / Padrao)
        $this->AliasNbPages( '{total}' );
        $this->periodoLetivo = $periodoLetivo;
        $this->siglaCurso = $siglaCurso;

        $this->AddPage();
                
        $this->listaDeMatriculaAluno = $listaDeMatriculaAluno;
        $this->listaDeNomeAluno = $listaDeNomeAluno;
        $this->listaDeGradeHorarios = $listaDeGradeHorarios;
        $this->listaDeDisciplinas = $listaDeDisciplinas;
        $this->listaDeTurnos = $listaDeTurnos;
        $this->listaDeSituacoes = $listaDeSituacoes;
        $this->listaDeParecerInscricao = $listaDeParecerInscricao;
        $this->periodoLetivo = $periodoLetivo;
        $this->siglaCurso = $siglaCurso;
        
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
        
        $classeCurso = Curso::obterCurso($this->siglaCurso);
        
        $txt="Resultado de Solicitações de Inscrição para o Período Letivo de ".$this->periodoLetivo;
        $txt.="\nCurso: ".$this->siglaCurso;
        $txt.=" - ".$classeCurso->getNomeCurso();
        
        $this->SetY(33);
        $this->MultiCell($tamHorizontalDoCabecalho,4.5,$txt, $this->debug,'L');

        $this->SetY(43);

        $espacamentoHorizontal = 5;       
    }

    function desenharResultado() {
        
        $this->SetY($this->GetY());
        //Desce um pouco o cursor

        $tamanGomoN1 = 20;
        $tamanGomoN3 = 30;
        $tamanGomoN2 = 50;
        
        $tamanGomoN4 = $this->larguraMaxima - ($tamanGomoN1 + $tamanGomoN3
                        + $tamanGomoN2 );

        $fonteMedia = 8;
        $espacamentoHorizontalMedio = 2.5;
        $fonteGrande = 9;
        $espacamentoHorizontalGrande = 4.5;

        $this->SetFont('Arial','B',$fonteMedia);

        $txt='MATRICULA';
        $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='NOME';
        $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='TURMA ';
        $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='RESULTADO e PARECER';
        $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');
        
        $this->SetFont('Arial','',$fonteMedia);

        $this->SetWidths(array($tamanGomoN1,$tamanGomoN2,$tamanGomoN3,$tamanGomoN4));//CADA VALOR DESTE ARRAY SERÁ A LARGURA DE CADA COLUNA

        srand(microtime()*1000000);
        
        for($row=0; $row<sizeof($this->listaDeDisciplinas); $row++) {

            $matricula = $this->listaDeMatriculaAluno[$row];
            $nome = $this->listaDeNomeAluno[$row];
            $grade = $this->listaDeGradeHorarios[$row];
            $disciplina = $this->listaDeDisciplinas[$row];
            $turno = $this->listaDeTurnos[$row];
            $resultado = $this->listaDeSituacoes[$row];
            $parecer = $this->listaDeParecerInscricao[$row];

            $this->Row(array("$matricula","$nome","$disciplina"." - "."$grade"." - "."$turno","$resultado"." - "."$parecer"));
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

 
if($_POST){
    $listaDeMatriculaAluno = $_POST['listaDeMatriculaAluno'];
    $listaDeNomeAluno = $_POST['listaDeNomeAluno'];
    $listaDeGradeHorarios = $_POST['listaDeGradeHorarios'];
    $listaDeDisciplinas=$_POST['listaDeDisciplinas'];
    $listaDeTurnos=$_POST['listaDeTurnos'];
    $listaDeSituacoes=$_POST['listaDeSituacoes'];
    $listaDeParecerInscricao= $_POST['listaDeParecerInscricao'];
    $periodoLetivo = $_POST['periodoLetivo'];
    $siglaCurso = $_POST['siglaCurso'];

    $pdf = new EmitirResultadoSolicitacaoInscricaoPDF($listaDeMatriculaAluno,
            $listaDeNomeAluno,$listaDeGradeHorarios,$listaDeDisciplinas,$listaDeTurnos, $listaDeSituacoes, $listaDeParecerInscricao,$periodoLetivo, $siglaCurso);

    $pdf->desenharResultado();
    $pdf->output();
    
} else {
    echo '<h4>USO INCORRETO</h4>';
}

?>
