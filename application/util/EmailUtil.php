<?php

class EmailUtil {

    public static function send($from, $to, $subject, $body, $send_admin = FALSE, $copies_hidden = FALSE) {
        $app_email = Zend_Registry::getInstance()->get('email');

        $zend_mail = new Zend_Mail('utf-8');
        $zend_mail->setFrom($from['email'], $from['name']);

        $zend_mail = self::addRecipient($app_email, $to, $zend_mail, $send_admin, $copies_hidden);

        $zend_mail->setSubject($subject);
        $zend_mail->setBodyHtml($body);

        $zend_mail->send(self::config($app_email, $app_email->mail->auth));
    }

    private static function config($app_email, $auth = FALSE) {
        if ($auth) {
            $config['auth'] = 'login';
            $config['username'] = $app_email->mail->username;
            $config['password'] = $app_email->mail->password;
            $config['port'] = $app_email->mail->port;
            //$config['ssl'] = 'ssl';

            return new Zend_Mail_Transport_Smtp($app_email->mail->smtp, $config);
        }

        return new Zend_Mail_Transport_Smtp($app_email->mail->smtp);
    }

    private static function addRecipient($app_email, $to, $zend_mail, $send_admin, $copies_hidden) {
        if ($app_email->mail->test)
            return $zend_mail->addTo($app_email->mail->test);

        if ($send_admin)
            $zend_mail->addBcc($app_email->mail->admin);

        if ($copies_hidden)
            return $zend_mail->addBcc($to);

        return $zend_mail->addTo($to);
    }

}
