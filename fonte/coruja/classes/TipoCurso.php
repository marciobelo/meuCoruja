<?php
require_once "$BASE_DIR/classes/Util.php";
/**
 *
 * @author vinicius
 */
class TipoCurso {
    public function getIdTipoCurso() {
        return $this->idTipoCurso;
    }

    public function setIdTipoCurso($idTipoCurso) {
        $this->idTipoCurso = $idTipoCurso;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }


    public function __construct($idTipoCurso, $descricao) {
        $this->idTipoCurso = $idTipoCurso;
        $this->descricao = $descricao;
    }

    /**
     * Altera um TipoCurso
     * @param <type> $idTipoCurso
     * @param <type> $descricao
     * @param <type> $con
     */
    public static function alterarTipoCurso($idTipoCurso, $descricao,  $con) {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update TipoCurso set descricao='%s'
            where idTipoCurso=%d",
            mysql_real_escape_string($descricao),
            $idTipoCurso);
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao alterar o Tipo de Curso");
        }
    }

    public static function excluirTipoCurso(TipoCurso $TipoCurso, $con) {
        if ($con == null) $con = BD::conectar();

        $query2 = sprintf("delete from TipoCurso where idTipoCurso=%d",
                        $TipoCurso->getIdTipoCurso());
        $result2 = mysql_query($query2, $con);
        if (!$result2) {
            throw new Exception("Erro ao excluir o Tipo de Curso");
        }
    }

    public static function incluirTipoCurso($descricao, $con) {
        if($con==null) $con=BD::conectar();
        $queryMaxId="select max(idTipoCurso) from TipoCurso";
        $resultMaxId=mysql_query($queryMaxId,$con);
        if(!$resultMaxId) {
          throw new Exception("Erro ao obter id do Tipo de Curso");
        }
        $idTipoCursoNovo=mysql_result($resultMaxId, 0, 0)+1;

        $query=sprintf("insert into TipoCurso (idTipoCurso, descricao)
            values(%d,'%s')",
            $idTipoCursoNovo,
            mysql_real_escape_string($descricao));
        $result=mysql_query($query,$con);
        if(!$result) {
          throw new Exception("Erro ao incluir o Tipo de Curso");
        }
    }

    /***
     * Retorna a lista de tipo de curso
     * @result coleção de objetos: TipoCurso
     **/
    public static function obterTipoCursos() {

         $con=BD::conectar();
         $query= "SELECT * FROM TipoCurso order by descricao";
         $result=mysql_query($query,$con);
         $col=array();
         while($linha = mysql_fetch_array($result)) {
            $TipoCurso = new TipoCurso();
            $TipoCurso->setIdTipoCurso($linha['idTipoCurso']);
            $TipoCurso->setDescricao($linha['descricao']);
            array_push($col,$TipoCurso);
         }
         return $col;
    }

    /**
     * Obtem um objeto de TipoCurso dado seu id
     * @param TipoCurso $TipoCurso
     * @return objeto TipoCurso,se encontrar, ou nulo.
     */
    public static function obterTipoCursoPorId($idTipoCurso) 
    {
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM TipoCurso where idTipoCurso=%d",
            $idTipoCurso);
        $result = mysql_query($query, $con);
        while ($linha = mysql_fetch_array($result)) {
            $TipoCurso = new TipoCurso( $linha['idTipoCurso'], $linha['descricao']);
            return $TipoCurso;
        }
        return null;
    }
}