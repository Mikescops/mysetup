<div id="add_setup_modal" class="lity-hide">
    <?= $this->Form->create($newSetupEntity, ['type' => 'file', 'url' => ['controller' => 'Setups', 'action' => 'add']]); ?>
    <fieldset style="border:0;">

        <div class="add-form">
        <ul class="tabs">
            <li>
                <a id="basics-tab" href="#basics" class="active"><?= __('Basics') ?></a>
            </li>
            <li>
                <a id="components-tab" href="#components"><?= __('Components') ?></a>
            </li>
            <li>
                <a id="infos-tab" href="#infos"><?= __('More infos') ?></a>
            </li>
        </ul>
        <div id="basics" class="form-action show">

            <?php
                echo $this->Form->control('title', ['label' => __('Title *'), 'id' => 'title', 'pattern' => '.{4,48}', 'maxlength' => 48, 'required' => 'true']);
                echo $this->Form->control('description', ['label' => __('Description'), 'id' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxlength' => 5000, 'placeholder' => __('What is the purpose of your setup ? Tell us your setup\'s story...')]);
            ?>
            <span class="float-right link-marksupp"><a target="_blank" href="<?=$this->Url->build('/pages/q&a#q-6')?>"><i class="fa fa-info-circle"></i> <?= __('Markdown supported') ?></a></span>
            <br>
            <?php
                echo $this->Form->control('featuredImage', ['type' => 'file', 'label' => ['class' => 'label_fimage label_fimage_add', 'text' => __('Click to add a featured image *')], 'class' => 'inputfile', 'required' => 'true']);
            ?>
            <img id="featuredimage_preview">
            <div class="hidden_five_inputs">
                <?php
                    echo $this->Form->control('gallery0', ['id' => 'gallery0add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    echo $this->Form->control('gallery1', ['id' => 'gallery1add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    echo $this->Form->control('gallery2', ['id' => 'gallery2add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    echo $this->Form->control('gallery3', ['id' => 'gallery3add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    echo $this->Form->control('gallery4', ['id' => 'gallery4add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                ?>
            </div>

            <div class="gallery-holder homide">
            <?php for($i = 0; $i < 5; $i++): ?>
                <div alt="<?= __('Gallery Preview') ?>" title="<?= __('Add gallery image') ?>" class="gallery_add_preview" id="gallery<?= $i ?>image_preview_add"><i class="fa fa-plus"></i></div>
            <?php endfor ?>
            </div>

            <span class="float-right">* <?= __('required fields') ?></span>
            <br/>

            <div class="modal-footer">
                <a href="#components" class="button next float-right"><?= __('Next step') ?></a>

                <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>
            </div>

        </div>

        <div id="components" class="form-action hide">

            <input type="text" class="liveInput add_setup" onkeyup="searchItem(this.value, 'add_setup');" placeholder="<?= __('Search for components...') ?>">
            <ul class="search_results add_setup"></ul>
            <ul class="basket_items add_setup"></ul>

            <div class="modal-footer">

                <a href="#infos" class="button next float-right"><?= __('Next step') ?></a>
                <a href="#basics" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>

            </div>

        </div>

        <div id="infos" class="form-action hide">

            <?php
                echo $this->Form->control('video', ['label' => __('Video (Youtube, Dailymotion, Twitch, ...)')]);

                // A hidden entry to gather the item resources
                echo $this->Form->control('resources', ['class' => 'hiddenInput add_setup', 'type' => 'hidden']);
            ?>
            <a class="is_author"><i class="fa fa-square-o"></i> <?= __("It's not my setup !") ?></a>
            <label for="author" class="setup_author"><?= __("Setup's owner") ?></label>
            <?php
                echo $this->Form->control('author', ['class' => 'setup_author', 'label' => false]);

                echo $this->Form->select('status', $status, ['id' => 'status-add', 'class' => 'hidden']);
            ?>

            <div class="modal-footer">

                <?= $this->Form->submit(__('Publish'), ['class' => 'float-right button', 'id' => 'publish-add']); ?>
                <a href="#components" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>

            </div>

        </div>

        </div>

    </fieldset>

    <?= $this->Form->end(); ?>

</div>