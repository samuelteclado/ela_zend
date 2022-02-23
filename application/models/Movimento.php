<?php

/**
 * Movimento
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Movimento extends DaoMovimento
{
    const ABERTO = 1;
    const REALIZADO = 2;
    const CANCELADO = 3;

    const EXCLUIDO = 4;


    const COMPRA = 1;
    const VENDA = 2;

    public function getStatus() {
        switch ($this->status) {

            case Movimento::ABERTO:
                return 'Aberto';

            case Movimento::REALIZADO:
                return 'Realizado';

            case Movimento::CANCELADO:
                return 'Cancelado';

            default:
                return 'Indefinido';
        }
    }

}