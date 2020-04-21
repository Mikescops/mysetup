<?php

/**
 * @var \App\View\AppView $this
 */

$this->assign('title', __('Add Setup') . ' | mySetup.co');

?>

<div class="colored-container">
</div>

<div class="container">
    <div class="maincontainer">

        <?= $this->Form->create($newSetupEntity, ['type' => 'file', 'class' => 'form_add_setup', 'url' => ['controller' => 'Setups', 'action' => 'add']]); ?>

        <div class="form-section">

            <?php
            echo $this->Form->control('title', ['label' => __('Title *'), 'id' => 'title', 'pattern' => '.{4,48}', 'maxlength' => 48, 'required' => 'true', 'placeholder' => __('My awesome setup')]);
            echo $this->Form->control('description', ['label' => __('Description'), 'id' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxlength' => 5000, 'placeholder' => __('What is the purpose of your setup ? Tell us your setup\'s story...')]);
            ?>
            <span class="float-right link-marksupp"><a target="_blank" href="<?= $this->Url->build('/pages/q&a#q-6') ?>"><i class="fa fa-info-circle"></i> <?= __('Markdown supported') ?></a></span>
            <br>
            <i class="fa fa-camera"></i> <?= __('We only accept images lighter than 5 MB !') ?>


            <div class="slim slim-round" data-did-save="featuredPreviewChange" data-ratio="1080:500" data-size="1080,500">
                <?php
                echo $this->Form->control('featuredImage', ['name' => 'featuredImage[]', 'type' => 'file', 'class' => 'input_hidden', 'label' => __('Featured image'), 'required' => 'true']);
                ?>
            </div>
            <div class="gallery-holder homide">
                <div class="slim slim-round" data-ratio="16:9" data-size="1366,768">
                    <?php echo $this->Form->control('gallery0', ['name' => 'gallery0[]', 'type' => 'file', 'class' => 'input_hidden', 'label' => '']); ?>
                </div>
                <div class="slim slim-round" data-ratio="16:9" data-size="1366,768">
                    <?php echo $this->Form->control('gallery1', ['name' => 'gallery1[]', 'type' => 'file', 'class' => 'input_hidden', 'label' => '']); ?>
                </div>
                <div class="slim slim-round" data-ratio="16:9" data-size="1366,768">
                    <?php echo $this->Form->control('gallery2', ['name' => 'gallery2[]', 'type' => 'file', 'class' => 'input_hidden', 'label' => '']); ?>
                </div>
                <div class="slim slim-round" data-ratio="16:9" data-size="1366,768">
                    <?php echo $this->Form->control('gallery3', ['name' => 'gallery3[]', 'type' => 'file', 'class' => 'input_hidden', 'label' => '']); ?>
                </div>
                <div class="slim slim-round" data-ratio="16:9" data-size="1366,768">
                    <?php echo $this->Form->control('gallery4', ['name' => 'gallery4[]', 'type' => 'file', 'class' => 'input_hidden', 'label' => '']); ?>
                </div>
            </div>

        </div>

        <div class="form-section">

            <h3><?= __('Components') ?> *</h3>

            <label><i class="fa fa-search"></i> <?= __('Search for components') ?></label>
            <input type="text" class="liveInput add_setup" onkeyup="searchItem(this.value, 'add_setup');" placeholder="Acer, Razer, Logitech...">

            <ul class="draggable-cards dragscroll search_results add_setup"></ul>

            <label class="basket-title"><i class="fas fa-box-open"></i> <?= __('Setup items') ?></label>
            <ul class="draggable-cards dragscroll basket_items add_setup"></ul>

        </div>

        <div class="hidden">
            <?php
            // A hidden entry to gather the item resources
            echo $this->Form->control('resources', ['class' => 'hiddenInput add_setup', 'required' => 'true', 'label' => __('Components')]);

            echo $this->Form->select('status', $status, ['id' => 'status-add', 'class' => 'hidden']);
            ?>
        </div>

        <span class="float-right">* <?= __('required fields') ?></span>

        <br>

        <?= $this->Form->submit(__('Publish'), ['class' => 'float-right button', 'id' => 'publish-add']); ?>
        <a class="button draft float-left fa fa-file-alt fa-lg" title="<?= __('Save as draft') ?>" onclick="event.preventDefault();fillProductForm('add_setup');saveasdraftadd()"></a>

        </fieldset>

        <?= $this->Form->end(); ?>


        <?= $this->Html->scriptBlock("
        var sortable = new Sortable($('.basket_items')[0], {
            onEnd: function(evt) {
                basket.splice(evt.newIndex, 0, basket.splice(evt.oldIndex, 1)[0]);
            }
        });
        ", array('block' => 'scriptBottom')) ?>

    </div>

    <div class="before-footer">
        <div class="container">
        </div>
    </div>

</div>