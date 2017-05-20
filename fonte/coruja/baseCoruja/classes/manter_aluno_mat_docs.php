<?php
/**
 * @author: Helder Nascimento
 * @name: manter_aluno_mat_docs.php
 * @version: 1.0
 * @since: versão 1.0 
 */ 

/**
* lista os cursos, períodos letivos e matrizes curriculares
* 
*/
function selecionaCurso(){
    $con = BD::conectar();
            
         $fCurso = "SELECT * FROM Curso ORDER BY idTipoCurso";
         $fCursoQuery = mysql_query($fCurso) or die(mysql_error()); 
             
         while($rowCurso=mysql_fetch_array($fCursoQuery)){
             $siglaCurso = $rowCurso['siglaCurso'];
             $nomeCurso = $rowCurso['nomeCurso'];
             $idTipoCurso = $rowCurso['idTipoCurso'];
                
                // periodo letivo
                //***************************************
                //  MONTA DINAMICAMENTE LISTA DE OPÇÕES   
                //***************************************
                
                $fLetivo = "SELECT * FROM periodoletivo WHERE siglaCurso='$siglaCurso' ORDER BY siglaPeriodoLetivo DESC LIMIT 1";
                $fLetivoQuery = mysql_query($fLetivo) or die(mysql_error()); 
                    while($rowLetivo=mysql_fetch_array($fLetivoQuery)){
                    $idPeriodoLetivo = $rowLetivo['idPeriodoLetivo']; 
                    $siglaCurso = $rowLetivo['siglaCurso'];
                    $siglaPeriodoLetivo = $rowLetivo['siglaPeriodoLetivo'];
                                 
                                 
                    }
                    
              $fMatriz = "SELECT * FROM matrizcurricular WHERE siglaCurso='$siglaCurso' ORDER BY dataInicioVigencia DESC LIMIT 1";
                $fMatrizQuery = mysql_query($fMatriz) or die(mysql_error()); 
                    while($rowMatriz=mysql_fetch_array($fMatrizQuery)){
                    $idMatriz = $rowMatriz['idMatriz']; 
                    $siglaCurso = $rowMatriz['siglaCurso'];
                    $dataInicioVigencia = $rowMatriz['dataInicioVigencia'];
                        
                    }
            
            // junta siglaCurso, idPeriodoLetivo e idMatriz para enviar todos de uma vez        
                    $cursoItens =  $siglaCurso .'-'. $idPeriodoLetivo .'-'. $idMatriz;
            
            // cria a lista com os cursos disponíveis, com checkbox para selecionar e exibir os campos especificos 
                    $curso .= "<label>&nbsp;</label><input type='radio' name='siglaCurso' value='$cursoItens' /><b>$siglaCurso</b><br />";
                    $curso .= "<label>&nbsp;</label>$nomeCurso<br />";
                             
             }      
         
         return $curso;               
}

/**
* listar os itens formaIngresso
* 
*/
function formaIngresso(){ 
    $con = BD::conectar();
    
          $fIngresso = "SELECT * FROM formaingresso";
          $fIngressoQuery = mysql_query($fIngresso) or die(mysql_error()); 
     
             while($rowIngresso=mysql_fetch_array($fIngressoQuery)){
                 $idFormaIngresso = $rowIngresso['idFormaIngresso'];
                 $descricao = $rowIngresso['descricao'];
             
                $formaIngresso .= "<label>&nbsp;</label><input type='radio' name='idFormaIngresso' value='$idFormaIngresso'> $descricao <br />";
             
             }
             
         return $formaIngresso;    
}


function docsEntregues(){ 
    $con = BD::conectar();

    //***************************************
    //  MONTA DINAMICAMENTE LISTA DE OPÇÕES   
    //***************************************    
    $corFundo = '1';   
    $fCurso = "SELECT * FROM Curso ORDER BY idTipoCurso";
    $fCursoQuery = mysql_query($fCurso) or die(mysql_error()); 
         
        while($rowCurso=mysql_fetch_array($fCursoQuery)){
            $siglaCurso = $rowCurso['siglaCurso'];
            $nomeCurso = $rowCurso['nomeCurso'];
            $idTipoCurso = $rowCurso['idTipoCurso'];
         
        // cria a lista com os cursos disponíveis, com checkbox para selecionar e exibir os campos especificos 
        $docEntr .= "<label>&nbsp;</label>";  
        $docEntr .= "<a class='pmais' id='ico$idTipoCurso' onfocus='blur()' ".
            "href=\"javascript:showP('$idTipoCurso');\"><b> $nomeCurso </a>";
        $docEntr .= "<br />";    
        
        // div oculta com os campos da lista. Só abre o que for selecionado
        $docEntr .= "<div id='p$idTipoCurso' style='display: none;'>"; 
    
        $docEntr .= "<label>&nbsp;</label>";
            // lista os documentos
            //***************************************
            //  MONTA DINAMICAMENTE LISTA DE OPÇÕES   
            //***************************************
            $fDocs  = "SELECT tipodocumento.* , CursoTipoDocumento.* ";
            $fDocs .= "FROM TipoDocumento ";
            $fDocs .= "INNER JOIN CursoTipoDocumento ";
            $fDocs .= "ON TipoDocumento.idTipoDocumento=CursoTipoDocumento.idTipoDocumento ";
            $fDocs .= "WHERE cursoTipoDocumento.siglaCurso='$siglaCurso'";
            $fDocs .= "ORDER BY CursoTipoDocumento.idTipoDocumento ";
            
            $docEntr .= "<table cellpadding=0 cellspacing=0>";
            $docEntr .= "<tr><td>&nbsp;</td><td align=center><b>Entregue</b></td><td align=center><b>Isento</b></td></tr>";
            
            // Indice para o array com as informações de documentos
            $n=1;  
            
            $dataEntrega = date('Y-m-d');
            
            $fDocsQuery = mysql_query($fDocs) or die(mysql_error()); 
                while($rowDocs=mysql_fetch_array($fDocsQuery)){
                $idTipoDocumento = $rowDocs['idTipoDocumento']; 
                $siglaCurso = $rowDocs['siglaCurso'];
                $descricao = $rowDocs['descricao'];
                
                if($corFundo==1){$cor='#87CEFA'; $corFundo=0;}
                elseif($corFundo==0){$cor=''; $corFundo=1;}
                    
                    $idDoc = $n;  
                             
                    $docEntr .= "<tr bgcolor=$cor><td>&nbsp;$descricao<input type='hidden' name='idTipoDocumento[$n]' value='$idDoc' /></td>";
                    
                    $docEntr .= "<td>&nbsp;<input type='radio' name='$siglaCurso.dataEntrega[$n]' value='$dataEntrega' />Sim";
                    $docEntr .= "&nbsp;<input type='radio' name='$siglaCurso.dataEntrega[$n]' value='0000-00-00' checked='checked' />N&atilde;o</td>";
                    
                    $docEntr .= "<td>&nbsp;&nbsp;&nbsp;<input type='radio' name='$siglaCurso.isento[$n]' value='SIM' />Sim";
                    $docEntr .= "&nbsp;<input type='radio' name='$siglaCurso.isento[$n]' value='NÃO' checked='checked' />N&atilde;o</td></tr>";
                    
                    $n++;
                }
                
            $docEntr .= '</table><br />';
            $docEntr .= '</div>';
                         
        }
        return $docEntr;
}                          
?>                    