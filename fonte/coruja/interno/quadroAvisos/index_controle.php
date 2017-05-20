<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Usuario.php");
require_once("$BASE_DIR/classes/Mensagem.php");

$usuario = $_SESSION["usuario"];
$acao = $_REQUEST["acao"];

switch( $acao ) 
{
    case "exibir":
        
        $idMensagem = filter_input(INPUT_GET, "idMensagem");
        if( !isset($idMensagem) ) {
            $mensagem = Mensagem::obterMensagemNaoLidaMaisAntiga( $usuario->getIdPessoa() );
            
            if( $mensagem == null ) {
                $mensagem = Mensagem::obterMensagemMaisRecente( $usuario->getIdPessoa() );
            }
        } else {
            $mensagem = Mensagem::obterMensagemPorId( $idMensagem, $usuario->getIdPessoa() );
            
        }
        $totalMensagem = Mensagem::obterTotalMensagem( $usuario->getIdPessoa() );
        
        $ultimasMensagens= Mensagem::obterUltimasMensagens( $usuario->getIdPessoa() );
        if( $mensagem == null ) {
            $posicaoMensagem = "?";
        } else {
            $pos = 1;
            $encontrou = false;
            foreach($ultimasMensagens as $mensagemUlt) {
                if( $mensagem->getIdMensagem() == $mensagemUlt->getIdMensagem() ) {
                    $encontrou = true;
                    break;
                }
                $pos++;
            }
            if( $encontrou ) {
                $posicaoMensagem = $pos;
            } else {
                $posicaoMensagem = "?";
            }
            
            $idMensagemAnterior = $mensagem->obterIdMensagemAnterior( $usuario->getIdPessoa() );
            $idMensagemPosterior = $mensagem->obterIdMensagemPosterior( $usuario->getIdPessoa() );
        }
        
        include_once "$BASE_DIR/interno/quadroAvisos/formVisualizaMensagem.php";
        break;
    case "darCiencia":
        $idMensagem = $_POST["idMensagem"];
        $idPessoa = $usuario->getIdPessoa();
        $mensagem = Mensagem::obterMensagemPorId($idMensagem, $idPessoa);
        $mensagem->marcarComoLidaPor($idPessoa);
        Header("Location: /coruja/interno/quadroAvisos/index_controle.php?acao=exibir&idMensagem=" . $idMensagem);
        exit;
}