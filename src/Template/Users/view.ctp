<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Setups'), ['controller' => 'Setups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Setup'), ['controller' => 'Setups', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($user->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mail') ?></th>
            <td><?= h($user->mail) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('ProfileImagePath') ?></th>
            <td><?= h($user->profileImagePath) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Facebook') ?></th>
            <td><?= h($user->facebook) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Twitter') ?></th>
            <td><?= h($user->twitter) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mastodon') ?></th>
            <td><?= h($user->mastodon) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Verified') ?></th>
            <td><?= $user->verified ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Setups') ?></h4>
        <?php if (!empty($user->setups)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Author') ?></th>
                <th scope="col"><?= __('Counter') ?></th>
                <th scope="col"><?= __('Featured') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->setups as $setups): ?>
            <tr>
                <td><?= h($setups->id) ?></td>
                <td><?= h($setups->user_id) ?></td>
                <td><?= h($setups->title) ?></td>
                <td><?= h($setups->description) ?></td>
                <td><?= h($setups->author) ?></td>
                <td><?= h($setups->counter) ?></td>
                <td><?= h($setups->featured) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Setups', 'action' => 'view', $setups->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Setups', 'action' => 'edit', $setups->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Setups', 'action' => 'delete', $setups->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setups->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
