<?php 
        
        echo "Curso:<b>". $classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso()."</b><br />";
        echo "Período Letivo: <b>".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().")</b> <br />";

        $cor = 1;

    	echo "<form name='eventoAdm' id='eventoAdm' method='post' action='PeriodoLetivo_controle.php?action=listar'>";
    	echo "<input type='hidden' name='seqEvento' id='seqEvento'>";
    	echo "<input type='hidden' name='idPeriodoLetivo' id='idPeriodoLetivo' value='$idPeriodo'>";
    	echo "<input type='hidden' name='siglaCurso' id='siglaCurso' value='".$classeCurso->getSiglaCurso()."'>";
    	echo "<fieldset id='fieldsetGeral'>";
            
        // checa se há resultados   
        if($collection==null)
        { 
            echo "<b>Nenhum evento administrativo encontrado.</b>";
            echo "<br /><br />";
            
        }
        else{
            
            echo "<table width=100%>";
            echo "<tr align='center'><th>Data</th><th>Descricao</th><th>Editar</th><th>Excluir</th></tr>";
            
            foreach($collection as $evento)
            {   

            	
                if($cor==1){$corfundo='#00BFFF'; $cor=2;}
                elseif($cor==2){$corfundo=''; $cor=1;}
                
                $lista .= "<tr bgcolor='$corfundo'><td width='25%' align='left'>";
                $lista .= EventoAdministrativo::DataDiaSemana($evento->getData());
                $lista .= "</td>";
                $lista .= "<td>";
                $lista .= $evento->getDescricao();
                $lista .= "</td>";    
                $lista .= "<td width='7%' align='center'><a href=javascript:alterar('seqEvento','EventoAdministrativo_controle.php?action=alterar','eventoAdm','";
                $lista .= $evento->getSeqEvento();
                $lista .= "')>";
                $lista .= "<img src='../imagens/editar.png' border='0' alt='Editar Periodo'></a></td>";    
                $lista .= "<td width='7%' align='center'><a href=javascript:excluir('seqEvento','EventoAdministrativo_controle.php?action=excluir','eventoAdm','";
                $lista .= $evento->getSeqEvento();
                $lista .= "') ";
                $lista .= "onClick='return confirm(\"Tem Certeza? Esta ação não poderá ser desfeita!\")'>";
                $lista .= "<img src='../imagens/excluir.png' border='0' alt='Excluir Periodo'></a></td></tr>";        
                
            }
            $lista .= "</table><br />";
            
            echo $lista;
        }    
            echo "<input type='button' id='button1' name='CadastroEvento' value='Cadastrar' onclick=cadastrar('eventoAdm','EventoAdministrativo_controle.php?action=cadastrar') />";
            echo "<input type='button' id='button2' name='Voltar' value='Voltar' onclick='document.eventoAdm.submit();' />";
            
            
            
        echo '<br />';
         
         echo '</form>';    
         echo '</fieldset>';
    
        
?>