<?php
class FormaIngresso 
{
    private $idFormaIngresso;
    private $descricao;

    public function getIdFormaIngresso( ) {
        // retorna o valor de: idFormaIngresso
        return $this->idFormaIngresso;
    }

    public function getDescricao( ) {
        // retorna o valor de: descricao
        return $this->descricao;
    }


    function setIdFormaIngresso( $idFormaIngresso ) {
        // seta o valor de: idFormaIngresso
        $this->idFormaIngresso = $idFormaIngresso;
    }

    function setDescricao( $descricao ) {
        // seta o valor de: descricao
        $this->descricao = $descricao;
    }

    /***
     * Retorna a lista de objetos baseado em parametros: formaingresso
     *
     * @param conditionalStatement = ''
     * @result coleção de objetos: FormaIngresso
     **/

    /* Obtem um objeto formaIngresso pelo seu Id
     * Casos de uso: UC01.09.00 - Emitir Ficha de Matrícula
     * @author: Marcelo Atie
     * @result: objeto formaIngresso
     */
    public static function getFormaIngressoById( $idFormaIngresso ) {
        $con = BD::conectar();
        $query=sprintf("SELECT * ".
                "FROM `FormaIngresso` ".
                "WHERE `idFormaIngresso` = %d ",
                mysql_real_escape_string($idFormaIngresso));
        $result=mysql_query($query,$con);
        $resFormaIngresso = mysql_fetch_array($result);
        $__obj = new formaingresso();
        $__obj->setIdFormaIngresso($resFormaIngresso['idFormaIngresso']);
        $__obj->setDescricao($resFormaIngresso['descricao']);
        return $__obj;
    }

    /**
    * Obtem as formas de ingresso cadastradas
    * @return array de FormaIngresso
    */
    public static function obterFormasIngresso() 
    {
        $con = BD::conectar();
        $query = "SELECT * FROM FormaIngresso";

        // recupera os valores com base no resultado
        $result = mysql_query($query, $con);

        $objetos = array();
        while( $rs = mysql_fetch_array($result))
        {
           $__newObj = new FormaIngresso();
           $__newObj->setIdFormaIngresso($rs['idFormaIngresso']);
           $__newObj->setDescricao($rs['descricao']);

           array_push($objetos, $__newObj);
        }
        return $objetos;
    }
}