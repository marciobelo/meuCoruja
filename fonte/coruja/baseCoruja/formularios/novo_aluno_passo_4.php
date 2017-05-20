<!--// QUARTA PARTE DO FORMULARIO DE ALUNO NOVO   -->

<fieldset id="fieldsetGeral">
    <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
    
    <legend>DOCUMENTOS</legend>
    
    <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
        
    <?php
        $formNovoAluno = new formulario();
        
        // RG
        echo $formNovoAluno->inputLabel('RG Aluno(*)');
        echo $formNovoAluno->inputText('rgNumero','',$formAluno->rgNumero,'12','20','','');
        echo '&nbsp;&nbsp;&Oacute;rg&atilde;o Emissor :(*)';
        echo "<input type='text' name='rgOrgaoEmissor' id='rgOrgaoEmissor' value='$formAluno->rgOrgaoEmissor' size='5' maxlength='20' style='text-transform: uppercase;' onchange='this.value=this.value.toUpperCase();' />";
        echo '<br />';
    
        // RG - data emissão
        echo $formNovoAluno->inputLabel('Emiss&atilde;o(*)');
        echo $formNovoAluno->inputText('rgDataEmissaoD','',$formAluno->rgDataEmissaoD,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('rgDataEmissaoM','',$formAluno->rgDataEmissaoM,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('rgDataEmissaoA','',$formAluno->rgDataEmissaoA,'4','4','','');
        echo '<br />';
    
        // CPF
        echo $formNovoAluno->inputLabel('CPF(*)');
        echo $formNovoAluno->inputText('cpf','',$formAluno->cpf,'12','12','','');
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pr&oacute;prio: ';
        echo $formNovoAluno->inputRadio('cpfProprio',array('SIM'=>'SIM','NÃO'=>'NÃO'),$formAluno->cpfProprio,'');
        echo '<br /><br />';
         
        // certidão nascimento
        echo $formNovoAluno->inputLabel('Cert. Nascimento');
        echo $formNovoAluno->inputText('certidaoNascimentoNumero','',$formAluno->certidaoNascimentoNumero,'12','20','','');
        echo '&nbsp;&nbsp; Livro:';
        echo $formNovoAluno->inputText('certidaoNascimentoLivro','',$formAluno->certidaoNascimentoLivro,'4','10','','');
        echo '&nbsp;&nbsp; Folha:';
        echo $formNovoAluno->inputText('certidaoNascimentoFolha','',$formAluno->certidaoNascimentoFolha,'4','10','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Cidade');
        echo $formNovoAluno->inputText('certidaoNascimentoCidade','',$formAluno->certidaoNascimentoCidade,'20','40','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Sub-Distrito');
        echo $formNovoAluno->inputText('certidaoNascimentoSubDistrito','',$formAluno->certidaoNascimentoSubDistrito,'20','40','','');
        echo '&nbsp;&nbsp; UF';
        echo $formNovoAluno->inputSelect('certidaoNascimentoUF',array('AC'=>'AC','AL'=>'AL','AM'=>'AM','AP'=>'AP','BA'=>'BA','CE'=>'CE','DF'=>'DF','ES'=>'ES','GO'=>'GO','MA'=>'MA','MG'=>'MG','MS'=>'MS','MT'=>'MT','PA'=>'PA','PB'=>'PB','PE'=>'PE','PI'=>'PI','PR'=>'PR','RJ'=>'RJ','RN'=>'RN','RO'=>'RO','RR'=>'RR','RS'=>'RS','SC'=>'SC','SE'=>'SE','SP'=>'SP','TO'=>'TO'),$formAluno->certidaoNascimentoUF,'');
        echo '<br /><br />';
    
        // certidão casamento
        echo $formNovoAluno->inputLabel('Cert. Casamento');
        echo $formNovoAluno->inputText('certidaoCasamentoNumero','',$formAluno->certidaoCasamentoNumero,'12','20','','');
        echo '&nbsp;&nbsp; Livro:';
        echo $formNovoAluno->inputText('certidaoCasamentoLivro','',$formAluno->certidaoCasamentoLivro,'4','10','','');
        echo '&nbsp;&nbsp; Folha:';
        echo $formNovoAluno->inputText('certidaoCasamentoFolha','',$formAluno->certidaoCasamentoFolha,'4','10','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Cidade');
        echo $formNovoAluno->inputText('certidaoCasamentoCidade','',$formAluno->certidaoCasamentoCidade,'20','40','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Sub-Distrito');
        echo $formNovoAluno->inputText('certidaoCasamentoSubDistrito','',$formAluno->certidaoCasamentoSubDistrito,'20','40','','');
        echo '&nbsp;&nbsp; UF';
        echo $formNovoAluno->inputSelect('certidaoCasamentoUF',array('AC'=>'AC','AL'=>'AL','AM'=>'AM','AP'=>'AP','BA'=>'BA','CE'=>'CE','DF'=>'DF','ES'=>'ES','GO'=>'GO','MA'=>'MA','MG'=>'MG','MS'=>'MS','MT'=>'MT','PA'=>'PA','PB'=>'PB','PE'=>'PE','PI'=>'PI','PR'=>'PR','RJ'=>'RJ','RN'=>'RN','RO'=>'RO','RR'=>'RR','RS'=>'RS','SC'=>'SC','SE'=>'SE','SP'=>'SP','TO'=>'TO'),$formAluno->certidaoCasamentoUF,'');
        echo '<br /><br />';
    
        // certificado alistamento militar
        echo $formNovoAluno->inputLabel('Cert. Alistamento Militar');
        echo $formNovoAluno->inputText('certificadoAlistamentoMilitarNumero','',$formAluno->certificadoAlistamentoMilitarNumero,'12','20','','');
        echo '&nbsp;&nbsp; S&eacute;rie:';
        echo $formNovoAluno->inputText('certificadoAlistamentoMilitarSerie','',$formAluno->certificadoAlistamentoMilitarSerie,'6','10','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('RM');
        echo $formNovoAluno->inputText('certificadoAlistamentoMilitarRM','',$formAluno->certificadoAlistamentoMilitarRM,'8','10','','');
        echo '&nbsp;&nbsp; CSM:';
        echo $formNovoAluno->inputText('certificadoAlistamentoMilitarCSM','',$formAluno->certificadoAlistamentoMilitarCSM,'8','10','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Data');
        echo $formNovoAluno->inputText('certificadoAlistamentoMilitarDataD','',$formAluno->certificadoAlistamentoMilitarDataD,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('certificadoAlistamentoMilitarDataM','',$formAluno->certificadoAlistamentoMilitarDataM,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('certificadoAlistamentoMilitarDataA','',$formAluno->certificadoAlistamentoMilitarDataA,'4','4','','');
        echo '<br /><br />';
    
        // certificado reservista
        echo $formNovoAluno->inputLabel('Cert. Reservista');
        echo $formNovoAluno->inputText('certificadoReservistaNumero','',$formAluno->certificadoReservistaNumero,'12','20','','');
        echo '&nbsp;&nbsp; S&eacute;rie:';
        echo $formNovoAluno->inputText('certificadoReservistaSerie','',$formAluno->certificadoReservistaSerie,'6','10','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Categoria');
        echo $formNovoAluno->inputText('certificadoReservistaCAT','',$formAluno->certificadoReservistaCAT,'12','10','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('RM');
        echo $formNovoAluno->inputText('certificadoReservistaRM','',$formAluno->certificadoReservistaRM,'8','10','','');
        echo '&nbsp;&nbsp; CSM:';
        echo $formNovoAluno->inputText('certificadoReservistaCSM','',$formAluno->certificadoReservistaCSM,'8','10','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Data');
        echo $formNovoAluno->inputText('certificadoReservistaDataD','',$formAluno->certificadoReservistaDataD,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('certificadoReservistaDataM','',$formAluno->certificadoReservistaDataM,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('certificadoReservistaDataA','',$formAluno->certificadoReservistaDataA,'4','4','','');
        echo '<br /><br />';
         
        // titulo de eleitor
        echo $formNovoAluno->inputLabel('T&iacute;tulo de Eleitor');
        echo $formNovoAluno->inputText('tituloEleitorNumero','',$formAluno->tituloEleitorNumero,'10','20','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Zona Eleitoral');
        echo $formNovoAluno->inputText('tituloEleitorZona','',$formAluno->tituloEleitorZona,'4','10','','');
        echo '&nbsp;&nbsp;Se&ccedil;&atilde;o:';
        echo $formNovoAluno->inputText('tituloEleitorSecao','',$formAluno->tituloEleitorSecao,'4','10','','');
        echo '<br />';
        echo $formNovoAluno->inputLabel('Emiss&atilde;o');
        echo $formNovoAluno->inputText('tituloEleitorDataD','',$formAluno->tituloEleitorDataD,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('tituloEleitorDataM','',$formAluno->tituloEleitorDataM,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('tituloEleitorDataA','',$formAluno->tituloEleitorDataA,'4','4','','');
        echo '<br /><br />';
    
        // Carteira de Trabalho
        echo $formNovoAluno->inputLabel('Carteira de Trabalho');
        echo $formNovoAluno->inputText('ctps','',$formAluno->ctps,'16','30','','');
        echo "<span>&nbsp;Ex. 2021193 Série 123</span>";
        echo '<br />';
    
    ?>
    
</fieldset>

<!--//  FIM DA QUARTA PARTE DO FORMULARIO DE ALUNO NOVO  -->