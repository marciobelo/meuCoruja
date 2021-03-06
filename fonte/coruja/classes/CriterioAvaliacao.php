<?php
require_once "$BASE_DIR/classes/ItemCriterioAvaliacao.php";

class CriterioAvaliacao {
    
    private $idCriterioAvalicao;
    private $rotulo;
    
    public function __construct($idCriterioAvalicao, $rotulo) {
        $this->idCriterioAvalicao = $idCriterioAvalicao;
        $this->rotulo = $rotulo;
    }
    
    public function getIdCriterioAvalicao() {
        return $this->idCriterioAvalicao;
    }

    public static function obterPorId($idCriterioAvaliacao) {
        $con = BD::conectar();
        $query = sprintf("select * from CriterioAvaliacao 
            where idCriterioAvaliacao = %d", $idCriterioAvaliacao);
        $result = mysql_query($query, $con);
        if( $linha = mysql_fetch_array($result)) {
            return new CriterioAvaliacao( $linha["idCriterioAvaliacao"], 
                    $linha["rotulo"]);
        }
        return null;
    }

    public function getItensCriterioAvaliacao() {
        $con = BD::conectar();
        $query = sprintf("select * from ItemCriterioAvaliacao 
            where idCriterioAvaliacao = %d 
            order by ordem", $this->idCriterioAvalicao);
        $result = mysql_query($query, $con);
        $col = array();
        while( $linha = mysql_fetch_array($result) ) {
            $col[] = new ItemCriterioAvaliacao($linha["idItemCriterioAvaliacao"],
                    $linha["idCriterioAvaliacao"],
                    $linha["rotulo"],
                    $linha["descricao"],
                    $linha["ordem"],
                    $linha["tipo"],
                    $linha["formulaCalculo"]);
        }
        return $col;
    }
    
	public static function obterIdCriteriosAvaliacao() {
        $con = BD::conectar();
        $query = sprintf("SELECT idCriterioAvaliacao from CriterioAvaliacao");
        $result = mysql_query($query, $con);
        $idCriteriosAvaliacao = array();
        while ($linha = mysql_fetch_array($result)){
            $idCriteriosAvaliacao[] = $linha["idCriterioAvaliacao"];
        }
        
        return $idCriteriosAvaliacao;
    }
    
    public static function obterIdCriteriosAvaliacaoCursando($matriculaAluno){
        $con = BD::conectar();
        $query = sprintf("SELECT DISTINCT idCriterioAvaliacao FROM inscricao i
                            INNER JOIN Turma t ON t.idTurma = i.idTurma 
                            WHERE i.matriculaAluno = '%s' and i.situacaoInscricao = 'CUR'",
                mysql_escape_string($matriculaAluno));
        $result = mysql_query($query, $con);
        $criteriosAvaliacaoCursando = array();
        while ($linha = mysql_fetch_array($result)){
            $criteriosAvaliacaoCursando[] = $linha["idCriterioAvaliacao"];
        }
        
        return $criteriosAvaliacaoCursando;
    }
}
?>
