<?php

/**
 * @var \App\View\AppView $this
 */

$this->layout = 'admin';
$this->assign('title', __('Users | myAdmin'));
?>

<div class="col-12 col-md-9 col-xl-10">
    <h3><?= h($user->name) ?> - <?= $user->id ?></h3>

    <div class="container-fluid">

        <div class="row mb-4 mt-4">
            <div class="col">
                <img width="100" alt="<?= __('Profile picture of') ?> #<?= $user->id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
                <hr><?= $this->Html->link('<i data-feather="eye"></i> ' . __('View live profile'), ['controller' => 'Users', 'action' => 'view', $user->id], ['title' => __('View'), 'escape' => false]) ?>
            </div>
            <div class="col-md-4 col-sm-12">
                <ul class="list-unstyled">
                    <li><i data-feather="mail" height="16" width="20"></i> <?= h($user->mail) ?></li>
                    <li><i data-feather="shopping-bag" height="16" width="20"></i> <?= h($user->preferredStore) ?></li>
                    <li><i data-feather="clock" height="16" width="20"></i> <?= $this->Time->format($user->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->creationDate, $authUser['timeZone']); ?></li>
                    <li><i data-feather="edit-2" height="16" width="20"></i> <?= $this->Time->format($user->modificationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->modificationDate, $authUser['timeZone']); ?></li>
                    <li><i data-feather="key" height="16" width="20"></i> <?= $this->Time->format($user->lastLogginDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->lastLogginDate, $authUser['timeZone']); ?></li>
                </ul>
            </div>
            <div class="col-md-4 col-sm-12">
                <ul class="list-unstyled">
                    <?php if ($user->uwebsite) : ?><li><i data-feather="globe" height="16" width="20"></i> <a href="<?= h($user->uwebsite) ?>" rel="nofollow" target="_blank"><?= h($this->MySetupTools->urlPrettifying($user->uwebsite)) ?></a></li><?php endif ?>
                    <?php if ($user->ufacebook) : ?><li><i data-feather="facebook" height="16" width="20"></i> <a href="<?= h($user->ufacebook) ?>" rel="nofollow" target="_blank"><?= h($this->MySetupTools->urlPrettifying($user->ufacebook)) ?></a></li><?php endif ?>
                    <?php if ($user->utwitter) : ?><li><i data-feather="twitter" height="16" width="20"></i> <a href="<?= h($user->utwitter) ?>" rel="nofollow" target="_blank"><?= h($this->MySetupTools->urlPrettifying($user->utwitter)) ?></a></li><?php endif ?>
                    <?php if ($user->utwitch) : ?><li>
                        <i data-feather="twitch" height="16" width="20"></i> <a href="<?= h($user->utwitch) ?>" rel="nofollow" target="_blank"><?= h($this->MySetupTools->urlPrettifying($user->utwitch)) ?></a>
                        | <?= $user->twitchUserId ?>
                    </li><?php endif ?>
                    <li><i data-feather="award" height="16" width="20"></i> <?= $user->verified ? __('Verified') : __('Not verified') ?></li>
                </ul>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col" style="overflow-x: auto;">
                <h4><?= __('Setups') ?></h4>

                <div class="table-responsive">
                    <table class="table table-striped" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('title', __('Title')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('author', __('Author')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('featured', __('Featured')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('status', __('Status')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('creationDate', __('Created on')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('modifiedDate', __('Modified on')) ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user->setups as $setup) : ?>
                                <tr>
                                    <td><?= $setup->id ?></td>
                                    <td><?= h($setup->title) ?></td>
                                    <td><?= h($setup->author) ?></td>
                                    <td><?= ($setup->featured ? __('Yes') : __('No')) ?></td>
                                    <td><?= h($setup->status) ?></td>
                                    <td><?= $this->Time->format($setup->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $setup->creationDate, $authUser['timeZone']); ?></td>
                                    <td><?= $this->Time->format($setup->modifiedDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $setup->modifiedDate, $authUser['timeZone']); ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link('<i data-feather="eye"></i>', ['controller' => 'Setups', 'action' => 'view', $setup->id], ['title' => __('View'), 'escape' => false]) ?>
                                        <?= $this->Form->postLink('<i data-feather="trash-2"></i>', ['controller' => 'Setups', 'action' => 'delete', $setup->id], ['title' => __('Delete'), 'confirm' => __('Are you sure you want to delete this setup ?'), 'escape' => false]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6" style="overflow-x: auto;">
                <h4><?= __('Comments') ?></h4>

                <div class="table-responsive">
                    <table class="table table-striped" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('content', __('Content')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('setup_id', __('Setup')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('dateTime', __('Date')) ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user->comments as $comment) : ?>
                                <tr>
                                    <td><?= $comment->id ?></td>
                                    <td><?= h($comment->content) ?></td>
                                    <td><?= $this->Html->link($comment->setup->title, ['controller' => 'Setups', 'action' => 'view', $comment->setup_id]) ?></td>
                                    <td><?= $this->Time->format($comment->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $comment->dateTime, $authUser['timeZone']); ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link('<i data-feather="eye"></i>',  ['controller' => 'Setups', 'action' => 'view', $comment->setup_id, '#' => 'comments'], ['title' => __('View'), 'escape' => false]) ?>
                                        <?= $this->Form->postLink('<i data-feather="trash-2"></i>', ['controller' => 'Comments', 'action' => 'delete', $comment->id], ['title' => __('Delete'), 'confirm' => __('Are you sure you want to delete this comment ?'), 'escape' => false]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6" style="overflow-x: auto;">
                <h4><?= __('Likes') ?></h4>

                <div class="table-responsive">
                    <table class="table table-striped" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('setup_id', __('Setup')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('dateTime', __('Date')) ?></th>
                                <th scope="col" class="actions"><?= __('See') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user->likes as $like) : ?>
                                <tr>
                                    <td><?= $like->id ?></td>
                                    <td><?= $this->Html->link($like->setup->title, ['controller' => 'Setups', 'action' => 'view', $like->setup_id]) ?></td>
                                    <td><?= $this->Time->format($like->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $like->dateTime, $authUser['timeZone']); ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link('<i data-feather="eye"></i>',  ['controller' => 'Setups', 'action' => 'view', $like->setup_id], ['title' => __('View'), 'escape' => false]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
