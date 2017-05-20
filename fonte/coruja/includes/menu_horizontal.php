<?php
    $usuario=$_SESSION["usuario"];
    if( ($usuario==null) || !isset($usuario) ) {
        trigger_error("Não foi possível identificar o usuário autenticado.",E_USER_ERROR);
    }
?>
<div class="hauptmenue" align="center">
    <table><tbody><tr><td>
        <ul class="dropdown">
            <li><a href="/coruja/baseCoruja/index.php">In&iacute;cio</a></li>
<?php
    if( $usuario->getPerfil() == Usuario::ADMINISTRADOR ) {
?>
            <li><a href="#">Cadastro</a>
         	    <ul>                    
                    <li><a href="/coruja/baseCoruja/controle/manterAluno_controle.php?acao=consultar">Aluno</a></li>
                    <li><a href="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=selecionarCurso">Administrar Matr&iacute;culas</a></li>
                    <li><a id="linkMenuPeriodoLetivo" href="/coruja/siro/controle/PeriodoLetivo_controle.php?action=curso">Per&iacute;odo Letivo</a></li>
                    <li><a href="/coruja/interno/selecionar_matricula_professor/selecionarMatricula_controle.php?acao=exibirFiltroPesquisa">Professor</a></li>
                    <li><a href="/coruja/interno/manter_espaco/manterEspaco_controle.php?acao=listar">Espa&ccedil;o</a></li>
                    <li><a href="/coruja/interno/manter_tipocurso/manterTpcurso_controle.php?acao=listar">Tipo de Curso</a></li>
                    <li><a href="/coruja/interno/manter_curso/manterCurso_controle.php?acao=listar">Curso</a></li>
                    </ul>
            </li>                       
            <li><a href="">Emiss&atilde;o</a>
                <ul>
                    <li><a href="/coruja/nort/controle/emitirDiarioDeClasse_controle.php">Diário de Classe</a></li>
                    <li><a href="/coruja/interno/emitir_grade_horario/GradeHorario_controle.php">Grade de Hor&aacute;rio</a></li>
                    <li><a href="/coruja/nort/controle/emitirHistoricoEscolar_controle.php?acao=buscarMatricula">Hist&oacute;rico Escolar</a></li>
                    <li><a href="/coruja/interno/emitir_hist_concl_controle/emitirHistConcl_controle.php?action=consultar">Hist&oacute;rico de Conclu&iacute;do</a></li>
                    <li><a href="/coruja/interno/emitir_ocupacao_espelho/emitirOcupacaoEspelho_controle.php?acao=emitirEspaco">Espelho de Ocupa&ccedil;&atilde;o de Espa&ccedil;o</a></li>
                    <li><a href="/coruja/nort/controle/emitirRelatorioDeAlunosPorSituacao_controle.php">Alunos Por Situa&ccedil;&atilde;o</a></li>
                    <li><a href="/coruja/nort/controle/emitirListaDeAlunosPorTurma_controle.php">Lista de Alunos Por Turma</a></li>
                    <li><a href="/coruja/nort/controle/emitirFichaDeMatricula_controle.php?acao=buscarMatricula">Ficha de Matr&iacute;cula</a></li>
                    <li><a href="/coruja/interno/exportar_dados_carteira/exportarDadosCarteira_controle.php">Exportar Dados para Carteira de Estudante</a></li>
                    <li><a href="/coruja/interno/alocacao_professor/emitirAlocacao_professor_controle.php?action=AlocacaoProfessor">Aloca&ccedil;&atilde;o de Professor</a></li>
                    <li><a href="/coruja/interno/resumo_alocacao_professor/emitirResumoAlocacao_professor_controle.php?action=ResumoAlocacaoProfessor">Resumo de Aloca&ccedil;&atilde;o de Professores</a></li>
                    <li><a href="/coruja/interno/emitir_decl_matr_aluno/emitirDeclMatrAluno_controle.php">Declara&ccedil;&atilde;o de Matr&iacute;cula</a></li>
                </ul>
            </li>
            <li><a href="#">Turmas</a>
                <ul>
                    <li><a href="/coruja/nort/controle/manterTurmas_controle.php">Manter Turmas</a></li>
                    <li><a href="/coruja/nort/controle/lancarNotas_controle.php">Lan&ccedil;ar Notas</a></li>
                    <li><a href="/coruja/siro/controle/ManterAlunosQueCursamTurma_controle.php?act=main">Manter Alunos que cursam uma Turma</a></li>
                </ul>
            </li>        
            <li><a href="#">Inscrições</a>
                <ul>
                    <li><a href="/coruja/siro/controle/ExibirResultadoSolicitacaoInscricao_controle.php?act=main">Exibir Resultado da Solicita&ccedil;&atilde;o de Inscri&ccedil;&atilde;o</a></li>
                    <li><a href="/coruja/siro/controle/SolicitarInscricaoEmTurmas_controle.php?act=main">Solicitar Inscri&ccedil;&atilde;o em Turma</a></li>
                    <li><a href="/coruja/siro/controle/ManterSituacaoInscricaoTurma_controle.php?action=curso">Situa&ccedil;&atilde;o de Inscri&ccedil;&otilde;es em Turmas</a></li>
                </ul>
            </li>
            <li><a href="#">Permissões</a>
                <ul>
                  <li><a href="/coruja/mmc_gpl/manterPermissao/buscarFuncionario_controle.php">Permissões</a></li>
                  <li><a href="/coruja/mmc_gpl/manterPermissao/grupoPermissoes_controle.php">Grupos</a></li>
                  <li><a href="/coruja/mmc_gpl/manterPermissao/gerenciaLog_controle.php">Gerência de Log</a></li>
              </ul>
            </li>
            <li><a href="#">Matriz Curricular</a>
                <ul>
                  <li><a href="/coruja/mmc_gpl/matrizCurricular/listaMatrizCurricularProposta_controle.php">Matriz Proposta </a></li>
                  <li><a href="/coruja/mmc_gpl/matrizCurricular/imprimirMatriz/imprimirMatriz_controle.php">Imprimir Matriz </a></li>
              </ul>
            </li>
            <li style="border: 0px none ;"><a href="#"><nobr>Ajuda</nobr></a></li>        
<?php
    } else if($usuario->getPerfil() == Usuario::ALUNO ) {
?>
            <li><a href="#">Inscri&ccedil;&otilde;es</a>
                <ul>
                    <li><a href="/coruja/siro/controle/SolicitarInscricaoEmTurmas_controle.php?action=listar">Solicitar Inscri&ccedil;&atilde;o</a></li>
                    <li><a href="/coruja/siro/controle/EmitirGradeHoraria_controle.php">Emitir Grade Hor&aacute;ria do Per&iacute;odo Vigente</a></li>
                </ul>
            </li>
            <li><a href="">Emiss&atilde;o</a>
                <ul>
                    <li><a href="/coruja/nort/controle/emitirFichaDeMatricula_controle.php?acao=gerarPDFproprioAluno">Ficha de Matr&iacute;cula</a></li>

                </ul>
            </li>
<?php
    } else if($usuario->getPerfil() == Usuario::PROFESSOR) {
?>
            <li><a href="/coruja/espacoProfessor/index_controle.php?acao=exibirIndex">Pauta Eletr&ocirc;nica</a></li>
<?php
    }
?>
        </ul>
        </td></tr></tbody></table>
</div>