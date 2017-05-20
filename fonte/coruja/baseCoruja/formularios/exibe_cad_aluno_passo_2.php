<!--// SEGUNDA PARTE DO FORMULARIO    -->
<fieldset id="fieldsetGeral">
    <?php
    
        echo "<legend>CONTATO<br/>" . $infoPessoa->getNome() ." </legend>";

        // cep 
        echo $formExibeAluno->inputLabel('CEP',$infoPessoa->getEnderecoCEP());
        echo "<br />";
        
        // endereço 
        if($infoPessoa->getEnderecoComplemento() == ''){ $endereco = $infoPessoa->getEnderecoLogradouro() . ', Numero: ' . $infoPessoa->getEnderecoNumero();}
        else{ $endereco = $infoPessoa->getEnderecoLogradouro() . ', Número: ' . $infoPessoa->getEnderecoNumero() . ' - ' . $infoPessoa->getEnderecoComplemento();}
        echo $formExibeAluno->inputLabel('Endere&ccedil;o',$endereco);
        echo "<br />";
        
        // bairro 
        echo $formExibeAluno->inputLabel('Bairro',$infoPessoa->getEnderecoBairro());
        echo "<br />";
        
        // municipio 
        echo $formExibeAluno->inputLabel('Munic&iacute;pio',$infoPessoa->getEnderecoMunicipio());
        echo "<br />";
        
        // estado  
        echo $formExibeAluno->inputLabel('Estado',$infoPessoa->getEnderecoEstado());
        echo "<br />";
        
        // tel residencial 
        echo $formExibeAluno->inputLabel('Tel. Residencial',$infoPessoa->getTelefoneResidencial());
        echo "<br />";

        // tel comercial
        echo $formExibeAluno->inputLabel('Tel. Comercial',$infoPessoa->getTelefoneComercial());
        echo "<br />";

        // tel celular
        echo $formExibeAluno->inputLabel('Tel. Celular',$infoPessoa->getTelefoneCelular());
        echo "<br />";
    
        // e-mail
        echo $formExibeAluno->inputLabel('E-mail',$infoPessoa->getEmail());
        echo "<br />";
        echo "<br />";
        
        echo "<p align='center'><input id='button1' name='escolhe_aba' type='button' value='Editar Contato' onclick='javascript:editar(2);' /></p>";
        
    ?>
</fieldset>

<!--//  FIM DA EGUNDA PARTE DO FORMULARIO  -->