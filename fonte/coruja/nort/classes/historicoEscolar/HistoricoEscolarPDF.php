<?php

/**
 * Gera um PDF contendo o Historico Escolar
 *
 * A maior parte do documento é gerada utilizando Cells (Tabela), tratando uma linha
 * por vez
 */

require("$BASE_DIR/nort/classes/FpdfNort.php");
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/TipoCurso.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/SituacaoMatriculaHistorico.php";

class HistoricoEscolarPDF extends FpdfNort {

    private $numeroMatriculaAluno;
    private $matriculaAluno;

    private $nomeDoCurso;
    private $descricaoTipoCurso;

    private $nomeAluno;
    private $nomeDoPaiDoAluno;
    private $nomeDaMaeDoAluno;
    private $naturalidade;
    private $identidade;
    private $orgaoEmissor;
    private $nascimento;
    private $cpf;

    //variáveis do ambiente
    private $larguraMaxima;
    private $alturaDaQuebraDePagina = 260; //Quanto maior, mais abaixo ocorre a quebra
    private $debug = 0;
    // $debug é usada para mostrar ou ocultar as bordas das tabelas
    //para fins de desenvolvimento


    /*
     * recebe como parametro apenas o numero da matricula do aluno, e não um objeto
     *
     */
    function HistoricoEscolarPDF($numeroMatriculaAluno) {
        parent::FpdfNort('P'); //Ajusta página Vertical(Normal / Padrao)
        $this->AliasNbPages( '{total}' );
        $this->AddPage();

        $this->numeroMatriculaAluno = $numeroMatriculaAluno;
        $this->matriculaAluno = MatriculaAluno::obterMatriculaAluno( $this->numeroMatriculaAluno);
        $this->carregaNomeDoCurso();
        $this->carregarInformacaoSobreOAluno();

        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);
    }

    function gerarCabecalho() {

        $margemDoCabecalho = 32;
        $tamHorizontalDoCabecalho =  $this->larguraMaxima - 30;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;

        //Logo do estado do Rio de Janeiro
        $this->Image('../../imagens/logorj.jpg',$this->lMargin,$this->tMargin,20);

        $this->SetX($margemDoCabecalho);
        $this->SetFont('Arial','',$tamFontePequena);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3;
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        $espacamentoHorizontal = 5;

        $txt = Config::INSTITUICAO_NOME_COMPLETO;
        $this->SetY($this->GetY()+0.5);
        $this->SetX($margemDoCabecalho);
        $this->Cell($tamHorizontalDoCabecalho, $espacamentoHorizontal, $txt, $this->debug , 1, 'L');
        $this->SetFont('Arial','',$tamFontePequena);
        $this->SetY(30);

        $espacamentoHorizontal = 5;

        //Nome Do Curso
        $this->SetFont('Arial','',$tamFonteGrande);
        $txt = "Curso de " . $this->descricaoTipoCurso . " em " . $this->nomeDoCurso;
        $this->SetX($margemDoCabecalho);
        $this->Cell($tamHorizontalDoCabecalho, $espacamentoHorizontal, $txt, $this->debug , 1, 'L');

        //Texto abaixo do nome do curso
        $this->SetX($margemDoCabecalho);
        $this->SetFont('Arial','',$tamFontePequena);
        $txt = 'DECRETO DE CRIAÇÃO Nº30.938 DE 18/03/2002 D.O.E.R.J. 19/03/2002
RECONHECIMENTO: PARECER CEE Nº066/2009 DE 09/06/2009 D.O.E.R.J. 14/07/2009
PARECER CEE Nº3576 DE 18/04/2017 D.O.E.R.J. 26/04/2017
DELIBERAÇÃO CEE 362 DE 11/04/2017 D.O.E.R.J. 26/04/2017';
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        //Texto: Histórico Escolar
        $this->SetFont('Arial','B',$tamFonteGrande);
        $txt = 'HISTÓRICO ESCOLAR';
        $this->SetY(55);
        $this->Cell($this->larguraMaxima, $espacamentoHorizontal, $txt, $this->debug , 1, 'C');

    }

    function gerarDescricaoDoAluno() {

        $tamanhoTerceiroGomo = 45;
        $tamanhoSegundoGomo = 45;
        $tamanhoPrimeiroGomo = $this->larguraMaxima - ($tamanhoTerceiroGomo + $tamanhoSegundoGomo);

        $fontePequena = 6;
        $espacamentoHorizontalPequeno = 2.5;
        $fonteMedia = 9;
        $espacamentoHorizontalGrande = 4;

        //linha1

        //parte superior
        $this->SetFont('Arial','',$fontePequena);
        $txt='NOME:';
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='MATRÍCULA:';
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='NASCIMENTO:';
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 1, 'L');

        //parte inferior
        $this->SetFont('Arial','',$fonteMedia);
        $txt=$this->nomeAluno;
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt=$this->numeroMatriculaAluno;
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt = Util::dataSQLParaBr($this->nascimento);
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 1, 'L');

        //linha2

        //parte superior
        $this->SetFont('Arial','',$fontePequena);
        $txt='NOME DO PAI:';
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='NATURALIDADE:';
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='CPF:';
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 1, 'L');

        //parte inferior
        $this->SetFont('Arial','',$fonteMedia);
        $txt=$this->nomeDoPaiDoAluno;
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt=$this->naturalidade;
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt=$this->cpf;
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 1, 'L');

        //linha3

        //parte superior
        $this->SetFont('Arial','',$fontePequena);
        $txt='NOME DA MAE:';
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='IDENTIDADE / ORGÃO EXPEDITOR:';
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 0, 'L');
        $txt='';
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTR' , 1, 'L');

        //parte inferior
        $this->SetFont('Arial','',$fonteMedia);
        $txt=$this->nomeDaMaeDoAluno;
        $this->Cell($tamanhoPrimeiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt=$this->identidade.'   '.$this->orgaoEmissor;
        $this->Cell($tamanhoSegundoGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 0, 'L');
        $txt='';
        $this->Cell($tamanhoTerceiroGomo, $espacamentoHorizontalGrande, $txt, ($this->debug)?1:'LRB' , 1, 'L');
    }

    function gerarListaDisciplinasCusadas() {
        $this->SetY($this->GetY()+7);
        //Dece um pouco o cursor

        $tamanGomoN1 = 20;
        $tamanGomoN2 = 20;
        $tamanGomoN4 = 15;
        $tamanGomoN5 = 15;
        $tamanGomoN6 = 15;
        $tamanGomoN7 = 15;
        $tamanGomoN3 = $this->larguraMaxima - ($tamanGomoN1 + $tamanGomoN2 + $tamanGomoN4 + $tamanGomoN5
            + $tamanGomoN6 + $tamanGomoN7 );

        $fonteMedia = 9;
        $espacamentoHorizontalMedio = 2.5;
        $fonteGrande = 9;
        $espacamentoHorizontalGrande = 4;

        $this->SetFont('Arial','B',$fonteGrande);

        //Cabeçalho, Linha 1, Nome da tabela
        $txt='DISCIPLINAS CURSADAS';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoHorizontalGrande, $txt, 1, 1, 'C', 1);
        //Cabeçalho, Linha 2, Colunas
        $this->SetFillColor(255);
        $txt='Período';
        $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Turno/Grade';
        $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Disciplina';
        $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='CRED';
        $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='CH';
        $this->Cell($tamanGomoN5, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='MF';
        $this->Cell($tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='SF';
        $this->Cell($tamanGomoN7, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');


        $con = BD::conectar();
        $matricula=$this->numeroMatriculaAluno;
        
        // DISCIPLINAS CURSADAS
        $query=sprintf("select PL.`siglaPeriodoLetivo`, T.`turno`, "
            ."T.`gradeHorario`, CC.`siglaDisciplina`, "
            ."CC.`nomeDisciplina`, CC.`creditos`, "
            ."CC.`cargaHoraria`, I.`mediaFinal`, I.`situacaoInscricao` "
            ."from Inscricao I, Turma T, ComponenteCurricular CC, PeriodoLetivo PL "
            ."where I.`matriculaAluno` = '%s' "
            ."and I.`idTurma` = T.`idTurma` "
            ."and T.`siglaCurso` = CC.`siglaCurso` "
            ."and T.`idMatriz` = CC.`idMatriz` "
            ."and T.`siglaDisciplina` = CC.`siglaDisciplina` "
            ."and T.`idPeriodoLetivo` = PL.`idPeriodoLetivo` "
            ."and I.`situacaoInscricao` in ('AP','RF','RM','ID') " //ID -> Isento de Disciplina
            ."and T.`tipoSituacaoTurma` = 'FINALIZADA' " //ID -> Isento de Disciplina
            ."ORDER BY PL.`siglaPeriodoLetivo`, CC.`siglaDisciplina` ",
            mysql_real_escape_string($matricula));
        $result=mysql_query($query,$con);

        $this->SetFont('Arial','',$fonteMedia);
        while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
            $periodo = $row['siglaPeriodoLetivo'];
            $turno = $row['turno'];
            $gradeHorario = $row['gradeHorario'];
            $siglaDisciplina = $row['siglaDisciplina'];
            $nomeDisciplina = $row['nomeDisciplina'];
            $creditos = $row['creditos'];
            $cargaHoraria = $row['cargaHoraria'];
            $mediaFinal = number_format($row['mediaFinal'], 1, ",", "");
            $situacaoFinal = $row['situacaoInscricao'];

            $txt=$periodo;
            $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt="$turno / $gradeHorario";
            $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt=$siglaDisciplina.' - '.$nomeDisciplina;
            $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'L');
            $txt=$creditos;
            $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt=$cargaHoraria;
            $this->Cell($tamanGomoN5, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            if($situacaoFinal=='ID') {
                $txt="---";
            } else {
                $txt=$mediaFinal;
            }
            $this->Cell($tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
            $txt=$situacaoFinal;
            $this->Cell($tamanGomoN7, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');
			
            if ($this->GetY() > $this->alturaDaQuebraDePagina) {
                $this->addPage();
            }
        }

        
    }

    function gerarComponentesCurricularesPendentes() {
        //Obter todos os componentes curriculares que o aluno deve cumprir da matriz dele

        $matricula=$this->numeroMatriculaAluno;
        $con = BD::conectar();
        // OBTEM TODOS OS COMPONENTES CURRICULARES DA MATRIZ CURRICULAR DA MATRICULA DO ALUNO
        $query=sprintf(''
            . ' SELECT'
            . ' CC.`siglaCurso`, CC.`idMatriz`, CC.`siglaDisciplina`'
            . ' FROM'
            . ' `ComponenteCurricular` CC, `MatriculaAluno` MA'
            . ' WHERE'
            . ' CC.`siglaCurso` = MA.`siglaCurso`'
            . ' and CC.`idMatriz` = MA.`idMatriz`'
            . " and MA.`matriculaAluno` = '%s'"
            . ' ORDER BY CC.`periodo`, CC.`siglaDisciplina`',
            mysql_real_escape_string($matricula));
        
        $result=mysql_query($query,$con);

        $this->SetY($this->GetY()+7);
        //Desce um pouco o cursor

        $tamanGomoN1 = 20;
        $tamanGomoN3 = 15;
        $tamanGomoN4 = 15;
        $tamanGomoN5 = 15;
        $tamanGomoN6 = 15;
        $tamanGomoN2 = $this->larguraMaxima - ($tamanGomoN1 + $tamanGomoN3 + $tamanGomoN4
            + $tamanGomoN5 + $tamanGomoN6 );

        $fonteMedia = 9;
        $espacamentoHorizontalMedio = 2.5;
        $fonteGrande = 9;
        $espacamentoHorizontalGrande = 4;

        $this->SetFont('Arial','B',$fonteGrande);

        //Cabeçalho, Linha 1, Nome da tabela
        $txt='DISCIPLINAS PENDENTES';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoHorizontalGrande, $txt, 1, 1, 'C', 1);
        //Cabeçalho, Linha 2, Colunas
        $this->SetFillColor(255);
        $txt='Período';
        $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='Disciplina';
        $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='CRED';
        $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='CH';
        $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
        $txt='TIPO';
        $this->Cell($tamanGomoN5 + $tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');

        $this->SetFont('Arial','',$fonteMedia);
        while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
            $siglaCurso = $row['siglaCurso'];
            $idMatriz = $row['idMatriz'];
            $siglaDisciplina = $row['siglaDisciplina'];
            $componenteCur = ComponenteCurricular::obterComponenteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
            
            //Se não há quitação, entao este componente curricular esta na lista de Componentes Pendentes
            if($componenteCur->obterQuitacao($this->matriculaAluno) == null){
                //$periodoLetivo = Periodoletivo::obterPeriodoLetivo($componenteCur->getPeriodo()) ;
                $txt = $componenteCur->getPeriodo().'º';//$periodoLetivo->getSiglaPeriodoLetivo();
                $this->Cell($tamanGomoN1, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
                $txt=$componenteCur->getSiglaDisciplina().' - '.$componenteCur->getNomeDisciplina();
                $this->Cell($tamanGomoN2, $espacamentoHorizontalGrande, $txt, 1, 0, 'L');
                $txt=$componenteCur->getCreditos();
                $this->Cell($tamanGomoN3, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
                $txt=$componenteCur->getCargaHoraria();
                $this->Cell($tamanGomoN4, $espacamentoHorizontalGrande, $txt, 1, 0, 'C');
                $txt=$componenteCur->getTipoComponenteCurricular();
                $this->Cell($tamanGomoN5 + $tamanGomoN6, $espacamentoHorizontalGrande, $txt, 1, 1, 'C');
				
                if($this->GetY() > $this->alturaDaQuebraDePagina){
                    $this->addPage();
                }
            }
        }
    }
    
    function gerarHistoricoDeSituacaoDeMatricula() {

        $this->SetY($this->GetY() + 7);
        //Desce um pouco o cursor
        
        if ($this->GetY() > $this->alturaDaQuebraDePagina) {
            $this->addPage(); //Quebra página caso nescessário
        }

        $situacoes = SituacaoMatriculaHistorico::getAllByNumMatriculaAluno($this->numeroMatriculaAluno);

        $fonteMedia = 9;
        $fonteGrande = 9;
        
        $espacamentoVerticalGrande = 4;
        $tamanGomoN1 = 20; //Data
        $tamanGomoN2 = 20; //Situacao
        $tamanGomoN3 = $this->larguraMaxima - ($tamanGomoN1 + $tamanGomoN2); //Texto da Observação
        
        //FONTE
        $this->SetFont('Arial','B',$fonteGrande);
        //Cabeçalho, Linha 1, Nome da tabela
        $txt='HISTÓRICO DE SITUAÇÕES DE MATRÍCULA';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 1, 'C', 1);
        //Cabeçalho, Linha 2, Colunas
        $this->SetFillColor(255);
        if (!empty ($situacoes)){
            $txt='Data';
            $this->Cell($tamanGomoN1, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $txt='Situação';
            $this->Cell($tamanGomoN2, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $txt='Observação';
            $this->Cell($tamanGomoN3, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $this->Ln();
        }

        //FONTE
        $this->SetFont('Arial','',$fonteMedia);
        
        foreach ($situacoes as $sit) {

            $txt = Util::dataSQLParaBr($sit->getDataHistorico());
            $this->Cell($tamanGomoN1, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $txt = $sit->getSituacaoMatricula();
            $this->Cell($tamanGomoN2, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $txt = $sit->getTexto();
            $this->MultiCell($tamanGomoN3, $espacamentoVerticalGrande, $txt, 1, 'C');
            //this->Ln(); -> MultiCell já aplica um breakLine ao final da celula

            if ($this->GetY() > $this->alturaDaQuebraDePagina) {
                $this->addPage();
            }
        }
        
        if (empty ($situacoes)){
            $txt = 'Não existem dados no histórico desta matrícula';
            $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 0, 'L');
            $this->Ln();
        }
    }
    
    function gerarListaDeDocumentosPendentes() {
        
        $this->SetY($this->GetY() + 7);
        //Desce um pouco o cursor
        
        if ($this->GetY() > $this->alturaDaQuebraDePagina) {
            $this->addPage(); //Quebra página caso nescessário
        }

        $documentos = $this->matriculaAluno->obterTipoDocumentosNaoEntregues();
        
        $fonteMedia = 9;
        $fonteGrande = 9;
        
        $espacamentoVerticalGrande = 4;
        
        
        //FONTE
        $this->SetFont('Arial','B',$fonteGrande);
        //Cabeçalho, Linha 1, Nome da tabela
        $txt='DOCUMENTOS PENDENTES';
        $this->SetFillColor(210);
        $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 1, 'C', 1);
        //Cabeçalho, Linha 2, Colunas
        $this->SetFillColor(255);
        
        //FONTE
        $this->SetFont('Arial','',$fonteMedia);
        
        foreach ($documentos as $doc) {

            $txt=$doc->getDescricao();
            $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 0, 'C');
            $this->Ln();

            if ($this->GetY() > $this->alturaDaQuebraDePagina) {
                $this->addPage();
            }
        }
        
        if (empty ($documentos)){
            $txt = 'Todos os documentos necessários já foram entregues';
            $this->Cell($this->larguraMaxima, $espacamentoVerticalGrande, $txt, 1, 0, 'L');
            $this->Ln();
        }
    }

    function gerarCR() {
        $this->Ln(2);

        //legenda
        $this->SetFont('Arial','',7);
        $txt = 'CRED - Créditos; CH - Carga Horária; MF - Média Final; SF - Situação Final';
        $this->Cell(140,4 /*$espacamentoHorizontalGrande*/, $txt, $this->debug, 0, 'L');
        
        //CR
        $this->SetFont('Arial','B',10);
        $txt='CR: ';
        $this->Cell(20, 4 /*$espacamentoHorizontalGrande*/, $txt, 1, 0, 'C');
        $txt = number_format($this->matriculaAluno->calcularCR(), 1, ",", "");
        $this->Cell(20, 4 /*$espacamentoHorizontalGrande*/, $txt, 1, 1, 'C');
    }

    function  Footer() {
        //parent::Footer();
        //$this->gerarFooter();
        parent::rodapePadrao($this->PageNo(), '{total}');
    }

    function gerarFooter() {
        $altura = 5;
        $largura = $this->larguraMaxima/3;
        //$this->SetFont('Arial','',10);
        $this->SetY(-1 * ($this->bMargin + $altura)); //posiciona o cursor
        //acima do margem de baixo do documento
        //$this->SetFillColor(80, 80, 255);
        $this->Cell($largura, $altura, 'Coruja', $this->debug, 0, 'L');
        $this->Cell($largura, $altura, 'Página: '.$this->PageNo().' de {total}', $this->debug, 0, 'C');
        $data = date("d/m/Y");
        $this->Cell($largura, $altura, 'Emitido em '.$data, $this->debug, 0, 'R');

    }

    private function carregaNomeDoCurso() 
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno( $this->numeroMatriculaAluno );
        $curso = Curso::obterCurso( $matriculaAluno->getSiglaCurso() );
        $this->nomeDoCurso = $curso->getNomeCurso();
        $tipoCurso = $curso->getTipoCurso();
        $this->descricaoTipoCurso = $tipoCurso->getDescricao();
    }

    private function carregarInformacaoSobreOAluno() {
        $con = BD::conectar();
        $matricula=$this->numeroMatriculaAluno;
        $query=sprintf("select P.nome, A.`nomePai`, A.`nomeMae`, P.`naturalidade`, A.`rgNumero`, "
            ."A.`rgOrgaoEmissor`,P.`dataNascimento`, A.`cpf` "
            ."from Pessoa P, Aluno A, MatriculaAluno MA "
            ."where MA.`matriculaAluno` = '%s' "
            ."and MA.`idPessoa`= A.`idPessoa` "
            ."and P.`idPessoa` = A.`idPessoa`",
            mysql_real_escape_string($matricula));
        $result=mysql_query($query,$con);
        $nome=mysql_result($result,0,0);
        $nomePai=mysql_result($result,0,1);
        $nomeMae=mysql_result($result,0,2);
        $naturalidade=mysql_result($result,0,3);
        $rgNumero=mysql_result($result,0,4);
        $rgOrgaoEmissor=mysql_result($result,0,5);
        $dataNascimento=mysql_result($result,0,6);
        $cpf=mysql_result($result,0,7);

        $this->nomeAluno = $nome;
        $this->nomeDoPaiDoAluno = $nomePai;
        $this->nomeDaMaeDoAluno = $nomeMae;
        $this->naturalidade = $naturalidade;
        $this->identidade = $rgNumero;
        $this->orgaoEmissor = $rgOrgaoEmissor;
        $this->nascimento = $dataNascimento;
        $this->cpf = $cpf;

    }
}


?>
