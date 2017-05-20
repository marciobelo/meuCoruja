<?php
    require_once "$BASE_DIR/includes/topo.php";
    require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<div>
  <fieldset>
    <legend>Impress&atildeo de Matriz Curricular</legend>
        <table>
            <tr>
              <th width="150px">Sigla do Curso</th>
              <th width="150px">Inicio da Vig&ecirc;ncia</th>
              <th></th>
            </tr>
            <?php 
                foreach($cursos as $curso) {
                    $siglaCurso = $curso->getSiglaCurso();
                    $idSelect = "select" . $siglaCurso;
                    
                    echo "<tr>";
                        echo "<td style='text-align:center'>";
                            echo $siglaCurso; 
                        echo "</td>";
                        echo "<td style='text-align:center'>";
                            echo "<select id='$idSelect'>";
                                if (in_array($siglaCurso, $matrizePropostasExistentes)) {
                                    echo "<option value='proposta'> Matriz Proposta </option>";
                                }
                                foreach($matrizesPorSiglaCurso[$siglaCurso] as $matriz) { 
                                    echo "<option value='" . $matriz->getIdMatriz() . "'>" . Util::dataSQLParaBr($matriz->getDataInicioVigencia()) . "</option>";
                                }
                            echo "</select>";
                        echo "</td>";
                        echo "<td>";
                            echo "<input type='button' value='Imprimir' onclick=\"submitForm('$siglaCurso')\">";
                        echo "</td>";
                    echo "</tr>";            
                }
            ?>
        </table>
        <form id="imprimirMatrizForm" action="gerarPDFMatriz_controle.php" method="POST" target="_blank">
            <input type="hidden" name="siglaCurso" id="siglaCurso">
            <input type="hidden" name="idMatriz" id="idMatriz">
            <input type="hidden" name="isProposta" id="isProposta" value="false">
        </form>
    </fieldset>
</div>
<script>
    function submitForm(siglaCurso) {
        var value = $('#select' + siglaCurso).val();
        $('#siglaCurso').val(siglaCurso);
        
        if(value === 'proposta') {
            $('#isProposta').val('true');
            $('#idMatriz').val('');
        } else {
            $('#idMatriz').val(value);
            $('#isProposta').val('false');
        }

        $('#imprimirMatrizForm').submit();
    }
</script>
