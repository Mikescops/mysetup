<?php
/**
  * @var \App\View\AppView $this
  */

function getplaintextintrofromhtml($html) {
    // Remove the HTML tags
    $html = strip_tags($html);
    // Convert HTML entities to single characters
    $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
    $html = str_replace("\n", " ", $html);
    return $html;
}

$this->assign('title', $setup->title.' | mySetup.co');

echo $this->Html->meta('description', $this->Text->truncate(getplaintextintrofromhtml($this->Markdown->transform($setup->description)),150,['ellipsis' => '..','exact' => true]), ['block' => true]);

echo $this->Html->meta(array('name' => 'canonical', 'content' => 'summary_large_image'), null, ['block' => true]);

echo $this->Html->meta(array('rel' => 'canonical', 'href' => $this->Url->build("/setups/".$setup->id."-".$this->Text->slug($setup->title), true)), null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:title', 'content' => $setup->title.' | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $this->Text->truncate(getplaintextintrofromhtml($this->Markdown->transform($setup->description)),150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['name' => 'twitter:description', 'content' => $this->Text->truncate(getplaintextintrofromhtml($this->Markdown->transform($setup->description)),150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg'), true)], null ,['block' => true]);
echo $this->Html->meta(['name' => 'twitter:image', 'content' => $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg'), true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build("/setups/".$setup->id."-".$this->Text->slug($setup->title), true)], null ,['block' => true]);
?>

<div class="featured-container">
    <div class="featured-gradient" style="background-image: url('<?= $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg'), true) ?>')"></div>

    <div class="post_slider">


        <div class="slider-item">
            <div class="featured-img-setup slider-item-inner">

                <img alt="<?= $setup->title ?>" src="<?= $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg'), true) ?>">
                <?php if(!empty($setup['resources']['video_link'])): ?>
                    <div class="overlay">
                        <a class="videoplayer-icon" href="<?= $setup['resources']['video_link']?>" title="<?= __('Watch it in video') ?>" data-lity>
                            <i class="fa fa-play-circle"></i>
                        </a>
                    </div>
                <?php endif ?>

            </div>
        </div>

        <?php foreach ($setup['resources']['gallery_images'] as $image): ?>
            <div class="slider-item">
                <div class="slider-item-inner">
                    <a href="<?= $this->Url->build('/', true)?><?= $image->src ?>" data-lity data-lity-desc="Photo of Config'">
                        <img data-lazy="<?= $this->Url->build('/', true)?><?= $image->src ?>">
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<div class="featured-inner">
    <div class="container">
        <div class="row">
            <div class="column column-70">
                <a class="featured-user" href="<?= $this->Url->build('/users/'.$setup->user['id']) ?>">
                    <img alt="<?= __('Profile picture of') ?> <?= h($setup->user['name']) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user->modificationDate, 'mmss', null, null)) ?>">
                </a>
                <h3>
                    <?= h($setup->title) ?>
                    <?php if($setup->status == 'DRAFT'): ?>
                        <i title="<?= __('Only you can see this setup') ?>" class="fa fa-eye-slash setup-unpublished"></i>
                    <?php endif ?>
                    <?php if($setup->id == $setup->user->mainSetup_id): ?>
                        <i title="<?= ($authUser['id'] != $setup->user_id ? __('This is the main setup of') . ' ' . h($setup->user->name) : __('This is your main setup')) ?>" class="fa fa-certificate setup-default"></i>
                    <?php endif ?>
                    <?php if($setup->featured): ?>
                        <i title="<?= __('This setup is featured on mySetup.co !')?>" class="fa fa-star setup-star"></i>
                    <?php endif ?>
                </h3>
                <p>
                    <?= __('Shared by') ?> <?php if($setup->user['name']){echo $this->Html->link($setup->user['name'], ['controller' => 'users', 'action' => 'view', $setup->user['id']]);}else{echo "Unknown";} ?><?php if($setup->user['verified']): echo ' <i class="fa fa-check-square verified_account"></i> '; endif; if($setup->user['name'] != $setup->author and $setup->author !== ''): echo __(", created by ") . h($setup->author) ; endif?>
                </p>
            </div>
            <div class="column column-25">
                <a class="red_button float-right" <?php if(!$authUser){echo "onclick=\"toast.message('" . __('You must be logged in to like !') . "');\"";} else{ echo "onclick=\"likeSetup('". $setup->id ."')\"";}?> tabindex="0">
                    <div class="labeled_button">
                        <i class="fa fa-heart"></i> <span>Like</span>
                    </div>
                    <span class="pointing_label">
                        0
                    </span>
                </a>
                <?= $this->Html->scriptBlock('$(document).ready(function() {printLikes("' . $setup->id . '");});', array('block' => 'scriptBottom')); ?>
                <?php if($authUser): echo $this->Html->scriptBlock('$(document).ready(function() {doesLike("' . $setup->id . '");});', array('block' => 'scriptBottom'));endif; ?>

                <?php if($authUser['id'] == $setup->user_id or $authUser['admin']): ?>
                    <div class="edit_panel">
                        <a href="#edit_setup_modal" data-lity title="<?= __('Edit') ?> <?php echo ($authUser['id'] == $setup->user_id ? __("your") : __("this")) ?> setup"><i class="fa fa-wrench"></i></a>
                        <a href="#embed_twitch_modal" data-lity title="<?= __('Embed it in Twitch') ?>"><i class="fa fa-twitch"></i></a>
                        <a href="#embed_website_script" data-lity title="<?= __('Embed on your website') ?>"><i class="fa fa-code"></i></a>
                    </div>

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
                                    <span class="float-right link-marksupp"><a target="_blank" href="<?=$this->Url->build('/pages/q&a#q-6')?>"><i class="fa fa-info-circle"></i> Markdown supported</a></span>
                                    <br />
                                    <?php
                                    echo $this->Form->control('featuredImage', ['type' => 'file', 'id' => 'featuredImage_edit', 'label' => ['class' => 'label_fimage', 'text' => __('Change featured image')], 'class' => 'inputfile']);
                                    ?>
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
                                        <img alt="<?= __('Gallery Preview') ?>" title="<?= __('Change gallery image') ?>" class="gallery_edit_preview" id="gallery<?= $i ?>image_preview_edit" src="<?= $this->Url->build('/'.$image->src)?>">
                                        <?php $i++; endforeach; for(;$i < 5;$i++): ?>
                                        <img alt="<?= __('Gallery Preview') ?>" title="<?= __('Add gallery image') ?>" class="gallery_edit_preview" id="gallery<?= $i ?>image_preview_edit" src="<?= $this->Url->build('/img/add_gallery_default.png')?>">
                                    <?php endfor ?>
                                    </div>

                                    <div class="modal-footer">
                                        <a href="#components-edit" class="button next float-right"><?= __('Next step') ?></a>
                                        <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft (the setup will not be visible)') ?>" onclick="saveasdraftedit()"></a>
                                    </div>
                                </div>

                                <div id="components-edit" class="form-action-edit hide-edit">

                                    <input type="text" class="liveInput edit_setup" onkeyup="searchItem(this.value, '<?= $authUser['preferredStore'] ?>' ,'edit_setup');" placeholder="<?= __('Search for components...') ?>">

                                    <?php if($authUser['admin']): ?>

                                        <a href="#edit_setup_manual_modal" data-lity><?= __('Add a product manually') ?></a>

                                    <?php endif ?>

                                    <ul class="search_results edit_setup"></ul>
                                    <ul class="basket_items edit_setup">
                                        <?php foreach ($setup['resources']['products'] as $item): ?>

                                            <li>
                                                <a onclick="deleteFromBasket('<?= $item->title ?>',this,'edit_setup')">
                                                    <img src="<?= urldecode($item->src) ?>">
                                                    <p><?= urldecode($item->title) ?></p>
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

                                    echo $this->Form->select('status', $status, ['default' => 'PUBLISHED', 'id' => 'status-edit', 'class' => 'hidden']);
                                    ?>

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
                                function manualAddToBasket(){
                                    var title = $('#manual-product-edit input[name="manual-title"]').val();
                                    var href = $('#manual-product-edit input[name="manual-href"]').val();
                                    var src = $('#manual-product-edit input[name="manual-src"]').val();
                                    var encodedHref = encodeURIComponent(href);
                                    var encodedTitle = encodeURIComponent(title);
                                    var encodedSrc = encodeURIComponent(src);
                                    addToBasket(title, href, src, 'edit_setup');
                                }
                            </script>

                        </div>

                    <?php endif ?>

                    <div id="embed_twitch_modal" class="lity-hide">
                        <h4><?= __('How to embed your setup in Twitch ?') ?></h4>
                        <p><?= __('Go to your Twitch channel and toggle panel edition.') ?></p>
                        <?= $this->Html->image('howto_twitch.png', array('alt' => 'Twitch Panel Edition')) ?> <br>
                        <p><?= __('Copy the following url in the link field') ?> :</p>
                        <pre><code><span><?= $this->Url->build('/setups/'.$setup->id."-".$this->Text->slug($setup->title).'?ref='.urlencode($setup->user['name']), true)?></span></code></pre>
                        <p><?= __('And add your personal mySetup.co banner image !') ?></p>
                        <p style="text-align: center;"><img alt="<?= ('Advert - Setup by') ?> <?= h($setup->user['name']) ?>" src="<?= $this->Url->build('/imgeneration/twitch-promote.php?id='. $setup->user_id . '&name=' . $setup->user['name'] . '&setup=' . $setup->title)?>"></p>

                        <p><?= __('You can even configure your Twitch Chat bot to display your link or image.') ?></p>
                    </div>

                    <div id="embed_website_script" class="lity-hide">
                        <h4><?= __('How to embed the setup on my website ?') ?></h4>
                        <?= __("It's pretty easy, just add the code below to your page (and set the setup id accordingly) :") ?>

                        <div class="input text"><input readonly name="embedcode" id="embedcode" value='<script src="https://mysetup.co/api/widgets.js"></script><div id="mysetup-embed" ms-setup="<?= $setup->id ?>" ms-width="350">Setup shared by <?php if($setup->user['name']){echo $this->Html->link($setup->user['name'], ['controller' => 'users', 'action' => 'view', $setup->user['id']]);}else{echo "Unknown";} ?> at <a href="https://mysetup.co/">mySetup.co</a></div>' type="text"></div>

                        <h5>Preview :</h5>

                        <div class="display-preview">
                            
                            <script async src="https://mysetup.co/api/widgets.js"></script>
                            <div id="mysetup-embed" ms-setup="<?= $setup->id ?>" ms-width="350">Setup shared by <?php if($setup->user['name']){echo $this->Html->link($setup->user['name'], ['controller' => 'users', 'action' => 'view', $setup->user['id']]);}else{echo "Unknown";} ?> at <a href="https://mysetup.co/">mySetup.co</a></div>

                        </div>

                        <br>
                        <p><?= __('You can customize the size of your embedded setup by editing the value of ms-width.') ?></p>
                    </div>

                <?php elseif($authUser && $setup->user_id != $authUser['id']): ?>
                    <div class="edit_panel">
                            <?= $this->Form->postLink('', ['action' => 'requestOwnership', $setup->id], ['confirm' => __('This will send an ownership-request for this setup, are you really sure ?'), 'title' => __('This is my setup !'), 'class' => 'fa fa-bolt']) ?>

                            <?= $this->Form->postLink('', ['action' => 'requestReport', $setup->id], ['confirm' => __('This will send a report-request against this setup, are you really sure ?'), 'title' => __('Report this setup'), 'class' => 'fa fa-flag']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container maincontainer setupview">
    <div class="row config-post">
        <div class="column">

            <div class="config-items">

                <?php $i=0; foreach ($setup['resources']['products'] as $item): ?>

                <div id="item-trigger-<?= $i ?>" class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div>

                <div id="item-about-<?= $i ?>" style="display: none;">
                    <div class="about-inner">
                      <h5><?= urldecode($item->title) ?></h5>
                      <a href="<?=  $this->Url->build('/setups/search?q='.urldecode($item->title)); ?>" class="button brelated"><i class="fa fa-search"></i> Find related setups</a>
                      <a href="<?= urldecode($item->href) ?>" traget="_blank" class="button amazon-buy">More info on <i class="fa fa-amazon"></i></a>
                  </div>
                </div>

                <?= $this->Html->scriptBlock("new tippy('#item-trigger-$i', {zIndex: 20, html: '#item-about-$i',arrow: true,animation: 'fade',position: 'bottom', interactive: true});", array('block' => 'scriptBottom')) ?>

                <?php $i++; endforeach ?>

            </div>

        </div>

    </div>

    <div class="row content-section">

        <div class="column column-60 item-meta">

            <div class="section-header">
                <h4><?= __('About this setup') ?></h4>
            </div>

            <div class="section-inner">

                <?= preg_replace('/<a (.*)>(.*)<\/a>/', '<a rel="nofollow" $1>$2</a>', $this->Markdown->transform(h($setup->description))) ?>

                <span class="setup-date">
                    <?php if($setup->creationDate != $setup->modifiedDate): ?>
                        <i class='fa fa-clock-o'></i> <?= __('Modified on') ?> <?= $this->Time->format($setup->modifiedDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $setup->modifiedDate, $authUser['timeZone']); if(!$authUser): echo ' (GMT)'; endif; ?>
                    <?php else: ?>
                        <i class='fa fa-clock-o'></i> <?= __('Published on') ?> <?= $this->Time->format($setup->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $setup->creationDate, $authUser['timeZone']); if(!$authUser): echo ' (GMT)'; endif; ?>
                    <?php endif; ?>
                </span>

            </div>

        </div>

        <div id="comments" class="column column-40">

            <div class="section-header">
                <h4 class="comment-section-title"><?= __('Wanna share your opinion ?') ?></h4>
            </div>

            <div class="section-inner">

                <section class="comments">
                    <?php if (!empty($setup->comments)): ?>
                        <?php foreach ($setup->comments as $comments): ?>
                            <article class="comment">
                                <a class="comment-img" href="<?= $this->Url->build('/users/'.$comments->user_id)?>">
                                    <img alt="<?= __('Profile picture of') ?> #<?= $comments->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $comments->user_id . '.png?' . $this->Time->format($comments->user->modificationDate, 'mmss', null, null)) ?>" width="50" height="50" />
                                </a>

                                <div class="comment-body">
                                    <div class="text" id="comment-<?= $comments->id ?>">
                                      <p content="<?= h($comments->content) ?>"><?= h($comments->content) ?></p>
                                      <?= $this->Html->scriptBlock("$(function(){ $('#comment-".  $comments->id ." > p').html(emojione.toImage(`".$comments->content."`)); });", array('block' => 'scriptBottom')) ?>
                                    </div>
                                    <p class="attribution"><?= __('by') ?> <a href="<?= $this->Url->build('/users/'.$comments->user_id)?>"><?= h($comments->user['name']) ?></a> <?= __('at') ?> <?= $this->Time->format($comments->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $comments->dateTime, $authUser['timeZone']); if(!$authUser): echo ' (GMT)'; endif; ?></p>

                                    <?php if($authUser['id'] == $comments->user_id):
                                        echo ' - ' . $this->Form->postLink(__('Delete'), array('controller' => 'Comments','action' => 'delete', $comments->id),array('confirm' => __('Are you sure you want to delete this comment ?')));
                                        echo ' - <a class="edit-comment" source="comment-'.$comments->id.'" href="#edit-comment-hidden" data-lity> ' . __('Edit') . ' </a>';
                                    endif ?>
                                </div>
                          </article>
                      <?php endforeach; ?>
                    <?php endif; ?>
                </section>

                <?php if($authUser): ?>

                    <a class="comment-img" href="<?= $this->Url->build('/users/'.$authUser->id)?>">
                        <img alt="<?= __('Profile picture of') ?> #<?= $authUser->id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $authUser->id . '.png?' . $this->Time->format($authUser->modificationDate, 'mmss', null, null)) ?>" width="50" height="50" />
                    </a>

                    <?= $this->Form->create(null, ['url' => ['controller' => 'Comments', 'action' => 'add', $setup->id], 'id' => 'comment-form']); ?>
                    <fieldset>
                        <?php echo $this->Form->control('content', ['label' => '', 'id' => 'commentField', 'type' => 'textarea', 'placeholder' => __('Nice config\'...'), 'rows' => "1", 'maxlength' => 500]); ?>
                    </fieldset>
                    <div class="g-recaptcha"
                        data-sitekey="6LcLKx0UAAAAADiwOqPFCNOhy-UxotAtktP5AaEJ"
                        data-size="invisible"
                        data-badge="bottomleft"
                        data-callback="onSubmit">
                    </div>
                    <?= $this->Form->button(__('Post this comment')) ?>
                    <?= $this->Form->end() ?>

                    <?= $this->Html->scriptBlock('$(document).ready(function() {$("#commentField").emojioneArea();});', array('block' => 'scriptBottom')) ?>

                    <div class="lity-hide" id="edit-comment-hidden">
                        <?php
                            /* This is the tricky part : Welcome inside a HIDDEN form. JS'll fill in the content entry, the form URL (with the comment id), and submit it afterwards */
                            echo $this->Form->create(null, ['url' => ['controller' => 'Comments', 'action' => 'edit']]);
                            echo $this->Form->control('content', ['label' => '', 'class' => 'textarea-edit-comment','id' => 'textarea-edit', 'type' => 'textarea', 'placeholder' => '' /* THIS HAS TO BE FILLED IN WITH THE EDITED CONTENT */]);
                            echo $this->Form->submit(__('Edit'), ['id' => 'editCommentButton', 'class' => 'float-right' /* THIS HAS TO BE PRESSED, LIKE A SIMPLE BUTTON */]);
                            echo $this->Form->end();
                        ?>
                    </div>

                    <?= $this->Html->scriptBlock('$(document).ready(function() {$("#textarea-edit").emojioneArea({pickerPosition: "top"});});', array('block' => 'scriptBottom')) ?>

                <?php else: ?>

                    <?= __('You must be logged in to comment') ?> > <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', '?' => ['redirect' => '/setups/' . $setup->id]])?>"><?= __('Log me in !') ?></a>

                <?php endif ?>

            </div>

        </div>

        <div class="section-footer">
            <div id="social-networks"></div>
        </div>

    </div>

</div>

<?= $this->Html->scriptBlock('
    $("#comment-form").submit(function(event) {
        event.preventDefault();
        grecaptcha.reset();
        grecaptcha.execute();
    });

    function onSubmit(token) {
        document.getElementById("comment-form").submit();
    }
', ['block' => 'scriptBottom']); ?>
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
