<?xml version="1.0" encoding="UTF-8"?>

<?xml-stylesheet type="text/xsl" href="<?=$this->Url->build('/css/', true)?>sitemap.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<?php foreach($data as $key => $dataForKey): ?>
		<!-- <?= __($key) ?> -->
		<?php foreach($dataForKey as $record): ?>
			<url>
				<loc><?= h($record->_loc) ?></loc>
				<changefreq><?= h($record->_changefreq) ?></changefreq>
				<priority><?= h($record->_priority) ?></priority>
			</url>
		<?php endforeach; ?>
	<?php endforeach; ?>


	<url>
		<loc>https://mysetup.co/</loc>
		<changefreq>always</changefreq>
		<priority>1</priority>
	</url>
	<url>
		<loc>https://mysetup.co/recents</loc>
		<changefreq>daily</changefreq>
		<priority>0.9</priority>
	</url>
	<url>
		<loc>https://mysetup.co/popular</loc>
		<changefreq>daily</changefreq>
		<priority>0.9</priority>
	</url>
</urlset>
