<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>
<div class="row">
    <div class="col-sm-12">
        <h3><?= __('Comments') ?> - <?= $this->Paginator->counter(['format' => {{count}}]) ?></h3>
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('content') ?></th>
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
                    <td><?= h($comment->content) ?></td>
                    <td><?= $comment->has('user') ? $this->Html->link($comment->user->name, ['controller' => 'Users', 'action' => 'view', $comment->user->id]) : '' ?></td>
                    <td><?= $comment->has('setup') ? $this->Html->link($comment->setup->title, ['controller' => 'Setups', 'action' => 'view', $comment->setup->id]) : '' ?></td>
                    <td><?= h($comment->dateTime) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['controller' => 'Setups', 'action' => 'view', $comment->setup->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $comment->id], ['confirm' => __('Are you sure you want to delete this comment ?')]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php $this->Paginator->setTemplates(['current' => '<li class="page-item active"><a class="page-link" href="{{url}}">{{text}}</a></li>']);
              $this->Paginator->setTemplates(['number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>']);
              $this->Paginator->setTemplates(['first' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>']);
              $this->Paginator->setTemplates(['last' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>']);
              $this->Paginator->setTemplates(['nextActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>']);
              $this->Paginator->setTemplates(['prevActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>']);
              $this->Paginator->setTemplates(['nextDisabled' => '<li class="page-item disabled"><a class="page-link" href="{{url}}">{{text}}</a></li>']);
              $this->Paginator->setTemplates(['prevDisabled' => '<li class="page-item disabled"><a class="page-link" href="{{url}}">{{text}}</a></li>']);  
         ?>
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