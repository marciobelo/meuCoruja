<?php
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/FormaIngresso.php";
require_once "$BASE_DIR/classes/FormaIngresso.php";
require_once "$BASE_DIR/classes/Util.php";
require("$BASE_DIR/nort/classes/FpdfNort.php");

class FichaDeMatriculaPDF extends FpdfNort {

    private $aluno;
    private $matricula;

    //variáveis do ambiente
    private $larguraMaxima; /* ajustada no construtor */
    private $tamFontDescricao = 7;
    private $tamFontValores = 11;
    private $alturaDosCampos = 6;
    private $debug = 0;

    // $debug é usada para mostrar ou ocultar as bordas das tabelas
    //para fins de desenvolvimento


    function FichaDeMatriculaPDF($matricula) {
        parent::FpdfNort('P'); //Ajusta página Vertical(Normal / Padrao)
        $this->AliasNbPages( '{total}' );
        $this->AddPage(); //Adiciona a primeira pagina

        $this->matricula = $matricula;
        //$this->aluno = $this->matricula->getAluno();
        $this->aluno = Aluno::getAlunoByNumMatricula($this->matricula->getMatriculaAluno());

        //Ajusta as variáveis de ambiente
        $this->larguraMaxima = $this->w - ($this->lMargin + $this->rMargin);

        $this->SetFont('Arial', '', $this->tamFontValores);

        //Desenha as partes do formulário
        $this->logo();
        $this->gerarCabecalho();
        $this->moverCursorAbaixo(1);

        $this->gerarCamposCursoETurno();
        $this->moverCursorAbaixo(1);

        $this->gerarBlocoInformacosBasicas();
        $this->moverCursorAbaixo(3);

        $this->gerarBlocoNascimento();
        $this->moverCursorAbaixo(3);

        $this->gerarBlocoEndereco();
        $this->moverCursorAbaixo(3);

        $this->gerarBlocoFiliacao();
        $this->moverCursorAbaixo(3);

        $this->gerarBlocoDocumentacao();
        $this->moverCursorAbaixo(3);

        $this->gerarBlocoDadosAcademicos();
        $this->moverCursorAbaixo(3);

        $this->gerarBlocoAssinaturas();
    }

    function logo() {

//Logo do IST e Nome do Instituto (Primeiras 4 linhas)

        $posOriginal = $this->GetY();

        $margemDoCabecalho = 32;
        $tamHorizontalDoCabecalho =  $this->larguraMaxima - 30;
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

        $this->SetY($posOriginal);
    }

    private function gerarCabecalho() {

        $con = BD::conectar();
        $query = sprintf("select `foto` "
                        . "from Login "
                        . "where `idPessoa` = %d ",
                        $this->aluno->getIdPessoa());
        $result = mysql_query($query, $con);
        while ($row = mysql_fetch_object($result)) {
            //header("Content-type:image/png");
            $foto = $row->foto;
        }

//$fiveMBs = 5 * 1024 * 1024;
//$fp = fopen("php://temp/maxmemory:$fiveMBs", 'r+');
//fputs($fp, $foto123);
// read what we have written
//rewind($fp);
//fclose($fp);
//echo stream_get_contents($fp);

        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        $this->SetFontSize(15);
        $this->Text($xInicial + 43, $yInicial + 30, "FICHA DE MATRÍCULA");
        $this->Text($xInicial + 20, $yInicial + 35, "DIVISÃO DE REGISTROS ESCOLARES - DRE");
        $this->SetFontSize(9);

        //Desenha Area da foto
        $this->Rect($this->larguraMaxima - 25, $yInicial, 30, 40);
        $this->Text($this->larguraMaxima - 18, $this->GetY() + 20, 'FOTO');

        $this->SetY($yInicial + 40);

//        if($foto) {
//            $this->ImageFromString($foto, $this->larguraMaxima - 25, $yInicial, 30, 40, "PNG");
//        }
    }

    private function gerarCamposCursoETurno() {

        $espacoEntreOsCampos = 2;
        $espacoTurno = 50;
        $espacoCurso = $this->larguraMaxima - ($espacoEntreOsCampos + $espacoTurno);

        $this->campoComDescricao('Curso', $espacoCurso, $this->matricula->getMatrizCurricular()->getCurso()->getNomeCurso());
        $this->moverCursorADireita($espacoEntreOsCampos);
        $this->campoComDescricao('Turno', 50, $this->matricula->getTurnoIngresso());
        $this->Ln($this->alturaDosCampos);
    }

