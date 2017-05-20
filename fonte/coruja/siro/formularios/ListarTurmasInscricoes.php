<!--// Criando listagem de turmas oferecidas -->

<script type="text/javascript">
    function excluirSolicitacao() {
        document.listaInscricao.action.value="confirmarExclusao";
        document.listaInscricao.submit();
    }

</script>

<?php
$controlaBotao = "";
$classBotao="confirmar";
$turmasAuxiliar;
$acaoFormulario;

//OBTEM AS TURAS A SEREM LISTADAS FILTRANDO PELOS CASOS DE USO
if($casoDeUso=="UC02.06.00"){
    $turmasAuxiliar = $turmasLiberadas;
    $acaoFormulario= "SolicitarInscricaoEmTurmas_controle.php?action=confirmarSolicitacao";
}elseif($casoDeUso=="UC02.06.02"){
    $turmasAuxiliar =$turmasSolicitadas;
    $acaoFormulario= "ExcluirSolicitacaoDeInscricaoEmTurmas_controle.php?action=confirmarExclusao";
}

//VERIFICA RN06
if($limiteSolicitacoesRN06 || $alunoRN12) {
    echo"<table class='table_RN' border=1>";
    echo"<caption class='RN_cabecalho'>";
    $alerta = htmlspecialchars("Atenção para a seguinte regra: ", ENT_QUOTES, "iso-8859-1");
    echo $alerta;
    echo"</caption>";

    // CASO EXISTA RN06
    if($limiteSolicitacoesRN06) {
        $controlaBotao="disabled='disabled'";
        $classBotao="desabilitado";
        echo"<tr>";
        echo"<td class='td_fundoRN'>RN06</td>";
        echo"<td class='td_textoRN'>";
        $regra = htmlspecialchars("Um Aluno não pode se inscrever para mais do que ".
                Config::MAX_SOLICS_POR_ALUNO . " turmas  neste período letivo", ENT_QUOTES, "iso-8859-1");
        echo $regra."</td>";
        echo"</tr>";
    }
    //FIM RN06

    // CASO EXISTA RN12
    if($alunoRN12) {
        echo"<tr>";
        echo"<td class='td_fundoRN'>RN12</td>";
        echo"<td class='td_textoRN'>";
        $regra = htmlspecialchars("Um Aluno cuja matrícula exceda 5 (cinco) anos ".
                "só poderá ter sua solicitação de inscrição em turma ".
                "aceita com a expressa anuência do Coordenador Acadêmico.", ENT_QUOTES, "iso-8859-1");
        echo $regra . "</td>";
        echo"</tr>";
    }
    //FIM RN12

    echo"</table><br/>";
}
?>

<?php
if($casoDeUso==$SOLICITAR_INSCRICOES_EM_TURMAS) {
?>
<form name="formMostraOfertaTurnoDiferente" action="SolicitarInscricaoEmTurmas_controle.php#ofertas">
    <input type="hidden" name="action" value="listar" />
    <input type="checkbox" name="mostraOfertaTurnoDiferente" id="mostraOfertaTurnoDiferente"
           value="SIM" onchange="javascript:document.formMostraOfertaTurnoDiferente.submit();"
        <?php if(!isset($mostraOfertaTurnoDiferente) || $mostraOfertaTurnoDiferente==false) echo ''; else echo 'checked="checked"'; ?> />
    <span style="font-size: medium;">Mostrar ofertas ainda n&atilde;o solicitadas de outros turnos (diferentes do turno da <?php echo $matriculaAluno->getTurnoIngresso();?>)</span>
</form>
<?php
}
?>

<a name="ofertas" />

    <table class="table_lista" width="100%" border=0 id="Lista">
        <caption class="cabecalho">
            Lista de Turmas Liberadas para o Per&iacute;odo <?php echo $periodoLetivo->getSiglaPeriodoLetivo();?>
        </caption>
        <tr align=center class="tr_cabecalho_lista">
            <td width=60px>Turno </td>
            <td width=80px>Grade Hor&aacute;ria</td>
            <td>Disciplina</td>
            <td width=100px>Pr&eacute;-Requisito</td>
            <td>Horário da Aula</td>
            <td>A&#231;&#227;o</td>
        </tr>

        <?php
        $turma= array();
        $contaLinha=0;
        
        // INICIA OS PROCEDIMENTOS PARA EXIBICAO DA LINHA
        foreach ($turmasAuxiliar as $turmasListadas) {

             if($casoDeUso==$SOLICITAR_INSCRICOES_EM_TURMAS) {
               
                //// OBTEM A INFORMACAO SE O ALUNO JA HOUVE APROVACAO NA DISCIPLINA OU JA REQUERIU NO PRESENTE PERIODO
                //RN05
             
                // VERIFICA SE O ALUNO JA CUMPRIU O COMPONENTE CURRICULAR;
                $cc = ComponenteCurricular::obterComponenteCurricular($turmasListadas->getSiglaCurso(), $turmasListadas->getIdMatriz(), $turmasListadas->getSiglaDisciplina());
                $quitacaoTO = $cc->obterQuitacao($matriculaAluno);

                // Verifica se o aluno já solicitou inscrição nessa oferta
                $jaRequerida = Inscricao::alunoJaRequereuInscricaoTurma($turmasListadas->getIdTurma(), $matriculaAluno->getMatriculaAluno());

                // Verifica seletor na interface e decide se deve exibir ou não oferta
                // de turma em turno diferente do de ingresso do aluno
                if( ( !isset($mostraOfertaTurnoDiferente) || $mostraOfertaTurnoDiferente==false)
                        && $jaRequerida==false
                        && $turmasListadas->getTurno() != $matriculaAluno->getTurnoIngresso() ) {
                    continue;
                }

                //Verifica se a disciplina ja foi aprovada ou requerida (RN05)
                if($quitacaoTO==null) {
                  $nomeBotao = htmlspecialchars("Solicitar Inscrição", ENT_QUOTES, "iso-8859-1");
                  require "$BASE_DIR/siro/formularios/carregarListaDeTurmas.php";

                }//FIM VERIFICACAO RN05
            } elseif($casoDeUso==$EXCLUIR_SOLICITACAO_INSCRICAO_TURMA) {
                $nomeBotao = htmlspecialchars("Excluir Inscrição", ENT_QUOTES, "iso-8859-1");
                require "$BASE_DIR/siro/formularios/carregarListaDeTurmas.php";
            }

        } //FIM FOREACH

        ?>
    </table>