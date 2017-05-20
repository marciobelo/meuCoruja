<?php
require_once "$BASE_DIR/classes/TempoSemanal.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";

/**
 * Representa um espaço físico que pode ser ocupado por
 * aulas ou outras atividades acadêmicas.
 */
class Espaco { 

    private $idEspaco;
    private $nome;
    private $capacidade;

    public function __construct($idEspaco, $nome, $capacidade) {
        $this->idEspaco = $idEspaco;
        $this->nome = $nome;
        $this->capacidade = $capacidade;
    }

    /**
     * Altera um espaco
     * @param <type> $idEspaco
     * @param <type> $nome
     * @param <type> $capacidade
     * @param <type> $con
     */
    public static function alterarEspaco($idEspaco, $nome, $capacidade, $con) {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update Espaco set nome='%s',capacidade=%d
            where idEspaco=%d",
            mysql_real_escape_string($nome),
            $capacidade,
            $idEspaco);
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao alterar o Espaço");
        }
    }

    public static function excluirEspaco(Espaco $espaco, $con) {
        if ($con == null) $con = BD::conectar();

// TODO: analisar o manter turmas antes de tornar nulas as alocações do espaço
//        $query1 = sprintf("update Aloca set idEspaco=null
//            where idEspaco=%d",$espaco->getIdEspaco());
//        $result = mysql_query($query1, $con);
//        if (!$result) {
//            throw new Exception("Erro ao Atualizar alocação de espaços");
//        }

        $query2 = sprintf("delete from Espaco where idEspaco=%d",
                        $espaco->getIdEspaco());
        $result2 = mysql_query($query2, $con);
        if (!$result2) {
            throw new Exception("Erro ao excluir o Espaço");
        }
    }

    public static function incluirEspaco($nome, $capacidade, $con) {
        if($con==null) $con=BD::conectar();
        $queryMaxId="select max(idEspaco) from Espaco";
        $resultMaxId=mysql_query($queryMaxId,$con);
        if(!$resultMaxId) {
          throw new Exception("Erro ao obter id espaço");
        }
        $idEspacoNovo=mysql_result($resultMaxId, 0, 0)+1;

        $query=sprintf("insert into Espaco (idEspaco, nome, capacidade)
            values(%d,'%s',%d)",
            $idEspacoNovo,
            mysql_real_escape_string($nome), $capacidade);
        $result=mysql_query($query,$con);
        if(!$result) {
          throw new Exception("Erro ao incluir o Espaço");
        }
    }

    /***
     * Pega o valor do campo: idEspaco
     *
     * @result idEspaco
     **/
    public function getIdEspaco( ) {
        // retorna o valor de: idEspaco
        return $this->idEspaco;
    }

    /***
     * Pega o valor do campo: nome
     *
     * @result nome
     **/
    public function getNome( ) {
        // retorna o valor de: nome
        return $this->nome;
    }

    /***
     * Pega o valor do campo: capacidade
     *
     * @result capacidade
     **/
    public function getCapacidade( ) {
        // retorna o valor de: capacidade
        return $this->capacidade;
    }

    /***
     * Seta valor para: idEspaco
     *
     * @param idEspaco
     * @result void
     **/
    function setIdEspaco( $idEspaco ) {
        // seta o valor de: idEspaco
        $this->idEspaco = $idEspaco;
    }

    /***
     * Seta valor para: nome
     *
     * @param nome
     * @result void
     **/
    function setNome( $nome ) {
        // seta o valor de: nome
        $this->nome = $nome;
    }

    /***
     * Seta valor para: capacidade
     *
     * @param capacidade
     * @result void
     **/
    function setCapacidade( $capacidade ) {
        // seta o valor de: capacidade
        $this->capacidade = $capacidade;
    }

    /**
     * Retorna um valor lógico indicado se o espaço está disponível
     * num determinado tempo semanal de um determinado período letivo
     * @param TempoSemanal $tempoSemanal
     * @param PeriodoLetivo $periodoLetivo
     * @return boolean True, se estiver disponível; false, se estiver ocupado
     */
    public function espacoEstaDisponivel(TempoSemanal $tempoSemanal,
            PeriodoLetivo $periodoLetivo) {
        $con = BD::conectar();
        $query = sprintf("select count(*)
            from Espaco e inner join Aloca a on e.idEspaco=a.idEspaco
                inner join Turma t on t.idTurma=a.idTurma
                inner join TempoSemanal ts on a.idTempoSemanal=ts.idTempoSemanal
            where t.tipoSituacaoTurma<>'CANCELADA' and
                e.idEspaco=%d and
                ts.idTempoSemanal=%d and
                t.idPeriodoLetivo=%d",
                $this->getIdEspaco(),
                $tempoSemanal->getIdTempoSemanal(),
                $periodoLetivo->getIdPeriodoLetivo());
        $result = mysql_query($query);
        if(mysql_result($result, 0,0)==1) return false;
        else return true;
    }

    /***
     * Retorna a lista de espaços
     * @author: Marcelo Atie
     * @result coleção de objetos: Espaco
     **/
    public static function obterEspacos() {

         $con=BD::conectar();
         $query= "SELECT * FROM Espaco order by nome";
         $result=mysql_query($query,$con);
         $col=array();
         while($linha = mysql_fetch_array($result)) {
            $espaco = new Espaco();
            $espaco->setIdEspaco($linha['idEspaco']);
            $espaco->setNome($linha['nome']);
            $espaco->setCapacidade($linha['capacidade']);
            array_push($col,$espaco);
         }
         return $col;
    }

    /**
     * Obtem um objeto de Espaco dado seu id
     * @param Espaco $espaco
     * @return objeto Espaco,se encontrar, ou nulo.
     */
    public static function obterEspacoPorId($idEspaco) {
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM Espaco where idEspaco=%d",
            $idEspaco);
        $result = mysql_query($query, $con);
        while ($linha = mysql_fetch_array($result)) {
            $espaco = new Espaco();
            $espaco->setIdEspaco($linha['idEspaco']);
            $espaco->setNome($linha['nome']);
            $espaco->setCapacidade($linha['capacidade']);
            return $espaco;
        }
        return null;
    }

    /**
     * Verifica se a nova capacidade proposta para o espaço comporta
     * as turmas planejadas e liberadas.
     * @param integer $novaCapacidade
     * @return boolean
     */
    public function novaCapacidadeAdequada($novaCapacidade) {
      $con = BD::conectar();
      $query = sprintf("SELECT * FROM Aloca a inner join Espaco e
on a.idEspaco = e.idEspaco inner join Turma t on a.idTurma=t.idTurma
and t.tipoSituacaoTurma in ('PLANEJADA','LIBERADA')
where t.qtdeTotal > %d and e.idEspaco=%d"
              ,$novaCapacidade, $this->idEspaco);
      $result = mysql_query($query, $con);
      $linha = mysql_num_rows($result);

       if($linha>0) {
          return false;
       } else {
          return true;
       }
    }

}
?>