<link rel="stylesheet" type="text/css" href="<?php echo $RAIZ_CORUJA; ?>/nort/estilos/gradeDeHorario.css" />

        
        <label>Créditos:</label> <?php echo $cc->getCreditos() ?><input type="hidden" name="auxCreditos" value="<?php echo $cc->getCreditos() ?>"><br>
        <label>Carga Horária:</label> <?php echo $cc->getCargaHoraria() ?> horas/aula<br>
        <label>Periodo na matriz:</label> <?php echo $cc->getPeriodo() ?>º periodo<br>
        <label>Tipo:</label> <?php echo $cc->getTipoComponenteCurricular() ?><br>


        <table id="tabelaGradeDeHorario" class="gradeDeHorario" align="center">
            <thead>
                <tr>
                    <td colspan="7"  align="center"><?php echo $cc->getPeriodo() ?>º Período - Turno: <?php echo utf8_decode($_POST['turno']) ?> - Grade: <?php echo $_POST['gradeHorario'] ?></td>
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

                //$tempos = array(1,2,3,4,5,6); //Substituido pelo código dinâmico abaixo
                $tempos = array();
                for ($auxTempo = 1; $auxTempo <= count($matrizTempos['SEG']);$auxTempo++){
                    array_push($tempos, $auxTempo);
                }

                foreach ($tempos as $t) {
                ?>
                <tr>
                    <td align="center"><?php echo $t; ?></td>
                    <?php
                    $dias = array('SEG','TER','QUA','QUI','SEX','SAB');

                    foreach ($dias as $d) {
                    $texto = $matrizTempos[$d][$t]['siglaDisciplina']; //Sigla da disciplina
                    if ($matrizTempos[$d][$t]['nome'] != null){
                        $texto = $texto.' ('.$matrizTempos[$d][$t]['nome'].')'; //Nome do expaço (ex. Hibrida 1)
                    }
                    ?>
                    <td id='celula-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>' align="center" >
                        <?php 
                        //print_r($matrizTempos[$d][$t]);
                        if ($matrizTempos[$d][$t]['siglaDisciplina']){ //Possui turma alocada
                            echo $texto;

                            //print_r($matrizTempos[$d][$t]);
                            
                        } else { // Nao possui turma alocada
                            if ($matrizTempos[$d][$t]['DURACAO'] > "00:10:00" ){ //caso a duracao seja maior que 10 minutos
                            ?>    <select id='tempoSemanal-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>' name="tempoSemanal-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>" onchange="pintarCelula ('<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>');">
                            <option></option>
                            <?php
                                    foreach ($matrizTempos[$d][$t]['espacosLivres'] as $espaco){
                                    ?><option value="<?php echo $espaco['idEspaco']?>"><?php echo substr($espaco['nome'], 0, 15)?></option><?php
                                    }
                            ?>
                            </select>
                              <?php
                            } else {
                                ?><font style="font-size: 14px; font-style: italic;"><!-- intervalo --> ------- </font><?php
                            }
                         } ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php
                } //FIM DA GRADE DE HORARIO
                ?>
            </tbody>
        </table>