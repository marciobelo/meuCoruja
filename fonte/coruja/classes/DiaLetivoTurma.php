<?php
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/TempoSemanal.php";

class DiaLetivoTurma {
    
    private $turma;
    private $data;
    private $dataLiberacao;
    private $conteudo;
    private $anotacaoProfessor;
    
    private $listaTempoSemanal; // array de TempoSemanal

    function __construct(Turma $turma, DateTime $data) {
        $this->turma = $turma;
        $this->data = $data;

        // Se existe, carrega
        $con = BD::conectar();
        $queryCount = sprintf("select * from DiaLetivoTurma DLT
            where idTurma = %d and
            data = '%s'",
                $turma->getIdTurma(),
                $data->format("Y-m-d"));
        $result = mysql_query($queryCount, $con);
        if(mysql_num_rows($result) == 1) { // dia já persistido
            $reg = mysql_fetch_array($result);
            $this->dataLiberacao = Util::converteDateTime($reg["dataLiberacao"]);
            $this->conteudo = $reg["conteudo"];
            $this->anotacaoProfessor = $reg["anotacaoProfessor"];
            $this->listaTempoSemanal = TempoSemanal::obterListaTempoSemanalPorDiaLetivoTurma( $this );

        } else { // dia não persistido
            $this->dataLiberacao = null;
            $this->conteudo = null;
            $this->anotacaoProfessor = null;
            $this->listaTempoSemanal = TempoSemanal::criarListaTempoSemanalPorDiaLetivoTurma( $this );
        }

    }

    public function lancarApontaTempoAulaAnotacao($numMatriculaAluno, $anotacao) {
        $con = BD::conectar();
        $cmd = sprintf("update DiaLetivoTurma set anotacaoProfessor='%s'
            where idTurma = %d and
            data = '%s'",
                mysql_real_escape_string($anotacao),
                $this->turma->getIdTurma(),
                $this->data->format("Y-m-d"));
        $result = mysql_query($cmd, $con);
        if( !$result ) {
            throw new Exception("Erro ao tentar alterar anotação de professor da aula");
        }
    }

