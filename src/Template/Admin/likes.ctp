<?php

/**
 * @var \App\View\AppView $this
 */

$this->layout = 'admin';
$this->assign('title', __('Likes | myAdmin'));
?>
<div class="col-12 col-md-9 col-xl-10">
    <h3><?= __('Likes') ?> - <?= $this->Paginator->counter(['format' => '{{count}}']) ?></h3>

    <div style="overflow-x: auto;">
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id', __('User')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('setup_id', __('Setup')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('dateTime', __('Date')) ?></th>
                    <th scope="col" class="actions"><?= __('See') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($likes as $like) : ?>
                    <tr>
                        <td><?= $like->id ?></td>
                        <td><?= $like->has('user') ? $this->Html->link($like->user->name, ['controller' => 'Users', 'action' => 'view', $like->user->id]) : '' ?></td>
                        <td><?= $like->has('setup') ? $this->Html->link($like->setup->title, ['controller' => 'Setups', 'action' => 'view', $like->setup->id]) : '' ?></td>
                        <td><?= $this->Time->format($like->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $like->dateTime, $authUser['timeZone']); ?></td>
                        <td class="actions">
                            <?= $this->Html->link('<i data-feather="eye"></i>',  ['controller' => 'Setups', 'action' => 'view', $like->setup->id, '#' => 'likes'], ['title' => __('View'), 'escape' => false]) ?>
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