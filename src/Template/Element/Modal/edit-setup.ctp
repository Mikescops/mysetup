<div id="edit_setup_modal" class="lity-hide">

    <?= $this->Form->create(null, ['type' => 'file', 'url' => ['controller' => 'Setups', 'action' => 'edit', $setup->id]]); ?>
    <fieldset style="border:0;">

        <div class="edit-form">
            <ul class="tabs-edit">
                <li>
                    <a id="basics-edit-tab" href="#basics-edit" class="active-edit"><?= __('Basics') ?></a>
                </li>
                <li>
                    <a id="components-edit-tab" href="#components-edit"><?= __('Components') ?></a>
                </li>
                <li>
                    <a id="infos-edit-tab" href="#infos-edit"><?= __('More infos') ?></a>
                </li>
            </ul>

            <div id="basics-edit" class="form-action-edit show-edit">

                <?php
                echo $this->Form->control('title', ['label' => __('Title'), 'id' => 'title', 'maxlength' => 48, 'default' => $setup->title, 'required' => 'true']);
                echo $this->Form->control('description', ['label' => __('Description'), 'id' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxlength' => 5000, 'default' => $setup->description]);
                ?>
                <span class="float-right link-marksupp"><a target="_blank" href="<?=$this->Url->build('/pages/q&a#q-6')?>"><i class="fa fa-info-circle"></i> <?= __('Markdown supported') ?></a></span>
                <br />
                <i class="fa fa-camera"></i> <?= __('We only accept images lighter than 5 MB !') ?>
                <div class="hidden">
                    <?php
                    echo $this->Form->control('featuredImage', ['type' => 'file', 'id' => 'featuredImage_edit', 'label' => ['class' => 'label_fimage label_fimage_edit', 'text' => __('Change featured image')], 'class' => 'inputfile']);
                    ?>
                </div>
                <img alt="<?= __('Featured Preview') ?>" id="featuredimage_preview_edit" src="<?= $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg')) ?>">
                <div class="hidden_five_inputs">
                    <?php
                    echo $this->Form->control('gallery0', ['id' => 'gallery0', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    echo $this->Form->control('gallery1', ['id' => 'gallery1', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    echo $this->Form->control('gallery2', ['id' => 'gallery2', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    echo $this->Form->control('gallery3', ['id' => 'gallery3', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    echo $this->Form->control('gallery4', ['id' => 'gallery4', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                    ?>
                </div>

                <div class="gallery-holder">
                    <?php $i = 0;foreach ($setup['resources']['gallery_images'] as $image):?>
                    <div alt="<?= __('Gallery Preview') ?>" title="<?= __('Change gallery image') ?>" class="gallery_edit_preview" id="gallery<?= $i ?>image_preview_edit" style="background-image:url(<?= $this->Url->build('/'.$image->src)?>)"></div>
                    <?php $i++; endforeach; for(;$i < 5;$i++): ?>
                    <div class="gallery_edit_preview" id="gallery<?= $i ?>image_preview_edit"><i class="fa fa-plus"></i></div>
                <?php endfor ?>
                </div>

                <div class="modal-footer">
                    <a href="#components-edit" class="button next float-right"><?= __('Next step') ?></a>
                    <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft (the setup will not be visible)') ?>" onclick="saveasdraftedit()"></a>
                </div>
            </div>

            <div id="components-edit" class="form-action-edit hide-edit">

                <input type="text" class="liveInput edit_setup" onkeyup="searchItem(this.value, 'edit_setup');" placeholder="<?= __('Search for components...') ?>">

                <?php if($authUser['admin']): ?>

                    <a href="#edit_setup_manual_modal" data-lity><?= __('Add a product manually') ?></a>

                <?php endif ?>

                <ul class="search_results edit_setup"></ul>
                <ul class="basket_items edit_setup">
                    <?php foreach ($setup['resources']['products'] as $item): ?>

                        <li>
                            <a onclick="deleteFromBasket('<?= h($item->title) ?>',this,'edit_setup')">
                                <img src="<?= urldecode($item->src) ?>">
                                <p><?= h(urldecode($item->title)) ?></p>
                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            </a>
                        </li>

                    <?php endforeach ?>
                </ul>

                <div class="modal-footer">

                    <a href="#infos-edit" class="button next float-right"><?= __('Next step') ?></a>
                    <a href="#basics-edit" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                    <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft (the setup will not be visible)') ?>" onclick="saveasdraftedit()"></a>

                </div>

            </div>

            <div id="infos-edit" class="form-action-edit hide-edit">

                <?php
                /* Fill the video source if exist */
                if(!empty($setup['resources']['video_link'])){$video_field = $setup['resources']['video_link'];}else{$video_field = '';}
                echo $this->Form->control('video', ['label' => __('Video (Youtube, Dailymotion, Twitch, ...)'), 'default' => $video_field]);

                /* Fill the current items in the field before edit */
                $item_field = '';
                foreach ($setup['resources']['products'] as $item){
                    $item_field = $item_field.$item->title.';'.$item->href.';'.$item->src.',';
                }
                // A hidden entry to gather the item resources
                echo $this->Form->control('resources', ['class' => 'hiddenInput edit_setup', 'type' => 'hidden', 'default' => $item_field]);
                ?>
                <a class="is_author"><i class="fa fa-square-o"></i> <?= __("It's not my setup !") ?></a>
                <label for="author" class="setup_author"><?= __("Setup's owner") ?></label>
                <?php
                echo $this->Form->control('author', ['class' => 'setup_author', 'label' => '', 'default' => $setup->author]);
                ?>
                <?php
                if($authUser['admin'])
                {
                    echo $this->Form->control('featured', ['type' => 'checkbox', 'label' => ['text' => __('Feature this setup !'), 'class' => 'checkbox'], 'default' => $setup->featured, 'hiddenField' => true]);
                }
                ?>

                <?php echo $this->Form->select('status', $status, ['default' => $setup->status, 'id' => 'status-edit']); ?>

                <div class="modal-footer">

                    <?= $this->Form->submit(__('Publish'), ['class' => 'float-right button', 'id' => 'publish-edit']); ?>
                    <?= $this->Form->end(); ?>
                    <a href="#components-edit" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                    <?= $this->Form->postLink('<i></i>', ['controller' => 'Setups', 'action' => 'delete', $setup->id], ['confirm' => __('You are going to delete this setup ! Are you sure ?'), 'escape' => false, 'class' => 'button delete float-left fa fa-trash-o']) ?>
                    <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft (the setup will not be visible)') ?>" onclick="saveasdraftedit()"></a>

                </div>
            </div>

        </div>
    </fieldset>
</div>

<?php if($authUser['admin']): ?>

    <div id="edit_setup_manual_modal" class="lity-hide">

        <span><?= __('Add a product manually') ?></span>
        <div id="manual-product-edit">
            <input type="text" name="manual-title" placeholder="<?= __('Product Title') ?>">
            <input type="text" name="manual-href" placeholder="Href">
            <input type="text" name="manual-src" placeholder="Src">
            <a class="button" onclick="manualAddToBasket()"><?= __('Add') ?></a>
        </div>

        <script type="text/javascript">
            function manualAddToBasket() {
                var encodedTitle = encodeURIComponent($('#manual-product-edit input[name="manual-title"]').val());
                var encodedHref = encodeURIComponent($('#manual-product-edit input[name="manual-href"]').val());
                var encodedSrc = encodeURIComponent($('#manual-product-edit input[name="manual-src"]').val());
                addToBasket(encodedTitle, encodedHref, encodedSrc, 'edit_setup');
            }
        </script>

    </div>

<?php endif ?>