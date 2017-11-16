<?php
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/ItemCriterioAvaliacao.php";

class ItemCriterioAvaliacaoInscricaoNota {
    
    private $inscricao;
    private $itemCriterioAvaliacao;
    private $nota;
    private $comentario;
    private $dataNotificacao;
    
    function __construct(Inscricao $inscricao, ItemCriterioAvaliacao $itemCriterioAvaliacao, 
            $nota, $comentario, $dataNotificacao) {
        $this->inscricao = $inscricao;
        $this->itemCriterioAvaliacao = $itemCriterioAvaliacao;
        $this->nota = $nota;
        $this->comentario = $comentario;
        $this->dataNotificacao = $dataNotificacao;
    }
    
    public function getItemCriterioAvaliacao() {
        return $this->itemCriterioAvaliacao;
    }


    public static function obterItensCriterioAvaliacaoInscricaoNota( Inscricao $inscricao ) {
        $con = BD::conectar();
        $query = sprintf("select ICA.idItemCriterioAvaliacao,
            ICAIN.nota, ICAIN.comentario, ICAIN.dataNotificacao
            from ItemCriterioAvaliacao ICA 
            left outer join ItemCriterioAvaliacaoInscricaoNota ICAIN 
                on ICA.idItemCriterioAvaliacao = ICAIN.idItemCriterioAvaliacao
                    and ICAIN.idTurma = %d 
                    and ICAIN.matriculaAluno = '%s' 
            where ICA.idCriterioAvaliacao = %d 
            order by ICA.ordem", 
                $inscricao->getIdTurma(), 
                $inscricao->getMatriculaAluno(),
                $inscricao->getTurma()->getCriterioAvaliacao()->getIdCriterioAvalicao()
                );
        $result = mysql_query($query, $con);
        $col = array();
        while( $registro = mysql_fetch_array($result) ) {
            $idItemCriterioAvaliacao = $registro["idItemCriterioAvaliacao"];
            $col[] = new ItemCriterioAvaliacaoInscricaoNota(
                    $inscricao, 
                    ItemCriterioAvaliacao::obterPorId( $idItemCriterioAvaliacao ),
                    $registro["nota"],
                    $registro["comentario"],
                    $registro["dataNotificacao"] );
        }
        return $col;
    }
    
    /** 
     * Nota lançada ou calculada, ou nulo, se não lançada ou não puder ser computada
     * @return float
     */
    public function getNota() {
        return $this->nota;
    }

    public function getComentario() {
        return $this->comentario;
    }
    
    public function getDataNotificacao() {
        return $this->dataNotificacao;
    }

    public function registrarNotificacao($con = null) {
        if( $con == null ) $con = BD::conectar ();
        $cmd = sprintf("update ItemCriterioAvaliacaoInscricaoNota 
            set dataNotificacao = NOW() 
            where idItemCriterioAvaliacao = %d and
            idTurma = %d and 
            matriculaAluno = '%s'",
                $this->itemCriterioAvaliacao->getIdItemCriterioAvaliacao(),
                $this->inscricao->getIdTurma(),
                $this->inscricao->getMatriculaAluno());
        $result = mysql_query($cmd, $con);
        if( !$result ) {
            throw new Exception("Não foi possível atualizar data de notificação para ItemCriterioAvaliacaoInscricaoNota");
        }
    }

}
?>
