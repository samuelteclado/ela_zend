<?php

class UsuarioGrupoPermissaoRepository extends RepositoryAbstract {

    public function __construct() {
        $this->obj = new UsuarioGrupoPermissao();
        parent::__construct();
    }

    public function getListByGrupo($grupo) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj) . ' p')
                ->where("p.usuario_grupo_id = ?", array($grupo));
        return $q->execute();
    }

    public function getPermissaoByGrupoAndFuncionalidade($grupo, $module, $controller = NULL, $action = NULL) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj) . ' p')
                ->innerJoin('p.UsuarioGrupoPermissaoFuncionalidade upf')
                ->where("p.usuario_grupo_id = ?", array($grupo))
                ->andWhere('upf.module = ?', array($module));

        if ($controller != NULL)
            $q->andWhere('upf.controller = ? OR upf.controller = ?', array($controller, '*'));

        if ($action != NULL)
            $q->andWhere('upf.action = ? OR upf.action = ?', array($action, '*'));

        return $q->fetchOne();
    }

}
