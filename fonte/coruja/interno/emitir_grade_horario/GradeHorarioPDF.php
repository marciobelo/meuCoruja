<?php
require_once "$BASE_DIR/baseCoruja/classes/fpdf/fpdf.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Espaco.php";
require_once "$BASE_DIR/classes/Professor.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/interno/emitir_grade_horario/TempoGrade.php";

class GradeHorarioPDF extends FPDF {

    private $grade;
    private $curso;
    private $periodoLetivo;

    private $LARGURA_MAXIMA = 282;
    private $LARGURA1 = 12;
    private $LARGURA2 = 45;
    private $ALTURA1 = 9; // altura da linha na grade. Deve ser divisível por 3
    private $ALTURA_PEQ = 5;

    function __construct(Curso $curso, PeriodoLetivo $periodoLetivo, $grade) {
        parent::FPDF();
        $this->curso = $curso;
        $this->periodoLetivo = $periodoLetivo;
        $this->grade = $grade;
        $this->AliasNbPages( '{total}' );
        $this->gerarGrade();
    }
   
    private function gerarGrade() {
        
        // verifica se cabem 2 grades por pagina (se até 7 tempos por grade, cabe)
        $maximoTempos = $this->grade["MAIOR_TEMPO_ORDINAL"];
        if( $maximoTempos > 7 ) {
            $maxGradePorPagina = 1;
        } else {
            $maxGradePorPagina = 2;
        }
            
        $turnos = array( 0 => "MANHÃ", 1 => "TARDE", 2 => "NOITE" );
        $this->gerarCabecalho( );
        $contGrade = 0;
        for( $contTurno = 0; $contTurno < 3; $contTurno++ ) {
            
            $turno = $turnos[ $contTurno ];
            $gradeTurno = $this->grade[ $turno ];
            
            foreach ($gradeTurno as $periodo => $gradePeriodo) {
                
                foreach ($gradePeriodo as $gradeHorario => $gradeGradeHorario) {
                    
                    if( $contGrade == $maxGradePorPagina ) {
                        $contGrade = 0;
                        $this->gerarRodape();
                        $this->gerarCabecalho( );
                    }
                    
                    $this->gerarCabecalhoGrade( $turno, $periodo, $gradeHorario );
                    
                    // imprime a linha do tempo de aula
                    for( $tempo = 1; $tempo <= $maximoTempos; $tempo++) {

                        $this->gerarLinhaTempo( $tempo, $gradeGradeHorario );
                    }
                    $contGrade++;
                    $this->Ln( 3 );
                }  
            }
        }
        $this->gerarRodape();
    }
    
    private function gerarCabecalho() {
        
        $largura = $this->LARGURA_MAXIMA;      
        $this->AddPage('L', 'A4');

        $this->setX( 9 );
        $this->Image("../../imagens/logorj.jpg",$this->lMargin,$this->tMargin,20);

        $this->SetX(32);
        $this->SetFont('Arial','',9);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO;               
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, 0,'L');

        $this->setXY(32,$this->GetY()+4);
	$this->SetFont('Arial','B',12);
	$txt = 'Grade de horário do curso : ' . $this->curso->getSiglaCurso() . 
                ' (' . $this->curso->getNomeCurso() . ')';
        $this->Cell($largura, $this->ALTURA_PEQ, $txt, '' , 0, 'L');

