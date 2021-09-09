<?php

class RssUtil {

    public static function getRss() {
    
        $feed = 'http://noticiasautomotivas.feedsportal.com/c/33946/f/615779/index.rss';
        $rss = simplexml_load_file($feed);
        $limit = 3;
        $count = 0;
        if ($rss) {
            foreach ($rss->channel->item as $item) {
                $noticia[] = $item;
                $count++;
                if ($count == $limit)
                    break;
            }
        }
        else {
            $noticia = 'Não foi possível acessar as notícias.';
        }
        return $noticia;
    }

}