<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', $setup->title.' | mySetup.co');

echo $this->Html->meta('description', $this->Text->truncate($setup->description,150,['ellipsis' => '..','exact' => true]), ['block' => true]);

echo $this->Html->meta(array('rel' => 'canonical', 'href' => $this->Url->build("/setups/".$setup->id."-".$this->Text->slug($setup->title), true)), null, ['block' => true]);


echo $this->Html->meta('description', 'See the most recent setups published on mySetup.co', ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => $setup->title.' | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $this->Text->truncate($setup->description,150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => $this->Text->truncate($setup->description,150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/'.$fimage->src, true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/'.$fimage->src, true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build("/setups/".$setup->id."-".$this->Text->slug($setup->title), true)], null ,['block' => true]);
?>

<div class="featured-container">
    <img width="1120" src="<?= $this->Url->build('/'.$fimage->src, true) ?>" alt="<?= $setup->title ?>">
    <div class="featured-inner">
        <div class="row">
            <div class="column column-75">
                <a class="featured-user" href="<?= $this->Url->build('/users/'.$additionalData['owner']['id']) ?>">
                    <img src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$setup->user_id.'.png') ?>">
                </a>
                <h3><?= $setup->title ?></h3>
                <p>
                    <?= __('Shared by') ?> <?php if($additionalData['owner']['name']){echo $this->Html->link($additionalData['owner']['name'], ['controller' => 'users', 'action' => 'view', $additionalData['owner']['id']]);}else{echo "Unknown";} ?><?php if($additionalData['owner']['verified']): echo ' <i class="fa fa-check-square verified_account"></i> '; endif; if($additionalData['owner']['name'] != $setup->author and $setup->author !== ''): echo ", created by " . $setup->author ; endif?>
                </p>
            </div>
            <a class="labeled_button float-right" <?php if(!$authUser){echo "onclick=\"toast.message('You must be login to like !');\"";} else{ echo "onclick=\"likeSetup('". $setup->id ."')\"";}?> tabindex="0">
              <div class="red_button">
                <i class="fa fa-heart"></i> Like
              </div>
              <span class="pointing_label">
                0
              </span>
            </a>
            <?= $this->Html->scriptBlock('$(document).ready(function() {printLikes("' . $setup->id . '");});', array('block' => 'scriptBottom')); ?>
            <?php if($authUser): echo $this->Html->scriptBlock('$(document).ready(function() {doesLike("' . $setup->id . '");});', array('block' => 'scriptBottom'));endif; ?>
        </div>
    </div>
</div>

