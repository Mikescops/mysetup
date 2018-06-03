<?xml version="1.0" encoding="UTF-8"?>

<?xml-stylesheet type="text/xsl" href="<?=$this->Url->build('/sitemap/css/sitemap.xsl', true) ?>"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<?php foreach($data as $key => $dataForKey): ?>
		<?php foreach($dataForKey as $record): ?>
	<url>
		<loc><?= h($record->_loc) ?></loc>
		<changefreq><?= h($record->_changefreq) ?></changefreq>
		<priority><?= h($record->_priority) ?></priority>
		<?php if($record->_lastmod): ?>
		<lastmod><?= $record->_lastmod->format('c') ?></lastmod>
		<?php endif ?>
	</url>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<url>
		<loc>https://mysetup.co/</loc>
		<changefreq>always</changefreq>
		<priority>1</priority>
	</url>
	<url>
		<loc>https://mysetup.co/?lang=fr</loc>
		<changefreq>always</changefreq>
		<priority>1</priority>
	</url>
	<url>
		<loc>https://mysetup.co/?lang=en</loc>
		<changefreq>always</changefreq>
		<priority>1</priority>
	</url>
	<url>
		<loc>https://mysetup.co/?lang=it</loc>
		<changefreq>always</changefreq>
		<priority>1</priority>
	</url>
	<url>
		<loc>https://mysetup.co/?lang=es</loc>
		<changefreq>always</changefreq>
		<priority>1</priority>
	</url>
	<url>
		<loc>https://mysetup.co/?lang=de</loc>
		<changefreq>always</changefreq>
		<priority>1</priority>
	</url>
	<url>
		<loc>https://mysetup.co/recent</loc>
		<changefreq>daily</changefreq>
		<priority>0.9</priority>
	</url>
	<url>
		<loc>https://mysetup.co/staffpicks</loc>
		<changefreq>daily</changefreq>
		<priority>0.9</priority>
	</url>
	<url>
		<loc>https://mysetup.co/blog</loc>
		<changefreq>daily</changefreq>
		<priority>0.9</priority>
	</url>
	<url>
		<loc>https://mysetup.co/pages/team</loc>
		<changefreq>daily</changefreq>
		<priority>0.3</priority>
	</url>
	<url>
		<loc>https://mysetup.co/pages/legal</loc>
		<changefreq>daily</changefreq>
		<priority>0.3</priority>
	</url>
	<url>
		<loc>https://mysetup.co/pages/q&amp;a</loc>
		<changefreq>daily</changefreq>
		<priority>0.3</priority>
	</url>
</urlset>
