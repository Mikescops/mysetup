<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Resource'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Setups'), ['controller' => 'Setups', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Setup'), ['controller' => 'Setups', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="resources index large-9 medium-8 columns content">
    <h3><?= __('Resources') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('setup_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('href') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resources as $resource): ?>
            <tr>
                <td><?= $this->Number->format($resource->id) ?></td>
                <td><?= $resource->has('user') ? $this->Html->link($resource->user->name, ['controller' => 'Users', 'action' => 'view', $resource->user->id]) : '' ?></td>
                <td><?= $resource->has('setup') ? $this->Html->link($resource->setup->title, ['controller' => 'Setups', 'action' => 'view', $resource->setup->id]) : '' ?></td>
                <td><?= h($resource->title) ?></td>
                <td><?= h($resource->href) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $resource->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $resource->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $resource->id], ['confirm' => __('Are you sure you want to delete # {0}?', $resource->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
