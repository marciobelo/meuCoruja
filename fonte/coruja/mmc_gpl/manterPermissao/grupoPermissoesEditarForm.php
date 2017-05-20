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
          <legend>Editar Grupo de Permiss&otilde;es</legend>

    <input type="button" onclick="goToList()" value="Lista de Grupo" ><br /><br />
    <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />
    <form method="post" id="EditarGrupoForm" action="grupoPermissoes_controle.php" onsubmit="return validaForm()">

        <table>   
            <tr>
                <td>
                  Nome(*):&nbsp;
                  <input type="text" size="55" name="novoNomeDoGrupo" id="novoNomeDoGrupo" value="<?php echo $nomeDoGrupo?>"> &nbsp;
                    <input type="submit" value="Salvar">
                    <input type="hidden" name="acao" value="salvar">
                    <input type="hidden" name="idGrupo" value="<?php echo $idGrupo?>">
                    <input type="hidden" id="funcoesDoGrupo" name="funcoesDoGrupo" value="">
                    <input type="hidden" id="nomeAntigo" name="nomeAntigo" value="<?php echo $nomeDoGrupo?>">
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
                foreach ($funcoesToView as $funcao) {
            ?> 
                    <tr>
                    <td align="center"><?php echo $funcao->getIdCasoUso()?></td> 
                    <td align="center"><?php echo $funcao->getDescricao()?></td>
            <?php
                    if (in_array($funcao->getIdCasoUso(), $funcoesToCheck)) {
            ?>
                        <td><input type="checkbox" onclick="this.disabled=true; setTimeout( function(this) { this.disabled =false;}, 500);" id="<?php echo $funcao->getIdCasoUso()?>" name="funcao" checked><br/></td>     
            <?php       
                    } else {
            ?>
                        <td><input type="checkbox" onclick="this.disabled=true; setTimeout( function(this) { this.disabled =false;}, 500);" id="<?php echo $funcao->getIdCasoUso()?>" name="funcao" ><br/></td>
            <?php
                    }
            ?>
                </tr>
            <?php
                }
            ?>
        </table>
    </form>
</fieldset>
<script>    
    
    function setaPermissoes() {
        var funcoes = $("input[name='funcao']");
        
        var arrayFuncoes = [];
        var i = 0;

        for (id in funcoes) {
            if(funcoes[id].checked) {
              arrayFuncoes[i] = funcoes[id].id;  
              i++;
            } 
        }
        
        if(arrayFuncoes.length == 0) {
            return false;
        } else {
            $('#funcoesDoGrupo').val(arrayFuncoes.join());
            console.log($('#funcoesDoGrupo').val());
            return true;
        }
    }
    
    function validaForm() {
        var gruposExistentes = '<?php echo $stringGruposExistentes ?>';
        
        var nome = $('#novoNomeDoGrupo');
        
        if (nome.val().length == 0) {
            alert("Insira um nome para o Grupo");
            return false;
        } if (!setaPermissoes()){
            alert("Selecione ao menos uma Permissao");
            return false;
        } else if (gruposExistentes.search( nome.val() ) > -1) {
            alert("Esse nome de Grupo já está em uso");
            return false;
        }
        
        return true;
    }
    
    function submitForm() {
        $("#editarGrupoForm").submit();
    }
    
    function goToList() {
        window.location = "grupoPermissoes_controle.php?acao=consultar";
    }
</script>