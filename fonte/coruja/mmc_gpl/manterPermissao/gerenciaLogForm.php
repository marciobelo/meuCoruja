<?php
    require_once "$BASE_DIR/includes/topo.php";
    require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<link href="gerenciaLog.css" rel="stylesheet"/>
<script type="text/javascript" src="/coruja/javascript/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="../font-awesome/css/font-awesome.min.css">
<form method="post" id="gerenciaLogForm" action="gerenciaLog_controle.php" onsubmit="return validaForm()">
    <fieldset>
        <legend>Buscar Logs das Permiss&otilde;es do Sistema</legend>
        <table>     
        <tr>
            <td> Nome de Usu&aacute;rio:</td>
            <td> <input type="text" name="nome" id="nome" style ="text-transform: uppercase;" value="<?php echo $nome ?>"></td>
        </tr>
        
        <tr>        
            <td> Nome de Acesso: </td>
            <td> <input type="text" name="nomeAcesso" id="nomeAcesso" value="<?php echo $nomeAcesso ?>"> </td>
        </tr>    

        <tr>
            <td> Parte da descri&ccedil;&atilde;o do Log: </td>
                <td> <input type="text" name="parteLog" id="parteLog" value="<?php echo $parteLog ?>"> </td>
            </tr>
        <tr>
            <td> Permiss&atilde;o: </td>
            <td> <select type="select" style="width:100px" name="casoUso" id="casoUso">
                <option value=""></option> 
                <?php foreach ($casosUso as $casoUso) {
                      if ($casoUso == $casoUsoSelecionado) {
                ?>
                          <option value="<?php echo $casoUso?>" selected>  <?php echo $casoUso?> </option>
                <?php
                      } else {
                ?>
                          <option value="<?php echo $casoUso?>" >  <?php echo $casoUso?> </option>
                <?php
                      }
                } ?>
                </select> </td>

        </tr>
        <tr>    
            <td> <input type="submit" class="btn fa-input" id="btnBuscar" value="Buscar">
                <input type="hidden" name="acao" value="buscar">
                <input type="hidden" name="paginaAtual" id="paginaAtual">
                <input type="hidden" name="paginaDigitada" id="paginaDigitada">
            </td>     
        </tr>
        </table>
    </fieldset>
