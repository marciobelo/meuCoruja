<script type="text/javascript">
    function gerarMatriculaProvisoria() {
        $.post("/coruja/baseCoruja/controle/gerarMatriculaProvisoria.php",
                {acao: "gerarMatriculaProvisoria"},
                function(xml) {
                    $("#novaMatriculaAluno").val($("matriculaAlunoProvisoria",xml).text());
                });
    }
</script>
<!--// FORMULARIO DE MATRÍCULA - ALUNO NOVO   -->
<?php
    include_once "$BASE_DIR/baseCoruja/classes/manter_aluno_mat_docs.php"; 

    $dataMatricula = date("Y-m-d"); 
    $hoje = date("d/m/Y"); 
    
    echo "<fieldset id='fieldsetGeral'>";
        echo "<input type='hidden' name='tipo' id='tipo' value='$tipo' />";
        echo "<input type='hidden' name='dataMatricula' id='dataMatricula' value='$dataMatricula' />";
        echo "<input type='hidden' name='situacaoMatricula' id='situacaoMatricula' value='CURSANDO' />";

        echo "<legend>MATR&Iacute;CULA</legend>";
        
        echo '<font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />';
         
        $formNovoAluno = new formulario();
        
        // Matricula
        echo $formNovoAluno->inputLabel('Matr&iacute;cula(*)');
        echo '<table border="0">';
        echo '<tr>';
        echo '<td>';
        echo $formNovoAluno->inputText('novaMatriculaAluno','',$formAluno->novaMatriculaAluno,'15','','','this.value=this.value.toUpperCase();');
        echo '</td>';
        echo '<td>';
        echo "&nbsp;<font color='red'>ATEN&Ccedil;&Atilde;O: COLOQUE COM CUIDADO ESSA INFORMA&Ccedil;&Atilde;O. USE APENAS N&Uacute;MEROS.</font>";
        echo '</td>';
        echo '</tr>';
//        echo '<tr>';
//        echo '<td>';
//        echo '<input type="button" value="Criar Provisória" onclick="javascript:gerarMatriculaProvisoria();" />';
//        echo '</td>';
//        echo '<td>';
//        echo "&nbsp;<font color='red'>Evite o uso de matr&iacute;cula provis&oacute;ria. Se usar, n&atilde;o se esque&ccedil;a de atualizar assim que chegar a definitiva.</font>";
//        echo '</td>';
//        echo '</tr>';
        echo '</table>';
//        echo '<br />';

        //data atual
        echo $formNovoAluno->inputLabel('Data Matr&iacute;cula(*)');
        echo $formNovoAluno->inputText('dataNovaMatriculaD','',$formAluno->dataNovaMatriculaD,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('dataNovaMatriculaM','',$formAluno->dataNovaMatriculaM,'2','2','','');
        echo '/';
        echo $formNovoAluno->inputText('dataNovaMatriculaA','',$formAluno->dataNovaMatriculaA,'4','4','','');
        echo '<br />';
         
        // Define em qual curso a matricula será feita
        echo $formNovoAluno->inputLabel('Curso(*)');
        echo "<select name='siglaCursoNovaMatricula' id='siglaCursoNovaMatricula' size='1' onchange='javascript:mudaCurso(this);'>\n";
        echo "<option value=''>-\n";
        foreach($formAluno->cursos as $curso) {
            $selecionado = $curso->getSiglaCurso()==$formAluno->siglaCursoNovaMatricula ? "selected" : "";
            echo "<option value='" . $curso->getSiglaCurso() . "' " . $selecionado . ">" . $curso->getSiglaCurso() . " - " . $curso->getNomeCurso() . "\n";
        }
        echo "</select>\n";
        echo '<br /><br />';

        // Turno de ingresso
        echo $formNovoAluno->inputLabel('Turno de Ingresso(*)');
        echo $formNovoAluno->inputSelectEnum('turnoIngressoNovaMatricula','MatriculaAluno','turnoIngresso',$formAluno->turnoIngressoNovaMatricula);
        echo '<br /><br />';

        echo $formNovoAluno->inputLabel('<b>CONCURSO</b>');
        echo '<br />';

        // Forma de ingresso
        echo $formNovoAluno->inputLabel('Forma de Ingresso(*)');
        echo "<select name='idFormaIngressoNovaMatricula' id='idFormaIngressoNovaMatricula' size='1'>\n";
        echo "<option value=''>-";
        foreach($formAluno->formasIngresso as $formaIngresso) {
            $selecionado = $formaIngresso->getIdFormaIngresso()==$formAluno->idFormaIngressoNovaMatricula ? "selected" : "";
            echo "<option value='" . $formaIngresso->getIdFormaIngresso() . "' " . $selecionado . ">" . $formaIngresso->getDescricao() . "\n";
        }
        echo "</select>\n";
        echo '<br /><br />';

        // pontuação
        echo $formNovoAluno->inputLabel('Pontua&ccedil;&atilde;o');
        echo $formNovoAluno->inputText('concursoPontosNovaMatricula','',$formAluno->concursoPontosNovaMatricula,'10','','    Se houver decimais, use ponto para separar.','');
        echo "(use v&iacute;rgula como separador decimal. P.ex: 50,35)";
        echo '<br />';
        echo $formNovoAluno->inputLabel('Classifica&ccedil;&atilde;o');
        echo $formNovoAluno->inputText('concursoClassificacaoNovaMatricula','',$formAluno->concursoClassificacaoNovaMatricula,'3','','','');
        echo '<br />';

        echo "</fieldset>";
?>

<!--//  FIM DO FORMULARIO DE MATRÍCULA - ALUNO NOVO   -->