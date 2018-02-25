<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Resources | myAdmin'));
?>

<div class="col-12 col-md-9 col-xl-10">
    <h3><?= __('Resources') ?> - <?= $this->Paginator->counter(['format' => '{{count}}']) ?></h3>
    <div style="overflow-x: auto;">
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
                    <td><?php if ($resource->href) :?> <a href="<?= urldecode(h($resource->href)) ?>"><i data-feather="link"></i></a> <?php endif ?></td>
                    <?php
                        $src = urldecode(h($resource->src));
                        if(substr($src, 0, strlen('uploads/files/')) === 'uploads/files/')
                        {
                            $src = $this->Url->build('/') . $src;
                        }
                    ?>
                    <td><a href="<?= $src ?>"><?= $src ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
