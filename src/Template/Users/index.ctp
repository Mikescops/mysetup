<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>

<div class="row">
    <div class="col-sm-12">
        <h3><?= __('Users') ?> - <?= $this->Paginator->counter(['format' => '{{count}}']) ?></h3>
        <table class="table table-striped table-responsive" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('name', __('Name')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('mail', __('Email')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('preferredStore', __('Store')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('timeZone', __('Timezone')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('verified', __('Status')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('mailVerification', __('Verified')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('creationDate', __('Created on')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('lastLogginDate', __('Last Login')) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('twitchToken', 'Twitch') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('uwebsite', 'Website') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('ufacebook', 'Facebook') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('utwitter', 'Twitter') ?></th>
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
                    <td><?= h($user->verified) ?></td>
                    <td><?= ($user->mailVerification ? __('No') : __('Yes')) ?></td>
                    <td><?= $this->Time->format($user->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->creationDate, $authUser['timeZone']); ?></td>
                    <td><?= $this->Time->format($user->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->lastLogginDate, $authUser['timeZone']); ?></td>
                    <td><?= ($user->twitchToken ? __('Yes') : __('No')) ?></td>
                    <td><?php if($user->uwebsite): echo '<i class="fa fa-globe"> <a href="$user->uwebsite" target="_blank">Website</a></i>'; endif; ?></td>
                    <td><?php if($user->ufacebook): echo '<i class="fa fa-facebook"> <a href="$user->ufacebook" target="_blank">Facebook</a></i>'; endif; ?></td>
                    <td><?php if($user->utwitter): echo '<i class="fa fa-twitter"> <a href="$user->utwitter" target="_blank">Twitter</a></i>'; endif; ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete this user ?')]) ?>
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
