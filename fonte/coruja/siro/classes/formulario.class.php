<?php
/**
 * @author: Helder Nascimento
 * @name: formulario.class.php
 * @version: 1.0
 * @since: versão 1.0 
 */
//require_once("../../includes/comum.php");

class formulario{

    /**
     * @param: $nome       -    nome do campo.
     * @param: $classe     -    classe do estilo, nesse caso: obrigatorio ou 'vazio'.      
     * @param: $valor      -    valor digitado ou valor recebido via GET ou POST.
     * @param: $size       -    comprimento do campo texto.
     * @param: $maxSize    -    Máximo de caracteres que o campo deve aceitar.
     * @param: $dica       -    string a ser exibida na dica de preenchimento de um campo.
     * @param: $onchange   -    usado por um script java para converter a string do campo em UPPERCASE.
     */

    function inputText($nome,$classe,$valor,$size,$maxSize,$dica,$onchange) {
        
        if($dica != ''){$dica = " tooltipText='   $dica'";}
        
        return "<input type='text' class='$classe' name='$nome' id='$nome' value='$valor' size='$size' $dica maxlength='$maxSize' onchange='$onchange' />";
    }          

    
    
    
    /**
     * @param: $nome                -    nome do campo.
     * @param: $valores                -    array de valores; esse por sua vez deve ser unidimensional.
     * @param: $selecionado            -    opção que retorna o valor atual gravado no banco.
     */

    function inputSelect($nome,$valores,$selecionado) {
        if($selecionado==''){
            $select .= "<option value='' selected='selected'>UF</option>";    
        }
        
        foreach($valores as $key=>$valor){
            if($valor == $selecionado) {$select .="<option value='$key' selected='selected' >$valor</option>";}
            else                     {$select .="<option value='$key' >$valor</option>";}
        }

        $select = " <select id='$nome' name='$nome'>$select</select>";

        return  $select;

    }
    
    /**
     * @param: $nome                -    nome do campo.
     * @param: $tabela              -    tabela onde está o campo ENUM requerido
     * @param: $coluna              -    coluna tipo ENUM requerida
     * @param: $selecionado         -    opção que retorna o valor atual gravado no banco para ficar selecionado.
     */

    function inputSelectEnum($nome,$tabela,$coluna,$selecionado) {
        
        $con = BD::conectar();
        
        if($selecionado==''){
            $selectEnum .= "<option value='' selected='selected'>SELECIONE</option>";    
        }
        
        
        $result=mysql_query("SHOW COLUMNS FROM $tabela WHERE field='$coluna'");
                while ($row=mysql_fetch_row($result))
                {
                foreach(explode("','",substr($row[1],6,-2)) as $opcoes)
                {
                    if($opcoes == $selecionado){
                        $selectEnum .= "<option value='$opcoes' selected='selected'>$opcoes</option>";
                    }else{
                    
                    $selectEnum .= "<option value='$opcoes'>$opcoes</option>";
                    }
                }
                }
      
        $selectEnum = " <select id='$nome' name='$nome'>$selectEnum</select>";

        return  $selectEnum;
    }
    
    /**
     * @param: $nome                -    nome do campo.
     * @param: $tabela              -    tabela onde está o campo ENUM requerido
     * @param: $coluna              -    coluna tipo ENUM requerida
     * @param: $selecionado         -    opção que retorna o valor atual gravado no banco para ficar selecionado.
     */

    function inputRadioEnum($nome,$tabela,$coluna,$selecionado) {
        $con = BD::conectar();
        
        $result=mysql_query("SHOW COLUMNS FROM $tabela WHERE field='$coluna'");
                while ($row=mysql_fetch_row($result))
                {
                foreach(explode("','",substr($row[1],6,-2)) as $opcoes)
                {
                    if($opcoes == $selecionado){
                        $radioEnum .= "<input type='radio' name='$nome' value='$opcoes' checked='checked' />$opcoes &nbsp;&nbsp;";
                    }else{
                    
                        $radioEnum .= "<input type='radio' name='$nome' value='$opcoes' />$opcoes &nbsp;&nbsp;";
                    }
                }
                }
      
        return  $radioEnum;
    }
	/**
	 * @param: $nome                -    nome do campo.
	 * @param: $tabela              -    tabela onde estao os campos requeridos
	 * @param: $coluna1             -    coluna requerida
	 * @param: $coluna2             -    coluna requerida
	 */

	function inputSelectDinamico($nome,$tabela,$coluna1,$coluna2) {
		$con = BD::conectar();
        
		$this->query = $query;
		
		$result = mysql_query("SELECT DISTINCT $coluna1,$coluna2 FROM $tabela");               
		
		$selectDinamico .= "<option value='' selected='selected'>SELECIONE</option>";    
		
				while ($row=mysql_fetch_array($result))
				{   
					$valor1 = $row[$coluna1];
					$valor2 = $row[$coluna2];
					
					$selectDinamico .= "<option value=$valor1> $valor2 </option>";

				}
	  
		$selectDinamico = " <select id='$nome' name='$nome'>$selectDinamico</select>";

		return  $selectDinamico;
	}


    /**
     *  especifico para o manter turma - listar
     * @param: $nome                -    nome do campo.
     * @param: $tabela              -    tabela onde estao os campos requeridos
     * @param: $coluna1             -    coluna requerida
     * @param: $coluna2             -    coluna requerida
     */

