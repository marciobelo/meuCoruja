<?php
include_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Util.php";

class PeriodoLetivo 
{
    private $idPeriodoLetivo;       
    private $siglaPeriodoLetivo;
    private $dataInicio;
    private $dataFim;
    private $siglaCurso;
    private $rodouBloqueioAutomatico;
    
    public function __construct( $idPeriodoLetivo, 
            $siglaPeriodoLetivo,
            $dataInicio,
            $dataFim,
            $siglaCurso,
            $rodouBloqueioAutomatico)
    {
        $this->setIdPeriodoLetivo( $idPeriodoLetivo);
        $this->setSiglaPeriodoLetivo($siglaPeriodoLetivo);
        $this->setDataInicio( $dataInicio);
        $this->setDataFim( $dataFim);
        $this->setSiglaCurso( $siglaCurso);
        $this->setRodouBloqueioAutomatico($rodouBloqueioAutomatico);
    }

    public function setIdPeriodoLetivo($idPeriodoLetivo) {
        $this->idPeriodoLetivo = $idPeriodoLetivo;
    }
    public function getIdPeriodoLetivo() {
        return $this->idPeriodoLetivo;
    }
    
    public function setSiglaPeriodoLetivo($siglaPeriodoLetivo){
    	$this->siglaPeriodoLetivo = $siglaPeriodoLetivo;
    }    
    public function getSiglaPeriodoLetivo() {
        return $this->siglaPeriodoLetivo;
    }
    
    public function setDataInicio($dataInicio){
    	$this->dataInicio = $dataInicio;
    }    
    public function getDataInicio() {
        return $this->dataInicio;
    }
    
    public function setDataFim($dataFim){
    	$this->dataFim = $dataFim;
    }    
    public function getDataFim() {
        return $this->dataFim;
    }
    
    public function setSiglaCurso($siglaCurso){
    	$this->siglaCurso = $siglaCurso;
    }    
    public function getSiglaCurso() {
        return $this->siglaCurso;
    }
    
    function obterDataInicioAulas() {
        return $this->getDataInicio();
    }
    
    function obterDataFimAulas() {
        return $this->getDataFim();
    }

    public function getRodouBloqueioAutomatico() {
        return $this->rodouBloqueioAutomatico;
    }

    public function setRodouBloqueioAutomatico($rodouBloqueioAutomatico) {
        $this->rodouBloqueioAutomatico = ($rodouBloqueioAutomatico === "SIM" ? true : false);
    }
        
    /**
     * Retorna a data do fim do per�odo de trancamento
     * de matr�cula (FIM_TRANC_MATRICULA) do per�odo letivo.
     * Se n�o houver, retorna null;
     * @return Data do fim da trancamento de matr., ou null, se n�o existir
     */
    public function obterDataFimTrancMatricula() {
        $con=BD::conectar();
        $query=sprintf("select data from EventoPeriodoLetivo
            where idPeriodoLetivo=%d and
            tipoEvento='FIM_TRANC_MATRICULA'",
                $this->getIdPeriodoLetivo());
        $result=mysql_query($query,$con);
        if(mysql_num_rows($result)==0) {
            return null;
        } else {
            $linha = mysql_fetch_array($result);
            return $linha["data"];
        }
    }

    /**
     * Retorna a data do fim do per�odo de inscri��o
     * em turmas (FIM_SOLIC_INSCR_TURMA) do per�odo letivo.
     * Se n�o houver, retorna null;
     * @return Data do fim da solic.inscri��o, ou null, se n�o existir
     */
    public function obterDataFimSolicInscrTurma() {
        $con=BD::conectar();
        $query=sprintf("select data from EventoPeriodoLetivo
            where idPeriodoLetivo=%d and
            tipoEvento='FIM_SOLIC_INSCR_TURMA'",
                $this->getIdPeriodoLetivo());
        $result=mysql_query($query,$con);
        if(mysql_num_rows($result)==0) {
            return null;
        } else {
            $linha = mysql_fetch_array($result);
            return $linha["data"];
        }
    }

