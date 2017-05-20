<?php
require_once "$BASE_DIR/classes/Pessoa.php";   
require_once "$BASE_DIR/classes/Aluno.php";   
require_once "$BASE_DIR/classes/MatriculaAluno.php";

class BuscaAluno 
{
    /**
    * BUSCA PESSOA EXISTENTE
    * 
    * @param mixed $tipoBusca - nome, cpf, matrícula
    * @param mixed $string    - parâmetro da busca
    */

    function retornaBusca($tipoBusca,$string)
    {
      
        if($tipoBusca == 'nome') {
            $string = trim($string);
            $classePessoa = new Pessoa();
            
            $busca=Pessoa::obterPessoasPorNome($string);

        } elseif($tipoBusca == 'cpf') {
            // retirar espaços em branco das extremidades da string
            $string = trim($string);
            
            $aluno = Aluno::obterAlunoPorCPF($string);
            $busca = array();
            if($aluno!=null) {
                $id = $aluno->getIdPessoa();
                $classePessoa = new Pessoa();
                $pessoa = Pessoa::obterPessoaPorId($id);
                array_push($busca,$pessoa);
            }
        } elseif($tipoBusca == 'matricula') {
            $matriculaAluno = MatriculaAluno::obterMatriculaAluno($string);
            $busca = array();
            if($matriculaAluno!=null) {
                $id = $matriculaAluno->getIdPessoa();
                $pessoa = Pessoa::obterPessoaPorId($id);
                array_push($busca,$pessoa);
            }
        }            
        
        if(empty($busca)) {
   
            
            $buscaPessoa .= "<br /><b>Nenhum registro com os par&acirc;metros informados!</b><br /><br />";
            
            // CADASTRAR NOVO 
            $buscaPessoa .= "<form id='novoCadastro' action='/coruja/baseCoruja/controle/manterAluno_controle.php' method='post'>";
            $buscaPessoa .= "<fieldset id='fieldsetGeral'>";
            
            // PARÂMETROS QUE DEFINEM A AÇAO A SER EXECUTADA    
            $buscaPessoa .= "<input type='hidden' name='acao' value='novoCadastro'>";
            $buscaPessoa .= "<center><input type='submit' value='Cadastrar Novo Aluno' /></center>";
            $buscaPessoa .= "</fieldset>";
            $buscaPessoa .= "</form>";

                // NOVA BUSCA
                $buscaPessoa .= "<form id='consultar' action='/coruja/baseCoruja/controle/manterAluno_controle.php' method='post'>";
                $buscaPessoa .= "<fieldset id='fieldsetGeral'>";
            
                // PARÂMETROS QUE DEFINEM A AÇAO A SER EXECUTADA
                $buscaPessoa .= "<input type='hidden' name='acao' value='consultar'>";
                
                $buscaPessoa .= "<center><input  type='submit' value='Realizar Nova Busca' /></center>";
                $buscaPessoa .= "</fieldset>";
                $buscaPessoa .= "</form>";

        } else {
            $buscaPessoa .= "<br /><b>Registro(s) encontrado(s)!</b><br />";
            $buscaPessoa .= "<small>Caso n&atilde;o encontre o aluno, ao final da lista haver&aacute; a op&ccedil;&atilde;o <b>Cadastrar Novo Aluno</b></small>.<br/>";

                
            foreach($busca as $itens) {
                $buscaPessoa .= "<form id='cadastro' action='/coruja/baseCoruja/controle/manterAluno_controle.php' method='post'>";
                $buscaPessoa .= "<fieldset id='fieldsetGeral'>";
                // PARÂMETROS QUE DEFINEM A AÇAO A SER EXECUTADA
                $buscaPessoa .= "<input type='hidden' name='acao' value='exibirAluno'>";
                
                $buscaPessoa .= "<input type='hidden' name='idPessoa' value='" . $itens->getIdPessoa() . "'>";
                $buscaPessoa .= "<table>";
                $buscaPessoa .= "<tr>";
                $buscaPessoa .= "<td>";
                $buscaPessoa .= "<img src=\"/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=" . $itens->getIdPessoa() . "\" height=\"70px\" width=\"70px\" />";
                $buscaPessoa .= "</td>";
                $buscaPessoa .= "<td>";
                $buscaPessoa .= "<label>Nome: </label>" . htmlspecialchars($itens->getNome(), ENT_QUOTES, "iso-8859-1");
                $buscaPessoa .= "<br />";
                $buscaPessoa .= "<label>Nascimento: </label>" . Util::formataData($itens->getDataNascimento());
                $buscaPessoa .= "<br />";
                $buscaPessoa .= "<label>Email: </label>" . htmlspecialchars($itens->getEmail(), ENT_QUOTES, "iso-8859-1");
                $buscaPessoa .= "</td>";
                $buscaPessoa .= "</tr>";
                $buscaPessoa .= "</table>";
                $buscaPessoa .= "<br />";
                $buscaPessoa .= "<center><input type='submit' value='Ver Completo' /></center>";
                $buscaPessoa .= "</fieldset>";
                $buscaPessoa .= "</form>";
            }
            
            // CADASTRAR UM NOVO
            $buscaPessoa .= "<form id='cadastro' action='/coruja/baseCoruja/controle/manterAluno_controle.php' method='post'>";
            $buscaPessoa .= "<fieldset id='fieldsetGeral'>";

            // PARÂMETROS QUE DEFINEM A AÇAO A SER EXECUTADA
            $buscaPessoa .= "<input type='hidden' name='acao' value='novoCadastro'>";
            $buscaPessoa .= "<input type='hidden' name='nome' value='" . $itens->getNome() . "'>";
            $buscaPessoa .= "Deseja cadastrar um novo Aluno?";
            $buscaPessoa .= "<br />";

            $buscaPessoa .= "<center><input type='submit' value='Cadastrar Novo Aluno' /></center>";
            $buscaPessoa .= "</fieldset>";
            $buscaPessoa .= "</form>";
            
        }
        return $buscaPessoa;
    }

}