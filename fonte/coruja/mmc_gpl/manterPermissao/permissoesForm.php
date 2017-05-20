<?php
    require_once "$BASE_DIR/includes/topo.php";
    require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript" src="/coruja/javascript/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/coruja/javascript/jquery-ui.min.js"></script>
<script src="/coruja/javascript/toastr.min.js"></script>
<link href="/coruja/estilos/toastr.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="permissoesForm.css">
<link rel="stylesheet" type="text/css" href="../font-awesome/css/font-awesome.min.css">
 
<fieldset class="fieldSet" id="fieldsetPermissoes">
    <legend>Permiss&otilde;es de <?php echo $nomePessoa?></legend>
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
                        <td><input type="checkbox" onchange="mudarEstado('<?php echo $funcao->getIdCasoUso()?>' ,'<?php echo $funcao->getDescricao()?>')" id="<?php echo $funcao->getIdCasoUso()?>" name="<?php echo $funcao->getIdCasoUso()?>" checked><br/></td>     
            <?php       
                    } else {
            ?>
                        <td><input type="checkbox" onchange="mudarEstado('<?php echo $funcao->getIdCasoUso()?>' ,'<?php echo $funcao->getDescricao()?>')" id="<?php echo $funcao->getIdCasoUso()?>" name="<?php echo $funcao->getIdCasoUso()?>" ><br/></td>
            <?php
                    }
            ?>
                </tr>
            <?php
                }
            ?>

    </table>
</fieldset>
<fieldset class="fieldSet" id="fieldsetGrupos" >
    <legend>Grupos de Permiss&otilde;es de <?php echo $nomePessoa?></legend>
    <table>   
        <?php
            if ($gruposToView) {
        ?>
                <tr>
                <th width="150px">Grupo</th>
                <th ></th>
                </tr>
        <?php
                foreach($gruposToView as $grupo) {
        ?>           
                    <tr class="tdLista">
                        <td align="center"><?php echo $grupo->getNome()?></td>
                        <td>
        <?php
                    if ( in_array($grupo->getId(), $gruposToCheck) ) {
        ?>          
                            <input type="checkbox" onchange="mudarEstadoGrupo('<?php echo $grupo->getId()?>', '<?php echo $grupo->getNome()?>', '<?php echo implode(',', $permissoes)?>')" id="<?php echo $grupo->getNome()?>" name="<?php echo $grupo->getNome()?>" checked>
        <?php
                    } else {
        ?>    
                            <input type="checkbox" onchange="mudarEstadoGrupo('<?php echo $grupo->getId()?>', '<?php echo $grupo->getNome()?>', '<?php echo implode(',', $permissoes)?>')" id="<?php echo $grupo->getNome()?>" name="<?php echo $grupo->getNome()?>">
        <?php
                    }
        ?>
                        <input type="button" class="btn fa-input" value="&#xf03a" aria-hidden="true" onclick="exibePermissoesGrupo('<?php echo $grupo->getNome()?>', '<?php echo implode('<br />', $funcoesDosGruposComDescricao[$grupo->getId()])?>')"/>
                        <input type='hidden' class='permissoesDosGrupos' name="<?php echo $grupo->getNome()?>"  value='<?php echo implode(',', $funcoesDosGrupos[$grupo->getId()])?>'>
                    </td>
        <?php
                }
        ?>
                           
        <?php                
            } else {
        ?>
            <p style="text-align:center">N&atildeo ha Grupos</p>
        <?php
            }
            
        ?> 
    </table>
