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
                <div class="slim slim-round" data-download="true" data-ratio="1080:500" data-size="1080,500">
                    <img alt="<?= __('Featured Preview') ?>" src="<?= $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg')) ?>">
                    <?php
                    echo $this->Form->control('featuredImage', ['name' => 'featuredImage[]', 'hidden','type' => 'file', 'label' => '']);
                    ?>
                </div>

                <div class="gallery-holder">
                    <?php $i = 0;foreach ($setup['resources']['gallery_images'] as $image):?>
                        <div class="slim slim-round" data-download="true" data-save-initial-image="true" data-ratio="16:9" data-size="1366,768">
                            <img alt="<?= __('Gallery Preview') ?>" title="<?= __('Change gallery image') ?>" src="<?= $this->Url->build('/'.$image->src)?>">
                            <?php echo $this->Form->control('gallery'.$i, ['name' => 'gallery'.$i.'[]', 'hidden', 'type' => 'file', 'label' => '']); ?>
                        </div>
                    <?php $i++; endforeach; for($i; $i < 5; $i++):?>
                        <div class="slim slim-round" data-download="true" data-ratio="16:9" data-size="1366,768">
                            <?php echo $this->Form->control('gallery'.$i, ['name' => 'gallery'.$i.'[]', 'hidden', 'type' => 'file', 'label' => '']); ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="modal-footer">
                    <a href="#components-edit" class="button next float-right"><?= __('Next step') ?></a>
                    <a class="button draft float-left fa fa-file-alt fa-lg" title="<?= __('Save as draft (the setup will not be visible)') ?>" onclick="saveasdraftedit()"></a>
                </div>
            </div>

            <div id="components-edit" class="form-action-edit hide-edit">

                <input type="text" class="liveInput edit_setup" onkeyup="searchItem(this.value, 'edit_setup');" placeholder="<?= __('Search for components...') ?>">

                <?php if($authUser['admin']): ?>

                    <a href="#edit_setup_manual_modal" data-lity><?= __('Add a product manually') ?></a>

                <?php endif ?>

                <ul class="draggable-cards dragscroll search_results edit_setup"></ul>

                <h5 class="basket-title"><?= __('Setup items') ?></h5>
                <ul class="draggable-cards dragscroll basket_items edit_setup">
                    <?php foreach ($setup['resources']['products'] as $item): ?>
                        <li class="text-card">
                            <div class="wrapper">
                                <div class="card-container">
                                    <div class="top" style="background: url(<?= urldecode($item->src) ?>) no-repeat center center; background-size: contain"></div> 
                                    <a onclick="deleteFromBasket('<?= h($item->title) ?>',this,'edit_setup')" class="bottom"><i class="far fa-trash-alt"></i></a> 
                                </div>
                                <div class="inside">
                                    <div class="icon"><i class="fas fa-info-circle"></i></div>
                                    <div class="contents"><?= h(urldecode($item->title)) ?></div>
                                </div>
                            </div>
                        </li>

                    <?php endforeach ?>
                </ul>

                <div class="modal-footer">

                    <a href="#infos-edit" class="button next float-right"><?= __('Next step') ?></a>
                    <a href="#basics-edit" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                    <a class="button draft float-left fa fa-file-alt fa-lg" title="<?= __('Save as draft (the setup will not be visible)') ?>" onclick="saveasdraftedit()"></a>

                </div>

            </div>

            <div id="infos-edit" class="form-action-edit hide-edit">

                <?php
                /* Fill the video source if exist */
                if(!empty($setup['resources']['video_link'])){$video_field = $setup['resources']['video_link'];}else{$video_field = '';}
                echo $this->Form->control('video', ['class' => 'video-url-input', 'label' => __('Video (Youtube, Dailymotion, Twitch, ...)'), 'placeholder' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'default' => $video_field]);

                /* Fill the current items in the field before edit */
                $item_field = '';
                foreach ($setup['resources']['products'] as $item){
                    $item_field = $item_field.$item->title.';'.$item->href.';'.$item->src.',';
                }
                // A hidden entry to gather the item resources
                echo $this->Form->control('resources', ['class' => 'hiddenInput edit_setup', 'type' => 'hidden', 'default' => $item_field]);
                ?>
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
                    <?= $this->Form->postLink('<i></i>', ['controller' => 'Setups', 'action' => 'delete', $setup->id], ['confirm' => __('You are going to delete this setup ! Are you sure ?'), 'escape' => false, 'class' => 'button delete float-left far fa-trash-alt fa-lg']) ?>
                    <a class="button draft float-left fa fa-file-alt fa-lg" title="<?= __('Save as draft (the setup will not be visible)') ?>" onclick="saveasdraftedit()"></a>

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