<h2 class="texto" id="titulo">Notícias</h2>
<style>
    .link-noticia:hover{text-decoration: underline; color: #0066CC;}
</style>
<?php $noticias = RssUtil::getRss(); ?>
<?php foreach ($noticias as $item): ?>
    <a class="link-noticia" href="<?php echo $item->link ?>" target="_blank">
        <p style="border-bottom: 1px solid #ccc; padding: 10px 0px; color: #019AD2; margin: 0 10px;">
            <?php echo $item->title ?>
        </p>
    </a>
<?php endforeach; ?>
    