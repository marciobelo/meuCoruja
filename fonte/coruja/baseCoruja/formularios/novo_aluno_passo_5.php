<!--// QUINTA PARTE DO FORMULARIO DE ALUNO NOVO   -->

<fieldset id="fieldsetGeral">
    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />       

    <legend>RESPONS&Aacute;VEIS</legend>

        <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
        
    <?php
        $formNovoAluno = new formulario();
        
        // nome da mãe
        echo $formNovoAluno->inputLabel('Nome da M&atilde;e(*)');
        echo "<input type='text' name='nomeMae' id='nomeMae' value='";
        echo htmlspecialchars($formAluno->nomeMae, ENT_QUOTES, "iso-8859-1");
        echo "' size='40' maxlength='80' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
        echo '<br />';

        // RG da mãe
        echo $formNovoAluno->inputLabel('R.G. M&atilde;e');
        echo $formNovoAluno->inputText('rgMae','',$formAluno->rgMae,'20','20','','');
        echo '<span>&nbsp;ex. 12345678-9 IFP/RJ</span>';
        echo '<br /><br />';

        // nome da pai
        echo $formNovoAluno->inputLabel('Nome do Pai');
        echo "<input type='text' name='nomePai' id='nomePai' value='";
        echo htmlspecialchars($formAluno->nomePai, ENT_QUOTES, "iso-8859-1");
        echo "' size='40' maxlength='80' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
        echo '<br />';

        // RG do pai
        echo $formNovoAluno->inputLabel('R.G. Pai');
        echo $formNovoAluno->inputText('rgPai','',$formAluno->rgPai,'12','20','','');
        echo '<span>&nbsp;ex. 12345678-9 IFP/RJ</span>';
        echo '<br /><br />';

        // nome responsavel legal
        echo $formNovoAluno->inputLabel('Respons&aacute;vel Legal');
        echo "<input type='text' name='responsavelLegal' id='responsavelLegal' value='";
        echo htmlspecialchars($formAluno->responsavelLegal, ENT_QUOTES, "iso-8859-1");
        echo "' size='40' maxlength='80' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
        echo '<br />';

        // RG do pai
        echo $formNovoAluno->inputLabel('R.G.');
        echo $formNovoAluno->inputText('rgResponsavel','',$formAluno->rgResponsavel,'12','20','','');
        echo '<span>&nbsp;ex. 12345678-9 IFP/RJ</span>';
        echo '<br /><br />';
     
    ?>    
    
</fieldset>

<!--//  FIM DA QUINTA PARTE DO FORMULARIO DE ALUNO NOVO  -->