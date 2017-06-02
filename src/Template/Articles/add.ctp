<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>

<div class="row">
    <div class="col-sm-12">
        <h3><?= __('Add post') ?></h3>

        <?= $this->Form->create($article) ?>
        <fieldset>
            <legend><?= __('Add Article') ?></legend>
            <?php
                echo $this->Form->control('title');
                echo $this->Form->control('content');
                echo $this->Form->control('src');
                echo $this->Form->control('dateTime');
                echo $this->Form->control('user_id');
                echo $this->Form->control('categories');
                echo $this->Form->control('tags');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>

    </div>
</div>
