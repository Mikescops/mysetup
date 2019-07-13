<?php foreach ($records as $record) : ?>
<url>
    <loc><?= $this->Url->build("/setups/".$record->id."-".$this->Text->slug($record->title), true) ?></loc>
    <changefreq>daily</changefreq>
    <priority>0.7</priority>
    <?php if ($record->modifiedDate) : ?>
    <lastmod><?= $record->modifiedDate->format('c') ?></lastmod>
    <?php endif ?>
</url>
<?php endforeach; ?>