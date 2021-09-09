<?php

class UsuarioRepository extends RepositoryAbstract {

    protected $_descriptionField = 'nome';

    public function __construct() {
        $this->obj = new Usuario();
        parent::__construct();
    }

    public function getList() {
        return parent::getList('email != ?', array('admin'));
    }

    public function getMail($id) {
        return parent::getList('id != ?', array($id));
    }

    public function getListByGroup($grupo, $empresa_id) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("usuario_grupo_id = ?", array($grupo))
                ->addWhere("empresa_id = ?", array($empresa_id))
                ->orderBy('nome ASC');
        return $q->execute();
    }

    public function isExiste($usuario, $empresa) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj) . ' p')
                ->where("p.email = ?", array($usuario->email))
                ->addWhere("p.status != ?", array(Usuario::EXCLUIDO))
                ->addWhere("p.empresa_id = ?", array($empresa));

        if ($usuario->id > 0)
            $q->addWhere("p.id != ?", array($usuario->id));

        return $q->count() > 0;
    }

    public function getUsuario($email) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("email = ?", array($email))
                ->addWhere('status = ' . Aluno::ATIVO);
        return $q->fetchOne();
    }

    public function getListColaboradoresByEmpresa($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa_id))
            ->addWhere('status = ' . Usuario::ATIVO);
            //->addWhere("email = !?", array('samuelteclado@hotmail.com'));
            //->addWhere('usuario_grupo_id = ' . UsuarioGrupo::FUNCIONARIO);

        return $q->execute();
    }


    public function getFuncionariosByEmpresa($empresa_id, $ids = array()) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where('empresa_id = ?', array($empresa_id))
                ->andWhereNotIn('usuario_grupo_id', array(UsuarioGrupo::ADMINISTRADOR))
                ->orderBy('nome ASC');
        if (count($ids) > 0)
            $q->andWhereIn("id", $ids);

        return $q->execute();
    }

}
