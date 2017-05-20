<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";

$siglaCurso = $_GET["siglaCurso"];

$periodosLetivos = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso( $siglaCurso );
?>
<font size="-1" color="#FF0000">Per&iacute;odo Letivo</font><br />
<select name="idPeriodoLetivo" id="idPeriodoLetivo">
     <option value=''>Selecione o Per&iacute;odo Letivo</option>
     <?php 
     foreach($periodosLetivos as $periodoLetivo) {
        echo"<option value='" . $periodoLetivo->getIdPeriodoLetivo() . "'>" . $periodoLetivo->getSiglaPeriodoLetivo() . "</option>";
     }
     ?>
</select>