    /**
    * Inserir dados na tabela periodoletivo
    *         
    */
    function inserePeriodo() {
            $this->setIdPeriodoLetivo($this->getProximoId());
                        
            $queryPeriodo =  "INSERT INTO PeriodoLetivo   
                (
                idPeriodoLetivo,
                siglaPeriodoLetivo,
                dataInicio,
                dataFim,
                siglaCurso
                )   
                VALUES  
                (
                '".$this->getIdPeriodoLetivo()."',
                '".$this->getSiglaPeriodoLetivo()."',
                '".Util::dataBrParaSQL($this->getDataInicio())."',
                '".Util::dataBrParaSQL($this->getDatafim())."',
                '".$this->getSiglaCurso()."'
                )";
            
            $msg_erro = "Erro ao Inserir Informa��es!";
            $msg_erro_duplicado ="Sigla do periodo letivo j� existente para este curso!" ;

            $classeCurso = Curso::obterCurso($this->getSiglaCurso());
            
            $descricao="Inserido o per�odo letivo ".$this->getSiglaPeriodoLetivo()." (".
                        $this->getDataInicio()." - ".
                        $this->getDatafim().") ".
                        "do curso ".$this->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";
            $con = BD::conectar();

            mysql_query($queryPeriodo,$con);
            
            if(mysql_errno()==1062){// numero do erro no mysql que identifica a entrada de chave duplicada
            	return $msg_erro_duplicado;
            }elseif(mysql_errno()!=0){
            	return $msg_erro;
            }else{

                $login = $_SESSION["login"];
                global $MANTER_PERIODO_LETIVO_INCLUIR;

                $login->incluirLog($MANTER_PERIODO_LETIVO_INCLUIR,$descricao,$con);

                return "";    
            }
    }
            
    /**
    * Atualizar dados na tabela de periodoletivo
    *         
    */
    public function atualizaPeriodo() {

            $queryPeriodo =  "UPDATE PeriodoLetivo SET  
                siglaPeriodoLetivo = '".$this->getSiglaPeriodoLetivo()."',
                dataInicio = '".$this->getDataInicio()."',
                dataFim = '".$this->getDataFim()."'
                WHERE idPeriodoLetivo = '".$this->getIdPeriodoLetivo()."'";
            
            $msg_erro = "Erro ao Atualizar Informa��es!";
            $msg_erro_duplicado ="Sigla do periodo letivo j� existente para este curso!" ;
            
             //monta a descricao para o log de autoria
                    
            $periodoLetivoAntes = PeriodoLetivo::obterPeriodoLetivo( $this->getIdPeriodoLetivo() );
            $classeCurso = Curso::obterCurso( $periodoLetivoAntes->getSiglaCurso() );

            $descricao="Alterado o per�odo letivo de ".$periodoLetivoAntes->getSiglaPeriodoLetivo()." (".
                        $periodoLetivoAntes->getDataInicio()." - ".
                        $periodoLetivoAntes->getDatafim().") ".
                        "para ".$this->getSiglaPeriodoLetivo()." (".
                        Util::dataSQLParaBr($this->getDataInicio())." - ".
                        Util::dataSQLParaBr($this->getDatafim()).") ".
                        "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";
            $con = BD::conectar();

            mysql_query($queryPeriodo,$con);
            
            if(mysql_errno()==1062){// numero do erro no mysql que identifica a entrada de chave duplicada
            	return $msg_erro_duplicado;
            }elseif(mysql_errno()!=0){
                return $msg_erro;
            }else {
                $login = $_SESSION["login"];
                global $ALTERAR_PERIODO_LETIVO;

                $login->incluirLog($ALTERAR_PERIODO_LETIVO,$descricao,$con);
                return "";
            }
    }
    
    /**
    * Exclui o periodo letivo 
    */
    public function excluir()  {
        $queryPeriodo = "DELETE FROM PeriodoLetivo where idPeriodoLetivo = $this->idPeriodoLetivo";
        //monta a descricao para o log de autoria
        $curso = Curso::obterCurso($this->getSiglaCurso());
        $descricao = "Excluido o per�odo letivo " . $this->getSiglaPeriodoLetivo() ." (" .
                    $this->getDataInicio() . " - " .
                    $this->getDatafim() . ") " .
                    "do curso " . $this->getSiglaCurso() . " (" . $curso->getNomeCurso() . "). ";

        $con = BD::conectar();

        $sqlPeriodo = mysql_query($queryPeriodo,$con);
        $msgErro = "Erro ao excluir Per�odo Letivo!";
        if(!$sqlPeriodo){
            return $msgErro;
        } else {
            $login = $_SESSION["login"];
            global $EXCLUIR_PERIODO_LETIVO;
            $login->incluirLog($EXCLUIR_PERIODO_LETIVO,$descricao,$con);
            return "Per�odo Letivo exclu�do com sucesso!";
        }
    }

