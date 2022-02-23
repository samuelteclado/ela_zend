<?php

/**
 * Cliente
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Cliente extends DaoCliente
{
    const ATIVO = 1;
    const INATIVO = 2;
    const EXCLUIDO = 3;


    const SIM = 1;
    const NAO = 2;



    public function getStatus() {
        switch ($this->status) {

            case Cliente::ATIVO:
                return 'Ativo';

            case Cliente::INATIVO:
                return 'Inativo';

            case Cliente::EXCLUIDO:
                return 'Excluido';

            default:
                return 'Indefinido';
        }
    }


    public function getFullNome(){
        $nome = $this->nome ." ".$this->sobrenome;
        return $nome;
    }

    public function getTotalAtendimentos() {

        $i = 0;
        foreach ($this->Procedimento as $procedimento) {
            if ($procedimento->status == Procedimento::REALIZADO)
                $i++;

        }
        return $i;
    }

    public function getUltimoAtendimento()
    {
        $data_array = array();
        $count = 0;
        foreach ($this->Procedimento as $procedimento) {
            if ($procedimento->status == Procedimento::REALIZADO) {
                $data_array[] = $procedimento->data;
                $count++;
            }
        }
        rsort($data_array);
        return $data_array[0];
     }

    private function _date_sort($a, $b)
    {
        return strtotime($b) - strtotime($a);
    }

}