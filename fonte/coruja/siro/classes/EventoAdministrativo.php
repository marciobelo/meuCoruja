<?php
/**
 * @author: Dyego Silva, Camila Areal
 * @name: EventoAdministrativo.php
 * @version: 1.0
 * @since: versão 1.0
 */
include_once "$BASE_DIR/classes/Curso.php";
include_once "$BASE_DIR/classes/PeriodoLetivo.php";

class EventoAdministrativo{

    private $idPeriodoLetivo;
    private $data;
    private $seqEvento;
    private $tipoEvento;
    private $descricao;
    private $mes;
    private $dia;

/**
* Getters e Setters
*
*/

    public function getIdPeriodoLetivo(){
    	return $this->idPeriodoLetivo;
    }
	public function setIdPeriodoLetivo($idPeriodoLetivo){
    	$this->idPeriodoLetivo = $idPeriodoLetivo;
    }
    public function getData(){
    	return $this->data;
    }
	public function setData($data){
    	$this->data = $data;
    }
 	public function getSeqEvento(){
    	return $this->seqEvento;
    }
	public function setSeqEvento($seqEvento){
    	$this->seqEvento = $seqEvento;
    }
	public function getTipoEvento(){
    	return $this->tipoEvento;
    }
	public function setTipoEvento($tipoEvento){
    	$this->tipoEvento = $tipoEvento;
    }
	public function getDescricao(){
    	return $this->descricao;
    }
	public function setDescricao($descricao){
    	$this->descricao = $descricao;
    }


/**
* Inserir dados na tabela EventoPeriodoLetivo
*
* @param $idPeriodoLetivo : periodo que corresponde o evento
* @param $dtIni : data inicial do evento
* @param $difDatas : diferenca calculada entre a data final e inicial do cadastro se a data final existir
* @param $tipoEvento : tipo do evento (não obrigatório)
* @param $descricaoEvento : descrição do evento
*/
    function insereEvento($difDatas)
        {


            if($this->getTipoEvento()==''){
                $tipoEvento = "NULL";
            }else{
                $tipoEvento = "'".$this->getTipoEvento()."'";
            }

            $con = BD::conectar();
            for($i = 0; $i <=$difDatas;$i++ ){
            	$this->setSeqEvento($this->getProximoId($con));
	            $queryEvento =  "INSERT INTO EventoPeriodoLetivo
	                (
	                seqEvento,
	                idPeriodoLetivo,
	                data,
	                tipoEvento,
	                descricao
	                )
	                VALUES
	                (
	                '".$this->getSeqEvento()."',
	                '".$this->getIdPeriodoLetivo()."',
	                DATE_ADD('".$this->getData()."', INTERVAL +$i DAY),
	                ".$tipoEvento.",
	                '".$this->getDescricao()."'
	                )";

	           	$msg_erro = "Erro ao Inserir Informações!";
            	$msg_erro_duplicado ="Tipo de evento já existente para este período letivo!" ;

	            mysql_query($queryEvento,$con);
	            if(mysql_errno()==1062){// numero do erro no mysql que identifica a entrada de chave duplicada
	            	return $msg_erro_duplicado;
	            }elseif(mysql_errno()!=0){
	            	return $msg_erro;
	            }
            }

            //monta a descricao para o log de autoria
            $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($this->getIdPeriodoLetivo());
            $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());

            $descricaoEvento = $this->getDescricao()==null? $this->getTipoEvento():$this->getDescricao();

            $descricao="Incluído o evento ".$descricaoEvento.
                        " para o período letivo ".
                        $periodoLetivo->getSiglaPeriodoLetivo()." (".
                        $periodoLetivo->getDataInicio()." - ".
                        $periodoLetivo->getDatafim().") ".
                        "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";

             $usuario = $_SESSION["usuario"];
             global $INCLUIR_EVENTOS_PERIODO_LETIVO;

             $usuario->incluirLog($INCLUIR_EVENTOS_PERIODO_LETIVO,$descricao);

             return "";
        }

/**
* METODO DE ATUALIZAÇÃO
*/

