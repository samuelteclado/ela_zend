<?php

/**
 * Lancamento
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Lancamento extends DaoLancamento
{
    const RECEITA = 1;
    const DESPESA = 2;
    const EXCLUIDO = 3;


    const ABERTO = 1;
    const LIQUIDADO = 2;
    const VENCIDO = 3;
    const SIM = 1;
    const NAO = 2;
    const EDITAR_RECORRENCIA_UM = 1;
    const EDITAR_RECORRENCIA_PROXIMOS = 2;
    const EDITAR_RECORRENCIA_TODOS = 3;

    const CLIENTE = 1;
    const FORNECEDOR = 2;

    public function getTipo() {
        switch ($this->tipo) {

            case Lancamento::RECEITA:
                return 'Receita';

            case Lancamento::DESPESA:
                return 'Despesa';

            default:
                return 'Indefinida';
        }
    }

    public function getSituacao() {
        if ($this->isAberto()) {
            return '<span class="label label-info">Em Aberto</span>';
        } else {
            return '<span class="label label-success">Liquidado</span>';
        }
    }

    public function isAberto() {
        return is_null($this->pagamento_data);
    }

    public function getValorLancamento() {
        if ($this->pagamento_data)
            return $this->pagamento_valor;

        return $this->vencimento_valor;
    }

    public function getValorVencimentoAtual() {
        if ($this->vencimento_valor_atualizado)
            return $this->vencimento_valor_atualizado;

        return $this->vencimento_valor;
    }

    public function getVencido() {
        if ( $this->pagamento_data == NULL)
            return TRUE;

        return NULL;
    }


    public function getValorDivergente() {
        $valor_a_pagar = $this->getValorVencimentoAtual() - $this->pagamento_desconto ;
        $diferenca =  $valor_a_pagar - $this->pagamento_valor;

        if ($diferenca != 0)
            return true;

        return false;
    }

}