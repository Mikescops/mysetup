<?php

$this->layout = 'default';
$this->assign('title', __('Popular this week | mySetup.co'));

echo $this->Html->meta('description', __('The most popular setups of the week on mySetup.co'), ['block' => true]);


echo $this->Html->meta(['property' => 'og:title', 'content' => 'Popular this week | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'The most popular setups of the week on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'The most popular setups of the week on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/popular', true)], null ,['block' => true]);

?>

<div class="colored-container">
    <div class="container">
        <br><h2><?= __('Popular this week') ?></h2><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">

    <div class="row">
        <div class="column column-75">
            <div class="fullitem_holder">

            <?php foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= h($setup->title) ?>" src="<?= $this->Url->build('/' . (!empty($setup->resources[0]) ? $setup->resources[0]->src : 'img/not_found.jpg' )) ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i> <?= $setup->like_count ?></div>

                <div class="fullitem-inner">
                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> #<?= $setup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user->modificationDate, 'mmss', null, null)); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>
                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach ?>

            </div>

        </div>
        <div class="column column-25 sidebar">

            <div class="blog-advert">
              <a href="<?=$this->Url->build('/blog/')?>">
                <h5><i class="fa fa-newspaper-o"></i><br><?= __('Read our latest news') ?></h5>
              </a>
            </div>

            <div class="social-networks">
                <a href="https://www.facebook.com/mysetup.co" target="_blank" style="background-color: #3b5998"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://twitter.com/mysetup_co" target="_blank" style="background-color: #55acee"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="https://geeks.one/@mysetup_co" title="Mastodon" target="_blank" style="background-color: #45668e"><img style="height:50px;margin-top:25px" src="<?= $this->Url->build('/img/mastodon_logo.svg')?>"></a>
            </div>

        </div>
    </div>

    </div>
</div>
