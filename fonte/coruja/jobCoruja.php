<?php
$BASE_DIR = __DIR__;
require_once "$BASE_DIR/config.php";
require_once("$BASE_DIR/classes/BD.php");
require_once("$BASE_DIR/classes/Mensagem.php");
require_once("$BASE_DIR/classes/PeriodoLetivo.php");
require_once("$BASE_DIR/classes/Curso.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");
require_once("$BASE_DIR/classes/Login.php");
require_once("$BASE_DIR/classes/Util.php");
require_once("$BASE_DIR/classes/RegraBloqueioMatriculaAluno.php");
require_once("$BASE_DIR/classes/util/KLogger.php");

// DIR_AREA_DADOS configurado config.php
$logger = new KLogger ( Config::DIR_AREA_DADOS . "/log.txt", KLogger::INFO );
$logger->LogInfo("Iniciado o jobCoruja!");

// Envia N mensagens
$con = BD::conectar();
$query = sprintf("select * from Mensagem M inner join MensagemPessoa MP
    on M.idMensagem = MP.idMensagem
    inner join Pessoa P on MP.idPessoa = P.idPessoa
    where
    MP.tentouEmail = 'NÃO'
    order by M.dataMensagem
    limit %d",
        Config::QTDE_EMAILS_POR_LOTE);
$result = mysql_query($query, $con);
while($linha = mysql_fetch_array($result)) {
    $email = $linha["email"];
    $assunto = $linha["assunto"];
    $texto = $linha["texto"];
    $nome = $linha["nome"];

    try {
        mysql_query("BEGIN", $con); // Inicia transação
        if( $email != NULL && Util::check_email_address($email) ) {
            Util::enviarEmail($email, $assunto, $texto);
            $ignorarEnvioEmail = false;
        } else {
            $ignorarEnvioEmail = true;
        }
        $idMensagem = $linha["idMensagem"];
        $idPessoa = $linha["idPessoa"];
        $mensagem = Mensagem::obterMensagemPorId($idMensagem, $idPessoa);
        $mensagem->marcarComoEnviadoPorEmailPara($idPessoa, $con);

        mysql_query("COMMIT", $con);
        if($ignorarEnvioEmail) {
            $logger->LogInfo( sprintf("Ignorado o envio de email para %s pois email %s eh invalido.", $nome, $email ) );
        } else {
            $logger->LogInfo("Tentado o envio de email para $nome. Email $email. idMensagem=" . $idMensagem);
        }
    } catch(Exception $ex) {
        $logger->LogError($ex->getMessage() . "\n" . $ex->getTraceAsString());
        mysql_query("ROLLBACK", $con);
        exit;
    }
}

// Processa bloqueio automatica de contas