    // METODO NECESSARIO PARA VERIFICAR SE A DATA INICIAL � MENOR QUE A �LTIMA CADASTRADA
    /**
    * retorna verdadeiro se � menor ou igual e falso se � maior
    *         
    * @param $dataInicial : data inicial digitada no novo periodo a ser inserido
    */	
    function isDataMenor($dataInicial,$id) {
			$encontrou = false;
			$dataInicial = Util::dataBrParaSQL($dataInicial);
			$con = BD::conectar();
            $dataAnterior = "select CASE
			                           WHEN dataInicio >= '$dataInicial' THEN 'invalido'
			                        END AS data
                             from PeriodoLetivo where idPeriodoLetivo = ".$id;
            $sqlDataAnterior = mysql_query($dataAnterior,$con);
         
            if($sqlDataAnterior){
            	
            	if(mysql_result($sqlDataAnterior,0,'data')=='invalido'){
            	  $encontrou = true;
            	}
            }
            return $encontrou;
    }
		
    public function getIdPeriodoAnterior($id) {
		    $idAnterior = null;
			$con = BD::conectar();
			
            $query = "select idPeriodoLetivo from PeriodoLetivo pl1
                       where pl1.dataInicio =
                        (select max(dataInicio) from
                            PeriodoLetivo pl
                        where pl.dataInicio < (select dataInicio from PeriodoLetivo where idPeriodoLetivo = ".$id."))";
            $sql = mysql_query($query,$con);
            if(mysql_num_rows($sql) > 0){
            	$idAnterior = (mysql_result($sql,0,'idPeriodoLetivo'));
            }
            return $idAnterior;
    }	
        
    /**
    * retorna uma lista de objetos dos 10(dez) ultimos periodos letivos da tabela periodoletivo
    *
    * @param $inicio: inicio da busca na querie
    * @param $fim: fim da busca na querie
    * @param $siglaCurso: qual curso exibir� os registros da querie
    */
    function listaPeriodos($inicio,$fim,$siglaCurso) {
        $lista = null;
        $collection = null;
        $con = BD::conectar();

        // lista os peridos
        $lista = mysql_query("SELECT date_format(dataInicio,'%d/%m/%Y') as dataInicio, date_format(dataFim,'%d/%m/%Y') as dataFim,idPeriodoLetivo,siglaCurso,siglaPeriodoLetivo FROM PeriodoLetivo where siglaCurso = '$siglaCurso'  order by siglaPeriodoLetivo desc limit $inicio,$fim");
        if(mysql_num_rows($lista) > 0) 
        {
            $collection = array();
            while( $objeto = mysql_fetch_array($lista) )
            {
                    $periodo = new PeriodoLetivo( $objeto["idPeriodoLetivo"],
                            $objeto["siglaPeriodoLetivo"], $objeto["dataInicio"],
                            $objeto["dataFim"], $objeto["siglaCurso"], 
                            $objeto["rodouBloqueioAutomatico"]);
                    $collection[] = $periodo;
            }
        }
        return $collection;
    }
	    

    public function getMaxId($siglaCurso) 
    {
        $con = BD::conectar();
        $maxId = null;
        $query = "select ifNULL(max(idPeriodoLetivo),0) as maxId from PeriodoLetivo where siglaCurso = %s".mysql_real_escape_string($siglaCurso);
        $sql = mysql_query($query,$con);
        if(mysql_num_rows($sql) > 0) 
        {
           $maxId = (mysql_result($sql,0,'maxId'));
        }
        return $maxId;
    }   
	 
    // METODO QUE BUSCA O PROXIMO ID DA TABELA DE PERIODOLETIVO
    /**
    * retorna o id seguinte para ser inserido na tabela de periodo letivo
    *         
    */
     public function getProximoId(){
            $con = BD::conectar();
            $queryProximoId = "select ifNULL(max(idPeriodoLetivo),0)+1 as proximoId from PeriodoLetivo";
            $resultado = mysql_query($queryProximoId,$con);
            $proximoId = mysql_result($resultado,0,'proximoId');
            return $proximoId; 
     }

    /* Funcao que obtem o atual periodo letivo
     *
     * Casos de Uso: UC02.06.00; UC02.01
     * @param: sigla do curso;
     * @return: periodo Letivo -> objeto
     * @throws Exception
     */
    public static function obterPeriodoLetivoAtual($siglaCurso) {
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM PeriodoLetivo p where dataFim >= CURDATE() and
            dataInicio <= CURDATE() and siglaCurso='%s'",
                mysql_real_escape_string($siglaCurso));
        $sqlPeriodo = mysql_query($query, $con);
        $qtdResultado = mysql_num_rows($sqlPeriodo);

