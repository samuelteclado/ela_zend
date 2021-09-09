<?php

class Site_SiteMapController extends Zend_Controller_Action {

    public function indexAction() {
        // action body
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $fh = fopen("../content/sitemap.xml", 'w') or die("can't open file");
        $site = "http://www.siges.sbrasilsolucoes.com";
        $time = date("Y-m-d") . "T" . date("H:i:s+00:00");

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
         <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
         http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
         xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                <url>
                    <loc>' . $site . '</loc>
                    <lastmod>' . $time . '</lastmod>
                </url>
                <url>
                    <loc>' . $site . '/institucional</loc>
                </url>
                <url>
                    <loc>' . $site . '/admin</loc>
                </url>  
                      <url>
                    <loc>' . $site . '/aluno</loc>
                </url>  
                ';

        if ($categories)
            foreach ($categories as $sitemap)
                $xml .= '<url>
         <loc>http://www.example.com/categories/page/id/' . $sitemap["category_id"] . '</loc>
                        <lastmod>' . $time . '</lastmod>
                        <changefreq>monthly</changefreq>
                        <priority>1.0</priority>
                </url>
                ';

        if ($news)
            foreach ($news as $sitemap)
                $xml .= '<url>
                 <loc>http://www.example.com/news/index/id/' . $sitemap["news_id"] . '</loc>
                        <changefreq>daily</changefreq>
                        <priority>1.0</priority>
                </url>
                ';

        $xml .='</urlset>';

        fwrite($fh, $xml);
        fclose($fh);
    }

}

?>