<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of DateUtil
 *
 * @author Paulo
 */
class DateUtil {

    public static function format() {
        date_default_timezone_set( 'America/Sao_Paulo' );

        $iniDate = '2010-01-04';
        $l10n = 'pt_BR';
        $format = Zend_Date::DATETIME;

        $locale = new Zend_Locale($l10n);

        $date = Zend_Locale_Format::getDate('41.10.20', array('date_format' => 'ddMMyy', 'fix_date' => true));

        if (Zend_Locale_Format::checkDateFormat('13.Apr.2006', array('date_format' => Zend_Locale_Format::STANDARD, $locale))) {
            print "date";
        } else {
            print "not a date";
        }

        if (Zend_Locale_Format::getTime('13:44:42', array('date_format' => Zend_Locale_Format::STANDARD, 'locale' => $locale))) {
            print "time";
        } else {
            print "not a time";
        }

        $iniDate = new Zend_Date( $iniDate, $format, $l10n );

        print_r($iniDate);

        $init   = $iniDate -> get( $format );

        print_r($init);


        $iniDate = '04/01/2010';
        //$l10n = 'en_US';
        $format = Zend_Date::DATETIME;

        $iniDate = new Zend_Date( $iniDate, $format, $l10n );

        print_r($iniDate);

        $init   = $iniDate -> get( 'yyyy-MM-dd' );

        print_r($init);
    }
}
