<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Setup'), ['action' => 'edit', $setup->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Setup'), ['action' => 'delete', $setup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setup->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Setups'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Setup'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Resources'), ['controller' => 'Resources', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Resource'), ['controller' => 'Resources', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="setups view large-9 medium-8 columns content">
    <h3><?= h($setup->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $setup->has('user') ? $this->Html->link($setup->user->name, ['controller' => 'Users', 'action' => 'view', $setup->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($setup->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Author') ?></th>
            <td><?= h($setup->author) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($setup->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Counter') ?></th>
            <td><?= $this->Number->format($setup->counter) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Featured') ?></th>
            <td><?= $setup->featured ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($setup->description)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Resources') ?></h4>
        <?php if (!empty($setup->resources)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Setup Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Href') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($setup->resources as $resources): ?>
            <tr>
                <td><?= h($resources->id) ?></td>
                <td><?= h($resources->setup_id) ?></td>
                <td><?= h($resources->title) ?></td>
                <td><?= h($resources->href) ?></td>
                <td><?= h($resources->type) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Resources', 'action' => 'view', $resources->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Resources', 'action' => 'edit', $resources->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Resources', 'action' => 'delete', $resources->id], ['confirm' => __('Are you sure you want to delete # {0}?', $resources->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
