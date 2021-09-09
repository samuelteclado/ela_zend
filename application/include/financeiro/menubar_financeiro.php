<div id="direita">

    <?php if (Zend_Auth::getInstance()->getIdentity()->id != null) : ?>
        <div  id="div_perfil" class="rounded-light shadow">
            <div id="div_perfil_nome"><?php echo Zend_Auth::getInstance()->getIdentity()->nome_completo ?></div>
            <img id="img_perfil" src="<?php echo AppUtil::getFileView(Zend_Auth::getInstance()->getIdentity(), 'u', '160') ?>">
            <div class="Bla Zx">Alterar foto do perfil</div>

            <ul id="menulist" >
                <li><a href="<?php echo $this->baseUrl(); ?>/financeiro/"><i class="icon-home icon-white"></i>Home</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/financeiro/conta"><i class="icon-hdd icon-white"></i>Conta Bancária</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/financeiro/lancamento"><i class="icon-asterisk icon-white"></i>Lançamento</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/aluno"><i class="icon-th-large icon-white"></i>Aluno</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/financeiro/siges"><i class="icon-shopping-cart icon-white"></i>Siges</a> </li>

            </ul>
        </div>
    <?php endif ?>

</div>