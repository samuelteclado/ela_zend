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

    public function getLancamentosByProcedimento($empresa_id, $procedimento_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere('procedimento_id = ?', array($procedimento_id));
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

    public function getDatePrimeiroLancamento($empresa_id) {
        $q = Doctrine_Query::create()
            ->select('MIN(vencimento_data) AS primeira_data')
            ->from(get_class($this->obj) . ' p')
            ->addWhere('empresa_id = ?', array($empresa_id));
        $result = $q->fetchOne();
        return $result->primeira_data;
    }


    public function getReceitaByDate($empresa_id, $date) {
        $q = Doctrine_Query::create()
            ->select('SUM(pagamento_valor) AS saldo')
            ->from(get_class($this->obj) . ' p')
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere('pagamento_data < ?', array($date))
            ->addWhere('tipo = ' . Lancamento::RECEITA);
        $result = $q->fetchOne();
        return $result->saldo;
    }

    public function getDespesaByDate($empresa_id, $date) {
        $q = Doctrine_Query::create()
            ->select('SUM(pagamento_valor) AS saldo')
            ->from(get_class($this->obj) . ' p')
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere('pagamento_data < ?', array($date))
            ->addWhere('tipo = ' . Lancamento::DESPESA);
        $result = $q->fetchOne();
        return $result->saldo;
    }


    public function getFluxoDeCaixaGeral($empresa_id, $data_inicial, $data_final, $conta_bancaria,$cliente_id) {

        $sql = 'SELECT cliente_id,cliente_nome, cliente_sobrenome, LEFT(data_lancamento + "", 6) + "" AS data, SUM(valor) AS valor, tipo, conta FROM 
        (
            SELECT 	L.cliente_id as cliente_id, 
			C.nome as cliente_nome, C.sobrenome as cliente_sobrenome, L.empresa_id,
			SUM(L.vencimento_valor) as valor, 
			L.vencimento_data as data_lancamento, 
			L.tipo, L.conta_bancaria_id as conta
		    FROM lancamento L 
			
			LEFT JOIN cliente C ON C.id = L.cliente_id
            WHERE L.empresa_id = "' . $empresa_id . '"
	        AND L.pagamento_data IS NULL
            AND L.vencimento_data BETWEEN "' . $data_inicial . '" AND "' . $data_final . '"
	        GROUP BY L.vencimento_data, L.tipo, L.conta_bancaria_id, L.cliente_id, C.nome

	        UNION ALL

            SELECT L.cliente_id as cliente_id, 
			C.nome as cliente_nome, C.sobrenome as cliente_sobrenome,L.cliente_id,
			SUM(L.pagamento_valor) as valor, 
			L.pagamento_data as data_lancamento, 
			L.tipo, L.conta_bancaria_id as conta
		    FROM lancamento L 
		    
			LEFT JOIN cliente C ON C.id = L.cliente_id
            WHERE L.empresa_id = "' . $empresa_id . '" 
            AND L.pagamento_data IS NOT NULL 
            AND L.pagamento_data BETWEEN "' . $data_inicial . '" AND "' . $data_final . '"
	        GROUP BY L.pagamento_data, L.tipo, L.conta_bancaria_id,  L.cliente_id, C.nome
        
        ) AS FluxoDeCaixa ';

        if ($conta_bancaria > 0 && $cliente_id <= 0)
            $sql .= 'WHERE conta = ' . $conta_bancaria . ' AND empresa_id = ' . $empresa_id;

        if ($cliente_id > 0 && $conta_bancaria <= 0)
            $sql .= 'WHERE cliente_id = ' . $cliente_id . ' AND empresa_id = ' . $empresa_id;

        if ($conta_bancaria > 0 && $cliente_id > 0)
            $sql .= 'WHERE conta = ' . $conta_bancaria . ' AND cliente_id = ' . $cliente_id . ' AND empresa_id = ' . $empresa_id;

        $sql .= ' GROUP BY data_lancamento, tipo, conta, cliente_id
        ORDER BY cliente_nome';

        $db = Doctrine_Manager::getInstance()->getCurrentConnection();
        $query = $db->prepare($sql);

        $query->execute();

        return $query->fetchAll();
    }
}