<?php if($authUser['id'] == $setup->user_id or $authUser['admin']): ?>
    <div class="edit_panel">
        <div><a href="#edit_setup_modal" data-lity><i class="fa fa-wrench"></i> <?= __('Edit') ?> <?php echo ($authUser['id'] == $setup->user_id ? "your" : "this") ?> setup</a></div>
        <div><a href="#embed_twitch_modal" data-lity><i class="fa fa-twitch"></i> <?= __('Embed it in Twitch') ?></a></div>
    </div>

    <div id="edit_setup_modal" class="lity-hide">
        <h4>Edit <?php echo ($authUser['id'] == $setup->user_id ? __("your") : __("this")) ?> setup</h4>

        <?= $this->Form->create(null, ['type' => 'file', 'url' => ['controller' => 'Setups', 'action' => 'edit', $setup->id]]); ?>
        <fieldset style="border:0;">
            <?php
                echo $this->Form->control('title', ['label' => __('Title'), 'required' => true, 'id' => 'title', 'maxLength' => 48, 'default' => $setup->title]);
                echo $this->Form->control('description', ['label' => __('Description'), 'id' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxLength' => 500, 'default' => $setup->description]);
            ?>
            <input type="text" class="liveInput edit_setup" onkeyup="searchItem(this.value, '<?= $authUser['preferredStore'] ?>' ,'edit_setup');" placeholder="<?= __('Search for components...') ?>">
            <ul class="search_results edit_setup"></ul>
            <ul class="basket_items edit_setup">
                <?php foreach ($products as $item): ?>

                    <li>
                        <img src="<?= urldecode($item->src) ?>">
                        <p><?= urldecode($item->title) ?></p>
                        <a onclick="deleteFromBasket('<?= $item->title ?>',this,'edit_setup')"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>
                    </li>

                <?php endforeach ?>
            </ul>
            <br />
            <?php
                echo $this->Form->input('featuredImage. ', ['id' => 'featuredImage_edit', 'type' => 'file', 'label' => ['class' => 'label_fimage', 'text' => 'Change featured image'], 'class' => 'inputfile']);
            ?>
            <img id="featuredimage_preview_edit" src="<?= $this->Url->build('/', true)?><?= $fimage->src ?>" alt="<?= $setup->title ?>">
            <div class="hidden_five_inputs">
                <?php
                    echo $this->Form->input('gallery0. ', ['id'=>'gallery0', 'type' => 'file', 'hidden', 'class' => 'inputfile']);
                    echo $this->Form->input('gallery1. ', ['id'=>'gallery1', 'type' => 'file', 'hidden', 'class' => 'inputfile']);
                    echo $this->Form->input('gallery2. ', ['id'=>'gallery2', 'type' => 'file', 'hidden', 'class' => 'inputfile']);
                    echo $this->Form->input('gallery3. ', ['id'=>'gallery3', 'type' => 'file', 'hidden', 'class' => 'inputfile']);
                    echo $this->Form->input('gallery4. ', ['id'=>'gallery4', 'type' => 'file', 'hidden', 'class' => 'inputfile']);
                ?>
            </div>

            <?php $i = 0;foreach ($gallery as $image):?>
            <img class="gallery_edit_preview" id="gallery<?= $i ?>image_preview_edit" src="<?= $this->Url->build('/'.$image->src)?>">
            <?php $i++; endforeach; for(;$i < 5;$i++): ?>
            <img class="gallery_edit_preview" id="gallery<?= $i ?>image_preview_edit" src="<?= $this->Url->build('/img/add_gallery_default.png')?>">
            <?php endfor ?>

            <br /><br />
            <?php
                /* Fill the video source if exist */
                if(!empty($video->src)){$video_field = $video->src;}else{$video_field = '';}
                echo $this->Form->control('video', ['label' => __('Video (Youtube, Dailymotion, Twitch, ...)'), 'default' => $video_field]);

                /* Fill the current items in the field before edit */
                $item_field = '';
                foreach ($products as $item){
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
                    echo $this->Form->control('featured', ['type' => 'checkbox', 'label' => 'Feature this setup !', 'default' => $setup->featured]);
                }
            ?>
        </fieldset>
        <?= $this->Form->submit(__('Edit setup'), ['class' => 'float-right']); ?>
        <?= $this->Form->end(); ?>

        <?= $this->Form->postLink(__('Delete this setup'), ['controller' => 'Setups', 'action' => 'delete', $setup->id], ['confirm' => __('You are going to delete this setup ! Are you sure ?')]) ?>
    </div>

    <div id="embed_twitch_modal" class="lity-hide">
        <h4><?= __('How to embed your setup in Twitch ?') ?></h4>
        <p><?= __('Go to your Twitch channel and toggle panel edition.') ?></p>
        <?= $this->Html->image('howto_twitch.png', array('alt' => 'Twitch Panel Edition')) ?> <br>
        <p><?= __('Copy the following url in the link field') ?> :</p>
        <pre><code><?= $this->Url->build('/setups/'.$setup->id."-".$this->Text->slug($setup->title), true)?></code></pre>
        <p><?= __('You can even configure your Twitch Chat bot to display this link !') ?></p>
    </div>
<?php endif ?>

<div class="maincontainer">

    <div class="row config-post">
        <div class="column column-67">

            <div class="config-items">

            <?php foreach ($products as $item): ?>

                        <a href="<?= urldecode($item->href) ?>" target="_blank">
                            <div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div>
                        </a>

            <?php endforeach ?>

            </div>

        </div>

        <div class="column column-33 item-meta">

            <?= $this->Text->autoParagraph(h($setup->description . " -- " . $setup->creationDate))?> 

            <div id="social-networks"></div></br>

            <?php if(!empty($video->src)): ?>
                <a class="button item-youtube" href="<?= $video->src ?>" data-lity><?= __('Watch it in video') ?></a>
            <?php endif?>
        </div>
    </div>

    <br>

    <div class="post_slider">
        <?php foreach ($gallery as $image): ?>
            <div class="slider-item">
                <div class="slider-item-inner">
                    <a href="<?= $this->Url->build('/', true)?><?= $image->src ?>" data-lity data-lity-desc="Photo of Config'">
                        <img width="1120" src="<?= $this->Url->build('/', true)?><?= $image->src ?>">
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <div class="row comment-section">
        
        <div class="column column-60 column-offset-20">
            <h4 class="comment-section-title"><?= __('Wanna share your opinion ?') ?></h4>

            <section class="comments">
            <?php if (!empty($setup->comments)): ?>
                <?php foreach ($setup->comments as $comments): ?>
                <article class="comment">
                    <a class="comment-img" href="<?= $this->Url->build('/users/'.$comments->user_id)?>">
                        <img src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$comments->user_id.'.png') ?>" alt="" width="50" height="50" />
                    </a>
                        
                    <div class="comment-body">
                        <div class="text">
                          <p><?= h($comments->content) ?></p>
                        </div>
                        <p class="attribution">by <a href="<?= $this->Url->build('/users/'.$comments->user_id)?>"><?= h($additionalData[$comments->user_id]) ?></a> at <?= h($comments->dateTime) ?></p>

                        <?php if($authUser['id'] == $comments->user_id): echo ' - ' . $this->Form->postLink(__('Delete'), array('controller' => 'Comments','action' => 'delete', $comments->id),array('confirm' => 'Are you sure ?')); endif ?>
                    </div>
                </article>

                <?php endforeach; ?>
            <?php endif; ?>

            </section>

            <?php if($authUser): ?>

                <?= $this->Form->create($newComment, ['type' => 'file', 'url' => ['controller' => 'Comments', 'action' => 'add', $setup->id], 'id' => 'comment-form']); ?>
                <fieldset>
                <?php echo $this->Form->control('content', ['label'=>'', 'id' => 'commentField', 'type' => 'textarea', 'placeholder' => __('Nice config\'…'),'rows' => 10, 'maxLength' => 500]);?>
                </fieldset>
                <?= $this->Form->submit(__('Comment'), ['class' => 'float-right g-recaptcha', 'data-sitekey' => '6LcLKx0UAAAAADiwOqPFCNOhy-UxotAtktP5AaEJ', 'data-callback' => 'onSubmit']); ?>
                <?= $this->Form->end(); ?>
            <?php else: ?>

                <?= __('You must be logged in to comment') ?> > <a href="<?= $this->Url->build('/login')?>"><?= __('Log me in !') ?></a>

            <?php endif ?>
        </div>

    </div>

</div>

<script>
    function onSubmit(token) {
        document.getElementById("comment-form").submit();
    }
</script>
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