$logger->LogInfo(".: INICIO PROCESSAMENTO BLOQUEIO AUTOMATIVO :.");
$aConfigCursos = explode(";", Config::BLOQUEIO_AUTOMATICO);
foreach( $aConfigCursos as $configCurso)
{
    $aParamConfigCurso = explode(",", $configCurso);
    if( count( $aParamConfigCurso) < 2 )
    {
        $logger->LogInfo(">>> ERRO: parametro Config::BLOQUEIO_AUTOMATICO incorreto: " . Config::BLOQUEIO_AUTOMATICO);
        break;
    }
    $siglaCurso = $aParamConfigCurso[0];

    $curso = Curso::obterCurso( $siglaCurso);
    if( $curso == null)
    {
        $logger->LogInfo(">>> ERRO: curso de sigla $siglaCurso nao encontrado. Abortando resto dos bloqueios.");
        break;
    }

    $logger->LogInfo(">>> Curso $siglaCurso. Analisando se pode disparar bloqueios automaticos...");
    try
    {
        $pl = PeriodoLetivo::obterPeriodoLetivoAtual( $siglaCurso);

    } catch (Exception $ex) {
        $logger->LogInfo(">>> Nao existe periodo letivo atual para esse curso. Ignorando.");
        continue;
    }

    if( $pl->getRodouBloqueioAutomatico() )
    {
        $logger->LogInfo("Ja rodou bloqueio automatico para o periodo letivo "
                . $pl->getSiglaPeriodoLetivo() . " deste curso. Ignorando este curso.");
        continue;
    }

    $matriculasAluno = MatriculaAluno::obterMatriculasAtivas( $curso);

    $relacaoBloqueados = array();

    foreach( $matriculasAluno as $matriculaAluno)
    {
        $idPessoa = $matriculaAluno->getIdPessoa();
        $nomeAcesso = $matriculaAluno->getNumMatriculaAluno();
        $login = Login::obterLoginPorIdPessoa( $matriculaAluno->getIdPessoa());

        if( $login == null) // se não houver login, criar um login inicial
        {
            $senha = "Inicial123";
            try
            {
                Login::criarLogin( $idPessoa, $nomeAcesso, $senha);
            }
            catch (Exception $ex)
            {
                $logger->LogInfo("Nao foi possivel criar primeiro login para $nomeAcesso. Ignorando essa matricula.");
                continue;
            }
            $logger->LogInfo("Login $nomeAcesso criado pela primeira vez.");
            continue;
        }

        // se houver login não-bloqueado
        if( !$login->isBloqueado() )
        {
            // verificar regras aplicáveis ao curso
            for($ir = 1; $ir < count( $aParamConfigCurso); $ir++ )
            {
                $idRegra = $aParamConfigCurso[ $ir];

                try
                {
                    $regra = RegraBloqueioMatriculaAluno::getInstancia( $idRegra, $matriculaAluno);
                }
                catch (Exception $ex)
                {
                    $logger->LogError( $ex->getMessage());
                }

                $deveBloquear = $regra->deveBloquear( $matriculaAluno);

                if( $deveBloquear)
                {
                    $motivoResumido = $regra->getTextoResumidoMotivo();
                    $motivoCompleto = $regra->getTextoCompleto();
                    try
                    {
                        Login::bloquear( $nomeAcesso, $motivoCompleto);
                    }
                    catch (Exception $ex)
                    {
                        $logger->LogInfo("Nao foi possivel bloquear login para $nomeAcesso. Ignorando essa matricula.");
                        break;
                    }
                    $logger->LogInfo("O login $nomeAcesso foi bloqueado por '$motivoResumido'");

                    $nomeAluno = $matriculaAluno->getAluno()->getNome();
                    $numMatriculaAluno = $matriculaAluno->getNumMatriculaAluno();
                    $nomeCurso = $curso->getNomeCurso();
                    $mensagemParaAluno = "Prezado(a) $nomeAluno,\n\n" .
                            "Sua conta no Coruja foi bloqueada (motivo abaixo). "
                            . "Procure com urgência a secretaria "
                            . " para que sua matrícula ($numMatriculaAluno) no curso '$nomeCurso' possa ser regularizada.\n\n "
                            . "Motivo do bloqueio: " . $motivoCompleto;
                    $aIdPessoa = array();
                    $aIdPessoa[] = $idPessoa;
                    Mensagem::depositarMensagem("Conta Coruja Bloqueada", $mensagemParaAluno, $aIdPessoa);

                    $relacaoBloqueados[]= $matriculaAluno->getAluno()->getNome() . " (" .
                            $matriculaAluno->getNumMatriculaAluno() . ") teve o login bloqueado. Motivo: " .
                            $regra->getTextoResumidoMotivo();
                    break; // se já bloqueou, não precisa bloquear por outro motivo
                }
            }
        }
    } // matrícula do curso

    $mensagemAdm = implode("\n", $relacaoBloqueados);
    $aIdPessoaSecretaria = array();
    $aIdPessoaSecretaria[] = Config::SECRETARIA_ID_PESSOA;
    Mensagem::depositarMensagem("Contas Coruja Bloqueadas Curso " . $siglaCurso,
            $mensagemAdm, $aIdPessoaSecretaria);

    $pl->registrarRodadaBloqueioAutomatico();
}
$logger->LogInfo(".: FIM PROCESSAMENTO BLOQUEIO AUTOMATIVO :.");

// TODO não é melhor juntar essas classes? Usuario e Login?

