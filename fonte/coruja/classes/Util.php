<?php
class Util 
{

    public static function enviarEmail($email, $assunto, $texto) {
        require_once "Mail.php";

        $emailRemetente = Config::EMAIL_USUARIO;
        $from = "Coruja <$emailRemetente>";
        $to = "<$email>";
        $subject = $assunto;
        $body = "E-MAIL AUTOMÁTICO ENVIADO PELO SISTEMA CORUJA. NÃO RESPONDA.\n\n" . $texto;

        $host = Config::EMAIL_SERVIDOR;
        $port = Config::EMAIL_PORTA;
        $username = Config::EMAIL_USUARIO;
        $password = Config::EMAIL_SENHA;

        $headers = array ('From' => $from,
          'To' => $to,
          'Subject' => $subject);
        $smtp = Mail::factory('smtp',
          array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password));

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
            throw new Exception($mail->getMessage());
         }
    }
    
    /**
     * Retirado de http://stackoverflow.com/questions/6232846/best-email-validation-function-in-general-and-specific-college-domain
     * @param string $email string que deve conter um email
     * @return boolean email eh valido ou nao
     */
    public static function check_email_address($email) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Gera uma senha aleatória
     * @return String 
     */
    public static function gerarSenhaAleatoria() {
        return substr(md5(rand().rand()), 0, 6);
    }

    /**
     * Retorna a data atual do MySQL
     * @param Connection $con
     * @return String data atual do MySQL 
     */
    public static function obterDataAtual($con) {
        if($con==null) $con=BD::conectar();
        $query="select CURDATE()";
        $result=mysql_query($query,$con);
        return mysql_result($result, 0, 0);
    }

    // Converte uma data ($data) do formato MySQL para o formato DD/MM/AAAA
    public static function dataSQLParaBr($data) {
        if (trim($data) == '' or trim($data) == NULL) {
            return null;
        }
        return substr($data, 8, 2) . '/' . substr($data, 5, 2) . '/' . substr($data, 0, 4);
    }

    // Converte uma data do formato DD/MM/AAAA para o formato MySQL
    public static function dataBrParaSQL($data) {
        if (trim($data) == '' or trim($data) == NULL) {
            return null;
        }
        return substr($data, 6, 4) . '-' . substr($data, 3, 2) . '-' . substr($data, 0, 2);
    }

    // Converte a primeira letra de cada palavra de um nome ($nome) para maiúscula, exceto os ligadores
    public static function formataNome($nome) 
    {
        $ligadores = Array("da", "das", "de", "do", "dos", "e");
        $partes = explode(" ", mb_strtolower($nome, "ISO-8859-1"));

        for ($i = 0; $i < count($partes); $i++) {
            if (!in_array($partes[$i], $ligadores)) {
                //É um nome, logo precisa ser alterado
                $partes[$i] = ucfirst($partes[$i]);
            }
        }

        return implode(" ", $partes);
    }

    /**
     * Tenta abreviar os nomes do meio antes de truncar
     * @param type $nomeOriginal
     * @param type $maximo limite de caracteres
     * @return string string abreviada ou truncada
     */
    public static function abreviaOuTruncaNome($nomeOriginal, $maximo) 
    {
        $nomeCompleto = Util::formataNome($nomeOriginal);
        $conectores = array('do', 'Do', 'DO', 'da', 'Da', 'DA', 'de', 'De', 'DE', 'dos', 'Dos', 'DOS', 'das', 'Das', 'DAS');
        if(strlen($nomeCompleto) < $maximo) return $nomeCompleto;
        $partes = explode(" ", $nomeCompleto);
        if( count($partes) <= 2 ) {
            if( count($partes) == 2 ) {
                return substr($partes[0] . " " . $partes[1], 0, $maximo);
            } else if( count($partes) == 1 ) {
                return substr($partes[0], 0, $maximo);
            } else {
                return "";
            }
        } else {
            $nomeAbreviado = $nomeCompleto;
            $w = count($partes) - 2;
            while( ($w > 1) && (strlen($nomeAbreviado) > $maximo) ) {
                if (! in_array($partes[$w], $conectores)) { // se não é um conector
                    $longo = $partes[$w];
                    $abreviado = $longo[0] . ".";
                    $nomeAbreviado = str_ireplace($longo, $abreviado, $nomeAbreviado);
                }
                $w--;
            }
            return substr($nomeAbreviado, 0, $maximo);
        }
    }
        
    /**
     * Transforma uma data em formato AAAA-MM-DD (formato SQL), para
     * DD/MM/AAAA.
     * @param string $data
     * @return string data em formato brasileiro
     */
    public static function formataData($data) {
        $data = explode("-", $data);
        $dataBr = implode("/", array_reverse($data));
        return $dataBr;
    }

    /* Vinícius
      data: 13/12/2010
      Este método retorna uma determinada data por extenso
     */
    public static function gerarDataExtenso($data) {

        $data_arr = explode("-", $data);

        // leitura das datas
        $dia = $data_arr[2];
        $mes = $data_arr[1];
        $ano = $data_arr[0];
        //$semana = date('w');
        // configuração mes

        switch ($mes) {
            case 1: $mes = "Janeiro";
                break;
            case 2: $mes = "Fevereiro";
                break;
            case 3: $mes = "Março";
                break;
            case 4: $mes = "Abril";
                break;
            case 5: $mes = "Maio";
                break;
            case 6: $mes = "Junho";
                break;
            case 7: $mes = "Julho";
                break;
            case 8: $mes = "Agosto";
                break;
            case 9: $mes = "Setembro";
                break;
            case 10: $mes = "Outubro";
                break;
            case 11: $mes = "Novembro";
                break;
            case 12: $mes = "Dezembro";
                break;
        }

        // configuração semana
        switch ($semana) {
            case 0: $semana = "DOMINGO";
                break;
            case 1: $semana = "SEGUNDA FEIRA";
                break;
            case 2: $semana = "TERÇA-FEIRA";
                break;
            case 3: $semana = "QUARTA-FEIRA";
                break;
            case 4: $semana = "QUINTA-FEIRA";
                break;
            case 5: $semana = "SEXTA-FEIRA";
                break;
            case 6: $semana = "SÁBADO";
                break;
        }
        $data = $dia . ' de ' . $mes . ' de ' . $ano;
        return $data;
    }

    /*
     * Prepara um string contendo uma data para a string
     * SQL. Faz o escape apropriado e trata os caso qdo for nulo.
     * Já inclui os plics quando houver data.
     */

    public static function tratarDataNullSQL($dataStr) {
        if (empty($dataStr))
            return "NULL";
        else {
            $dataEscape = mysql_real_escape_string($dataStr);
            return "'" . $dataEscape . "'";
        }
    }

    /*
     * Prepara um string contendo um valor para a string
     * SQL. Faz o escape apropriado e trata os caso qdo for nulo ou não numérico.
     */
    public static function tratarNumeroNullSQL($numeroStr) {
        if( !is_numeric($numeroStr) )
            return "NULL"; //Caso não seja um número
        else {
            $numeroEscape = mysql_real_escape_string($numeroStr);
            return $numeroEscape;
        }
    }

    /*
     * Função que valida o CPF
      @autor: Moacir Selínger Fernandes
      @email: hassed@hassed.com
      Qualquer dúvida é só mandar um email
     */
    public static function validaCPF($cpf) { // Verifiva se o número digitado contém todos os digitos
        $cpf = str_pad(preg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    public static function obterEntradasLogAlteradas($log1, $log2) {
        $strEntradasAlteradas = "<ul>";
        $aLog1 = explode("<br/>", $log1);
        $aLog2 = explode("<br/>", $log2);
        for ($l = 0; $l < count($aLog1); $l++) {
            if ($aLog1[$l] != $aLog2[$l]) {
                $aCamposLog1 = explode(": ", $aLog1[$l]);
                $aCamposLog2 = explode(": ", $aLog2[$l]);
                $valor1 = empty($aCamposLog1[1]) ? "VAZIO" : $aCamposLog1[1];
                $valor2 = empty($aCamposLog2[1]) ? "VAZIO" : $aCamposLog2[1];
                $strAlterado = "<li>" . $aCamposLog1[0] . ", de " . $valor1 . "<br/>" .
                        " para " . $valor2 . "</li>";
                $strEntradasAlteradas .= $strAlterado;
            }
        }
        $strEntradasAlteradas .= "</ul>";
        return $strEntradasAlteradas;
    }

    /**
     * Converto um número que representa uma dia da semana (0..6)
     * para a sigla (DOM,SEG...SAB)
     * @param int $codigoDiaSemana
     * @return string
     */
    public static function gerarSiglaDiaSemana($codigoDiaSemana) {
        switch($codigoDiaSemana) {
            case 0: return "DOM";
            case 1: return "SEG";
            case 2: return "TER";
            case 3: return "QUA";
            case 4: return "QUI";
            case 5: return "SEX";
            case 6: return "SAB";
        }
    }

    /**
     * Obtém sigla do dia da semana da data
     * @param DateTime $data
     * @return string 'DOM', 'SEG', ... , 'SAB'
     */
    public static function obterSiglaDiaSemana(DateTime $data) {
        $cod = $data->format("w");
        return Util::gerarSiglaDiaSemana($cod);
    }

    /**
     * Converte uma string com uma data no padrão nacional DD/MM/YYYY
     * num DateTime válido. Lança exceção caso contrário.
     * @param string $dataBr
     */
    public static function converteDataBrParaDateTime($dataBr) {
        $partes = explode("/", $dataBr);
        $dia = $partes[0];
        $mes = $partes[1];
        $ano = $partes[2];
        if(!checkdate($mes, $dia, $ano)) {
            throw new Exception("Data $dataBr incorreta");
        } else {
            return new DateTime($ano . "-" . $mes . "-" . $dia);
        }
    }
    
    /**
     * Converte uma string no formato AAAA-MM-DD para um DateTime
     * @param string $dataString
     * @return null|\DateTime
     */
    public static function converteDateTime($dataString) {
        if( $dataString == null ) return null;
        return new DateTime($dataString);
    }

    /**
     * Gera o texto do mês longo
     * @param int $mesAtual mês de 1..12
     */
    public static function obterMesPorExtenso($mesAtual) {
        switch ($mesAtual) {
            case 1: $mes = "Janeiro";
                break;
            case 2: $mes = "Fevereiro";
                break;
            case 3: $mes = "Março";
                break;
            case 4: $mes = "Abril";
                break;
            case 5: $mes = "Maio";
                break;
            case 6: $mes = "Junho";
                break;
            case 7: $mes = "Julho";
                break;
            case 8: $mes = "Agosto";
                break;
            case 9: $mes = "Setembro";
                break;
            case 10: $mes = "Outubro";
                break;
            case 11: $mes = "Novembro";
                break;
            case 12: $mes = "Dezembro";
                break;
        }
        return $mes;
    }

    /**
     * Gera o texto do mês curto
     * @param int $mesAtual mês de 1..12
     */
    public static function obterMesTextoCurto($mesAtual) {
        switch ($mesAtual) {
            case 1: $mes = "Jan";
                break;
            case 2: $mes = "Fev";
                break;
            case 3: $mes = "Mar";
                break;
            case 4: $mes = "Abr";
                break;
            case 5: $mes = "Mai";
                break;
            case 6: $mes = "Jun";
                break;
            case 7: $mes = "Jul";
                break;
            case 8: $mes = "Ago";
                break;
            case 9: $mes = "Set";
                break;
            case 10: $mes = "Out";
                break;
            case 11: $mes = "Nov";
                break;
            case 12: $mes = "Dez";
                break;
        }
        return $mes;
        
    }

    /**
     * Gera um texto que representa o tempo transcorrido entre a data atual e
     * a data passada por argumento.
     * @param DateTime $dataPassada
     * @return string p.ex.: "2 dias atrás"
     */
    public static function gerarTempoDecorridoTextual(DateTime $dataPassada) {
        $agora = new DateTime();
        return Util::tempoData($dataPassada->format("d/m/Y H:i:s"), $agora->format("d/m/Y H:i:s"));
    }

    /**
     * Verifica se a string contém um número decimal válido com separador
     * de milhar vírgula
     * @param string $strValor
     * @return boolean
     */
    public static function ehDecimalValido( $strValor ) {
        $pattern = "/^\d+(\,\d*)?$/";
        if( !preg_match($pattern, $strValor) ) return FALSE;
        $str = str_replace(",", ".", $strValor );
        return is_numeric( $str );
    }

    /**
     * Converte uma string em formato brasileiro para um float em PHP
     * @param string $stringNota
     * @return float
     * @throws InvalidArgumentException
     */
    public static function converteParaNota( $stringNota ) {
        if( ! Util::ehDecimalValido($stringNota)) {
            throw new InvalidArgumentException(sprintf("\"%s\" não é um valor correto", $stringNota));
        }
        
        $nota = floatval( str_replace(",", ".", $stringNota ) );
        if( $nota < 0 || $nota > 10 ) {
            throw new InvalidArgumentException(sprintf("\"%s\" fora da faixa de 0 a 10", $stringNota));
        }
        return $nota;
    }
    
    private static function tempoData($dataini, $datafim) {

        # Split para dia, mes, ano, hora, minuto e segundo da data inicial
        $_split_datehour = explode(' ', $dataini);
        $_split_data = explode("/", $_split_datehour[0]);
        $_split_hour = explode(":", $_split_datehour[1]);
        # Coloquei o parse (integer) caso o timestamp nao tenha os segundos, dai ele fica como 0
        $dtini = mktime($_split_hour[0], $_split_hour[1], (integer) $_split_hour[2], $_split_data[1], $_split_data[0], $_split_data[2]);

        # Split para dia, mes, ano, hora, minuto e segundo da data final
        $_split_datehour = explode(' ', $datafim);
        $_split_data = explode("/", $_split_datehour[0]);
        $_split_hour = explode(":", $_split_datehour[1]);
        $dtfim = mktime($_split_hour[0], $_split_hour[1], (integer) $_split_hour[2], $_split_data[1], $_split_data[0], $_split_data[2]);

        # Diminui a datafim que é a maior com a dataini
        $time = ($dtfim - $dtini);

        # Recupera os dias
        $days = floor($time / 86400);
        # Recupera as horas
        $hours = floor(($time - ($days * 86400)) / 3600);
        # Recupera os minutos
        $mins = floor(($time - ($days * 86400) - ($hours * 3600)) / 60);
        # Recupera os segundos
        $secs = floor($time - ($days * 86400) - ($hours * 3600) - ($mins * 60));

        # Monta o retorno no formato
        # 5d 10h 15m 20s
        # somente se os itens forem maior que zero
        $retorno = "";
        $retorno .= ($days > 0) ? ($hours > 0 ? $days+1 : $days) . ' dias ' : "";
        $retorno .= ($hours > 0 && $retorno == "") ? ($mins > 0 ? $hours+1 : $hours) . ' horas ' : "";
        $retorno .= ($mins>0 && $retorno == "")  ?  ($secs > 0 ? $mins+1 : $mins) .' minutos ' : ""  ;
        $retorno .= ($secs > 0 && $retorno == "")  ?  $secs .' segundos ' : ""  ;
        $retorno .= "atrás";
        return $retorno;
    }

    /**
     * Converte um número para string ordinal
     * @param type $numeral inteiro
     * @return string texto ordinal do numeral
     * @throws InvalidArgumentException se numeral fora do intervalo 1-12
     */
    public static function converteNumeralParaOrdinal( $numeral ) {
        switch ($numeral) {
            Case '1':
                $periodoExtenso = 'PRIMEIRO';
                BREAK;

            Case '2':
                $periodoExtenso = 'SEGUNDO';
                BREAK;

            Case '3':
                $periodoExtenso = 'TERCEIRO';
                BREAK;

            Case '4':
                $periodoExtenso = 'QUARTO';
                BREAK;

            Case '5':
                $periodoExtenso = 'QUINTO';
                BREAK;

            Case '6':
                $periodoExtenso = 'SEXTO';
                BREAK;

            Case '7':
                $periodoExtenso = 'SÉTIMO';
                BREAK;

            Case '8':
                $periodoExtenso = 'OITAVO';
                BREAK;

            Case '9':
                $periodoExtenso = 'NONO';
                BREAK;

            Case '10':
                $periodoExtenso = 'DÉCIMO';
                BREAK;

            Case '11':
                $periodoExtenso = 'DÉCIMO PRIMEIRO';
                BREAK;

            Case '12':
                $periodoExtenso = 'DÉCIMO SEGUNDO';
                BREAK;
            
            default:
                throw new InvalidArgumentException("Entrada $numeral inválida");
        }
        return $periodoExtenso;
    }

    /**
     * Função criada para dar retrocompatibilidade do código com a versão 5.2
     * do PHP, que não tinha o método citado a seguir (inserido no 5.3).
     * Como no hostgator eles estavam com a versão 5.2, essa alteração foi
     * necessária
     * $novaData->add(new DateInterval("P1D"));
     * @param DateTime $dataAtual
     * @param type $dias
     * @return \DateTime
     */
    public static function DateTimeAddDay(DateTime $dataAtual, $dias) 
    {
        $str = $dataAtual->format("Y-m-d") . " + " . $dias . " days";
        return new DateTime( date("Y-m-d", strtotime( $str ) ) );
    }

    /**
     * Indica se a data informada está mais que diasAtraso atrás da data atual
     * @param DateTime $data
     * @param int $diasAtraso
     * @return boolean
     */
    public static function isDiasOuMaisAntesDeHoje( DateTime $data, $diasAtraso) 
    {
        $agora = new DateTime();
        return ($agora->diff( $data)->format("%r%a") < (-$diasAtraso));
    }
    
    /*
     * Retorna se existe algum filtro configurado em sessão para o curso
     * Se não existor, retorna string vazia.
     */
    public static function obterFiltroSiglaCurso()
    {
        if( isset($_SESSION["siglaCursoFiltro"]))
        {
            $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
        }
        else
        {
            $siglaCursoFiltro = "";
        }
        return $siglaCursoFiltro;
    }
}