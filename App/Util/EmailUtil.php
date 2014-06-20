<?php

namespace App\Util;

/**
 * Description of EmailUtil
 *
 * @author gilmario
 */
class EmailUtil {

    private static $cabecalho = "Governo Municipal de Caucaia<br/>Secretaria Municipal de Finanças, Planejamento e Orçamento<br/><br/>Caro Cidadão,<br/><br/>";
    private static $rodape = "<br/><br/>NOTA CAUCAIA, todos ganham no final.<br/>Peça e ganhe desconto no IPTU.";

    public static function enviarEmail($dest, $assunto, $mensagem) {
        setlocale(LC_ALL, "pt_BR.utf8");
        $headers = 'MIME-Version: 1.0' . "\r\n";
//        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: NOTA CAUCAIA <notacaucaia@sefin.caucaia.ce.gov.br>" . "\r\n";
//        $headers .= "X-Mailer: PHP/";
        $texto = EmailUtil::$cabecalho . $mensagem . EmailUtil::$rodape;
        mail($dest, "Nota Caucaia - " . $assunto, $texto, $headers);
    }

}
