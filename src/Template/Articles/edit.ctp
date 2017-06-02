<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>

<div class="row justify-content-md-center">
    <div class="col-12 col-md-auto">
        <h3><?= __('Edit post') ?></h3>

        <?= $this->Form->create($article) ?>

        <fieldset>            
            <?php
                echo '<div class="form-group">' . $this->Form->control('title', ['class' => 'form-control']) . '</div>';
                echo '<div class="form-group">' . $this->Form->control('content', ['class' => 'form-control']) . '</div>';
                echo '<div class="form-group">' . $this->Form->control('src', ['class' => 'form-control']) . '</div>';
                echo '<div class="form-group">' . $this->Form->control('dateTime', ['class' => 'form-control']) . '</div>';
                echo '<div class="form-group">' . $this->Form->control('categories', ['class' => 'form-control']) . '</div>';
                echo '<div class="form-group">' . $this->Form->control('tags', ['class' => 'form-control']) . '</div>';
            ?>
        </fieldset>
        <?= $this->Form->button(__('Edit'), ['class' => 'btn btn-primary']) ?>

        <?= $this->Form->end() ?>

    </div>
</div>
