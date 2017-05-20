<?php
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/MatriculaProfessor.php");

class ManterProfessorForm {

    // Dados da Pessoa
    public $idPessoa;
    public $nome="";
    public $sexo="M";
    public $dataNascimentoD="";
    public $dataNascimentoM="";
    public $dataNascimentoA="";
    public $dataInicioD ="";
    public $dataInicioM = "";
    public $dataInicioA = "";

    public $dataEncerramentoD = "";
    public $dataEncerramentoM = "";
    public $dataEncerramentoA="";


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

    //Professor
    public $titulacaoAcademica;
    public $cvLattes;
    public $nomeGuerra="";
    public $corFundo="";

    // MatriculaProfessor
    public $novaMatriculaProfessor;
    public $cargaHoraria;
    public $dataInicio;
    public $dataEncerramento;

    public $modo; // 'novo' ou 'edicao'

    function __construct($modo="novo") {
        // Indica se é inserção (novo) ou edicao
        $this->modo = $modo;
    }

    /**
     * Obtem os dados enviados por requisição e atualiza
     * os dados do objeto formulário.
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

        //Professor
        $this->titulacaoAcademica = $_POST['titulacaoAcademica'];
        $this->cvLattes = $_POST['cvLattes'];
        $this->nomeGuerra=$_POST['nomeGuerra'];
        $this->corFundo=$_POST['corFundo'];


        // MatriculaProfessor
        $this->novaMatriculaProfessor = $_POST['novaMatriculaProfessor'];
        $this->cargaHoraria = $_POST['cargaHoraria'];
        $this->dataInicioD = $_POST['dataInicioD'];
        $this->dataInicioM = $_POST['dataInicioM'];
        $this->dataInicioA = $_POST['dataInicioA'];

        $this->dataEncerramentoD = $_POST['dataEncerramentoD'];
        $this->dataEncerramentoM = $_POST['dataEncerramentoM'];
        $this->dataEncerramentoA = $_POST['dataEncerramentoA'];

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

    public function getTitulacaoAcademica() {
        return $this->titulacaoAcademica;
    }

    public function getCvLattes() {
        return $this->cvLattes;
    }

    public function getNomeGuerra() {
        return $this->nomeGuerra;
    }

    public function getCorFundo() {
        return $this->corFundo;
    }

    public function getDataInicio() {
        return $this->dataInicioA . "-" . $this->dataInicioM . "-" . $this->dataInicioD;
    }

    public function getDataEncerramento() {
        if($this->dataEncerramentoA!='' && $this->dataEncerramentoM!='' && $this->dataEncerramentoD !='') {
            return $this->dataEncerramentoA . "-" . $this->dataEncerramentoM . "-" . $this->dataEncerramentoD;
        } else {
            return null;
        }
    }

    public function validarDados() {

        $msgsErro = array();

        // verifica se o sexo  foi informado
        if($_REQUEST["nome"]=="") {
            array_push($msgsErro, "O nome deve ser informado");
        }

        // verifica se o sexo  foi informado
        if($_REQUEST["sexo"]=="") {
            array_push($msgsErro, "O sexo deve ser informado");
        }

        // verifica se a nacionalidade  foi informado
        if($_REQUEST["nacionalidade"]=="") {
            array_push($msgsErro, "A nacionalidade  deve ser informado");
        }

        // verifica se a naturalidade  foi informado
        if($_REQUEST["naturalidade"]=="") {
            array_push($msgsErro, "A naturalidade deve ser informada");
        }

        // Valida campo data de nascimento
        if(!checkdate($this->dataNascimentoM, $this->dataNascimentoD, $this->dataNascimentoA)) {
            array_push($msgsErro, "Data de nascimento  incorreta.");
        }

        
        // Valida campo nova matrícula do professor
        if($this->modo == "novo") {
           
            if(empty($this->novaMatriculaProfessor) || trim($this->novaMatriculaProfessor)=="") {
                array_push($msgsErro, "A matrícula não pode ser vazia.");
            }

            // Valida campo data de inicio
            if(!checkdate($this->dataInicioM, $this->dataInicioD, $this->dataInicioA)) {
                array_push($msgsErro, "Data de início da matrícula incorreta.");
            }
        }

        return $msgsErro;
    }

    /**
     * Atualiza os dados do objeto-formulário com os do objeto de Aluno
     * @param Aluno $professor
     */
    public function atualizarDadosProfessor(Professor $professor) {

        $this->idPessoa = $professor->getIdPessoa();
        $this->nome = $professor->getNome();
        $this->sexo= $professor->getSexo();
        $this->dataNascimentoD=date("d",strtotime($professor->getDataNascimento()));
        $this->dataNascimentoM=date("m",strtotime($professor->getDataNascimento()));
        $this->dataNascimentoA=date("Y",strtotime($professor->getDataNascimento()));
        $this->enderecoLogradouro=$professor->getEnderecoLogradouro();
        $this->enderecoNumero=$professor->getEnderecoNumero();
        $this->enderecoComplemento=$professor->getEnderecoComplemento();
        $this->enderecoBairro=$professor->getEnderecoBairro();
        $this->enderecoMunicipio=$professor->getEnderecoMunicipio();
        $this->enderecoEstado=$professor->getEnderecoEstado();
        $this->enderecoCEP=$professor->getEnderecoCep();

        $this->nacionalidade=$professor->getNacionalidade();
        $this->naturalidade=$professor->getNaturalidade();
        $this->telefoneResidencial=$professor->getTelefoneResidencial();
        $this->telefoneComercial=$professor->getTelefoneComercial();
        $this->telefoneCelular=$professor->getTelefoneCelular();
        $this->email=$professor->getEmail();

        //Professor      
        $this->titulacaoAcademica=$professor->getTitulacaoAcademica();
        $this->cvLattes=$professor->getCvLattes();
        $this->nomeGuerra=$professor->getNomeGuerra();
        $this->corFundo=$professor->getCorFundo();
    }
}
?>
