<?php

/**
 * RegistroPonto
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class RegistroPonto extends DaoRegistroPonto
{

    const ATIVO = 1;
    const INATIVO = 2;
    const EXCLUIDO = 3;




    public function getStatus() {
        switch ($this->status) {

            case RegistroPonto::ATIVO:
                return 'Ativo';

            case RegistroPonto::INATIVO:
                return 'Inativo';

            case RegistroPonto::EXCLUIDO:
                return 'Excluido';

            default:
                return 'Indefinido';
        }
    }


}