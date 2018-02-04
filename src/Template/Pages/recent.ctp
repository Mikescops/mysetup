<?php

$this->layout = 'default';
$this->assign('title', __('Latest Setups | mySetup.co'));

echo $this->Html->meta('description', __('See the most recent setups published on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Latest Setups | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'See the most recent setups published on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'See the most recent setups published on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/recent', true)], null ,['block' => true]);

?>
<div class="colored-container">
    <div class="container">
        <br><h2><?= __('Latest setups') ?></h2><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">
    <div class="row">
        <div class="column column-75">

            <div class="fullitem_holder">

            <?php foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= h($setup->title) ?>" src="<?= $this->Url->build('/' . (!empty($setup->resources[0]) ? $setup->resources[0]->src : 'img/not_found.jpg' )) ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i> <?= $setup->like_count ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> <?= $setup->user->name ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user->modificationDate, 'mmss', null, null)); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach ?>

            </div>

            <p class="no_more_setups"></p>

            <?= $this->Html->scriptBlock('infiniteScroll(6);', array('block' => 'scriptBottom')); ?>


        </div>
        <div class="column column-25 sidebar">
            <?= $this->element('Structure/sidebar') ?>
        </div>
    </div>
    </div>
</div>
