<?php

/**
 *
 * @author Marcelo Atie
 */

require("$BASE_DIR/nort/classes/FpdfNort.php");

class AlunosNaTurmaPDF extends FpdfNort {

    //Dados
    private $disciplina;
    private $professor;
    private $ano;
    private $semestre;
    private $dataDaImpressao;
    private $turnoDaTurma;
    private $periodo;
    private $aulasPrevistas;

    //vari�veis do ambiente
    private $larguraMaxima;
    private $totalDePaginasAtual = 0;
    private $totalDeAlunos = 0; 
    private $qntMaximaDeAlunosPorPagina = 40;
    private $debug = 0;
    // $debug � usada para mostrar ou ocultar as bordas das tabelas
    //para fins de desenvolvimento

    function AlunosNaTurmaPDF() {
    // exemplo AlunosNaTurmaPDF('Programa��o Orientada a Objetos 2','Marcio Belo', 2009, 1, 'AS431', 120) {
        parent::FpdfNort('P'); //Ajusta p�gina Vertical (Retrato)
        //$this->AddPage(); passou a ser chamada pelo m�todo gerarCabecalho()
        
        //Ajusta as vari�veis de ambiente
        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);
    }

    function redefinirDadosDoCabecalho($disciplina, $professor, $ano, $semestre, $turnoDaTurma, $gradeHorario, $periodo){
        //Ajusta os Dados
        $this->disciplina = $disciplina;
        $this->professor = $professor;
        $this->ano = $ano;
        $this->semestre = $semestre;
        $this->turnoDaTurma = $turnoDaTurma;
        $this->gradeHorario = $gradeHorario;
        $this->periodo = $periodo;
        $this->aulasPrevistas = $aulasPrevistas;
        $this->dataDaImpressao = Date('d/m/Y'); //Exemplo de retorno '22/07/2009'
    }

    function gerarCabecalho() {
        $this->AddPage();
        //Logo do IST e Nome do Instituto (Primeiras 4 linhas)

        $margemDoCabecalho = 32;
        $tamHorizontalDoCabecalho =  $this->larguraMaxima - 30;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;

        $this->Cell(1); //Escreve algo no documento para posicionar corretamente o cursor

        $this->Image("../../imagens/logorj.jpg",$this->lMargin,$this->tMargin,20);
        //$this->Write(1, $this->lMargin.' - '.$this->tMargin.' - P'.$this->PageNo());

        $this->SetX($margemDoCabecalho);
        $this->SetFont('Arial','',$tamFonteMedia);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO;               
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        $this->SetY(35);

        $espacamentoHorizontal = 5;

        $this->SetFont('Arial','B',12);
        $this->Cell(110,$espacamentoHorizontal, $this->disciplina,$this->debug,0,'C');
        $this->SetFont('Arial','',12);

        //Titulo (5� Linha)
        $this->SetFont('Arial','',13);
        $txt = $this->ano.'  -  '.$this->semestre.'� SEMESTRE  -  '.$this->turnoDaTurma;
        $this->Cell(70, $espacamentoHorizontal, $txt, $this->debug , 1, 'C');

        //Data (6� linha)
        

        //Professor, Disciplina, Turno (7� linha)

        $this->Cell(110,$espacamentoHorizontal, 'Professor(a): '.$this->professor,$this->debug);
        $this->Cell(45, $espacamentoHorizontal, 'Grade: '.$this->gradeHorario, $this->debug, 0, 'C');
        $this->Ln();
        
        //Se��o 2 do cabe�alho,a fonte diminui
        $this->SetFont('Arial','',10);


        /*
         * Parte de cima da tabela dos alunos
         *
         *
         * Os tamanhos daqui devem estar de acordo com os tamanhos utilizados
         * na funcao gerarLista
         */

        $espacamentoDaLista = 5;

        //CABE�ALHO DA LISTA DE NOMES DOS ALUNOS
        $this->Ln(5);
        //Numero
        $this->Cell(8,$espacamentoDaLista,'N�',1,0,'C');
        //Matricula
        $this->Cell(25,$espacamentoDaLista,'Matricula',1,0,'C');
        //Nome
        $this->Cell(102,$espacamentoDaLista,'Aluno',1,0,'C');
        //M�dia Final
        $this->Cell(16,$espacamentoDaLista,'M�dia',1,0,'C');
        //Total de Faltas
        $this->Cell(16,$espacamentoDaLista,'Faltas',1,0,'C');
        //Situa��o
        $this->Cell(16,$espacamentoDaLista,'Situa��o',1,0,'C');

        $this->Ln();

    }
    
    function gerarLista($listaDeAlunos) {
        $espacamento = 5;
        $paginaAtual = $this->PageNo();
        $qntAlunos = 0;
        $this->totalDeAlunos = count($listaDeAlunos);
        $this->SetFont('Arial','',9.5);
        $this->totalDePaginasAtual = 0;
        foreach($listaDeAlunos as $aluno) {
            //Adiciona outra p�gina e desenha outro cabe�alho caso necessrio
            if(($qntAlunos % $this->qntMaximaDeAlunosPorPagina == 0)AND($qntAlunos!=0)){
                //$this->AddPage(); chamado no gerar cabe�alho
                //$this->totalDePaginasAtual++; passou a incrementado ao se desenhar o rodap�
                $this->gerarCabecalho();
            }

            //Numero
            $this->Cell(8,$espacamento,++$qntAlunos.' ',1,0,'R');
            //Matricula
            $this->Cell(25,$espacamento,$aluno->getMatriculaAluno(),1,0,'C');
            //Nome
            $NomeFormatado = $aluno->getNomeAluno();
            $this->Cell(102,$espacamento,$NomeFormatado,1);
            //M�dia Final
            $this->Cell(16,$espacamento,$aluno->getMediaFinal(),1,0,'C');
            //Total de Faltas
            $this->Cell(16,$espacamento,$aluno->getTotalFaltas(),1,0,'C');
            //Situa��o
            $this->Cell(16,$espacamento,$aluno->getSituacaoInscricao(),1,0,'C');

            $this->Ln();
            
        }

    }
    
