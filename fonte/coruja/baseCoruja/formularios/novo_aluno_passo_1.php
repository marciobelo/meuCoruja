<!--// PRIMEIRA PARTE DO FORMULARIO DE MATRICULA NOVA   -->

    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
    <fieldset id="fieldsetGeral">
    
        <legend>DADOS PESSOAIS</legend>
        
        <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
        
        <?php
            $formNovoAluno = new formulario();
            
             // nome
             echo $formNovoAluno->inputLabel('Nome(*)');
             echo "<input type='text' name='nome' id='nome' value='";
             echo htmlspecialchars($formAluno->nome, ENT_QUOTES, "iso-8859-1");
             echo "' size='40' maxlength='80' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
             echo '<br />';
             
             // sexo
             echo $formNovoAluno->inputLabel('Sexo(*)');
             echo $formNovoAluno->inputRadio('sexo',array('M'=>'Masculino','F'=>'Feminino'),$formAluno->sexo,'');
             echo '<br />'; 
             
             // Data Nascimento
             echo $formNovoAluno->inputLabel('Data Nasc.(*)');
             echo $formNovoAluno->inputText('dataNascimentoD','',$formAluno->dataNascimentoD,'2','2','','');
             echo '/';
             echo $formNovoAluno->inputText('dataNascimentoM','',$formAluno->dataNascimentoM,'2','2','','');
             echo '/';
             echo $formNovoAluno->inputText('dataNascimentoA','',$formAluno->dataNascimentoA,'4','4','','');
             echo '<br />';
             
             // nacionalidade
             echo $formNovoAluno->inputLabel('Nacionalidade(*)');
             echo "<input type='text' name='nacionalidade' id='nacionalidade' value='";
             echo htmlspecialchars($formAluno->nacionalidade, ENT_QUOTES, "iso-8859-1");
             echo "' size='40' maxlength='45' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
             echo '<br />'; 
             
            // naturalidade
             echo $formNovoAluno->inputLabel('Naturalidade(*)');
             echo "<input type='text' name='naturalidade' id='naturalidade' value='";
             echo htmlspecialchars($formAluno->naturalidade, ENT_QUOTES, "iso-8859-1");
             echo "' size='40' maxlength='45' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
             echo '<br />';                
        ?>
    </fieldset>

<!--//  FIM DA PRIMEIRA PARTE DO FORMULARIO DE MATRICULA NOVA  -->