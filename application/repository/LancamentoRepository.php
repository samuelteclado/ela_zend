<?php

class LancamentoRepository extends RepositoryAbstract
{

    public function __construct() {
        $this->obj = new Lancamento();
        parent::__construct();
    }

    public function getLancamentoAbertosByCliente($empresa_id, $cliente_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere('cliente_id = ?', array($cliente_id))
            ->addWhere('data_efetiva IS NULL')
            ->orderBy('data ASC');
        return $q->execute();
    }

    public function getByRecorrencia($lancamento_recorrencia_id, $empresa_id = NULL) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('lancamento_recorrencia_id = ?', array($lancamento_recorrencia_id));
        if (isset($empresa_id))
            $q->addWhere('empresa_id = ?', array($empresa_id));
        return $q->execute();
    }

    public function getLancamentosByPlanodeContas($empresa_id, $plano_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere('plano_de_contas_id = ?', array($plano_id));
        return $q->execute();
    }

    public function getByRecorrenciaAndEmpresa($lancamento, $empresa_id,$vencimento_data = NULL) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('id != ?', array($lancamento->id))
            ->addWhere('lancamento_recorrencia_id = ?', array($lancamento->lancamento_recorrencia_id))
            ->addWhere('empresa_id = ?', array($empresa_id));
        if (isset($vencimento_data))
            $q->addWhere('vencimento_data > ?', array($lancamento->vencimento_data));
        return $q->execute();
    }

    public function getReceitaByDate($empresa_id, $date) {
        $q = Doctrine_Query::create()
            ->select('SUM(valor_real) AS saldo')
            ->from(get_class($this->obj) . ' p')
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere('pagamento_data < ?', array($date))
            ->addWhere('tipo = ' . Lancamento::RECEITA);
        $result = $q->fetchOne();
        return $result->saldo;
    }

    public function getDespesaByDate($empresa_id, $date) {
        $q = Doctrine_Query::create()
            ->select('SUM(valor_real) AS saldo')
            ->from(get_class($this->obj) . ' p')
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere('pagamento_data < ?', array($date))
            ->addWhere('tipo = ' . Lancamento::DESPESA);
        $result = $q->fetchOne();
        return $result->saldo;
    }

}