    public function alterarTempos($listaIdTempoSemanal, $con) {
        $cmdApaga = sprintf("delete from TempoDiaLetivo
            where idTurma = %d and
            data = '%s'",
                $this->turma->getIdTurma(),
                $this->data->format("Y-m-d"));
        $result = mysql_query($cmdApaga, $con);
        if( !$result ) {
            throw new Exception("Erro ao apagar tempo do dia letivo.");
        }

        if( !$this->estaPersistido() ) {
            $cmdInsereNovoDiaLetivo = sprintf("insert into DiaLetivoTurma (idTurma, data)
                values (%d, '%s')",
                    $this->turma->getIdTurma(),
                    $this->data->format("Y-m-d") );
            mysql_query($cmdInsereNovoDiaLetivo, $con);
            if( mysql_affected_rows($con) != 1) {
                throw new Exception("Erro ao tentar inserir dia letivo aula");
            }
        }

        $cmdInsereGabarito = "insert into TempoDiaLetivo (idTurma, data,
            idTempoSemanal) values (%d, '%s', %d)";
        foreach($listaIdTempoSemanal as $idTempoSemanal) {
            $cmdInsereTempo = sprintf($cmdInsereGabarito,
                    $this->turma->getIdTurma(),
                    $this->data->format("Y-m-d"),
                    $idTempoSemanal);
            mysql_query($cmdInsereTempo, $con);
            if( mysql_affected_rows($con) != 1) {
                throw new Exception("Erro ao tentar inserir tempo de aula");
            }
        }
        $this->listaTempoSemanal = TempoSemanal::obterListaTempoSemanalPorDiaLetivoTurma( $this );
    }

    public function lancarApontaTempoAulaConteudo($numMatriculaAluno, $conteudo) {
        $con = BD::conectar();
        $cmd = sprintf("update DiaLetivoTurma set conteudo='%s'
            where idTurma = %d and
            data = '%s'",
                mysql_real_escape_string($conteudo),
                $this->turma->getIdTurma(),
                $this->data->format("Y-m-d"));
        $result = mysql_query($cmd, $con);
        if( !$result ) {
            throw new Exception("Erro ao tentar alterar conteúdo da aula");
        }
    }

    /**
     * Registra os apontamentos de uma dia letivo para o aluno
     * 
     * @param string $numMatriculaAluno matrícula do aluno
     * @param string $stringPresenca
     * @return string "ok" se lançou com sucesso, ou lança exceção
     */
    public function lancarApontaTempoAula($numMatriculaAluno, $stringPresenca) {

        if( is_numeric($stringPresenca) ) {
            $qtdeFaltas = intval($stringPresenca);
            if($qtdeFaltas > $this->getQtdeTempos()) {
                throw new Exception("Qtde. de faltas maior que qtde. de tempos do dia letivo.");
            }
            $stringAponta = str_repeat("F", $qtdeFaltas) . str_repeat("P", $this->getQtdeTempos() - $qtdeFaltas);
        } else {
            $this->validarStringPresenca( $stringPresenca );
            $stringAponta = $stringPresenca;
        }

        for($i=0 ; $i < strlen($stringAponta) ; $i++) {

            $digito = $stringAponta[$i];
            $tempoSemanal = $this->listaTempoSemanal[$i];

            if( $this->ehDigitoPresenca($digito) ) {
                $situacao = "P";
            } else if( $this->ehDigitoFalta($digito) ) {
                $situacao = "F";
            } else if( $digito == "-" ) { // omissão de lançamento (ingresso tardio)
                $situacao = "-";
            } else {
                throw new Exception(sprintf("Entrada \"%s\" incorreta.", $stringPresenca));
            }

            $con = BD::conectar();
            $queryExisteApontaTempoAula = sprintf("select count(*) from ApontaTempoAula
                where idTurma = %d and
                matriculaAluno = '%s' and
                data = '%s' and
                idTempoSemanal = %d",
                    $this->turma->getIdTurma(),
                    $numMatriculaAluno,
                    $this->data->format("Y-m-d"),
                    $tempoSemanal->getIdTempoSemanal());
            $resultExisteApontaTempoAula = mysql_query($queryExisteApontaTempoAula, $con);
            if(mysql_result($resultExisteApontaTempoAula, 0, 0) == 0) { // insere
                $cmdInsere = sprintf("insert into ApontaTempoAula (idTurma,
                    matriculaAluno, data, idTempoSemanal, situacao)
                    values (%d, '%s', '%s', %d, '%s')",
                    $this->turma->getIdTurma(),
                    $numMatriculaAluno,
                    $this->data->format("Y-m-d"),
                    $tempoSemanal->getIdTempoSemanal(),
                    $situacao );
                $result_insere = mysql_query($cmdInsere, $con);
                if( mysql_affected_rows($con) != 1) {
                    throw new Exception("Erro ao tentar inserir apontamento de aula");
                }
            } else {
                if($situacao == '-') {
                    $cmdAltera = sprintf("delete from ApontaTempoAula 
                        where idTurma = %d and
                        matriculaAluno = '%s' and
                        data = '%s' and
                        idTempoSemanal = %d",
                        $this->turma->getIdTurma(),
                        $numMatriculaAluno,
                        $this->data->format("Y-m-d"),
                        $tempoSemanal->getIdTempoSemanal() );
                } else {
                    $cmdAltera = sprintf("update ApontaTempoAula set situacao='%s'
                        where idTurma = %d and
                        matriculaAluno = '%s' and
                        data = '%s' and
                        idTempoSemanal = %d",
                        $situacao,
                        $this->turma->getIdTurma(),
                        $numMatriculaAluno,
                        $this->data->format("Y-m-d"),
                        $tempoSemanal->getIdTempoSemanal() );
                }
                $result_altera = mysql_query($cmdAltera, $con);
                if( !$result_altera ) {
                    throw new Exception("Erro ao tentar alterar apontamento de aula");
                }
            }
        }
    }

    private function ehDigitoPresenca( $digito ) {
        if( $digito == "P" || $digito == "p" || $digito == "." ) {
            return true;
        }
    }

    private function ehDigitoFalta( $digito ) {
        if( $digito == "F" || $digito == "f" ) {
            return true;
        }
    }

    private function validarStringPresenca( $stringPresenca ) {
        if( strlen($stringPresenca) != $this->getQtdeTempos() ) {
            throw new Exception(sprintf("Entrada \"%s\" incorreta.", $stringPresenca));
        }
    }

    /**
     * Persiste objeto DiaLetivoTurma (e sua coleção de tempos semanais).
     */
    public function persisteDiaLetivoRegular() {
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $sqlInsereDiaLetivo = sprintf("insert into DiaLetivoTurma (idTurma,data)
                values ( %d, '%s' )",
                    $this->turma->getIdTurma(),
                    $this->data->format("Y-m-d") );
            $result = mysql_query($sqlInsereDiaLetivo, $con);
            if(mysql_affected_rows() != 1) {
                throw new Exception("Não foi inserir dia letivo!");
            }
            foreach( $this->listaTempoSemanal as $tempoSemanal ) {
                $sqlInsereTempoDiaLetivo = sprintf("insert into TempoDiaLetivo (idTurma,
                    data, idTempoSemanal)
                    values ( %d, '%s', %d )",
                        $this->turma->getIdTurma(),
                        $this->data->format("Y-m-d"),
                        $tempoSemanal->getIdTempoSemanal() );
                $result = mysql_query($sqlInsereTempoDiaLetivo, $con);
                if(mysql_affected_rows() != 1) {
                    throw new Exception("Não foi inserir dia letivo!");
                }
            }
            mysql_query("COMMIT", $con);
        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            throw $ex;
            exit;
        }
    }

    public function estaPersistido() {
        $con = BD::conectar();
        $query = sprintf("select count(*) from DiaLetivoTurma DLT
            where idTurma = %d and
            data = '%s'",
                $this->turma->getIdTurma(),
                $this->data->format("Y-m-d"));
        $result = mysql_query( $query, $con);
        if(mysql_result($result, 0, 0) == 1) return true;
        return false;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getTurma() {
        return $this->turma;
    }
    
    public function getQtdeTempos() {
        return count( $this->listaTempoSemanal );
    }
    
    public function getDataLiberacao() {
        return $this->dataLiberacao;
    }

    public function getConteudo() {
        return $this->conteudo;
    }

    public function getAnotacaoProfessor() {
        return $this->anotacaoProfessor;
    }
    
    public function getListaTempoSemanal() {
        return $this->listaTempoSemanal;
    }
}