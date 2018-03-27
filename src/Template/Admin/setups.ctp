<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Setups | myAdmin'));
?>

<div class="col-12 col-md-9 col-xl-10">

    <h3><?= __('Setups') ?> - <?= $this->Paginator->counter(['format' => '{{count}}']) ?></h3>

    <div style="overflow-x: auto;">
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id', __('User')) ?></th>
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
                <?php foreach ($setups as $setup): ?>
                <tr>
                    <td><?= $setup->id ?></td>
                    <td><?= $setup->has('user') ? $this->Html->link($setup->user->name, ['controller' => 'Users', 'action' => 'view', $setup->user->id]) : '' ?></td>
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
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers(['first' => 1, 'last' => 1, 'modulus' => 3]) ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>

