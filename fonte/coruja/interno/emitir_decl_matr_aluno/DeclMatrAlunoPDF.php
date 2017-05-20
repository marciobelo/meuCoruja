<?php

//require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/util/FpdfCoruja.php";
require_once "$BASE_DIR/classes/Util.php";

class DeclMatrAlunoPDF extends FpdfCoruja {

    //variáveis do ambiente
    private $larguraMaxima; /* ajustada no construtor */
    private $tamFontValores = 11;
    private $debug = 0;

    function DeclMatrAlunoPDF() {
        parent::FpdfCoruja('P');
        $this->AliasNbPages( '{total}' );
        $this->AddPage(); //Adiciona a primeira pagina
        $this->setAlturaDoRodape(1);
        $this->bMargin = $this->bMargin - 10;

        //Ajusta as variáveis de ambiente
        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);

        $this->SetFont('Arial', '', $this->tamFontValores);

        //Desenha as partes do formulário
        $this->gerarCabecalho();
        $this->moverCursorAbaixo(1);
        $this->gerarCorpo();
        $this->gerarRodapeDePagina();

    }

    function gerarCabecalho() {

        $posOriginal = $this->GetY();

        $margemDoCabecalho = 32;
        $tamHorizontalDoCabecalho =  $this->larguraMaxima;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;

        $this->Image("../../imagens/logorj.jpg",$this->larguraMaxima / 2 - 5,$this->tMargin,12);

        $this->SetX(60);
        $this->SetY(27);
        $this->SetFont('Arial','B',12);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO . "\n" .
            Config::INSTITUICAO_NOME_CURTO;               
        $this->MultiCell($this->larguraMaxima-10,4.5,$txt, $this->debug,'C');

        $this->SetY($posOriginal);       
    }

    private function gerarCorpo() {

        $espacoEntreOsCampos = 2;
        $espacoTurno = 50;
        $espacoCurso = $this->larguraMaxima - ($espacoEntreOsCampos + $espacoTurno);

        $this->moverCursorAbaixo(80);
        $this->SetFont("Arial", "B", 18);
        $this->Text(85,$this->GetY(),"DECLARAÇÃO",0,0,"C");

        $this->moverCursorAbaixo(1);

        global $aluno;
        global $numMatriculaAluno;
        global $periodoReferencia;
        global $matriculaAluno;
        global $curso;
        if($aluno->getSexo()=="M") $sufixo="o";
        else $sufixo="a";

        $texto = "Declaramos para os devidos fins que " . $aluno->getNome() .
                ", matrícula " . $numMatriculaAluno . ", encontra-se regularmente " .
                "matriculad" . $sufixo . " nesta Instituição, no " . $periodoReferencia . "º " .
                "período do Curso Superior de " . $curso->getNomeCurso() . ", " .
                "no turno da " . mb_strtolower($matriculaAluno->getTurnoIngresso()) . ".";

        $this->SetFont("Arial", "", 14);
        $this->moverCursorAbaixo(10);
        $this->MultiCell(180, 10, $texto);

    }

    private function gerarRodapeDePagina() {

        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        $posicaoXDaLinha = $xInicial;

        $txt = 'Rio de Janeiro, ' . Util::gerarDataExtenso( Util::obterDataAtual($con));
        $this->Text($posicaoXDaLinha, $yInicial+20, $txt);

        //Desenha os Campos
        $alturaDaLinha = $yInicial + 40;
        $tamanhoDaLarguraDaLinha = 80;

        //Secretária
        $this->Line($posicaoXDaLinha, $alturaDaLinha, $posicaoXDaLinha + $tamanhoDaLarguraDaLinha, $alturaDaLinha);
        $this->Text($posicaoXDaLinha + 14, $alturaDaLinha + 6, "Secretária Acadêmica");

        // Identificação da Instituição
        $texto = Config::INSTITUICAO_NOME_COMPLETO . "\n" .
        Config::INSTITUICAO_NOME_CURTO . "\n" .
        Config::INSTITUICAO_ENDERECO . "\n" .
        Config::INSTITUICAO_TELEFONE . "\n";
        $this->SetFont("Arial", "", "10");
        $this->SetX(0);
        $this->SetY($alturaDaLinha+74);

        $this->MultiCell($this->larguraMaxima, 4, $texto, 0, 'C');

    }

    private function moverCursorAbaixo($px) {
        $this->SetY($this->GetY() + $px);
    }

    public function Footer() {
        parent::rodapePadrao($this->PageNo(), '{total}');
    }

}
?>
