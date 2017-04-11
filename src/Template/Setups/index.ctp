<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="maincontainer">

            <div class="row">
                <div class="column column-75">

                    <h3>Latest setups</h3>

                    <div class="fullitem">
                        <a href="#">
                            <img src="https://i.ytimg.com/vi/4kBLJK4FdfQ/maxresdefault.jpg">
                        </a>
                        <div class="fullitem-inner">

                            <div class="row">

                                <div class="column column-75">
                                    <a class="featured-user" href="#">
                                        <img src="https://avatars1.githubusercontent.com/u/4266283?v=3&s=460">
                                    </a>

                                    <a href="post.html"><h3>Ma config perso #1</h3></a>

                                </div>

                                <div class="column column-25"></div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="column column-25 sidebar">

                        <ul class="side-nav">
                            <li class="heading"><?= __('Actions') ?></li>
                            <li><?= $this->Html->link(__('New Setup'), ['action' => 'add']) ?></li>
                            <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
                            <li><?= $this->Html->link(__('List Comments'), ['controller' => 'Comments', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New Comment'), ['controller' => 'Comments', 'action' => 'add']) ?></li>
                            <li><?= $this->Html->link(__('List Resources'), ['controller' => 'Resources', 'action' => 'index']) ?></li>
                            <li><?= $this->Html->link(__('New Resource'), ['controller' => 'Resources', 'action' => 'add']) ?></li>
                        </ul>

                    <h4>Nos réseaux sociaux</h4>

                    <div class="social-networks">
                        <a href="#" class="button button-clear"><i class="fa fa-facebook fa-2x"></i></a>
                        <a href="#" class="button button-clear"><i class="fa fa-twitter fa-2x"></i></a>
                        <a href="#" class="button button-clear"><i class="fa fa-youtube fa-2x"></i></a>
                    </div>

                </div>
            </div>

        </div>

<div class="setups index large-9 medium-8 columns content">
    <h3><?= __('Setups') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('author') ?></th>
                <th scope="col"><?= $this->Paginator->sort('counter') ?></th>
                <th scope="col"><?= $this->Paginator->sort('featured') ?></th>
                <th scope="col"><?= $this->Paginator->sort('creationDate') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($setups as $setup): ?>
            <tr>
                <td><?= $this->Number->format($setup->id) ?></td>
                <td><?= $setup->has('user') ? $this->Html->link($setup->user->name, ['controller' => 'Users', 'action' => 'view', $setup->user->id]) : '' ?></td>
                <td><?= h($setup->title) ?></td>
                <td><?= h($setup->author) ?></td>
                <td><?= $this->Number->format($setup->counter) ?></td>
                <td><?= h($setup->featured) ?></td>
                <td><?= h($setup->creationDate) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $setup->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $setup->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $setup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setup->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
