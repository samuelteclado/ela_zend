<ul>
    <li><a href="<?php echo $this->baseUrl() ?>/diretoria">Home</a></li>
    <li><a href="#">Fornecedor</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/fornecedor"><i class="icon-shopping-cart"></i> Fornecedores</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/fornecedor-categoria"><i class="icon-folder-close"></i> Fornecedor Categorias</a></li>
        </ul>
    </li>
    <li><a href="#">Financeiro</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl() ?>/financeiro/conta"><i class="icon-hdd"></i> Conta Bancária</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/financeiro/lancamento"><i class="icon-asterisk"></i> Lançamentos</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/financeiro/boleto-baixa"><i class="icon-barcode"></i> Baixa Automática Boleto</a></li>
            <li><a href="#"><i class="icon-shopping-cart"></i> Produtos</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl() ?>/financeiro/produto"> Cadastros de Produtos</a></li>
                    <li><a href="<?php echo $this->baseUrl() ?>/financeiro/produto-aprovacao"> Aprovação de Produtos</a></li>
                </ul>
            </li>            <li><a href="<?php echo $this->baseUrl() ?>/financeiro/plano-de-contas"><i class="icon-list-alt"></i> Plano de Contas</a></li>
            <li><a href="#"><i class="icon-file"></i> Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl() ?>/financeiro/relatorio/boletos"><i class="icon"></i> Boletos Emitidos</a></li>
                    <li><a href="<?php echo $this->baseUrl() ?>/financeiro/relatorio/mensalidade"><i class="icon"></i> Mensalidade de Alunos</a></li>
                    <li><a href="<?php echo $this->baseUrl() ?>/financeiro/relatorio/extrato"><i class="icon"></i> Extrato Bancário</a></li>
                    <li><a href="<?php echo $this->baseUrl() ?>/financeiro/relatorio/fluxo-caixa"><i class="icon"></i> Fluxo de Caixa</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="<?php echo $this->baseUrl() ?>/financeiro/siges">Siges</a></li>
</ul>
