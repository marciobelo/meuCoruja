<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/Turma.php";

if( !$login->temPermissao( "UC02.01.01")) {
    echo "-2";
    exit;
}

$idTurma = filter_input(INPUT_POST, "idTurma", FILTER_SANITIZE_NUMBER_INT);
$turma = Turma::getTurmaById( $idTurma);

$inscricoes = Inscricao::obterInscricoesAlunos($idTurma, "'" . Inscricao::REQ . "'");

$qtdeJaDeferido = count( Inscricao::obterInscricoesAlunos($idTurma, "'" . Inscricao::DEF . "'"));
$qtdeJaCursando = count( Inscricao::obterInscricoesAlunos($idTurma, "'" . Inscricao::CUR . "'"));
$qtdeDisponivel = $turma->getQtdeTotal() - $qtdeJaDeferido - $qtdeJaCursando;

$qtdeTotalAutomatico = 0;
$inscricoesParaDeferir = array();
foreach ( $inscricoes as $inscricao) 
{
    if( !$inscricao->isTemRestricoes()) // não tem restrições
    {
        $qtdeTotalAutomatico++;
        $inscricoesParaDeferir[] = $inscricao;
    }
}

if( count( $inscricoesParaDeferir) > $qtdeDisponivel)
{
    echo "-1"; exit; // mais requisicoes do que vagas
}
else
{
    // defere automaticamente os alunos
    foreach( $inscricoesParaDeferir as $inscricaoParaDeferir)
    {
        $inscricaoParaDeferir->deferirInscricao( "Deferimento automático");
    }
    echo $qtdeTotalAutomatico;
}