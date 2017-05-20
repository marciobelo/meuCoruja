<?php
    require_once "$BASE_DIR/includes/topo.php";
    require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript" src="/coruja/javascript/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/coruja/javascript/jquery-ui.min.js"></script>
<script src="/coruja/javascript/toastr.min.js"></script>
<link href="/coruja/estilos/toastr.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="permissoesForm.css">
   
    <fieldset class="fieldSet">
        <legend>Novo Grupo de Permiss&otilde;es</legend>
        <input type="button" onclick="goToList()" value="Lista de Grupo" ><br /><br />
        <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
        <form method="post" id="grupoForm" action="grupoPermissoes_controle.php" onsubmit="return validaForm()">
            <table>   
                <tr>
                    <td>
                      Nome(*) :&nbsp;
                        <input type="text" size="55" name="nomeDoNovoGrupo" id="nomeDoNovoGrupo"> &nbsp;
                        <input type="submit" value="Salvar">
                        <input type="hidden" name="acao" value="salvar">
                        <input type="hidden" id="permissoesDoNovoGrupo" name="permissoesDoNovoGrupo" value="">
                    </td>
                </tr>
            </table>
            <br/>
            <div align="center">Permissões do Grupo(*) :</div>
            <br/>
            <table>
                <tr>
                  <th width="150px">C&oacute;digo</th>
                  <th width="300px">Fun&ccedil;&atilde;o</th>
                </tr>
                <?php
                    foreach($funcoesToView as $val) {
                        $id      = $val->getIdCasoUso();
                        $desc    = $val->getDescricao();
                ?>           
                        <tr>
                            <td align="center"><?php echo $id?></td>
                            <td align="center"><?php echo $desc?></td>
                            <td><input type="checkbox" name="permissao" id="<?php echo $id?>" name="<?php echo $id?>" ><br/></td>    
                        </tr>  
                <?php
                    }
                ?> 
            </table>
        </form>
    </fieldset>
<script>    
    
    function setaPermissoes() {
        var permissoes      = $("input[name='permissao']");
        var arrayPermissoes = [];
        var i = 0;
        
        for (id in permissoes) {
            if(permissoes[id].checked) {
              arrayPermissoes[i] = permissoes[id].id;  
              i++;
            } 
        }
        
        if(arrayPermissoes.length == 0) {
            return false;
        } else {
            $('#permissoesDoNovoGrupo').val(arrayPermissoes.join());
            return true;
        }
    }
    
    function validaForm() {
        var gruposExistentes = '<?php echo $nomeGruposExistentes ?>';
        gruposExistentes = gruposExistentes.split(',');
        var nomeDoNovoGrupo = $('#nomeDoNovoGrupo').val();        
        
        if (nomeDoNovoGrupo.length == 0) {
            alert("Insira um nome para o Grupo");
            return false;
        } if (!setaPermissoes()){
            alert("Selecione ao menos uma Permiss\u00e3o");
            return false;
        } else if (gruposExistentes.indexOf(nomeDoNovoGrupo) > -1 ) {
            alert("Esse nome de Grupo j\u00e1 est\u00e1 em uso");
            return false;
        }
        
        return true;
    }
    
    function submitForm() {
        $("#grupoForm").submit();
    }
    
    function goToList() {
        window.location = "grupoPermissoes_controle.php?acao=consultar";
    }
</script>