<div class="menu_superior">
    <ul>
        <a href="<?php echo $this->baseUrl(); ?>/institucional/">
        <li  <?php echo $this->active == 'index' ? 'class="active"' : '' ?> >
            Home
        </li>
        </a>
        <a href="<?php echo $this->baseUrl(); ?>/institucional/somos/">
        <li <?php echo $this->active == 'somos' ? 'class="active"' : '' ?> >
            Somos
        </li>
        </a>
        <a href="<?php echo $this->baseUrl(); ?>/institucional/servicos/">
        <li <?php echo $this->active == 'servicos' ? 'class="active"' : '' ?> >
            Serviços
        </li>
        </a>
        <a href="<?php echo $this->baseUrl(); ?>/institucional/contato/">
        <li <?php echo $this->active == 'contato' ? 'class="active"' : '' ?> >
            Contato
        </li>
        </a>
    </ul>
</div>