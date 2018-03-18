<?php

$this->layout = 'default';
$this->assign('title', __('My likes | mySetup.co'));

echo $this->Html->meta('description', __('Setups you like on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'My likes | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'Setups you like on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'Setups you like on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/likes', true)], null ,['block' => true]);

?>

<div class="colored-container">
    <div class="container">
        <br><h2><?= __('My likes') ?></h2><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">

    <div class="row">
        <div class="column">
            <div class="feeditem">

            <?php foreach ($likes as $like): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$like->setup->id.'-'.$this->Text->slug($like->setup->title)); ?>">
                    <img alt="<?= h($like->setup->title) ?>" src="<?= $this->Url->build('/' . (!empty($like->setup->resources[0]) ? $like->setup->resources[0]->src : 'img/not_found.jpg')) ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i> <?= $like->setup->like_count ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$like->setup->user->id)?>">
                                <img alt="<?= __('Profile picture of') ?> <?= h($like->setup->user->name) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $like->setup->user->id . '.png?' . $this->Time->format($like->setup->user->modificationDate, 'mmss', null, null)); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$like->setup->id.'-'.$this->Text->slug($like->setup->title)); ?>"><h3><?= h($like->setup->title) ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach ?>

            </div>

        </div>
    </div>

    </div>
</div>
