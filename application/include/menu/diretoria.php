<ul>
    <li><a href="<?php echo $this->baseUrl() ?>/diretoria">Home</a></li>
    <li><a href="<?php echo $this->baseUrl() ?>/diretoria/escola">Escola</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl(); ?>/diretoria/escola"><i class="icon-cog"></i> Configuração</a> </li>
            <li><a href="<?php echo $this->baseUrl() ?>/diretoria/unidade"><i class="icon-map-marker"></i> Unidades</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/diretoria/usuario"><i class="icon-user"></i> Usuários</a></li>

        </ul>
    </li>
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
            </li>
            <li><a href="<?php echo $this->baseUrl() ?>/financeiro/plano-de-contas"><i class="icon-list-alt"></i> Plano de Contas</a></li>
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
    <li><a href="#">Secretaria</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/disciplina"><i class="icon-star"></i> Disciplinas</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/curso"><i class="icon-bookmark"></i> Cursos</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/turma"><i class="icon-th"></i> Turmas</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/professor"><i class="icon-user"></i> Professores</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/comunicado"><i class="icon-envelope"></i> Comunicados</a></li>
            <li><a href="#"><i class="icon-file"></i> Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl() ?>/secretaria/turma/vagas"><i class="icon"></i> Vagas por Turma</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="#">Aluno</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/aluno"><i class="icon-user"></i> Cadastro de Alunos</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/presenca"><i class="icon-check"></i> Presença</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/nota"><i class="icon-plus-sign"></i> Notas</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/secretaria/boletim"><i class="icon-file"></i> Boletim</a></li>

            <!--<li><a href="#"><i class="icon-file"></i> Relatórios</a>
                <ul>
                    <li><a href="<?php echo $this->baseUrl() ?>/secretaria/turma/vagas"><i class="icon"></i> Vagas por Turma</a></li>
                </ul>
            </li>-->
        </ul>
    </li>
    <li><a href="#">Biblioteca</a>
        <ul>
            <li><a href="<?php echo $this->baseUrl() ?>/biblioteca/livro"><i class="icon-book"></i> Livros</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/biblioteca/emprestimo"><i class="icon-arrow-right"></i> Empréstimos</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/biblioteca/emprestimo/devolver"><i class="icon-arrow-left"></i> Devolução</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/biblioteca/livro-categoria"><i class="icon-tags"></i> Categorias</a></li>
            <li><a href="<?php echo $this->baseUrl() ?>/biblioteca/livro-grau-relevancia"><i class="icon-warning-sign"></i> Relevâncias</a></li>

        </ul>
    </li>
    <li><a href="<?php echo $this->baseUrl() ?>/financeiro/siges">Siges</a></li>
</ul>