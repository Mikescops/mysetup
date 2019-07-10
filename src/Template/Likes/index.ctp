<?php

$this->layout = 'default';
$this->assign('title', __('{0}\'s likes', h($user->name)) . ' | mySetup.co');

echo $this->Html->meta('description', __('Setups liked by {0} on mySetup.co', $user->name), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'My likes | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => __('Setups liked by {0} on mySetup.co', $user->name)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => __('Setups liked by {0} on mySetup.co', $user->name)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build($this->request->getPath(), true)], null ,['block' => true]);

?>

<div class="colored-container">
    <div class="container">
        <div class="row user-profile">
            <div class="column column-80">
                <img alt="<?= __('Profile picture of') ?> #<?= $user->id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
                <div><h2><?= __('{0}\'s likes', h($user->name)) ?> <?php if($user->verified): echo '<i class="fa fa-check-circle verified_account"></i>'; endif ?></h2></div>
            </div>
            <div class="column column-20">
                <ul class="user-stats">
                    <li><span><?= count($likes) ?></span></li>
                </ul>
            </div>
        </div>
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
                <div class="badge_like"><i class="fa fa-thumbs-up"></i> <?= $like->setup->like_count ?></div>

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
