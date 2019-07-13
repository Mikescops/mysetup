<sitemap>
    <loc>
        <?= $this->Url->build([
            'controller' => 'sitemaps',
            'action' => 'static'
        ], true) ?>
    </loc>
</sitemap>

<sitemap>
    <loc>
        <?= $this->Url->build([
            'controller' => 'sitemaps',
            'action' => 'setups'
        ], true) ?>
    </loc>
</sitemap>

<sitemap>
    <loc>
        <?= $this->Url->build([
            'controller' => 'sitemaps',
            'action' => 'articles'
        ], true) ?>
    </loc>
</sitemap>

<sitemap>
    <loc>
        <?= $this->Url->build([
            'controller' => 'sitemaps',
            'action' => 'users'
        ], true) ?>
    </loc>
</sitemap>