/**
* Atualizar dados na tabela de periodoletivo
*
* @param $idPeriodoLetivo
* @param $itensPeriodo: array com os valores a serem inseridos no BD
*/
    function atualizaEvento()
        {

            if($this->getTipoEvento()==''){
                $tipoEvento = "NULL";
            }else{
                $tipoEvento = "'".$this->getTipoEvento()."'";
            }

            $queryPeriodo =  "UPDATE EventoPeriodoLetivo SET
                idPeriodoLetivo = '".$this->getIdPeriodoLetivo()."',
                data = '".$this->getData()."',
                tipoEvento = ".$tipoEvento.",
                descricao = '".$this->getDescricao()."'
                WHERE seqEvento = '".$this->getSeqEvento()."'";

            	$msg_erro = "Erro ao Inserir Informações!";
            	$msg_erro_duplicado ="Tipo de evento já existente para este período letivo!" ;

            //monta a descricao para o log de autoria
            $eventoAntes = new EventoAdministrativo();
            $eventoAntes->getEvento($this->getSeqEvento());
            $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($eventoAntes->getIdPeriodoLetivo());
            $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());

            $descricaoEventoAntes = $eventoAntes->getDescricao()==null? $eventoAntes->getTipoEvento():$eventoAntes->getDescricao();

            $descricaoEvento = $this->getDescricao()==null? $this->getTipoEvento():$this->getDescricao();

            $descricao="Alterado o evento ".$descricaoEventoAntes." para ".$descricaoEvento.", ".
                        "e a data de ".$eventoAntes->getData()." para ".$this->getData()." para o período letivo ".
                        $periodoLetivo->getSiglaPeriodoLetivo()." (".
                        $periodoLetivo->getDataInicio()." - ".
                        $periodoLetivo->getDatafim().") ".
                        "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";
            
            $con = BD::conectar();
            $sqlPeriodo = mysql_query($queryPeriodo,$con);

            if(mysql_errno()==1062){// numero do erro no mysql que identifica a entrada de chave duplicada
            	return $msg_erro_duplicado;
            }elseif(mysql_errno()!=0){
            	return $msg_erro;
            }else{
                $usuario = $_SESSION["usuario"];
                global $ALTERAR_EVENTOS_PERIODO_LETIVO;

                //insere o log
                $usuario->incluirLog($ALTERAR_EVENTOS_PERIODO_LETIVO,$descricao);

                return "";
            }
        }


/*
    * METODOS DE LISTAGEM
*/

/**
* retorna uma lista de eventos administrativos de um determinado periodo letivo e de um curso.
*
* @param $idPeriodoLetivo: qual periodo letivo sera usado na restricao da query.
* @param $idCasoUso: há duas funcionalidades que utilizam esse metodo, e é necessário o idCasoUso
 * para gravar a descrição adequada no log de autoria
* */

    public function listaEvento($idPeriodoLetivo, $idCasoUso){
    	$collection = null;
        $con = BD::conectar();

        // lista os peridos
        $lista = mysql_query("SELECT date_format(data,'%d/%m/%Y') as data,"
                . "seqEvento,idPeriodoLetivo,tipoEvento,descricao "
                . "FROM EventoPeriodoLetivo e where idPeriodoLetivo =$idPeriodoLetivo "
                . "order by year(data),month(data),day(data)", $con);
        if(mysql_num_rows($lista) > 0){
        	$collection = array();
        	while($objeto = mysql_fetch_array($lista)){
        		$evento = new EventoAdministrativo();
        		$evento->setData($objeto['data']);
        		$evento->setDescricao($objeto['descricao']);
        		$evento->setIdPeriodoLetivo($objeto['idPeriodoLetivo']);
        		$evento->setTipoEvento($objeto['tipoEvento']);
        		$evento->setSeqEvento($objeto['seqEvento']);
        		$collection[] = $evento;
        	}
        }

        //monta a descricao para o log de autoria
        $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodoLetivo);
        $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());

        global $MANTER_EVENTOS_PERIODO_LETIVO;
        
        if($idCasoUso==$MANTER_EVENTOS_PERIODO_LETIVO){
            $descricao="Consultado os eventos administrativos ".
                    "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ".
                    "do período letivo ".
                    $periodoLetivo->getSiglaPeriodoLetivo()." (".
                    $periodoLetivo->getDataInicio()." - ".
                    $periodoLetivo->getDatafim().") ";
        }
        else{
            $descricao="Gerado calendário letivo para o período letivo ".
                    $periodoLetivo->getSiglaPeriodoLetivo()." (".
                    $periodoLetivo->getDataInicio()." - ".
                    $periodoLetivo->getDatafim().") ".
                    "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";
        }

        $usuario = $_SESSION["usuario"];

        $usuario->incluirLog($idCasoUso,$descricao);

        return $collection;
    }

 public function isAlteracaoInvalida($idPeriodo,$dataInicio,$dataFim){
    	    $encontrou = false;
			$con = BD::conectar();
            $sql = "select CASE
      					WHEN (SELECT count(*) as linhas FROM EventoPeriodoLetivo e where e.idPeriodoLetivo = ".$idPeriodo.") > (select count(*) from EventoPeriodoLetivo e1 where e1.data between '".$dataInicio."' and '".$dataFim."') then 'invalido'
					END AS cont
					from EventoPeriodoLetivo";
            $result = mysql_query($sql,$con);
         
            if($result){
            	
            	if(mysql_result($result,0,'cont')=='invalido'){
            	  $encontrou = true;
            	}
            }
            return $encontrou;
    }
    
   /**
    * 
    * @param type $data
    * @param type $dataInicialPeriodo
    * @param type $dataFinalPeriodo
    * @return boolean
    * @deprecated since version number
    * TODO deletar essa budega
    */ 
   public static function isDataInvalida($data,$dataInicialPeriodo,$dataFinalPeriodo){
      	    $invalido = false;
			$con = BD::conectar();
            $sql = "SELECT IF('".$data."' between '".$dataInicialPeriodo."' and '".$dataFinalPeriodo."','sim','nao') as estaNoPeriodo";
            $result = mysql_query($sql,$con);
         
            if($result){
            	
            	if(mysql_result($result,0,'estaNoPeriodo')=='nao'){
            	  $invalido = true;
            	}
            }
            return $invalido;
   } 
    
 /**
* retorna o objeto de EventoPeriodoLetivo.
*
* @param $seqEvento: identificador do objeto.
*
* */

 public function getEvento($seqEvento)
{
       $con = BD::conectar();
       $queryEvento =  "select date_format(data,'%d/%m/%Y') as data,seqEvento,idPeriodoLetivo,tipoEvento,descricao from EventoPeriodoLetivo where seqEvento =$seqEvento";
       $sqlEvento = mysql_query($queryEvento,$con);
       $qtd = mysql_num_rows($sqlEvento);
       if($qtd > 0)
       {
           $this->setIdPeriodoLetivo(mysql_result($sqlEvento,0,'idPeriodoLetivo'));
           $this->setData(mysql_result($sqlEvento,0,'data'));
           $this->setTipoEvento(mysql_result($sqlEvento,0,'tipoEvento'));
           $this->setDescricao(mysql_result($sqlEvento,0,'descricao'));
           $this->setSeqEvento($seqEvento);
       }
}

