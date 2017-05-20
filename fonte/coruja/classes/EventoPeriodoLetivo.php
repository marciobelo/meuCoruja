<?php
class EventoPeriodoLetivo { 
    
    private $seqEvento;
    private $idPeriodoLetivo;
    private $data;
    private $tipoEvento;
    private $descricao;

    public function getSeqEvento( ) {
        // retorna o valor de: seqEvento
        return $this->seqEvento;
    }
    
    public function getIdPeriodoLetivo( ) {
        // retorna o valor de: idPeriodoLetivo
        return $this->idPeriodoLetivo;
    }
    
    public function getData( ) {
        // retorna o valor de: data
        return $this->data;
    }

    public function getTipoEvento( ) {
        // retorna o valor de: tipoEvento
        return $this->tipoEvento;
    }

    public function getDescricao( ) {
        // retorna o valor de: descricao
        return $this->descricao;
    }

    function setSeqEvento( $seqEvento ) {
        // seta o valor de: seqEvento
        $this->seqEvento = $seqEvento;
    }

    function setIdPeriodoLetivo( $idPeriodoLetivo ) {
        // seta o valor de: idPeriodoLetivo
        $this->idPeriodoLetivo = $idPeriodoLetivo;
    }

    function setData( $data ) {
        // seta o valor de: data
        $this->data = $data;
    }

    function setTipoEvento( $tipoEvento ) {
        // seta o valor de: tipoEvento
        $this->tipoEvento = $tipoEvento;
    }

    function setDescricao( $descricao ) {
        // seta o valor de: descricao
        $this->descricao = $descricao;
    }

     /***
     * Verifica se ainda está no período de siro
     *
     * @param $idPeriodoLetivo
     * @result true: Ainda estamos no periodo
     **/
    public static function verificaEncerramentoInscricoes( $idPeriodoLetivo ) {
        $con = BD::conectar();
        $query = sprintf("select * from EventoPeriodoLetivo " .
                        "where idPeriodoLetivo = %d " .
                        "and DATE_ADD(data, INTERVAL 1 DAY) >= now() ".
                        "and tipoEvento = 'FIM_SOLIC_INSCR_TURMA'", $idPeriodoLetivo);
        $sqlPeriodo = mysql_query($query, $con);

        $qtdResultado = mysql_num_rows($sqlPeriodo);
        if(empty ($qtdResultado)) {
            return false;
        } else {
            return true;
        }
    }
}
?>