        if ($qtdResultado == 1) {
            $itensPeriodo = mysql_result($sqlPeriodo, 0, 'idPeriodoLetivo');
            return PeriodoLetivo::obterPeriodoLetivo($itensPeriodo);
        } elseif($qtdResultado > 1) {
            throw new Exception(sprintf("Erro de inconsist�ncia: existem mais de um per�odo letivo para a data atual do curso %s.",$siglaCurso));
        } else {
            throw new Exception(sprintf("N�o existem per�odo letivos dispon�veis na data atual do curso %s.",$siglaCurso));
        }
    }

    /**
     * Retorna o per�odo letivo vigente mais antigo cadastrado para um determinado curso
     * @param String $siglaCurso
     * @return PeriodoLetivo
     */
    public static function obterPeriodoLetivoVigenteMaisAntigo($siglaCurso) {
        $con = BD::conectar();
        $query = sprintf("select * from PeriodoLetivo pl where 
            dataFim >= CURDATE() 
            and siglaCurso='%s' 
            order by dataInicio",
        mysql_escape_string($siglaCurso),
        mysql_escape_string($siglaCurso));
        $result = mysql_query($query, $con);
        $qtdResultado = mysql_num_rows($result);
        if ($qtdResultado >= 1) 
        {
            $linha = mysql_fetch_array($result);
            $pl = new PeriodoLetivo( $linha["idPeriodoLetivo"],
                    $linha["siglaPeriodoLetivo"], 
                    $linha["dataInicio"],
                    $objeto["dataFim"],
                    $objeto["siglaCurso"],
                    $objeto["rodouBloqueioAutomatico"] );
            return $pl;
        } else {
            throw new RuntimeException("Erro ao identificar o per�odo letivo mais antigo vigente do curso.");
        }
    }
     
    /***
     * Retorna uma entrada existente: periodoletivo
     * @param idPeriodoLetivo
     * @result new PeriodoLetivo
     **/
    /**
     * Retorna um inst�ncia de per�odo letivo pelo id
     * @param integer $idPeriodoLetivo
     * @return PeriodoLetivo
     */
    public static function obterPeriodoLetivo( $idPeriodoLetivo) {
        // retorna o dado
        $con = BD::conectar();

        // retorna o valor no DB
        $query = sprintf("SELECT * FROM PeriodoLetivo WHERE idPeriodoLetivo = %d",
            $idPeriodoLetivo );
        $rs = mysql_query($query,$con);
        if( mysql_num_rows($rs) != 1) return null;
        $rsPL = mysql_fetch_array($rs);
        $pl = new PeriodoLetivo( $rsPL["idPeriodoLetivo"], 
                $rsPL["siglaPeriodoLetivo"],
                $rsPL["dataInicio"],
                $rsPL["dataFim"],
                $rsPL["siglaCurso"],
                $rsPL["rodouBloqueioAutomatico"] );
        return $pl;
    }

    public static function obterPeriodosLetivoPorSiglaCurso($siglaCurso) {
        $lista = null;
        $collection = null;
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM PeriodoLetivo WHERE siglaCurso='%s'
            order by siglaPeriodoLetivo DESC",
        mysql_real_escape_string($siglaCurso));
        $lista = mysql_query($query);
        $collection = array();
        if (mysql_num_rows($lista) > 0) 
        {
            while ($objeto = mysql_fetch_array($lista) ) 
            {
                $periodoLetivo = new PeriodoLetivo( $objeto['idPeriodoLetivo'],
                        $objeto["siglaPeriodoLetivo"],
                        $objeto["dataInicio"],
                        $objeto["dataFim"],
                        $objeto["siglaCurso"],
                        $objeto["rodouBloqueioAutomatico"] );
                array_push( $collection, $periodoLetivo);
            }
        }
        return $collection;
    }
    
    /**
     * Indica se est� fora do per�odo letivo
     * @param string $data data no formato AAAA-MM-DD
     * @return boolean true, se estiver fora do per�odo letivo
     */
    public function isDataForaPeriodo( $data ) {
        if( $data < $this->dataInicio || $data > $this->dataFim ) {
            return true;
        } else {
            return false;
        }
    }

    public function registrarRodadaBloqueioAutomatico() 
    {
        $con = BD::conectar();
        $update = sprintf("update PeriodoLetivo set rodouBloqueioAutomatico='SIM' "
                . "where idPeriodoLetivo = %d",
            mysql_real_escape_string( $this->idPeriodoLetivo) );
        $result = mysql_query($update, $con);
        if($result)
        {
            $this->rodouBloqueioAutomatico = true;
        }
        else 
        {
            throw new Exception("Erro ao registrar rodada bloqueio automatico");
        }
    }
}