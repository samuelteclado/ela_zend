<?php

class Ajax_PlanoDeContasController extends Zend_Controller_Action {

    private $_planodecontasRepository;
    private $_empresa_id;

    public function init() {
        $this->_planodecontasRepository = new PlanodeContasRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $this->_helper->layout->disableLayout();
    }

    public function listAction() {

        $planos_de_contas = $this->_planodecontasRepository->getListByEmpresa($this->_empresa_id);

        $plano_de_conta = array();
        $plano_list = array();

        foreach ($planos_de_contas as $plano_obj):

            $plano_de_conta['id'] = $plano_obj->id;
            $plano_de_conta['codigo'] = $plano_obj->codigo;
            $plano_de_conta['descricao'] = $plano_obj->descricao;

            $plano_list[] = $plano_de_conta;

        endforeach;

        $this->_helper->json(Zend_Json::encode($plano_list));
    }

}
