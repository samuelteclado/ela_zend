<div id="direita">

    <?php if (Zend_Auth::getInstance()->getIdentity()->id != null) : ?>
        <div  id="div_perfil" class="rounded-light shadow">
            <div id="div_perfil_nome"><?php echo Zend_Auth::getInstance()->getIdentity()->nome ?></div>
            <img id="img_perfil" src="<?php echo AppUtil::getFileView(Zend_Auth::getInstance()->getIdentity(), 'u', '160') ?>">
            <div class="Bla Zx">Alterar foto do perfil</div>

            <ul id="menulist" >
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/"><i class="icon-home icon-white"></i>Home</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/disciplina"><i class="icon-star icon-white"></i>Disciplina</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/curso"><i class="icon-bookmark icon-white"></i>Curso</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/professor"><i class="icon-tags icon-white"></i>Professor</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/turma"><i class="icon-th icon-white"></i>Turma</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/aluno"><i class="icon-th-large icon-white"></i>Aluno</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/presenca"><i class="icon-ok icon-white"></i>Presença</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/nota"><i class="icon-file icon-white"></i>Notas</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/livro"><i class="icon-book icon-white"></i>Livro</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/emprestimo"><i class="icon-random icon-white"></i>Empréstimo</a> </li>
            </ul>
        </div>
    <?php endif ?>

</div>