    private function gerarBlocoInformacosBasicas() {
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        //Desenha o Bloco
        $tamanhoDoBloco = 35;
        $larguraDeCadaBloco = 61;
        $this->Rect($xInicial, $yInicial, $this->larguraMaxima, $tamanhoDoBloco);

        // Matricula e Data
        $this->moverCursorAbaixo(2);
        $this->moverCursorADireita(1);
        $this->campoComDescricao('Número da Matricula', 110, $this->matricula->getMatriculaAluno());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Data da Matricula', 75, Util::dataSQLParaBr($this->matricula->getDataMatricula()));
        $this->Ln($this->alturaDosCampos);

        //Nome do Aluno
        $this->moverCursorAbaixo(2);
        $this->moverCursorADireita(1);
        $this->campoComDescricao('Nome do Aluno ', $this->larguraMaxima - 3, Util::formataNome($this->aluno->getNome()));
        $this->Ln($this->alturaDosCampos);

        //Linha 1:
        //Sexo
        //Estado Civil
        //Tipo de Vaga


        $this->moverCursorAbaixo(2);
        $this->moverCursorADireita(1);

        //Sexo
        if ($this->aluno->getSexo() == 'M') {
            $descSex = 'Masculino';
        } else {
            $descSex = 'Feminino';
        }
        $this->campoComDescricao('Sexo', $larguraDeCadaBloco, $descSex);

        //Mover
        $this->moverCursorADireita(2);

        //Estado Civil
        $this->campoComDescricao('Estado Civil', $larguraDeCadaBloco, ucfirst(strtolower($this->aluno->getEstadoCivil())));

        //Mover
        $this->moverCursorADireita(2);

        //Tipo de Vagas
        $formaIngresso = FormaIngresso::getFormaIngressoById($this->matricula->getIdFormaIngresso());
        $this->campoComDescricao('Tipo de vaga', $larguraDeCadaBloco, $formaIngresso->getDescricao() );



        //Segunda Linha
        // - Cor / Raça
        // - Necessidades Educacionais Especiais
        // - Pontuação
        //NOVA LINHA
        $this->Cell(33);
        $this->Ln($this->alturaDosCampos + 1);
        $this->moverCursorAbaixo(2);

        //Mover
        $this->moverCursorADireita(1);

        //Cor / Raca
        $this->campoComDescricao('Cor / Raça', $larguraDeCadaBloco, ucfirst(strtolower($this->aluno->getCorRaca())));

        //Mover
        $this->moverCursorADireita(2);

        //Necessidades educacionais especiais
        if ($this->aluno->getDeficienciaVisual() == 'SIM' |
                $this->aluno->getDeficienciaMotora() == 'SIM' |
                $this->aluno->getDeficienciaAuditiva() == 'SIM' |
                $this->aluno->getDeficienciaMental() == 'SIM') {
            $deficiencia = true;
        } else {
            $deficiencia = false;
        }
        $this->campoComDescricao('Necessidades Educacionais Especiais', $larguraDeCadaBloco);
        $this->desenhaCampoMultiplo("Sim", $this->GetX() - 45, $this->GetY() + 1.2, $deficiencia);
        $this->desenhaCampoMultiplo("Não", $this->GetX() - 25, $this->GetY() + 1.2, !($deficiencia));

        //Mover
        $this->moverCursorADireita(2);

        //Concurso (Pontuação)
        $this->campoComDescricao('Pontuação no Concurso', $larguraDeCadaBloco, $this->matricula->getConcursoPontos());

        //Posiciona o cursor no fim do bloco
        $this->SetX($xInicial);
        $this->SetY($yInicial + $tamanhoDoBloco);
    }

