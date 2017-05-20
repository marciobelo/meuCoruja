<?php

/**
 * Gera um PDF contendo a Listagem de Alunos recebida por parametro
 *
 * A maior parte do documento é gerada utilizando Cells (Tabela), tratando uma linha
 * por vez
 *
 *
 * @author Marcelo Atie
 */

require_once("../../includes/comum.php");
require("$BASE_DIR/nort/classes/FpdfNort.php");


class RelatorioDeAlunosPorSituacaoPDF extends FpdfNort {

    //Dados
    private $listaDeMatriculaAluno;
    private $listaDenome;
    private $listaDeSituacaoMatricula;
    private $siglaCurso;
    private $listaDedescPeriodoLetivo;

    private $qntDePaginas;

    //variáveis do ambiente
    private $larguraMaxima;
    private $debug = 0;

    function RelatorioDeAlunosPorSituacaoPDF(
            $listaDeMatriculaAluno,
            $listaDenome,
            $listaDeSituacaoMatricula,
            $siglaCurso,
            $nomeCurso,
            $situacoesEscolhidas,
            $listaDedescPeriodoLetivo,
            $periodoInical,
            $periodoFinal,
            $turnos) {

        parent::FpdfNort('P'); //Ajusta página Vertical(Normal / Padrao)
        $this->AliasNbPages( '{total}' );
        $this->AddPage();

        $this->listaDeMatriculaAluno = $listaDeMatriculaAluno;
        $this->listaDenome = $listaDenome;
        $this->listaDeSituacaoMatricula = $listaDeSituacaoMatricula;
        $this->siglaCurso = $siglaCurso;
        $this->nomeCurso = $nomeCurso;
        $this->situacoesEscolhidas = $situacoesEscolhidas;
        $this->listaDedescPeriodoLetivo = $listaDedescPeriodoLetivo;
        $this->periodoInicial = $periodoInical;
        $this->periodoFinal = $periodoFinal;
        $this->turnos = $turnos;

        //Ajusta as variáveis de ambiente
        $this->qntDePaginas = 1;
        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);


    }

    function gerarCabecalho() {

        //Logo do IST e Nome do Instituto (Primeiras 4 linhas)

        $margemDoCabecalho = 34;
        $tamHorizontalDoCabecalho =  $this->larguraMaxima - 30;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;

        $this->Image("../../imagens/logorj.jpg", $this->lMargin, $this->tMargin-6 );

        $this->SetX($margemDoCabecalho);
        $this->SetFont('Arial','',$tamFonteMedia);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO;               
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        $espacamentoHorizontal = 5;

        $this->SetFont('Arial','',$tamFonteMedia);
        $txt="Curso: $this->siglaCurso - $this->nomeCurso";
        $txt.="\nSituações de Matricula: ";
        for($i=0; $i<sizeof($this->situacoesEscolhidas); $i++){
            if($i == 0){
                //primeira
                $txt.=$this->situacoesEscolhidas[$i];
            }else{
                //seguintes
                $txt.=', '.$this->situacoesEscolhidas[$i];
            }
        }
        if($this->periodoInicial != $this->periodoFinal)
            $txt.="\nIntervalo do Período de Matrículas: $this->periodoInicial até $this->periodoFinal";
        else
            $txt.="\nPeríodo de Matrículas: $this->periodoInicial";
        $txt.="\nTurnos: ";
        for($i=0; $i<sizeof($this->turnos); $i++){
            if($i == 0){
                //primeira
                $txt.=$this->turnos[$i];
            }else{
                //seguintes
                $txt.=', '.$this->turnos[$i];
            }
        }

        $this->SetY(33);
        $this->MultiCell($tamHorizontalDoCabecalho,4.5,$txt, $this->debug,'L');

        $this->SetY(48);

        $espacamentoHorizontal = 5;

    }

    function desenharListaAlunos() {
        $this->SetY($this->GetY()+7);
        //Dece um pouco o cursor

        $tamanGomoN1 = 30;
        $tamanGomoN3 = 25;
        $tamanGomoN4 = 0; //Desabilitado, era utilizado como coluna do curso
        $tamanGomoN5 = 20;
        $tamanGomoN2 = $this->larguraMaxima - ($tamanGomoN1 + $tamanGomoN3
                        + $tamanGomoN4 + $tamanGomoN5 );

        $fonteMedia = 9;
        $espacamentoHorizontalMedio = 2.5;
        $fonteGrande = 9;
        $espacamentoHorizontalGrande = 4;

        $this->SetFont('Arial','B',$fonteGrande);

        $txt='MATRÍCULA';
        $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='ALUNO';
        $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='SITUAÇÃO';
        $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        //$txt='CURSO';
        //$this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='PERÍODO';
        $this->Cell($tamanGomoN5, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');


        $this->SetFont('Arial','',$fonteMedia);

        for($row=0; $row<sizeof($this->listaDeMatriculaAluno); $row++) {
            $matricula = $this->listaDeMatriculaAluno[$row];
            $nome = $this->listaDenome[$row];
            $situacao = $this->listaDeSituacaoMatricula[$row];
            $curso = $this->siglaCurso;
            $periodo = $this->listaDedescPeriodoLetivo[$row];

            if(!parent::temEspacoDisponivelPara($espacamentoHorizontalGrande)){
                //Gatilho para não escrever em cima do rodapé
                $this->AddPage();
            }

            $txt=$matricula;
            $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt=$nome;
            $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'L');
            $txt=$situacao;
            $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            //$txt=$curso;
            //$this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt=$periodo;
            $this->Cell($tamanGomoN5, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');
        }
    }
    function  Footer() {
        parent::Footer();
        parent::rodapePadrao($this->PageNo(), "{total}");
    }
}

?>
