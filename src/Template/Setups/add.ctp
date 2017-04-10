<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="row">
    <nav class="column column-25" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('List Setups'), ['action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
            <li><?= $this->Html->link(__('List Comments'), ['controller' => 'Comments', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New Comment'), ['controller' => 'Comments', 'action' => 'add']) ?></li>
            <li><?= $this->Html->link(__('List Resources'), ['controller' => 'Resources', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New Resource'), ['controller' => 'Resources', 'action' => 'add']) ?></li>
        </ul>
    </nav>
    <div class="column column-75">
        <?= $this->Form->create($setup) ?>
        <fieldset>
            <legend><?= __('Add Setup') ?></legend>
            <?php
                echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                echo $this->Form->control('title');
                echo $this->Form->control('description');
                echo $this->Form->control('author');
                echo $this->Form->control('counter');
                echo $this->Form->control('featured');
                echo $this->Form->control('creationDate', ['empty' => true]);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>