<?php
/**
 * @author: Marcelo Atie
 * @name: SituacaoMatriculaHistorico.php
 * @version: 1.0
 * @since: versão 1.0
 */
class SituacaoMatriculaHistorico {

    private $matriculaAluno;
    private $dataHistorico;
    private $situacaoMatricula;
    private $texto;

    public function __construct($matriculaAluno, $dataHistorico, $situacaoMatricula, $texto) {
        $this->matriculaAluno = $matriculaAluno;
        $this->dataHistorico = $dataHistorico;
        $this->situacaoMatricula = $situacaoMatricula;
        $this->texto = $texto;
    }
    
    public function __toString() {
        return '<|'.$this->matriculaAluno .'|'.
        $this->dataHistorico .'|'.
        $this->situacaoMatricula .'|'.
        $this->texto.'|>';
    }

    public function getMatriculaAluno() {
        return $this->matriculaAluno;
    }

    public function setMatriculaAluno($matriculaAluno) {
        $this->matriculaAluno = $matriculaAluno;
    }

    public function getDataHistorico() {
        return $this->dataHistorico;
    }

    public function setDataHistorico($dataHistorico) {
        $this->dataHistorico = $dataHistorico;
    }

    public function getSituacaoMatricula() {
        return $this->situacaoMatricula;
    }

    public function setSituacaoMatricula($situacaoMatricula) {
        $this->situacaoMatricula = $situacaoMatricula;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    /**
     * Retorna uma lista com o histórico das situações da matrícula do aluno, opcionnalmente pode ser alterada a ordenação
     *
     * @author Marcelo Atie
     * @param <type> $numMatricculaAluno
     * @param <type> $ordem
     * @return SituacaoMatriculaHistorico
     */
    public static function getAllByNumMatriculaAluno($numMatricculaAluno, $ordem = 'smh.`dataHistorico` DESC') {
        $collection = array();
        $situMatriHisto;
        $con = BD::conectar();
        $query = sprintf(
                ' select ' .
                '    smh.`matriculaAluno`,' .
                '    smh.`dataHistorico`,' .
                '    smh.`situacaoMatricula`,' .
                '    smh.`texto`' .
                ' from' .
                '    SituacaoMatriculaHistorico smh' .
                ' where' .
                '    smh.`matriculaAluno` = \'%s\''.
                ' order by'.
                '    %s ',
                mysql_real_escape_string($numMatricculaAluno),
                mysql_real_escape_string($ordem));
        $result = mysql_query($query, $con);
        
        while ($resSituacoes = mysql_fetch_array($result)) {
            $situMatriHisto = new SituacaoMatriculaHistorico($resSituacoes['matriculaAluno'], $resSituacoes['dataHistorico'], $resSituacoes['situacaoMatricula'], $resSituacoes['texto']);
            $collection[] = $situMatriHisto;
        }
        return $collection;
    }

}

?>
