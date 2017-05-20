<table id="tabelaGradeDeHorario" class="gradeDeHorario" align="center">
            <thead>
                <tr>
                    <td colspan="7"  align="center" id="topo-gradeHorario"><?php echo $cc->getPeriodo() ?>º Período - Turno: <?php echo $dadosDaTurma['turno'] ?> - Grade: <?php echo $dadosDaTurma['gradeHorario'] ?></td>
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

                //$tempos = array(1,2,3,4,5,6); //PEGANDO TEMPOS DINAMICAMENTE

                $tempos = array();
                for ($auxTempo = 1; $auxTempo <= count($matrizTempos['SEG']);$auxTempo++){
                    array_push($tempos, $auxTempo);
                }

                foreach ($tempos as $t) { //TEMPOS (LINHA)
                ?>
                <tr>
                    <td align="center"><?php echo $t; ?></td>
                    <?php
                    $dias = array('SEG','TER','QUA','QUI','SEX','SAB');

                    foreach ($dias as $d) { //DIAS (COLUNA)
                    if ($matrizTempos[$d][$t]['nome'] != null){
                        $texto = $matrizTempos[$d][$t]['siglaDisciplina']; //Sigla da disciplina
                        $texto = $texto.' ('.$matrizTempos[$d][$t]['nome'].')'; //Nome do expaço (ex. Hibrida 1)
                    }
                    if($matrizTempos[$d][$t]['idTurma'] == $dadosDaTurma['idTurma']){
                        $colorir = 'style="background: #40E080"';
                    } else {
                        $colorir = '';
                    }
                    ?>
                    <td id='celula-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>' align="center" <?php echo $colorir; ?> >
                        <?php
                        //print_r($matrizTempos[$d][$t]);
                        if ($matrizTempos[$d][$t]['siglaDisciplina'] && $matrizTempos[$d][$t]['idTurma'] <> $_POST['idTurma']){ //Possui turma alocada
                            echo $texto; //Escreve a Disciplina e a Sala naquele tempo de aula
                            //print_r($matrizTempos[$d][$t]);
                        } else { // Nao possui turma alocada
                            if ($matrizTempos[$d][$t]['DURACAO'] > "00:15:00" ){ //caso a duracao seja maior que 15 minutos
                            ?>    <select id='tempoSemanal-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>' name="tempoSemanal-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>" onchange="pintarCelula ('<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>');">
                            <option></option>
                            <?php
                                    foreach ($matrizTempos[$d][$t]['espacosLivres'] as $espaco){
                                        if ($espaco['flagSendoEditado'] == TRUE) {
                                            $selected = 'SELECTED = "SELECTED"';
                                        } else {
                                            $selected = '';
                                        }
                                        echo '<option '.$selected.' value="'.$espaco['idEspaco'].'">'.substr($espaco['nome'], 0, 15).'</option>';
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