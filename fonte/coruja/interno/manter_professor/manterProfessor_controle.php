<?php
require_once "../../includes/comum.php";
require_once("$BASE_DIR/classes/Pessoa.php");
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/MatriculaProfessor.php");
require_once("$BASE_DIR/classes/MatriculaProfessor.php");
require_once("$BASE_DIR/classes/Util.php");
require_once "$BASE_DIR/interno/manter_professor/ManterProfessorForm.php";
require_once "$BASE_DIR/interno/manter_professor/ManterMatriculaProfForm.php";
$acao = $_REQUEST["acao"];

if($acao=="ExibirDados") {

    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($MANTER_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $professor=Professor::obterProfessorPorMatricula($_REQUEST['matriculaProfessor']);
    require_once("$BASE_DIR/interno/manter_professor/exibe_dados_professor.php");
    exit;
} else if($acao=="novoCadastro") {

    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($INCLUIR_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    require_once("$BASE_DIR/interno/manter_professor/novo_professor.php");
    exit;
} else if($acao==="salvarProfessor") {

    require_once "$BASE_DIR/classes/Pessoa.php";
    require_once "$BASE_DIR/classes/Professor.php";
    require_once "$BASE_DIR/classes/MatriculaProfessor.php";
    require_once "$BASE_DIR/interno/manter_professor/ManterProfessorForm.php";

    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($INCLUIR_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $formProfessor = new ManterProfessorForm();
    $formProfessor->atualizarDadosForm();

    // Validações
    $msgsErro = $formProfessor->validarDados();
    if( count($msgsErro) == 0) {

        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação

            $idPessoa = Pessoa::inserirPessoa($formProfessor->nome, $formProfessor->sexo, $formProfessor->enderecoLogradouro,
                    $formProfessor->enderecoNumero, $formProfessor->enderecoComplemento,
                    $formProfessor->enderecoBairro, $formProfessor->enderecoMunicipio,
                    $formProfessor->enderecoEstado, $formProfessor->enderecoCEP,
                    $formProfessor->getDataNascimento(),
                    $formProfessor->nacionalidade,
                    $formProfessor->naturalidade,
                    $formProfessor->getTelefoneResidencial(),
                    $formProfessor->getTelefoneComercial(),
                    $formProfessor->getTelefoneCelular(),
                    $formProfessor->email,
                    $con);

            Professor::inserirProfessor($idPessoa,
                    $formProfessor-titulacaoAcademica,
                    $formProfessor->cvLattes,
                    $formProfessor->nomeGuerra,
                    $formProfessor->corFundo,
                    $con );

            MatriculaProfessor::criarMatriculaProfessor($idPessoa,$formProfessor->novaMatriculaProfessor,
                    $formProfessor->cargaHoraria,
                    $formProfessor->getDataInicio(),
                    $formProfessor->getDataEncerramento(),
                    $con);

            // Insere dados no Log

            $strLog = "Inserido o professor com os dados:<br/> Matricula ->" .$formProfessor->novaMatriculaProfessor." Nome ->".$formProfessor->nome;

            $login->incluirLog($INCLUIR_PROFESSOR,$strLog,$con);

            mysql_query("COMMIT", $con);

        } catch (Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            require_once("$BASE_DIR/interno/manter_professor/novo_professor.php");
            exit;
        }
        // Exibe mensagem de sucesso e remete para o cadastro do professor
        $msgsErro=array();
        array_push($msgsErro, "Novo professor inserido com sucesso.");
        unset($_POST);
        require_once("$BASE_DIR/interno/manter_professor/novo_professor.php");
        exit;

    } else {

        require_once("$BASE_DIR/interno/manter_professor/novo_professor.php");
    }

    // Exibe mensagem de sucesso e remete para o cadastro do professor
    $msgs=array();
    array_push($msgs, "Novo professor inserido com sucesso.");
    $_REQUEST["idPessoa"] = $idPessoa;
    require_once("$BASE_DIR/interno/manter_professor/novo_professor.php");
    exit;

} 
else if($acao=="preparaEdicaoProfessor") 
{
    require_once "$BASE_DIR/interno/manter_professor/ManterProfessorForm.php";

    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($ALTERAR_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $idPessoa = $_REQUEST["idPessoa"];
    $formProfessor = new ManterProfessorForm("edicao");
    $professor = Professor::getProfessorByIdPessoa($idPessoa);
    $formProfessor->atualizarDadosProfessor($professor);

    require_once("$BASE_DIR/interno/manter_professor/editar_professor.php");

} 
else if($acao === "salvarProfessorEditado") 
{
    require_once "$BASE_DIR/classes/Professor.php";
    require_once "$BASE_DIR/classes/MatriculaProfessor.php";
    require_once "$BASE_DIR/interno/manter_professor/ManterProfessorForm.php";

    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($ALTERAR_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $formProfessor = new ManterProfessorForm("edicao");
    $formProfessor->atualizarDadosForm();

    // Validações
    $msgsErro = $formProfessor->validarDados();
    if( count($msgsErro) != 0 ) {
        require_once("$BASE_DIR/interno/manter_professor/editar_professor.php");
        exit;
    }

    $idPessoa = $formProfessor->idPessoa;

    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação

        $professorAntes = Professor::getProfessorByIdPessoa($idPessoa);

        Pessoa::atualizar(
                $idPessoa,
                $formProfessor->nome,
                $formProfessor->sexo,
                $formProfessor->enderecoLogradouro,
                $formProfessor->enderecoNumero,
                $formProfessor->enderecoComplemento,
                $formProfessor->enderecoBairro,
                $formProfessor->enderecoMunicipio,
                $formProfessor->enderecoEstado,
                $formProfessor->enderecoCEP,
                $formProfessor->getDataNascimento(),
                $formProfessor->nacionalidade,
                $formProfessor->naturalidade,
                $formProfessor->getTelefoneResidencial(),
                $formProfessor->getTelefoneComercial(),
                $formProfessor->getTelefoneCelular(),
                $formProfessor->email,
                $con);

        Professor::atualizar( $idPessoa,
                $formProfessor->titulacaoAcademica,
                $formProfessor->cvLattes,
                $formProfessor->nomeGuerra,
                $formProfessor->corFundo,
                $con );

        $professorDepois = Professor::getProfessorByIdPessoa($idPessoa);

        $strLog = "Dados do Professor " . $professorAntes->getNome() . " alterados:<br/>";
        $strLog .= Util::obterEntradasLogAlteradas($professorAntes->toString(),$professorDepois->toString());
        $login->incluirLog($ALTERAR_PROFESSOR,$strLog,$con);

        mysql_query("COMMIT", $con);
    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());
        require_once("$BASE_DIR/interno/manter_professor/editar_professor.php");
        exit;
    }

    // Exibe mensagem de sucesso e remete para consulta ao aluno
    $msgsErro = array();
    array_push($msgsErro, "Dados do professor alterados com sucesso.");
    $professor = Professor::getProfessorByIdPessoa($idPessoa);

    require_once("$BASE_DIR/interno/manter_professor/exibe_dados_professor.php");
    exit;

} 
else if($acao=="preparaEdicaoMatricula") 
{
    require_once "$BASE_DIR/interno/manter_professor/ManterMatriculaProfForm.php";

    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($ALTERAR_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $idPessoa = $_REQUEST["idPessoa"];
    $matriculaProfessor = $_REQUEST["matriculaProfessor"];

    $formMatricula = new ManterMatriculaProfForm("edicao");
    $formMatricula->atualizarDadosMatricula($idPessoa,$matriculaProfessor);

    require_once("$BASE_DIR/interno/manter_professor/editar_matricula_professor.php");

} 
else if($acao=="salvarMatriculaEditada") 
{
    require_once("$BASE_DIR/classes/MatriculaProfessor.php");
    require_once("$BASE_DIR/classes/Professor.php");
    require_once("$BASE_DIR/interno/manter_professor/ManterMatriculaProfForm.php");
    $idPessoa = $_REQUEST["idPessoa"];
    $formMatricula = new ManterMatriculaProfForm();
    $formMatricula->atualizarDadosForm();

    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($ALTERAR_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação
        if($formMatricula->modo=="edicao") {

            $msgsErro = $formMatricula->validarDados();
            if(count($msgsErro)>0) {
                require_once("$BASE_DIR/interno/manter_professor/editar_matricula_professor.php");
                exit;
            }

            MatriculaProfessor::atualizar($idPessoa,
                    $formMatricula->matriculaProfessorAntiga,
                    $formMatricula->matriculaProfessorNova,
                    $formMatricula->cargaHoraria,
                    $formMatricula->getDataInicio(),
                    $formMatricula->getDataEncerramento(), $con);
        } else { // "novo"

            $msgsErro = $formMatricula->validarDados();
            if(count($msgsErro)>0) {
                require_once("$BASE_DIR/interno/manter_professor/nova_matricula_professor.php");
                exit;
            }
            MatriculaProfessor::criarMatriculaProfessor($idPessoa,
                    $formMatricula->matriculaProfessorNova,
                    $formMatricula->getCargaHoraria(),
                    $formMatricula->getDataInicio(),
                    $formMatricula->getDataEncerramento(),
                    $con);
        }

        // Salvar log de alteração ou criação de matrícula
        $strLog="";
        $uc="";
        $professor = Professor::getProfessorByIdPessoa($idPessoa);
        $nome = $professor->getNome();
        if($formMatricula->modo=="edicao") {
            $strLog .= "Matrícula do Professor $nome alterada<br/>";
            $strLog .= "Nº Matrícula Antiga: " . $formMatricula->matriculaProfessorAntiga . "<br/>";
            $uc = $ALTERAR_PROFESSOR;
        } else {
            $strLog="Matrícula do professor $nome inserida.<br/>";
            $uc = $INCLUIR_PROFESSOR;
        }
        $strLog .= "Nº Matrícula Nova: " . $formMatricula->matriculaProfessorNova . "<br/>";
        $strLog .= "Carga Horária: " . $formMatricula->cargaHoraria . "<br/>";
        $strLog .= "Data de Início: " .
            Util::dataSQLParaBr($formMatricula->getDataInicio()) . "<br/>";
        $strLog .= "Data de Encerramento: " . 
            Util::dataSQLParaBr($formMatricula->getDataEncerramento()) . "<br/>";
        $login->incluirLog($uc,  $strLog, $con);

        mysql_query("COMMIT", $con);
    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);
        $msgsErro=array();
        array_push($msgsErro, $ex->getMessage());

        require_once("$BASE_DIR/interno/manter_professor/editar_matricula_professor.php");
        exit;
    }

    // Exibe mensagem de sucesso e remete para consulta ao Professor
    $msgsErro=array();
    $msgOK = $formMatricula->modo=="edicao" ? "Matrícula alterada com sucesso." :"Matrícula criada com sucesso.";
    array_push($msgsErro, $msgOK);

    $professor = Professor::obterProfessorPorMatricula($_REQUEST["matriculaProfessorNova"]);
    require_once("$BASE_DIR/interno/manter_professor/exibe_dados_professor.php");
    exit;

} 
else if( $acao === "preparaNovaMatricula") 
{
    // Verifica antes se usuário tem permissão
    if(!$login->temPermissao($ALTERAR_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    require_once("$BASE_DIR/interno/manter_professor/nova_matricula_professor.php");
    
} else {
    trigger_error("Ação não identificada.",E_USER_ERROR);
}