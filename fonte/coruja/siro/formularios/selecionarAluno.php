<?php

echo"<link href='/coruja/estilos/tabelas.css' rel='stylesheet' type='text/css' />";
echo"<link href='/coruja/estilos/botoes.css' rel='stylesheet' type='text/css' />";

        $buscaPessoa .= "<br /><b>Aluno(s) encontrado(s)!</b><br />";

        foreach($busca as $itens)
        {
            // buscar os itens que sao da classe pessoa
            //$pessoa = $classePessoa->pega_pessoa($itens->getIdPessoa());
            $buscaPessoa .= "<form id='cadastro' action='SolicitarInscricaoEmTurmas_controle.php?action=idPessoa' method='post'>";
            $buscaPessoa .= "<fieldset id='fieldsetGeral'>";

        // PARÂMETROS QUE DEFINEM A AÇAO A SER EXECUTADA
            $buscaPessoa .= "<input type='hidden' name='acao' value='procurar'>";

            $buscaPessoa .= "<input type='hidden' name='idPessoa' value='" . $itens->getIdPessoa() . "'>";
            $buscaPessoa .= "<input type='hidden' name='nome' value='" . $itens->getNomeAluno() . "'>";
            $buscaPessoa .= "<input type='hidden' name='matriculaAluno' value='" . $itens->getMatriculaAluno() . "'>";
            $buscaPessoa .= "<br />";
            $buscaPessoa .= "<label>Matricula: </label>" . $itens->getMatriculaAluno();
            $buscaPessoa .= "<br />";
            $buscaPessoa .= "<label>Nome: </label>" . $itens->getNomeAluno();
            $buscaPessoa .= "<br />";
            $buscaPessoa .= "<label>Curso: </label>" . $itens->getSiglaCurso()." (".$itens->getNomeCurso().")";
            $buscaPessoa .= "<br />";
            $buscaPessoa .= "<label>Situacao da Matrícula: </label>" . $itens->getSituacaoMatricula();
            $buscaPessoa .= "<br />";
            $buscaPessoa .= "<br />";
            if($itens->getSituacaoMatricula()=="CURSANDO" || $itens->getSituacaoMatricula()=="TRANCADO") {
                $buscaPessoa .= "<center><input type='submit' value='  Solicitar Inscrição  ' /></center>";
            }
            else{
                $buscaPessoa .= "<center><font color=#F00>Situação da Matrícula do Aluno impede a Solicitação de Inscrição</font></center>";
            }
            $buscaPessoa .= "</fieldset>";
            $buscaPessoa .= "</form>";
        }

        echo $buscaPessoa;
?>

<form name="voltar" id="voltar" action="SelecionarAlunoParaInscricao_controle.php?action=selecionaAluno" method="post">
    <input id='button1' type='submit' value='  Voltar  ' >
</form>