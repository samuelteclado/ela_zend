<ul>
    <li><a href="<?php echo $this->baseUrl() ?>/funcionario/principal">Home</a></li>
    <!--<li><a href="<?php echo $this->baseUrl(); ?>/funcionario/cliente/">Cliente</a></li>-->
    <li><a href="#">Cliente</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/cliente"><i class="icon-plus-sign"></i>Cadastro Clientes</a> </li>
            <li><a href="#"><i class="icon-file"></i>Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/clientes-confirmar">Clientes à Confirmar</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="#">Agenda</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/procedimento-cilios"><i class="icon-eye-open"></i> Cílios</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/procedimento-massagem"><i class="icon-heart"></i> Massagem</a> </li>
            <!---<li><a href="#"><i class="icon-file"></i>Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/procedimento-cancelado">Procedimentos Cancelados</a></li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/clientes-confirmar">Clientes à Confirmar</a></li>
                </ul>-->
            </li>
        </ul>
    </li>
</ul>