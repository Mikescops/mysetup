<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
?>

<div class="col-12 col-md-auto">
    <h3><?= __('Edit post') ?></h3>

    <?= $this->Form->create($article, ['type' => 'file']) ?>

    <fieldset>            
        <?php
            echo '<div class="form-group">' . $this->Form->control('title', ['class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->control('content', ['class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->input('picture', ['type' => 'file', 'class' => 'form-control inputfile', 'required' => 'false']) . '</div>';
            echo '<div class="form-group">' . $this->Form->select('category', $categories, ['class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->control('tags', ['class' => 'form-control']) . '</div>';
        ?>
    </fieldset>
    <?= $this->Form->button(__('Edit'), ['class' => 'btn btn-primary']) ?>

    <?= $this->Form->end() ?>

</div>
