<div id="direita">

    <?php if (Zend_Auth::getInstance()->getIdentity()->id != null) : ?>
        <div  id="div_perfil" class="rounded-light shadow">
            <div id="div_perfil_nome"><?php echo Zend_Auth::getInstance()->getIdentity()->nome ?></div>
            <img id="img_perfil" src="<?php echo AppUtil::getFileView(Zend_Auth::getInstance()->getIdentity(), 'u', '160') ?>">
            <div class="Bla Zx">Alterar foto do perfil</div>

            <ul id="menulist" >
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/principal"><i class="icon-home icon-white"></i>Home</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/plano"><i class="icon-th-large icon-white"></i>Planos</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/banco"><i class="icon-briefcase icon-white"></i>Banco</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/conta-bancaria"><i class="icon-hdd icon-white"></i>Conta Bancária</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/recorrencia"><i class="icon-asterisk icon-white"></i>Recorrência</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/escola/"><i class="icon-flag icon-white"></i>Escola</a></li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/lancamento"><i class="icon-tasks icon-white"></i>Lançamento</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/usuario/"><i class="icon-user icon-white"></i>Usuários</a></li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/usuario-grupo/"><i class="icon-tasks icon-white"></i>Grupo de Usuários</a></li>
                <li><a href="<?php echo $this->baseUrl(); ?>/admin/usuario-funcionalidade/"><i class="icon-wrench icon-white"></i>Funcionalidades</a></li>
            </ul>
        </div>
    <?php endif ?>

</div>