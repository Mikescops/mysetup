<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', __('Setups by ') . $user->name . ' | mySetup.co');
echo $this->Html->meta('description', __('All the setups shared by ') . $user->name, ['block' => true]);
?>

<div class="colored-container">

    <div class="container">
        <div class="row user-profile">

            <div class="column column-50">
                <img alt="<?= __('Profile picture of') ?> #<?= $user->id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">

                <div><h2><?= h($user->name) ?> <?php if($user->verified): echo '<i class="fa fa-check-square verified_account"></i>'; endif ?></h2>
                    <ul>
                        <?php
                            function urlPrettifying($url) {
                               return preg_replace('/https?:\/\/(www\.)?/', '', $url);
                            }
                        ?>

                        <?php if($user->uwebsite): ?><li><i class="fa fa-globe" style="margin-right: 2px;"></i> <a href="<?= $user->uwebsite ?>" rel="nofollow" target="_blank"><?= h(urlPrettifying($user->uwebsite)) ?></a></li><?php endif ?>
                        <?php if($user->ufacebook): ?><li><i class="fa fa-facebook" style="margin-right: 6px;"></i> <a href="<?= $user->ufacebook ?>" rel="nofollow" target="_blank"><?= h(urlPrettifying($user->ufacebook)) ?></a></li><?php endif ?>
                        <?php if($user->utwitter): ?><li><i class="fa fa-twitter"></i> <a href="<?= $user->utwitter ?>" rel="nofollow" target="_blank"><?= h(urlPrettifying($user->utwitter)) ?></a></li><?php endif ?>
                        <?php if($user->utwitch): ?><li><i class="fa fa-twitch"></i> <a href="<?= $user->utwitch ?>" rel="nofollow" target="_blank"><?= h(urlPrettifying($user->utwitch)) ?></a></li><?php endif ?>
                    </ul>
                </div>
            </div>

            <div class="column column-50">
                <ul class="user-stats">
                    <li><span><?= count($user['setups']) ?></span> <?= __n('setup', 'setups', count($user['setups'])) ?></li>
                    <li><span><?= count($user['likes']) ?></span> <?= __n('like', 'likes', count($user['likes'])) ?></li>
                    <li><span><?= count($user['comments']) ?></span> <?= __n('comment', 'comments', count($user['comments'])) ?></li>
                </ul>
            </div>

        </div>
    </div>


</div>

<div class="container">

    <div class="maincontainer">

        <div class="row">
            <div class="column column-100">
                <div class="feeditem userfeed">
                    <?php  if (!empty($user->setups)): foreach ($user->setups as $setup): ?>
                        <div class="fullitem">
                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                                <img alt="<?= ('Gallery image of') ?> <?= h($setup->title) ?>" src="<?= $this->Url->build('/' . (!empty($setup->resources[0]) ? $setup->resources[0]->src : 'img/not_found.jpg')) ?>">
                            </a>
                            <div class="red_like"><i class="fa fa-heart"></i> <?= $setup->like_count ?></div>

                            <div class="fullitem-inner">

                                <div class="row">

                                    <div class="column column-75">
                                        <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                            <img alt="<?= __('Profile picture of') ?> #<?= $setup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
                                        </a>

                                        <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                                            <h3>
                                                <?= h($setup->title) ?>
                                                <?php if($setup->status == 'DRAFT'): ?>
                                                    <i title="<?= __('Only you can see this setup') ?>" class="fa fa-eye-slash setup-unpublished"></i>
                                                <?php endif ?>
                                                <?php if($setup->id == $user->mainSetup_id): ?>
                                                    <i title="<?= ($authUser['id'] != $setup->user_id ? __('This is the main setup of') . ' ' . h($user->name) : __('This is your main setup')) ?>" class="fa fa-diamond setup-default"></i>
                                                <?php endif ?>
                                                <?php if($setup->featured): ?>
                                                    <i title="<?= __('This setup is featured on mySetup.co !')?>" class="fa fa-star setup-star"></i>
                                                <?php endif ?>
                                            </h3>
                                        </a>
                                    </div>

                                    <div class="column column-25"></div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>

                        <?php if(!$authUser || $authUser && $authUser->id != $user->id): ?>
                            <?= __('There is no setup here yet...') ?>
                        <?php endif; ?>

                    <?php endif ?>
                </div>

                <br clear="all">

                <?php if($authUser['admin']): ?>
                    <a class="button" href="#edit_user_admin" data-lity><?= __('Edit this user') ?></a>
                    <?= $this->element('Modal/edit-profile-admin') ?>
                <?php endif ?>

                <?php if($authUser && $authUser->id == $user->id): ?>
                    <div class="addsetup-suggest">
                        <i class="fa fa-plus"></i>
                        <?php if($authUser['mainSetup_id'] == 0): ?>
                            <p><?= __('Add your first setup !') ?></p>
                        <?php else: ?>
                            <p><?= __('Got another setup ? Add it now !') ?></p>
                        <?php endif; ?>
                        <a href="#add_setup_modal" data-lity class="hero_calltoaction"><?= __('Create a Setup') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
