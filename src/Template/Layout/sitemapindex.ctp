<?xml version="1.0" encoding="UTF-8"?>

<?xml-stylesheet type="text/xsl" href="<?=$this->Url->build('/dist/sitemap.xsl', true) ?>"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?= $this->fetch('content') ?>
</sitemapindex>
