<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Comments | myAdmin'));
?>
<div class="col-12 col-md-9 col-xl-10">
    <h3><?= __('Comments') ?> - <?= $this->Paginator->counter(['format' => '{{count}}']) ?></h3>

    <div style="overflow-x: auto;">
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('content', __('Content')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id', __('User')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('setup_id', __('Setup')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('dateTime', __('Date')) ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= $comment->id ?></td>
                    <td><?= h($comment->content) ?></td>
                    <td><?= $comment->has('user') ? $this->Html->link($comment->user->name, ['controller' => 'Users', 'action' => 'view', $comment->user->id]) : '' ?></td>
                    <td><?= $comment->has('setup') ? $this->Html->link($comment->setup->title, ['controller' => 'Setups', 'action' => 'view', $comment->setup->id]) : '' ?></td>
                    <td><?= $this->Time->format($comment->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $comment->dateTime, $authUser['timeZone']); ?></td>
                    <td class="actions">
                        <?= $this->Html->link('<i data-feather="eye"></i>',  ['controller' => 'Setups', 'action' => 'view', $comment->setup->id, '#' => 'comments'], ['title' => __('View'), 'escape' => false]) ?>
                        <?= $this->Form->postLink('<i data-feather="trash-2"></i>', ['controller' => 'Comments', 'action' => 'delete', $comment->id], ['title' => __('Delete'), 'confirm' => __('Are you sure you want to delete this comment ?'), 'escape' => false]) ?>
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
