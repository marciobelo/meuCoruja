<?php
/**
 * @author: Helder Nascimento
 * @name: busca_cad_aluno_docs.php
 * @version: 1.0
 * @since: versão 1.0 
 */
 require_once("../includes/comum.php");   

class buscaCadAlunoDocs
{
    // Pessoa
    private $idPessoa;
    private $matriculaAluno;
    private $siglaCurso;
    private $idTipoDocumento;
    private $isento;
    private $dataEntrega;
    private $opcao;
    
    private $quant_matriculas;

/**
* put your comment there...
*     
* @param $idPessoa
* @param $opcao  -  exibir ou editar
*/
    function docsAluno($idPessoa,$opcao)
    {
    $con = BD::conectar();
    $this->idPessoa = $idPessoa;
    $this->opcao = $opcao;
    
        $sqlMat = "SELECT MatriculaAluno.matriculaAluno, MatriculaAluno.siglaCurso, curso.* ".
                "FROM MatriculaAluno ".
                "INNER JOIN Curso ".
                "ON MatriculaAluno.siglaCurso = Curso.siglaCurso ".
                "WHERE idPessoa = '$this->idPessoa'";
        
        $resMat = mysql_query($sqlMat,$con);
        
        // PROCURA AS MATRICULAS DO ALUNO
        while($rowMat = mysql_fetch_array($resMat))
        {
            $this->matriculaAluno = $rowMat['matriculaAluno'];
            $this->siglaCurso = $rowMat['siglaCurso'];
            $this->nomeCurso = $rowMat['nomeCurso'];
        
            // BUSCA OS DOCUMENTOS REQUERIDOS AO CURSO
            $sqlTipoDocs ="SELECT CursoTipoDocumento.*, tipodocumento.* ".
                "FROM CursoTipoDocumento ".
                "INNER JOIN tipodocumento ".
                "ON CursoTipoDocumento.idTipoDocumento = tipodocumento.idTipoDocumento ".
                "WHERE CursoTipoDocumento.siglaCurso = '$this->siglaCurso'";
        
            $resTipoDocs = mysql_query($sqlTipoDocs,$con);
        
            
            echo "<a class='pmais' id='icoDoc" .$this->matriculaAluno. "' onfocus='blur()' href=\"javascript:showP('Doc" .$this->matriculaAluno. "');\">".
                     "Matr&iacute;cula: <b>" .$this->matriculaAluno. "</a><br />" .$this->siglaCurso. " - " .$this->nomeCurso. "</b><br /><br />";
         
            echo "<div id='pDoc" .$this->matriculaAluno. "' style='display: none;'>";
            
            $defEstilo = 1;
            
                // se for para o form de edição, inicia uma tabela, para organização
                if($this->opcao == "editar")    
                {
                    echo "<table cellpadding=0 cellspacing=0 width=100%>";
                    echo "<tr><td>&nbsp;</td><td align=center><b>Entregue</b></td><td align=center><b>Isento</b></td></tr>";
                }

            
            while($rowTipoDocs = mysql_fetch_array($resTipoDocs))
            {
                $this->idTipoDocumento = $rowTipoDocs['idTipoDocumento'];
                $this->descricao = $rowTipoDocs['descricao'];
                $this->siglaCurso = $rowTipoDocs['siglaCurso'];
                
                // DOCUMENTOS REFERENTES À MATRICULA EM QUESTÃO
                $sqlDocs ="SELECT * FROM exigenciadocumento ".
                    "WHERE idTipoDocumento = '$this->idTipoDocumento' ".
                    "AND  siglaCurso = '$this->siglaCurso' ". 
                    "AND  matriculaAluno = '$this->matriculaAluno'"; 
                    
                $resDocs = mysql_query($sqlDocs,$con);    
                
                // ESTA LINHA É PARA O CASO DE UM DOCUMENTO CADASTRADO POSTERIORMENTE
                $existe = mysql_num_rows($resDocs);
                
                $rowDocs = mysql_fetch_array($resDocs);
                
                $this->isento = $rowDocs['isento'];
                $this->dataEntrega = $rowDocs['dataEntrega'];
                $this->dataEntregaBr = explode('-',$rowDocs['dataEntrega']); //separa a string por "-"
                $this->dataEntregaBr = implode('/',array_reverse($this->dataEntregaBr)); // reverte a ordem da string quebrada acima e monta novamente no novo formato DD/MM/YYYY
                
                // DATA ATUAL - PARA DOCUMENTO ENTREGUE NA DATA ATUAL
                $dataEntregaEdit = date('Y-m-d');
                
                // DEFINE A COR DE FUNDO
                if($defEstilo == 1){ $estilo = "docStatusImpar"; $defEstilo = 2; }
                elseif($defEstilo == 2){ $estilo = "docStatusPar"; $defEstilo = 1; }
                
                // se o documento existe na lista mas nao tem entrada ligada à matricula                
                    if($existe == 0)
                    { 
                        if($this->opcao == "exibir")
                        {
                            echo "<div class='docDesc'>$this->descricao</div>"; 
                            echo "<div class='$estilo'>N&atilde;o Consta.</div>";   
                            
                        }
                        elseif($this->opcao == "editar")    
                        {
                            echo "<tr  class='$estilo'><td>&nbsp;$this->descricao<input type='hidden' name='idTipoDocumento[" .$this->idTipoDocumento. "]' ".
                                "value='" .$this->idTipoDocumento. "' /></td>";
                            
                            
                            echo "<td>&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".dataEntrega[" .$this->idTipoDocumento. "]' ".
                                "value='$dataEntregaEdit' />Sim";
                            
                            echo "&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".dataEntrega[" .$this->idTipoDocumento. "]' ".
                                "value='0000-00-00' checked='checked' />N&atilde;o</td>";
                            
                            
                            echo "<td>&nbsp;&nbsp;&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".isento[" .$this->idTipoDocumento. "]' ".
                                "value='SIM' />Sim";
                            
                            echo "&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".isento[" .$this->idTipoDocumento. "]' ".
                                "value='NÃO' checked='checked' />N&atilde;o</td></tr>";
                        }
                        
                        
                    }
                // documento existe e possui entrada referente à matricula    
                    else
                    {    
                        if($this->opcao == "exibir")
                        {
                            echo "<div class='docDesc'>$this->descricao</div>";
                        }
                        
                        if($this->isento == "SIM")
                        {
                            if($this->opcao == "exibir")
                            {
                                echo "<div class='$estilo'>Isento</div>";    
                            }
                            elseif($this->opcao == "editar")    
                            {
                                echo "<tr  class='$estilo'><td>&nbsp;$this->descricao<input type='hidden' name='idTipoDocumento[" .$this->idTipoDocumento. "]' ".
                                    "value='" .$this->idTipoDocumento. "' /></td>";
                                
                                echo "<td>&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".dataEntrega[" .$this->idTipoDocumento. "]' ".
                                    "value='" .$this->dataEntrega. "' checked='checked' />Sim";
                                
                                echo "&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".dataEntrega[" .$this->idTipoDocumento. "]' ".
                                    "value='0000-00-00' />N&atilde;o</td>";
                                
                                
                                echo "<td>&nbsp;&nbsp;&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".isento[" .$this->idTipoDocumento. "]' ".
                                    "value='SIM' checked='checked' />Sim";
                                
                                echo "&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".isento[" .$this->idTipoDocumento. "]' ".
                                    "value='NÃO' />N&atilde;o</td></tr>";
                            }
                            
                            
                        }
                        else
                        {
                            if($this->dataEntrega == "0000-00-00")
                            {
                                if($this->opcao == "exibir")
                                {
                                    echo "<div class='$estilo'>Pendente</div>";    
                                }
                                elseif($this->opcao == "editar")    
                                {
                                    echo "<tr  class='$estilo'><td>&nbsp;$this->descricao<input type='hidden' name='idTipoDocumento[" .$this->idTipoDocumento. "]' ".
                                        "value='" .$this->idTipoDocumento. "' /></td>";
                                    
                                    echo "<td>&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".dataEntrega[" .$this->idTipoDocumento. "]' ".
                                        "value='$dataEntregaEdit' />Sim";
                                    
                                    echo "&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".dataEntrega[" .$this->idTipoDocumento. "]' ".
                                        "value='0000-00-00' checked='checked' />N&atilde;o</td>";
                                    
                                    
                                    echo "<td>&nbsp;&nbsp;&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".isento[" .$this->idTipoDocumento. "]' ".
                                        "value='SIM' />Sim";
                                    
                                    echo "&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".isento[" .$this->idTipoDocumento. "]' ".
                                        "value='NÃO' checked='checked' />N&atilde;o</td></tr>";
                                }                            
                            
                            
                            
                            }
                            else
                            {
                                if($this->opcao == "exibir")
                                {
                                    echo "<div class='$estilo'>Entregue em $this->dataEntregaBr</div>";
                                }
                                elseif($this->opcao == "editar")    
                                {
                                    echo "<tr  class='$estilo'><td>&nbsp;$this->descricao<input type='hidden' name='idTipoDocumento[" .$this->idTipoDocumento. "]' ".
                                        "value='" .$this->idTipoDocumento. "' /></td>";
                                    
                                    echo "<td>&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".dataEntrega[" .$this->idTipoDocumento. "]' ".
                                        "value='" .$this->dataEntrega. "' checked='checked' />Sim";
                                    
                                    echo "&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".dataEntrega[" .$this->idTipoDocumento. "]' ".
                                        "value='0000-00-00' />N&atilde;o</td>";
                                    
                                    
                                    echo "<td>&nbsp;&nbsp;&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".isento[" .$this->idTipoDocumento. "]' ".
                                        "value='SIM' />Sim";
                                    
                                    echo "&nbsp;<input type='radio' name='" .$this->matriculaAluno. ".isento[" .$this->idTipoDocumento. "]' ".
                                        "value='NÃO' checked='checked' />N&atilde;o</td></tr>";
                                }    
                            }
                        }
                    }
                
            }
        // se for para o form de edição, finaliza a tabela
                if($this->opcao == "editar")    
                {
                    echo "</table>";
                    
                }    
        echo "<br /><br />";               
        echo "</div>";
        
        }
    }
}
?>