<!--// FORMULARIO DE MATRÍCULA   -->

<fieldset id="fieldsetGeral">

    <?php

    echo "<legend>MATR&Iacute;CULA<br/>" . $infoPessoa->getNome() ." </legend>";

    if(empty($buscaMatriculas)) {   // SE NÃO HOUVER NENHUMA MATRÍCULA
        echo 'Nenhuma matr&iacute;cula encontrada!';
    } else {   // SE EXISTIR(EM) MATRÍCULA(S)
        echo "<font size='-2' color='#F00'>Clique no item para expandir.</font><br /><br />";

        // EXIBE AS INFORMAÇÕES DE MATRÍCULA(S)
        foreach($buscaMatriculas as $itensMat) {

            $curso = Curso::obterCurso( $itensMat->getSiglaCurso());
            $periodo = PeriodoLetivo::obterPeriodoLetivo($itensMat->getIdPeriodoLetivo());
            $matriz = MatrizCurricular::obterMatrizCurricular($curso->getSiglaCurso(), $itensMat->getIdMatriz());
            $ingresso = FormaIngresso::getFormaIngressoById( $itensMat->getIdFormaIngresso());

            echo "<a class='pmais' id='ico" .$itensMat->getMatriculaAluno(). "' onfocus='blur()' ".
                "href=\"javascript:showP('" .$itensMat->getMatriculaAluno(). "');\"> Matr&iacute;cula: <b> " .$itensMat->getMatriculaAluno(). " </a>";
            
            echo "<input type='button' value='Exibir/Ocultar Detalhes' onclick=\"javascript:showP('" . $itensMat->getMatriculaAluno() . "');\" />";
            echo "&nbsp;";

            echo "<input id='editarMatricula' type='button' value='Editar Matr&iacute;cula' ";
            echo "onclick='document.cadastro.acao.value=\"preparaEdicaoMatricula\"; document.cadastro.matriculaAluno.value=\"";
            echo $itensMat->getMatriculaAluno() . "\"; document.cadastro.submit();' />";

            echo "<br />" .$itensMat->getSiglaCurso(). ": " . $curso->getNomeCurso() . "</b><br /><br />";

            echo "<div id='p" .$itensMat->getMatriculaAluno(). "' style='display: none;'>";

            // matricula
            echo $formExibeAluno->inputLabel('Matr&iacute;cula',$itensMat->getMatriculaAluno());
            echo "<br />";

            // data da matricula
            echo $formExibeAluno->inputLabel('Data da Matr&iacute;cula',Util::formataData($itensMat->getDataMatricula()));
            echo "<br />";

            // curso
            echo $formExibeAluno->inputLabel('Curso',$itensMat->getSiglaCurso());
            echo "<br />";

            // situação da matricula
            echo $formExibeAluno->inputLabel('Situa&ccedil;&atilde;o da Matr&iacute;cula',$itensMat->getSituacaoMatricula());
            echo "<br />";

            // se concluído, data da conclusão
            echo $formExibeAluno->inputLabel('Data Conclus&atilde;o',Util::formataData($itensMat->getDataConclusao()));
            echo "<br />";

            // turno de ingresso
            echo $formExibeAluno->inputLabel('Turno de Ingresso',$itensMat->getTurnoIngresso());
            echo "<br />";

            // periodo letivo
            echo $formExibeAluno->inputLabel('Per&iacute;odo Letivo',$periodo->getSiglaPeriodoLetivo());
            echo "<br />";

            // Matriz Curricular
            echo $formExibeAluno->inputLabel('Matriz Curricular',Util::formataData($matriz->getDataInicioVigencia()));
            echo "<br />";
            echo '<br />';

            // INFORMAÇÕES DE CONCURSO
            echo $formExibeAluno->inputLabel('<b>CONCURSO</b>','');
            echo "<br />";
            echo '<br />';

            // forma de ingresso

            echo $formExibeAluno->inputLabel('Forma de Ingresso',$ingresso->getDescricao());
            echo "<br />";

            // pontos no concurso
            echo $formExibeAluno->inputLabel('Pontua&ccedil;&atilde;o', number_format($itensMat->getConcursoPontos(),2,",","") );
            echo "<br />";

            // classificação
            echo $formExibeAluno->inputLabel('Classifica&ccedil;&atilde;o',$itensMat->getConcursoClassificacao());


            echo "<br />";
            echo "<br />";
            echo "<br />";
            echo '</div>';

        }
    }

    echo "<input type='button' value='Nova Matr&iacute;cula' id='novaMatricula'";
    echo "onclick='document.cadastro.acao.value=\"preparaNovaMatricula\"; document.cadastro.submit();' />";

    echo "<br />";

    ?>
</fieldset>

<!--//  FIM DO FORMULARIO DE MATRÍCULA   -->