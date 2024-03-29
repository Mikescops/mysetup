<?php

$this->layout = 'default';

$seo_title = 'mySetup.co | ' . __('Discover and Share Awesome Setups');
$seo_description = __('The best place to share your computer setup with your community ! Inspire others or get inspired with gaming setups, battlestations...');

$this->assign('title', $seo_title);
echo $this->Html->meta('description', $seo_description, ['block' => true]);
echo $this->Html->meta(['property' => 'og:title', 'content' => $seo_title], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:title', 'content' => $seo_title], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $seo_description], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => $seo_description], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:type', 'content' => 'website'], null, ['block' => true]);
?>

<div class="home_hero_container sitecontainer" <?php if ($authUser && $authUser['mainSetup_id'] != 0) : ?>style="background-image: radial-gradient(ellipse closest-side, rgba(0, 0, 0, 0.80), #161623), url(<?= $this->Url->build('/' . (!empty($mainSetup->resources[0]) ? $mainSetup->resources[0]->src : 'img/not_found.jpg')) ?>)" <?php endif ?>>
    <div class="container">
        <?php if (!$authUser) : ?>
            <div class="hero_image">
                <div class="hero_container">
                    <div class="hero_content">
                        <h2><?= __('Share your setup everywhere') ?></h2>
                        <p><?= __('Twitch, Twitter, Facebook, personal website...') ?></p>
                        <?= $this->Html->link(__('Add my setup'), '/login', ['class' => 'hero_calltoaction']) ?>
                    </div>
                    <?= $this->Html->image('hero_computer.svg', ['alt' => 'Hero mySetup.co', 'class' => 'hero-setup']) ?>
                </div>
            </div>
        <?php else : ?>
            <?php if ($authUser['mainSetup_id'] == 0) : ?>
                <div class="hero_inner">
                    <div class="hero_column">
                        <h3><?= __('Welcome') ?> <?= $authUser['name'] ?>,</h3>
                        <p><?= __('You can add your setup or find inspiration in the awesome content below!') ?></p>
                    </div>
                    <div class="hero_column right-margin-button">
                        <a href="<?= $this->Url->build(['controller' => 'Setups', 'action' => 'add']) ?>" class="hero_calltoaction"><?= __('Add my setup now') ?></a>
                    </div>
                </div>
                <br>
            <?php else : ?>
                <div class="hero_mainsetup">
                    <h2><?= __('Welcome back') ?> <?= $authUser['name'] ?></h2>

                    <div class="embed-links">
                        <span class="jssocials-share-link"><?= $mainSetup->like_count ?> <?= __n('like', 'likes', $mainSetup->like_count) ?></span>
                        <a href="<?= $this->Url->build('/setups/' . $mainSetup->id . '-' . $this->Text->slug($mainSetup->title)) ?>" class="jssocials-share-link"><i class="fa fa-laptop"></i> <?= __('See it in action') ?></a>
                        <a href="#embed_twitch_modal" data-lity class="jssocials-share-link"><i class="fab fa-twitch"></i> <?= __('Embed on Twitch') ?></a>
                        <a href="#embed_website_script" data-lity class="jssocials-share-link"><i class="fa fa-code"></i> <?= __('Embed on your website') ?></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $this->Url->build('/setups/' . $mainSetup->id . "-" . $this->Text->slug($mainSetup->title), true) ?>&t=<?= h($mainSetup->title) ?>" target="_blank" class="jssocials-share-link"><i class="fab fa-facebook-square"></i> <?= __('Post it !') ?></a>
                        <a href="https://twitter.com/intent/tweet?via=mysetup_co&url=<?= $this->Url->build('/setups/' . $mainSetup->id . "-" . $this->Text->slug($mainSetup->title), true) ?>&text=<?= h($mainSetup->title) ?>" target="_blank" class="jssocials-share-link"><i class="fab fa-twitter"></i> <?= __('Tweet it !') ?></a>
                    </div>
                </div>
                <?= $this->element('Modal/twitch', ['setup' => $mainSetup]) ?>
                <?= $this->element('Modal/embed', ['setup' => $mainSetup]) ?>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>

