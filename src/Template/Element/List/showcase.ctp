<?php
    $rgb_colors = json_decode($setup->main_colors)[0];
?>

<div>
    <div class="container">
        <div class="rowfeed">
            <div class="feeditem">

<div class="setup-showcase">
	<h4><a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><?= h($setup->title) ?></a></h4>
	<div class="showcase-meta">
		<div class="showcase-avatar">
			<a class="author-avatar" href="<?=$this->Url->build('/users/'.$setup->user_id.'-'.$this->Text->slug($setup->user->name))?>">
                <img alt="<?= __('Profile picture of') ?> <?= h($setup->user->name) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user->modificationDate, 'mmss', null, null)); ?>">
            </a>
            <svg class="avatar-circle" width="80px" height="80px">
            	<use xlink:href="#half-circle"></use>
            	 <defs>
					<linearGradient id="main-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
						<stop offset="0%"   stop-color="#3A62A8"/>
						<stop offset="50%"   stop-color="#344154"/>
						<stop offset="100%"  stop-color="#3A62A8"/>
					</linearGradient>
					<symbol id="half-circle" viewBox="0 0 106 57"><path d="M102 4c0 27.1-21.9 49-49 49S4 31.1 4 4"></path></symbol>
				</defs>
            </svg>
            
		</div>
		<div class="showcase-author">
			<div class="author-name-prefix"><?= __('Creator') ?></div>
			<a class="author-name" href="<?=$this->Url->build('/users/'.$setup->user_id.'-'.$this->Text->slug($setup->user->name))?>"><?= h($setup->user->name) ?></a>
		</div>
		<div class="showcase-likes"><?= $setup->like_count ?> likes</div>
	</div>
	<div class="showcase-content">
		<div class="showcase-description"></div>
		<div class="showcase-products">
			<div class="config-items">
	            <?php foreach($setup['resources']['products'] as $item): ?>

	                <a target="_blank" href="<?= $this->Url->build('/setups/'.$setup->id, true) ?>" class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></a>

	            <?php endforeach ?>
        	</div>
		</div>
	</div>
</div>
<div class="setup-showcase-background" style="background-image: radial-gradient(ellipse closest-side, rgba(0, 0, 0, 0.60), #151515), url(<?= urldecode($setup['resources']['featured_image']) ?>);
"></div>


            </div>
            <br clear='all'>
        </div>
    </div>
</div>