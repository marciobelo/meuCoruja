<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/baseCoruja/classes/fpdf/fpdf.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/to/QuitacaoComponenteCurricularTO.php";

class HistoricoConcluintesPDF extends FPDF {

    private $matriculaAluno;
    private $nomeAluno;
    private $nomeMae;
    private $nomePai;
    private $rgNumero;
    private $dataNascimento;
    private $rgOrgaoEmissor;
    private $naturalidade;
    private $nacionalidade;
    private $siglaPeriodoLetivo;
    private $concursoClassificacao;
    private $estabCursoOrigem;
    private $estabCursoOrigemCidade;
    private $estabCursoOrigemUF;
    private $cursoOrigemAnoConclusao;
    private $dataMatricula;
    private $dataFim;
    private $nomeDisciplina;
    private $siglaDisciplina;
    private $cargaHoraria;
    private $creditos;
    private $situacaoInscricao;
    private $mediaFinal;
    private $exibeCR;

    public function setExibeCR( $exibeCR ) {
        $this->exibeCR = $exibeCR;
    }

    public function getDtini() {
        return $this->dtini;
    }

    public function setDtini($dtini) {
        $this->dtini = $dtini;
    }

    public function getDtfim() {
        return $this->dtfim;
    }

    public function setDtfim($dtfim) {
        $this->dtfim = $dtfim;
    }

    public function getChtda() {
        return $this->chtda;
    }

    public function setChtda($chtda) {
        $this->chtda = $chtda;
    }

    public function getChes() {
        return $this->ches;
    }

    public function setChes($ches) {
        $this->ches = $ches;
    }

    public function getChaec() {
        return $this->chaec;
    }

    public function setChaec($chaec) {
        $this->chaec = $chaec;
    }

    public function getTchc() {
        return $this->tchc;
    }

