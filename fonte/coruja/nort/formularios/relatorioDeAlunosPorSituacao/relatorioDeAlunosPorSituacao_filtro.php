<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
function atualizarPeriodosLetivos() 
{
    var form = document.getElementById("cadastro");
    form.acao.value = "exibirFiltro";
    form.submit();
}
function validarForm() 
{
    var form = document.getElementById("cadastro");
    if( form.siglaCurso.value === "") 
    {
        window.alert("Selecione o curso");
        return false;
    }

    var marcouAlgumTurno = document.getElementById("turnoM").checked ||
                           document.getElementById("turnoT").checked ||
                           document.getElementById("turnoN").checked;
    if( !marcouAlgumTurno) 
    {
        window.alert("Selecione ao menos um turno.");
        return false;
    }

    var marcouAlgumaSituacao = document.getElementById("situacaoCURSANDO").checked ||
                           document.getElementById("situacaoTRANCADO").checked ||
                           document.getElementById("situacaoEVADIDO").checked ||
                           document.getElementById("situacaoCONCLUIDO").checked ||
                           document.getElementById("situacaoDESISTENTE").checked ||
                           document.getElementById("situacaoDESLIGADO").checked;
    if( !marcouAlgumaSituacao) 
    {
        window.alert("Selecione ao menos uma situação de matrícula.");
        return false;
    }
    return true;
}
</script>
<form id="cadastro" method="POST" action="/coruja/nort/controle/emitirRelatorioDeAlunosPorSituacao_controle.php"
      onsubmit="return validarForm();">
    <input type="hidden" id="acao" name="acao" value="mostrarFormatoWeb" />

    <fieldset>
    <legend>Emitir Relat&oacute;rio de Alunos Por Situa&ccedil;&atilde;o</legend>

    <div>
    Curso:
    <select name='siglaCurso' onchange='atualizarPeriodosLetivos()'>
        <option value='' >
        <?php
        foreach($cursos as $curso) 
        {
            echo "<option value='" . $curso->getSiglaCurso() . "'";
            if( $siglaCurso === $curso->getSiglaCurso())
            {
                echo " selected ";
            }
            echo ">" . $curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
            echo "</option>";
        }
        ?>
    </select>
    </div>

    <br/>
    
    <div>
        Intervalo do Per&iacute;odo das Matr&iacute;culas:
        <select name="periodoInicial">
            <?php
                foreach($periodosLetivos as $peridoLetivo) 
                {
                    echo "<option value='" . $peridoLetivo->getSiglaPeriodoLetivo() . "'";
                    if( $periodoInicial === $peridoLetivo->getSiglaPeriodoLetivo())
                    {
                            echo " selected ";
                    }
                    echo ">" . $peridoLetivo->getSiglaPeriodoLetivo();
                    echo "</option>";
                }
            ?>
        </select>
        at&eacute;
        <select name="periodoFinal">
            <?php
                foreach($periodosLetivos as $peridoLetivo) 
                {
                    echo "<option value='" . $peridoLetivo->getSiglaPeriodoLetivo() . "'";
                    if($periodoFinal==$peridoLetivo->getSiglaPeriodoLetivo())
                    {
                            echo " selected ";
                    }
                    echo ">" . $peridoLetivo->getSiglaPeriodoLetivo();
                    echo "</option>";
                }
            ?>
        </select>
    </div>

    <br/>
    
    <div>
        Situa&ccedil;&otilde;es das matriculas:
        <br/>
        <input type="checkbox" name="situacoes[]" value="CURSANDO" id="situacaoCURSANDO" <?php if(in_array("CURSANDO",$situacoesEscolhidas)) { echo "checked=\"checked\""; } ?> />
        Cursando
        <input type="checkbox" name="situacoes[]" value="TRANCADO" id="situacaoTRANCADO" <?php if(in_array("TRANCADO",$situacoesEscolhidas)) { echo "checked=\"checked\""; } ?> />
        Trancado
        <input type="checkbox" name="situacoes[]" value="EVADIDO" id="situacaoEVADIDO" <?php if(in_array("EVADIDO",$situacoesEscolhidas)) { echo "checked=\"checked\""; } ?> />
        Evadido
        <input type="checkbox" name="situacoes[]" value="CONCLUÍDO" id="situacaoCONCLUIDO" <?php if(in_array("CONCLUÍDO",$situacoesEscolhidas)) { echo "checked=\"checked\""; } ?> />
        Conclu&iacute;do
        <input type="checkbox" name="situacoes[]" value="DESISTENTE" id="situacaoDESISTENTE" <?php if(in_array("DESISTENTE",$situacoesEscolhidas)) { echo "checked=\"checked\""; } ?> />
        Desistente
        <input type="checkbox" name="situacoes[]" value="DESLIGADO" id="situacaoDESLIGADO" <?php if(in_array("DESLIGADO",$situacoesEscolhidas)) { echo "checked=\"checked\""; } ?> />
        Desligado
    </div>

    <br/>

    <div>
        Turno:
        <input type="checkbox" name="turnos[]" id="turnoM" value="MANHÃ" <?php if(in_array("MANHÃ",$turnos)) { echo "checked=\"checked\""; } ?> />
        MANH&Atilde;
        <input type="checkbox" name="turnos[]" id="turnoT" value="TARDE" <?php if(in_array("TARDE",$turnos)) { echo "checked=\"checked\""; } ?> />
        TARDE
        <input type="checkbox" name="turnos[]" id="turnoN" value="NOITE" <?php if(in_array("NOITE",$turnos)) { echo "checked=\"checked\""; } ?> />
        NOITE
    </div>

    <br/>

    <div>
    Ordenar Alunos por:
    <input type="radio" name="ordem" value="Matricula" checked>Matrícula
    <input type="radio" name="ordem" value="Nome">Nome
    <input type="radio" name="ordem" value="Situacao">Situação
    <input type="radio" name="ordem" value="Periodo">Período
    </div>

    <br/>
    
    <input type="submit" name="botaoVisualizarConsulta" value="Visualizar Consulta">
    </fieldset>
</form>