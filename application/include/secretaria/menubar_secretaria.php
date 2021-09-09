<div id="direita">

    <?php if (Zend_Auth::getInstance()->getIdentity()->id != null) : ?>
        <div  id="div_perfil" class="rounded-light shadow">
            <div id="div_perfil_nome"><?php echo Zend_Auth::getInstance()->getIdentity()->nome_completo ?></div>
            <img id="img_perfil" src="<?php echo AppUtil::getFileView(Zend_Auth::getInstance()->getIdentity(), 'u', '160') ?>">
            <div class="Bla Zx">Alterar foto do perfil</div>

            <ul id="menulist" >
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/"><i class="icon-home icon-white"></i>Home</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/disciplina"><i class="icon-star icon-white"></i>Disciplina</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/curso"><i class="icon-bookmark icon-white"></i>Curso</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/professor"><i class="icon-tags icon-white"></i>Professor</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/turma"><i class="icon-th icon-white"></i>Turma</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/aluno"><i class="icon-th-large icon-white"></i>Aluno</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/presenca"><i class="icon-ok icon-white"></i>Presença</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/nota"><i class="icon-file icon-white"></i>Notas</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/biblioteca/livro"><i class="icon-book icon-white"></i>Livro</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/biblioteca/emprestimo"><i class="icon-random icon-white"></i>Empréstimo</a> </li>
            </ul>
        </div>
    <?php endif ?>

</div>