</form>
    <?php 
        if (isset($logsToView) && count($logsToView) < 1) {
            echo "<p id='semResultado'>Nenhum resultado encontrado</p>";
    ?>  
    <?php    
        } elseif( (isset($logsToView) && count($logsToView) > 0) ) {     
        $numeroPaginas = '';        
        $count = $infosPaginacao['primeiraPaginaASerExibida'];
        while ($count <= $infosPaginacao['paginaLimite']) {
            $id = "pagina";

            $pagina = "<a class='numPagina' href='javascript:void(0)' onclick=\"mudarPagina('" . $count . "')\" >$count</a>";

            if($count === (int)$infosPaginacao['paginaAtual']) {
                $id="paginaAtual";
                $pagina = "<span class='numPagina' style='color:black'>$count</span>";
            }

            $numeroPaginas .= $pagina;    
            $count++;
        }
        
        $registros = $infosPaginacao['primeiroRegistro'] . ' - ' . $infosPaginacao['ultimoRegistro'] . ' de ' . $infosPaginacao['totalRegistros'];

        echo "<div id='paginacao'>";
            echo "<div id='paginas'>";
                echo "<input type='button' class='fa fa-input primeira' value='&#xf100;' aria-hidden='true' onclick='mudarPagina( " . 1 . " )'>";
                echo "<input type='button' class='fa fa-input anterior' value='&#xf104;' aria-hidden='true' onclick='mudarPagina( " . ($infosPaginacao['paginaAtual']-1) . " )'>";
                    echo $numeroPaginas;
                echo "<input type='button' class='fa fa-input proxima' value='&#xf105;' aria-hidden='true' onclick='mudarPagina( " . ($infosPaginacao['paginaAtual']+1) . " )'>";
                echo "<input type='button' class='fa fa-input ultima' value='&#xf101;' aria-hidden='true' onclick='mudarPagina( " . $infosPaginacao['totalDePaginas'] . " )'>";
            echo "</div>";
            echo "<div id='irPara'>";
                echo "<span>Ir para a p&aacute;gina </span>";
                echo "<input type='text' size='3' id='digitadaInicio' onkeypress='return event.charCode >= 48 && event.charCode <= 57'>";
                echo "<input type='button' id='botaoIr' value='Ir' class='btn fa-input' aria-hidden='true' onclick=\"mudarPagina(-1, 'Inicio')\">";
                echo "<span id='totalPagina'> Total: " . $infosPaginacao['totalDePaginas'] . "</span>";
            echo "</div>";
        echo "</div>";
        echo "<div id='registros'> ";
            echo "<span> Registros: $registros </span>";
        echo  "</div>";
    ?>
<fieldset id="fieldsetLog">
    <table>
        <?php
            foreach ($arrLogkeys as $key => $val) {
                echo "<th width=" . $arrayTamanhoColuna[$key] . " >" . $val . "</th>";
            } 
        ?>

        <?php 
            foreach ($logsToView as $key => $logToView) {
        ?>
                <tr bgcolor = "<?php echo ($key % 2 === 0) ?  '#00BFF' : ''; ?>">
                    <td align="center"> <?php echo $logToView->getDataHora(); ?></td>
                    <td align="center"> <?php echo $logToView->getIdPessoa(); ?></td>
                    <td align="center"><?php echo $logToView->getNomeAcesso(); ?></td>
                    <td align="center"><?php echo $logToView->getNome(); ?></td>
                    <td align="center"> <?php echo $logToView->getIdCasoUso(); ?></td>
                    <td align="center"> <?php echo $logToView->getDescricao();?></td>
                </tr>
        <?php 
            } 
        ?>
    </table>
</fieldset>
  
<?php   
        echo "<div id='registros'> ";
            echo "<span> Registros: $registros </span>";
        echo  "</div>";
        echo "<div id='paginacao'>";
            echo "<div id='paginas'>";
                echo "<input type='button' class='fa fa-input primeira' value='&#xf100;' aria-hidden='true' onclick='mudarPagina( " . 1 . " )'>";
                echo "<input type='button' class='fa fa-input anterior' value='&#xf104;' aria-hidden='true' onclick='mudarPagina( " . ($infosPaginacao['paginaAtual']-1) . " )'>";
                    echo $numeroPaginas;
                echo "<input type='button' class='fa fa-input proxima' value='&#xf105;' aria-hidden='true' onclick='mudarPagina( " . ($infosPaginacao['paginaAtual']+1) . " )'>";
                echo "<input type='button' class='fa fa-input ultima' value='&#xf101;' aria-hidden='true' onclick='mudarPagina( " . $infosPaginacao['totalDePaginas'] . " )'>";
            echo "</div>";
            echo "<div id='irPara'>";
                echo "<span>Ir para a p&aacute;gina </span>";
                echo "<input type='text' size='3' id='digitadaFim' onkeypress='return event.charCode >= 48 && event.charCode <= 57'>";
                echo "<input type='button' id='botaoIr' value='Ir' class='btn fa-input' aria-hidden='true' onclick=\"mudarPagina(-1, 'Fim')\">";
                echo "<span id='totalPagina'> Total: " . $infosPaginacao['totalDePaginas'] . "</span>";
            echo "</div>";
        echo "</div>";
    }
?>

<script>
    var paginaAtual = <?php echo json_encode($infosPaginacao['paginaAtual']); ?>;
    var totalDePaginas = <?php echo json_encode($infosPaginacao['totalDePaginas']); ?>;
    
    function validaForm() {
        if($('#nome').val().length > 0 || $('#nomeAcesso').val().length > 0 
            || $('#casoUso').val().length > 0 || $('#parteLog').val().length > 0){
            return true;
        }
        
        alert("Preencha algum filtro");
        return false;
    }
    
    function mudarPagina(pagina, id) {
        if (pagina !== -1) {
            $('#paginaAtual').val(pagina);
            $('#paginaDigitada').val('');
        } else {
            var paginaDigitada = $('#digitada'+id).val();
            
            if (paginaDigitada > totalDePaginas) {
                alert('Essa página Não existe');
                return false;
            }
            
            $('#paginaAtual').val(paginaDigitada);
            $('#paginaDigitada').val(paginaDigitada);
        }
        
        $('#gerenciaLogForm').submit();
    }
    
    function configuraClassesBotoesPaginacao() {
        if (parseInt(paginaAtual) === 1) {
            $('.primeira').prop("disabled", true);
            $('.anterior').prop("disabled", true);
        }
        
        if (parseInt(paginaAtual) === parseInt(totalDePaginas)) {
            $('.proxima').prop("disabled", true);
            $('.ultima').prop("disabled", true);
        }
    }
    configuraClassesBotoesPaginacao();
</script>