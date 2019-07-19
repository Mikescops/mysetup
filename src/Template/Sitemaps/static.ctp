<url>
    <loc><?= $this->Url->build('/', true) ?></loc>
    <changefreq>always</changefreq>
    <priority>1</priority>
    <xhtml:link
               rel="alternate"
               hreflang="fr"
               href="<?= $this->Url->build('/?lang=fr', true) ?>"/>
    <xhtml:link
               rel="alternate"
               hreflang="it"
               href="<?= $this->Url->build('/?lang=it', true) ?>"/>
    <xhtml:link
               rel="alternate"
               hreflang="es"
               href="<?= $this->Url->build('/?lang=es', true) ?>"/>
    <xhtml:link
               rel="alternate"
               hreflang="de"
               href="<?= $this->Url->build('/?lang=de', true) ?>"/>
    <xhtml:link
               rel="alternate"
               hreflang="en"
               href="<?= $this->Url->build('/?lang=en', true) ?>"/>
    <xhtml:link
               rel="alternate"
               hreflang="uk"
               href="<?= $this->Url->build('/?lang=uk', true) ?>"/>
    <xhtml:link
               rel="alternate"
               hreflang="us"
               href="<?= $this->Url->build('/?lang=us', true) ?>"/>
</url>
<url>
    <loc><?= $this->Url->build('/recent', true) ?></loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
</url>
<url>
    <loc><?= $this->Url->build('/staffpicks', true) ?></loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
</url>
<url>
    <loc><?= $this->Url->build('/blog', true) ?></loc>
    <changefreq>daily</changefreq>
    <priority>1</priority>
</url>
<url>
    <loc><?= $this->Url->build('/pages/team', true) ?></loc>
    <changefreq>daily</changefreq>
    <priority>1</priority>
</url>
<url>
    <loc><?= $this->Url->build('/pages/legal', true) ?></loc>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
</url>
<url>
    <loc><?= $this->Url->build('/pages/q&a', true) ?></loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
</url>
