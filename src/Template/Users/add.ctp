<?php
/**
  * @var \App\View\AppView $this
  */
?>

<div class="row">
    <nav class="column column-25" id="actions-sidebar">
        <ul class="side-nav">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('List Resources'), ['controller' => 'Resources', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New Resource'), ['controller' => 'Resources', 'action' => 'add']) ?></li>
            <li><?= $this->Html->link(__('List Comments'), ['controller' => 'Comments', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New Comment'), ['controller' => 'Comments', 'action' => 'add']) ?></li>
            <li><?= $this->Html->link(__('List Setups'), ['controller' => 'Setups', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New Setup'), ['controller' => 'Setups', 'action' => 'add']) ?></li>
        </ul>
    </nav>
    <div class="column column-75">
        <?= $this->Form->create($user) ?>
        <fieldset>
            <legend><?= __('Add User') ?></legend>
            <?php
                echo $this->Form->control('name');
                echo $this->Form->control('mail');
                echo $this->Form->control('password');
                echo $this->Form->control('resource_id', ['options' => $resources, 'empty' => true]);
                echo $this->Form->control('verified');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>