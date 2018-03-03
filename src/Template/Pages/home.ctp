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
            <div class="hero_mainsetup" alt="<?= h($mainSetup->title) ?>" style="background-image:url(<?= $this->Url->build('/' . (!empty($mainSetup->resources[0]) ? $mainSetup->resources[0]->src : 'img/not_found.jpg' )) ?>)"></div>

            <div class="hero_hover">
                <h2><?= __('Your main setup has') ?> <?= $mainSetup->like_count ?> <?= __n('like', 'likes', $mainSetup->like_count) ?> !</h2>
                <p><?= __('Share it to get more') ?> :</p>

                <div class="embed-links">
                    <a href="#embed_twitch_modal" data-lity class="jssocials-share-link"><i class="fa fa-twitch"></i> <?= __('Embed on Twitch') ?></a>
                    <a href="#embed_website_script" data-lity class="jssocials-share-link"><i class="fa fa-code"></i> <?= __('Embed on your website') ?></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $this->Url->build('/setups/'.$mainSetup->id."-".$this->Text->slug($mainSetup->title), true)?>&t=<?= $mainSetup->title ?>" target="_blank" class="jssocials-share-link"><i class="fa fa-facebook"></i> <?= __('Post it !') ?></a>
                    <a href="https://twitter.com/intent/tweet?via=mysetup_co&url=<?= $this->Url->build('/setups/'.$mainSetup->id."-".$this->Text->slug($mainSetup->title), true)?>&text=<?= $mainSetup->title ?>" target="_blank" class="jssocials-share-link"><i class="fa fa-twitter"></i> <?= __('Tweet it !') ?></a>
                    <a href="<?= $this->Url->build('/setups/'.$mainSetup->id.'-'.$this->Text->slug($mainSetup->title)); ?>">
                    </a>
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
            <?= $this->Html->scriptBlock(' let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`search/?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>

        </div>

        <div class="config-items">
            <?php foreach ($randomResources as $item): ?>
                <a href="<?= $this->Url->build('/search/?q=' . $item->title) ?>"><div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div></a>
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
              <a href="https://www.facebook.com/mysetup.co" target="_blank" style="background: #00c6ff;
                background: -webkit-linear-gradient(to right, #0072ff, #00c6ff);
                background: linear-gradient(to right, #0072ff, #00c6ff);
                "><i class="fa fa-facebook fa-2x"></i></a>
              <a href="https://twitter.com/mysetup_co" target="_blank" style="background-color: #55acee"><i class="fa fa-twitter fa-2x"></i></a>
              <a href="https://geeks.one/@mysetup_co" title="Mastodon" target="_blank" style="background-color: #282c37"><img style="height:50px;margin-top:25px" src="<?= $this->Url->build('/img/mastodon_logo.svg')?>"></a>
              <a href="https://www.instagram.com/mysetup.co/" target="_blank" style="background:
                radial-gradient(circle farthest-corner at 35% 90%, #fec564, transparent 50%),
                radial-gradient(circle farthest-corner at 0 140%, #fec564, transparent 50%),
                radial-gradient(ellipse farthest-corner at 0 -25%, #5258cf, transparent 50%),
                radial-gradient(ellipse farthest-corner at 20% -50%, #5258cf, transparent 50%),
                radial-gradient(ellipse farthest-corner at 100% 0, #893dc2, transparent 50%),
                radial-gradient(ellipse farthest-corner at 60% -20%, #893dc2, transparent 50%),
                radial-gradient(ellipse farthest-corner at 100% 100%, #d9317a, transparent),
                linear-gradient(#6559ca, #bc318f 30%, #e33f5f 50%, #f77638 70%, #fec66d 100%);
                "><i class="fa fa-instagram fa-2x"></i></a>
          </div>
        </div>

    </div>
</div>

<div class="colored-box-8">
    <div class="container">
        <div class="rowfeed">
            <h4 class="fancy"><span><?= __('Popular setups') ?></span></h4>
            <div class="feeditem">

                <?php foreach($popularSetups as $setup): ?>

                    <?= $this->element('List/cards', ['setup' => $setup]) ?>

                <?php endforeach; ?>
            </div>
            <br clear='all'>
        </div>
    </div>
</div>

<div class="colored-box-6">
    <div class="container">
        <div class="rowfeed">
            <h4 class="fancy"><span><?= __('Latest setups') ?></span></h4>
            <div class="feeditem">

                <?php $i=0; foreach ($recentSetups as $setup): ?>

                    <?= $this->element('List/cards', ['setup' => $setup]) ?>

                <?php if (++$i == 8) break; endforeach ?>
            </div>
            <a class="home_more float-right" href="<?= $this->Url->build('/pages/recent'); ?>"><?= __('More recent setups') ?> <i class="fa fa-chevron-right"></i></a>
            <br clear='all'>
        </div>
    </div>
</div>

<?php $i = 1;  foreach($brandSetups as $brand => $setups): ?>

    <div class="colored-box-<?= ++$i ?>">
        <div class="container">
            <div class="rowfeed">
                <h4 class="fancy"><span><?= h($brand) ?></span></h4>
                <div class="feeditem">

                    <?php foreach ($setups as $setup): ?>

                        <?= $this->element('List/cards', ['setup' => $setup]) ?>

                    <?php endforeach; ?>
                </div>
                <a class="home_more float-right" href="<?= $this->Url->build('/search/?q='.$brand); ?>"><?= __('More {0} setups', $brand) ?> <i class="fa fa-chevron-right"></i></a>
                <br clear='all'>
            </div>
        </div>
    </div>

<?php endforeach; ?>

<div class="container">
    <div class="rowfeed">
        <h4 class="fancy"><span><?= __('Suggested Users') ?></span></h4>
        <div class="activeUsers">
            <?php foreach($activeUsers as $activeUser): ?>
                <div class="featured-user">
                    <a href="<?=$this->Url->build('/users/'.$activeUser->id)?>">
                        <img alt="<?= __('Profile picture of') ?> <?= $activeUser->name ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $activeUser->id . '.png?' . $this->Time->format($activeUser->modificationDate, 'mmss', null, null)); ?>">
                        <span>
                            <strong><?= $activeUser->name ?></strong>
                            <span></span>
                        </span>
                    </a>
                </div>
            <?php endforeach ?>
        </div>
        <br clear='all'>
    </div>
</div>
