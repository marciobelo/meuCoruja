<?php
require "$BASE_DIR/nort/classes/FpdfNort.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/DiaLetivoTurma.php";
require_once "$BASE_DIR/classes/Turma.php";

class DiarioDeClassePDF extends FpdfNort {

    const QTDE_COLUNAS_TEMPO = 58;
    
    //Dados
    private $disciplina;
    private $professor;
    private $ano;
    private $semestre;
    private $dataDaImpressao;
    private $turnoDaTurma;
    private $periodo;
    private $aulasPrevistas;

    //variáveis do ambiente
    private $larguraMaxima;
    private $debug = 0; // $debug é usada para mostrar ou ocultar as bordas das tabelas para fins de desenvolvimento

    private $totalDePaginasDinamico; // Essas 3 variáveis são utilizadas para
    private $paginaAtualDinamico;    //determinar o numero e o total de paginas
    private $qntPaginasCoberturaTempos; // paginas necessarias para cobrir todos os tempos de apontamento
    private $alunosPorPagina = 21;        //em relação a turma sendo exibida (1 relatório pode conter varias turmas)
    private $qntAlunosSendoDesenhada;
    private $diasLetivoTurmaPagina;
    private $diasLetivoTurma;

    function DiarioDeClassePDF() {
        
        parent::FpdfNort('L'); //Ajusta página Horizontal
        $this->bMargin = $this->bMargin - 12;
       
        //Ajusta as variáveis de ambiente
        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);
    }

    function redefinirDadosDoCabecalho($siglaDaDisciplina, $disciplina, $professor, $ano, $semestre, 
            $turnoDaTurma, $gradeHorario, $periodo, $aulasPrevistas, 
            array $diasLetivoTurma,
            array $diasLetivoTurmaPagina,
            $qntPaginasCoberturaTempos) {
        //Ajusta os Dados
        $this->siglaDaDisciplina = $siglaDaDisciplina;
        $this->disciplina = $disciplina;
        $this->professor = $professor;
        $this->ano = $ano;
        $this->semestre = $semestre;
        $this->turnoDaTurma = $turnoDaTurma;
        $this->gradeHorario = $gradeHorario;
        $this->periodo = $periodo;
        $this->aulasPrevistas = $aulasPrevistas;
        $this->dataDaImpressao = Date('d/m/Y'); //Exemplo de retorno '22/07/2009'
        $this->diasLetivoTurmaPagina = $diasLetivoTurmaPagina;
        $this->diasLetivoTurma = $diasLetivoTurma;
        $this->qntPaginasCoberturaTempos = $qntPaginasCoberturaTempos;
    }

    function gerarCabecalho($quadroTempos = true) {
        $this->AddPage();
        $this->Cell(2); //Escreve algo no documento para posicionar corretamente o cursor - se remover a ultima página nao tera logo

        //Logo do IST e Nome do Instituto (Primeiras 4 linhas)
        $this->Image('../../imagens/logorj.jpg',$this->lMargin,$this->tMargin,20);
        $this->SetX(32);
        $this->SetFont('Arial','',10);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO;               
        $this->MultiCell(210,5.5,$txt, $this->debug,'L');
        $this->SetY(35);

        $espacamentoHorizontal = 5;

        //Titulo (5ª Linha)
        $this->SetFont('Arial','',13);
        $txt = 'DIÁRIO DE CLASSE '.$this->ano . '  -  ' . $this->semestre . 'º SEMESTRE';
        $this->Cell($this->larguraMaxima, $espacamentoHorizontal, $txt, $this->debug , 1, 'C');

        //Data (6ª linha)
        $this->SetFont('Arial','',12);
        $this->Cell(50,$espacamentoHorizontal, '',$this->debug);
        $this->Ln();

        //Professor, Disciplina, Turno (7ª linha)

        $this->Cell(110,$espacamentoHorizontal, 'Professor(a): '.$this->professor,$this->debug);
        $this->Cell(25,$espacamentoHorizontal, 'Disciplina: ',$this->debug,0,'L');

        $this->SetFont('Arial','B',12);
        $this->Cell(98 + 35,$espacamentoHorizontal, $this->siglaDaDisciplina . ' - ' . $this->disciplina,$this->debug,1,'L');
        $this->SetFont('Arial','',12);

        //Seção 2 do cabeçalho,a fonte diminui
        $this->SetFont('Arial','',10);

        //Aulas Previstas, Aulas Dadas, Limite de Faltas, Periodo, Página (8ª linha)
        $this->Cell(40, $espacamentoHorizontal+1, 'Aulas Previstas: '.$this->aulasPrevistas, $this->debug, 0, 'L');
        $this->Cell(50, $espacamentoHorizontal+1, 'Aulas Dadas: _________', $this->debug, 0, 'L');
        $this->Cell(50, $espacamentoHorizontal+1, 'Limite de Faltas: __________', $this->debug, 0, 'L');
        $this->Cell(45, $espacamentoHorizontal+1, 'Período: '.$this->periodo.'º', $this->debug, 0, 'C');
        $this->Cell(45, $espacamentoHorizontal+1, 'Grade: '.$this->gradeHorario, $this->debug, 0, 'C');
        $this->Cell(35, $espacamentoHorizontal+1, 'Turno: '.$this->turnoDaTurma, $this->debug, 1, 'R');
        
        /* Parte de cima da tabela dos alunos
         * Os tamanhos daqui devem estar de acordo com os tamanhos utilizados
         * na funcao gerarLista
         */
        $espacamentoDaLista = 5;

        if($quadroTempos) {
            $this->Cell(8+ 22+ (70-20));
            $this->Cell(20,$espacamentoDaLista,'Mês',1,0,'C');
            $qtdeTempos = 0;
            $qtdeTemposMes = 0;
            $mesAtual = count($this->diasLetivoTurmaPagina) > 0 ? 
                    $this->diasLetivoTurmaPagina[0]->getData()->format("n") : "";
            foreach($this->diasLetivoTurmaPagina as $diaLetivoTurma) {
                if( $mesAtual != $diaLetivoTurma->getData()->format("n") ) {
                    $textoMes = $this->gerarTextoMes($mesAtual, $qtdeTemposMes);
                    $this->Cell(3 * $qtdeTemposMes, $espacamentoHorizontal, $textoMes ,1, 0, 'L');
                    $qtdeTemposMes = 0;
                    $mesAtual = $diaLetivoTurma->getData()->format("n");
                }
                $qtdeTemposMes += $diaLetivoTurma->getQtdeTempos();
                $qtdeTempos += $diaLetivoTurma->getQtdeTempos();
            }
            $textoMes = $this->gerarTextoMes($mesAtual, $qtdeTemposMes);
            if( $qtdeTempos > 0 ) {
                $this->Cell(3 * $qtdeTemposMes, $espacamentoHorizontal, $textoMes ,1, 0, 'L');
            }
            if( $qtdeTempos < DiarioDeClassePDF::QTDE_COLUNAS_TEMPO ) {
                $this->SetFillColor(230);
                $this->Cell(3 * (DiarioDeClassePDF::QTDE_COLUNAS_TEMPO - $qtdeTempos), $espacamentoHorizontal, '',1, 0, 'C', true);
                $this->SetFillColor(255);
            }        
            $this->Ln();

            //Numero
            $this->Cell(7,$espacamentoDaLista,'Nº',1,0,'C');
            //Matricula
            $this->Cell(25.5,$espacamentoDaLista,'Matricula',1,0,'C');
            //Nome
            $this->Cell(67.5-20,$espacamentoDaLista,'Aluno',1,0,'C');
            //Outros
            $this->Cell(20,$espacamentoDaLista,'Tempos',1,0,'C');

            $qtdeTempos = 0;
            foreach($this->diasLetivoTurmaPagina as $diaLetivoTurma) {
                $this->Cell(3 * $diaLetivoTurma->getQtdeTempos() , $espacamentoHorizontal, 
                        $diaLetivoTurma->getData()->format("d") ,1, 0, 'C');
                $qtdeTempos += $diaLetivoTurma->getQtdeTempos();
            }
            if( $qtdeTempos < DiarioDeClassePDF::QTDE_COLUNAS_TEMPO ) {
                $this->SetFillColor(230);
                $this->Cell(3 * (DiarioDeClassePDF::QTDE_COLUNAS_TEMPO - $qtdeTempos), $espacamentoHorizontal, '',1, 0, 'C', true);
                $this->SetFillColor(255);
            }
        } else {
            $this->Cell(100);
        }
        $this->Ln();
    }
    
    function gerarLista($matriculasAluno, Turma $turma, array $diasLetivoTurmaPagina) {
        $this->qntAlunosSendoDesenhada = count($matriculasAluno);
                
        $espacamento = 5;
        $paginaAtual = $this->PageNo();
        $qntAlunos = 0;
        $this->SetFont('Arial','',9.5);
        $this->SetFillColor(255);

        foreach($matriculasAluno as $matriculaAluno) {

            $inscricao = Inscricao::getInscricao($turma->getIdTurma(), $matriculaAluno->getNumMatriculaAluno() );
            
            //Adiciona outra página e desenha outro cabeçalho caso necessrio
            if( ($qntAlunos % $this->alunosPorPagina == 0) ){
                $this->gerarCabecalho();
            }

            //Numero
            $qntAlunos++;

            $this->Cell(7,$espacamento,$qntAlunos.' ',1,0,'R',1);
            //Matricula
            $this->SetFont('Arial' , '', 8);
            $this->Cell(25.5,$espacamento,$matriculaAluno->getNumMatriculaAluno(),1,0,'C',1);
            $this->SetFont('Arial' , '', 9.5);
            //Nome
            $NomeFormatado = Util::abreviaOuTruncaNome( $matriculaAluno->getAluno()->getNome() , 40 );
            if($inscricao->isReclamadoPeloProfessor()) {
                $this->SetTextColor(255,0,0);
                $this->SetFont('Arial','I',9.5);
                $this->Cell(67.5,$espacamento,"* " . $NomeFormatado,1,0,'L',1);
                $this->SetFont('Arial','',9.5);
                $this->SetTextColor(0,0,0);                
            } else {
                $this->Cell(67.5,$espacamento,$NomeFormatado,1,0,'L',1);
            }

            //Outros
            $qtdeTempos = 0;
            foreach($diasLetivoTurmaPagina as $diaLetivoTurma) {
                $strResumo = $inscricao->obterResumoApontamentoDiaLetivo($diaLetivoTurma);
                if( $strResumo == "" ) $strResumo = str_repeat("-", $diaLetivoTurma->getQtdeTempos());
                for($i=0 ; $i < strlen($strResumo); $i++) {
                    $qtdeTempos++;
                    $this->SetFont('Arial' , '', 8);
                    $this->Cell(3, $espacamento, $strResumo[$i], 1, 0, 'C', false);
                    $this->SetFont('Arial' , '', 9.5);
                }                    
            }
            while( $qtdeTempos < DiarioDeClassePDF::QTDE_COLUNAS_TEMPO) {
                $this->SetFillColor(230);
                $this->Cell(3, $espacamento, " ", 1, 0, '', true);
                $this->SetFillColor(255);
                $qtdeTempos++;
            }
            $this->Ln();
        }
    }

    function gerarFooter() {
        $tamanho = 5;
        $this->SetFont('Arial','',10);
        //O rodapé padrao também ocupa 5mm, entao subiremos o rodapé 7mm à mais que o $tamanho
        $this->SetY(-1 * ($this->bMargin + ($tamanho+10))); //posiciona o cursor (sobe ou dece as assinaturas)
        //acima do margem de baixo do documento
        $this->Cell(35, $tamanho, 'Data: ___/___/___', 0, 0, 'L');
        $this->Cell(15);
        $this->Cell(60, $tamanho, 'As. do Professor', 'T', 0, 'C');
        $this->Cell(20);
        $this->Cell(60, $tamanho, 'As. do Coordenador', 'T', 0, 'C');
        $this->Cell(20);
        $this->Cell(60, $tamanho, 'As. do Secretário Acadêmico', 'T', 0, 'C');
    }

    function Footer() {
        $this->paginaAtualDinamico++;
        $paginasListaAlunos = (int) ($this->qntAlunosSendoDesenhada / ($this->alunosPorPagina+1)) + 1;
        $paginasAvaliacao = $paginasListaAlunos;
        $paginasConteudo = (int) (count($this->diasLetivoTurma) / 16 + 1);
        $this->totalDePaginasDinamico = ($paginasListaAlunos * $this->qntPaginasCoberturaTempos)
                + $paginasAvaliacao + $paginasConteudo;
        $this->gerarFooter();
        $this->SetY(-1 * ($this->bMargin + (5.9))); //posiciona o cursor (sobe ou dece a linha)
        $this->Line($this->GetX(), $this->GetY(),$this->GetX() + $this->larguraMaxima , $this->GetY());
        parent::rodapePadrao($this->paginaAtualDinamico, $this->totalDePaginasDinamico);
    }
    
    function gerarTextoMes( $mesAtual, $qtdeTemposMes) {
        if( $qtdeTemposMes > 5 ) {
            return Util::obterMesPorExtenso( $mesAtual );
        } else {
            return Util::obterMesTextoCurto( $mesAtual );
        }
    }

    public function gerarFolhaNotas( Turma $turma ) {
        
        $itensCriterioAvaliacao = $turma->getCriterioAvaliacao()->getItensCriterioAvaliacao();
        $inscricoes = $turma->getInscricoesDePauta();

        $this->gerarCabecalho(false);

        $this->qntAlunosSendoDesenhada = count( $inscricoes );
        $espacamento = 5;
        $paginaAtual = $this->PageNo();
        $qntAlunos = 0;
        $this->SetFont('Arial','',9.5);
        
        $espacamentoHorizontal = 5;

        // Quadro de notas
        $this->Cell(7.5,$espacamentoHorizontal,'Nº',1,0,'C');
        $this->Cell(25,$espacamentoHorizontal,'Matricula',1,0,'C');
        $this->Cell(67.5,$espacamentoHorizontal,'Aluno',1,0,'C');
        $this->Cell(20,$espacamentoHorizontal,'T.F.',1);
        
        foreach($itensCriterioAvaliacao as $itemCriterioAvaliacao) {
            $this->Cell(20,$espacamentoHorizontal,$itemCriterioAvaliacao->getRotulo(),1);
        }
        $this->Ln();
        
        $espacamento = 5;
        $paginaAtual = $this->PageNo();
        $qntAlunos = 0;
        $this->SetFont('Arial','',9.5);

        foreach($inscricoes as $inscricao) {

            //Adiciona outra página e desenha outro cabeçalho caso necessrio
            if(($qntAlunos % $this->alunosPorPagina == 0) AND ($qntAlunos!=0) ){
                $this->gerarCabecalho(false);
                
                // Quadro de notas
                $this->Cell(7.5,$espacamentoHorizontal,'Nº',1,0,'C');
                $this->Cell(25,$espacamentoHorizontal,'Matricula',1,0,'C');
                $this->Cell(67.5,$espacamentoHorizontal,'Aluno',1,0,'C');
                $this->Cell(20,$espacamentoHorizontal,'T.F.',1);
                foreach($itensCriterioAvaliacao as $itemCriterioAvaliacao) {
                    $this->Cell(20,$espacamentoHorizontal,$itemCriterioAvaliacao->getRotulo(),1);
                }                
                $this->Ln();
                
            }
            //Numero
            $qntAlunos++;

            $this->Cell(7.5,$espacamento,$qntAlunos.' ',1,0,'R',1);
            //Matricula
            $this->Cell(25,$espacamento, $inscricao->getMatriculaAluno(),1,0,'C',1);
            //Nome
            $NomeFormatado = Util::abreviaOuTruncaNome( $inscricao->getNomeAluno() , 40 );
            if($inscricao->isReclamadoPeloProfessor()) {
                $this->SetTextColor(255,0,0);
                $this->SetFont('Arial','I',9.5);
                $this->Cell(67.5,$espacamento,"* " . $NomeFormatado,1,0,'L',1);
                $this->SetFont('Arial','',9.5);
                $this->SetTextColor(0,0,0);
            } else {
                $this->Cell(67.5,$espacamento,$NomeFormatado,1,0,'L',1);
            }
            
            $this->Cell(20,$espacamento, $inscricao->obterFaltasLancadas(),1,0,'',1); // T.F.
            
            $itensCriterioAvaliacaoInscricaoNota = $inscricao->obterItensCriterioAvaliacaoInscricaoNota();
            foreach($itensCriterioAvaliacaoInscricaoNota as $itemCriterioAvaliacaoInscricaoNota) {
                $itemCriterioAvaliacao = $itemCriterioAvaliacaoInscricaoNota->getItemCriterioAvaliacao(); 
                $this->Cell(20,$espacamento, $itemCriterioAvaliacao->exibir($inscricao) ,1,0,'',1);
            }
            $this->Ln();
        }
    }

    public function gerarFolhaConteudo($diasLetivoTurma) {
        
        $this->gerarCabecalho(false);

        $espacamento = 5;
        $paginaAtual = $this->PageNo();
        $qntAlunos = 0;
        $this->SetFont('Arial','',9.5);
        
        $espacamentoHorizontal = 5;

        $espacamento = 5;
        $paginaAtual = $this->PageNo();
        $qntAlunos = 0;
        $this->SetFont('Arial','',9.5);

        $this->Cell(8, $espacamento, "Dia", 1, 0, "C" );
        $this->Cell(8, $espacamento, "Mês", 1, 0, "C" );
        $this->Cell(120, $espacamento, "Conteúdo Ministrado", 1, 0, "C" );
        $pos2aColuna = $this->getX();
        $this->Cell(8, $espacamento, "Dia", 1, 0, "C" );
        $this->Cell(8, $espacamento, "Mês", 1, 0, "C" );
        $this->Cell(120, $espacamento, "Conteúdo Ministrado", 1, 0, "C" );
        $this->Ln();
        
        $posVerticalInicial = $this->GetY();
        $qtdeItens = 0;
        $col = 1;
        foreach($diasLetivoTurma as $diaLetivoTurma) {
            
            $xInicial = $this->GetX();
            $this->Cell(8, 15, $diaLetivoTurma->getData()->format("d"), 1, 0, "C" );
            $this->Cell(8, 15, $diaLetivoTurma->getData()->format("m"), 1, 0, "C" );
            $xMoldura = $this->GetX();
            $yMoldura = $this->GetY();
            $this->Cell(120, 15, "", 1);
            $this->SetXY($xMoldura, $yMoldura);
            $strTexto = $diaLetivoTurma->getConteudo() . "\n\n\n";
            $this->MultiCell(120, 5, $strTexto, 0, "C", 0, 3);
            $this->SetX( $xInicial );
            
            if( $col == 2 ) $this->SetX($pos2aColuna);
            $qtdeItens++;
            if( $qtdeItens >= 8 ) {
                if($col == 1) {
                    $this->SetY($posVerticalInicial);
                    $this->SetX($pos2aColuna);
                    $col = 2;
                } else {
                    $this->gerarCabecalho(false);
                    $this->Cell(8, $espacamento, "Dia", 1, 0, "C" );
                    $this->Cell(8, $espacamento, "Mês", 1, 0, "C" );
                    $this->Cell(120, $espacamento, "Conteúdo Ministrado", 1, 0, "C" );
                    $this->Cell(8, $espacamento, "Dia", 1, 0, "C" );
                    $this->Cell(8, $espacamento, "Mês", 1, 0, "C" );
                    $this->Cell(120, $espacamento, "Conteúdo Ministrado", 1, 0, "C" );
                    $this->Ln();
                    $col = 1;
                }
                $qtdeItens = 0;
            }
        }        
    }
    
    function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $maxline=0) {
        //Output text with automatic or explicit line breaks, maximum of $maxlines
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r", '', $txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $b=0;
        if($border)
        {
            if($border==1)
            {
                $border='LTRB';
                $b='LRT';
                $b2='LR';
            }
            else
            {
                $b2='';
                if(is_int(strpos($border, 'L')))
                    $b2.='L';
                if(is_int(strpos($border, 'R')))
                    $b2.='R';
                $b=is_int(strpos($border, 'T')) ? $b2.'T' : $b2;
            }
        }
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $ns=0;
        $nl=1;
        while($i<$nb)
        {
            //Get next character
            $c=$s[$i];
            if($c=="\n")
            {
                //Explicit line break
                if($this->ws>0)
                {
                    $this->ws=0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border and $nl==2)
                    $b=$b2;
                if ( $maxline  && $nl > $maxline ) 
                    return substr($s, $i);
                continue;
            }
            if($c==' ')
            {
                $sep=$i;
                $ls=$l;
                $ns++;
            }
            $l+=$cw[$c];
            if($l>$wmax)
            {
                //Automatic line break
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        $this->_out('0 Tw');
                    }
                    $this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
                }
                else
                {
                    if($align=='J')
                    {
                        $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        $this->_out(sprintf('%.3f Tw', $this->ws*$this->k));
                    }
                    $this->Cell($w, $h, substr($s, $j, $sep-$j), $b, 2, $align, $fill);
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border and $nl==2)
                    $b=$b2;
                if ( $maxline  && $nl > $maxline ) 
                    return substr($s, $i);
            }
            else
                $i++;
        }
        //Last chunk
        if($this->ws>0)
        {
            $this->ws=0;
            $this->_out('0 Tw');
        }
        if($border and is_int(strpos($border, 'B')))
            $b.='B';
        $this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
        $this->x=$this->lMargin;
        return '';
    }    
    
}
?>
