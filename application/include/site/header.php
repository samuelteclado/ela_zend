<div id="top">
    <a href="<?php echo $this->baseUrl(); ?>/institucional" style="position:absolute;margin: -35px 0px 0px -30px">
        <img style="height: 200px " src="<?php echo $this->baseUrl()?>/content/img/logo1.png">
    </a>

    <div  style="width: 330px; height: 40px; float: right;">
        <div class="righthead" style="width: 330px; height: 70px; float: right;">
            <form action="<?php echo $this->baseUrl(); ?>/institucional/busca/" id="search_form">
                <input type="hidden" value="013012598562310282331:uphixrjrcpk" name="cx">
                <input type="hidden" value="FORID:9" name="cof">
                <input type="hidden" value="UTF-8" name="ie">
                <div class="search">
                    <h3 class="white bold">Busca</h3>
                    <ul>
                        <li><input type="text" id="s" name="q" value="Digite uma palavra-chave" id="searchBox" name="s" onblur="if(this.value == '') { this.value = 'Digite uma palavra-chave'; }" onfocus="if(this.value == 'Digite uma palavra-chave') { this.value = ''; }" class="txtfield"></li>
                        <li><input type="submit" name="sa" class="go backcolr" value=""></li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
    <div style="float:right;height: 30px;width: 370px;margin-top: 25px;margin-left: 305px">
        <div class="menu_superior">
            <div class="menu">
                <ul>
                    <li class="page_item page-item-2">
                        <a href="<?php echo $this->baseUrl(); ?>/institucional/">Home</a>
                    </li>
                    <li class="page_item page-item-2">
                        <a href="<?php echo $this->baseUrl(); ?>/institucional/somos/">Quem Somos</a>
                    </li>
                    <li class="page_item page-item-2">
                        <a href="<?php echo $this->baseUrl(); ?>/institucional/servicos/">Serviços</a>
                    </li>
                    <li class="page_item page-item-2">
                        <a href="<?php echo $this->baseUrl(); ?>/institucional/cliente/">Clientes</a>
                    </li> 
                    <li class="page_item page-item-2">
                        <a href="<?php echo $this->baseUrl(); ?>/institucional/contato/">Contato</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

</div>