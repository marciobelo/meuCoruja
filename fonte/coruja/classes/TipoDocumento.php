<?php

require_once "$BASE_DIR/classes/BD.php";

/**
 * TipoDocumento para matrícula em cursos
 * @author mbelo
 */
class TipoDocumento {
    private $idTipoDocumento;
    private $descricao;

    /**
     * Obtem uma instância de TipoDocumento dado o seu id.
     * @param int $id
     * @return TipoDocumento 
     */
    public static function obterTipoDocumentoPorId($id) {
        $con = BD::conectar();
        $query=sprintf("select idTipoDocumento,descricao
            from TipoDocumento where idTipoDocumento=%d",  mysql_escape_string($id));
        $result = mysql_query($query,$con);
        if(!result) trigger_error("Erro ao consultar TipoDocumento.",E_USER_ERROR);
        $linha = mysql_fetch_array($result);
        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->setIdTipoDocumento($linha["idTipoDocumento"]);
        $tipoDocumento->setDescricao($linha["descricao"]);
        return $tipoDocumento;
    }
    
    function __construct($idTipoDocumento, $descricao) {
        $this->idTipoDocumento = $idTipoDocumento;
        $this->descricao = $descricao;
    }

        public function getIdTipoDocumento() {
        return $this->idTipoDocumento;
    }

    public function setIdTipoDocumento($idTipoDocumento) {
        $this->idTipoDocumento = $idTipoDocumento;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    /**
     * @author Marcelo Atie
     *
     * Retorna uma lista com os documentos ainda não entregues por determinada matrícula
     * 
     * @param type $matriculaAluno
     * @param type $siglaCurso
     * @return array
     */
    public static function obterTipoDocumentosNaoEntregues($matriculaAluno, $siglaCurso){
        $con = BD::conectar();
        $query = sprintf("
                SELECT
                    ctd.`idTipoDocumento`, td.descricao
                FROM
                    CursoTipoDocumento ctd
                INNER JOIN
                    TipoDocumento td
                ON
                    ctd.`idTipoDocumento` = td.`idTipoDocumento`
                LEFT JOIN
                    ExigenciaDocumento ed
                ON
                    ctd.`idTipoDocumento` = ed.`idTipoDocumento`
                    AND `matriculaAluno` = '%s'
                WHERE
                    isnull(ed.`matriculaAluno`)
                    AND ctd.`siglaCurso` = '%s'
                ORDER BY
                    ctd.`siglaCurso`, ctd.`idTipoDocumento`
                ", mysql_escape_string($matriculaAluno), mysql_escape_string($siglaCurso));
        $result = mysql_query($query, $con);
        if (!result)
            trigger_error("Erro ao consultar CursoTipoDocumento e ExigenciaDocumento.", E_USER_ERROR);
        $col = array();
        while ($linha = mysql_fetch_array($result)) {
            $tpDoc = new TipoDocumento($linha["idTipoDocumento"], $linha["descricao"]);
            array_push($col, $tpDoc);
        }
        return $col;
    }

}
?>
