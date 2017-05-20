<?php
require_once "$BASE_DIR/classes/Espaco.php";

class ManterEspacoForm {
    private $idEspaco;
    private $nome;
    private $capacidade;

    public function validar() {
        $msgs=array();
        
        if($this->nome=="") {
            array_push($msgs,"Campo nome é obrigatório.");
        }

        if($this->capacidade=="") {
            array_push($msgs,"Campo capacidade é obrigatório.");
        }

        if(!is_numeric($this->capacidade)) {
            array_push($msgs,"Campo capacidade deve ser numérico.");
        }

        return $msgs;
    }

    public function getIdEspaco() {
        return $this->idEspaco;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCapacidade() {
        return $this->capacidade;
    }

    public function atualizarDadosEspaco(Espaco $espaco) {
        $this->idEspaco=$espaco->getIdEspaco();
        $this->nome=$espaco->getNome();
        $this->capacidade=$espaco->getCapacidade();
    }

   /**
     * Obtem os dados enviados por requisição e atualiza
     * os dados do objeto formulário.
     */
    public function atualizarDadosForm() {
        $this->idEspaco=$_REQUEST["idEspaco"];
        $this->nome=$_REQUEST["nome"];
        $this->capacidade=$_REQUEST["capacidade"];
    }

}
?>
