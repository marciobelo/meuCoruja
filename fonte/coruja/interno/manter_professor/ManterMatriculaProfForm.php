<?php
require_once "$BASE_DIR/classes/MatriculaProfessor.php";
require_once "$BASE_DIR/classes/Util.php";

class ManterMatriculaProfForm {
    
    // Campos de edição
    public $idPessoa;
    public $matriculaProfessorAntiga="";
    public $matriculaProfessorNova;
    public $cargaHoraria;
    public $dataInicioD;
    public $dataInicioM;
    public $dataInicioA;
    public $dataEncerramentoD;
    public $dataEncerramentoM;
    public $dataEncerramentoA;
    
    public $modo; // 'edicao' ou 'novo'

    function __construct($modo) {
        $this->modo=$modo;
    }

    public function atualizarDadosMatricula($idPessoa,$matriculaProfessor) {
        $this->modo = $_POST["modo"];
        $this->idPessoa = $idPessoa;
        $mp = MatriculaProfessor::obterMatriculaProfessor($matriculaProfessor);
        $this->matriculaProfessorAntiga = $mp->getMatriculaProfessor();
        $this->matriculaProfessorNova = $mp->getMatriculaProfessor();
        $this->cargaHoraria=$mp->getCargaHoraria();
        $this->dataInicioD=date("d",strtotime($mp->getDataInicio()));
        $this->dataInicioM=date("m",strtotime($mp->getDataInicio()));
        $this->dataInicioA=date("Y",strtotime($mp->getDataInicio()));
        $this->dataEncerramentoD = $mp->getDataEncerramento()!=null ? date("d",strtotime($mp->getDataEncerramento())) : "";
        $this->dataEncerramentoM = $mp->getDataEncerramento()!=null ? date("m",strtotime($mp->getDataEncerramento())) : "";
        $this->dataEncerramentoA = $mp->getDataEncerramento()!=null ? date("Y",strtotime($mp->getDataEncerramento())) : "";
    }

    public function atualizarDadosForm() {
        $this->modo = $_POST["modo"];
        $this->idPessoa = $_POST["idPessoa"];
        $this->matriculaProfessorAntiga = $_POST["matriculaProfessorAntiga"];
        $this->matriculaProfessorNova = $_POST["matriculaProfessorNova"];
        $this->cargaHoraria = $_POST["cargaHoraria"];
        $this->dataInicioD = $_POST["dataInicioD"];
        $this->dataInicioM = $_POST["dataInicioM"];
        $this->dataInicioA = $_POST["dataInicioA"];
        $this->dataEncerramentoD = $_POST["dataEncerramentoD"];
        $this->dataEncerramentoM = $_POST["dataEncerramentoM"];
        $this->dataEncerramentoA = $_POST["dataEncerramentoA"];
    }

    public function getDataInicio() {
        return $this->dataInicioA . "-" . $this->dataInicioM . "-" . $this->dataInicioD;
    }

     public function getDataEncerramento() {
        if($this->dataEncerramentoA!='' && $this->dataEncerramentoM!='' && $this->dataEncerramentoD !='') {
            return $this->dataEncerramentoA . "-" . $this->dataEncerramentoM . "-" . $this->dataEncerramentoD;
        } else {
            return "";
        }
     }

     public function getCargaHoraria() {
        return $this->cargaHoraria;
    }


    public function validarDados() {

        $msgsErro = array();

        // Valida campo matrícula
        if(empty($this->matriculaProfessorNova) || trim($this->matriculaProfessorNova)=="") {
            array_push($msgsErro, "A matrícula não pode ser vazia.");
        }
        
        // Valida campo data da início da matrícula
        if(!checkdate($this->dataInicioM, $this->dataInicioD, $this->dataInicioA)) {
            array_push($msgsErro, "Data de Início incorreta.");
        }      

        // Se estiver preenchido, valida o campo data do encerramento
        if($this->dataEncerramentoD != "" ||
            $this->dataEncerramentoM != "" ||
            $this->dataEncerramentoA != "") {
            if(!checkdate($this->dataEncerramentoM,
                    $this->dataEncerramentoD, $this->dataEncerramentoA)) {
                array_push($msgsErro, "Data de Encerramento incorreta.");
            }
        }

        return $msgsErro;
    }

}
?>
