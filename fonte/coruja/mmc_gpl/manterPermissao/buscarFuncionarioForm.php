<?php
    require_once "$BASE_DIR/includes/topo.php";
    require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<form id="buscaFunc" method="post" name="buscaFunc" action="buscarFuncionario_controle.php" onsubmit="return validaForm()">
    <fieldset>
        <legend>Buscar funcion&aacute;rio</legend>
        <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
        <br/>
        <div class="row" id="divNomeAcesso">
            <label style="width:25%" for="busca">Busque por Nome ou Nome de Acesso(*) :&nbsp;</label>
            <input name="busca" id="busca" type="text" size="25" maxlength="25" value="<?php echo $busca ?>">
        
            <input type="submit" value="Buscar" />
            <input type="hidden" name="acao" value="buscar" />
        </div>
    </fieldset>
</form>
<?php
    if ($acao == 'buscar') {
        if( !empty($funcionariosToView) ){
?>
            <form id="editarFunc" method="post" name="editarFunc" action="buscarFuncionario_controle.php">        
                <fieldset>
                    <legend>Funcion&aacute;rios encontrados</legend>

                        <input type="hidden" name="idPessoa" value="" />
                        <input type="hidden" name="nomePessoa" value="" />
                        <table>   
                            <tr>
                              <th width="300px">Nome</th>
                              <th width="150px">Nome de Acesso</th>
                            </tr>
                            <?php
                                foreach($funcionariosToView as $funcionario) {
                            ?>           
                                    <tr>
                                        <td align="center"><?php echo $funcionario->getPessoa()->getNome()?></td>
                                        <td align="center"><?php echo $funcionario->getNomeAcesso()?></td>
                                        <td><input type="submit" value="Editar" onclick="setPessoa('<?php echo $funcionario->getPessoa()->getIdPessoa()?>', '<?php echo $funcionario->getPessoa()->getNome()?>')"><br/></td>
                                    </tr>
                                    <input type="hidden" name="acao" value="editar" />
                            <?php
                                }   
                            ?>
                        </table>
                </fieldset>
            </form>
<?php 
        } else {
?>
            <p style="text-align:center">Nenhum resultado encontrado</p>
<?php
        }
    }
?>

<script>
function setPessoa(idPessoa, nomePessoa) {
    document.getElementsByName('idPessoa')[0].value   = idPessoa;
    document.getElementsByName('nomePessoa')[0].value = nomePessoa;
}

function validaForm(){
    var form = document.getElementById("buscaFunc");
    
    if (form.busca.value === "") { 
        alert("O campo da busca precisa ser preenchido");
        form.busca.focus();
        return false;
    }
}
</script>