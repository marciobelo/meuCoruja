<?php
require_once "$BASE_DIR/classes/TempoSemanal.php";
require_once "$BASE_DIR/classes/Espaco.php";

require_once "$BASE_DIR/classes/BD.php";

class Aloca {

    private $idTurma;
    private $idTempoSemanal;
    private $horaInicio;
    private $horaFim;
    private $diaSemana;
    private $idEspaco;
    private $nome;
    
    public function __construct($idTurma, $idTempoSemanal, $idEspaco) {
        $this->idTurma = $idTurma;
        $this->idTempoSemanal = $idTempoSemanal;
        $this->idEspaco = $idEspaco;
    }

    public function getIdTurma( ) {
        // retorna o valor de: idTurma
        return $this->idTurma;
    }

    public function getIdTempoSemanal( ) {
        // retorna o valor de: idTempoSemanal
        return $this->idTempoSemanal;
    }

    public function getHoraInicio( ) {
        // retorna o valor de: horaInicio
        return $this->horaInicio;
    }

    public function getHoraFim( ) {
        // retorna o valor de: horaFim
        return $this->horaFim;
    }

    public function getDiaSemana( ) {
        // retorna o valor de: diaSemana
        return $this->diaSemana;
    }

    public function getIdEspaco( ) {
        // retorna o valor de: idEspaco
        return $this->idEspaco;
    }

    public function getNome( ) {
        // retorna o valor de: idEnomespaco
        return $this->nome;
    }

    public function setIdTurma( $idTurma ) {
        // seta o valor de: idTurma
        $this->idTurma = $idTurma;
    }

    public function setIdTempoSemanal( $idTempoSemanal ) {
        // seta o valor de: idTempoSemanal
        $this->idTempoSemanal = $idTempoSemanal;
    }

    public function setHoraInicio( $horaInicio ) {
        // seta o valor de: horaInicio
        $this->horaInicio = $horaInicio;
    }

    public function setHoraFim( $horaFim ) {
        // seta o valor de: horaFim
        $this->horaFim = $horaFim;
    }

    public function setDiaSemana( $diaSemana ) {
        $this->diaSemana = $diaSemana;
    }
    
    public function setIdEspaco( $idEspaco ) {
        // seta o valor de: idEspaco
        $this->idEspaco = $idEspaco;
    }

    public function setNome( $nome ) {
        // seta o valor de: idEspaco
        $this->nome = $nome;
    }
    
    /***
     * nome, diaSemana, horaInicio e horaFim não pertencem a classe Aloca
     * 
     * Retorna uma entrada existente: aloca
     * Casos de Uso: UC02.06.00
     * @param idTurma
     * @result new Aloca
     **/
    public function pega_aloca( $idTurma ) {

        // retorna o dado
        $con = BD::conectar();

        // retorna o valor no DB
        $result = mysql_query( sprintf("SELECT * FROM Aloca as a ".
                                                "INNER JOIN TempoSemanal as t ".
                                                "ON a.idTempoSemanal = t.idTempoSemanal ".
                                                "INNER JOIN Espaco as e ".
                                                "ON a.idEspaco = e.idEspaco ".
                                                "WHERE a.idTurma = '%s'", mysql_real_escape_string($idTurma)), $con);

        // cria novo objeto
        $_resultSet = mysql_fetch_array( $result);
        $__obj = new Aloca();
        $__obj->setIdTurma($_resultSet['idTurma']);
        $__obj->setIdTempoSemanal($_resultSet['idTempoSemanal']);
        $__obj->setHoraInicio($_resultSet['horaInicio']);
        $__obj->setHoraFim($_resultSet['horaFim']);
        $__obj->setDiaSemana($_resultSet['diaSemana']);
        $__obj->setIdEspaco($_resultSet['idEspaco']);
        $__obj->setNome($_resultSet['nome']);

        return $__obj;
    }

