<?php foreach ($records as $record) : ?>
<url>
    <loc><?= $this->Url->build("/blog/".$record->id."-".$this->Text->slug($record->title), true) ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.6</priority>
    <?php if ($record->dateTime) : ?>
    <lastmod><?= $record->dateTime->format('c') ?></lastmod>
    <?php endif ?>
</url>
<?php endforeach; ?>