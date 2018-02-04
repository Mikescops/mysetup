<?php

$this->layout = 'default';
$this->assign('title', __('mySetup | Share your own setup'));

echo $this->Html->meta('description', __('The best place to share your computer setup with your community ! Inspire others or get inspired with gaming setups, battlestations...'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'mySetup | Share your own setup'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'The best place to share your "my setup" with your community ! Inspire others or get inspired with gaming setups, battlestations...'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'The best place to share your "my setup" with your community ! Inspire others or get inspired with gaming setups, battlestations...'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/', true)], null ,['block' => true]);

?>

<div class="home_hero_container sitecontainer">
    <div class="container">
    <?php if(!$authUser): ?>
        <div class="hero_image">
            <?= $this->Html->image('hero.svg', ['alt' => 'Hero mySetup.co', 'class' => 'hero-image']) ?>
            <?= $this->Html->link(__('Add my setup'), '/login', ['class' => 'hero_calltoaction']) ?>
        </div>
    <?php else: ?>
        <?php if($authUser['mainSetup_id'] == 0): ?>
            <div class="hero_inner">
                <div class="hero_column">
                    <h3><?= __('You didn\'t add any setup yet !') ?></h3>
                    <p><?= __('Start now and select all your setup\'s components.') ?></p>
                </div>
                <div class="hero_column center-margin-button">
                    <a href="#add_setup_modal" data-lity class="hero_calltoaction"><?= __('Add my setup now') ?></a>
                </div>
            </div>

            <div class="rowfeed">
                <div class="feeditem">

                    <?php foreach ($featuredSetups as $setup): ?>

                        <?= $this->element('List/cards', ['setup' => $setup]) ?>

                    <?php endforeach ?>
                    <br clear="all">
                </div>
            </div>
        <?php else: ?>
            <div class="hero_inner">
                <div class="hero_column">
                    <h3><?= __('Your main setup has') ?> <?= $mainSetup->like_count ?> <?= __n('like', 'likes', $mainSetup->like_count) ?> !</h3>
                    <p><?= __('Share it to get more') ?> :</p>

                    <a href="#embed_website_script" data-lity class="jssocials-share-link"><i class="fa fa-code"></i> Embed it to your website</a>
                    <a href="#embed_twitch_modal" data-lity class="jssocials-share-link"><i class="fa fa-twitch"></i> Integrate on Twitch</a><br>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $this->Url->build('/setups/'.$mainSetup->id."-".$this->Text->slug($mainSetup->title), true)?>&t=<?= $mainSetup->title ?>" target="_blank" class="jssocials-share-link"><i class="fa fa-facebook"></i> Post it !</a>
                    <a href="https://twitter.com/intent/tweet?via=mysetup_co&url=<?= $this->Url->build('/setups/'.$mainSetup->id."-".$this->Text->slug($mainSetup->title), true)?>&text=<?= $mainSetup->title ?>" target="_blank" class="jssocials-share-link"><i class="fa fa-twitter"></i> Tweet it !</a>
                </div>

                <div class="hero_column">
                    <div class="fullitem">
                        <a href="<?= $this->Url->build('/setups/'.$mainSetup->id.'-'.$this->Text->slug($mainSetup->title)); ?>">
                            <img alt="<?= h($mainSetup->title) ?>" src="<?= $this->Url->build('/' . (!empty($mainSetup->resources[0]) ? $mainSetup->resources[0]->src : 'img/not_found.jpg' )) ?>">
                        </a>
                    </div>
                </div>

            </div>
            <?= $this->element('Modal/twitch', ['setup' => $mainSetup]) ?>
            <?= $this->element('Modal/embed', ['setup' => $mainSetup]) ?>
        <?php endif ?>
    <?php endif ?>
    </div>
</div>

<div class="container">

    <div class="maincontainer">

        <div class="large_search" style="margin-top: -60px"> <i class="fa fa-search"></i>

            <input type="text" id="keyword-search" placeholder="<?= __('Search a component... Find a cool setup !') ?>" />
            <?= $this->Html->scriptBlock(' let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`search/resources?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>

        </div>

        <div class="rowfeed">
            <?php foreach ($recentResources as $item): ?>
                <a href="<?= $this->Url->build('/search/resources?q=' . $item->title) ?>"><div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div></a>
            <?php endforeach?>
        </div>

        <br clear='all'>

        <div class="rowsocial">
          <?php if(!$authUser): ?>
            <div class="twitch-advert" onclick="logTwitch('<?= $lang ?>')">
              <h4><i class="fa fa-twitch"></i> <?= __('Login with Twitch and create my Setup !') ?></h4>
            </div>
          <?php else: ?>
              <div class="blog-advert">
                <a href="<?=$this->Url->build('/blog/')?>">
                  <h5><i class="fa fa-newspaper-o"></i> <?= __('Read our latest news') ?></h5>
                </a>
              </div>
          <?php endif ?>

          <div class="social-networks">
              <a href="https://www.facebook.com/mysetup.co" target="_blank" style="background-color: #3b5998"><i class="fa fa-facebook fa-2x"></i></a>
              <a href="https://twitter.com/mysetup_co" target="_blank" style="background-color: #55acee"><i class="fa fa-twitter fa-2x"></i></a>
              <a href="https://geeks.one/@mysetup_co" title="Mastodon" target="_blank" style="background-color: #45668e"><img style="height:50px;margin-top:25px" src="<?= $this->Url->build('/img/mastodon_logo.svg')?>"></a>
          </div>
        </div>


        <div class="rowfeed">
            <div class="feeditem">

                <?php $i=0; foreach ($popularSetups as $setup): ?>

                    <?= $this->element('List/cards', ['setup' => $setup]) ?>

                <?php if (++$i == 3) break; endforeach ?>
            </div>
            <a class="home_more float-right" href="<?= $this->Url->build('/pages/popular'); ?>"><?= __('More popular setups') ?> <i class="fa fa-chevron-right"></i></a>
        </div>

        <br clear='all'>

        <div class="rowfeed">
            <h4 class="fancy"><span><?= __('Latest setups') ?></span></h4>
            <div class="feeditem">

                <?php $i=0; foreach ($recentSetups as $setup): ?>

                    <?= $this->element('List/cards', ['setup' => $setup]) ?>

                <?php if (++$i == 8) break; endforeach ?>
            </div>
            <a class="home_more float-right" href="<?= $this->Url->build('/pages/recent'); ?>"><?= __('More recent setups') ?> <i class="fa fa-chevron-right"></i></a>
        </div>

        <br clear='all'>

        <div class="rowfeed">
            <h4 class="fancy"><span>AMD</span></h4>
            <div class="feeditem">

                <?php $i = 0; foreach ($amdSetups as $setup): ?>

                    <?= $this->element('List/cards', ['setup' => $setup]) ?>

                <?php if (++$i == 3) break; endforeach ?>
            </div>
            <a class="home_more float-right" href="<?= $this->Url->build('/search/resources?q=amd'); ?>"><?= __('More AMD setups') ?> <i class="fa fa-chevron-right"></i></a>
        </div>

        <br clear='all'>

        <div class="rowfeed">
            <h4 class="fancy"><span>Nvidia</span></h4>
            <div class="feeditem">

                <?php $i = 0; foreach ($nvidiaSetups as $setup): ?>

                    <?= $this->element('List/cards', ['setup' => $setup]) ?>

                <?php if (++$i == 3) break; endforeach ?>
            </div>
            <a class="home_more float-right" href="<?= $this->Url->build('/search/resources?q=nvidia'); ?>"><?= __('More Nvidia setups') ?> <i class="fa fa-chevron-right"></i></a>
        </div>

        <br clear='all'>

        <div class="rowfeed">
            <h4 class="fancy"><span><?= __('Suggested Users') ?></span></h4>
            <div class="activeUsers">
                <?php foreach($activeUsers as $activeUser): ?>

                    <a class="featured-user" href="<?=$this->Url->build('/users/'.$activeUser->user_id)?>">
                        <img alt="<?= __('Profile picture of') ?> <?= $activeUser->user->name ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $activeUser->user_id . '.png?' . $this->Time->format($activeUser->user->modificationDate, 'mmss', null, null)); ?>">
                    </a>

                <?php endforeach ?>
            </div>
        </div>

        <br clear='all'>

    </div>
</div>
