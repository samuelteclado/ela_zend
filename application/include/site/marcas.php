<h2 id="titulo">Marcas</h2>
<?php if (count($this->marcas) > 0) { ?>
    <?php foreach ($this->marcas as $item): ?>
            <p class="marcas"><?php echo $item->descricao ?></p>
    <?php endforeach; ?>  
<?php } else { ?>
    <div style='padding: 30px; text-align: center;'>
        <img style="  padding: 10px;" src="/content/site/images/info.png" >
        <h3><b>Nenhuma marca encontrada.</b></h3>  
    </div> 
<?php } ?>
</div>

<p style="width: 300px; float: left; margin-top: 20px; text-align: right;"><img width="140" height="130"src="<?php echo $this->baseUrl() ?>/content/img/motor.jpg"></p>
