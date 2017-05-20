<!--// QUARTA PARTE DO FORMULARIO  -->

<fieldset id="fieldsetGeral">
   <?php
    
        echo "<legend>DOCUMENTOS<br/>" . $infoPessoa->getNome() ." </legend>";

        // rg
        $rg = $infoAluno->getRgNumero() . '  &Oacute;rg&atilde;o Emissor: ' . $infoAluno->getRgOrgaoEmissor();
        echo $formExibeAluno->inputLabel('R.G. Aluno',$rg);
        echo "<br />";
        
        // data de emissao
        echo $formExibeAluno->inputLabel('Dt. Emiss&atilde;o',Util::dataSQLParaBr($infoAluno->getRgDataEmissao()));
        echo "<br />";
        
        // cpf
        echo $formExibeAluno->inputLabel('CPF',$infoAluno->getCpf());
        echo '<br />';
        
        // cpf próprio
        echo $formExibeAluno->inputLabel('CPF Próprio',$infoAluno->getCpfProprio());
        echo '<br />';

        // Cert. Nascimento
        $certNasc =   $infoAluno->getCertidaoNascimentoNumero() . ' Livro: ' . $infoAluno->getCertidaoNascimentoLivro() . ' Folha: ' . $infoAluno->getCertidaoNascimentoFolha();       
        echo $formExibeAluno->inputLabel('Cert. Nascimento',$certNasc);
        echo "<br />";
        
        // cidade
        echo $formExibeAluno->inputLabel('Cidade',$infoAluno->getCertidaoNascimentoCidade());
        echo "<br />";
        
        // cidade sub distrito
        echo $formExibeAluno->inputLabel('Sub-Distrito',$infoAluno->getCertidaoNascimentoSubDistrito());
        echo "<br />";
        
        // uf
        echo $formExibeAluno->inputLabel('UF',$infoAluno->getCertidaoNascimentoUF());
        echo '<br /><br />';
        
        // Cert casamento
        $certCas =   $infoAluno->getCertidaoCasamentoNumero() . ' Livro: ' . $infoAluno->getCertidaoCasamentoLivro() . ' Folha: ' . $infoAluno->getCertidaoCasamentoFolha();       
        echo $formExibeAluno->inputLabel('Cert. Casamento',$certCas);
        echo "<br />";
        
        // Cert casamento cidade
        echo $formExibeAluno->inputLabel('Cidade',$infoAluno->getCertidaoCasamentoCidade());
        echo "<br />";
        
        // Cert casamento cidade sub distrito
        echo $formExibeAluno->inputLabel('Sub-Distrito',$infoAluno->getCertidaoCasamentoSubDistrito());
        echo "<br />";

        // Cert casamento uf
        echo $formExibeAluno->inputLabel('UF',$infoAluno->getCertidaoCasamentoUF());
        echo '<br /><br />';
        
        // cert alistamento militar
        // Cert. Alistamento Militar
        $certificadoAlistamentoMilitar = $infoAluno->getCertificadoAlistamentoMilitarNumero() . ' S&eacute;rie: ' . $infoAluno->getCertificadoAlistamentoMilitarSerie();
        echo $formExibeAluno->inputLabel('Cert. Alistamento Militar',$certificadoAlistamentoMilitar);
        echo "<br />";
        
        // Data
        echo $formExibeAluno->inputLabel('Data',Util::dataSQLParaBr($infoAluno->getCertificadoAlistamentoMilitarData()));
        echo "<br />";
        
        // RM
        echo $formExibeAluno->inputLabel('RM',$infoAluno->getCertificadoAlistamentoMilitarRM());
        echo "<br />";
        
        // CSM
        echo $formExibeAluno->inputLabel('CSM',$infoAluno->getCertificadoAlistamentoMilitarCSM());
        echo "<br />";
            
        echo '<br />';
        
        // cert reservista
        $certificadoReservista = $infoAluno->getCertificadoReservistaNumero() . ' S&eacute;rie: ' . $infoAluno->getCertificadoReservistaSerie();
        echo $formExibeAluno->inputLabel('Cert. Reservista',$certificadoReservista);
        echo "<br />";
        
        // Data
        echo $formExibeAluno->inputLabel('Data',Util::dataSQLParaBr($infoAluno->getCertificadoReservistaData()));
        echo "<br />";
        
        // Categoria
        echo $formExibeAluno->inputLabel('Categoria',$infoAluno->getCertificadoReservistaCAT());
        echo "<br />";
        
        // RM
        echo $formExibeAluno->inputLabel('RM',$infoAluno->getCertificadoReservistaRM());
        echo "<br />";
        
        // CSM
        echo $formExibeAluno->inputLabel('CSM',$infoAluno->getCertificadoReservistaCSM());
        echo "<br />";
            
        echo '<br />';
        
        
        // Titulo eleitor
        echo $formExibeAluno->inputLabel('T&iacute;tulo de Eleitor',$infoAluno->getTituloEleitorNumero());
        echo "<br />";
        
        // Titulo eleitor Data
        echo $formExibeAluno->inputLabel('Emiss&atilde;o',Util::dataSQLParaBr($infoAluno->getTituloEleitorData()));
        echo "<br />";
        
        // Titulo eleitor Zona 
        echo $formExibeAluno->inputLabel('Zona Eleitoral',$infoAluno->getTituloEleitorZona());
        echo "<br />";
        
        // Titulo eleitor seçao 
        echo $formExibeAluno->inputLabel('Se&ccedil;&atilde;o',$infoAluno->getTituloEleitorSecao());
        echo "<br /><br />";
        
        // CTPS
        echo $formExibeAluno->inputLabel('Carteira de Trabalho',$infoAluno->getCtps());
        echo "<br />";
        echo "<br />";
        
        echo "<p align='center'><input id='button1' type='button' value='Editar Documentos' onclick='javascript:editar(4);' /></p>";
                
    ?>        
</fieldset>

<!--//  FIM DA QUARTA PARTE DO FORMULARIO  -->