/**
* METODO DE EXCLUSÃO
*/

/**
* retorna uma lista dos 10(dez) ultimos periodos letivos da tabela periodoletivo
*
* @param $idPeriodoLetivo
*/


 function excluir($seqEvento)
    {
        
        // exclui o periodo
        $queryEvento = "DELETE FROM EventoPeriodoLetivo where seqEvento = $seqEvento";

        //monta a descricao para o log de autoria
        $this->getEvento($seqEvento);
        $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($this->getIdPeriodoLetivo());
        $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());

        $descricaoEvento = $this->getDescricao()==null? $this->getTipoEvento():$this->getDescricao();
        
        $descricao="Excluido o evento ".$descricaoEvento.
                    " para o período letivo ".
                    $periodoLetivo->getSiglaPeriodoLetivo()." (".
                    $periodoLetivo->getDataInicio()." - ".
                    $periodoLetivo->getDatafim().") ".
                    "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";
        $con = BD::conectar();
        
        mysql_query($queryEvento,$con);
        if(mysql_errno()!=0){
          echo "<br />Erro ao excluir periodo letivo!<br /><br />";
        }
        else{
            $usuario = $_SESSION["usuario"];
            global $EXCLUIR_EVENTOS_PERIODO_LETIVO;

            $usuario->incluirLog($EXCLUIR_EVENTOS_PERIODO_LETIVO,$descricao);
        }
    }


/*
    * METODOS UTEIS
*/

// RESGATA O DIA DA SEMANA DE UMA DATA
/**
* retorna a data com o seu dia da semana
*
* @param $data : DD/MM/AAAA
*/

	public static function DataDiaSemana($data) {
		$dia =  substr($data, 0, 2);
		$mes =  substr($data, 3, 2);
		$ano =  substr($data, 6, 10);

		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

		switch($diasemana) {
			case"0": $diasemana = "Domingo";       break;
			case"1": $diasemana = "Segunda-Feira"; break;
			case"2": $diasemana = "Terça-Feira";   break;
			case"3": $diasemana = "Quarta-Feira";  break;
			case"4": $diasemana = "Quinta-Feira";  break;
			case"5": $diasemana = "Sexta-Feira";   break;
			case"6": $diasemana = "Sábado";        break;
		}

		return $data." - ".$diasemana;
	}

// METODO QUE BUSCA O PROXIMO ID DA TABELA DE PERIODOLETIVO
/**
* retorna o id seguinte para ser inserido na tabela de EventoPeriodoLetivo
*
*/
	 public function getProximoId($con){

	 	$queryProximoId = "select ifNULL(max(seqEvento),0)+1 as proximoId from EventoPeriodoLetivo";
	 	$resultado = mysql_query($queryProximoId,$con);
	 	$proximoId = mysql_result($resultado,0,'proximoId');

	 	return $proximoId;
	 }

}
?>