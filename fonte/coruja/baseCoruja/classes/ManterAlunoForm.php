<?php
require_once("$BASE_DIR/classes/Curso.php");
require_once("$BASE_DIR/classes/FormaIngresso.php");
require_once("$BASE_DIR/classes/Aluno.php");

class ManterAlunoForm {
    
    /*
     * Campos do Formul�rio
     */
    // Dados da Pessoa
    public $idPessoa;
    public $nome="";
    public $sexo="M";
    public $dataNascimentoD="";
    public $dataNascimentoM="";
    public $dataNascimentoA="";
    public $enderecoLogradouro="";
    public $enderecoNumero="";
    public $enderecoComplemento="";
    public $enderecoBairro="";
    public $enderecoMunicipio="";
    public $enderecoEstado="RJ";
    public $enderecoCEP="";
    public $nacionalidade="";
    public $naturalidade="";
    public $telefoneResidencial="";
    public $telefoneComercial="";
    public $telefoneCelular="";
    public $email="";

    //Aluno
    public $estadoCivil="SOLTEIRO";
    public $corRaca="";
    public $deficienciaAuditiva="N�O";
    public $deficienciaVisual="N�O";
    public $deficienciaMotora="N�O";
    public $deficienciaMental="N�O";
    public $rgNumero="";
    public $rgOrgaoEmissor="";
    public $rgDataEmissaoD="";
    public $rgDataEmissaoM="";
    public $rgDataEmissaoA="";
    public $cpf="";
    public $cpfProprio="SIM";
    public $certidaoNascimentoNumero="";
    public $certidaoNascimentoLivro="";
    public $certidaoNascimentoFolha="";
    public $certidaoNascimentoCidade="";
    public $certidaoNascimentoSubDistrito="";
    public $certidaoNascimentoUF="";
    public $certidaoCasamentoNumero="";
    public $certidaoCasamentoLivro="";
    public $certidaoCasamentoFolha="";
    public $certidaoCasamentoCidade="";
    public $certidaoCasamentoSubDistrito="";
    public $certidaoCasamentoUF="";
    public $certificadoAlistamentoMilitarNumero="";
    public $certificadoAlistamentoMilitarSerie="";
    public $certificadoAlistamentoMilitarDataD="";
    public $certificadoAlistamentoMilitarDataM="";
    public $certificadoAlistamentoMilitarDataA="";
    public $certificadoAlistamentoMilitarRM="";
    public $certificadoAlistamentoMilitarCSM="";
    public $certificadoReservistaNumero="";
    public $certificadoReservistaSerie="";
    public $certificadoReservistaDataD="";
    public $certificadoReservistaDataM="";
    public $certificadoReservistaDataA="";
    public $certificadoReservistaCAT="";
    public $certificadoReservistaRM="";
    public $certificadoReservistaCSM="";
    public $tituloEleitorNumero="";
    public $tituloEleitorDataD="";
    public $tituloEleitorDataM="";
    public $tituloEleitorDataA="";
    public $tituloEleitorZona="";
    public $tituloEleitorSecao="";
    public $ctps="";

    public $nomeMae="";
    public $rgMae="";
    public $nomePai="";
    public $rgPai="";
    public $responsavelLegal="";
    public $rgResponsavel="";
    public $estabCursoOrigem="";
    public $estabCursoOrigemCidade = "RIO DE JANEIRO";
    public $estabCursoOrigemUF="";
    public $cursoOrigemAnoConclusao="";
    public $modalidadeCursoOrigem="ENSINO M�DIO";

    // MatriculaAluno
    public $novaMatriculaAluno="";
    public $dataNovaMatriculaD="";
    public $dataNovaMatriculaM="";
    public $dataNovaMatriculaA="";
    public $siglaCursoNovaMatricula="";
    public $turnoIngressoNovaMatricula="";
    public $idFormaIngressoNovaMatricula;
    public $concursoPontosNovaMatricula;
    public $concursoClassificacaoNovaMatricula;

    public $modo; // 'novo' ou 'edicao'
    public $aba; // n�mero da aba que est� sendo editada

    public $cursos; // array de Curso
    public $formasIngresso; // array de FormaIngresso

    function __construct($modo="novo") {

        // Preenche lista auxiliar de cursos dispon�veis
        $this->cursos = Curso::obterCursosOrdemPorSigla();

        // Preenche lista auxiliar de formas de ingresso
        $this->formasIngresso = FormaIngresso::obterFormasIngresso();

        // Indica se � inser��o (novo) ou edicao
        $this->modo = $modo;
    }

