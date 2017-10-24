<!DOCTYPE html>
<?php
    $this->layout = null;
?>
<html lang="<?= $lang ?>">
<head>
    <title><?= h($setup->title) ?></title>
    <style type="text/css">
abbr,acronym,address,applet,aside,audio,big,blockquote,body,canvas,caption,cite,dd,details,dfn,div,dt,em,embed,figcaption,figure,footer,form,h2,h3,h4,h5,h6,header,hgroup,html,i,img,ins,kbd,label,mark,menu,nav,object,ol,output,p,q,s,section,span,strike,strong,sub,sup,tbody,tfoot,th,thead,tr,tt,u,ul,video{display:block}body{line-height:1;background:transparent}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:afterq:before,blockquote:before,q:after{content:'';content:none}table{border-collapse:collapse;border-spacing:0}body,html{height:100%;width:100%;overflow:hidden;box-sizing:border-box;margin:0;font-family:'Roboto','Helvetica Neue','Helvetica','Arial',sans-serif}.embed-frame{border-radius:4px;overflow:hidden}.config-items{height:25vw}.item_box{float:left;position:relative;width:25%;padding-bottom:25%;background-position:center center;background-repeat:no-repeat;background-size:contain;background-color:#fff}.item_box:hover,.Main-image:hover{opacity:.9;-webkit-transition:opacity .2s ease-in-out;-moz-transition:opacity .2s ease-in-out;-ms-transition:opacity .2s ease-in-out;-o-transition:opacity .2s ease-in-out;transition:opacity .2s ease-in-out}.box-image{height:50vw;overflow:hidden}.main-image{height:100%;min-width:100%}.meta-links{padding-top:5px}.box-image h3{position:absolute;bottom:2vw;margin:0;left:1.5vw;color:#fff;font-size:7vw;text-shadow:0 1px 0 black}.box-overlay{position:absolute;top:0;left:0;width:100%;height:50vw;background:-moz-linear-gradient(top,rgba(0,0,0,0) 0,rgba(0,0,0,.8) 100%);background:-webkit-linear-gradient(top,rgba(0,0,0,0) 0,rgba(0,0,0,.8) 100%);background:linear-gradient(to bottom,rgba(0,0,0,0) 0,rgba(0,0,0,.8) 100%);background-repeat:repeat;background-position-x:0%;background-position-y:0%;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#00000000',endColorstr='#cc000000',GradientType=0);background-position:top;background-repeat:repeat-x;z-index:8}.box-overlay:hover{opacity:.9;-webkit-transition:opacity .2s ease-in-out;-moz-transition:opacity .2s ease-in-out;-ms-transition:opacity .2s ease-in-out;-o-transition:opacity .2s ease-in-out;transition:opacity .2s ease-in-out}.watermark{text-decoration:none;position:absolute;top:2vw;height:12vh;max-height:20vw;right:2vw;opacity:1;z-index:10}.watermark img{height:100%}.watermark:hover{top:2.5vw;-webkit-transition:.3s;-moz-transition:.3s;-ms-transition:.3s;-o-transition:.3s;transition:.3s}
    </style>
</head>
<body>

	<div class="embed-frame">
		<a target="_blank" href="<?= $this->Url->build('/setups/'.$setup->id.'?ref=embedProgram', true)?>">
			<div class="box-image">

			    <img alt="<?= $setup->title ?>" class="main-image" src="<?= $this->Url->build('/'.$setup['resources']['featured_image'], true) ?>">

			    <div class="box-overlay"><h3><?= __('Setup of') ?> <?= $setup->user['name'] ?></h3></div>
			</div>
		</a>

		<a href="<?= $this->Url->build('/?ref=embedProgram', true)?>" class="watermark"><?php echo $this->Html->image('mySetup_logo.svg', array('alt' => 'mysetup.co')); ?></a>

	    <div class="config-items">
	        <?php $i=0; foreach ($setup['resources']['products'] as $item): ?>

	            <a target="_blank" href="<?= $this->Url->build('/setups/'.$setup->id.'?ref=embedProgram', true)?>" id="item-trigger-<?= $i ?>" class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></a>

	        <?php $i++; if($i == 4) break; endforeach ?>
	    </div>
	</div>

</body>