    private function gerarBlocoNascimento() {
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        //Desenha o Bloco
        $tamanhoDoBloco = 11;
        $this->Rect($xInicial, $yInicial, $this->larguraMaxima, $tamanhoDoBloco);
        $this->desenharDescricao('Nascimento', $xInicial, $yInicial);

        //Desenha os Campos
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Data', 50, Util::dataSQLParaBr($this->aluno->getDataNascimento()));
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Cidade', 70, $this->aluno->getCertidaoNascimentoCidade());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('UF', 10, $this->aluno->getCertidaoNascimentoUF());
        $this->moverCursorADireita(2);

        $pais;
        switch (strtoupper($this->aluno->getNacionalidade())) {
            case "BRASILEIRA":
                $pais = "Brasil";
                break;
            default:
                $pais = ucfirst(strtolower(($this->aluno->getNacionalidade())));
        }
        $this->campoComDescricao('País', 50, $pais);

        //Posiciona o cursor na posicao final
        $this->SetX($xInicial);
        $this->SetY($yInicial + $tamanhoDoBloco);
    }

    private function gerarBlocoEndereco() {
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        //Desenha o Bloco
        $tamanhoDoBloco = 27;
        $this->Rect($xInicial, $yInicial, $this->larguraMaxima, $tamanhoDoBloco);
        $this->desenharDescricao('Endereço', $xInicial, $yInicial);

        //Desenha os Campos
        //Endereco e Numero
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Avenida / Estrada / Rua / Travessa, etc', 160, $this->aluno->getEnderecoLogradouro());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Número', 24, $this->aluno->getEnderecoNumero());

