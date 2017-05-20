<!--// SEGUNDA PARTE DO FORMULARIO DE ALUNO NOVO   -->
<fieldset id="fieldsetGeral">
    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />

    <legend>CONTATO</legend>
    
    <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
        
    <?php
        $formNovoAluno = new formulario();
        
        // CEP
        echo $formNovoAluno->inputLabel('CEP(*)');
        echo $formNovoAluno->inputText('enderecoCEP','',$formAluno->enderecoCEP,'8','9','','');
        echo '<br />';
         
        // endereço
        echo $formNovoAluno->inputLabel('Endere&ccedil;o(*)');
        echo "<input type='text' name='enderecoLogradouro' id='enderecoLogradouro' value='";
        echo htmlspecialchars($formAluno->enderecoLogradouro, ENT_QUOTES, "iso-8859-1");
        echo "' size='50' maxlength='80' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
        echo '<br />';
    
        // número
        echo $formNovoAluno->inputLabel('N&uacute;mero(*)');
        echo $formNovoAluno->inputText('enderecoNumero','',$formAluno->enderecoNumero,'5','10','','');
    
        // complemento
        echo ' Complemento:';
        echo $formNovoAluno->inputText('enderecoComplemento','',$formAluno->enderecoComplemento,'5','45','','this.value=this.value.toUpperCase();');
        echo '<br />';
    
        // Bairro
        echo $formNovoAluno->inputLabel('Bairro(*)');
        echo "<input type='text' name='enderecoBairro' id='enderecoBairro' value='";
        echo htmlspecialchars($formAluno->enderecoBairro, ENT_QUOTES, "iso-8859-1");
        echo "' size='40' maxlength='60' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
        echo '<br />';
    
        // municipio
        echo $formNovoAluno->inputLabel('Munic&iacute;pio(*)');
        echo "<input type='text' name='enderecoMunicipio' id='enderecoMunicipio' value='";
        echo htmlspecialchars($formAluno->enderecoMunicipio, ENT_QUOTES, "iso-8859-1");
        echo "' size='40' maxlength='60' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
        echo '<br />';
         
        // estado
        echo $formNovoAluno->inputLabel('Estado(*)');
        echo $formNovoAluno->inputSelect('enderecoEstado',array('AC'=>'AC','AL'=>'AL','AM'=>'AM','AP'=>'AP','BA'=>'BA','CE'=>'CE','DF'=>'DF','ES'=>'ES','GO'=>'GO','MA'=>'MA','MG'=>'MG','MS'=>'MS','MT'=>'MT','PA'=>'PA','PB'=>'PB','PE'=>'PE','PI'=>'PI','PR'=>'PR','RJ'=>'RJ','RN'=>'RN','RO'=>'RO','RR'=>'RR','RS'=>'RS','SC'=>'SC','SE'=>'SE','SP'=>'SP','TO'=>'TO'),$formAluno->enderecoEstado);
        echo '<br /><br />';
    
        // Tel residencial
        echo $formNovoAluno->inputLabel('Tel. Residencial(*)');
        echo $formNovoAluno->inputText('telefoneResidencial','',$formAluno->telefoneResidencial,'15','15','','');
        echo "&nbsp;<font color='red'>Digite com o DDD. Ex.: (21) 2332-4048</font>";
        echo '<br />';

        // Tel comercial
        echo $formNovoAluno->inputLabel('Tel. Comercial');
        echo $formNovoAluno->inputText('telefoneComercial','',$formAluno->telefoneComercial,'15','15','','');
        echo "&nbsp;<font color='red'>Digite com o DDD. Ex.: (11) 2332-4042</font>";
        echo '<br />';
    
        // Tel celular
        echo $formNovoAluno->inputLabel('Tel. Celular');
        echo $formNovoAluno->inputText('telefoneCelular','',$formAluno->telefoneCelular,'15','15','','');
        echo "&nbsp;<font color='red'>Digite com o DDD. Ex.: (21) 9898-8989</font>";
        echo '<br />';
    
        // Email
        echo $formNovoAluno->inputLabel('Email(*)');
        echo $formNovoAluno->inputText('email','',$formAluno->email,'40','80','','');
        echo '<br />';
    
    ?>
    
</fieldset>

<!--//  FIM DA EGUNDA PARTE DO FORMULARIO DE ALUNO NOVO  -->