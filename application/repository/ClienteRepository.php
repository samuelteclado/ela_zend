<?php

class ClienteRepository extends RepositoryAbstract
{

    protected $_descriptionField = 'cpf';

    public function __construct() {
        $this->obj = new Cliente();
        parent::__construct();
    }

    public function getListActive($empresa_id, $ids = array(), $selected_id = 0) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id))
            ->orderBy('nome ASC');

        if ($selected_id <= 0)
            $q->addWhere('status = ' . Cliente::ATIVO);
        else
            $q->addWhere('status = ' . Cliente::ATIVO . ' OR id = ' . $selected_id);

        if (count($ids) > 0)
            $q->andWhereIn("id", $ids);

        return $q->execute();
    }

    public function getUltimoAtendimento() {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->leftJoin('p.Procedimento pr')
            ->where('pr.status = ' . Procedimento::REALIZADO)
            ->orderBy('pr.data DESC');
       // echo $q->getSqlQuery();
        //die;
        return $q->fetchOne()->data;
    }

    public function isExiste($cliente, $empresa) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("p.email = ?", array($cliente->email))
            ->addWhere("p.status != ?", array(Cliente::EXCLUIDO))
            ->addWhere("p.empresa_id = ?", array($empresa));

        if ($cliente->id > 0)
            $q->addWhere("p.id != ?", array($cliente->id));

        return $q->count() > 0;
    }

    public function getProdutosByDate($data_inicial, $data_final, $categoria = NULL) {
        $sql = 'SELECT pc.id as produto_categoria_id,
                pc.descricao as produto_categoria_descricao,
                p.descricao as produto_descricao,
                p.un_medida as medida,
                AVG(op.valor_corrigido) as media,
                SUM(op.quantidade)
                    as total   FROM
                (
                    SELECT id, quantidade, valor_corrigido, orcamento_id, produto_id
                    FROM orcamento_produto_item 
                    UNION 
                    SELECT oc.id, oci.quantidade, oc.valor_corrigido, oc.orcamento_id, oci.produto_id FROM orcamento_cortesia oc
                    INNER JOIN orcamento_cortesia_item oci on oci.orcamento_cortesia_id = oc.id
                ) as op
                INNER JOIN produto p on  op.produto_id = p.id
                INNER JOIN orcamento o on op.orcamento_id = o.id
                INNER JOIN produto_categoria pc on p.produto_categoria_id = pc.id
                WHERE o.data_evento BETWEEN "' . $data_inicial . '" AND "' . $data_final . '"
                AND o.status = "' . Orcamento::CONCLUIDO . '"';
        if (isset($categoria))
            $sql .= ' AND pc.id = " ' . $categoria . '"';
        $sql .= ' GROUP BY p.id
        ORDER BY pc.descricao ASC, p.descricao ASC';


        $db = Doctrine_Manager::getInstance()->getCurrentConnection();
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(Doctrine_Core::FETCH_CLASS);
    }


    public function getListRemarcacoes($empresa_id, $data_inicial, $data_final) {

        $data_inicial_mes_atual = date('Y-m-01');

        $sql = 'SELECT * FROM cliente c
        JOIN procedimento pr
        ON pr.cliente_id = c.id
        WHERE pr.data
        BETWEEN "'. $data_inicial.'" AND "' . $data_final . '"
        AND pr.status = "' . Procedimento::REALIZADO . '"
        AND c.id NOT IN (
                SELECT nome FROM cliente c
                JOIN procedimento pr
                ON pr.cliente_id = c.id
                WHERE pr.data
                BETWEEN "'. $data_inicial_mes_atual.'" AND "' . $data_final . '"
                AND pr.status != "' . Procedimento::CANCELADO . '")';




        $sql .= ' GROUP BY c.id
        ORDER BY  c.nome ASC, pr.data ASC';

        $db = Doctrine_Manager::getInstance()->getCurrentConnection();
        $query = $db->prepare($sql);

var_dump($sql);
//die;
        $query->execute();
        return $query->fetchAll(Doctrine_Core::FETCH_CLASS);
        /*

                $data_inicial_mes_atual = date('Y-m-01');

                $q = Doctrine_Query::create()
                    ->from(get_class($this->obj) . ' p')
                    ->innerJoin('p.Procedimento pr')
                    ->where('p.empresa_id = ?', array($empresa_id))
                    ->addWhere("p.status != ?", array(Cliente::EXCLUIDO))
                    ->addWhere('pr.data between "' . $data_inicial_mes_atual . '" AND "' . $data_final . '"')
                    ->addWhere("pr.status != ?", array(Procedimento::ABERTO))
                    ->orderBy('p.nome ASC');



                $q = Doctrine_Query::create()
                    ->from(get_class($this->obj) . ' p')
                    ->innerJoin('p.Procedimento pr')
                    ->where('p.empresa_id = ?', array($empresa_id))
                    ->addWhere("p.status != ?", array(Cliente::EXCLUIDO))
                    ->addWhere('pr.data between "' . $data_inicial . '" AND "' . $data_final . '"')
                    ->addWhere("pr.status = ?", array(Procedimento::REALIZADO))
                    ->orderBy('p.nome ASC')
                ->addWhere('p.id NOT IN (SELECT pos_calculo.evento_id FROM PosCalculo as pos_calculo WHERE pos_calculo.evento_id != ' . $id . ')');
        **/
       // echo $q->getSqlQuery();
           // die;
        //return $q->execute();
    }

    public function getListAll($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere("status != ?", array(Cliente::EXCLUIDO))
            ->orderBy('nome ASC');

        return $q->execute();
    }

    public function getListByEmpresa($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa_id))
            ->addWhere("status = " . Cliente::ATIVO)
            ->orderBy('nome ASC');

        return $q->execute();
    }

}