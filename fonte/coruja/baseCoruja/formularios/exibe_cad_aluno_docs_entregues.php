<!--// DOCUMENTOS ENTREGUES    -->
<script type="text/javascript">
    function mudouSituacaoDocEntregue(matricula,idTipoDocumento,situacao) {
        document.cadastro.acao.value="mudarSituacaoDocEntregue";
        document.cadastro.matriculaAluno.value=matricula;
        document.cadastro.idTipoDocumento.value=idTipoDocumento;
        document.cadastro.situacaoDocEntregue.value=situacao;
        document.cadastro.submit();

    }
</script>
<fieldset id="fieldsetGeral">

    <?php
    echo "<legend>DOCUMENTOS ENTREGUES<br/>" . $infoPessoa->getNome() ." </legend>";

    if(empty($buscaMatriculas)) {   // SE NÃO HOUVER NENHUMA MATRÍCULA
        echo 'Nenhuma matr&iacute;cula encontrada!';

    }
    else {   // SE EXISTIR MATRÍCULA(S)

        // EXIBE AS INFORMAÇÕES DE MATRÍCULA(S)
        $nmat=1;

        foreach($buscaMatriculas as $itensMat) {
            $curso = Curso::obterCurso($itensMat->getSiglaCurso());

            echo "<a class='pmais' id='icoD" .$itensMat->getMatriculaAluno(). "' onfocus='blur()' ".
                "href=\"javascript:showP('D" .$itensMat->getMatriculaAluno(). "');\"> Matr&iacute;cula: <b> " .$itensMat->getMatriculaAluno(). " </a>";
            echo "&nbsp;";

            echo "<br />" .$itensMat->getSiglaCurso(). ": " .$curso->getNomeCurso(). "</b><br /><br />";

            echo "<div id='pD" .$itensMat->getMatriculaAluno(). "'>";

            // LISTAR TODOS OS DOCUMENTOS E COMPARAR COM OS CADASTRADOS NA MATRICULA
            $paramSiglaCurso = $itensMat->getSiglaCurso();

            //$documentos = $classeCursoTipoDocumento->lista_cursotipodocumento("c.siglaCurso=".$itensMat->getSiglaCurso());
            $colExigeDocumento = ExigeDocumento::obterTodosExigeDocumentoPorCursoMatricula($itensMat->getSiglaCurso(),
                $itensMat->getMatriculaAluno());

            echo "<table width=100%>";
            echo "<tr><th>Documento</th><th>Situa&ccedil;&atilde;o</th></tr>";
            $corFundo = '1';

            foreach($colExigeDocumento as $exigeDocumento) {
                if($corFundo==1) {$cor='#87CEFA'; $corFundo=0;}
                elseif($corFundo==0) {$cor=''; $corFundo=1;}
                $tipoDocumento = $exigeDocumento->getTipoDocumento();
                $descricao = $tipoDocumento->getDescricao();
                if($exigeDocumento->getIsento()=="SIM") {
                    $situacao="ISENTO";
                    $dataEntrega =  Util::dataSQLParaBr($exigeDocumento->getDataEntrega());
                } else {
                    if($exigeDocumento->getDataEntrega()!=null) {
                        $situacao="ENTREGUE";
                        $dataEntrega =  Util::dataSQLParaBr($exigeDocumento->getDataEntrega());
                    } else {
                        $situacao="PENDENTE";
                    }
                }
                echo "<tr bgcolor='$cor'><td>";
                echo $descricao;
                echo "</td><td>";
                echo "<input type=\"radio\" name=\"situacao" . $itensMat->getMatriculaAluno() . $tipoDocumento->getIdTipoDocumento() . "\" value=\"PENDENTE\" ";
                if($situacao=="PENDENTE") echo "checked=\"checked\"";
                echo " onclick=\"javascript:mudouSituacaoDocEntregue('" . $itensMat->getMatriculaAluno() .
                        "','" . $tipoDocumento->getIdTipoDocumento() . "','PENDENTE');\"/>";
                echo "Pendente&nbsp;";
                echo "<input type=\"radio\" name=\"situacao" . $itensMat->getMatriculaAluno() . $tipoDocumento->getIdTipoDocumento() . "\" value=\"ENTREGUE\" ";
                if($situacao=="ENTREGUE") echo "checked=\"checked\"";
                echo " onclick=\"javascript:mudouSituacaoDocEntregue('" . $itensMat->getMatriculaAluno() .
                        "','" . $tipoDocumento->getIdTipoDocumento() . "','ENTREGUE');\"/>";
                echo "Entregue&nbsp;";
                if($situacao=="ENTREGUE") echo "em " . $dataEntrega . "&nbsp;";
                echo "<input type=\"radio\" name=\"situacao" . $itensMat->getMatriculaAluno() . $tipoDocumento->getIdTipoDocumento() . "\" value=\"ISENTO\" ";
                if($situacao=="ISENTO") echo "checked=\"checked\"";
                echo " onclick=\"javascript:mudouSituacaoDocEntregue('" . $itensMat->getMatriculaAluno() .
                        "','" . $tipoDocumento->getIdTipoDocumento() . "','ISENTO');\"/>";
                echo "Isento&nbsp;";
                if($situacao=="ISENTO") echo "em " . $dataEntrega . "&nbsp;";
                echo "</td></tr>";

            }
            echo "</table><br /><br />";
            echo '</div>';

            $nmat++;

        }
    }
    echo "<br />";

    ?>
</fieldset>
<!--// FIM DE DOCUMENTOS ENTREGUES   -->