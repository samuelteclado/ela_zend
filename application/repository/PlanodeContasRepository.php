<?php

class PlanodeContasRepository extends RepositoryAbstract {

    public function __construct() {
        $this->obj = new PlanoDeContas();
        parent::__construct();
    }

    public function getListByEmpresa($empresa) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("empresa_id = ?", array($empresa))
                ->orderBy('codigo ASC');
        return $q->execute();
    }
    
    public function getGeral() {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("empresa_id IS NULL")
                ->orderBy('descricao ASC');
        return $q->execute();
    }

    public function getListByPlanosPai($empresa) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("empresa_id = ?", array($empresa))
                ->addWhere("codigo IS NOT NULL")
                ->addWhere('tipo =' . PlanoDeContas::PAI)
                ->orderBy('codigo ASC');
        return $q->execute();
    }

    public function getCodPlanoPai($empresa) {
        $q = Doctrine_Query::create()
                ->select('codigo')
                ->from(get_class($this->obj))
                ->where("empresa_id = ?", array($empresa))
                ->addWhere('tipo =' . PlanoDeContas::PAI)
                ->orderBy('codigo DESC')
                ->limit(1);
        return $q->fetchOne()->codigo + 1;
    }
     public function getCodByPlano($empresa,$plano_id) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("empresa_id = ?", array($empresa))
                ->addWhere("plano_de_contas_id = ?", array($plano_id))
                ->addWhere('tipo = ' . PlanoDeContas::FILHO);
        return $q->execute()->count()+1;
    }
    
     public function getByDescricao($empresa,$descricao) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("empresa_id = ?", array($empresa))
                ->addWhere("descricao = ?", array($descricao));
        return $q->fetchOne();
    }
    
    
    public function getPlanoDeContas() {
        $plano_de_contas = $this->getListByEmpresa(SessionUtil::getEmpresaSession());
        $pl_contas_geral = $this->getGeral();
        $pl_empresa_list = array();

        foreach ($pl_contas_geral as $plano_de_conta) {
            $pl_geral_arr['id'] = $plano_de_conta->id;
            $pl_geral_arr['descricao'] = $plano_de_conta->descricao;
            $pl_geral_arr['codigo'] = $plano_de_conta->codigo;
            $pl_empresa_list[] = $pl_geral_arr;
        }

        foreach ($plano_de_contas as $plano_de_conta) {
            $pl_empresa_arr['id'] = $plano_de_conta->id;
            $pl_empresa_arr['descricao'] = $plano_de_conta->descricao;
            $pl_empresa_arr['codigo'] = $plano_de_conta->codigo;
            $pl_empresa_list[] = $pl_empresa_arr;
        }

        return $pl_empresa_list;
    }
    

}