        $this->setX(32);
        $txt='Período Letivo : ' . $this->periodoLetivo->getSiglaPeriodoLetivo();
	$this->Cell($largura - 22 , $this->ALTURA_PEQ, $txt, '' , 0, 'R');
        $this->Ln( $this->ALTURA_PEQ + 2);
    }    
    
    private function gerarCabecalhoGrade( $turno, $periodo, $gradeHorario ) {        
        $fonteMedia = 9;        
        
        // imprime a linha de titulo
        $this->setX( 9 );
        $this->SetFont('Arial', 'B', $fonteMedia);
        
        $txt = $turno . ' - ' . Util::converteNumeralParaOrdinal( $periodo ) . ' - ' . $gradeHorario;
        $this->Cell($this->LARGURA_MAXIMA, $this->ALTURA_PEQ, $txt, 'LTRB', 0, 'C');

        // imprime linha das colunas dos dias da semana
        $this->setXY(9, $this->GetY() + 5);
        $txt = 'Tempo';
        $this->Cell($this->LARGURA1, $this->ALTURA_PEQ, $txt, 'LTRB', 0, 'C');

        $this->Cell($this->LARGURA2, $this->ALTURA_PEQ, 'SEG', 'LTRB', 0, 'C');
        $this->Cell($this->LARGURA2, $this->ALTURA_PEQ, 'TER', 'LTRB', 0, 'C');
        $this->Cell($this->LARGURA2, $this->ALTURA_PEQ, 'QUA', 'LTRB', 0, 'C');
        $this->Cell($this->LARGURA2, $this->ALTURA_PEQ, 'QUI', 'LTRB', 0, 'C');
        $this->Cell($this->LARGURA2, $this->ALTURA_PEQ, 'SEX', 'LTRB', 0, 'C');
        $this->Cell($this->LARGURA2, $this->ALTURA_PEQ, 'SAB', 'LTRB', 0, 'C');
        $this->Ln();
    }

    private function gerarLinhaTempo( $tempo, $gradeGradeHorario ) {
               
        // configurações
        $fontePequena = 7;
        $altura = 9;       
        
        $this->setX( 9 );
        $this->SetFont('Arial', '', $fontePequena);
        $this->Cell($this->LARGURA1, $altura, $tempo . '°', 'LTRB', 0, 'C');

        $this->desenharCelula3Linhas( $this->obterTextoAlocacao( $gradeGradeHorario, "SEG", $tempo ) );
        $this->desenharCelula3Linhas( $this->obterTextoAlocacao( $gradeGradeHorario, "TER", $tempo ) );
        $this->desenharCelula3Linhas( $this->obterTextoAlocacao( $gradeGradeHorario, "QUA", $tempo ) );
        $this->desenharCelula3Linhas( $this->obterTextoAlocacao( $gradeGradeHorario, "QUI", $tempo ) );
        $this->desenharCelula3Linhas( $this->obterTextoAlocacao( $gradeGradeHorario, "SEX", $tempo ) );
        $this->desenharCelula3Linhas( $this->obterTextoAlocacao( $gradeGradeHorario, "SAB", $tempo ) );       
        $this->Ln( $altura );
    }

    private function obterTextoAlocacao($gradeGradeHorario, $diaSemana, $tempo) {
        
        $retorno = array();
        $retorno[0] = "";
        $retorno[1] = "";
        $retorno[2] = "";
        $retorno[3] = "";
        if( !isset($gradeGradeHorario[$diaSemana]) ||
            !isset($gradeGradeHorario[$diaSemana][$tempo]) ) {
            return $retorno;
        }
        $tempoGrade = $gradeGradeHorario[$diaSemana][$tempo];
        
        if( $tempoGrade == null ) return $retorno;
        
        $turma = $tempoGrade->getTurma();
        $espaco = $tempoGrade->getEspaco();
        
        $retorno[0] = $turma->getSiglaDisciplina();
        if( $turma != null ) {
            $professor = $turma->getProfessor();
            if( $professor != null ) {
                $nome = $professor->getNomeGuerra();
                if( $nome == null || trim( $nome ) === "" ) {
                    $nome = strtoupper( Util::abreviaOuTruncaNome( $professor->getNome(), 15) );
                }
                $retorno[1] = $nome;
            }
        }
        if( $espaco != null ) {
            $retorno[2] = $espaco->getNome();
        }
        
        $retorno[3] = substr( $tempoGrade->getHoraInicio(), 0, 5) . "~" . 
        substr( $tempoGrade->getHoraFim(), 0, 5);
        return $retorno;
    }
    
    public function gerarRodape() {
        
        $alturaTexto = 2;
        
        $this->SetY( $this->h - 23 );
        $this->setX( 9 );
        $this->SetFont('Arial', '', 9);
        $txt = 'Coruja';
        $this->Cell(20, $alturaTexto, $txt, '', 0, 'L');

        $txt = "Emitido em " . date('d/m/Y');
        $this->Cell( 243, $alturaTexto, $txt, '', 0, 'C');

        $txt = "Página " . $this->PageNo().' de {total}';
        $this->Cell(20 , $alturaTexto, $txt, '', 0, 'R');
    }

    public function desenharCelula3Linhas($txt) {
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();
        $this->Cell($this->LARGURA2, $this->ALTURA1 / 3, $txt[1], 'R', 0, 'R');
        $this->Ln();
        $this->SetX($xInicial);
        $yLinha2 = $this->GetY();
        $this->SetFont('Arial', 'B', 9);
        $this->Cell($this->LARGURA2, $this->ALTURA1 / 3, $txt[0], 'R', 0, 'L');
        $this->SetXY($xInicial, $yLinha2);
        $this->SetFont('Arial', '', 7);
        $this->Cell($this->LARGURA2, $this->ALTURA1 / 3, $txt[2], 'R', 0, 'R');
        $this->Ln();
        $this->SetX($xInicial);
        $this->Cell($this->LARGURA2, $this->ALTURA1 / 3, $txt[3], 'RB', 0, 'R');
        $this->SetY($yInicial);
        $this->SetX( $xInicial + $this->LARGURA2 );
    }
}
?>