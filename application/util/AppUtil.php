<?php

class AppUtil {

    //put your code here
    public static function autoload($className) {
        require_once $className . ".php";
        return true;
    }

    public static function getCurrentDate() {
        return date('Y-m-d H:i:s', time());
    }

    public static function getWeekNumber() {
        $w = date('w', time());

        return $w;
    }

    public static function getAdicionarDia($num_dia){
        $data = date("Y-m-d");
        $data_atualizada = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data)) . "+" . $num_dia . " day"));

        return $data_atualizada;
    }


    public static function setAddDay($add_dia) {
        $data = date("Y-m-d");
        $data_atualizada = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data)) . "+" . $add_dia . " day"));

        $data_util = AppUtil::getNextDayUtil($data_atualizada);

        $data_formatada = implode('/', array_reverse(explode('-', $data_util)));

        return $data_formatada;
    }

    public static function getNextDayUtil($data, $saida = 'Y-m-d') {

        $timestamp = strtotime($data);

        $dia = date('N', $timestamp);

        if ($dia >= 6) {
            $timestamp_final = $timestamp + ((8 - $dia) * 3600 * 24);
        } else {
            $timestamp_final = $timestamp;
        }

        return date($saida, $timestamp_final);
    }

    public static function calcularIdade($data){
        $idade = 0;
        $data_nascimento = date('Y-m-d', strtotime($data));
        list($anoNasc, $mesNasc, $diaNasc) = explode('-', $data_nascimento);

        $idade = date("Y") - $anoNasc;
        if (date("m") < $mesNasc){
            $idade -= 1;
        } elseif ((date("m") == $mesNasc) && (date("d") <= $diaNasc) ){
            $idade -= 1;
        }

        return $idade;
    }

    public function getDiferecaDias($data_inicial, $data_final) {
        $time_inicial = strtotime($data_inicial);
        $time_final = strtotime($data_final);

        $diferenca = $time_final - $time_inicial;

        $dias = (int) round($diferenca / (60 * 60 * 24));

        return $dias;
    }

    public static function setWordLower($str) {
        return mb_strtolower($str, 'UTF-8');
    }

    public static function setWordUpper($str) {
        return mb_strtoupper($str, 'UTF-8');
    }

    public static function setFirstUpWord($str) {
        return ucwords(AppUtil::setWordLower($str));
    }

    public static function getIdUser() {
        return Zend_Auth::getInstance()->getIdentity()->id;
    }

    public static function getGroupUser() {
        return Zend_Auth::getInstance()->getIdentity()->usuario_grupo_id;
    }

    public static function gerar_senha($tamanho, $maiuscula, $minuscula, $numeros, $codigos) {
        $maius = "ABCDEFGHIJKLMNOPQRSTUWXYZ";
        $minus = "abcdefghijklmnopqrstuwxyz";
        $numer = "0123456789";
        $codig = '!@#$%&*()-+.,;?{[}]^><:|';

        $base = '';
        $base .= ($maiuscula) ? $maius : '';
        $base .= ($minuscula) ? $minus : '';
        $base .= ($numeros) ? $numer : '';
        $base .= ($codigos) ? $codig : '';

        srand((float) microtime() * 10000000);
        $senha = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $senha .= substr($base, rand(0, strlen($base) - 1), 1);
        }
        return $senha;
    }

    public static function getWeekDate($data) {
        $data_array = array_reverse(explode('-', $data));

        $d = mktime(0, 0, 0, $data_array[1], $data_array[0], $data_array[2]);
        $w = date('w', $d);

        return self::getSmallWeekText($w);
    }

    public static function getDayDate($data) {
        if (empty($data) || $data == '')
            return '';

        $data_array = array_reverse(explode('-', $data));

        return $data_array[0];
    }

    public static function getSmallMonthDate($data) {
        if (empty($data) || $data == '')
            return '';

        $data_array = array_reverse(explode('-', $data));

        return self::getSmallMonthText($data_array[1]);
    }

    private static function getSmallWeekText($week) {
        switch ($week) {
            case 0:
                return "DOM";
            case 1:
                return "SEG";
            case 2:
                return "TER";
            case 3:
                return "QUA";
            case 4:
                return "QUI";
            case 5:
                return "SEX";
            case 6:
                return "SAB";
        }
    }

    private static function getSmallMonthText($mon) {
        switch ($mon) {
            case 1:
                return "JAN";
            case 2:
                return "FEV";
            case 3:
                return "MAR";
            case 4:
                return "ABR";
            case 5:
                return "MAI";
            case 6:
                return "JUN";
            case 7:
                return "JUL";
            case 8:
                return "AGO";
            case 9:
                return "SET";
            case 10:
                return "OUT";
            case 11:
                return "NOV";
            case 12:
                return "DEZ";
        }
    }

    public static function validaCpf($cpf) {
        // Verifiva se o número digitado contém todos os digitos
        $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return $result.="<li>O CPF informado é invalido.</li>";
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return $result.="<li>O <b>CPF</b> informado é invalido.</li>";
                }
            }

            return "";
        }
    }

    public static function convertDateToString($data) {
        if (empty($data) || $data == '')
            return '-';

        return join('/', array_reverse(explode('-', $data)));
    }

    public static function convertDateToText($data) {
        if (empty($data) || $data == '')
            return '-';

        $data_array = array_reverse(explode('-', $data));

        return $data_array[0] . self::getMonthText($data_array[1]) . $data_array[2];
    }

    public static function convertDateTimeToString($date_time, $type = 'datetime') {
        switch ($type) {
            case 'date':
                $data = substr($date_time, 0, 10);
                return self::convertDateToString($data);

            case 'time':
                $hora = substr($date_time, 11, 5);
                return $hora;

            default:
                $data = substr($date_time, 0, 10);
                $hora = substr($date_time, 11, 5);
                return join('/', array_reverse(explode('-', $data))) . ' ' . $hora;
        }
    }

    public static function getSmallMonth($mon) {
        switch ($mon) {
            case 1:
                return "JAN";
            case 2:
                return "FEV";
            case 3:
                return "MAR";
            case 4:
                return "ABR";
            case 5:
                return "MAI";
            case 6:
                return "JUN";
            case 7:
                return "JUL";
            case 8:
                return "AGO";
            case 9:
                return "SET";
            case 10:
                return "OUT";
            case 11:
                return "NOV";
            case 12:
                return "DEZ";
        }
    }

    public static function getMeses() {
        $meses = array();
        $mes = array();


        for ($index = 1; $index <= 12; $index++) {
            $mes['id'] = $index;
            $mes['text'] = AppUtil::getTextMonth($index);
            $meses[] = $mes;
        }

        return $meses;
    }

    public static function getTextMonth($mon) {
        switch ($mon) {
            case 1:
                return "Janeiro";
            case 2:
                return "Fevereiro";
            case 3:
                return "Março";
            case 4:
                return "Abril";
            case 5:
                return "Maio";
            case 6:
                return "Junho";
            case 7:
                return "Julho";
            case 8:
                return "Agosto";
            case 9:
                return "Setembro";
            case 10:
                return "Outubro";
            case 11:
                return "Novembro";
            case 12:
                return "Dezembro";
        }
    }

    private static function getMonthText($mon) {
        switch ($mon) {
            case 1:
                return " de Janeiro de ";
            case 2:
                return " de Fevereiro de ";
            case 3:
                return " de Março de ";
            case 4:
                return " de Abril de ";
            case 5:
                return " de Maio de ";
            case 6:
                return " de Junho de ";
            case 7:
                return " de Julho de ";
            case 8:
                return " de Agosto de ";
            case 9:
                return " de Setembro de ";
            case 10:
                return " de Outubro de ";
            case 11:
                return " de Novembro de ";
            case 12:
                return " de Dezembro de ";
        }
    }

    public static function covertFloatToString($valor = 0, $maiusculas = false) {
        // verifica se tem virgula decimal
        if (strpos($valor, ",") > 0) {
            // retira o ponto de milhar, se tiver
            $valor = str_replace(".", "", $valor);

            // troca a virgula decimal por ponto decimal
            $valor = str_replace(",", ".", $valor);
        }
        $singular = array("centavo", "real", "mil", "milhÃ£o", "bilhÃ£o", "trilhÃ£o", "quatrilhÃ£o");
        $plural = array("centavos", "reais", "mil", "milhÃµes", "bilhÃµes", "trilhÃµes",
            "quatrilhÃµes");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
            "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
            "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
            "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "trÃªs", "quatro", "cinco", "seis",
            "sete", "oito", "nove");

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        $cont = count($inteiro);
        for ($i = 0; $i < $cont; $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];

        $fim = $cont - ($inteiro[$cont - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < $cont; $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                    $ru) ? " e " : "") . $ru;
            $t = $cont - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++; elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                        ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if (!$maiusculas) {
            return($rt ? $rt : "zero");
        } elseif ($maiusculas == "2") {
            return (strtoupper($rt) ? strtoupper($rt) : "Zero");
        } else {
            return (ucwords($rt) ? ucwords($rt) : "Zero");
        }
    }

    public static function convertStringToDate($data) {
        if (empty($data) || $data == '') {
            return NULL;
        }

        return join('-', array_reverse(explode('/', $data)));
    }

    public static function convertStringToFloat($value) {
        if (empty($value)) {
            return 0;
        }

        return str_replace(",", ".", $value);
    }

    public static function convertFloatToString($value, $dec_point = '', $label_zero = "0,00") {
        if (empty($value)) {
            return $label_zero;
        }

        // $value = round($value, 2); //Arredondamento Automático
        //$value = ceil($value); // Arredondamento p/ cima
        //$value = floor($value, 2);  Arredondamento p/ baixo

        return number_format($value, 2, ',', $dec_point);
    }

    public static function convertFloatToStringFluxoDeCaixa($value, $dec_point = '', $label_zero = "0,00") {

        if ($value < 0)
            return '<span style="color: red;">' . self::convertFloatToString($value * -1, '.', '-') . '</span>';

        return '<span style="color: blue;">' . self::convertFloatToString($value, '.', '-') . '</span>';
    }

    public static function convertFloatToStringColor($value) {
        $valor = self::convertFloatToString($value);

        if ($value < 0)
            return '<span style="color: red;">' . $valor . ' D</span>';

        return '<span style="color: blue;">' . $valor . ' C</span>';
        ;
    }

    public static function convertLancamentoReceita($value) {
        if ($value->tipo == Lancamento::RECEITA && $value->pagamento_data =! NULL)
            return $value->pagamento_valor;

        return 0;
    }

    public static function convertFloatToStringExtrato($value) {
        if ($value->tipo == Lancamento::RECEITA && $value->pagamento_data =! NULL)
            return $value->pagamento_valor * 1;

            return $value->pagamento_valor * -1;
    }

    public static function convertLancamentoDespesa($value) {
        if ($value->tipo == Lancamento::DESPESA && $value->pagamento_data =! NULL)
            return $value->pagamento_valor;

        return $value->pagamento_valor * 0 ;
    }

    public static function convertIntToCelular($value) {
        if (empty($value))
            return;
        $ddd = substr($value, 0, 2);
        $tel_inicio = substr($value, 2, 5);
        $tel_fim = substr($value, 7, 4);

        return '(' . $ddd . ') ' . $tel_inicio . '-' . $tel_fim;
    }

    public static function convertIntToTelefone($value) {
        if (empty($value))
            return;
        $ddd = substr($value, 0, 2);
        $tel_inicio = substr($value, 2, 4);
        $tel_fim = substr($value, 6, 4);

        return '(' . $ddd . ') ' . $tel_inicio . '-' . $tel_fim;
    }

    public static function convertIntToCPF($value) {
        if (empty($value))
            return;
        $parte1 = substr($value, 0, 3);
        $parte2 = substr($value, 3, 3);
        $parte3 = substr($value, 6, 3);
        $parte4 = substr($value, 9, 2);

        return $parte1 . '.' . $parte2 . '.' . $parte3 . '-' . $parte4;
    }

    public static function convertIntToCNPJ($value) {
        if (empty($value))
            return;
        $parte1 = substr($value, 0, 2);
        $parte2 = substr($value, 2, 3);
        $parte3 = substr($value, 5, 3);
        $parte4 = substr($value, 8, 4);
        $parte5 = substr($value, 12, 2);


        return $parte1 . '.' . $parte2 . '.' . $parte3 . '/' . $parte4 . '-' . $parte5;
    }

    public static function convertIntToCEP($value) {
        if (empty($value))
            return;
        $parte1 = substr($value, 0, 2);
        $parte2 = substr($value, 2, 3);
        $parte3 = substr($value, 5, 3);

        return $parte1 . '.' . $parte2 . '-' . $parte3;
    }

    public static function resumeTexto($texto, $limite, $tres_p = '') {
        //Retorna o texto em plain/text
        $texto = self::somenteTexto($texto);

        if (strlen($texto) <= $limite)
            return $texto;
        return array_shift(explode('||', wordwrap($texto, $limite, '||'))) . $tres_p;
    }

    private static function somenteTexto($string) {
        //$trans_tbl = get_html_translation_table(HTML_ENTITIES);
        //$trans_tbl = array_flip($trans_tbl);
        return trim(strip_tags($string));
    }

    public static function getValorMulta($data_vencimento, $data_recebimento, $taxa_multa, $valor_original) {
        $dias_atraso = self::_getDiasAtraso($data_vencimento, $data_recebimento);
        $taxa_multa = $taxa_multa / 100;
        return ($dias_atraso > 0) ? $taxa_multa * $valor_original : 0;
    }

    public static function getValorJuros($data_vencimento, $data_recebimento, $taxa_juros, $valor_original) {
        $dias_atraso = self::_getDiasAtraso($data_vencimento, $data_recebimento);
        $taxa_juros = $taxa_juros / 100;
        return ($taxa_juros / 30 * $dias_atraso) * $valor_original;
    }

    private static function _getDiasAtraso($data_vencimento, $data_recebimento) {

        $data_vencimento = explode('-', $data_vencimento);
        $data_vencimento = mktime(0, 0, 0, $data_vencimento[1], $data_vencimento[2], $data_vencimento[0]);

        $data_recebimento = explode('-', $data_recebimento);
        $data_recebimento = mktime(0, 0, 0, $data_recebimento[1], $data_recebimento[2], $data_recebimento[0]);

        $diferenca = $data_recebimento - $data_vencimento;
        $diferenca = ($diferenca > 0 ) ? $diferenca : 0;

        return (int) floor($diferenca / (60 * 60 * 24));
    }

    public static function getFileView($obj, $path, $size, $imageSuffix = null, $createThumb = true) {
        if (file_exists(APPLICATION_UPLOAD_PATH . '/' . $path . '/' . AppUtil::getFileName($obj, $imageSuffix)))
            return self::_getImageThumb(Zend_Controller_Front::getInstance()->getBaseUrl() . '/content/upload/' . $path . '/' . AppUtil::getFileName($obj, $imageSuffix), $size, $createThumb);


        return Zend_Controller_Front::getInstance()->getBaseUrl() . '/content/img/' . $size . 'x' . $size . '.gif';
    }

    private static function _getImageThumb($img, $size, $createThumb = true) {
        if (!$createThumb)
            return $img;

        return Zend_Controller_Front::getInstance()->getBaseUrl() . '/content/timthumb.php?src=' . $img . '&h=' . $size . '&w=' . $size;
    }

    public static function getImgResized($obj, $heigth, $width) {
        return Zend_Controller_Front::getInstance()->getBaseUrl() . '/content/site/thumb.php?src=' . $obj . '&amp;h=' . $heigth . '&amp;w=' . $width;
    }

    public static function isFileExists($obj, $path, $imageSuffix = null) {
        if (file_exists(APPLICATION_UPLOAD_PATH . '/' . $path . '/' . AppUtil::getFileName($obj, $imageSuffix)))
            return true;

        return false;
    }

    public static function getImage($obj, $path) {
        return Zend_Controller_Front::getInstance()->getBaseUrl() . '/content/upload/' . $path . '/' . self::getFileName($obj);
    }

    public static function getImageResized($obj, $path, $heigth, $width) {
        return Zend_Controller_Front::getInstance()->getBaseUrl() . '/content/site/thumb.php?src=' . self::getImage($obj, $path) . '&amp;h=' . $heigth . '&amp;w=' . $width;
    }

    public static function getFileName($obj, $imageSuffix = null, $extension = 'jpg') {
        if ($imageSuffix && $imageSuffix <> "")
            $imageSuffix = '_' . $imageSuffix;

        return md5($obj->id) . $imageSuffix . "." . $extension;
    }


    /**
     * Translates a number to a short alhanumeric version
     *
     * Translated any number up to 9007199254740992
     * to a shorter version in letters e.g.:
     * 9007199254740989 --> PpQXn7COf
     *
     * specifiying the second argument true, it will
     * translate back e.g.:
     * PpQXn7COf --> 9007199254740989
     *
     * this function is based on any2dec && dec2any by
     * fragmer[at]mail[dot]ru
     * see: http://nl3.php.net/manual/en/function.base-convert.php#52450
     *
     * If you want the alphaID to be at least 3 letter long, use the
     * $pad_up = 3 argument
     *
     * In most cases this is better than totally random ID generators
     * because this can easily avoid duplicate ID's.
     * For example if you correlate the alpha ID to an auto incrementing ID
     * in your database, you're done.
     *
     * The reverse is done because it makes it slightly more cryptic,
     * but it also makes it easier to spread lots of IDs in different
     * directories on your filesystem. Example:
     * $part1 = substr($alpha_id,0,1);
     * $part2 = substr($alpha_id,1,1);
     * $part3 = substr($alpha_id,2,strlen($alpha_id));
     * $destindir = "/".$part1."/".$part2."/".$part3;
     * // by reversing, directories are more evenly spread out. The
     * // first 26 directories already occupy 26 main levels
     *
     * more info on limitation:
     * - http://blade.nagaokaut.ac.jp/cgi-bin/scat.rb/ruby/ruby-talk/165372
     *
     * if you really need this for bigger numbers you probably have to look
     * at things like: http://theserverpages.com/php/manual/en/ref.bc.php
     * or: http://theserverpages.com/php/manual/en/ref.gmp.php
     * but I haven't really dugg into this. If you have more info on those
     * matters feel free to leave a comment.
     *
     * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
     * @author  Simon Franz
     * @author  Deadfish
     * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
     * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
     * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
     * @link    http://kevin.vanzonneveld.net/
     *
     * @param mixed   $in    String or long input to translate
     * @param boolean $to_num  Reverses translation when true
     * @param mixed   $pad_up  Number or boolean padds the result up to a specified length
     * @param string  $passKey Supplying a password makes it harder to calculate the original ID
     *
     * @return mixed string or long
     */
    public static function alphaID($in, $to_num = false, $pad_up = 4, $passKey = 'ofertassim') {
        if ($in > 50000) {
            $pad_up += intval($in / 50000);
        }// acada 50 mil sobe 1
        $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($passKey !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (http://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID

            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }

            $passhash = hash('sha256', $passKey);
            $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512', $passKey) : $passhash;

            for ($n = 0; $n < strlen($index); $n++) {
                $p[] = substr($passhash, $n, 1);
            }

            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }

        $base = strlen($index);

        if ($to_num) {
            // Digital number  <<--  alphabet letter code
            $in = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = pow($base, $len - $t);
                $out = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {
            // Digital number  -->>  alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            $out = "";
            for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                $bcp = pow($base, $t);
                $a = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }

        return $out;
    }

    public static function authenticate(Usuario $usuario) {

            $login = $usuario->email;
            $senha = $usuario->senha;
            $Identity_column = "email";


        if (!isset($login) || $login == '' || !isset($senha) || $senha == '')
            return FALSE;


        $db_config = Zend_Registry::getInstance()->get('database');
        $dbAdapter = Zend_Db::factory($db_config->db->adapter, array(
                    'host' => $db_config->db->params->host,
                    'username' => $db_config->db->params->username,
                    'password' => $db_config->db->params->password,
                    'dbname' => $db_config->db->params->dbname
        ));

        $adpter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $adpter->setTableName('usuario')
                ->setIdentityColumn($Identity_column)
                ->setCredentialColumn('senha');
        //      ->setCredentialTreatment('SHA1(?)'); 

        $adpter->setIdentity($login)
                ->setCredential($senha);

        $auth = Zend_Auth::getInstance();
        $resultado = $auth->authenticate($adpter);

        if ($resultado->isValid()) {
            $info = $adpter->getResultRowObject(null, 'senha');
            $storage = $auth->getStorage();
            $storage->write($info);

            return TRUE;
        }

        return FALSE;
    }

    public static function busca_cep($cep) {
        $xml_resultado = simplexml_load_file('https://viacep.com.br/ws/' . urlencode($cep) . '/xml');

        $retorno['cidade'] = strval($xml_resultado->localidade);
        $retorno['bairro'] = strval($xml_resultado->bairro);
        $retorno['logradouro'] = strval($xml_resultado->logradouro);
        $retorno['uf'] = strval($xml_resultado->uf);
        $retorno['resultado'] = 1;
        if ($xml_resultado->erro) {
            $retorno['resultado'] = 0;
        }

        return $retorno;
    }

    public static function convertArrayValuesToUtf8Decode(array $array) {
        foreach ($array as $key => $value) {
            if (is_string($value))
                $array[$key] = utf8_encode($value);
        }
        return $array;
    }

    public static function getBrowser() {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Interet Explorer ';
        } elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Opera ';
        } elseif (preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Firefox ';
        } elseif (preg_match('|Chrome/([0-9\.]+)|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Chrome ';
        } elseif (preg_match('|Safari/([0-9\.]+)|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Safari ';
        } elseif (preg_match('|NETSCAPE/([0-9\.]+)|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Netscape ';
        } else {
            // browser not recognized!
            $browser_version = 0;
            $browser = 'other';
        }
        return $browser . $browser_version;
    }

}
