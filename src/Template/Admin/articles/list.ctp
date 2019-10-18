<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Articles | myAdmin'));
?>
<div class="col-12 col-md-9 col-xl-10">
    <a class="btn btn-primary" href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'articles_add']); ?>"><i data-feather="edit"></i> <?= __('Add Article') ?></a>
    <hr>

    <div style="overflow-x: auto;">
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('title', __('Title')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id', __('User')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('category', __('Category')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('dateTime', __('Date')) ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= $article->id ?></td>
                    <td><?= $this->Html->link($article->title, ['controller' => 'Articles', 'action' => 'view', $article->id]) ?></td>
                    <td><?= $article->has('user') ? $this->Html->link($article->user->name, ['controller' => 'Users', 'action' => 'view', $article->user->id]) : '' ?></td>
                    <td><?= $article->category ?></td>
                    <td><?= $this->Time->format($article->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $article->dateTime, $authUser['timeZone']); ?></td>
                    <td class="actions">
                        <?= $this->Html->link('<i data-feather="eye"></i>',  ['controller' => 'Articles', 'action' => 'view', $article->id, '#' => 'articles'], ['title' => __('View'), 'escape' => false]) ?>
                        <?= $this->Html->link('<i data-feather="edit-2"></i>',  ['controller' => 'Admin', 'action' => 'articles_edit', $article->id], ['title' => __('Edit'), 'escape' => false]) ?>
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