    /**
     * Obtem os dados enviados por requisi��o e atualiza
     * os dados do objeto formul�rio.
     */
    public function atualizarDadosForm() {

        $this->idPessoa = $_POST["idPessoa"];
        $this->nome = $_POST["nome"];
        $this->sexo = $_POST["sexo"];
        $this->dataNascimentoD = $_POST["dataNascimentoD"];
        $this->dataNascimentoM = $_POST["dataNascimentoM"];
        $this->dataNascimentoA = $_POST["dataNascimentoA"];
        $this->nacionalidade = $_POST["nacionalidade"];
        $this->naturalidade = $_POST["naturalidade"];

        $this->enderecoCEP = $_POST['enderecoCEP'];
        $this->enderecoLogradouro = $_POST['enderecoLogradouro'];

        $this->enderecoNumero = $_POST['enderecoNumero'];
        $this->enderecoComplemento = $_POST['enderecoComplemento'];
        $this->enderecoBairro = $_POST['enderecoBairro'];
        $this->enderecoMunicipio = $_POST['enderecoMunicipio'];
        $this->enderecoEstado = $_POST['enderecoEstado'];
        $this->telefoneResidencial = $_POST['telefoneResidencial'];
        $this->telefoneComercial = $_POST['telefoneComercial'];
        $this->telefoneCelular = $_POST['telefoneCelular'];
        $this->email = $_POST['email'];

        //Aluno
        $this->estadoCivil = $_POST['estadoCivil'];
        $this->corRaca = $_POST['corRaca'];
        $this->deficienciaVisual = isset($_POST['deficienciaVisual'])?$_POST['deficienciaVisual']:"N�O";
        $this->deficienciaMotora = isset($_POST['deficienciaMotora'])?$_POST['deficienciaMotora']:"N�O";
        $this->deficienciaAuditiva = isset($_POST['deficienciaAuditiva'])?$_POST['deficienciaAuditiva']:"N�O";
        $this->deficienciaMental = isset($_POST['deficienciaMental'])?$_POST['deficienciaMental']:"N�O";
        $this->rgNumero = $_POST['rgNumero'];
        $this->rgOrgaoEmissor = $_POST['rgOrgaoEmissor'];
        $this->rgUF = $_POST['rgUF'];
        $this->rgDataEmissaoD = $_POST['rgDataEmissaoD'];
        $this->rgDataEmissaoM = $_POST['rgDataEmissaoM'];
        $this->rgDataEmissaoA = $_POST['rgDataEmissaoA'];
        $this->cpf = $_POST['cpf'];
        $this->cpfProprio = $_POST['cpfProprio'];
        $this->certidaoNascimentoNumero = $_POST['certidaoNascimentoNumero'];
        $this->certidaoNascimentoLivro = $_POST['certidaoNascimentoLivro'];
        $this->certidaoNascimentoFolha = $_POST['certidaoNascimentoFolha'];
        $this->certidaoNascimentoCidade = $_POST['certidaoNascimentoCidade'];
        $this->certidaoNascimentoSubDistrito = $_POST['certidaoNascimentoSubDistrito'];
        $this->certidaoNascimentoUF = $_POST['certidaoNascimentoUF'];
        $this->certidaoCasamentoNumero = $_POST['certidaoCasamentoNumero'];
        $this->certidaoCasamentoLivro = $_POST['certidaoCasamentoLivro'];
        $this->certidaoCasamentoFolha = $_POST['certidaoCasamentoFolha'];
        $this->certidaoCasamentoCidade = $_POST['certidaoCasamentoCidade'];
        $this->certidaoCasamentoSubDistrito = $_POST['certidaoCasamentoSubDistrito'];
        $this->certidaoCasamentoUF = $_POST['certidaoCasamentoUF'];
        $this->certificadoAlistamentoMilitarNumero = $_POST['certificadoAlistamentoMilitarNumero'];
        $this->certificadoAlistamentoMilitarSerie = $_POST['certificadoAlistamentoMilitarSerie'];
        $this->certificadoAlistamentoMilitarDataD = $_POST["certificadoAlistamentoMilitarDataD"];
        $this->certificadoAlistamentoMilitarDataM = $_POST["certificadoAlistamentoMilitarDataM"];
        $this->certificadoAlistamentoMilitarDataA = $_POST["certificadoAlistamentoMilitarDataA"];
        $this->certificadoAlistamentoMilitarRM = $_POST['certificadoAlistamentoMilitarRM'];
        $this->certificadoAlistamentoMilitarCSM = $_POST['certificadoAlistamentoMilitarCSM'];
        $this->certificadoReservistaNumero = $_POST['certificadoReservistaNumero'];
        $this->certificadoReservistaSerie = $_POST['certificadoReservistaSerie'];
        $this->certificadoReservistaDataD = $_POST["certificadoReservistaDataD"];
        $this->certificadoReservistaDataM = $_POST["certificadoReservistaDataM"];
        $this->certificadoReservistaDataA = $_POST["certificadoReservistaDataA"];
        $this->certificadoReservistaCAT = $_POST['certificadoReservistaCAT'];
        $this->certificadoReservistaRM = $_POST['certificadoReservistaRM'];
        $this->certificadoReservistaCSM = $_POST['certificadoReservistaCSM'];
        $this->tituloEleitorNumero = $_POST['tituloEleitorNumero'];
        $this->tituloEleitorDataD = $_POST['tituloEleitorDataD'];
        $this->tituloEleitorDataM = $_POST['tituloEleitorDataM'];
        $this->tituloEleitorDataA = $_POST['tituloEleitorDataA'];
        $this->tituloEleitorZona = $_POST['tituloEleitorZona'];
        $this->tituloEleitorSecao = $_POST['tituloEleitorSecao'];
        $this->ctps = $_POST['ctps'];

        $this->nomeMae = $_POST['nomeMae'];
        $this->rgMae = $_POST['rgMae'];
        $this->nomePai = $_POST['nomePai'];
        $this->rgPai = $_POST['rgPai'];
        $this->responsavelLegal = $_POST['responsavelLegal'];
        $this->rgResponsavel = $_POST['rgResponsavel'];
        $this->estabCursoOrigem = $_POST['estabCursoOrigem'];
        $this->estabCursoOrigemCidade = $_POST['estabCursoOrigemCidade'];
        $this->estabCursoOrigemUF = $_POST['estabCursoOrigemUF'];
        $this->cursoOrigemAnoConclusao = $_POST['cursoOrigemAnoConclusao'];
        $this->modalidadeCursoOrigem = $_POST['modalidadeCursoOrigem'];

        // MatriculaAluno
        $this->novaMatriculaAluno = $_POST['novaMatriculaAluno'];
        $this->dataNovaMatriculaD = $_POST['dataNovaMatriculaD'];
        $this->dataNovaMatriculaM = $_POST['dataNovaMatriculaM'];
        $this->dataNovaMatriculaA = $_POST['dataNovaMatriculaA'];
        $this->siglaCursoNovaMatricula = $_POST["siglaCursoNovaMatricula"];
        $this->turnoIngressoNovaMatricula = $_POST['turnoIngressoNovaMatricula'];
        $this->idFormaIngressoNovaMatricula = $_POST["idFormaIngressoNovaMatricula"];
        $this->concursoPontosNovaMatricula = $_POST["concursoPontosNovaMatricula"];
        $this->concursoClassificacaoNovaMatricula = $_POST["concursoClassificacaoNovaMatricula"];

        $this->modo = $_POST["modo"];
        $this->aba = $_POST["aba"];
    }

