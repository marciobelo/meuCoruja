<?php
/* 
 * Aqui est�o algumas das velidal��es do caso de uso de Criar Turma e Editar Turma
 */

/*
Fluxo Alternativo 3
 1. Ocorreu conflito de hor�rio em um ou mais tempos de aula. Embora o tempo de aloca��o seja previamente verificado, outro usu�rio pode simultaneamente alocar aquele tempo de aula em conflito. O sistema exibe uma mensagem indicado que houve conflito na aloca��o dos tempos de aula e volta ao passo 5, mantendo apenas as sele��es que n�o conflitaram;
*/

/*
Fluxo Alternativo 6
 1. Ocorreu conflito de aloca��o do mesmo professor (independente da matr�cula dele sendo alocada) em um ou mais tempos de aula. O sistema exibe uma mensagem indicado que houve conflito na aloca��o do professor e volta ao passo 5, mantendo apenas as sele��es que n�o conflitaram;
 */
function professorEstaDisponivel($numMatriculaProfessor, $idTempoSemanal, $idPeriodoLetivo) {

    $con = BD::conectar();

    $query = sprintf("".
        ' select'.
        '    MP.`idPessoa`,'.
        '    E.`idEspaco`,'.
        '    AL.`idTempoSemanal`,'.
        '    T.`idPeriodoLetivo`,'.
        '    T.`siglaDisciplina`,'.
        '    E.nome,'.
        '    TS.`horaInicio`'.
        ' from'.
        '    MatriculaProfessor MP,'.
        '    Turma T,'.
        '    Aloca AL,'.
        '    Espaco E,'.
        '    TempoSemanal TS'.
        ' where'.
        '    MP.`matriculaProfessor` = T.`matriculaProfessor`'.
        '    and T.`idTurma` = AL.`idTurma`'.
        '    and AL.`idTempoSemanal` = TS.`idTempoSemanal`'.
        '    and AL.`idEspaco` = E.`idEspaco`'.
        '    and MP.`idPessoa` in'.
        '        ('.
        '        select MP2.`idPessoa`'.
        '        from MatriculaProfessor MP2'.
        "        where MP2.`matriculaProfessor` = '%s'". // #1
        '        )'.
        '    and AL.`idTempoSemanal` = %d'. // #2
        '    and T.`idPeriodoLetivo` = %d'. // #3
        '    and T.tipoSituacaoTurma <>\'CANCELADA\' ' .
        ' order by'.
        '    T.`matriculaProfessor`',
        mysql_real_escape_string($numMatriculaProfessor),         // #1
        mysql_real_escape_string($idTempoSemanal),   // #2
        mysql_real_escape_string($idPeriodoLetivo)); // #3

    $result = mysql_query($query);

    //    $res = mysql_fetch_array($result);

    if (mysql_num_rows($result) == 0){
        return true;
    } else {
        return false;
    }

}
?>
