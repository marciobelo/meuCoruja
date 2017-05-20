<?php
require_once "$BASE_DIR/classes/BD.php";

class buscaAluno
{

    private $idPessoa;
    private $nomeAluno;
    private $matriculaAluno;
    private $situacaoMatricula;
    private $siglaCurso;
    private $nomeCurso;

    public function getIdPessoa() {
        return $this->idPessoa;
    }

    public function setIdPessoa($idPessoa) {
        $this->idPessoa = $idPessoa;
    }

    public function getNomeAluno() {
        return $this->nomeAluno;
    }

    public function setNomeAluno($nomeAluno) {
        $this->nomeAluno = $nomeAluno;
    }
    
    public function getMatriculaAluno() {
        return $this->matriculaAluno;
    }

    public function setMatriculaAluno($matriculaAluno) {
        $this->matriculaAluno = $matriculaAluno;
    }

    public function getSiglaCurso() {
        return $this->siglaCurso;
    }

    public function setSiglaCurso($siglaCurso) {
        $this->siglaCurso = $siglaCurso;
    }
    
    public function setNomeCurso($nomeCurso) {
        $this->nomeCurso = $nomeCurso;
    }
    public function getNomeCurso() {
        return $this->nomeCurso;
    }

    public function setSituacaoMatricula($situacaoMatricula) {
        $this->situacaoMatricula = $situacaoMatricula;
    }
    public function getSituacaoMatricula() {
        return $this->situacaoMatricula;
    }
    
    function buscaAlunoByNome($nomeAluno){
        $buscaAlunos = null;
        
        $con = BD::conectar();
        
        $query=sprintf("SELECT p.idPessoa, matriculaAluno, nome, m.siglaCurso, situacaoMatricula, nomeCurso ".
                "FROM Pessoa p ".
                "INNER JOIN MatriculaAluno m ".
                "ON p.idPessoa = m.idPessoa ".
                "INNER JOIN Curso c ".
                "ON m.siglaCurso = c.siglaCurso ".
                "WHERE nome like '%s%%' order by nome",mysql_real_escape_string($nomeAluno));
        $result=mysql_query($query,$con);
        
        $listaAlunos = array();
        
        while($resAluno = mysql_fetch_array($result) ) {

            $buscaAlunos = new buscaAluno();
            
            $buscaAlunos->setIdPessoa($resAluno['idPessoa']);
            $buscaAlunos->setMatriculaAluno($resAluno['matriculaAluno']);
            $buscaAlunos->setNomeAluno($resAluno['nome']);
            $buscaAlunos->setSiglaCurso($resAluno['siglaCurso']);
            $buscaAlunos->setNomeCurso($resAluno['nomeCurso']);
            $buscaAlunos->setSituacaoMatricula($resAluno['situacaoMatricula']);
            
            array_push($listaAlunos, $buscaAlunos);
        }
        return $listaAlunos;   
    }

    function buscaAlunoByMatricula($matriculaAluno){
        $buscaAlunos = null;
        
        $con = BD::conectar();

        $query=sprintf("SELECT p.idPessoa, matriculaAluno, nome, m.siglaCurso, situacaoMatricula, nomeCurso ".
                "FROM Pessoa p ".
                "INNER JOIN MatriculaAluno m ".
                "ON p.idPessoa = m.idPessoa ".
                "INNER JOIN Curso c ".
                "ON m.siglaCurso = c.siglaCurso ".
                "WHERE m.matriculaAluno = '%s' order by nome ",mysql_real_escape_string($matriculaAluno));
        $result=mysql_query($query,$con);


        $listaAlunos = array();

        while($resAluno = mysql_fetch_array($result) ) {

            $buscaAlunos = new buscaAluno();

            $buscaAlunos->setIdPessoa($resAluno['idPessoa']);
            $buscaAlunos->setMatriculaAluno($resAluno['matriculaAluno']);
            $buscaAlunos->setNomeAluno($resAluno['nome']);
            $buscaAlunos->setSiglaCurso($resAluno['siglaCurso']);
            $buscaAlunos->setNomeCurso($resAluno['nomeCurso']);
            $buscaAlunos->setSituacaoMatricula($resAluno['situacaoMatricula']);
            array_push($listaAlunos, $buscaAlunos);
        }
        return $listaAlunos;
    }
}