//    function /* OBSOLETO */ gerarFooter() {
//        //substituida pelo rodap� padrao
//        $tamanho = 10;
//
//        $this->SetY(-1 * ($this->bMargin + $tamanho)); //posiciona o cursor
//        $this->SetFont('Arial','',12);
//        $this->Cell($this->larguraMaxima/2, 10, 'Impresso em: '.$this->dataDaImpressao,$this->debug,0,'C');
//        //$this->Cell($this->larguraMaxima/2, 10, 'P�gina: '.$this->PageNo().'�', $this->debug,0,'C');
//        $this->Cell($this->larguraMaxima/2, 10, 'P�gina: '.$this->totalDePaginasAtual.'/'.(intval($this->totalDeAlunos/$this->qntMaximaDeAlunosPorPagina)+1), $this->debug,0,'C');
//        $this->SetFont('Arial','',10);
//
//        $tamanho = 0;
//        $this->SetY(-1 * ($this->bMargin + $tamanho));
//        $this->Ln(30);
//        //acima do margem de baixo do documento
//    }

    function  Footer() {
        //$this->gerarFooter(); substituida pelo rodap� padrao
        
        $qntAlunosSendoDesenhada = $this->totalDeAlunos;
        $alunosPorPagina = $this->qntMaximaDeAlunosPorPagina;

        $totalDePaginasDinamico = (int) ($qntAlunosSendoDesenhada / $alunosPorPagina);
        if (($qntAlunosSendoDesenhada % $alunosPorPagina) > 0){
            $totalDePaginasDinamico++;
        }
        if ($totalDePaginasDinamico == 0){
            $totalDePaginasDinamico = 1;
        }

        $this->totalDePaginasAtual++;
        parent::rodapePadrao($this->totalDePaginasAtual, $totalDePaginasDinamico);
    }
}
?>
