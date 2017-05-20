<?php 
    if($siglaCurso=='')
    {
        echo "Curso não selecionado!<br /><br />";
        echo "<input type='button' id='button2' name='Voltar' value='Voltar' onclick='history.back(-1);' />";
    }
    else
    {
        echo "Curso: <b>".$classeCurso->getSiglaCurso()." -". $classeCurso->getNomeCurso()."</b> <br />";

        $cor = 1;

    	echo "<form name='periodo' id='periodo' method='post' action='PeriodoLetivo_controle.php?action=curso'>";
    	echo "<input type='hidden' name='idPeriodoLetivo' id='idPeriodoLetivo'>";
    	echo "<input type='hidden' name='siglaPeriodoLetivo' id='siglaPeriodoLetivo'>";
    	echo "<input type='hidden' name='siglaCurso' id='siglaCurso' value='$siglaCurso'>";
    	echo "<fieldset id='fieldsetGeral'>";
            
        // checa se há resultados    
        if($collection==null)
        { 
            echo "<b>Nenhum per&iacute;odo letivo encontrado.</b>";
            echo "<br /><br />";
            
        }
        else{
            
            echo "<table width=100%>";
            echo "<tr align='center'><th>Periodo</th><th>Data Inicio/Fim</th><th>Emitir Calendario</th><th>Evento Administrativo</th><th>Editar</th><th>Excluir</th></tr>";
            
            foreach($collection as $periodo)
            {   

                if($cor==1){$corfundo='#00BFFF'; $cor=2;}
                elseif($cor==2){$corfundo=''; $cor=1;}
                
                $lista .= "<tr bgcolor='$corfundo'><td width='7%' align='center'>";
                $lista .= $periodo->getSiglaPeriodoLetivo();
                $lista .= "</td>";
                $lista .= "<td width='15%'>&nbsp;"; 
                $lista .= $periodo->getDataInicio();
                $lista .= " - ";
                $lista .= $periodo->getDataFim();
                $lista .= "</td>";
                $lista .= "<td width='5%' align='center'>";
                $lista .= "<input type='button' id='button3' name='Calendario' value='Calendário Letivo' onclick=calendario('";
                $lista .= $periodo->getIdPeriodoLetivo()."','".$periodo->getSiglaPeriodoLetivo();              
                $lista .= "')>";
                $lista .= "</td>";
                $lista .= "<td width='5%' align='center'>";
                $lista .= "<input type='button' id='button3' name='Evento' value='Evento Administrativo' onclick=evento('";
                $lista .= $periodo->getIdPeriodoLetivo()."','".$periodo->getSiglaPeriodoLetivo();
                $lista .= "')>";
                $lista .= "</td>";    
                $lista .= "<td width='7%' align='center'><a href=javascript:alterar('idPeriodoLetivo','PeriodoLetivo_controle.php?action=alterar','periodo','";
                $lista .= $periodo->getIdPeriodoLetivo();
                $lista .= "')>";
                $lista .= "<img src='../imagens/editar.png' border='0' alt='Editar Periodo'></a></td>";    
                $lista .= "<td width='7%' align='center'><a href=javascript:excluir('idPeriodoLetivo','PeriodoLetivo_controle.php?action=excluir','periodo','";
                $lista .= $periodo->getIdPeriodoLetivo();
                $lista .= "') ";
                $lista .= "onClick='return confirm(\"Tem Certeza? Esta ação não poderá ser desfeita!\")'>";
                $lista .= "<img src='../imagens/excluir.png' border='0'  alt='Excluir Periodo'></a></td></tr>";        
                
            }
            $lista .= "</table><br />";
            
            echo $lista;
        }    
            echo "<input type='button' id='button1' name='CadastroPeriodo' value='Cadastrar' onclick=cadastrar('periodo','PeriodoLetivo_controle.php?action=cadastrar') />";
            echo "<input type='button' id='button1' name='Voltar' value='Voltar' onclick='document.periodo.submit();' />";
            
            
            
        echo '<br />';
         
         echo '</form>';    
         echo '</fieldset>';
    }
        
?>