    public function getDataNascimento() {
        return $this->dataNascimentoA . "-" . $this->dataNascimentoM . "-" . $this->dataNascimentoD;
    }

    public function getTelefoneResidencial() {
        return $this->telefoneResidencial;
    }

    public function getTelefoneComercial() {
        return $this->telefoneComercial;
    }

    public function getTelefoneCelular() {
        return $this->telefoneCelular;
    }

    public function getRgDataEmissao() {
        return  $this->rgDataEmissaoA . "-" . $this->rgDataEmissaoM . "-" . $this->rgDataEmissaoD;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getTituloEleitorData() {
        if(empty($this->tituloEleitorDataA) && empty($this->tituloEleitorDataM) && empty($this->tituloEleitorDataD) ) {
            return "";
        } else  {
            return $this->tituloEleitorDataA . "-" . $this->tituloEleitorDataM . "-" . $this->tituloEleitorDataD;
        }
    }

    public function getCertificadoAlistamentoMilitarData() {
        if(empty($this->certificadoAlistamentoMilitarDataA) && empty($this->certificadoAlistamentoMilitarDataM) && empty($this->certificadoAlistamentoMilitarDataD) ) {
            return "";
        } else  {
            return $this->certificadoAlistamentoMilitarDataA . "-" . $this->certificadoAlistamentoMilitarDataM . "-" . $this->certificadoAlistamentoMilitarDataD;
        }
    }

    public function getCertificadoReservistaData() {
        if(empty($this->certificadoReservistaDataA) && empty($this->certificadoReservistaDataM) && empty($this->certificadoReservistaDataD) ) {
            return "";
        } else  {
            return $this->certificadoReservistaDataA . "-" . $this->certificadoReservistaDataM . "-" . $this->certificadoReservistaDataD;
        }
    }

    public function getDataNovaMatricula() {
        return $this->dataNovaMatriculaA . "-" . $this->dataNovaMatriculaM . "-" . $this->dataNovaMatriculaD;
    }

    public function getConcursoPontosNovaMatricula() {
        return str_replace(",", ".", $this->concursoPontosNovaMatricula);
    }

    public function validarDados() {
        $msgsErro = array();

        // verifica se o sexo do aluno foi informado
        if($_REQUEST["sexo"]=="") {
            array_push($msgsErro, "O sexo do aluno deve ser informado");
        }

        // verifica se a nacionalidade do aluno foi informado
        if($_REQUEST["nacionalidade"]=="") {
            array_push($msgsErro, "A nacionalidade do aluno deve ser informado");
        }

        // verifica se a naturalidade do aluno foi informado
        if($_REQUEST["naturalidade"]=="") {
            array_push($msgsErro, "A naturalidade deve ser informada");
        }

        // Valida campo data de nascimento
        if(!checkdate($this->dataNascimentoM, $this->dataNascimentoD, $this->dataNascimentoA)) {
            array_push($msgsErro, "Data de nascimento do aluno incorreta.");
        }

        // Valida campo data de emiss�o do rg
        if(!checkdate($this->rgDataEmissaoM, $this->rgDataEmissaoD, $this->rgDataEmissaoA)) {
            array_push($msgsErro, "Data de Emiss�o do RG incorreta.");
        }

        // Valida o campo de CPF
        if(!Util::validaCPF(str_ireplace("-", "", $this->getCpf()))) {
            array_push($msgsErro, "CPF inv�lido.");
        }

        // Se estiver preenchido, valida o campo data do cert.alistamento militar
        if($this->certificadoAlistamentoMilitarDataD != "" ||
            $this->certificadoAlistamentoMilitarDataM != "" ||
            $this->certificadoAlistamentoMilitarDataA != "") {
            if(!checkdate($this->certificadoAlistamentoMilitarDataM,
                    $this->certificadoAlistamentoMilitarDataD, $this->certificadoAlistamentoMilitarDataA)) {
                array_push($msgsErro, "Data do Certificado de Alistamento Militar incorreta.");
            }
        }

        // Se estiver preenchido, valida o campo data do cert.reservista
        if($this->certificadoReservistaDataD != "" ||
            $this->certificadoReservistaDataM != "" ||
            $this->certificadoReservistaDataA != "") {
            if(!checkdate($this->certificadoReservistaDataM,
                    $this->certificadoReservistaDataD, $this->certificadoReservistaDataA)) {
                array_push($msgsErro, "Data do Certificado de Reservista incorreta.");
            }
        }
        
        // Se estiver preenchido, valida o campo data de emiss�o tit.eleitoral
        if($this->tituloEleitorDataD != "" ||
            $this->tituloEleitorDataM != "" ||
            $this->tituloEleitorDataA != "") {
            if(!checkdate($this->tituloEleitorDataM,
                    $this->tituloEleitorDataD, $this->tituloEleitorDataA)) {
                array_push($msgsErro, "Data de emiss�o do t�tulo eleitoral incorreta.");
            }
        }
        
        // Se estiver preenchido, valida o campo email
        if( $this->email != "") {
            if( !Util::check_email_address($this->email) ) {
                array_push($msgsErro, "Email incorreto.");
            }
        }
        
        /* Nesse modo novo, uma nova matr�cula de aluno tamb�m est� sendo
         * entrada, portanto, valida-se tamb�m os campos obrigat�rios.
         */
        if( $this->modo=="novo" ) {

            // Valida campo nova matricula aluno
            if($this->novaMatriculaAluno=="") {
                array_push($msgsErro, "A matr�cula do aluno deve ser informada.");
            }

            // Valida data da matricula
            if(!checkdate($this->dataNovaMatriculaM,
                    $this->dataNovaMatriculaD, $this->dataNovaMatriculaA)) {
                array_push($msgsErro, "Data do Matr�cula n�o � v�lida.");
            }

            // verifica se o curso foi informado
            if($this->siglaCursoNovaMatricula=="") {
                array_push($msgsErro, "O curso deve ser informado.");
            }

            // verifica se turno da nova matr�cula foi informado
            if($this->turnoIngressoNovaMatricula=="") {
                array_push($msgsErro, "O turno deve ser informado.");
            }

            // verifica se a forma de ingresso da nova matr�cula foi informada
            if($this->idFormaIngressoNovaMatricula=="") {
                array_push($msgsErro, "A forma de ingresso deve ser informada.");
            }

            // Valida se o campo concurso pontos � ok
            if( (!is_numeric( $this->getConcursoPontosNovaMatricula() )) && (!empty($this->concursoPontosNovaMatricula)) ) {
                array_push($msgsErro, "O campo Concurso Pontos est� incorreto.");
            }

            // Valida se o campo concurso classifica��o � ok
            if( (!is_numeric($this->concursoClassificacaoNovaMatricula)) && (!empty($this->concursoClassificacaoNovaMatricula)) ) {
                array_push($msgsErro, "O campo Concurso Classifica��o est� incorreto.");
            }
        }

        return $msgsErro;
    }

    /**
     * Atualiza os dados do objeto-formul�rio com os do objeto de Aluno
     * @param Aluno $aluno
     */
    public function atualizarDadosAluno(Aluno $aluno) {

        $this->idPessoa = $aluno->getIdPessoa();
        $this->nome = $aluno->getNome();
        $this->sexo= $aluno->getSexo();
        $this->dataNascimentoD=date("d",strtotime($aluno->getDataNascimento()));
        $this->dataNascimentoM=date("m",strtotime($aluno->getDataNascimento()));
        $this->dataNascimentoA=date("Y",strtotime($aluno->getDataNascimento()));
        $this->enderecoLogradouro=$aluno->getEnderecoLogradouro();
        $this->enderecoNumero=$aluno->getEnderecoNumero();
        $this->enderecoComplemento=$aluno->getEnderecoComplemento();
        $this->enderecoBairro=$aluno->getEnderecoBairro();
        $this->enderecoMunicipio=$aluno->getEnderecoMunicipio();
        $this->enderecoEstado=$aluno->getEnderecoEstado();
        $this->enderecoCEP=$aluno->getEnderecoCep();

        $this->nacionalidade=$aluno->getNacionalidade();
        $this->naturalidade=$aluno->getNaturalidade();
        $this->telefoneResidencial=$aluno->getTelefoneResidencial();
        $this->telefoneComercial=$aluno->getTelefoneComercial();
        $this->telefoneCelular=$aluno->getTelefoneCelular();
        $this->email=$aluno->getEmail();

        //Aluno
        $this->estadoCivil=$aluno->getEstadoCivil();
        $this->corRaca=$aluno->getCorRaca();
        $this->deficienciaAuditiva=$aluno->getDeficienciaAuditiva();
        $this->deficienciaVisual=$aluno->getDeficienciaVisual();
        $this->deficienciaMotora=$aluno->getDeficienciaMotora();
        $this->deficienciaMental=$aluno->getDeficienciaMental();

        $this->rgNumero=$aluno->getRgNumero();
        $this->rgOrgaoEmissor=$aluno->getRgOrgaoEmissor();

        $this->rgDataEmissaoD=date("d",strtotime($aluno->getRgDataEmissao()));
        $this->rgDataEmissaoM=date("m",strtotime($aluno->getRgDataEmissao()));
        $this->rgDataEmissaoA=date("Y",strtotime($aluno->getRgDataEmissao()));
        $this->cpf=$aluno->getCpf();
        $this->cpfProprio=$aluno->getCpfProprio();
        $this->certidaoNascimentoNumero=$aluno->getCertidaoNascimentoNumero();
        $this->certidaoNascimentoLivro=$aluno->getCertidaoNascimentoLivro();
        $this->certidaoNascimentoFolha=$aluno->getCertidaoNascimentoFolha();
        $this->certidaoNascimentoCidade=$aluno->getCertidaoNascimentoCidade();
        $this->certidaoNascimentoSubDistrito=$aluno->getCertidaoNascimentoSubdistrito();
        $this->certidaoNascimentoUF=$aluno->getCertidaoNascimentoUF();
        $this->certidaoCasamentoNumero=$aluno->getCertidaoCasamentoNumero();
        $this->certidaoCasamentoLivro=$aluno->getCertidaoCasamentoLivro();
        $this->certidaoCasamentoFolha=$aluno->getCertidaoCasamentoFolha();
        $this->certidaoCasamentoCidade=$aluno->getCertidaoCasamentoCidade();
        $this->certidaoCasamentoSubDistrito=$aluno->getCertidaoCasamentoSubdistrito();
        $this->certidaoCasamentoUF=$aluno->getCertidaoCasamentoUF();
        $this->certificadoAlistamentoMilitarNumero=$aluno->getCertificadoAlistamentoMilitarNumero();
        $this->certificadoAlistamentoMilitarSerie=$aluno->getCertificadoAlistamentoMilitarSerie();
        $this->certificadoAlistamentoMilitarDataD= $aluno->getCertificadoAlistamentoMilitarData()!=null ? date("d",strtotime($aluno->getCertificadoAlistamentoMilitarData())) : "";
        $this->certificadoAlistamentoMilitarDataM= $aluno->getCertificadoAlistamentoMilitarData()!=null ? date("m",strtotime($aluno->getCertificadoAlistamentoMilitarData())) : "";
        $this->certificadoAlistamentoMilitarDataA= $aluno->getCertificadoAlistamentoMilitarData()!=null ? date("Y",strtotime($aluno->getCertificadoAlistamentoMilitarData())) : "";
        $this->certificadoAlistamentoMilitarRM=$aluno->getCertificadoAlistamentoMilitarRM();
        $this->certificadoAlistamentoMilitarCSM=$aluno->getCertificadoAlistamentoMilitarCSM();
        $this->certificadoReservistaNumero=$aluno->getCertificadoReservistaNumero();
        $this->certificadoReservistaSerie=$aluno->getCertificadoReservistaSerie();
        $this->certificadoReservistaDataD= $aluno->getCertificadoReservistaData()!=null ? date("d",strtotime($aluno->getCertificadoReservistaData())) : "";
        $this->certificadoReservistaDataM= $aluno->getCertificadoReservistaData()!=null ? date("m",strtotime($aluno->getCertificadoReservistaData())) : "";
        $this->certificadoReservistaDataA= $aluno->getCertificadoReservistaData()!=null ? date("Y",strtotime($aluno->getCertificadoReservistaData())) : "";
        $this->certificadoReservistaCAT=$aluno->getCertificadoReservistaCAT();
        $this->certificadoReservistaRM=$aluno->getCertificadoReservistaRM();
        $this->certificadoReservistaCSM=$aluno->getCertificadoReservistaCSM();
        $this->tituloEleitorNumero=$aluno->getTituloEleitorNumero();
        $this->tituloEleitorDataD= $aluno->getTituloEleitorData()!=null ? date("d",strtotime($aluno->getTituloEleitorData())) : "";
        $this->tituloEleitorDataM= $aluno->getTituloEleitorData()!=null ? date("m",strtotime($aluno->getTituloEleitorData())) : "";
        $this->tituloEleitorDataA= $aluno->getTituloEleitorData()!=null ? date("Y",strtotime($aluno->getTituloEleitorData())) : "";
        $this->tituloEleitorZona=$aluno->getTituloEleitorZona();
        $this->tituloEleitorSecao=$aluno->getTituloEleitorSecao();
        $this->ctps=$aluno->getCtps();

        $this->nomeMae=$aluno->getNomeMae();
        $this->rgMae=$aluno->getRgMae();
        $this->nomePai=$aluno->getNomePai();
        $this->rgPai=$aluno->getRgPai();
        $this->responsavelLegal=$aluno->getResponsavelLegal();
        $this->rgResponsavel=$aluno->getRgResponsavel();
        $this->estabCursoOrigem=$aluno->getEstabCursoOrigem();
        $this->estabCursoOrigemCidade = $aluno->getEstabCursoOrigemCidade();
        $this->estabCursoOrigemUF=$aluno->getEstabCursoOrigemUF();
        $this->cursoOrigemAnoConclusao=$aluno->getCursoOrigemAnoConclusao();
        $this->modalidadeCursoOrigem=$aluno->getModalidadeCursoOrigem();

    }
}
?>
