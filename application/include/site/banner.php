<link rel="stylesheet" href="<?php echo $this->baseUrl() ?>/content/nivo-slider/themes/default/default.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $this->baseUrl() ?>/content/nivo-slider/nivo-slider.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $this->baseUrl() ?>/content/nivo-slider/demo/style.css" type="text/css" media="screen" />

<div style="width: 1000px; height: 400px; margin: auto;">
    <div class="slider-wrapper theme-default">
        <div id="slider" class="nivoSlider">
            <?php foreach ($this->banner as $item): ?>
            <img src="<?php echo AppUtil::getImageResized($item,'b',400, 1000)?>" data-thumb="<?php echo AppUtil::getFileView($item,'b',146,318)?>" title="<?php echo $item->descricao?>">
            <?php endforeach; ?>
        </div>       
    </div>
</div>

<script type="text/javascript" src="<?php echo $this->baseUrl() ?>/content/nivo-slider/demo/scripts/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="<?php echo $this->baseUrl() ?>/content/nivo-slider/jquery.nivo.slider.js"></script>
<script type="text/javascript">
$(window).load(function() {
    $('#slider').nivoSlider({
        effect: 'random', // Specify sets like: 'fold,fade,sliceDown'
        slices: 15, // For slice animations
        boxCols: 8, // For box animations
        boxRows: 4, // For box animations
        animSpeed: 900, // Slide transition speed
        pauseTime: 7000 // How long each slide will show
 
    });
});
</script>