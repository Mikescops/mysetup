<!DOCTYPE html>
<?php
    $this->layout = null;
    if(!$lang)
    {
      $lang = ($authUser && $authUser['preferredStore'] !== "US" && $authUser['preferredStore'] !== "UK" ? strtolower($authUser['preferredStore']) : "en");
    }
?>
<html lang="<?= $lang ?>">
<head>
	<title><?= h($setup->title) ?></title>
	<style type="text/css">
abbr,acronym,address,applet,aside,audio,big,blockquote,body,canvas,caption,cite,dd,details,dfn,div,dt,em,embed,figcaption,figure,footer,form,h2,h3,h4,h5,h6,header,hgroup,html,i,img,ins,kbd,label,mark,menu,nav,object,ol,output,p,q,s,section,span,strike,strong,sub,sup,tbody,tfoot,th,thead,tr,tt,u,ul,video{display:block}body{line-height:1;background:transparent}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:afterq:before,blockquote:before,q:after{content:'';content:none}table{border-collapse:collapse;border-spacing:0}body,html{max-width: 900px;height:100%;width:100%;box-sizing:border-box;margin:0;font-family:'Roboto','Helvetica Neue','Helvetica','Arial',sans-serif}.item_box{float:left;position:relative;width:23%;padding-bottom:23%;margin:1%;border-radius:4px;background-position:center center;background-repeat:no-repeat;background-size:contain;background-color:#fff}.item_box:hover,.Main-image:hover{opacity:.9;-webkit-transition:opacity .2s ease-in-out;-moz-transition:opacity .2s ease-in-out;-ms-transition:opacity .2s ease-in-out;-o-transition:opacity .2s ease-in-out;transition:opacity .2s ease-in-out}.main-image{margin:0 1%;border-radius:4px}.meta-links{padding-top:5px;margin:1%}.button-view{background-color:#328fea;border:.1rem solid #328fea;border-radius:4px;color:#fff;cursor:pointer;display:inline-block;font-size:1rem;height:2rem;letter-spacing:.1rem;line-height:2rem;text-align:center;text-decoration:none;text-transform:uppercase;white-space:nowrap;width:100%}.button-view:hover{background-color:#2b7dcc}
	</style>
</head>
<body>
	<a target="_blank" href="<?= $this->Url->build('/setups/'.$setup->id.'?ref=embed-'.urlencode($setup->user['name']), true)?>">
		<img alt="<?= $setup->title ?>" width="98%" class="main-image" src="<?= $this->Url->build('/'.$setup['resources']['featured_image'], true) ?>" alt="<?= $setup->title ?>">
	</a>

	<div class="config-items">
		<?php $i=0; foreach ($setup['resources']['products'] as $item): ?>

	        <a target="_blank" href="<?= $this->Url->build('/setups/'.$setup->id.'?ref=embed-'.urlencode($setup->user['name']), true)?>" id="item-trigger-<?= $i ?>" class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></a>

	    <?php $i++; if($i == 4) break; endforeach ?>
	</div>

	<div class="meta-links">
		<a target="_blank" href="<?= $this->Url->build('/setups/'.$setup->id.'?ref=embed-'.urlencode($setup->user['name']), true)?>" class="button-view"><?= __('View full setup') ?></a>
	</div>

</body>
