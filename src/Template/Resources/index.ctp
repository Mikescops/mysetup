<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>
<div class="row">
    <div class="col-sm-12">
        <h3><?= __('Resources') ?> - <?= $this->Paginator->counter(['format' => '{{count}}']) ?></h3>
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('user_id', __('User')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('setup_id', __('Setup')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('type', __('Type')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('title', __('Title')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('href', 'HREF') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('src', 'SRC') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resources as $resource): ?>
                <tr>
                    <td><?= $resource->id ?></td>
                    <td><?= $this->Html->link($resource->user->name, ['controller' => 'Users', 'action' => 'view', $resource->user_id]) ?></td>
                    <td><?= $this->Html->link($resource->setup->title, ['controller' => 'Setups', 'action' => 'view', $resource->setup_id]) ?></td>
                    <td><?= h($resource->type) ?></td>
                    <td><?= urldecode(h($resource->title)) ?></td>
                    <td><?= urldecode(h($resource->href)) ?></td>
                    <td><?= urldecode(h($resource->src)) ?></td>
                    <td class="actions">
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $resource->id], ['confirm' => __('Are you sure you want to delete this resource ?')]) ?>
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
            <ul class="pagination pagination-large">
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
