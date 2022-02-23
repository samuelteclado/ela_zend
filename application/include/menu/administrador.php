<ul>
    <li><a href="<?php echo $this->baseUrl() ?>/admin/principal">Home</a></li>
    <li><a href="#">Cliente</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/cliente"><i class="icon-plus-sign"></i>Cadastro Clientes</a> </li>
            <li><a href="#"><i class="icon-file"></i>Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/clientes-geral">Relação de Clientes</a></li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/clientes-desistencia">Clientes/Desistencia</a></li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/clientes-confirmar">Clientes à Confirmar</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="#">Agenda</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/procedimento-cilios"><i class="icon-eye-open"></i> Cílios</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/procedimento-massagem"><i class="icon-heart"></i> Massagem</a> </li>
            <li><a href="#"><i class="icon-file"></i>Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/possivel-remarcacao">Possiveis Remarcações</a></li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/procedimento-cancelado">Procedimentos Cancelados</a></li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/funcionario/relatorio/clientes-confirmar">Clientes à Confirmar</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="#">Financeiro</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/recorrencia"><i class="icon-asterisk"></i> Recorrência</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/lancamento"><i class="icon-tasks"></i> Lançamento</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/plano-de-contas"><i class="icon-list-alt"></i> Plano de Contas</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/banco"><i class="icon-briefcase"></i> Banco</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/conta-bancaria"><i class="icon-hdd"></i> Conta Bancária</a> </li>
            <li><a href="#"><i class="icon-file"></i>Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/financeiro/contas-receber">Contas a Receber</a> </li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/financeiro/contas-pagar">Contas a Pagar</a> </li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/financeiro/extrato-periodo">Extrato Por Periodo</a> </li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/financeiro/extrato-procedimentos">Extrato Procedimentos</a> </li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/financeiro/fluxo-caixa">Fluxo de Caixa</a> </li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/financeiro/comissao">Comissão por Periodo</a> </li>
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
    <li><a href="#">Usuários</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/usuario/"><i class="icon-user"></i> Usuários</a></li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/usuario-grupo/"><i class="icon-user"></i> Grupo de Usuários</a></li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/usuario-funcionalidade/"><i class="icon-wrench"></i> Funcionalidades</a></li>
            <li><a href="#"><i class="icon-file"></i>Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl(); ?>/admin/relatorio/usuario">Usuários</a> </li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="<?php echo $this->baseUrl(); ?>/admin/procedimento-tipo">Procedimentos</a> </li>
    <li><a href="#">Empresa</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/empresa"><i class="icon-asterisk"></i> Dados da Empresa</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/pagamento-tipo"><i class="icon-hdd"></i> Tipos de Pagamento</a> </li>
            <li><a href="<?php echo $this->baseUrl(); ?>/admin/ponto"><i class="icon-time"></i> Registro de Ponto</a> </li>
        </ul>
    </li>

</ul>