<style>
    #img_perfil {
        width: 50px; 
        margin: 0px 0px 10px 1px;
        border-radius: 30px;
        border: 2px solid #FFF;
    }
    #pop_over{
        margin-top:5px;
        height: 110px;
        background-color: #FFF;
    }
    #pop_over_foto{
        width: 95%;
        margin: auto;
        height: 100px;
    }
    #pop_over_content{
        width: 170px;
        height: 100px;
        float: right;
    }
    #pop_over_nome{
        font-size: 14px;
        font-weight: bold;
        margin: 4px 0 15px 0;
    }
    #pop_over_email{
        font-size: 12px;
        letter-spacing:0px;
        color: #677;
        margin: 5px 0 15px 0;
    }
</style>
<div id="top_header_small" >
    <a href="http://www.sbrasilsolucoes.com/institucional" target='_blank' style="position:absolute; margin: -5px 0px 0px 5px;">
        <img style="width: 60px; float: left;" src="<?php echo PageUtil::getLogo() ?>">
    </a>
    <div style="width: 420px;margin-top: 15px;height: 25px;text-align: right; float: right; line-height: 25px; font-size: 12px; color: #777">
        <span style="text-transform: uppercase; text-align: left;margin-right: 40px;display: block; font-weight: bold;letter-spacing: -1px;position: relative;color: #fff;font-weight: bold;font-size: 24px; ">Espaço ELA</span>
        <?php if (Zend_Auth::getInstance()->getIdentity()->id != null) : ?>
            <div style="width: 45px;height: 40px;float: right;margin-top: -40px">
                <div id="foto_perfil" rel="popover" data-content="
                     <div id='pop_over'>
                     <div id='pop_over_foto'>
                     <img src='<?php echo AppUtil::getFileView(Zend_Auth::getInstance()->getIdentity(), 'u', '100') ?>'>
                     <div id='pop_over_content'>
                     <p id='pop_over_nome'><?php echo ucfirst(Zend_Auth::getInstance()->getIdentity()->nome) ?></p>
                     <p id='pop_over_email'><?php echo (Zend_Auth::getInstance()->getIdentity()->email) ?></p>
                         <a class='btn btn-primary' href='<?php echo $this->baseUrl(); ?>/funcionario/perfil'>Ver Perfil</a>
                         <a class='btn' style='float: right' href='<?php echo $this->base_url ?>/admin/auth/logout'>Sair</a>
                     </div>
                     </div> 
                     </div>">
                    <img id="img_perfil"  src="<?php echo AppUtil::getFileView(Zend_Auth::getInstance()->getIdentity(), 'u', '50') ?>">
                </div>
            </div>
        <?php endif; ?>      
    </div>
</div>


