<?php

/**
 * @var \App\View\AppView $this
 */

$this->assign('title', __('Setups by ') . h($user->name) . ' | mySetup.co');
echo $this->Html->meta('description', __('All the setups shared by ') . $user->name, ['block' => true]);
?>

<div class="colored-container">

    <div class="container">
        <div class="row user-profile">

            <div class="column column-50">
                <img alt="<?= __('Profile picture of') ?> #<?= $user->id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">

                <div>
                    <h2><?= h($user->name) ?> <?php if ($user->verified) : echo '<i class="fa fa-check-circle verified_account"></i>';
                                                endif ?></h2>
                    <ul>
                        <?php if ($user->uwebsite) : ?><li><i class="fa fa-globe" style="margin-right: 2px;"></i> <a href="<?= h($user->uwebsite) ?>" rel="nofollow" target="_blank"><?= h($this->MySetupTools->urlPrettifying($user->uwebsite)) ?></a></li><?php endif ?>
                        <?php if ($user->ufacebook) : ?><li><i class="fab fa-facebook" style="margin-right: 6px;"></i> <a href="<?= h($user->ufacebook) ?>" rel="nofollow" target="_blank"><?= h($this->MySetupTools->urlPrettifying($user->ufacebook)) ?></a></li><?php endif ?>
                        <?php if ($user->utwitter) : ?><li><i class="fab fa-twitter"></i> <a href="<?= h($user->utwitter) ?>" rel="nofollow" target="_blank"><?= h($this->MySetupTools->urlPrettifying($user->utwitter)) ?></a></li><?php endif ?>
                        <?php if ($user->utwitch) : ?><li><i class="fab fa-twitch"></i> <a href="<?= h($user->utwitch) ?>" rel="nofollow" target="_blank"><?= h($this->MySetupTools->urlPrettifying($user->utwitch)) ?></a></li><?php endif ?>
                    </ul>
                </div>
            </div>

            <div class="column column-50">
                <ul class="user-stats">
                    <li><span><?= count($user['setups']) ?></span> <?= __n('setup', 'setups', count($user['setups'])) ?></li>
                    <li>
                        <?php if (count($user['likes'])) : ?>
                            <span><?= $this->Html->link(count($user['likes']), '/likes/' . $user->id . '-' . $user->name) ?></span>
                        <?php else : ?>
                            <span><?= count($user['likes']) ?></span>
                        <?php endif; ?>
                        <?= __n('like', 'likes', count($user['likes'])) ?>
                    </li>
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
                <div class="card-grid">
                    <?php if (!empty($user->setups)) : foreach ($user->setups as $setup) : ?>
                            <?= $this->element('List/card-item', ['setup' => $setup]) ?>
                        <?php endforeach;
                        else : ?>

                        <?php if (!$authUser || $authUser->id != $user->id) : ?>
                            <?= __('There is no setup here yet...') ?>
                        <?php endif; ?>

                    <?php endif ?>
                    <?php if ($authUser && $authUser->id == $user->id) : ?>
                        <div class="addsetup-suggest">
                            <i class="fa fa-plus-circle"></i>
                            <?php if ($authUser['mainSetup_id'] == 0) : ?>
                                <p><?= __('Add your first setup !') ?></p>
                            <?php else : ?>
                                <p><?= __('Got another setup ? Add it now !') ?></p>
                            <?php endif; ?>
                            <a href="<?= $this->Url->build(['controller' => 'Setups', 'action' => 'add']) ?>" class="hero_calltoaction"><?= __('Create a Setup') ?></a>
                        </div>
                        <br>
                    <?php endif; ?>
                </div>

                <br clear="all">

                <?php if ($authUser['admin']) : ?>
                    <a class="button" href="#edit_user_admin" data-lity><?= __('Edit this user') ?></a>
                    <?= $this->element('Modal/edit-profile-admin') ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>