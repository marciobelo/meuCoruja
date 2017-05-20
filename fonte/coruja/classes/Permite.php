<?php

class Permite 
{
    private $login;
    private $funcao;
    
    function __construct($login, $funcao) {
        $this->login  = $login;
        $this->funcao = $funcao;
    }
    
    function getLogin() {
        return $this->login;
    }

    function getFuncao() {
        return $this->funcao;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setFuncao($funcao) {
        $this->funcao = $funcao;
    }
    
    public static function obterPermissoesPorIdPessoa($idPessoa) {
        $con = BD::conectar();
        $permissoes = array();
        
        $query = sprintf("select * from Login L left outer join Permite P "
                        . "on L.idPessoa = P.idPessoa "
                        . "where P.idPessoa = %d", $idPessoa);
        
        $result = mysql_query($query, $con);
        
        while($row = mysql_fetch_assoc($result)) {    
            $permissoes[] = new Permite( $login, Funcao::obterPorId( $row["idCasoUso"]) );
        }
        
        return $permissoes;
    }
}
