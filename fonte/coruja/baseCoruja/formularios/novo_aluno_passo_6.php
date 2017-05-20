<!--// SEXTA PARTE DO FORMULARIO DE ALUNO NOVO   -->

<fieldset id="fieldsetGeral">
    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />           

    <legend>DADOS ESCOLARES</legend>
    
    <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
         
    <?php
        $formNovoAluno = new formulario();
        
        // Escola de origem
        echo $formNovoAluno->inputLabel('Escola Ens. M&eacute;dio(*)');
        echo "<input type='text' name='estabCursoOrigem' id='estabCursoOrigem' value='";
        echo htmlspecialchars($formAluno->estabCursoOrigem, ENT_QUOTES, "iso-8859-1");
        echo "' size='40' maxlength='80' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";

        echo '<br />';
        echo $formNovoAluno->inputLabel('Cidade(*)');
        echo "<input type='text' name='estabCursoOrigemCidade' id='estabCursoOrigemCidade' value='";
        echo htmlspecialchars($formAluno->estabCursoOrigemCidade, ENT_QUOTES, "iso-8859-1");
        echo "' size='40' maxlength='40' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
        echo '<br />';
        echo $formNovoAluno->inputLabel('UF(*)');
        echo $formNovoAluno->inputSelect('estabCursoOrigemUF',array('AC'=>'AC','AL'=>'AL','AM'=>'AM','AP'=>'AP','BA'=>'BA','CE'=>'CE','DF'=>'DF','ES'=>'ES','GO'=>'GO','MA'=>'MA','MG'=>'MG','MS'=>'MS','MT'=>'MT','PA'=>'PA','PB'=>'PB','PE'=>'PE','PI'=>'PI','PR'=>'PR','RJ'=>'RJ','RN'=>'RN','RO'=>'RO','RR'=>'RR','RS'=>'RS','SC'=>'SC','SE'=>'SE','SP'=>'SP','TO'=>'TO'),$formAluno->estabCursoOrigemUF);
        echo '<br /><br />';

        // Ano conclusão do ensino médio
        echo $formNovoAluno->inputLabel('Ano de Conclus&atilde;o(*)');
        echo $formNovoAluno->inputText('cursoOrigemAnoConclusao','',$formAluno->cursoOrigemAnoConclusao,'4','4','','this.value=this.value.toUpperCase();');
        echo '<br />';

        // modalidade do curso de origem
        echo $formNovoAluno->inputLabel('Modalidade (*)');
        echo $formNovoAluno->inputRadio('modalidadeCursoOrigem',array('ENSINO MÉDIO'=>'ENSINO M&Eacute;DIO','SUPLETIVO'=>'SUPLETIVO','GRADUAÇÃO'=>'GRADUA&Ccedil;&Atilde;O','OUTROS'=>'OUTROS'),$formAluno->modalidadeCursoOrigem,'');
        echo '<br/>'; 
     ?>    
</fieldset>
<!--//  FIM DA SEXTA PARTE DO FORMULARIO DE ALUNO NOVO  -->