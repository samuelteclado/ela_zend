<div id="direita">

    <?php if (Zend_Auth::getInstance()->getIdentity()->id != null) : ?>

        <div  id="div_perfil" class="rounded-light shadow">
            <div id="div_perfil_nome"><?php echo Zend_Auth::getInstance()->getIdentity()->nome_completo ?></div>
            <img id="img_perfil" src="<?php echo AppUtil::getFileView(Zend_Auth::getInstance()->getIdentity(), 'u', '160') ?>">
            <div class="Bla Zx">Alterar foto do perfil</div>

            <ul id="menulist" >
                <li><a href="<?php echo $this->baseUrl(); ?>/professor/"><i class="icon-home icon-white"></i>Home</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/professor/livro"><i class="icon-book icon-white"></i>Livro</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/professor/turma"><i class="icon-th icon-white"></i>Turma</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/professor/presenca"><i class="icon-ok icon-white"></i>Presença</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/professor/nota"><i class="icon-file icon-white"></i>Notas</a> </li>
            </ul>
        </div>
    <?php endif ?>

</div>