<div id="direita">

    <?php if (Zend_Auth::getInstance()->getIdentity()->id != null) : ?>
        <div  id="div_perfil" class="rounded-light shadow">
            <div id="div_perfil_nome"><?php echo Zend_Auth::getInstance()->getIdentity()->nome ?></div>
            <img id="img_perfil" src="<?php echo AppUtil::getFileView(Zend_Auth::getInstance()->getIdentity(), 'u', '160') ?>">
            <div class="Bla Zx">Alterar foto do perfil</div>

            <ul id="menulist" >
                <li><a href="<?php echo $this->baseUrl(); ?>/diretoria/"><i class="icon-home icon-white"></i>Home</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/diretoria/escola/"><i class="icon-flag icon-white"></i>Escola</a></li>
                <li><a href="<?php echo $this->baseUrl(); ?>/diretoria/unidade"><i class="icon-map-marker icon-white"></i>Unidade</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/financeiro/conta"><i class="icon-hdd icon-white"></i>Conta Bancária</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/financeiro/lancamento"><i class="icon-asterisk icon-white"></i>Lançamento</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/disciplina"><i class="icon-star icon-white"></i>Disciplina</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/curso"><i class="icon-bookmark icon-white"></i>Curso</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/professor"><i class="icon-tags icon-white"></i>Professor</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/turma"><i class="icon-th icon-white"></i>Turma</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/aluno"><i class="icon-th-large icon-white"></i>Aluno</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/presenca"><i class="icon-ok icon-white"></i>Presença</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/secretaria/nota"><i class="icon-file icon-white"></i>Notas</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/biblioteca/livro"><i class="icon-book icon-white"></i>Livro</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/biblioteca/emprestimo"><i class="icon-random icon-white"></i>Empréstimo</a> </li>
                <li><a href="<?php echo $this->baseUrl(); ?>/diretoria/usuario/"><i class="icon-user icon-white"></i>Usuários</a></li>
                <li><a href="<?php echo $this->baseUrl(); ?>/financeiro/siges"><i class="icon-shopping-cart icon-white"></i>Siges</a> </li>
            </ul>
        </div>
    <?php endif ?>


</div>