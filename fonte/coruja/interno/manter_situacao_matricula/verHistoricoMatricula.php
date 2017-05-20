    <table border="1">
        <tr>
            <td>Matr&iacute;cula:</td>
            <td colspan="2"><?php echo $matriculaAluno; ?></td>
        </tr>
        <tr>
            <td >Nome:</td>
            <td colspan="2"><?php echo $nomeAluno; ?></td>
        </tr>
        <tr>
            <td>Data Matr&iacute;cula:</td>
            <td colspan="2"><?php echo Util::formataData($dataMatricula); ?></td>
        </tr>
        <tr>
            <td>Curso:</td>
            <td colspan="2"><?php echo $nomeCurso; ?></td>
        </tr>
        <tr>
            <td>Situação Matr&iacute;cula:</td>
            <td colspan="2"><?php echo $situacaoMatricula; ?></td>
        </tr>
        <tr>
            <td colspan="3" align="center">Hist&oacute;rico de Modifica&ccedil;&otilde;es na Matr&iacute;cula</td>
        </tr>
        <tr>
            <td>Data Modifica&ccedil;&atilde;o</td>
            <td>Situa&ccedil;&atilde;o</td>
            <td>Observação</td>
        </tr>
        <?php
        if(count($listaSituacaoMatriculaHistorico)==0) {
        ?>
        <tr>
            <td colspan="3" align="center">N&atilde;o h&aacute; registros.</td>
        </tr>
        <?php
        } else {
            foreach($listaSituacaoMatriculaHistorico as $sitMatHist) {
        ?>
        <tr>
            <td><?php echo Util::dataSQLParaBr($sitMatHist["dataHistorico"]) ?></td>
            <td><?php echo $sitMatHist["situacaoMatricula"] ?></td>
            <td><?php echo $sitMatHist["texto"] ?></td>
        </tr>
        <?php
            } // fecha o for
        } // fecha o else
        ?>
    </table>