    /***
     * @INCONSISTENTE
     * nome, diaSemana, horaInicio e horaFim não pertencem a classe Aloca
     * 
     * Retorna a lista de objetos baseado em parametros: aloca
     * Casos de Uso: UC02.06.00, UC02.07.00
     * @param conditionalStatement = ''
     * @result coleção de objetos: Aloca
     **/
    public function lista_aloca( $conditionalStatement = '' ) {

         $con = BD::conectar();
         // checa se foram passados parametros
         if(!empty($conditionalStatement)) { 
              $sqlStatement = sprintf("SELECT * FROM Aloca as a ".
                                "INNER JOIN TempoSemanal as t ".
                                "ON a.idTempoSemanal = t.idTempoSemanal ".
                                "INNER JOIN Espaco as e ".
                                "ON a.idEspaco = e.idEspaco ".
                                "%s", mysql_real_escape_string($conditionalStatement));
                                
         } else { 
              $sqlStatement = "SELECT * FROM Aloca as a ".
                                "INNER JOIN Espaco as e ".
                                "ON a.idEspaco = e.idEspaco ".
                                "INNER JOIN TempoSemanal as t ".
                                "ON a.idTempoSemanal = t.idTempoSemanal ";
         }

         // recupera os valores com base no resultado
         $result = mysql_query($sqlStatement, $con);

        $__collectionOfObjects = array();
        while( $__rs = mysql_fetch_array($result))
        {
            $__newObj = new Aloca();

            $__newObj->setIdTurma($__rs['idTurma']);
            $__newObj->setIdTempoSemanal($__rs['idTempoSemanal']);
            $__newObj->setHoraInicio($__rs['horaInicio']);
            $__newObj->setHoraFim($__rs['horaFim']);
            $__newObj->setDiaSemana($__rs['diaSemana']);
            $__newObj->setIdEspaco($__rs['idEspaco']);
            $__newObj->setNome($__rs['nome']);

            // adiciona objetos à coleção 
            array_push($__collectionOfObjects, $__newObj);
        }

         // retorna a coleção de objetos
         return $__collectionOfObjects;
    }

    /*Retorna um array de Tempos Semanais
     * Casos de Uso:UC02.06.00
     * @param: $idTurma
     * @return: $TemposSemanais
     */
    public function obterTemposByTurma($idTurma){
                // retorna o dado
        $con = BD::conectar();
        $collection=array();

        // OBTEM O RESULTADO DO BANCO DE DADOS
        $query=sprintf("SELECT idTempoSemanal FROM Aloca ".
                       "WHERE idTurma = '%s'", mysql_real_escape_string($idTurma));
        
        $result=mysql_query($query,$con);

        while( $resTempos = mysql_fetch_array($result) ) {
         $tempoSemanal = TempoSemanal::getTempoSemanalById($resTempos['idTempoSemanal']);
         $collection[] = $tempoSemanal;
        }

        return $collection;
    }
    
    /* Retorna uma lista com todas as alocações de uma determinada turma
     * @param: $idTurma
     * @result: Array<Aloca>
     */
    public static function getListAlocaByIdTurma($idTurma) {
        $con = BD::conectar();
        $query = sprintf(
                "SELECT * FROM Aloca ".
                "WHERE idTurma = %s",
                mysql_real_escape_string($idTurma));
        
        $result = mysql_query($query,$con);
        $collection = array();
        while( $resAloca = mysql_fetch_array($result) ) {
            $umaAlocao= new Aloca($resAloca['idTurma'], $resAloca['idTempoSemanal'], $resAloca['idEspaco']);
            $collection[] = $umaAlocao;
        }
        return $collection;
    }
    
    public function getEspaco() {
        return Espaco::obterEspacoPorId($this->getIdEspaco());
    }
    
    public function getTempoSemanal() {
        return TempoSemanal::getTempoSemanalById($this->getIdTempoSemanal());
    }
} ?>