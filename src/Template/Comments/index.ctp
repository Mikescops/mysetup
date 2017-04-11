<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <nav class="column column-25">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('New Comment'), ['action' => 'add']) ?></li>
            <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
            <li><?= $this->Html->link(__('List Setups'), ['controller' => 'Setups', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New Setup'), ['controller' => 'Setups', 'action' => 'add']) ?></li>
        </ul>
    </nav>
    <div class="column column-75">
        <h3><?= __('Comments') ?></h3>
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('setup_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('dateTime') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= $this->Number->format($comment->id) ?></td>
                    <td><?= $comment->has('user') ? $this->Html->link($comment->user->name, ['controller' => 'Users', 'action' => 'view', $comment->user->id]) : '' ?></td>
                    <td><?= $comment->has('setup') ? $this->Html->link($comment->setup->title, ['controller' => 'Setups', 'action' => 'view', $comment->setup->id]) : '' ?></td>
                    <td><?= h($comment->dateTime) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $comment->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $comment->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $comment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comment->id)]) ?>
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
</div>