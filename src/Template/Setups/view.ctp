<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="featured-container">

    <img width="1120" src="<?= $this->Url->build('/', true)?><?= $fimage->src ?>" alt="<?= $setup->title ?>">

    <div class="featured-inner">

        <div class="row">

            <div class="column column-75">
                <a class="featured-user" href="<?= $this->Url->build('/users/view/'); ?><?= $additionalData['owner']['id'] ?>">
                    <img src="<?= $this->Url->build('/'); ?>uploads/files/profile_picture_<?= $setup->user_id ?>.png">
                </a>

                <h3><?= $setup->title ?></h3>

                <p>Shared by <?= $this->Html->link($additionalData['owner']['name'], ['controller' => 'users', 'action' => 'view', $additionalData['owner']['id']]) ?>  <?php if($additionalData['owner']['verified']): echo '<i class="fa fa-check-square verified_account"></i>'; endif; if($additionalData['owner']['name'] != $setup->author): echo ", created by " . $setup->author; endif?></p>

            </div>
                
                <a class="labeled_button float-right" <?php if(!$authUser){echo "onclick=\"const toast = new siiimpleToast();toast.message('You must be login to like !');\"";} else{ echo "onclick=\"likeSetup('". $setup->id ."')\"";}?> tabindex="0">
                  <div class="red_button">
                    <i class="fa fa-heart"></i> Like
                  </div>
                  <span class="pointing_label">
                    0
                  </span>
                </a>

                <?= $this->Html->scriptBlock('$(document).ready(function() {printLikes("' . $setup->id .'"); doesLike("' . $setup->id .'");});', array('block' => 'scriptBottom')); ?>

        </div>

    </div>

</div>

<?php if($authUser['id'] == $setup->user_id): ?>
    <div class="edit_panel">
        <div><a href="#edit_setup_modal" data-lity><i class="fa fa-wrench"></i> Edit your setup</a></div>
        <div><a href="#embed_twitch_modal" data-lity><i class="fa fa-twitch"></i> Embed it in Twitch</a></div>
    </div>

    <div id="edit_setup_modal" class="lity-hide">
        <h4>Edit your setup</h4>
    </div>

    <div id="embed_twitch_modal" class="lity-hide">
        <h4>How to embed your setup in Twitch ?</h4>

        <p>Go to your Twitch channel and toggle panel edition.</p>

        <?= $this->Html->image('howto_twitch.png', array('alt' => 'Twitch Panel Edition')) ?> <br>

        <p>Copy the following url in the link field :</p>

        <pre><code><?= $this->Url->build('setups/view/', true) . $setup->id?></code></pre>

        <p>You can even configure your Twitch Chat bot to display this link !</p>
            
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

            <?= $this->Text->autoParagraph(h($setup->description)); ?>

            <p>Published on : <?= h($setup->creationDate) ?></p>

            <div id="social-networks"></div><br>

            <?php if(!empty($video->src)): ?>
                <a class="button item-youtube" href="<?= $video->src ?>" data-lity>Voir la vidéo Youtube</a>
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
        
        <div class="column column-50 column-offset-25">
        <h4>Wanna share your opinion ?</h4>

        <section class="comments">
        <?php if (!empty($setup->comments)): ?>
            <?php foreach ($setup->comments as $comments): ?>
            <article class="comment">
                <a class="comment-img" href="#non">
                    <img src="https://avatars1.githubusercontent.com/u/4266283?v=3&s=460" alt="" width="50" height="50" />
                </a>
                    
                <div class="comment-body">
                    <div class="text">
                      <p><?= h($comments->content) ?></p>
                    </div>
                    <p class="attribution">by <a href="#non"><?= h($additionalData[$comments->user_id]) ?></a> at <?= h($comments->dateTime) ?></p>
                </div>
            </article>

            <?php endforeach; ?>
        <?php endif; ?>

        </section>
            
        <form>
            <label for="commentField">Comment</label>
            <textarea placeholder="Nice config' …" id="commentField"></textarea>
            <input class="button-primary float-right" type="submit" value="Send">
          </fieldset>
        </form>

        </div>

    </div>

</div>
