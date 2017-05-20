<?php
// Para solicionar problema de ACENTOS

header('Content-Type: text/html; charset=ISO-8859-1');

require_once("../../includes/comum.php");

function get_periodos($siglaCurso) 
{
    $con = BD::conectar();
      
        //criando a instrução de busca
        $instrucao = "SELECT idPeriodoLetivo,siglaCurso,siglaPeriodoLetivo FROM ".
                "PeriodoLetivo WHERE siglaCurso = '$siglaCurso' ORDER BY siglaPeriodoLetivo DESC LIMIT 30";
      
        $query = mysql_query($instrucao) or die ("Erro");

        $lista_periodo = array();
        
        while($res = mysql_fetch_array($query))
        {
            $siglaCurso = $res['siglaCurso']; 
            $idPeriodoLetivo = $res['idPeriodoLetivo']; 
            $siglaPeriodoLetivo = $res['siglaPeriodoLetivo']; 

            $lista_periodo[] = array('siglaCurso' => $siglaCurso, 'idPeriodoLetivo' => $idPeriodoLetivo, 'siglaPeriodoLetivo' => $siglaPeriodoLetivo);

        }
        
    $periodo = array();

    $cont = 0;
    
    for($i=0; $i < count($lista_periodo); $i++) {
        if($lista_periodo[$i]['siglaCurso'] == $siglaCurso) 
        {
            $periodo[$cont]['idPeriodoLetivo']= $lista_periodo[$i]['idPeriodoLetivo'];
            $periodo[$cont]['siglaPeriodoLetivo'] = $lista_periodo[$i]['siglaPeriodoLetivo'];
            $cont++;
        }
    }
    return $periodo;
}

/**
*  FILTRA A AÇÃO
*/
switch ($_POST['acao'])
{
    // EXIBE OS PERIODOS DE ACORDO COM A SIGLA DO CURSO
    case "exibePeriodoSelect":
    
    $txt = '<select id="idPeriodoLetivo" name="idPeriodoLetivo">';
        $txt .= '<option value="">Selecione</option>';

        foreach(get_periodos($_POST['siglaCurso']) as $periodo) 
        {
            $txt .= '<option value="'.$periodo['idPeriodoLetivo'].'">' . $periodo['siglaPeriodoLetivo'] . '</option>';
        }

    $txt .= "</select>";

    echo $txt;
    break; 
}
?>