    public function setTchc($tchc) {
        $this->tchc = $tchc;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getDtdefesa() {
        return $this->dtdefesa;
    }

    public function setDtdefesa($dtdefesa) {
        $this->dtdefesa = $dtdefesa;
    }

    public function getNtcc() {
        return $this->ntcc;
    }

    public function setNtcc($ntcc) {
        $this->ntcc = $ntcc;
    }

    public function getEnade() {
        return $this->enade;
    }

    public function setEnade($enade) {
        $this->enade = $enade;
    }

    public function getDtcolacao() {
        return $this->dtcolacao;
    }

    public function setDtcolacao($dtcolacao) {
        $this->dtcolacao = $dtcolacao;
    }

    public function getDtemissao() {
        return $this->dtemissao;
    }

    public function setDtemissao($dtemissao) {
        $this->dtemissao = $dtemissao;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    private $larguraMaxima;
    private $debug = 0;

    public function getMatriculaAluno() {
        return $this->matriculaAluno;
    }

    public function setMatriculaAluno($matriculaAluno) {
        $this->matriculaAluno = $matriculaAluno;
    }

    public function setNomeAluno( $nome) {
        return $this->nomeAluno = $nome;
    }

    public function getNomeAluno() {
        return $this->nomeAluno;
    }

    public function getNomeMae()
    {
        return $this->nomeMae;
    }

    public function setNomeMae($nomeMae) {
        $this->nomeMae = $nomeMae;
    }

    public function getNomePai() {
        return $this->nomePai;
    }

    public function setNomePai($nomePai) {
        $this->nomePai = $nomePai;
    }

    public function getRgNumero() {
        return $this->rgNumero;
    }

    public function setRgNumero($rgNumero) {
        $this->rgNumero = $rgNumero;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function getRgOrgaoEmissor() {
        return $this->rgOrgaoEmissor;
    }

    public function setRgOrgaoEmissor($rgOrgaoEmissor) {
        $this->rgOrgaoEmissor = $rgOrgaoEmissor;
    }

    public function getNaturalidade() {
        return $this->naturalidade;
    }

    public function setNaturalidade($naturalidade) {
        $this->naturalidade = $naturalidade;
    }

    public function getNacionalidade() {
        return $this->nacionalidade;
    }

    public function setNacionalidade($nacionalidade) {
        $this->nacionalidade = $nacionalidade;
    }

    public function getSiglaPeriodoLetivo() {
        return $this->siglaPeriodoLetivo;
    }

    public function setSiglaPeriodoLetivo($siglaPeriodoLetivo) {
        $this->siglaPeriodoLetivo = $siglaPeriodoLetivo;
    }

    public function getconcursoClassificacao() {
        return $this->concursoClassificacao;
    }

    public function setConcursoClassificacao($concursoClassificacao) {
        $this->concursoClassificacao = $concursoClassificacao;
    }

    public function getEstabCursoOrigem() {
        return $this->estabCursoOrigem;
    }

    public function setEstabCursoOrigem($estabCursoOrigem) {
        $this->estabCursoOrigem = $estabCursoOrigem;
    }

    public function getEstabCursoOrigemCidade() {
        return $this->estabCursoOrigemCidade;
    }

    public function setEstabCursoOrigemCidade($estabCursoOrigemCidade) {
        $this->estabCursoOrigemCidade = $estabCursoOrigemCidade;
    }

    public function getCursoOrigemAnoConclusao() {
        return $this->cursoOrigemAnoConclusao;
    }

    public function setCursoOrigemAnoConclusao($cursoOrigemAnoConclusao) {
        $this->cursoOrigemAnoConclusao = $cursoOrigemAnoConclusao;
    }

    public function getDataMatricula() {
        return $this->dataMatricula;
    }

    public function setDataMatricula($dataMatricula) {
        $this->$dataMatricula = $dataMatricula;
    }

    public function getDataFim() {
        return $this->$dataFim;
    }

    public function setDataFim( $dataFim) {
        $this->$dataFim = $dataFim;
    }

    public function getNomeDisciplina() {
        return $this->nomeDisciplina;
    }

    public function setNomeDisciplina($nomeDisciplina) {
        $this->$nomeDisciplina = $nomeDisciplina;
    }

    public function getSiglaDisciplina() {
        return $this->siglaDisciplina;
    }

    public function setSiglaDisciplina($siglaDisciplina) {
        $this->$siglaDisciplina = $siglaDisciplina;
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->$cargaHoraria = $cargaHoraria;
    }

    public function getCreditos() {
        return $this->creditos;
    }

    public function setCreditos($creditos) {
        $this->$creditos = $creditos;
    }

    public function getSituacaoInscricao() {
        return $this->situacaoInscricao;
    }

    public function setSituacaoInscricao($situacaoInscricao) {
        $this->$situacaoInscricao = $situacaoInscricao;
    }

    public function getMediaFinal() {
        return $this->mediaFinal;
    }

    public function setMediaFinal($mediaFinal) {
        $this->$mediaFinal = $mediaFinal;
    }


    public function verificaArquivo($mat) {

       $filename=mat.".xml";
       if (file_exists($filename)) {

          return true;

       }else{

         return false;

       }

    }

    public function criarXML($dtini,$dtfim,$estabelecimentoVestibular, $chtda,$ches,$chaec,$tchc,
       $titulo,$dtdefesa,$ntcc,$enade,$dtcolacao,$dtExpedicaoDiploma,$dtemissao,$observacao){

        //versao do encoding xml
         $dom = new DOMDocument("1.0", "utf-8");
        //retirar os espacos em branco
        $dom->preserveWhiteSpace = false;

        //gerar o codigo
        $dom->formatOutput = true;

        //criando o nó principal (root)
        $root = $dom->createElement("dados");

        //nó filho (historico)
        $historico = $dom->createElement("historico");
        //setanto nomes e atributos dos elementos xml (nós)
        $dtini = $dom->createElement("dtini",$dtini);
        $dtfim = $dom->createElement("dtfim",$dtfim);
        $estabelecimentoVestibular = $dom->createElement("estabelecimentoVestibular",$estabelecimentoVestibular);
        $chtda = $dom->createElement("chtda",$chtda);
        $ches = $dom->createElement("ches",$ches);
        $chaec = $dom->createElement("chaec",$chaec);
        $tchc = $dom->createElement("tchc",$tchc);
        $titulo = $dom->createElement("titulo",  utf8_encode($titulo));
        $dtdefesa = $dom->createElement("dtdefesa",$dtdefesa);
        $ntcc = $dom->createElement("ntcc",$ntcc);
        $enade = $dom->createElement("enade", utf8_encode($enade));
        $dtcolacao = $dom->createElement("dtcolacao",$dtcolacao);
        $dtExpedicaoDiploma = $dom->createElement("dtExpedicaoDiploma",$dtExpedicaoDiploma);
        $dtemissao = $dom->createElement("dtemissao",$dtemissao);
        if($observacao==null){$observacao=" ";}
        $observacao = $dom->createElement("observacao",utf8_encode($observacao));

       //adiciona os nós (informacaoes do contato) em contato
        $historico->appendChild($dtini);
        $historico->appendChild($dtfim);
        $historico->appendChild($estabelecimentoVestibular);
        $historico->appendChild($chtda);
        $historico->appendChild($ches);
        $historico->appendChild($chaec);
        $historico->appendChild($tchc);
        $historico->appendChild($titulo);
        $historico->appendChild($dtdefesa);
        $historico->appendChild($ntcc);
        $historico->appendChild($enade);
        $historico->appendChild($dtcolacao);
        $historico->appendChild($dtExpedicaoDiploma);
        $historico->appendChild($dtemissao);
        $historico->appendChild($observacao);

        //adiciona o nó contato em (root) agenda
        $root->appendChild($historico);
        $dom->appendChild($root);

       // Para salvar o arquivo, descomente a linha
        $dom->save( Config::DIR_AREA_DADOS . "/" . $_REQUEST['mat'] . ".xml");
    }

    public function obterDadoAluno($matriculaAluno)
    {
        $con = BD::conectar();
        $result = mysql_query(sprintf("SELECT
     upper(Aluno.nomeMae) as nomeMae,
     upper(Aluno.nomePai) as nomePai,
     Aluno.rgNumero,
     upper(Pessoa.nome) as nome,
     Pessoa.dataNascimento,
     MatriculaAluno.matriculaAluno,
     Aluno.rgOrgaoEmissor,
     upper(Pessoa.naturalidade) as naturalidade,
     upper(Pessoa.nacionalidade) as nacionalidade,
     PeriodoLetivo.siglaPeriodoLetivo,
     MatriculaAluno.concursoClassificacao,
     Aluno.estabCursoOrigem,
     Aluno.estabCursoOrigemCidade,
     Aluno.estabCursoOrigemUF,
     Aluno.cursoOrigemAnoConclusao,
     MatriculaAluno.dataMatricula,
     PeriodoLetivo.dataFim
FROM
     Pessoa Pessoa INNER JOIN Aluno Aluno ON Pessoa.idPessoa = Aluno.idPessoa
     INNER JOIN MatriculaAluno MatriculaAluno ON Aluno.idPessoa = MatriculaAluno.idPessoa
     INNER JOIN PeriodoLetivo PeriodoLetivo ON MatriculaAluno.idPeriodoLetivo = PeriodoLetivo.idPeriodoLetivo
WHERE
     MatriculaAluno.matriculaAluno = '%s'", mysql_real_escape_string($matriculaAluno)), $con);

        $objetos = array();
        if (mysql_num_rows($result) > 0) {

            while ($rs = mysql_fetch_array($result)) {

                $HistoricoConcluintesPDF = new HistoricoConcluintesPDF();
                $HistoricoConcluintesPDF->setMatriculaAluno($rs['matriculaAluno']);
                $HistoricoConcluintesPDF->setNomeAluno($rs['nome']);
                $HistoricoConcluintesPDF->setNomeMae($rs['nomeMae']);
                $HistoricoConcluintesPDF->setNomePai($rs['nomePai']);
                $HistoricoConcluintesPDF->setRgNumero($rs['rgNumero']);
                $HistoricoConcluintesPDF->setDataNascimento($rs['dataNascimento']);
                $HistoricoConcluintesPDF->setRgOrgaoEmissor($rs['rgOrgaoEmissor']);
                $HistoricoConcluintesPDF->setNaturalidade($rs['naturalidade']);
                $HistoricoConcluintesPDF->setNacionalidade($rs['nacionalidade']);
                $HistoricoConcluintesPDF->setSiglaPeriodoLetivo($rs['siglaPeriodoLetivo']);
                $HistoricoConcluintesPDF->setConcursoClassificacao($rs['concursoClassificacao']);
                $HistoricoConcluintesPDF->setEstabCursoOrigem($rs['estabCursoOrigem']);
                $HistoricoConcluintesPDF->setEstabCursoOrigemCidade($rs['estabCursoOrigemCidade']);
                $HistoricoConcluintesPDF->setEstabCursoOrigemUF($rs['estabCursoOrigemUF']);
                $HistoricoConcluintesPDF->setCursoOrigemAnoConclusao($rs['cursoOrigemAnoConclusao']);
                $HistoricoConcluintesPDF->setDataMatricula($rs['dataMatricula']);
                $HistoricoConcluintesPDF->setDataFim($rs['dataFim']);

                array_push($objetos, $HistoricoConcluintesPDF);
            }
        }
        return $objetos;
    }

    /*
     * Gerando o cabeçalho
     * Logo do IST e Nome do Instituto (Primeiras 4 linhas) */

    public function gerarCabecalho() {

        $margemDoCabecalho = 32;
        $tamHorizontalDoCabecalho = 100;
        $tamFonteGrande = 12;
        $tamFonteMedia = 9;
        $tamFontePequena = 8;
        $this->lMargin = 100;
        $this->tMargin = 10;


        $this->setXY(9, 5);
        $this->Image('../../imagens/logorj.jpg', $tamHorizontalDoCabecalho, 10, 20, 25);
        $this->setXY(10, 34);
        $this->SetFont('Arial', 'B', $tamFonteMedia);
        $tamHorizontalDoCabecalho = 200;

        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_CURTO;        
        
        $this->MultiCell($tamHorizontalDoCabecalho, 4.0, $txt, $this->debug, 'C');

        $this->setXY(4, 52);
        $this->SetFont('Arial', '', $tamFonteMedia);
        $txt = "CURSO SUPERIOR DE TECNOLOGIA EM ANÁLISE DE SISTEMAS INFORMATIZADOS";
        $this->Cell($tamHorizontalDoCabecalho, $espacamentoHorizontal, $txt, $this->debug, 1, 'C');

        $this->setXY(5, 54);
        $this->SetFont('Arial', '', $tamFontePequena);
        $txt = 'DECRETO DE CRIAÇÃO NÚMERO 30.938 DE 18/03/2002 D.O.E.R.J. 19/03/2002
RECONHECIMENTO : PARECER C.E.E 066/2009 DE 09/06/2009 D.O.E.R.J. 14/07/2009
DELIBERAÇÃO CEE 361 DE 11/04/2017 D.O.E.R.J. 02/05/2017
DELIBERAÇÃO CEE 362 DE 11/04/2017 D.O.E.R.J. 02/05/2017';
        $this->MultiCell($tamHorizontalDoCabecalho, 4.0, $txt, $this->debug, 'C');

        //Texto: Histórico Escolar
        $this->SetFont('Arial', 'B', $tamFonteGrande);
        $txt = 'HISTÓRICO ESCOLAR';
        $this->setXY(10, $this->GetY() + 6.1);
        $this->Cell($this->larguraMaxima, $espacamentoHorizontal, $txt, $this->debug, 1, 'C');
    }

    public function obterDescricaoDoAluno($matriculaAluno) {

        $collection = $this->obterDadoAluno($matriculaAluno);

        $this->larguraMaxima = 190;
        $cumprimento3 = 45;
        $cumprimento2 = 45;
        $cumprimento1 = $this->larguraMaxima - ($cumprimento2 + $cumprimento3);

        $fontePequena = 6;
        $espacamentoHorizontalPequeno = 4;
        $fonteMedia = 9;
        $espacamentoHorizontalGrande = 4;

        //linha1 superior
        $this->setXY(9, $this->GetY() + 4.5);
        $this->SetFont('Arial', '', $fontePequena);
        $this->setX(9);
        $txt = 'NOME:';
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LTR', 0, 'L');

        $txt = 'MATRÍCULA:';
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LTR', 0, 'L');

        $txt = 'NASCIMENTO:';
        $this->Cell($cumprimento3, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LTR', 0, 'L');

        //parte inferior 1
        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->SetFont('Arial', 'B', $fonteMedia);
        $this->setX(9);
        $txt = $collection[0]->getNomeAluno();
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $txt = $collection[0]->getMatriculaAluno();
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $txt = Util::dataSQLParaBr( $collection[0]->getDataNascimento());
        $this->Cell($cumprimento3, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');


        //linha2 superior
        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->SetFont('Arial', '', $fontePequena);
        $this->setX(9);
        $txt = 'RG / ÓRGÃO EXPEDIDOR:';
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $txt = 'NATURALIDADE:';
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $txt = 'NACIONALIDADE';
        $this->Cell($cumprimento3, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        //parte inferior 2
        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->SetFont('Arial', 'B', $fonteMedia);
        $this->setX(9);
        $txt = $collection[0]->getRgNumero() . ' ' . $collection[0]->getRgOrgaoEmissor();
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $txt = $collection[0]->getNaturalidade();
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LB', 0, 'L');

        $txt = $collection[0]->getNacionalidade();
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        //linha3 superior
        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->SetFont('Arial', '', $fontePequena);
        $this->setX(9);
        $txt = 'MÃE:';
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $txt = 'PAI:';
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'L', 0, 'L');

        $txt = '';
        $this->Cell($cumprimento3, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'R', 0, 'L');

        //parte inferior 3
        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->SetFont('Arial', 'B', $fonteMedia);
        $this->setX(9);
        $txt = $collection[0]->getNomeMae();
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $txt = $collection[0]->getNomePai();
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LB', 0, 'L');

        $txt = '';
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'RB', 0, 'L');

        //linha4 superior
        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);

        $this->setX(9);
        $this->SetFont('Arial', 'B', $fontePequena+2);
        $txt = 'CONCURSO VESTIBULAR:';
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

        $txt = 'CURSO ENSINO MÉDIO:';
        $this->Cell($cumprimento3 + $cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->setX(9);
        $this->SetFont('Arial', 'B',  $fontePequena+2);
        $txt = '';
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $this->SetFont('Arial', 'B',  $fontePequena+2);
        $txt = 'ESTABELECIMENTO : ';
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->SetFont('Arial', 'B',  $fontePequena+2);
        $txt = 'ANO PERÍODO: ' . $collection[0]->getSiglaPeriodoLetivo();
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $this->SetFont('Arial', 'B',  $fontePequena );
        $txt = $collection[0]->getEstabCursoOrigem();
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->setX(9);
        $txt = 'ESTABELECIMENTO: ' . $_REQUEST['estabelecimentoVestibular'];
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $txt = 'LOCAL: ' . $collection[0]->getEstabCursoOrigemCidade() . ", " . $collection[0]->getEstabCursoOrigemUF();
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LR', 0, 'L');

        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->setX(9);
        $txt = 'CLASSIFICAÇÃO : ' . $collection[0]->getConcursoClassificacao();
        ;
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $txt = 'ANO DE CONCLUSÃO : ' . $collection[0]->getCursoOrigemAnoConclusao();
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        //linha5 superior
        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->SetFont('Arial', 'B', $fonteMedia);
        $this->setX(9);
        $txt = 'CURSO SUPERIOR DE TECNOLOGIA EM ANÁLISE DE SISTEMAS INFORMATIZADOS';
        $this->Cell($this->larguraMaxima, $espacamentoHorizontalPequeno + 1, $txt, ($this->debug) ? 1 : 'LR', 0, 'C');

        $this->setXY(9, $this->GetY() + $espacamentoHorizontalPequeno);
        $this->SetFont('Arial', 'B',  $fontePequena+2);
        $this->setX(9);

        $txt = 'INÍCIO : ' . $_REQUEST['dtini'];
        $this->Cell($cumprimento1, $espacamentoHorizontalPequeno + 1, $txt, ($this->debug) ? 1 : 'LB', 0, 'C');

        $txt = 'TÉRMINO : ' . $_REQUEST['dtfim'];
        $this->Cell($cumprimento2, $espacamentoHorizontalPequeno + 1, $txt, ($this->debug) ? 1 : 'LB', 0, 'C');

        $txt = '';
        $this->Cell($cumprimento3, $espacamentoHorizontalPequeno + 1, $txt, ($this->debug) ? 1 : 'RB', 0, 'C');
    }

    public function obterDadosGradeCurricular($matriculaAluno) {

        $MatriculaAluno = MatriculaAluno::obterMatriculaAluno($matriculaAluno);

        $cumprimento1 = 20;
        $cumprimento2 = 15;
        $cumprimento3 = 95;
        $cumprimento4 = 15;
        $cumprimento5 = 20;
        $cumprimento6 = 25;
        $cumprimento = $this->larguraMaxima - ($cumprimento2 + $cumprimento3);

        $fontePequena = 6;
        $fontePequena2 = 7;
        $espacamentoHorizontal = 5;
        $fonteMedia = 8.5;

        //retorna as disciplinas devidamente cursadas
        $colletion = $this->gradeCurricular($matriculaAluno);
        $total = $colletion[0]['totalPeriodo'];
        /* --------------------------------ordena o array ($colletion)------------------ */
        $colletion = $this->ordenarArray($colletion, $total); /* Array ordenado por período */
        //print_r($colletion);exit;
        //----------------GERA AS DICIPLINAS CURSADAS AGRUPADAS POR PERÍODO----------------------------//
        $i = $this->GetY() + $espacamentoHorizontal*2;
        foreach ($colletion as $objeto) {

            $this->setXY(9, $i);
            $this->SetFont('Arial', 'B', $fontePequena2);
            if ($objeto['periodoExtenso'] != '') {

                // imprime linha com a carga horária do período
                if(isset ($chPeriodo)) {
                    $this->SetFont('Arial', '', $fontePequena2);
                    $this->setXY(9, $i);
                    $this->Cell($cumprimento1+$cumprimento2+$cumprimento3, $espacamentoHorizontal, "CH do Período:",0, 0, 'R');
                    $this->Cell($cumprimento4, $espacamentoHorizontal, $chPeriodo, 1, 0, 'C');
                    $this->Cell($cumprimento5+$cumprimento6, $espacamentoHorizontal, "", 0, 'C');
                    $chPeriodo = 0;
                    $i += $espacamentoHorizontal*2;
                }

                if( !$this->temEspacoDisponivelPara($espacamentoHorizontal*9) ) {
                    $this->AddPage();
                    
                    // imprime linha com nome e matrícula do aluno
                    $i = $this->GetY();
                    $this->setXY(8, $i);
                    $this->SetFont('Arial', '', $fonteMedia);
                    $txt = 'Aluno: ' . $MatriculaAluno->getAluno()->getNome();
                    $this->Cell($cumprimento1, $espacamentoHorizontal, $txt, 0, 0, 'L');
                    $this->setXY(180, $i);
                    $this->SetFont('Arial', '', $fonteMedia);
                    $txt = 'Matrícula: ' . $MatriculaAluno->getMatriculaAluno();
                    $this->Cell($cumprimento1, $espacamentoHorizontal, $txt, 0, 0, 'R');
                    $i = $this->GetY() + 2;
                }

                $i += 3;
                $this->setXY(9, $i);
                $txt = $objeto['periodoExtenso'];
                $this->Cell($this->larguraMaxima, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRTB', 0, 'C');
                $i = $i + $espacamentoHorizontal+0.2;

                $this->setXY(9, $i);
                $this->SetFont('Arial', '', $fontePequena);
                $txt = 'ANO / SEM';
                $this->Cell($cumprimento1, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

                $txt = 'SIGLA';
                $this->Cell($cumprimento2, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

                $txt = 'DISCIPLINA';
                $this->Cell($cumprimento3, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

                $txt = 'C.H';
                $this->Cell($cumprimento4, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

                $txt = 'GRAU';
                $this->Cell($cumprimento5, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

                $txt = 'SITUAÇÃO FINAL';
                $this->Cell($cumprimento6, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');
                $i = $i + $espacamentoHorizontal;

            }//fim if

            $this->setXY(9, $i);
            $this->SetFont('Arial', '', $fonteMedia);
            $this->setX(9);
            $txt = $objeto['PeriodoCursado'];
            $this->Cell($cumprimento1, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $objeto['SiglaDisciplina'];
            $this->Cell($cumprimento2, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $objeto['NomeDisciplina'];
            $this->Cell($cumprimento3, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $objeto['CargaHoraria'];
            $chPeriodo += $objeto['CargaHoraria'];
            $this->Cell($cumprimento4, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $objeto['MediaFinal'];
            $this->Cell($cumprimento5, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $txt = $objeto['Situacao'];
            $this->Cell($cumprimento6, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

            $i += $espacamentoHorizontal;

        } //fim foreach

        // imprime linha com a carga horária do período
        if(isset ($chPeriodo)) {
            $this->SetFont('Arial', '', $fontePequena2);
            $this->setXY(9, $i);
            $this->Cell($cumprimento1+$cumprimento2+$cumprimento3, $espacamentoHorizontal, "CH do Período:",0, 0, 'R');
            $this->Cell($cumprimento4, $espacamentoHorizontal, $chPeriodo, 1, 0, 'C');
            $this->Cell($cumprimento5+$cumprimento6, $espacamentoHorizontal, "", 0, 'C');
            $chPeriodo = 0;
            $i += $espacamentoHorizontal;
        }

        $i += 1;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', $fonteMedia);
        $this->setX(9);
        
        if( $this->exibeCR )
        {
            $txt = 'CRA : ' . number_format($MatriculaAluno->calcularCR(), 1, ",", "");
            $this->Cell($cumprimento6, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : '', 0, 'C');
        }

        $i += 0.6;
        $this->setXY(9, $i);
        $this->SetFont('Arial', '', 6);
        $this->setX(9);
        $txt = 'Legendas : CH - Carga Horária, AP - Aprovado, ID - Isento de Disciplina';
        $tamanho = $cumprimento1 + $cumprimento2 + $cumprimento3 + $cumprimento4 + $cuprimento5 + $cumprimento6;
        $this->Cell($tamanho + 21, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : '', 0, 'R');


        $this->larguraMaxima = 190;
        $cumprimento3 = 30;
        $cumprimento2 = 160;

        $i += 9.5;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'CARGA HORÁRIA TOTAL DAS DISCIPLINAS EM AULAS';
        $this->Cell($cumprimento2, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'L');

        $this->SetFont('Arial', 'B', 8);
        $txt = $_REQUEST['chtda'];
        $this->Cell($cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'C');

        $i += 5.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'CARGA HORÁRIA DE ESTÁGIO SUPERVISIONADO';
        $this->Cell($cumprimento2, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $this->SetFont('Arial', 'B', 8);
        $txt = $_REQUEST['ches'];
        $this->Cell($cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

        $i += 5.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'CARGA HORÁRIA DE ATIVIDADES DE EXTENSÃO CULTURAL';
        $this->Cell($cumprimento2, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $this->SetFont('Arial', 'B',8);
        $txt = $_REQUEST['chaec'];
        $this->Cell($cumprimento3,$espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

        $i += 5.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'TOTAL DE C.H. DO CURSO';
        $this->Cell($cumprimento2, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $this->SetFont('Arial', 'B', 8);
        $txt = $_REQUEST['tchc'];
        $this->Cell($cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

        $i += 7.2;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'TRABALHO DE CONCLUSÃO DE CURSO';
        $this->Cell($cumprimento2, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'L');

        $this->SetFont('Arial', 'B', 8);
        $txt = 'DATA DA DEFESA';
        $this->Cell($cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'C');

        $i += 5.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'TÍTULO : ' . $_REQUEST['titulo'];
        $this->Cell($cumprimento2, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $this->SetFont('Arial', 'B', 8);
        $txt = $_REQUEST['dtdefesa'];
        $this->Cell($cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'C');

        $i += 5.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'NOTA DO TCC : ' . $_REQUEST['ntcc'];
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LRB', 0, 'L');

        $i += 7.2;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'ENADE : ' . $_REQUEST['enade'];
        $this->MultiCell($cumprimento2 + $cumprimento3, $espacamentoHorizontal-2, $txt, 1, 'L');

        $i += 7.2;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'DATA DA COLAÇÃO DE GRAU : ' . $_REQUEST['dtcolacao'];
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'L');

        $i += 7.2;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'DATA DA EXPEDIÇÃO DO DIPLOMA : ' . $_REQUEST['dtExpedicaoDiploma'];
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'L');

        $i += 7.2;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'OBSERVAÇÕES ';
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontal+0.2, $txt, ($this->debug) ? 1 : 'LTR', 0, 'C');

        $i += 5.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 7);
        $txt = $_REQUEST['observacao'];
        $this->MultiCell($cumprimento2 + $cumprimento3, $espacamentoHorizontal-2, $txt, 1, 'C');

        $this->larguraMaxima = 190;
        $cumprimento3 = 95;
        $cumprimento2 = 95;

        $i = $this->GetY() + 1.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'RIO DE JANEIRO, ' . Util::gerarDataExtenso( Util::dataBrParaSQL($_REQUEST['dtemissao']));
        $this->Cell($cumprimento2 + $cumprimento3, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : '', 0, 'L');

        $i = $this->GetY() + 6.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 6);
        $txt = '___________________________________';
        $this->Cell($cumprimento2, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : '', 0, 'C');

        $this->SetFont('Arial', 'B', 6);
        $txt = '___________________________________';
        $this->Cell($cumprimento3, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : '', 0, 'C');

        $i = $this->GetY() + 3.0;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 8);
        $txt = 'SECRETARIA ACADÊMICA';
        $this->Cell($cumprimento2, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : '', 0, 'C');

        $this->SetFont('Arial', 'B', 8);
        $txt = 'DIREÇÃO GERAL';
        $this->Cell($cumprimento3, $espacamentoHorizontal, $txt, ($this->debug) ? 1 : '', 0, 'C');

        $i = $this->h - $this->bMargin - 4.5;
        $this->setXY(9, $i );
        $this->SetFont('Arial', 'B', 6);
        $txt = Config::INSTITUICAO_NOME_COMPLETO;
        $this->Cell(180, 2, $txt, ($this->debug) ? 1 : '', 0, 'C');

        $i += 2.5;
        $this->setXY(9, $i);
        $this->SetFont('Arial', 'B', 6);
        $txt = 'Rua Clarimundo de Melo, 847 - CEP 21311-281- Quintino (21) 2332-4048';
        $this->Cell(180, 2, $txt, ($this->debug) ? 1 : '', 0, 'C');
    }

    public function gradeCurricular($matriculaAlunoString) {

        $matricula = MatriculaAluno::obterMatriculaAluno($matriculaAlunoString);
        $matriz = $matricula->getMatrizCurricular();

        $acc = $matriz->obterComponentesCurriculares();
        // exit(print_r($acc));
        $_SESSION['arr'] = array();
        $colletion = array();
        $indice = 0;
        $ii = 0;
        foreach ($acc as $cc) {
            $quitacaoTO = $cc->obterQuitacao($matricula);
            if ($quitacaoTO == null) {
                unset($_SESSION['arr']);
                $_SESSION['arr'] = array();

                $_SESSION['arr'][$ii]['SiglaDisciplina'] = $cc->getSiglaDisciplina();
                $_SESSION['arr'][$ii]['NomeDisciplina'] = $cc->getNomeDisciplina();

                $ii++;

                header('Location: /coruja/interno/view/pendencia/disciplinasPendentes.php');

            } else {

                $colletion[$indice]['SiglaDisciplina'] = $cc->getSiglaDisciplina();
                $colletion[$indice]['NomeDisciplina'] = $cc->getNomeDisciplina();
                $colletion[$indice]['Creditos'] = $cc->getCreditos();
                $colletion[$indice]['CargaHoraria'] = $cc->getCargaHoraria();
                $colletion[$indice]['Periodo'] = $cc->getPeriodo();

                if( $quitacaoTO->isIsento() ) 
                {
                    $colletion[$indice]['MediaFinal'] = 'ID';
                    $colletion[$indice]['Situacao'] = 'ID';
                } 
                else 
                {
                    $colletion[$indice]['Situacao'] = 'AP';
                    $colletion[$indice]['MediaFinal'] = number_format($quitacaoTO->getMediaFinal(), 1, ",", "");
                }

                $pl = $quitacaoTO->getPeriodoLetivo(); //exit($pl);
                $colletion[$indice]['PeriodoCursado'] = $pl->getSiglaPeriodoLetivo();
                $colletion[$indice]['totalPeriodo'] = $matriz->obterQuantidadePeriodos();

                //exit($pl->getSiglaPeriodoLetivo());
            }
            $indice++;
            //array_push($colletion, $cc);
        }//fim foreach

        if ($var != 'vázio') {

            //$colletion=$this->ordenarColecao($total,$colletion);
            //print_r($colletion);
            return $colletion;
        }
    }

    /* ------------------------------Este método retorna o período corrente por extenso---------- */

    public function verificaPeriodo($x) {

        switch ($x) {
            Case '1':
                $periodoExtenso = 'PRIMEIRO PERÍODO';
                BREAK;

            Case '2':
                $periodoExtenso = 'SEGUNDO PERÍODO';
                BREAK;

            Case '3':
                $periodoExtenso = 'TERCEIRO PERÍODO';
                BREAK;

            Case '4':
                $periodoExtenso = 'QUARTO PERÍODO';
                BREAK;

            Case '5':
                $periodoExtenso = 'QUINTO PERÍODO';
                BREAK;

            Case '6':
                $periodoExtenso = 'SEXTO PERÍODO';
                BREAK;

            Case '7':
                $periodoExtenso = 'SÉTIMO PERÍODO';
                BREAK;
        }

        return $periodoExtenso;
    }

    /* Este método ordena o array e acrescenta a informação de quantidades de linhas por período
     * para o agrupamento do relatório */

    public function ordenarArray($colletion, $total) {
        for ($x = 1; $x < $total + 1; $x++) {
            $a = 0;
            foreach ($colletion as $key => $objeto) {
                if ($objeto['Periodo'] == $x) {
                    $obj[$key]['Periodo'] = $objeto['Periodo'];
                    $obj[$key]['PeriodoCursado'] = $objeto['PeriodoCursado'];
                    $obj[$key]['SiglaDisciplina'] = $objeto['SiglaDisciplina'];
                    $obj[$key]['NomeDisciplina'] = $objeto['NomeDisciplina'];
                    $obj[$key]['CargaHoraria'] = $objeto['CargaHoraria'];
                    $obj[$key]['MediaFinal'] = $objeto['MediaFinal'];
                    $obj[$key]['Situacao'] = $objeto['Situacao'];
                    $obj[$key]['totalPeriodo'] = $objeto['totalPeriodo'];
                    $a = $a + 1;
                }//fim if
            }//fim foreach
            $arr[$x]['periodoCorrente'] = $periodoCorrente;
            $arr[$x]['qtPeriodo'] = $a;
        }//fim for

        for ($x = 1; $x < $total + 1; $x++) {
            $a = 0;

            foreach ($colletion as $key => $objeto) {

                if ($objeto['Periodo'] == $x) {
                    $obj[$key]['Periodo'] = $objeto['Periodo'];
                    $obj[$key]['PeriodoCursado'] = $objeto['PeriodoCursado'];
                    $obj[$key]['SiglaDisciplina'] = $objeto['SiglaDisciplina'];
                    $obj[$key]['NomeDisciplina'] = $objeto['NomeDisciplina'];
                    $obj[$key]['CargaHoraria'] = $objeto['CargaHoraria'];
                    $obj[$key]['MediaFinal'] = $objeto['MediaFinal'];
                    $obj[$key]['Situacao'] = $objeto['Situacao'];
                    $obj[$key]['totalPeriodo'] = $objeto['totalPeriodo'];
                    if ($a == 0) {
                        $obj[$key]['periodoExtenso'] = $this->verificaPeriodo($x);
                    }
                    $a = $a + 1;


                    if ($a == $arr[$x]['qtPeriodo']) {

                        $obj[$key]['qtPeriodo'] = $arr[$x]['qtPeriodo'];
                    } else {

                        $obj[$key]['qtPeriodo'] = '';
                    }
                }//fim if
            }//fim foreach
        }//fim for

        return $obj;
    }

    function temEspacoDisponivelPara($tamanho) {
        $alturaPagina = $this->h;
        $alturaMargemInferior = $this->bMargin;
        $alturaMargemSuperior =  $this->tMargin;
        $linhaAtual = $this->GetY();
        if($this->GetY() >= 
                ($alturaPagina - $alturaMargemInferior - $alturaMargemSuperior - $tamanho - 10 )) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function setEstabCursoOrigemUF($uf) {
        $this->estabCursoOrigemUF = $uf;
    }
    
    public function getEstabCursoOrigemUF() 
    {
        return $this->estabCursoOrigemUF;
    }

}
