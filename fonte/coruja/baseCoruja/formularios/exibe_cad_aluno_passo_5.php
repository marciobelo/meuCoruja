<!--// QUINTA PARTE DO FORMULARIO   -->

<fieldset id="fieldsetGeral">
    
    <?php
    
        echo "<legend>RESPONS&Aacute;VEIS<br/>" . $infoPessoa->getNome() ." </legend>";

        // nome da mãe
        echo $formExibeAluno->inputLabel('Nome da M&atilde;e',$infoAluno->getNomeMae());
        echo "<br />";
        
        // rg da mãe
        echo $formExibeAluno->inputLabel('R.G. M&atilde;e',$infoAluno->getRgMae());
        echo "<br />";
        
        // nome do pai
        echo $formExibeAluno->inputLabel('Nome do Pai',$infoAluno->getNomePai());
        echo "<br />";

        // rg do pai
        echo $formExibeAluno->inputLabel('R.G. Pai',$infoAluno->getRgPai());
        echo "<br />";
        
        // responsavel legal, se houver
        echo $formExibeAluno->inputLabel('Respons&aacute;vel Legal',$infoAluno->getResponsavelLegal());
        echo "<br />";
        echo $formExibeAluno->inputLabel('R.G.',$infoAluno->getRgResponsavel());
        echo "<br />";
        
        echo "<br />";
        
        echo "<p align='center'><input id='button1' name='escolhe_aba' type='button' value='Editar Respons&aacute;veis' onclick='javascript:editar(5);' /></p>";
        
    ?>    
      
</fieldset>
            
<!--//  FIM DA QUINTA PARTE DO FORMULARIO  -->