<?php if ($featuredSetups && count($featuredSetups) > 0) : ?>

    <div class="container">
        <div class="rowfeed">
            <h3><span><?= __('Staff Picks') ?></span></h3>
            <div id="random-grid" class="card-grid">
                <?php foreach ($featuredSetups as $setup) : ?>

                    <?= $this->element('List/card-item', ['setup' => $setup]) ?>

                <?php endforeach; ?>
            </div>
            <br clear='all'>
        </div>
    </div>

<?php endif ?>

<div class="colored-box-8">
    <div class="container">
        <div class="rowfeed">
            <h3><?= __('Components you may like') ?></h3>
            <div class="config-items">
                <?php foreach ($randomResources as $item) : ?>
                    <a href="<?= $this->Url->build('/search/?q=' . h($item->title)) ?>">
                        <div class="item_box lazy" data-src="<?= urldecode($item->src) ?>"></div>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="container">
        <?php if ($popularSetups) :
            $randFeatured = array_rand($popularSetups, 1);
            echo $this->element('List/showcase', ['setup' => $popularSetups[$randFeatured]]);
        endif; ?>
    </div>
</div>

<?php $i = 0;
foreach ($brandSetups as $brand => $setups) : ?>

    <div class="colored-box-<?= (++$i % 8) + 1 ?>">
        <div class="container">
            <div class="rowfeed">
                <h3><?= h($brand) ?></h3>
                <div class="card-grid">
                    <?php foreach ($setups as $setup) : ?>

                        <?= $this->element('List/card-item', ['setup' => $setup]) ?>

                    <?php endforeach; ?>
                </div>
                <br>
                <a class="home_more float-right" href="<?= $this->Url->build('/search/?q=' . $brand); ?>"><?= __('More {0} setups', $brand) ?> <i class="fa fa-chevron-right"></i></a>
                <br clear='all'>
            </div>
        </div>
    </div>

<?php endforeach; ?>

<div class="container">
    <?php if ($activeUsers) : ?>
        <div class="rowfeed">
            <h3><?= __('Suggested Users') ?></h3>
            <div class="user-grid">
                <?php foreach ($activeUsers as $activeUser) : ?>
                    <div class="item-grid">
                        <a href="<?= $this->Url->build('/users/' . $activeUser->id . '-' . $this->Text->slug($activeUser->name)) ?>">
                            <img alt="<?= __('Profile picture of') ?> <?= h($activeUser->name) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $activeUser->id . '.png?' . $this->Time->format($activeUser->modificationDate, 'mmss', null, null)); ?>">
                            <span>
                                <strong><?= h($activeUser->name) ?></strong>
                                <span><?= h($this->MySetupTools->urlPrettifying($activeUser->utwitch)) ?></span>
                            </span>
                        </a>
                    </div>
                <?php endforeach ?>
            </div>
            <br clear='all'>
        </div>
    <?php endif ?>

    <div class="rowsocial">
        <?php if (!$authUser) : ?>
            <div class="twitch-advert" onclick="logTwitch('<?= $lang ?>')">
                <h4><i class="fab fa-twitch"></i> <?= __('Login with Twitch and create my Setup !') ?></h4>
            </div>
        <?php else : ?>
            <div class="blog-advert">
                <a href="<?= $this->Url->build('/blog/') ?>">
                    <h5><i class="far fa-newspaper"></i> <?= __('Read our latest news') ?></h5>
                </a>
            </div>
        <?php endif ?>

        <?= $this->element('Structure/social_networks_tiles') ?>
    </div>
</div>

<div class="row partners-row before-footer">
    <div class="column"><a href="https://www.lafrenchtech.com/" target="_blank" class="item"><img alt="Partner French Tech" src="<?= $this->Url->build('/img/partners/french-tech.png') ?>"></a></div>
    <div class="column"><a href="https://ledenicheur.fr/?ref=61490" target="_blank" class="item"><img alt="Partner LeDenicheur" src="<?= $this->Url->build('/img/partners/ledenicheur.png') ?>"></a></div>
    <div class="column"><a href="https://www.twitch.tv/" target="_blank" class="item"><img alt="Partner Twitch" src="<?= $this->Url->build('/img/partners/twitch-white.png') ?>"></a></div>
    <div class="column"><a href="https://geek-mexicain.net/" target="_blank" class="item"><img alt="Partner Geek Mexicain" src="<?= $this->Url->build('/img/partners/geekmexicain.png') ?>"></a></div>
</div>