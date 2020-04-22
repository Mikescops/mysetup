<?php

$this->layout = 'default';
$seo_title =  __('{0}\'s likes', h($user->name)) . ' | mySetup.co';
$seo_description = __('Setups liked by {0} on mySetup.co', h($user->name));

$this->assign('title', $seo_title);
echo $this->Html->meta('description', $seo_description, ['block' => true]);
echo $this->Html->meta(['property' => 'og:title', 'content' => $seo_title], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:title', 'content' => $seo_title], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $seo_description], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => $seo_description], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:type', 'content' => 'website'], null, ['block' => true]);
?>

<div class="colored-container">
    <div class="container">
        <div class="row user-profile">
            <div class="column column-80">
                <img alt="<?= __('Profile picture of') ?> #<?= $user->id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
                <div>
                    <h2><?= __('{0}\'s likes', h($user->name)) ?> <?php if ($user->verified) : echo '<i class="fa fa-check-circle verified_account"></i>';
                                                                    endif ?></h2>
                </div>
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
                <div class="card-grid">

                    <?php foreach ($likes as $like) : ?>

                        <?= $this->element('List/card-item', ['setup' => $like->setup]) ?>

                    <?php endforeach ?>

                </div>

            </div>
        </div>

    </div>
</div>