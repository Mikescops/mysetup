<?php

$this->layout = 'default';
$this->assign('title', $this->Paginator->counter('({{count}}) '). __('Notifications') . ' | mySetup.co');

echo $this->Html->meta('description', __('Your notifications on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Notifications | mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'Your notifications on mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'Your notifications on mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/notifications', true)], null, ['block' => true]);

?>

<div class="colored-container"></div>

<div class="container">

    <div class="maincontainer">
        <div class="notifications-container">
            <h4><i class="fas fa-stream"></i> <?= $this->Paginator->counter('{{count}}') ?> <?= __n('Notification', 'Notifications', $this->Paginator->counter('{{count}}')) ?></h4>
            <?php if (count($notifications) > 0) : foreach ($notifications as $notification) : ?>
                <div class="notif notifnb-<?= $notification->id ?> <?= ($notification->new == 1) ? 'unread' : '' ?>">
                    <?= $notification->content ?>
                    <div class="notif-close">
                        <span class="notif-read">
                            <?php if ($notification->new == 1) : ?>
                                <span alt="<?= __('Mark as read') ?>" onclick="markNotificationAsRead(<?= $notification->id ?>)"><i class="fa fa-eye-slash"></i></span>
                            <?php else : ?>
                                <span alt="<?= __('Mark as unread') ?>" onclick="markNotificationAsUnread(<?= $notification->id ?>)"><i class="fa fa-eye"></i></span>
                            <?php endif; ?>
                        </span>
                        <span alt="<?= __('Delete notification') ?>" onclick="deleteNotification(<?= $notification->id ?>)"><i class="far fa-trash-alt"></i></span>
                    </div>
                </div>
            <?php endforeach; ?>
                <ul class="pagination">
                    <?= $this->Paginator->first('<< ' . __('first')) ?>
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers(['first' => 1, 'last' => 1, 'modulus' => 3]) ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                    <?= $this->Paginator->last(__('last') . ' >>') ?>
                </ul>
            <?php else : ?>
                <p><?= __('No new notification') ?></p>
            <?php endif; ?>
        </div>

    </div>
</div>