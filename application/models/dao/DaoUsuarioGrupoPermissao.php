<?php

/**
 * DaoUsuarioGrupoPermissao
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $permitido
 * @property integer $usuario_grupo_id
 * @property integer $usuario_grupo_permissao_funcionalidade_id
 * @property UsuarioGrupo $UsuarioGrupo
 * @property UsuarioGrupoPermissaoFuncionalidade $UsuarioGrupoPermissaoFuncionalidade
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DaoUsuarioGrupoPermissao extends DaoGeneric
{
    public function setTableDefinition()
    {
        $this->setTableName('usuario_grupo_permissao');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('permitido', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('usuario_grupo_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('usuario_grupo_permissao_funcionalidade_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('UsuarioGrupo', array(
             'local' => 'usuario_grupo_id',
             'foreign' => 'id'));

        $this->hasOne('UsuarioGrupoPermissaoFuncionalidade', array(
             'local' => 'usuario_grupo_permissao_funcionalidade_id',
             'foreign' => 'id'));
    }
}