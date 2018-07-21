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
            <i class="fa fa-camera"></i> <?= __('We only accept images lighter than 5 MB !') ?>
            

            <div class="slim slim-round" data-did-save="featuredPreviewChange" data-ratio="1080:500" data-size="1080,500">
                <?php
                    echo $this->Form->control('featuredImage', ['name' => 'featuredImage[]', 'type' => 'file', 'class' => 'input_hidden', 'label' => 'Featured Image', 'required' => 'true']);
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

            <span class="float-right">* <?= __('required fields') ?></span>
            <br/>

            <div class="modal-footer">
                <a href="#components" class="button next float-right"><?= __('Next step') ?></a>

                <a class="button draft float-left fa fa-file-alt fa-lg" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>
            </div>

        </div>

        <div id="components" class="form-action hide">

            <input type="text" class="liveInput add_setup" onkeyup="searchItem(this.value, 'add_setup');" placeholder="<?= __('Search for components...') ?> *">
            <ul class="search_results add_setup"></ul>
            <ul class="basket_items add_setup"></ul>

            <div class="modal-footer">

                <a href="#infos" class="button next float-right"><?= __('Next step') ?></a>
                <a href="#basics" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                <a class="button draft float-left fa fa-file-alt fa-lg" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>

            </div>

        </div>

        <div id="infos" class="form-action hide">

            <?php
                echo $this->Form->control('video', ['label' => __('Video (Youtube, Dailymotion, Twitch, ...)'), 'placeholder' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
            ?>
            <a class="is_author"><i class="far fa-square"></i> <?= __("It's not my setup !") ?></a>
            <label for="author" class="setup_author"><?= __("Setup's owner") ?></label>
            <?php
                echo $this->Form->control('author', ['class' => 'setup_author', 'label' => false]);
            ?>

            <div class="hidden">
                <?php
                    // A hidden entry to gather the item resources
                    echo $this->Form->control('resources', ['class' => 'hiddenInput add_setup', 'required' => 'true', 'label' => __('Components')]);

                    echo $this->Form->select('status', $status, ['id' => 'status-add', 'class' => 'hidden']);
                ?>
            </div>

            <div class="modal-footer">

                <?= $this->Form->submit(__('Publish'), ['class' => 'float-right button', 'id' => 'publish-add']); ?>
                <a href="#components" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                <a class="button draft float-left fa fa-file-alt fa-lg" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>

            </div>

        </div>

        </div>

    </fieldset>

    <?= $this->Form->end(); ?>

</div>