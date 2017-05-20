<!--// TERCEIRA PARTE DO FORMULARIO DE ALUNO NOVO   -->

<fieldset id="fieldsetGeral">
    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
         
    <legend>DADOS COMPLEMENTARES</legend>
    
    <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
        
    <?php
        $formNovoAluno = new formulario();
        
        // estado civil
        echo $formNovoAluno->inputLabel('Estado Civil(*)');
        echo $formNovoAluno->inputRadio('estadoCivil',array('SOLTEIRO'=>'Solteiro','CASADO'=>'Casado','OUTROS'=>'Outros'),$formAluno->estadoCivil,'&nbsp;');
        echo '<br /><br />';
     
        // cor/raça
        echo $formNovoAluno->inputLabel('Cor/Ra&ccedil;a(*)');
        echo $formNovoAluno->inputSelectEnum('corRaca','Aluno','corRaca',$formAluno->corRaca);
        echo '<br /><br />';
     
        // necessidade especial
        echo $formNovoAluno->inputLabel('Necessidade Especial');
        echo $formNovoAluno->inputCheckbox('deficienciaAuditiva',array('SIM'=>'Auditiva'),$formAluno->deficienciaAuditiva,'&nbsp;&nbsp;');
        echo $formNovoAluno->inputCheckbox('deficienciaVisual',array('SIM'=>'Visual'),$formAluno->deficienciaVisual,'&nbsp;&nbsp;');
        echo $formNovoAluno->inputCheckbox('deficienciaMotora',array('SIM'=>'Motora'),$formAluno->deficienciaMotora,'&nbsp;&nbsp;');
        echo $formNovoAluno->inputCheckbox('deficienciaMental',array('SIM'=>'Mental'),$formAluno->deficienciaMental,'&nbsp;&nbsp;');
        echo '<br /><br />';
         
     ?>    
    
</fieldset>

<!--//  FIM DA TERCEIRA PARTE DO FORMULARIO DE ALUNO NOVO  -->