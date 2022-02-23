<ul>
    <li><a href="<?php echo $this->baseUrl() ?>/secretaria/principal">Home</a></li>
    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/cliente/">Cliente</a></li>
    <li><a href="#">Agenda</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/procedimento-cilios"><i class="icon-eye-open"></i> Cílios</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/procedimento-massagem"><i class="icon-heart"></i> Massagem</a> </li>
            <li><a href="#"><i class="icon-file"></i>Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/procedimento-cancelado">Procedimentos Cancelados</a></li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/clientes-confirmar">Clientes à Confirmar</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="#">Fornecedor</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/fornecedor"><i class="icon-shopping-cart"></i> Fornecedor</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/fornecedor-categoria"><i class="icon-folder-close"></i> Categoria de Fornecedor</a> </li>
            <li><a href="#"><i class="icon-file"></i>Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/relatorio/fornecedor"> Fornecedores</a> </li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="#">Produto</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/produto-venda"><i class="icon-shopping-cart"></i> Venda de Produtos</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/produto-compra"><i class="icon-shopping-cart"></i> Compra de Produtos</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/produto"><i class="icon-eye-open"></i> Produto</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/produto-categoria"><i class="icon-folder-close"></i> Categoria de Produto</a> </li>
            <li><a href="#"><i class="icon-file"></i> Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/relatorio/produto"> Produtos</a> </li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="#">Empresa</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/empresa"><i class="icon-asterisk"></i> Dados da Empresa</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/procedimento-tipo"><i class="icon-eye-open"></i> Tipos de Procedimentos</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/pagamento-tipo"><i class="icon-hdd"></i> Tipos de Pagamento</a> </li>
        </ul>
    </li>

</ul>