<?php
require_once "$BASE_DIR/classes/TipoCurso.php";

class ManterTpcursoForm {
    private $idTipoCurso;
    private $descricao;

    public function validar() {
        $msgs=array();
        
        if(trim($this->descricao)=="") {
            array_push($msgs,"Campo Descri��o � obrigat�rio.");
        }

              return $msgs;
    }

    public function getIdTipoCurso() {
        return $this->idTipoCurso;
    }

    public function getDescricao() {
        return $this->descricao;
    }


    public function atualizarDadosTipoCurso(TipoCurso $idTipoCurso) {
        $this->idTipoCurso=$idTipoCurso->getIdTipoCurso();
        $this->descricao=$idTipoCurso->getDescricao();
    }

   /**
     * Obtem os dados enviados por requisi��o e atualiza
     * os dados do objeto formul�rio.
     */
    public function atualizarDadosForm() {
        $this->idTipoCurso=$_REQUEST["idTipoCurso"];
        $this->descricao=$_REQUEST["descricao"];
   }

}
?>