    function inputSelectDinamicoComboBox($nome,$tabela,$coluna1,$coluna2,$onChange) {
        $con = BD::conectar();
        
        $this->query = $query;
        
        $result = mysql_query("SELECT DISTINCT $coluna1,$coluna2 FROM $tabela");               
        
        $selectDinamico .= "<option value='' selected='selected'>SELECIONE</option>";    
        
                while ($row=mysql_fetch_array($result))
                {   
                    $valor1 = $row[$coluna1];
                    $valor2 = $row[$coluna2];
                    
                    $selectDinamico .= "<option value=$valor1> $valor2 </option>";

                }
      
        $selectDinamico = " <select id='$nome' name='$nome' onchange='$onChange'>$selectDinamico</select>";

        return  $selectDinamico;
    }


    
    /**
     * @param: $nome                -    nome do campo.
     * @param: $tabela              -    tabela onde estao os campos requeridos
     * @param: $idRef               -    idReferencia
     * @param: $descricao           -    descrição desejada
     */

    function inputValorId($nome,$tabela,$idRef,$descricao) {
        $con = BD::conectar();
        
        $this->query = $query;
        
        $result = mysql_query("SELECT $nome,$descricao FROM $tabela WHERE $nome='$idRef'");               
        
                while ($row=mysql_fetch_array($result))
                {   
                    $valor1 = $row[$idRef];
                    $valor2 = $row[$descricao];
                
                // descobrir o tipo de campo    
                    $tipocampo = mysql_field_type($result,"1");
                    
                if($tipocampo=='date'){ 
                    $valor2 = explode('-',$valor2);
                    $valor2 = implode('/',array_reverse($valor2));
                }    
                    
                    $descId .= "$valor2";
                    
                }
      
        return  $descId;
    }
    
    /**
     * ESPECÍFICO PARA O CASO DE PROFESSOR 
     * @param: $nome                -    nome do campo.
     * @param: $tabela              -    tabela onde estao os campos requeridos
     * @param: $coluna1             -    coluna requerida
     * @param: $coluna2             -    coluna requerida
     */

    function inputSelectDinamicoProf($nome, $selecionado) {
        $con = BD::conectar();
        
        $this->query = $query;
        
        $resultProf = mysql_query("SELECT matriculaprofessor.matriculaProfessor,matriculaprofessor.idPessoa,pessoa.idPessoa,pessoa.nome ".
                            " FROM matriculaprofessor INNER JOIN pessoa ".
                            "ON matriculaprofessor.idPessoa = pessoa.idPessoa ORDER BY pessoa.nome ASC");               
        
        $selectDinamicoProf .= "<option value='' selected='selected'>SELECIONE</option>";    
        
                while ($rowProf=mysql_fetch_array($resultProf))
                {   
                    $matriculaProfessor = $rowProf['matriculaProfessor'];
                    $nomeProf = $rowProf['nome'];
                    
                    if($matriculaProfessor == $selecionado)
                    {
                        $selectDinamicoProf .= "<option value='$matriculaProfessor' selected='selected'> $nomeProf ($matriculaProfessor) </option>";
                    }
                    else
                    {
                        $selectDinamicoProf .= "<option value='$matriculaProfessor'> $nomeProf ($matriculaProfessor) </option>";
                    }
                }
      
        $selectDinamicoProf = " <select id='$nome' name='$nome'>$selectDinamicoProf</select>";

        return  $selectDinamicoProf;
    }

    
    /**
     * @param: $name                 -    nome do campo.
     * @param: $values               -    array de valores; esse por sua vez deve ser unidimensional.
     * @param: $check                -    opção marcada.
     * @param: $separation           -    Aqui podemos utlizar o '<br />' caso necessite de quebra de linha.
     */
     function inputRadio($name,$values,$check,$separation=null) {

        foreach($values as $key=>$valor){
            
            if($key == $check) {$radio .="<input type='radio' name='$name' value='$key' checked='checked' />$valor $separation";}
            else               {$radio .="<input type='radio' name='$name' value='$key' />$valor $separation";}
            
        }

            return $radio;
        }
      
      
      function inputLabel($nome) {
        return "<label for='$nome'> $nome : </label>";
    } 
      function inputHidden($nome,$valor) {
        return "<input type='hidden' id='$nome' name='$nome' value='$valor' />";
    }
    
      function inputCheckbox($name,$values,$check,$separation=null) {

        foreach($values as $key=>$valor){
            if($key == $check) {$checkbox .="<input type='checkbox' name='$name' value='$key' checked='checked' />$valor $separation";}
            else               {$checkbox .="<input type='checkbox' name='$name' value='$key' />$valor $separation";}
            }

            return $checkbox;
        }
       function inputTextarea($nome,$linhas,$largura) {
        return "<textarea id='$nome' name='$nome' rows='$linhas' cols='$largura' />";
    }  
    function inputBotaoSubmit($nome,$valor) {
        return "<input id='button1' type='submit' name='$nome' value=' $valor ' />";
    }  
                       
}
    


class formularioExibir{

    /**
     * @param: $nome       -    nome do campo.
     * @param: $valor      -    valor digitado ou valor recebido via GET ou POST.
     */
     
     function inputLabel($nome,$valor) {
        return "<label for='$nome'> $nome : </label> $valor";
    } 
      function inputHidden($nome,$valor) {
        return "<input type='hidden' id='$nome' name='$nome' value='$valor' />";
    }
     
}        
?>            