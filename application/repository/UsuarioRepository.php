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
                ->addWhere('status = ' . Usuario::ATIVO);
        return $q->fetchOne();
    }

    public function getColaboradorByID($usuario_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('id = ' . $usuario_id)
            ->addWhere('status = ' . Usuario::ATIVO);
        //echo $q->getSqlQuery();
        //die;
        return $q->execute();


    }

    public function getListColaboradoresByMassagem($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa_id))
            ->addWhere('tipo = ' . Usuario::MASSAGEM)
            ->orWhere('tipo = ' . Usuario::CILIOS_MASSAGEM)
            ->addWhere('status = ' . Usuario::ATIVO)
            ->addWhere('usuario_grupo_id = ' . UsuarioGrupo::FUNCIONARIO)
            ->orderBy('nome ASC');
        //->addWhere('usuario_grupo_id = ' . UsuarioGrupo::FUNCIONARIO);


        return $q->execute();
    }


    public function getListColaboradoresByCilios($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa_id))
            ->addWhere('tipo = ' . Usuario::CILIOS)
            ->orWhere('tipo = ' . Usuario::CILIOS_MASSAGEM)
            ->addWhere('status = ' . Usuario::ATIVO)
            ->addWhere('usuario_grupo_id = ' . UsuarioGrupo::FUNCIONARIO)
            ->orWhere("email = ?",array('lilian.assuncaod@gmail.com'))
            ->orderBy('nome ASC');
        //->addWhere('usuario_grupo_id = ' . UsuarioGrupo::FUNCIONARIO);

        //echo $q->getSqlQuery();
        //die;
        return $q->execute();
    }


    public function getListColaboradoresByEmpresa($empresa_id, $tipo) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa_id))
            ->addWhere("tipo = ?", array($tipo))
            ->addWhere('status = ' . Usuario::ATIVO)
            ->addWhere('usuario_grupo_id = ' . UsuarioGrupo::FUNCIONARIO)
            ->orWhere("email = ?",array('lilian.assuncaod@gmail.com'))
            ->orderBy('nome ASC');
            //->addWhere('usuario_grupo_id = ' . UsuarioGrupo::FUNCIONARIO);

        //echo $q->getSqlQuery();
        //die;
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
