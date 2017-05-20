<!--// TERCEIRA PARTE DO FORMULARIO   -->

<fieldset id="fieldsetGeral">
    <?php
    
        echo "<legend>DADOS COMPLEMENTARES<br/>" . $infoPessoa->getNome() ." </legend>";

        // estado civil 
        echo $formExibeAluno->inputLabel('Estado Civil',$infoAluno->getEstadoCivil());
        echo "<br />";
        
        // cor/raça 
        echo $formExibeAluno->inputLabel('Cor/Ra&ccedil;a',$infoAluno->getCorRaca());
        echo "<br />";
        
        // Necessidade Especial
        if($infoAluno->getDeficienciaAuditiva() == 'SIM') {
            $necessidadeEspecial = 'AUDITIVA';
        }
        if($infoAluno->getDeficienciaVisual() == 'SIM') {
            if($necessidadeEspecial!="") $necessidadeEspecial.=",";
            $necessidadeEspecial .= 'VISUAL';
        }
        if($infoAluno->getDeficienciaMotora() == 'SIM') {
            if($necessidadeEspecial!="") $necessidadeEspecial.=",";
            $necessidadeEspecial .= 'MOTORA';
        }
        if($infoAluno->getDeficienciaMental() == 'SIM') {
            if($necessidadeEspecial!="") $necessidadeEspecial.=",";
            $necessidadeEspecial .= 'MENTAL';
        }
        if($necessidadeEspecial=="") $necessidadeEspecial.="NENHUMA";
        
        echo $formExibeAluno->inputLabel('Necessidade Especial',$necessidadeEspecial);
        echo "<br />";
        echo "<br />";
        
        echo "<p align='center'><input id='button1' name='escolhe_aba' type='button' value='Editar Complementar' onclick='javascript:editar(3);' /></p>";
        
    ?>
</fieldset>
  
<!--//  FIM DA TERCEIRA PARTE DO FORMULARIO  -->