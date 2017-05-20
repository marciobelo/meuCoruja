<?php
/**
 * @author: Helder Nascimento
 * @name: busca_cad_aluno.php
 * @version: 1.0
 * @since: versão 1.0 
 */
 require_once("../includes/comum.php");   

include_once "$BASE_DIR/baseCoruja/classes/formulario.class.php";

class buscaCadAluno
{
    // pessoa
    private $idPessoa;
    private $nome;
    private $sexo;
    private $dataNascimento;
    private $dataNascimentoBr;
    private $nacionalidade;
    private $naturalidade;
    private $enderecoCEP;
    private $enderecoLogradouro;
    private $enderecoNumero;
    private $enderecoComplemento;
    private $enderecoBairro;
    private $enderecoMunicipio;
    private $enderecoEstado;
    private $telefoneResidencial;
    private $telefoneComercial;
    private $telefoneCelular;
    private $email;
    // aluno
    private $nomeMae;
    private $rgMae;
    private $rgMaeNumero;
    private $rgMaeEmissor;
    private $rgMaeUF;
    private $nomePai;
    private $nomePaiNumero;
    private $nomePaiEmissor;
    private $nomePaiUF;
    private $rgPai;
    private $rgNumero;
    private $rgDataEmissao;
    private $rgOrgaoEmissor;
    private $cpf;
    private $cpfProprio;
    private $certidaoNascimentoNumero;
    private $certidaoNascimentoLivro;
    private $certidaoNascimentoFolha;
    private $certidaoNascimentoCidade;
    private $certidaoNascimentoSubdistrito;
    private $certidaoNascimentoUF;
    private $certidaoCasamentoNumero;
    private $certidaoCasamentoLivro;
    private $certidaoCasamentoFolha;
    private $certidaoCasamentoCidade;
    private $certidaoCasamentoSubdistrito;
    private $certidaoCasamentoUF;
    private $estabCursoOrigem;
    private $estabCursoOrigemUF;
    private $cursoOrigemAnoConclusao;
    private $modalidadeCursoOrigem;
    private $ctps;
    private $corRaca;
    private $estadoCivil;
    private $deficienciaVisual;
    private $deficienciaMotora;
    private $deficienciaAuditiva;
    private $deficienciaMental;
    private $responsavelLegal;
    private $rgResponsavel;
    private $tituloEleitorNumero;
    private $tituloEleitorData;
    private $tituloEleitorZona;
    private $tituloEleitorSecao;
    private $certificadoAlistamentoMilitarNumero;
    private $certificadoAlistamentoMilitarSerie;
    private $certificadoAlistamentoMilitarData;
    private $certificadoAlistamentoMilitarRM;
    private $certificadoAlistamentoMilitarCSM;
    private $certificadoReservistaNumero;
    private $certificadoReservistaSerie;
    private $certificadoReservistaData;
    private $certificadoReservistaCAT;
    private $certificadoReservistaRM;
    private $certificadoReservistaCSM;
    private $matriculaAluno;
    private $dataMatricula;
    private $dataMatriculaBr;
    private $turnoIngresso;
    private $idPeriodoLetivo;
    private $siglaCurso;
    private $idMatriz;
    private $situacaoMatricula;
    private $idFormaIngresso;
    private $concursoPontos;
    private $concursoClassificacao;
    private $nomeCurso;
    private $descricao;
    private $siglaPeriodoLetivo;
    private $dataInicioVigencia;
    private $quant_matriculas;
    private $opcao;

    
    function cadAluno($idPessoa)
    {
        $con = BD::conectar();
        $this->idPessoa = $idPessoa;
        
    // BUSCA AS INFORMAÇÕES
        $sqlPessoa ="SELECT Pessoa.*, Aluno.* FROM Pessoa";
        $sqlPessoa.=" INNER JOIN Aluno";
        $sqlPessoa.=" ON Aluno.idPessoa=Pessoa.idPessoa";
        $sqlPessoa.=" WHERE Pessoa.idPessoa = '$this->idPessoa'";
        
    // VERIFICA SE A PESSOA POSSUI MAIS DE UMA MATRICULA
        $checaMatricula ="SELECT MatriculaAluno.* FROM MatriculaAluno WHERE MatriculaAluno.idPessoa = '$this->idPessoa'";
        $res_checaMatricula = mysql_query($checaMatricula,$con) or die(mysql_error());

            $this->quant_matriculas = mysql_num_rows($res_checaMatricula);
         
    // EXECUTA O QUERY
        $resPessoa = mysql_query($sqlPessoa,$con) or die(mysql_error());

        $rowPessoa = mysql_fetch_array($resPessoa);

        $this->nome = $rowPessoa['nome'];
        $this->sexo = $rowPessoa['sexo'];
        $this->dataNascimento = $rowPessoa['dataNascimento'];
        $this->dataNascimentoBr = explode('-',$rowPessoa['dataNascimento']); //separa a string por "-"
        $this->dataNascimentoBr = implode('/',array_reverse($this->dataNascimentoBr)); // reverte a ordem da string quebrada acima e monta novamente no novo formato DD/MM/YYYY

        $this->nacionalidade = $rowPessoa['nacionalidade'];
        $this->naturalidade = $rowPessoa['naturalidade'];
        $this->enderecoCEP = $rowPessoa['enderecoCEP'];
        $this->enderecoLogradouro = $rowPessoa['enderecoLogradouro'];
        $this->enderecoNumero = $rowPessoa['enderecoNumero'];
        $this->enderecoComplemento = $rowPessoa['enderecoComplemento'];
        $this->enderecoBairro = $rowPessoa['enderecoBairro'];
        $this->enderecoMunicipio = $rowPessoa['enderecoMunicipio'];
        $this->enderecoEstado = $rowPessoa['enderecoEstado'];
        $this->telefoneResidencial = $rowPessoa['telefoneResidencial'];
        $this->telefoneComercial = $rowPessoa['telefoneComercial'];
        $this->telefoneCelular = $rowPessoa['telefoneCelular'];
        $this->email = $rowPessoa['email'];
        
        //  BUSCA NA TABELA ALUNO

        $this->nomeMae = $rowPessoa['nomeMae'];
        $this->rgMae = $rowPessoa['rgMae'];

        $this->nomePai = $rowPessoa['nomePai'];
        $this->rgPai = $rowPessoa['rgPai'];

        $this->rgNumero = $rowPessoa['rgNumero'];
        $this->rgOrgaoEmissor = $rowPessoa['rgOrgaoEmissor'];
        $this->rgDataEmissao = $rowPessoa['rgDataEmissao'];
        $this->rgDataEmissaoBr = explode('-',$rowPessoa['rgDataEmissao']); //separa a string por "-"
        $this->rgDataEmissaoBr = implode('/',array_reverse($this->rgDataEmissaoBr)); // reverte a ordem da string quebrada acima e monta novamente no novo formato DD-MM-YYYY
        $this->cpf = $rowPessoa['cpf'];

        $this->certidaoNascimentoNumero = $rowPessoa['certidaoNascimentoNumero'];
        $this->certidaoNascimentoLivro = $rowPessoa['certidaoNascimentoLivro'];
        $this->certidaoNascimentoFolha = $rowPessoa['certidaoNascimentoFolha'];
        $this->certidaoNascimentoCidade = $rowPessoa['certidaoNascimentoCidade'];
        $this->certidaoNascimentoSubDistrito = $rowPessoa['certidaoNascimentoSubDistrito'];
        $this->certidaoNascimentoUF = $rowPessoa['certidaoNascimentoUF'];

        $this->certidaoCasamentoNumero = $rowPessoa['certidaoCasamentoNumero'];
        $this->certidaoCasamentoLivro = $rowPessoa['certidaoCasamentoLivro'];
        $this->certidaoCasamentoFolha = $rowPessoa['certidaoCasamentoFolha'];
        $this->certidaoCasamentoCidade = $rowPessoa['certidaoCasamentoCidade'];
        $this->certidaoCasamentoSubDistrito = $rowPessoa['certidaoCasamentoSubDistrito'];
        $this->certidaoCasamentoUF = $rowPessoa['certidaoCasamentoUF'];

        $this->estabCursoOrigem = $rowPessoa['estabCursoOrigem'];
        $this->estabCursoOrigemUF = $rowPessoa['estabCursoOrigemUF'];
        $this->cursoOrigemAnoConclusao = $rowPessoa['cursoOrigemAnoConclusao'];
        $this->modalidadeCursoOrigem = $rowPessoa['modalidadeCursoOrigem'];

        $this->ctps = $rowPessoa['ctps'];

        $this->estadoCivil = $rowPessoa['estadoCivil'];
        $this->corRaca = $rowPessoa['corRaca'];
        $this->possuiNecessidadeEspecial = $rowPessoa['possuiNecessidadeEspecial'];

        $this->responsavelLegal = $rowPessoa['responsavelLegal'];
        $this->rgResponsavel = $rowPessoa['rgResponsavel'];

        $this->tituloEleitorNumero = $rowPessoa['tituloEleitorNumero'];
        $this->tituloEleitorData = $rowPessoa['tituloEleitorData'];
        $this->tituloEleitorDataBr = explode('-',$rowPessoa['tituloEleitorData']); //separa a string por "-"
        $this->tituloEleitorDataBr = implode('/',array_reverse($this->tituloEleitorDataBr)); // reverte a ordem da string quebrada acima e monta novamente no novo formato DD-MM-YYYY
        $this->tituloEleitorZona = $rowPessoa['tituloEleitorZona'];
        $this->tituloEleitorSecao = $rowPessoa['tituloEleitorSecao'];

        $this->certificadoAlistamentoMilitarNumero = $rowPessoa['certificadoAlistamentoMilitarNumero'];
        $this->certificadoAlistamentoMilitarSerie = $rowPessoa['certificadoAlistamentoMilitarSerie'];
        $this->certificadoAlistamentoMilitarData = $rowPessoa['certificadoAlistamentoMilitarData'];
        $this->certificadoAlistamentoMilitarDataBr = explode('-',$rowPessoa['certificadoAlistamentoMilitarData']); //separa a string por "-"
        $this->certificadoAlistamentoMilitarDataBr = implode('/',array_reverse($this->certificadoAlistamentoMilitarDataBr)); // reverte a ordem da string quebrada acima e monta novamente no novo formato DD-MM-YYYY
        $this->certificadoAlistamentoMilitarRM = $rowPessoa['certificadoAlistamentoMilitarRM'];
        $this->certificadoAlistamentoMilitarCSM = $rowPessoa['certificadoAlistamentoMilitarCSM'];

        $this->certificadoReservistaNumero = $rowPessoa['certificadoReservistaNumero'];
        $this->certificadoReservistaSerie = $rowPessoa['certificadoReservistaSerie'];
        $this->certificadoReservistaData = $rowPessoa['certificadoReservistaData'];
        $this->certificadoReservistaDataBr = explode('-',$rowPessoa['certificadoReservistaData']); //separa a string por "-"
        $this->certificadoReservistaDataBr = implode('/',array_reverse($this->certificadoReservistaDataBr)); // reverte a ordem da string quebrada acima e monta novamente no novo formato DD-MM-YYYY
        $this->certificadoReservistaCAT = $rowPessoa['certificadoReservistaCAT'];
        $this->certificadoReservistaRM = $rowPessoa['certificadoReservistaRM'];
        $this->certificaoReservistaCSM = $rowPessoa['certificaoReservistaCSM'];
    }

/**
* PEGAR INFORMAÇÕES DE MATRÍCULA
* @var $idPessoa
* @var $opção  : exibir ou editar
*     
*/
    public function matAluno($idPessoa,$opcao)
    {
    $con = BD::conectar();
    $this->idPessoa = $idPessoa; 
    $this->opcao = $opcao; 
    
        /*
        *  tanto a tabela periodoletivo quanto a formaingresso possuem um campo chamado 'descricao'
        * por isso foram dados aliases para ambos, para evitar conflitos
        * 
        */
        $sqlMat ="SELECT MatriculaAluno.*, Curso.*, ".
                "FormaIngresso.idFormaIngresso, FormaIngresso.descricao AS descI, ".
                "MatrizCurricular.*, ".
                "PeriodoLetivo.idPeriodoLetivo, PeriodoLetivo.siglaPeriodoLetivo AS descP ".
                "FROM MatriculaAluno ".
                "INNER JOIN Curso ".
                "ON MatriculaAluno.siglaCurso = Curso.siglaCurso ".
                "INNER JOIN FormaIngresso ".
                "ON MatriculaAluno.idFormaIngresso = FormaIngresso.idFormaIngresso ".
                "INNER JOIN MatrizCurricular ".
                "ON MatriculaAluno.idMatriz = MatrizCurricular.idMatriz ".
                "INNER JOIN PeriodoLetivo ".
                "ON MatriculaAluno.idPeriodoLetivo = PeriodoLetivo.idPeriodoLetivo ".
                "WHERE MatriculaAluno.idPessoa = '$this->idPessoa'";
        
    
        $resMat = mysql_query($sqlMat,$con) or die(mysql_error());
    
        $nmat=1;   
        while($rowMat = mysql_fetch_array($resMat))
        {
        
            $this->matriculaAluno = $rowMat['matriculaAluno'];
            $this->dataMatricula = $rowMat['dataMatricula'];
            $this->dataMatriculaBr = explode('-',$rowMat['dataMatricula']); //separa a string por "-"
            $this->dataMatriculaBr = implode('/',array_reverse($this->dataMatriculaBr)); // reverte a ordem da string quebrada acima e monta novamente no novo formato DD/MM/YYYY
            $this->turnoIngresso = $rowMat['turnoIngresso'];
            $this->idPeriodoLetivo = $rowMat['idPeriodoLetivo'];
            $this->siglaCurso = $rowMat['siglaCurso'];
            $this->idMatriz = $rowMat['idMatriz'];
            $this->situacaoMatricula = $rowMat['situacaoMatricula'];
            $this->idFormaIngresso = $rowMat['idFormaIngresso'];
            $this->concursoPontos = $rowMat['concursoPontos'];
            $this->concursoClassificacao = $rowMat['concursoClassificacao'];

            $this->nomeCurso = $rowMat['nomeCurso'];
    
            /*
            *  tanto a tabela periodoletivo quanto a formaingresso possuem um campo chamado 'descricao'
            * por isso foram dados aliases para ambos, para evitar conflitos
            * 
            */
            $this->descI = $rowMat['descI'];
            $this->descP = $rowMat['descP'];
            
            $this->dataInicioVigencia = $rowMat['dataInicioVigencia'];
            $this->dataInicioVigenciaBr = explode('-',$rowMat['dataInicioVigencia']); //separa a string por "-"
            $this->dataInicioVigenciaBr = implode('/',array_reverse($this->dataInicioVigenciaBr)); // reverte a ordem da string quebrada acima e monta novamente no novo formato DD/MM/YYYY
            
        
            echo "<a class='pmais' id='ico" .$this->matriculaAluno. "' onfocus='blur()' ".
                "href=\"javascript:showP('" .$this->matriculaAluno. "');\"> Matr&iacute;cula: <b> " .$this->matriculaAluno. " </a>";
             echo "<br />" .$this->siglaCurso. ": " .$this->nomeCurso. "</b><br /><br />"; 
             
             echo "<div id='p" .$this->matriculaAluno. "' style='display: none;'>";   
             
            
            
            
            
            if($this->opcao == 'editar')
            {
                
                    echo formulario::inputHidden('dataMatricula',$this->dataMatricula);
                     
                 // Matricula 
                     echo formulario::inputLabel('Matr&iacute;cula');
                     echo formulario::inputText('matriculaAluno','obrigatorio',$this->matriculaAluno,'15','','','this.value=this.value.toUpperCase();');
                     echo '<br />';
                 
                 //data atual   
                     echo formulario::inputLabel('Data Matr&iacute;cula');
                     echo $this->dataMatriculaBr;
                     echo '<br />';

             
             
                 // Turno de ingresso    
                     echo formulario::inputLabel('Turno de Ingresso');
                     echo formulario::inputSelectEnum('turnoIngresso','matriculaaluno','turnoIngresso',$this->turnoIngresso);    
                     echo '<br />';
                     echo '<br />';
                
                // periodo letivo
                        echo formulario::inputLabel('Per&iacute;odo Letivo');
                        echo formulario::inputValorId('idPeriodoLetivo','periodoletivo',$this->idPeriodoLetivo,'siglaPeriodoLetivo');
                        echo formulario::inputHidden('idPeriodoLetivo',$this->idPeriodoLetivo);
                        echo "<br />";    
                    
                // curso
                        echo formulario::inputLabel('Curso');
                        echo formulario::inputValorId('siglaCurso','curso',$this->siglaCurso,'nomeCurso');
                        echo formulario::inputHidden('siglaCurso',$this->siglaCurso);
                        echo "<br />";    
                
                // Matriz Curricular
                        echo formulario::inputLabel('Matriz Curricular');
                        echo formulario::inputValorId('idMatriz','matrizcurricular',$this->idMatriz,'dataInicioVigencia');
                        echo formulario::inputHidden('idMatriz',$this->idMatriz);
                        echo "<br />";    
                
                // Situação da Matrícula
                        echo formulario::inputLabel('Situa&ccedil;&atilde;o da Matr&iacute;cula');
                        echo formulario::inputSelectEnum('situacaoMatricula','matriculaaluno','situacaoMatricula',$this->situacaoMatricula);
                        echo '<br /><br />';  
                 
                 // Informações do ingresso por concurso
                        echo formulario::inputLabel('CONCURSO');
                        echo '<br /><br />';
                        
                 // Forma de Ingresso
                        echo formulario::inputLabel('Forma de Ingresso');
                        echo formulario::inputValorId('idFormaIngresso','formaingresso',$this->idFormaIngresso,'descricao');
                        echo formulario::inputHidden('idFormaIngresso',$this->idFormaIngresso);
                        echo '<br /><br />';   
                  
                  // pontuação e classificação
                     echo formulario::inputLabel('Pontua&ccedil;&atilde;o');
                     echo formulario::inputText('concursoPontos','',$this->concursoPontos,'4','','    Se houver decimais, use ponto para separar.','');
                     echo '&nbsp;&nbsp;Classifica&ccedil;&atilde;o: ';
                     echo formulario::inputText('concursoClassificacao','',$this->concursoClassificacao,'3','','','');
                     echo '<br />';       

             
                    echo "<br />";
                    echo "<br />";
                    echo "<br />";
                
                
            }
            elseif($this->opcao == 'exibir')
            {
            
            
            // matricula
                echo formularioExibir::inputLabel('Matr&iacute;cula',$this->getMatriculaAluno());
                echo "<br />";
        
            // data da matricula
                echo formularioExibir::inputLabel('Data',$this->dataMatriculaBr);
                echo "<br />";
        
            // curso
                echo formularioExibir::inputLabel('Curso',$this->siglaCurso);
                echo "<br />";
        
            // situação da matricula
                echo formularioExibir::inputLabel('Situa&ccedil;&atilde;o da Matr&iacute;cula',$this->situacaoMatricula);
                echo "<br />";
                
                echo "<div align='center'>";
                echo "<a class='pmais' id='ico$nmat' onfocus='blur()' href=\"javascript:showP('$nmat');\">".
                     " <b>Clique aqui para ver detalhes</b></a><br /><br />";
                echo "</div>";
                
                echo "<div id='p$nmat' style='display:none;'>";   
             
            // turno de ingresso
                echo formularioExibir::inputLabel('Turno de Ingresso',$this->turnoIngresso);
                echo "<br />";
            
            // periodo letivo
                echo formularioExibir::inputLabel('Per&iacute;odo Letivo',$this->descP);
                echo "<br />";
            
            // Matriz Curricular
                echo formularioExibir::inputLabel('Matriz Curricular',$this->dataInicioVigenciaBr);
                echo "<br />";
                echo '<br />';
        
            // INFORMAÇÕES DE CONCURSO
                echo formularioExibir::inputLabel('<b>CONCURSO</b>','');
                echo "<br />";
                echo '<br />';
        
            // forma de ingresso
        
               echo formularioExibir::inputLabel('Forma de Ingresso',$this->descI);
               echo "<br />";

            // pontos no concurso
                echo formularioExibir::inputLabel('Pontua&ccedil;&atilde;o',$this->concursoPontos);
                echo "<br />";
        
            // classificação
                echo formularioExibir::inputLabel('Classifica&ccedil;&atilde;o',$this->concursoClassificacao);
                
                echo "<br />";
                echo "<br />";
                echo "<br />";
                
                echo '</div>';
            
            
            }
                
                echo '</div>';
                
            $nmat = $nmat+1; 
            
        }    
    }
    
    
    public function getNome() {
        return $this->nome;
    }
    
