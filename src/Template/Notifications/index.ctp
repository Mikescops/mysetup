<?php

$this->layout = 'default';
$this->assign('title', __('Notifications') . ' | mySetup.co');

echo $this->Html->meta('description', __('Your notifications on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Notifications | mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'Your notifications on mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'Your notifications on mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/notifications', true)], null, ['block' => true]);

?>

<div class="col-12 col-md-9 col-xl-10">
    <h3><?= __('Notifications') ?> - <?= $this->Paginator->counter(['format' => '{{count}}']) ?></h3>

    <div style="overflow-x: auto;">
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th scope="col"><?= __('Content') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('dateTime', __('Date')) ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                <tr>
                    <td><?= $notification->id ?></td>
                    <td><?= h($notification->content) ?></td>
                    <td><?= $this->Time->format($notification->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $notification->dateTime, $authUser['timeZone']); ?></td>
                    <td class="actions">
                        <!--
                            Les actions ci-dessous doivent-être effectuée en AJAX (`/notifications/<action>`) :
                            * Mark as read
                            * Mark as unread
                            * Delete
                        -->
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
