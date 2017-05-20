<?php 
        echo "Curso: <b>".$classeCurso->getSiglaCurso()." -". $classeCurso->getNomeCurso()."</b> <br />";
        echo "Calendário <b>".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().")</b> do IST-Rio <br />";

        $cor = 1;

    	echo "<form name='calendario' id='calendario' method='post' action='PeriodoLetivo_controle.php?action=listar'>";
    	echo "<input type='hidden' name='siglaCurso' id='siglaCurso' value='".$classeCurso->getSiglaCurso()."'>";
        echo "<input type='hidden' name='idPeriodoLetivo' id='idPeriodoLetivo' value='".$periodoLetivo->getIdPeriodoLetivo()."'>";
    	echo "<fieldset id='fieldsetGeral'>";
            
        // checa se há resultados   
        if($collection==null)
        { 
            echo "<b>Nenhuma atividade encontrada para este período letivo.</b>";
            echo "<br /><br />";
            
        }
        else{
            $meses = array( "Janeiro" , "Fevereiro" , "Março" , "Abril" , "Maio" , "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" );
            echo "<table width=100%>";
            echo "<tr align='center'><th>Ano</th><th>M&ecirc;s</th><th>Dia</th><th>Atividade</th></tr>";
            $mesEvento = '';
            $mes = '';
            foreach($collection as $evento)
            {
            	$diaSubstr =  substr($evento->getData(), 0, 2);
                $mesSubstr =  substr($evento->getData(), 3, 2);
                $anoSubstr =  substr($evento->getData(), 6, 4);
            	
                if($mesSubstr!=$mes){
                	$mesEvento = $meses[$mesSubstr-1];
                	$mes = $mesSubstr;
                    if($cor==1){$corfundo='#00BFFF'; $cor=2;}
                    elseif($cor==2){$corfundo=''; $cor=1;}
                }else{
                	$mesEvento = '';
                }
                $lista .= "<tr bgcolor='$corfundo'>";
                $lista .= "<td width='10%' align='center'><b>";
                $lista .= $anoSubstr;
                $lista .= "<input type='hidden' name='collectionAnos[]' value='".$anoSubstr."'>";
                $lista .= "</b></td>";
                $lista .= "<td width='15%' align='center'><b>";
                $lista .= $mesEvento;
                $lista .= "<input type='hidden' name='collectionMeses[]' value='".$mesEvento."'>";
                $lista .= "</b></td>";
                $lista .= "<td align=center>";
                $lista .= $diaSubstr;
                $lista .= "<input type='hidden' name='collectionDias[]' value='".$diaSubstr."'>";
                $lista .= "</td>";    
                $lista .= "<td>";
                $lista .= $evento->getDescricao();
                $lista .= "<input type='hidden' name='collectionDescricoes[]' value='".$evento->getDescricao()."'>";
                $lista .= "</td>";
                $lista .= "</tr>";        
                
            }
            $lista .= "</table><br />";
            
            echo $lista;
        }    
            echo "<input type='button' id='button2' name='Voltar' value='Voltar' onclick='document.voltarAcao.submit();' />";
            echo "<input type='button' id='button1' name='botaoImpressao' value='Emitir Calendário Letivo' onclick='emitirCalendario2();' />";
            
            
            
        echo '<br />';
         
         echo '</form>';    
        echo "<form name='voltarAcao' id='voltarAcao' method='post' action='PeriodoLetivo_controle.php?action=listar'>";
    	echo "<input type='hidden' name='siglaCurso' id='siglaCurso' value='".$classeCurso->getSiglaCurso()."'>";
        echo "<input type='hidden' name='idPeriodoLetivo' id='idPeriodoLetivo' value='".$periodoLetivo->getIdPeriodoLetivo()."'>";
        echo "</form>";
         echo '</fieldset>';
    echo "</div>";