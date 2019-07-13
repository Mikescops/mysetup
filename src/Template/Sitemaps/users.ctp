<?php foreach ($records as $record) : ?>
<url>
    <loc><?= $this->Url->build("/users/".$record->id."-".h($record->name), true) ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.4</priority>
</url>
<?php endforeach; ?>