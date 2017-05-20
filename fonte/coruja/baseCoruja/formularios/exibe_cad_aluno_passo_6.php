<!--// SEXTA PARTE DO FORMULARIO   -->

<fieldset id="fieldsetGeral">
    
    <?php
    
        echo "<legend>ESCOLAR<br/>" . $infoPessoa->getNome() ." </legend>";

        // escola de origem
        echo $formExibeAluno->inputLabel('Escola Ens. M&eacute;dio',$infoAluno->getEstabCursoOrigem());
        echo "<br />";
        
        // Cidade escola de origem
        echo $formExibeAluno->inputLabel('Cidade', $infoAluno->getEstabCursoOrigemCidade() );
        echo "<br />";

        // UF escola de origem
        echo $formExibeAluno->inputLabel('UF',$infoAluno->getEstabCursoOrigemUF());
        echo "<br />";
        
        // modalidade de origem
        echo $formExibeAluno->inputLabel('Modalidade de Origem',$infoAluno->getModalidadeCursoOrigem());
        echo "<br />";
        
        // ano de conclusão
        echo $formExibeAluno->inputLabel('Ano de Conclus&atilde;o',$infoAluno->getCursoOrigemAnoConclusao());
        echo "<br />";
        echo "<br />";
        
        echo "<p align='center'><input id='button1' name='escolhe_aba' type='button' value='Editar Escolar' onclick='javascript:editar(6);' /></p>";
        
    ?>    

</fieldset>

<!--//  FIM DA SEXTA PARTE DO FORMULARIO  -->