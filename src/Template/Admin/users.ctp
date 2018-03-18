<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Users | myAdmin'));
?>

<div class="col-12 col-md-9 col-xl-10">
    <h3><?= __('Users') ?> - <?= $this->Paginator->counter(['format' => '{{count}}']) ?></h3>

    <div style="overflow-x: auto;">

        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('name', __('Name')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('mail', __('Email')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('preferredStore', __('Store')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('timeZone', __('Timezone')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('verified') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('mailVerification', __('Email')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('creationDate', __('Created on')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('lastLogginDate', __('Last Login')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('twitchToken', 'Twitch') ?></th>
                    <th scope="col"><?= __('Social') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= h($user->name) ?></td>
                    <td><?= h($user->mail) ?></td>
                    <td><?= h($user->preferredStore) ?></td>
                    <td><?= h($user->timeZone) ?></td>
                    <td>
                    <?php
                        if($user->verified === 125 || $user->mail === 'admin@admin.admin')
                        {
                            echo __('Admin');
                        }
                        elseif($user->verified === 0)
                        {
                            echo __('No');
                        }
                        else
                        {
                            echo __('Yes');
                        }
                    ?>
                    </td>
                    <td><?= ($user->mailVerification ? __('No') : __('Yes')) ?></td>
                    <td><?= $this->Time->format($user->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->creationDate, $authUser['timeZone']); ?></td>
                    <td><?= $this->Time->format($user->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->lastLogginDate, $authUser['timeZone']); ?></td>
                    <td><?= ($user->twitchToken ? __('Yes') : __('No')) ?></td>
                    <td>
                        <?php if($user->uwebsite or $user->ufacebook or $user->utwitter or $user->utwitch): ?>
                        <div class="btn-group">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="info" width="20px" height="20px"></i>
                            </button>
                            <div class="dropdown-menu">
                                <?php if($user->uwebsite): echo '<a class="dropdown-item" href="' . h($user->uwebsite) . '" title="' . h($user->uwebsite) . '" target="_blank">Website</a>'; endif; ?>
                                <?php if($user->ufacebook): echo '<a class="dropdown-item" href="' . h($user->ufacebook) . '" title="' . h($user->ufacebook) . '" target="_blank">Facebook</a>'; endif; ?>
                                <?php if($user->utwitter): echo '<a class="dropdown-item" href="' . h($user->utwitter) . '" title="' . h($user->utwitter) . '" target="_blank">Twitter</a>'; endif; ?>
                                <?php if($user->utwitch): echo '<a class="dropdown-item" href="' . h($user->utwitch) . '" title="' . h($user->utwitch) . '" target="_blank">Twitch</a>'; endif; ?>
                            </div>
                        <?php endif; ?>

                    <td class="actions">
                        <?= $this->Html->link('<i data-feather="eye"></i>', ['controller' => 'Users', 'action' => 'view', $user->id], ['title' => __('View'), 'escape' => false]) ?>
                        <?= $this->Form->postLink('<i data-feather="trash-2"></i>', ['controller' => 'Users', 'action' => 'delete', $user->id], ['title' => __('Delete'), 'confirm' => __('Are you sure you want to delete this user ?'), 'escape' => false]) ?>
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
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