</fieldset>
<div id="dialog-permissoes"></div>
<script>
    function mudarEstado(idPermissao, descPermissao) {
        var isChecked = document.getElementsByName(idPermissao)[0].checked; 
        var acao      = isChecked ? "atribuir" : "remover";
        var idPessoa  = '<?php echo $idPessoa?>';
        
        if(acao == "atribuir") {            
            atribuirPermissao(idPermissao, descPermissao, idPessoa);
            bloquearAcoes();
        } else {
            removerPermissao(idPermissao, descPermissao, idPessoa);
            bloquearAcoes();
        }
    }
    
    function atribuirPermissao(idPermissao, descPermissao, idPessoa) {
        var message;
        $.ajax({
            type: "POST",
            url: "alterarPermissoes_controle.php",
            data: {acao: "Atribuir", idPessoa: idPessoa, idPermissao: idPermissao, descPermissao: descPermissao},
        })
        .done(function(resp){
            if (resp == 'Sucesso') {
                message = "Permiss&atilde;o: " + descPermissao + " atribuida com sucesso";
                verificarCheckboxGrupos();
                toastr.success(message);
            } else if (resp == "Sem Permissao") {
                window.location.assign("/coruja/baseCoruja/formularios/sem_permissao.php");
            } else {
                message = "Erro ao atribuir permiss&atilde;o: " + descPermissao;
                toastr.error(message);
            }
        })
        .fail(function(error){
            console.log("Ocorreu o seguinte erro no servidor: " + error);
        });
    }
    
    function bloquearAcoes() {
        $('#fieldsetPermissoes').addClass('fieldsetInativo');
        $('#fieldsetGrupos').addClass('fieldsetInativo');
        $(":checkbox").attr('disabled', true);
        
        setTimeout(function() {
            $('#fieldsetPermissoes').removeClass('fieldsetInativo');
            $('#fieldsetGrupos').removeClass('fieldsetInativo');
            
            $('#fieldsetPermissoes').addClass('fieldsetAtivo');
            $('#fieldsetGrupos').addClass('fieldsetAtivo');
            
            $(":checkbox").attr('disabled', false);
        }, 1000);
    }
    
    function removerPermissao(idPermissao, descPermissao, idPessoa) {
        var message;
        
        $.ajax({
            type: "POST",
            url: "alterarPermissoes_controle.php",
            data: {acao: "Remover", idPessoa: idPessoa, idPermissao: idPermissao, descPermissao: descPermissao},
        })
        .done(function(resp){
            if (resp == 'Sucesso') {
                message = "Permiss&atilde;o: " + descPermissao + " removida com sucesso";
                verificarCheckboxGrupos();
                toastr.success(message);
            } else if(resp == "Sem Permissao"){
                window.location.assign("/coruja/baseCoruja/formularios/sem_permissao.php");
            } else {
                message = "Erro ao remover permiss&atilde;o: " + descPermissao;
                toastr.error(message);
            }            
        })
        .fail(function(error){
            console.log("Ocorreu o seguinte erro no servidor: " + error);
        });
    }
    
    function mudarEstadoGrupo(idGrupo, nameGrupo) {
        var isChecked  = document.getElementsByName(nameGrupo)[0].checked; 
        var acao       = isChecked ? "Atribuir" : "remover";
        var idPessoa   = '<?php echo $idPessoa?>';
        var permissoes = document.getElementsByName(nameGrupo)[1].value; 
        
        if(acao == "Atribuir") {            
            atribuirGrupo(idGrupo, nameGrupo, permissoes, idPessoa);
            bloquearAcoes();
        } else {
            removerGrupo(idGrupo, nameGrupo, permissoes, idPessoa);
            bloquearAcoes();
        }
    }
    
    function atribuirGrupo(idGrupo, nameGrupo, permissoes, idPessoa) {
        var message;
        
        $.ajax({
            type: "POST",
            url:  "alterarGrupoPermissoes_controle.php",
            data: {acao: "Atribuir", idGrupo: idGrupo, nomeGrupo: nameGrupo, idPessoa: idPessoa},
        })
        .done(function(resp){
            if (resp == 'Sucesso') {
                message = "Grupo: " + nameGrupo + " atribuido com sucesso";
                mudarCheckboxPermissoes('Atribuir', permissoes);
                verificarCheckboxGrupos();
                toastr.success(message);
            } else if (resp == "Sem Permissao") {
                window.location.assign("/coruja/baseCoruja/formularios/sem_permissao.php");
            } else {
                message = "Falha ao atribuir Grupo: " + nameGrupo;
                toastr.error(message);
            }
        })
        .fail(function(error){
            console.log("Ocorreu o seguinte erro no servidor: " + error);
        });
    }
    
    function removerGrupo(idGrupo, nameGrupo, permissoes, idPessoa) {
        var message;

        $.ajax({
            type: "POST",
            url:  "alterarGrupoPermissoes_controle.php",
            data: {acao: "Remover", idGrupo: idGrupo, nameGrupo: nameGrupo, idPessoa: idPessoa},
        })
        .done(function(resp){
            if (resp == 'Sucesso') {
                message = "Grupo: " + nameGrupo + " removido com sucesso";
                mudarCheckboxPermissoes('Remover', permissoes);
                verificarCheckboxGrupos();
                toastr.success(message);
            } else if (resp == "Sem Permissao") {
                window.location.assign("/coruja/baseCoruja/formularios/sem_permissao.php");
            } else {
                message = "Falha ao remover Grupo: " + nameGrupo;
                toastr.error(message);
            }
        })
        .fail(function(error){
            console.log("Ocorreu o seguinte erro no servidor: " + error);
        });
    }
    
    function mudarCheckboxPermissoes(acao, permissoes) {
        permissoes  = permissoes.split(',');
        var checked = (acao == 'Atribuir') ? true : false;
        
        for (idx in permissoes) {
            checkbox         = document.getElementById(permissoes[idx]);
            checkbox.checked = checked;
        }
    }
    
    function verificarCheckboxGrupos() {
        var grupos  = $('.permissoesDosGrupos');
        for (id in grupos) {
            if (!isNaN(id)) {
                permissoes = grupos[id].value.split(',');
                for(idx in permissoes) {
                    if(!document.getElementById(permissoes[idx]).checked) {                        
                        document.getElementById(grupos[id].name).checked = false; 
                        break;
                    } else {
                        document.getElementById(grupos[id].name).checked = true;
                    }
                }
            }
        }
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
    
    $(document).ready(function() {
        toastr.options = {
            "positionClass": "toast-top-center",
            "timeOut": "5000",
        };
    });
</script>