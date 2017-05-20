<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Curso.php";

// Variaveis de entrada:
// $titulo string com o titulo da janela
// $destino string com a url do controle de destino
// $cursos lista de objetos de curso
?>

<script type="text/javascript">
    function tratarMudouCurso() {
        var id = document.getElementById("siglaCurso").value;
        if( id !== "" ) 
        {
            carregarPeriodosLetivoCurso( id);
        } 
        else 
        {
            window.alert("Selecione um curso");
        }
    }
    
    function carregarPeriodosLetivoCurso( siglaCurso)
    {
            // requisicao ajax para obter lista de periodos letivos desse curso
            $.get(
                '/coruja/interno/selecionar_curso_periodoletivo/obterTrechoPeriodosLetivos_controle.php',
                { siglaCurso: siglaCurso }
            ).done(                 
                function( data ) {
                    $("#secaoPeriodoLetivo").html( data );
                }
            );        
    }
    
    function submeter() {
        if( $("#siglaCurso").val() === "" || $("#idPeriodoLetivo").val() === ""  ) {
            window.alert("Selecione um curso e um periodo letivo.");
        } else {
            window.open("<?php echo $destino ?>?siglaCurso=" +
                    $("#siglaCurso").val() +"&idPeriodoLetivo=" +
                    $("#idPeriodoLetivo").val(),"_top");
        }
    }
    $(function()
    {
        carregarPeriodosLetivoCurso( $("#siglaCurso").val());
    });
    
</script>

<form method="post" name="selecionaCursoPeriodoLetivo" id="selecionaCursoPeriodoLetivo" action="/coruja/interno/selecionar_curso_periodoletivo/selecionarCursoPeriodoLetivo_controle.php">
    <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
    <input type="hidden" name="controleDestino" value="<?php echo $controleDestino; ?>" />
    <input type="hidden" name="acaoControleDestino" value="<?php echo $acaoControleDestino; ?>" />
    <input type="hidden" name="controleDestinoTitulo" value="<?php echo $controleDestinoTitulo; ?>" />

    <fieldset id="fieldsetGeral">
        <legend><?php echo $titulo; ?></legend>
        
        <font size="-1" color="#FF0000">Selecione um curso.</font><br />
        <select name="siglaCurso" id="siglaCurso" onchange="tratarMudouCurso();">
             <option value=''>Selecione o curso</option>
             <?php foreach($cursos as $curso){
                 $selecionado = ($siglaCursoFiltro === $curso->getSiglaCurso() ? "selected" : "");
                echo"<option value='".$curso->getSiglaCurso()."' $selecionado>" . $curso->getSiglaCurso() . " - " . $curso->getNomeCurso() . "</option>";
             }?>
        </select>

        <div id="secaoPeriodoLetivo">
            <font size="-1" color="#FF0000">Per&iacute;odo Letivo</font><br />
        </div>
        
        <br/>
        <input type="button" value="Selecionar" onclick="submeter()" />
        
    </fieldset>
</form>

<form id="selecionar" method="GET" action="<?php echo $destino; ?>">
    <input type="hidden" name="siglaCurso" />
    <input type="hidden" name="idPeriodoLetivo" />
</form>

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>