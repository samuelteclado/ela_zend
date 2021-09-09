<?php

/**
 * Description of EmailManagerOfertas
 *
 * @author Paulo
 */
class EmailManagerOfertas {

    private static $inst = null;

    /**
     * @return EmailManagerOfertas
     */
    public static function getInstance() {
        if (!self::$inst) {
            self::$inst = new EmailManagerOfertas();
        }
        return self::$inst;
    }

    public function setMailConfirmacaoPromocao(Usuario $usuario, Email $email, $params = array()) {
        $logMail = $this->createInitLogMail($usuario, $email,$params);
        $logMail->save();
    }

    private function prepareEmail(Email $email, array $params, LogMail $logMail) {
        $corpo = $email->corpo;
        foreach ($params as $key => $value) {
            $corpo = str_replace("{" . $key . "}", $value, $corpo);
        }
        return ($corpo . '<img style="display:none;" src="' . $logMail->getUrlMarkRead() . '"/>');
    }

    private function createInitLogMail(Usuario $usuario, Email $email,$params) {
        $logMail = new LogMail();
        $logMail->Usuario = $usuario;
        $logMail->status = LogMail::INISTATUS;
        $logMail->Email = $email;
        $logMail->setParamsArray($params);
        $logMail->save();
        return $logMail;
    }

    public function sendLogMail(LogMail $logMail) {
        try {
            $params = $logMail->getParamsArray();
            $params['idlog_mail'] = $logMail->idlog_mail;
            $params['baseurl'] = ViewGeneric::baseUrl();
            $corpo = $this->prepareEmail($logMail->Email,$params, $logMail);
            EmailUtil::sendMail($logMail->Usuario->email, $logMail->Email->assunto, $corpo);
            $logMail->status = LogMail::ENVIADO;
        } catch (Exception $e) {
            $logMail->status = LogMail::ERRO;
            $logMail->obs = $e->getMessage();
        }
        $logMail->save();
    }

}

