<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>

<div class="row">
    <div class="col-sm-12">
        <h3><?= __('Setups') ?></h3>
        <table class="table table-striped" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('author') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('featured') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('creationDate') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($setups as $setup): ?>
                <tr>
                    <td><?= $this->Number->format($setup->id) ?></td>
                    <td><?= $setup->has('user') ? $this->Html->link($setup->user->name, ['controller' => 'Users', 'action' => 'view', $setup->user->id]) : '' ?></td>
                    <td><?= h($setup->title) ?></td>
                    <td><?= h($setup->author) ?></td>
                    <td><?= h($setup->featured) ?></td>
                    <td><?= h($setup->creationDate) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $setup->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $setup->id], ['confirm' => __('Are you sure you want to delete this setup ?')]) ?>
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
