<?php
    require_once "$BASE_DIR/includes/topo.php";
    require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<script type="text/javascript" src="/coruja/javascript/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/coruja/javascript/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="listaMatrizCurricularProposta.css">
<link rel="stylesheet" type="text/css" href="../font-awesome/css/font-awesome.min.css">

<form method="post" id="listaMatrizCurricularProstaForm" action="matrizCurricularProposta_controle.php">
    <input type="hidden" name="acao" id="acao"/>
    <input type="hidden" name="idMatriz" id="idMatriz"/>
    <input type="hidden" name="siglaCurso" id="siglaCurso"/>
</form>
    <div id="mensagem"></div></br>
    <fieldset class="fieldSet">
        <legend>Equival&ecirc;ncia de Matriz Curricular Propostas</legend>
        <?php
            if ($matrizesToView) {
        ?>
                <table>
                    <tr>
                      <th width="150px">Sigla Do Curso</th>
                    </tr>
        <?php
                forEach($matrizesToView as $matriz) {
                    $siglaCurso = $matriz->getSiglaCurso();
                    $idMatriz = $matriz->getIdMatriz();
                    $idIconeCreate = "create" . $siglaCurso;
                    $idIconeEdit = "edit" . $siglaCurso;
                    $idIconeDelete = "delete" . $siglaCurso;
        ?>
                    <tr>
                        <td align='center' id="tdLista"><?php echo $siglaCurso ?></td>
                        <td class="tdLista">
                            
                            <input type="button" class="btn fa-input" value="&#xf067" id="<?php echo $idIconeCreate ?>"  aria-hidden="true" onclick="gerenciarMatrizProposta('<?php echo $siglaCurso ?>', '<?php echo $idMatriz ?>')"/>
                            <input type="button" class="btn fa-input" value="&#xf044" id="<?php echo $idIconeEdit ?>"  aria-hidden="true" onclick="gerenciarMatrizProposta('<?php echo $siglaCurso ?>', '<?php echo $idMatriz ?>')"/>
                            <input type="button" class="btn fa-input" value="&#xf1f8" id="<?php echo $idIconeDelete ?>"  aria-hidden="true" onclick="gerenciarMatrizProposta('<?php echo $siglaCurso ?>', '<?php echo $idMatriz ?>', 'excluir')"/>
                        </td>
                    </tr>
        <?php
                }
           }
        ?>
        </table>
        <input type="hidden" value="<?php echo $mensagemValidacao ?>" id="mensagemValidacao">
    </fieldset>
<script>
    
    function gerenciarMatrizProposta(siglaCurso, idMatriz, acao='') {
        if( acao === 'excluir') {
            if(!confirm("Deseja realmente excluir a Matriz Curricular Equivalente ao curso " + siglaCurso + " ?")){
                return false;
            }
        }
        
        $("#acao").val(acao);
        $("#siglaCurso").val(siglaCurso);
        $("#idMatriz").val(idMatriz);
        
        $('#listaMatrizCurricularProstaForm').submit();
    }
    
    function configuraClasses() {
        var siglaMatrizesExistentes = <?php echo json_encode($siglaMatrizesExistentes); ?>;
        var siglaTodasMatrizes = <?php echo json_encode($siglaTodasMatrizes); ?>;
    
        for (idx in siglaTodasMatrizes) {
            var sigla = siglaTodasMatrizes[idx];

            if (siglaMatrizesExistentes.indexOf(sigla) === -1 ) {
                $('#edit' + sigla).attr("disabled","disabled");
                $('#delete' + sigla).attr("disabled","disabled");;
            } else {
                $('#create' + sigla).attr("disabled","disabled");;
            }
        }
    }
    
    function notificaValidacaoMatrizProposta() {
        var mensagem = $('#mensagemValidacao').val();
        
        if(mensagem.length > 0) {
            console.log('aaa');
            $('#mensagem').append(mensagem);
            $('#mensagem').show();
        }
    }
    
    $(document).ready(function() {
        configuraClasses();
        notificaValidacaoMatrizProposta();
        
    })
    
</script>    