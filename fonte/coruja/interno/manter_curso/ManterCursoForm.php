<?php
require_once "$BASE_DIR/classes/Curso.php";

class ManterCursoForm {

    private $siglaCursoAntes;
    private $siglaCursoDepois;
    private $siglaCurso;
    private $nomeCurso;
    private $idTipoCurso;
    private $descricao;
    

    public function validar() {
        $msgs=array();
        
        if($this->siglaCursoAntes=="") {
            array_push($msgs,"Campo sigla do curso � obrigat�rio.");
        }

         if($this->nomeCurso=="") {
            array_push($msgs,"Campo nome do curso � obrigat�rio.");
        }

         if($this->idTipoCurso=="") {
            array_push($msgs,"Campo tipo do curso � obrigat�rio.");
        }

        return $msgs;
    }

    public function validarIncluir() {
        $msgs=array();

        if($this->siglaCurso=="") {
            array_push($msgs,"Campo sigla do curso � obrigat�rio.");
        }

         if($this->nomeCurso=="") {
            array_push($msgs,"Campo nome do curso � obrigat�rio.");
        }

         if($this->idTipoCurso=="") {
            array_push($msgs,"Campo tipo do curso � obrigat�rio.");
        }

        return $msgs;
    }

     public function getSiglaCurso() {
        return $this->siglaCurso;
    }

    public function getSiglaCursoAntes() {
        return $this->siglaCursoAntes;
    }

    public function getSiglaCursoDepois() {
        return $this->siglaCursoDepois;
    }

    public function getNomeCurso() {
        return $this->nomeCurso;
    }

    public function getIdTipoCurso() {
        return $this->idTipoCurso;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    
    public function atualizarDadosCurso(Curso $curso) {
        $this->siglaCursoAntes=$curso->getSiglaCurso();
        $this->siglaCursoDepois=$curso->getSiglaCurso();
        $this->siglaCurso=$curso->getSiglaCurso();
        $this->nomeCurso=$curso->getNomeCurso();
        $this->idTipoCurso=$curso->getIdTipoCurso();
        $this->descricao = $curso->getTipoCurso()->getDescricao();        
    }

   /**
     * Obtem os dados enviados por requisi��o e atualiza
     * os dados do objeto formul�rio.
     */
    public function atualizarDadosForm() {
        $this->siglaCursoAntes=$_REQUEST["siglaCursoAntes"];
        $this->siglaCursoDepois=$_REQUEST["siglaCursoDepois"];
        $this->siglaCurso=$_REQUEST["siglaCurso"];
        $this->nomeCurso=$_REQUEST["nomeCurso"];
        $this->idTipoCurso=$_REQUEST["idTipoCurso"];        
   }
   
}
?>
