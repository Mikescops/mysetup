<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>

<div class="col-12 col-md-auto" style="width: 800px;">
    <h3><?= __('Add post') ?></h3>

    <?= $this->Form->create($article, ['type' => 'file']) ?>

    <fieldset>
        <?php
            echo '<div class="form-group">' . $this->Form->control('title', ['class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->control('content', ['class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->control('picture', ['type' => 'file', 'class' => 'form-control inputfile', 'required' => 'true']) . '</div>';
            echo '<div class="form-group">' . $this->Form->select('category', $categories, ['class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->control('tags', ['class' => 'form-control']) . '</div>';
        ?>
    </fieldset>
    <?= $this->Form->submit(__('Submit'), ['class' => 'btn btn-primary']) ?>

    <?= $this->Form->end() ?>

</div>

