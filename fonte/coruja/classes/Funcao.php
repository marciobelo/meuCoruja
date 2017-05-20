<?php
class Funcao 
{
    private $idCasoUso;
    private $descricao;
    private $critico;
    
    function __construct($idCasoUso, $descricao, $critico) {
        $this->idCasoUso = $idCasoUso;
        $this->descricao = $descricao;
        $this->critico = $critico;
    }
    
    function getIdCasoUso() {
        return $this->idCasoUso;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getCritico() {
        return $this->critico;
    }
    
    public function atribuirPermissao(Login $login) {
        $con   = BD::conectar();
        $query = sprintf("INSERT INTO Permite (idPessoa,idCasoUso) ".
                         "VALUES (%d,'%s')", $login->getPessoa()->getIdPessoa(), $this->idCasoUso);
        
        $result = mysql_query($query, $con);
        if (!$result) {
            throw new Exception("Erro ao atribuir nova permissão.");
        }
    }
    
    public function removerPermissao(Login $login) {
        $con   = BD::conectar();
        $query = sprintf("DELETE FROM Permite WHERE idPessoa = %d ".
                         "AND idCasoUso = '%s'", $login->getPessoa()->getIdPessoa(), $this->idCasoUso);

        $result = mysql_query($query, $con);
        if (!$result) {
            throw new Exception("Erro ao remover permissão.");
        }
    }

    public static function obterPorId($idCasoUso) 
    {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM Funcao WHERE idCasoUso = '%s'", mysql_real_escape_string($idCasoUso));
        $result = mysql_query($query, $con);
        $linha  = mysql_fetch_array($result);
        
        if($linha) {
            return new Funcao($linha['idCasoUso'], $linha['descricao'], $linha['critico']);
        } else {
            return null;
        }
    }
    
    public static function obterTodasFuncoes() {
        $con   = BD::conectar();
        $query = "SELECT idCasoUso, descricao, critico
                  FROM Funcao";
        
        $result = mysql_query($query, $con);
        while ($row = mysql_fetch_assoc($result)) {
            $todosCasos[] = new Funcao( $row["idCasoUso"], $row["descricao"], $row["critico"]);
        }
        return $todosCasos;
    }
}