$diasAtraso = 15;
$periodoEnvioEmDias = 30; // em dias
$arq_rel_leitura = @fopen( Config::DIR_AREA_DADOS . "/controle_rel_status_apontamento.txt","r");
$data_ult_envio = null;
if( $arq_rel_leitura)
{
    $ult_rel_str = fgets( $arq_rel_leitura);
    $data_ult_envio = new DateTime( $ult_rel_str);
    fclose( $arq_rel_leitura);
}
if( $data_ult_envio == null || Util::isDiasOuMaisAntesDeHoje( $data_ult_envio, $periodoEnvioEmDias))
{
    $pdo = BD::conectarPDO();
    $stmt = $pdo->query( "select
    PR.nomeGuerra as nome,
	concat( T.siglaCurso, '/', PL.siglaPeriodoLetivo , '/', T.siglaDisciplina, '/', T.turno, '/', T.gradeHorario) as pauta,
    max(DLT.data) as ult_apontamento
from Professor PR inner join MatriculaProfessor MP on PR.idPessoa = MP.idPessoa
    inner join Turma T on T.matriculaProfessor = MP.matriculaProfessor
    inner join PeriodoLetivo PL on PL.idPeriodoLetivo = T.idPeriodoLetivo
    left outer join DiaLetivoTurma DLT on T.idTurma = DLT.idTurma and DLT.dataLiberacao is not null
where MP.dataEncerramento is null
and T.tipoSituacaoTurma = 'CONFIRMADA'
group by
	PR.nomeGuerra,
	T.siglaCurso,
    PL.siglaPeriodoLetivo,
	T.siglaDisciplina,
    T.turno,
    T.gradeHorario");
    $atrasados = "";
    $emDia = "";
    while( $reg = $stmt->fetch())
    {
        if( !isset( $reg["ult_apontamento"]))
        {
            $textoUltApontamento = "NENHUM";
        }
        else
        {
            $textoUltApontamento = Util::dataSQLParaBr( $reg["ult_apontamento"]);
        }
        $registroPauta = utf8_decode( str_pad($reg["nome"], 15, " ") . " " . str_pad( $reg["pauta"], 25, " ") . " " . $textoUltApontamento . "\n" );
        if( $reg["ult_apontamento"] == null || Util::isDiasOuMaisAntesDeHoje( new DateTime( $reg["ult_apontamento"]), $diasAtraso) )
        {
            $atrasados .= $registroPauta;
        }
        else 
        {
            $emDia .= $registroPauta;
        }
    }
    $atrasadosOuNinguem = ($atrasados === "" ? "(ninguem)" : $atrasados);
    $emDiaOuNinguem = ($emDia === "" ? "(ninguem)" : $emDia);
    $msgRelStatusApontamento = "ATRASADOS (nunca apontaram ou não liberam apontamento há mais de " . $diasAtraso . " dias):\n\n"
            . $atrasadosOuNinguem
            . "\nEM DIA (liberaram apontamentos nos últimos " . $diasAtraso . " dias):\n\n" . $emDiaOuNinguem;
    $aIdPessoaSecretaria = array();
    $aIdPessoaSecretaria[] = Config::SECRETARIA_ID_PESSOA;
    Mensagem::depositarMensagem("Relatorio Status Apontamento",
        $msgRelStatusApontamento, $aIdPessoaSecretaria);
    
    $logger->LogInfo("Relatorio status de apontamento foi enviado.");

    $arq_rel_escrita = @fopen( Config::DIR_AREA_DADOS . "/controle_rel_status_apontamento.txt","w");
    if( $arq_rel_escrita)
    {
        $agora = new DateTime();
        fwrite( $arq_rel_escrita, $agora->format( "Y-m-d") );
        fclose( $arq_rel_escrita);
    }
    else
    {
        $logger->LogError( "Erro ao escrever data atual no arquivo de controle do rel.status apontamento");
    }
        
}
else
{
  $logger->LogInfo("Relatorio status de apontamento já foi enviado.");
}

$logger->LogInfo("Finalizado o jobCoruja!");
