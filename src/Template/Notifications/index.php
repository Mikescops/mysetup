<?php
    $this->layout = 'default';
    $this->assign('title', 'Notifications | mySetup;co');
?>

<div class="row">
    <div class="col-sm-12">
        <h3><?= __('Notifications') ?> - <?= $this->Paginator->counter(['format' => __('{{count}}')]) ?></h3>
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('content') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('dateTime') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                <tr>
                    <td><?= h($notification->content) ?></td>
                    <td><?= h($notification->dateTime) ?></td>
                    <td class="actions">
                        <?php
                            /* BUTTON MARK AS READ / MARK AS NON-READ */
                        ?>
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