        //Complemento e Bairro
        $this->Ln($this->alturaDosCampos);
        $this->moverCursorAbaixo(2);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Complemento', ($this->larguraMaxima / 2) - 3, $this->aluno->getEnderecoComplemento());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Bairro', ($this->larguraMaxima / 2) - 3, $this->aluno->getEnderecoBairro());

        //Municipio, CEP, UF, Telefone
        $this->Ln($this->alturaDosCampos);
        $this->moverCursorAbaixo(2);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Municipio', 75, $this->aluno->getEnderecoMunicipio());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('CEP', 47, $this->aluno->getEnderecoCEP());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('UF', 12, $this->aluno->getEnderecoEstado());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Telefone', 46, $this->aluno->getTelefoneResidencial());


        //Posiciona o cursor na posicao final
        $this->SetX($xInicial);
        $this->SetY($yInicial + $tamanhoDoBloco);
    }

    private function gerarBlocoFiliacao() {
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        //Desenha o Bloco
        $tamanhoDoBloco = 27;
        $this->Rect($xInicial, $yInicial, $this->larguraMaxima, $tamanhoDoBloco);
        $this->desenharDescricao('Filiação', $xInicial, $yInicial);

        //Desenha os Campos
        //Pai
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Pai', 134, Util::formataNome($this->aluno->getNomePai()));
        $this->moverCursorADireita(2);
        $this->campoComDescricao('RG Pai', 50, $this->aluno->getRgPai());

        //Mãe
        $this->Ln($this->alturaDosCampos);
        $this->moverCursorAbaixo(2);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Mãe', 134, Util::formataNome($this->aluno->getNomeMae()));
        $this->moverCursorADireita(2);
        $this->campoComDescricao('RG Mãe', 50, $this->aluno->getRgMae());

        //Responsável
        $this->Ln($this->alturaDosCampos);
        $this->moverCursorAbaixo(2);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Responsável', 134, Util::formataNome($this->aluno->getResponsavelLegal()));
        $this->moverCursorADireita(2);
        $this->campoComDescricao('RG Responsável', 50, $this->aluno->getRgResponsavel());

        //Posiciona o cursor na posicao final
        $this->SetX($xInicial);
        $this->SetY($yInicial + $tamanhoDoBloco);
    }

    private function gerarBlocoDocumentacao() {
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        //Desenha o Bloco
        $tamanhoDoBloco = 61;
        $this->Rect($xInicial, $yInicial, $this->larguraMaxima, $tamanhoDoBloco);
        $this->desenharDescricao('Documentação', $xInicial, $yInicial);

        //Desenha os subblocos e seus respectivos Campos
        $tamanhoDoSubbloco = $this->larguraMaxima / 3 - 3;

        //CPF
        $xSubbloco = $xInicial + 2;
        $ySubbloco = $yInicial + 3;
        $this->Rect($xSubbloco, $ySubbloco, $tamanhoDoSubbloco, $this->alturaDosCampos + 5);
        $this->desenharDescricao('CPF', $xSubbloco, $ySubbloco);

        //Campos
        $this->SetY($ySubbloco);
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita(4);
        $this->campoComDescricao('Número', $tamanhoDoSubbloco - 4, $this->aluno->getCpf());

        //Subbloco da Identidade
        $xSubbloco = $xInicial + 64.5;
        $ySubbloco = $yInicial + 3;
        $this->Rect($xSubbloco, $ySubbloco, $tamanhoDoSubbloco, $this->alturaDosCampos * 3 + 9);
        $this->desenharDescricao('Identidade', $xSubbloco, $ySubbloco);

        //Campos da Identidade
        $this->SetY($ySubbloco);
        $this->SetX($xSubbloco);
        $distanciaDaMargem = $xSubbloco - $this->lMargin;
        $this->moverCursorAbaixo(3);
        $this->Cell($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('RG', $tamanhoDoSubbloco - 4, $this->aluno->getRgNumero());
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->Cell($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Data', $tamanhoDoSubbloco - 4, Util::dataSQLParaBr($this->aluno->getRgDataEmissao()));
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->Cell($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Orgão Expeditor  -  UF', $tamanhoDoSubbloco - 4, $this->aluno->getRgOrgaoEmissor());


        //Subbloco Alistamento Militar
        $xSubbloco = $xInicial + 64.5;
        $ySubbloco = $yInicial + 32;
        $this->Rect($xSubbloco, $ySubbloco, $tamanhoDoSubbloco, $this->alturaDosCampos * 3 + 9);
        $this->desenharDescricao('Alistamento Militar', $xSubbloco, $ySubbloco);

        //Campos do Alistamento Militar
        $this->SetY($ySubbloco);
        $this->SetX($xSubbloco);
        $distanciaDaMargem = $xSubbloco - $this->lMargin;
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Número', $tamanhoDoSubbloco - 18, $this->aluno->getCertificadoAlistamentoMilitarNumero());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Série', 12, $this->aluno->getCertificadoAlistamentoMilitarSerie());
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Data', $tamanhoDoSubbloco - 4, Util::dataSQLParaBr($this->aluno->getCertificadoAlistamentoMilitarData()));
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('RM', $tamanhoDoSubbloco / 2 - 3, $this->aluno->getCertificadoAlistamentoMilitarRM());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('CSM', $tamanhoDoSubbloco / 2 - 3, $this->aluno->getCertificadoAlistamentoMilitarCSM());

        //Subbloco Certificado de Reservista
        $xSubbloco = $xInicial + 127.5;
        $ySubbloco = $yInicial + 32;
        $this->Rect($xSubbloco, $ySubbloco, $tamanhoDoSubbloco, $this->alturaDosCampos * 3 + 9);
        $this->desenharDescricao('Certificado de Reservista', $xSubbloco, $ySubbloco);

        //Campos do Certificado de Reservista
        $this->SetY($ySubbloco);
        $this->SetX($xSubbloco);
        $distanciaDaMargem = $xSubbloco - $this->lMargin;
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Número', $tamanhoDoSubbloco - 18, $this->aluno->getCertificadoReservistaNumero());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Série', 12, $this->aluno->getCertificadoReservistaSerie());
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Data', $tamanhoDoSubbloco - 18, Util::dataSQLParaBr($this->aluno->getCertificadoReservistaData()));
        $this->moverCursorADireita(2);
        $this->campoComDescricao('CAT', 12, $this->aluno->getCertificadoReservistaCAT());
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('RM', $tamanhoDoSubbloco / 2 - 3, $this->aluno->getCertificadoReservistaRM());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('CSM', $tamanhoDoSubbloco / 2 - 3, $this->aluno->getCertificadoReservistaCSM());

        //Subbloco Titulo de Eleitor
        $xSubbloco = $xInicial + 127.5;
        $ySubbloco = $yInicial + 3;
        $this->Rect($xSubbloco, $ySubbloco, $tamanhoDoSubbloco, $this->alturaDosCampos * 3 + 9);
        $this->desenharDescricao('Título de Eleitor', $xSubbloco, $ySubbloco);

        //Campos do Titulo de Eleitor
        $this->SetY($ySubbloco);
        $this->SetX($xSubbloco);
        $distanciaDaMargem = $xSubbloco - $this->lMargin;
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Número', $tamanhoDoSubbloco - 4, $this->aluno->getTituloEleitorNumero());
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Data', $tamanhoDoSubbloco - 4, Util::dataSQLParaBr($this->aluno->getTituloEleitorData()));
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Zona', $tamanhoDoSubbloco / 2 - 3, $this->aluno->getTituloEleitorZona());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Seção', $tamanhoDoSubbloco / 2 - 3, $this->aluno->getTituloEleitorSecao());

        //Subbloco Certidao de Nascimento
        $xSubbloco = $xInicial + 2;
        $ySubbloco = $yInicial + 20;
        $this->Rect($xSubbloco, $ySubbloco, $tamanhoDoSubbloco, $this->alturaDosCampos * 4 + 11);
        $this->desenharDescricao('Certidao de Nascimento', $xSubbloco, $ySubbloco);

        //Campos do Certidao de Nascimento
        $this->SetY($ySubbloco);
        $this->SetX($xSubbloco);
        $distanciaDaMargem = $xSubbloco - $this->lMargin;
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Número', $tamanhoDoSubbloco - 4, $this->aluno->getCertidaoNascimentoNumero());
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Livro', $tamanhoDoSubbloco / 2 - 3, $this->aluno->getCertidaoNascimentoLivro());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Página', $tamanhoDoSubbloco / 2 - 3, $this->aluno->getcertidaoNascimentoFolha());
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Cidade', $tamanhoDoSubbloco - 4, $this->aluno->getcertidaoNascimentoCidade());
        $this->moverCursorAbaixo($this->alturaDosCampos + 2);
        $this->moverCursorADireita($distanciaDaMargem);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Sub-Distrito', $tamanhoDoSubbloco - 16, $this->aluno->getCertidaoNascimentoSubdistrito());
        $this->moverCursorADireita(2);
        $this->campoComDescricao('UF', 10, $this->aluno->getCertidaoNascimentoUF());


        //Posiciona o cursor na posicao final
        $this->SetX($xInicial);
        $this->SetY($yInicial + $tamanhoDoBloco);
    }

    private function gerarBlocoDadosAcademicos() {
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        //Desenha o Bloco
        $tamanhoDoBloco = 20;
        $this->Rect($xInicial, $yInicial, $this->larguraMaxima, $tamanhoDoBloco);
        $this->desenharDescricao('Dados Acadêmicos', $xInicial, $yInicial);

        //Desenha os Campos
        //Estabelecimento de Origem
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Estabelecimento de Origem', $this->larguraMaxima - 4, $this->aluno->getEstabCursoOrigem());

        //Origem do Aluno
        $this->Ln($this->alturaDosCampos);
        $this->moverCursorAbaixo(3);
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Origem do Aluno', 60, $this->matricula->getDescFormaIngresso());
        //$this->desenhaCampoMultiplo("Concurso", $xInicial + 10, $alturaDasOpcoes, true);
        //$this->desenhaCampoMultiplo("Transferido", $xInicial + 35, $alturaDasOpcoes, true);

        //Modalidade do Curso de Origem
        $this->moverCursorADireita(2);
        $this->campoComDescricao('Modalidade do Curso de Origem', ($this->larguraMaxima - 6) - 60);
        $alturaDasOpcoes = $yInicial + 13.5;
        $this->desenhaCampoMultiplo("Ensino Médio", $xInicial + 70, $alturaDasOpcoes, $this->aluno->getModalidadeCursoOrigem() == "ENSINO MÉDIO");
        $this->desenhaCampoMultiplo("Supletivo", $xInicial + 100, $alturaDasOpcoes, $this->aluno->getModalidadeCursoOrigem() == "SUPLETIVO");
        $this->desenhaCampoMultiplo("Graduação", $xInicial + 130, $alturaDasOpcoes, $this->aluno->getModalidadeCursoOrigem() == "GRADUAÇÃO");
        $this->desenhaCampoMultiplo("Outros", $xInicial + 160, $alturaDasOpcoes, $this->aluno->getModalidadeCursoOrigem() == "OUTROS");


        //Posiciona o cursor na posicao final
        $this->SetX($xInicial);
        $this->SetY($yInicial + $tamanhoDoBloco);
    }

    private function gerarBlocoAssinaturas() {
        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        //Desenha o Bloco
        $tamanhoDoBloco = 10;
        $this->Rect($xInicial, $yInicial, $this->larguraMaxima, $tamanhoDoBloco);
        $this->desenharDescricao('Assinaturas', $xInicial, $yInicial);

        //Desenha os Campos
        $alturaDaLinha = $yInicial + 7;
        $tamanhoDaLarguraDaLinha = 50;

        //Responsável
        $posicaoXDaLinha = $xInicial + 10;
        $this->Line($posicaoXDaLinha, $alturaDaLinha, $posicaoXDaLinha + $tamanhoDaLarguraDaLinha, $alturaDaLinha);
        $this->Text($posicaoXDaLinha + 15, $alturaDaLinha + 2.5, "Responsável");

        //Secretária(o)
        $posicaoXDaLinha = $xInicial + 70;
        $this->Line($posicaoXDaLinha, $alturaDaLinha, $posicaoXDaLinha + $tamanhoDaLarguraDaLinha, $alturaDaLinha);
        $this->Text($posicaoXDaLinha + 20, $alturaDaLinha + 2.5, "Secretária");

        //Diretor(a)
        $posicaoXDaLinha = $xInicial + 130;
        $this->Line($posicaoXDaLinha, $alturaDaLinha, $posicaoXDaLinha + $tamanhoDaLarguraDaLinha, $alturaDaLinha);
        $this->Text($posicaoXDaLinha + 20, $alturaDaLinha + 2.5, "Diretor(a)");

        //Posiciona o cursor na posicao final
        $this->SetX($xInicial);
        $this->SetY($yInicial + $tamanhoDoBloco);
    }

    //Fim dos Blocos

    private function campoComDescricao($desc, $largura, $valor='') {

        $xInicial = $this->GetX();
        $yInicial = $this->GetY();

        $this->SetFont('Arial', '', $this->tamFontValores);
        $this->Cell($largura, $this->alturaDosCampos, $valor, 1, 0, 'C');
        $this->desenharDescricao($desc, $xInicial, $yInicial);
    }

    private function desenharDescricao($desc, $x, $y) {
        $this->SetFontSize($this->tamFontDescricao);
        $alturaDesc = $this->tamFontDescricao / 3;
        //$larguraDesc = ((strlen($desc)) * ($this->tamFontDescricao/6.2))+1.5;
        $larguraDesc = $this->GetStringWidth($desc) + 1;


        $this->SetFillColor(255, 255, 255);
        //Desenha um retangulo no fundo da descrição para que nao seja exibida uma linha cortando o texto
        $this->Rect($x + 3, $y - 1.7, $larguraDesc, $alturaDesc, 'F');
        //Desenha o texto presente no topo do retangulo (descrição)
        //$this->Text($x + 3.5, $y + 0.7, $desc);
        $this->Text($x + 4, $y + 0.3, $desc);
        $this->SetFillColor(255, 255, 255);
    }

    private function moverCursorAbaixo($px) {
        $this->SetY($this->GetY() + $px);
    }

    private function moverCursorADireita($px) {
        $this->SetX($this->GetX() + $px);
    }

    private function desenhaCampoMultiplo($nome, $x, $y, $selecionado = false) {
        $larguraCaixa = 7;
        $alturaCaixa = 4;
        $this->Rect($x, $y, $larguraCaixa, $alturaCaixa);
        $this->SetFontSize($this->tamFontDescricao);
        $this->Text($x + $larguraCaixa + 1, $y + ($alturaCaixa / 2) + 0.7, $nome);
        if ($selecionado) {
            $this->SetFontSize(12);
            $this->Text($x + ($larguraCaixa / 2) - 1.3, $y + ($alturaCaixa / 2) + 1.5, "X");
        }
    }

    public function Footer() {
        parent::rodapePadrao($this->PageNo(), '{total}');
    }

}
/*
$matricula = $_POST['matricula'];
if ($_POST['matricula']) {
    $matricula = $_POST['matricula'];
    $aluno = Aluno::getAlunoByNumMatricula($matricula);
    $pdf = new FichaDoAlunoPDF($aluno);
    $pdf->Output();
} else {
    $pdf=new FichaDoAlunoPDF();
    $pdf->Output();
}
*/

?>
