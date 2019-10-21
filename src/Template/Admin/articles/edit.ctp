<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Edit ') . h($article->title) . ' | myAdmin');
?>

<div class="col-12 col-md-9 col-xl-10" style="max-width: 800px">
    <h3><?= __('Edit post') ?></h3>

    <?= $this->Form->create($article, ['type' => 'file', 'url' => ['controller' => 'Articles', 'action' => 'edit', $article->id]]) ?>

    <fieldset>
        <?php
            echo '<div class="form-group">' . $this->Form->control('title', ['class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->control('content', ['id' => 'editor', 'class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->control('picture', ['type' => 'file', 'class' => 'form-control inputfile', 'required' => 'false']) . '</div>';
            echo '<div class="form-group">' . $this->Form->select('category', $categories, ['class' => 'form-control']) . '</div>';
            echo '<div class="form-group">' . $this->Form->control('tags', ['class' => 'form-control']) . '</div>';
        ?>
    </fieldset>
    <?= $this->Form->button(__('Edit'), ['class' => 'btn btn-primary']) ?>

    <?= $this->Form->end() ?>

    <script src="https://cdn.ckeditor.com/ckeditor5/12.4.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .then( editor => {
                console.log( editor );
            } )
            .catch( error => {
                console.error( error );
            } );
    </script>

</div>
