<?php

use Cake\Core\Configure;

/**
  * @var \App\View\AppView $this
  */

$this->assign('title', h($setup->title) . ' | mySetup.co');

echo $this->Html->meta('description', $this->Text->truncate($this->MySetupTools->getPlainTextIntroFromHTML($this->Markdown->transform($setup->description)),150,['ellipsis' => '..','exact' => true]), ['block' => true]);

echo $this->Html->meta(array('rel' => 'canonical', 'href' => $this->Url->build("/setups/".$setup->id."-".$this->Text->slug($setup->title), true)), null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:title', 'content' => $setup->title.' | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $this->Text->truncate($this->MySetupTools->getPlainTextIntroFromHTML($this->Markdown->transform($setup->description)),150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['name' => 'twitter:description', 'content' => $this->Text->truncate($this->MySetupTools->getPlainTextIntroFromHTML($this->Markdown->transform($setup->description)),150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg'), true)], null ,['block' => true]);
echo $this->Html->meta(['name' => 'twitter:image', 'content' => $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg'), true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build("/setups/".$setup->id."-".$this->Text->slug($setup->title), true)], null ,['block' => true]);
?>

<?php
    $rgb_colors = json_decode($setup->main_colors)[0];
?>

<div class="featured-container">
    <div class="featured-gradient" style="background-image: url('<?= $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg'), true) ?>')"></div>

    <div class="post_slider">


        <div class="slider-item">
            <div class="featured-img-setup slider-item-inner">

                <img alt="<?= h($setup->title) ?>" src="<?= $this->Url->build('/' . ($setup['resources']['featured_image'] ? $setup['resources']['featured_image'] : 'img/not_found.jpg'), true) ?>">
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
                    <a href="<?= $this->Url->build('/', true)?><?= $image->src ?>" data-lity data-lity-desc="<?= __('Setup picture of') ?> <?= h($setup->user['name']) ?>">
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
                    <?php if($setup->id == $setup->user->mainSetup_id): ?>
                        <i title="<?= ($authUser['id'] != $setup->user_id ? __('This is the main setup of') . ' ' . h($setup->user->name) : __('This is your main setup')) ?>" class="fa fa-diamond setup-default"></i>
                    <?php endif ?>
                    <?php if($setup->featured): ?>
                        <i title="<?= __('This setup is featured on mySetup.co !')?>" class="fa fa-star setup-star"></i>
                    <?php endif ?>
                    <?php if($setup->status == 'DRAFT'): ?>
                        <i title="<?= __('Only you can see this setup') ?>" class="fa fa-eye-slash setup-unpublished"></i>
                    <?php elseif($setup->status == 'REJECTED'): ?>
                        <i title="<?= __('Your setup has been rejected. You will find the reason within its description.') ?>" class="fa fa-ban setup-rejected"></i>
                    <?php endif ?>
                </h3>
                <p>
                    <?= __('Shared by') ?> <?= $this->Html->link($setup->user['name'], ['controller' => 'users', 'action' => 'view', $setup->user['id']]) ?><?php if($setup->user['verified']): echo ' <i class="fa fa-check-square verified_account"></i> '; endif; if($setup->user['name'] != $setup->author and $setup->author !== ''): echo ", " . __("created by ") . h($setup->author) ; endif?>
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
                        <a href="#embed_twitch_modal" data-lity title="<?= __('Embed on Twitch') ?>"><i class="fa fa-twitch"></i></a>
                        <a href="#embed_website_script" data-lity title="<?= __('Embed on your website') ?>"><i class="fa fa-code"></i></a>
                    </div>

                    <?= $this->element('Modal/edit-setup') ?>

                    <?= $this->element('Modal/twitch') ?>

                    <?= $this->element('Modal/embed') ?>

                <?php elseif($authUser && $setup->user_id != $authUser['id']): ?>
                    <div class="edit_panel">
                            <?= $this->Form->postLink('', ['controller' => 'Requests', 'action' => 'requestOwnership', $setup->id], ['confirm' => __('This will send an ownership-request for this setup, are you really sure ?'), 'title' => __('This is my setup !'), 'class' => 'fa fa-bolt']) ?>

                            <?= $this->Form->postLink('', ['controller' => 'Requests', 'action' => 'requestReport', $setup->id], ['confirm' => __('This will send a report-request against this setup, are you really sure ?'), 'title' => __('Report this setup'), 'class' => 'fa fa-flag']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container maincontainer">
    <div class="row config-post">

        <div class="config-items">

            <?php $i=0; foreach ($setup['resources']['products'] as $item): ?>

            <div id="item-trigger-<?= $i ?>" class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div>

            <div id="item-about-<?= $i ?>" style="display: none;">
                <div class="about-inner">
                  <h5><?= h(urldecode($item->title)) ?></h5>
                  <a href="<?= $this->Url->build('/search/?q='.h($item->title)); ?>" class="button brelated"><i class="fa fa-search"></i> <?= __('Find related setups') ?></a>
                  <a href="<?= urldecode($item->href) ?>" traget="_blank" class="button amazon-buy"><i class="fa fa-shopping-bag"></i> <?= __('More info') ?></a>
              </div>
            </div>

            <?= $this->Html->scriptBlock("new tippy('#item-trigger-$i', {zIndex: 20, html: '#item-about-$i',arrow: true,animation: 'fade',position: 'bottom', interactive: true});", array('block' => 'scriptBottom')) ?>

            <?php $i++; endforeach ?>

            <br clear="all">
        </div>

    </div>

</div>

<div class="section-social">
    <div id="social-networks"></div>
</div>

<div class="colored-box-1">
    <div class="container">
        <div class="row content-section">

            <div class="column column-60 item-meta">

                <div class="section-header">
                    <h4><?= __('About this setup') ?></h4>
                </div>

                <div class="section-inner">

                    <?= preg_replace('/<a (.*)>(.*)<\/a>/', '<a rel="nofollow" $1>$2</a>', $this->Markdown->transform(h($setup->description))) ?>

                    <div class="setup-colors">
                        <?php foreach (json_decode($setup->main_colors) as $color): ?>
                            <div class="setup-color-box" title="RGB <?= $color[0].', '.$color[1].', '.$color[2] ?>" style="background: rgba(<?= $color[0].', '.$color[1].', '.$color[2] ?>,1);"></div>
                        <?php endforeach ?>
                    </div>

                    <br>

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
                                            echo ' - <a class="edit-comment" source="comment-'.$comments->id.'"onclick="commentModal(`edit`)"> ' . __('Edit') . ' </a>';
                                        endif ?>
                                    </div>
                              </article>
                          <?php endforeach; ?>
                        <?php endif; ?>
                    </section>

                    <?php if($authUser): ?>

                        <button id="add-comment-button" class="button large-button float-right" onclick="commentModal('add')"><?= __('Add a comment') ?></button>

                        <script type="text/template" id="add-comment-script">
                            <div id="add-comment-hidden">
                                <?= $this->Form->create(null, ['url' => ['controller' => 'Comments', 'action' => 'add', $setup->id], 'id' => 'comment-form']); ?>
                                <fieldset>
                                    <?php echo $this->Form->control('content', ['label' => '', 'id' => 'add-comment-field', 'type' => 'textarea', 'placeholder' => __('Nice config\'...'), 'rows' => "1", 'maxlength' => 500]); ?>
                                </fieldset>
                                    <?= $this->Form->button(__('Post this comment'), ['id' => 'addCommentButton', 'class' => 'float-right']) ?>
                                    <?= $this->Form->end() ?>
                            </div>
                        </script>

                        <script type="text/template" id="edit-comment-script">
                            <div id="edit-comment-hidden">
                                <?php
                                    /* This is the tricky part : Welcome inside a HIDDEN form. JS'll fill in the content entry, the form URL (with the comment id), and submit it afterwards */
                                    echo $this->Form->create(null, ['url' => ['controller' => 'Comments', 'action' => 'edit']]);
                                    echo $this->Form->control('content', ['label' => '', 'class' => 'textarea-edit-comment','id' => 'edit-comment-field', 'type' => 'textarea', 'placeholder' => '' /* THIS HAS TO BE FILLED IN WITH THE EDITED CONTENT */]);
                                    echo $this->Form->submit(__('Edit'), ['id' => 'editCommentButton', 'class' => 'float-right' /* THIS HAS TO BE PRESSED, LIKE A SIMPLE BUTTON */]);
                                    echo $this->Form->end();
                                ?>
                            </div>
                        </script>

                    <?php else: ?>

                        <?= __('You must be logged in to comment') ?> > <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', '?' => ['redirect' => '/setups/' . $setup->id]])?>"><?= __('Log me in !') ?></a>

                    <?php endif ?>

                </div>

            </div>

        </div>
    </div>
</div>


<div class="before-footer">
    <div class="container">
    </div>
</div>
