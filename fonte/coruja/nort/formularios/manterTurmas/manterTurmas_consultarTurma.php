<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

/*
 1.1. Sigla do Curso e Nome do Curso (TASI ? Análise de Sistemas Informatizados);
 1.2. Período Letivo da Turma (p.ex.: 2011.1);
 1.3. Data de início da vigência da matriz (P.ex.: 01/01/2006);
 1.4. Sigla e Nome da Disciplina (AC1 ? Arquitetura de Computadores I);
 1.5. Créditos do Componente Curricular (p.ex. 6);
 1.6. Carga Horária do Componente Curricular (p.ex. 120 horas/aula);
 1.7. Período Letivo do Componente na Matriz (p.ex. 1º Período);
 1.8. Tipo de Componente Curricular (p.ex. OBRIGATÓRIO);
 1.9. Grade de Horário (p.ex. B);
 1.10. Turno (p.ex. NOITE);
 1.11. Situação da Turma (p.ex. LIBERADA);
 1.12. Professor (se estiver registrado);
 1.13. Quantidade de Vagas;
 1.14. Sistema exibe a grade de alocações:
          */

//print_r($dadosDaTurma);

// <script type="text/css" src="<?php echo $RAIZ_CORUJA; ? >/nort/estilos/gradeDeHorario.css" >
// </script>

?>


<link rel="stylesheet" type="text/css" href="<?php echo $RAIZ_CORUJA; ?>/nort/estilos/gradeDeHorario.css" />

<form id="cadastro" name="nenhum" method="POST" action="nenhuma" >
    <fieldset>
        <legend>Turma consultada</legend>

        <p>
        <label>Curso:</label> <?php echo $dadosDaTurma['siglaCurso'] ?> - <?php echo $dadosDaTurma['nomeCurso'] ?><br>
        <label>Período Letivo:</label> <?php echo $dadosDaTurma['siglaPeriodoLetivo'] ?><br>
        <label>Data da Matriz:</label> <?php echo Util::dataSQLParaBr($dadosDaTurma['dataInicioVigencia']) ?><br>
        </p>
        <label>Disciplina:</label> <?php echo $dadosDaTurma['siglaDisciplina'] ?> - <?php echo $dadosDaTurma['nomeDisciplina'] ?><br>
        <label>Créditos:</label> <?php echo $dadosDaTurma['creditos'] ?><br>
        <label>Carga Horária:</label> <?php echo $dadosDaTurma['cargaHoraria'] ?> horas/aula<br>
        <p>
        <label>Período na matriz:</label> <?php echo $dadosDaTurma['periodo'] ?>º periodo<br>
        <label>Tipo:</label> <?php echo $dadosDaTurma['tipoComponenteCurricular'] ?><br>
        <label>Grade de horário:</label> <?php echo $dadosDaTurma['gradeHorario'] ?><br>
        </p>
        <label>Turno:</label> <?php echo $dadosDaTurma['turno'] ?><br>
        <label>Situação da Turma:</label> <?php echo $dadosDaTurma['tipoSituacaoTurma'] ?><br>
        <label>Professor:</label> <?php echo $dadosDaTurma['nomeProfessor'] ?><br>
        <label>Qtde de vagas:</label> <?php echo $dadosDaTurma['qtdeTotal'] ?><br><br>


        <table class="gradeDeHorario" align="center">
            <thead>
                <tr>
                    <td colspan="7"  align="center"><?php echo $dadosDaTurma['periodo'] ?>º Período - Turno: <?php echo $dadosDaTurma['turno'] ?> - Grade: <?php echo $dadosDaTurma['gradeHorario'] ?></td>
                </tr>
                <tr>
                    <td width="40" align="center">---</td>
                    <td width="80" align="center">SEG</td>
                    <td width="80" align="center">TER</td>
                    <td width="80" align="center">QUA</td>
                    <td width="80" align="center">QUI</td>
                    <td width="80" align="center">SEX</td>
                    <td width="80" align="center">SAB</td>
                </tr>
            </thead>
            <tbody>
                <?php
                //INICIO DA GRADE DE HORARIO
                $tempos = array(1,2,3,4,5,6);
                foreach ($tempos as $t) {
                ?>
                <tr>
                    <td align="center"><?php echo $t; ?></td>
                    <?php
                    $dias = array('SEG','TER','QUA','QUI','SEX','SAB');
     
                    foreach ($dias as $d) {
                    if($matrizDeAlocacoes[$d][$t]['idTurma'] == $dadosDaTurma['idTurma']){
                        $colorir = 'class="destacado"';
                    } else {
                        $colorir = '';
                    }
                    $texto = $matrizDeAlocacoes[$d][$t]['siglaDisciplina'];
                    if ($matrizDeAlocacoes[$d][$t]['nome'] != null){
                        $texto = $texto.' ('.$matrizDeAlocacoes[$d][$t]['nome'].')';
                    }
                    ?>
                    <td align="center" <?php echo $colorir?> >
                         <?php echo $texto?>
                    </td>
                    <?php } ?>
                </tr>
                <?php
                } //FIM DA GRADE DE HORARIO
                ?>
            </tbody>
        </table>
        <input type="button" value="Voltar" onclick="document.formVoltar.submit();" style="width: 100px">

    </fieldset>
</form>
<form name="formVoltar" action="manterTurmas_controle.php?acao=exibirTurmas" method="POST">
    <input type="hidden" name="siglaCurso" value = "<?php echo $dadosDaTurma['siglaCurso']; ?>">
    <input type="hidden" name="idPeriodoLetivo" value = "<?php echo $dadosDaTurma['idPeriodoLetivo']; ?>">
    <input type="hidden" name="turno" value = "<?php echo $_POST['voltar_turno']; ?>">
</form>