    public function getSexo() {
        return $this->sexo;
    }
    
    public function getEnderecoLogradouro() {
        return $this->enderecoLogradouro;
    }

    public function getEnderecoNumero() {
        return $this->enderecoNumero;
    }

    public function getEnderecoComplemento() {
        return $this->enderecoComplemento;
    }

    public function getEnderecoBairro() {
        return $this->enderecoBairro;
    }

    public function getEnderecoMunicipio() {
        return $this->enderecoMunicipio;
    }

    public function getEnderecoEstado() {
        return $this->enderecoEstado;
    }

    public function getEnderecoCEP() {
        return $this->enderecoCEP;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getCep() {
        return $this->cep;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function getDataNascimentoD() {
        $dataNascimento = explode('-',$this->dataNascimento); //separa a string por "-"
        $this->dataNascimentoD = $dataNascimento[2]; 
        return $this->dataNascimentoD;
    }

    public function getDataNascimentoM() {
        $dataNascimento = explode('-',$this->dataNascimento); //separa a string por "-"
        $this->dataNascimentoM = $dataNascimento[1]; 
        return $this->dataNascimentoM;
    }

    public function getDataNascimentoA() {
        $dataNascimento = explode('-',$this->dataNascimento); //separa a string por "-"
        $this->dataNascimentoA = $dataNascimento[0]; 
        return $this->dataNascimentoA;
    }

    public function getDataNascimentoBr() {
        return $this->dataNascimentoBr;
    }

    public function getNacionalidade() {
        return $this->nacionalidade;
    }

    public function getNaturalidade() {
        return $this->naturalidade;
    }

    public function getTelefoneResidencial() {
        return $this->telefoneResidencial;
    }

    public function getTelefoneResidencialDDD() {
        $telefoneResidencial = explode('-',$this->telefoneResidencial); //separa a string por "-"
        $this->telefoneResidencialDDD = $telefoneResidencial[0]; 
        return $this->telefoneResidencialDDD;
    }

    public function getTelefoneResidencialA() {
        $telefoneResidencial = explode('-',$this->telefoneResidencial); //separa a string por "-"
        $this->telefoneResidencialA = $telefoneResidencial[1]; 
        return $this->telefoneResidencialA;
    }

    public function getTelefoneResidencialB() {
        $telefoneResidencial = explode('-',$this->telefoneResidencial); //separa a string por "-"
        $this->telefoneResidencialB = $telefoneResidencial[2]; 
        return $this->telefoneResidencialB;
    }

    public function getTelefoneComercial() {
        return $this->telefoneComercial;
    }

    public function getTelefoneComercialDDD() {
        $telefoneComercial = explode('-',$this->telefoneComercial); //separa a string por "-"
        $this->telefoneComercialDDD = $telefoneComercial[0]; 
        return $this->telefoneComercialDDD;
    }

    public function getTelefoneComercialA() {
        $telefoneComercial = explode('-',$this->telefoneComercial); //separa a string por "-"
        $this->telefoneComercialA = $telefoneComercial[1]; 
        return $this->telefoneComercialA;
    }

    public function getTelefoneComercialB() {
        $telefoneComercial = explode('-',$this->telefoneComercial); //separa a string por "-"
        $this->telefoneComercialB = $telefoneComercial[2]; 
        return $this->telefoneComercialB;
    }

    public function getTelefoneCelular() {
        return $this->telefoneCelular;
    }

    public function getTelefoneCelularDDD() {
        $telefoneCelular = explode('-',$this->telefoneCelular); //separa a string por "-"
        $this->telefoneCelularDDD = $telefoneCelular[0]; 
        return $this->telefoneCelularDDD;
    }

    public function getTelefoneCelularA() {
        $telefoneCelular = explode('-',$this->telefoneCelular); //separa a string por "-"
        $this->telefoneCelularA = $telefoneCelular[1]; 
        return $this->telefoneCelularA;
    }

    public function getTelefoneCelularB() {
        $telefoneCelular = explode('-',$this->telefoneCelular); //separa a string por "-"
        $this->telefoneCelularB = $telefoneCelular[2]; 
        return $this->telefoneCelularB;
    }

    public function getEmail() {
        return $this->email;
    }
    
    
    // ALUNO
    
    public function getNomeMae() {
        return $this->nomeMae;
    }

    public function getRgMae() {
        return $this->rgMae;
    }

    public function getRgMaeNumero() {
        $rgMaeNumero = explode(' ',$this->rgMae); //separa a string por " "
        $rgMaeNumero = $rgMaeNumero[0];
        $this->rgMaeNumero = $rgMaeNumero;
        return $this->rgMaeNumero;
    }

    public function getRgMaeEmissor() {
        $rgMaeEmissor = explode(' ',$this->rgMae); //separa a string por "/"
        $rgMaeEmissor = $rgMaeEmissor[1];
        
        $rgMaeEmissor = explode('/',$rgMaeEmissor); //separa a string por "/"
        $rgMaeEmissor = $rgMaeEmissor[0];
        
        $this->rgMaeEmissor = $rgMaeEmissor;
        return $this->rgMaeEmissor;
    }

    public function getRgMaeUF() {
        $rgMaeEmissor = explode(' ',$this->rgMae); //separa a string por "/"
        $rgMaeEmissor = $rgMaeEmissor[1];
        
        $rgMaeEmissor = explode('/',$rgMaeEmissor); //separa a string por "/"
        $rgMaeEmissor = $rgMaeEmissor[1];
        
        $this->rgMaeUF = $rgMaeEmissor;
        return $this->rgMaeUF;
    }

    public function getNomePai() {
        return $this->nomePai;
    }

    public function getRgPai() {
        return $this->rgPai;
    }

    public function getRgPaiNumero() {
        $rgPaiNumero = explode(' ',$this->rgPai); //separa a string por " "
        $rgPaiNumero = $rgPaiNumero[0];
        $this->rgPaiNumero = $rgPaiNumero;
        return $this->rgPaiNumero;
    }

    public function getRgPaiEmissor() {
        $rgPaiEmissor = explode(' ',$this->rgPai); //separa a string por "/"
        $rgPaiEmissor = $rgPaiEmissor[1];
        
        $rgPaiEmissor = explode('/',$rgPaiEmissor); //separa a string por "/"
        $rgPaiEmissor = $rgPaiEmissor[0];
        
        $this->rgPaiEmissor = $rgPaiEmissor;
        return $this->rgPaiEmissor;
    }

    public function getRgPaiUF() {
        $rgPaiEmissor = explode(' ',$this->rgPai); //separa a string por "/"
        $rgPaiEmissor = $rgPaiEmissor[1];
        
        $rgPaiEmissor = explode('/',$rgPaiEmissor); //separa a string por "/"
        $rgPaiEmissor = $rgPaiEmissor[1];
        
        $this->rgPaiUF = $rgPaiEmissor;
        return $this->rgPaiUF;
    }

    public function getRgNumero() {
        return $this->rgNumero;
    }

    public function getRgDataEmissao() {
        return $this->rgDataEmissao;
    }

    public function getRgDataEmissaoD() {
        $rgDataEmissao = explode('-',$this->rgDataEmissao); //separa a string por "-"
        $this->rgDataEmissaoD = $rgDataEmissao[2]; 
        return $this->rgDataEmissaoD;
    }

    public function getRgDataEmissaoM() {
        $rgDataEmissao = explode('-',$this->rgDataEmissao); //separa a string por "-"
        $this->rgDataEmissaoM = $rgDataEmissao[1]; 
        return $this->rgDataEmissaoM;
    }

    public function getRgDataEmissaoA() {
        $rgDataEmissao = explode('-',$this->rgDataEmissao); //separa a string por "-"
        $this->rgDataEmissaoA = $rgDataEmissao[0]; 
        return $this->rgDataEmissaoA;
    }

    public function getRgDataEmissaoBr() {
        return $this->rgDataEmissaoBr;
    }

    public function getRgOrgaoEmissor() {
        return $this->rgOrgaoEmissor;
    }

    public function getRgOrgaoEmissorA() {
        $rgOrgaoEmissor = explode('/',$this->rgOrgaoEmissor); //separa a string por "-"
        $this->rgOrgaoEmissorA = $rgOrgaoEmissor[0]; 
        return $this->rgOrgaoEmissorA;
    }

    public function getRgOrgaoEmissorUF() {
        $rgOrgaoEmissor = explode('/',$this->rgOrgaoEmissor); //separa a string por "-"
        $this->rgOrgaoEmissorUF = $rgOrgaoEmissor[1]; 
        return $this->rgOrgaoEmissorUF;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getCpfA() {
        $cpf = explode('/',$this->cpf); //separa a string por "-"
        $this->cpfA = $cpf[0]; 
        return $this->cpfA;
    }

    public function getCpfDig() {
        $cpf = explode('/',$this->cpf); //separa a string por "-"
        $this->cpfDig = $cpf[1]; 
        return $this->cpfDig;
    }

    public function getCpfProprio() {
        return $this->cpfProprio;
    }

    public function getCertidaoNascimentoNumero() {
        return $this->certidaoNascimentoNumero;
    }

    public function getCertidaoNascimentoLivro() {
        return $this->certidaoNascimentoLivro;
    }

    public function getCertidaoNascimentoFolha() {
        return $this->certidaoNascimentoFolha;
    }

    public function getCertidaoNascimentoCidade() {
        return $this->certidaoNascimentoCidade;
    }

    public function getCertidaoNascimentoSubdistrito() {
        return $this->certidaoNascimentoSubdistrito;
    }

    public function getCertidaoNascimentoUF() {
        return $this->certidaoNascimentoUF;
    }

    public function getCertidaoCasamentoNumero() {
        return $this->certidaoCasamentoNumero;
    }

    public function getCertidaoCasamentoLivro() {
        return $this->certidaoCasamentoLivro;
    }

    public function getCertidaoCasamentoFolha() {
        return $this->certidaoCasamentoFolha;
    }

    public function getCertidaoCasamentoCidade() {
        return $this->certidaoCasamentoCidade;
    }

    public function getCertidaoCasamentoSubdistrito() {
        return $this->certidaoCasamentoSubdistrito;
    }

    public function getCertidaoCasamentoUF() {
        return $this->certidaoCasamentoUF;
    }

    public function getEstabCursoOrigem() {
        return $this->estabCursoOrigem;
    }

    public function getEstabCursoOrigemUF() {
        return $this->estabCursoOrigemUF;
    }

    public function getCursoOrigemAnoConclusao() {
        return $this->cursoOrigemAnoConclusao;
    }

    public function getModalidadeCursoOrigem() {
        return $this->modalidadeCursoOrigem;
    }

    public function getCtps() {
        return $this->ctps;
    }

    /*
    * nos dois itens abaixo, retirar as strings numero e serie para retornar apenas os valores da ctps
    */
    
    public function getCtpsNumero() {
        $arrDe = array("Número: "," Série: ");
        $arrPara = array("","-");
        
        $ctps = $this->ctps;
        $ctps = str_replace($arrDe,$arrPara,$ctps);
        
        $ctps = explode('-',$ctps); //separa a string por "-"
        $this->ctpsNumero = $ctps[0]; 
        
        return $this->ctpsNumero;
    }

    public function getCtpsSerie() {
        $arrDe = array("Número: "," Série: ");
        $arrPara = array("","-");
        
        $ctps = $this->ctps;
        $ctps = str_replace($arrDe,$arrPara,$ctps);
        
        $ctps = explode('-',$ctps); //separa a string por "-"
        $this->ctpsSerie = $ctps[1]; 
        
        return $this->ctpsSerie;
    }

    public function getCorRaca() {
        return $this->corRaca;
    }

    public function getEstadoCivil() {
        return $this->estadoCivil;
    }

    public function getDeficienciaVisual() {
        return $this->deficienciaVisual;
    }

    public function getDeficienciaMotora() {
        return $this->deficienciaMotora;
    }

    public function getDeficienciaAuditiva() {
        return $this->deficienciaAuditiva;
    }

    public function getDeficienciaMental() {
        return $this->deficienciaMental;
    }

    public function getResponsavelLegal() {
        return $this->responsavelLegal;
    }

    public function getRgResponsavel() {
        return $this->rgResponsavel;
    }

    public function getRgResponsavelNumero() {
        $rgResponsavelNumero = explode(' ',$this->rgResponsavel); //separa a string por " "
        $rgResponsavelNumero = $rgResponsavelNumero[0];
        $this->rgResponsavelNumero = $rgResponsavelNumero;
        return $this->rgResponsavelNumero;
    }

    public function getRgResponsavelEmissor() {
        $rgResponsavelEmissor = explode(' ',$this->rgResponsavel); //separa a string por "/"
        $rgResponsavelEmissor = $rgResponsavelEmissor[1];
        
        $rgResponsavelEmissor = explode('/',$rgResponsavelEmissor); //separa a string por "/"
        $rgResponsavelEmissor = $rgResponsavelEmissor[0];
        
        $this->rgResponsavelEmissor = $rgResponsavelEmissor;
        return $this->rgResponsavelEmissor;
    }

    public function getRgResponsavelUF() {
        $rgResponsavelEmissor = explode(' ',$this->rgResponsavel); //separa a string por "/"
        $rgResponsavelEmissor = $rgResponsavelEmissor[1];
        
        $rgResponsavelEmissor = explode('/',$rgResponsavelEmissor); //separa a string por "/"
        $rgResponsavelEmissor = $rgResponsavelEmissor[1];
        
        $this->rgResponsavelUF = $rgResponsavelEmissor;
        return $this->rgResponsavelUF;
    }

    public function getTituloEleitorNumero() {
        return $this->tituloEleitorNumero;
    }

    public function getTituloEleitorNumeroA() {
        $tituloEleitorNumero = explode('-',$this->tituloEleitorNumero); //separa a string por "-"
        $this->tituloEleitorNumeroA = $tituloEleitorNumero[0]; 
        return $this->tituloEleitorNumeroA;
    }

    public function getTituloEleitorNumeroDig() {
        $tituloEleitorNumero = explode('/',$this->tituloEleitorNumero); //separa a string por "-"
        $this->tituloEleitorNumeroDig = $tituloEleitorNumero[1]; 
        return $this->tituloEleitorNumeroDig;
    }

    public function getTituloEleitorData() {
        return $this->tituloEleitorData;
    }

    public function getTituloEleitorDataD() {
        $tituloEleitorData = explode('-',$this->tituloEleitorData); //separa a string por "-"
        $this->tituloEleitorDataD = $tituloEleitorData[2]; 
        return $this->tituloEleitorDataD;
    }

    public function getTituloEleitorDataM() {
        $tituloEleitorData = explode('-',$this->tituloEleitorData); //separa a string por "-"
        $this->tituloEleitorDataM = $tituloEleitorData[1]; 
        return $this->tituloEleitorDataM;
    }

    public function getTituloEleitorDataA() {
        $tituloEleitorData = explode('-',$this->tituloEleitorData); //separa a string por "-"
        $this->tituloEleitorDataA = $tituloEleitorData[0]; 
        return $this->tituloEleitorDataA;
    }

    public function getTituloEleitorDataBr() {
        return $this->tituloEleitorDataBr;
    }

    public function getTituloEleitorZona() {
        return $this->tituloEleitorZona;
    }

    public function getTituloEleitorSecao() {
        return $this->tituloEleitorSecao;
    }

    public function getCertificadoAlistamentoMilitarNumero() {
        return $this->certificadoAlistamentoMilitarNumero;
    }

    public function getCertificadoAlistamentoMilitarSerie() {
        return $this->certificadoAlistamentoMilitarSerie;
    }

    public function getCertificadoAlistamentoMilitarData() {
        return $this->certificadoAlistamentoMilitarData;
    }

    public function getCertificadoAlistamentoMilitarDataD() {
        $certificadoAlistamentoMilitarData = explode('-',$this->certificadoAlistamentoMilitarData); //separa a string por "-"
        $this->certificadoAlistamentoMilitarDataD = $certificadoAlistamentoMilitarData[2]; 
        return $this->certificadoAlistamentoMilitarDataD;
    }

    public function getCertificadoAlistamentoMilitarDataM() {
        $certificadoAlistamentoMilitarData = explode('-',$this->certificadoAlistamentoMilitarData); //separa a string por "-"
        $this->certificadoAlistamentoMilitarDataM = $certificadoAlistamentoMilitarData[1]; 
        return $this->certificadoAlistamentoMilitarDataM;
    }

    public function getCertificadoAlistamentoMilitarDataA() {
        $certificadoAlistamentoMilitarData = explode('-',$this->certificadoAlistamentoMilitarData); //separa a string por "-"
        $this->certificadoAlistamentoMilitarDataA = $certificadoAlistamentoMilitarData[0]; 
        return $this->certificadoAlistamentoMilitarDataA;
    }

    public function getCertificadoAlistamentoMilitarDataBr() {
        return $this->certificadoAlistamentoMilitarDataBr;
    }

    public function getCertificadoAlistamentoMilitarRM() {
        return $this->certificadoAlistamentoMilitarRM;
    }

    public function getCertificadoAlistamentoMilitarCSM() {
        return $this->certificadoAlistamentoMilitarCSM;
    }

    public function getCertificadoReservistaNumero() {
        return $this->certificadoReservistaNumero;
    }

    public function getCertificadoReservistaSerie() {
        return $this->certificadoReservistaSerie;
    }

    public function getCertificadoReservistaData() {
        return $this->certificadoReservistaData;
    }

    public function getCertificadoReservistaDataD() {
        $certificadoReservistaData = explode('-',$this->certificadoReservistaData); //separa a string por "-"
        $this->certificadoReservistaDataD = $certificadoReservistaData[2]; 
        return $this->certificadoReservistaDataD;
    }

    public function getCertificadoReservistaDataM() {
        $certificadoReservistaData = explode('-',$this->certificadoReservistaData); //separa a string por "-"
        $this->certificadoReservistaDataM = $certificadoReservistaData[1]; 
        return $this->certificadoReservistaDataM;
    }

    public function getCertificadoReservistaDataA() {
        $certificadoReservistaData = explode('-',$this->certificadoReservistaData); //separa a string por "-"
        $this->certificadoReservistaDataA = $certificadoReservistaData[0]; 
        return $this->certificadoReservistaDataA;
    }

    public function getCertificadoReservistaDataBr() {
        return $this->certificadoReservistaDataBr;
    }

    public function getCertificadoReservistaCAT() {
        return $this->certificadoReservistaCAT;
    }

    public function getCertificadoReservistaRM() {
        return $this->certificadoReservistaRM;
    }
    
    public function getCertificadoReservistaCSM() {
        return $this->certificadoReservistaCSM;
    }
    
    // MATRICULA
    public function getQuant_matriculas() {
        return $this->quant_matriculas;
    }
    
    public function getMatriculaAluno() {
        return $this->matriculaAluno;
    }
    
    public function getDataMatricula() {
        return $this->dataMatricula;
    }
    
    public function getDataMatriculaBr() {
        return $this->dataMatriculaBr;
    }
    
    public function getTurnoIngresso() {
        return $this->turnoIngresso;
    }
    
    public function getIdPeriodoLetivo() {
        return $this->idPeriodoLetivo;
    }
    
    public function getSiglaCurso() {
        return $this->siglaCurso;
    }
    
    public function getIdMatriz() {
        return $this->idMatriz;
    }
    
    public function getSituacaoMatricula() {
        return $this->situacaoMatricula;
    }
    
    public function getIdFormaIngresso() {
        return $this->idFormaIngresso;
    }
    
    public function getConcursoPontos() {
        return $this->concursoPontos;
    }
    
    public function getConcursoClassificacao() {
        return $this->concursoClassificacao;
    }
    
    public function getNomeCurso() {
        return $this->nomeCurso;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }
    
    public function getSiglaPeriodoLetivo() {
        return $this->siglaPeriodoLetivo;
    }
    
    public function getDataInicioVigencia() {
        return $this->dataInicioVigencia;
    }
    
    public function getDataInicioVigenciaBr() {
        return $this->dataInicioVigenciaBr;
    }
    
}

?>