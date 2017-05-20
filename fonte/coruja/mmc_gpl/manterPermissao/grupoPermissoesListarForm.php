<?php
    require_once "$BASE_DIR/includes/topo.php";
    require_once "$BASE_DIR/includes/menu_horizontal.php";
?>


<link rel="stylesheet" type="text/css" href="permissoesForm.css">
<script type="text/javascript" src="/coruja/javascript/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="../font-awesome/css/font-awesome.min.css">

<div id="mensagem"><?php echo $mensagem ?></div>
</div>
<form method="post" id="listarGruposForm" action="grupoPermissoes_controle.php">
    <input type="hidden" name="acao" id="acao"/>
    <input type="hidden" name="idGrupo" id="idGrupo"/>
</form>
<fieldset class="fieldSet">
    <legend>Grupos de Permiss&otilde;es do Sistema</legend>
    <input id="botaoCriar" type="button" onclick="goToCreate();" value="+ Novo Grupo"><br />
        <?php
            if ($gruposDeFuncao) {
        ?>
            <br />
            <table>
                <tr>
                  <th width="200px">Grupo</th>
                </tr>
        <?php
                foreach ($gruposDeFuncao as $grupo) {
                    $nome = $grupo->getNome();
                    $id   = $grupo->getId();
        ?>           
                    <tr>
                        <td align="center" id="tdLista"><?php echo $nome?></td>
                        <td class="tdLista">
                          
                            
                            <input type="button"  class="btn fa-input" value="&#xf03a" aria-hidden="true" onclick="exibePermissoesGrupo('<?php echo $nome?>', '<?php echo implode('<br />', $funcoesDosGruposComDescricao[$id])?>')"/>
                            <input type="button"  class="btn fa-input" value="&#xf044" aria-hidden="true" onclick="submitForm('alterar', '<?php echo $id?>')"/>
                            <input type="button"  class="btn fa-input" value="&#xf1f8" aria-hidden="true" onclick="submitForm('remover', '<?php echo $id?>', '<?php echo $nome?>')"/>
                           

                        </td>
                    </tr>  
        <?php
                }
        ?>
            </table>
        <?php
            } else {
        ?>
                <p style="text-align:center">N&atildeo ha Grupos</p>
        <?php
            }
        ?> 
</fieldset>
<div id="dialog-permissoes"></div>
<script>
    function goToCreate() {
        window.location = "grupoPermissoes_controle.php?acao=incluir";
    }
    
    function exibePermissoesGrupo(nomeGrupo, permissoes) {
        
        $("#dialog-permissoes").prop('title', "Permissões de: " + nomeGrupo);
        $("#dialog-permissoes").html(permissoes);
        
        var componenteCurricularPropostoDialog = $("#dialog-permissoes").dialog({
            autoOpen: false,
            modal: true,
            height: 'auto',
            maxHeight: 400,
            width: 600,
            draggable: false,
            resizable: false,
            overflow: 'auto',
            buttons: [
                {
                  text: "Ok",
                  click: function() {
                    $( this ).dialog( "close" );
                  }

                }
            ]
        });
        
        componenteCurricularPropostoDialog.dialog('open');
    }
    
    function submitForm(acao = '', idGrupo = '', nomeGrupo = '') {
        
        if(acao == 'remover') {
            if(!confirm("Deseja mesmo excluir o grupo de permissões " + nomeGrupo + "?")){
                return false;
            }
        }

        $('#acao').val(acao);        
        $('#idGrupo').val(idGrupo);
        
        $("#listarGruposForm").submit();
    }
</script>    