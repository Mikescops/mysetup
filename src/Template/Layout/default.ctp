<?php
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?= $lang ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?= $lang ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?= $lang ?>"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="<?= $lang ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>

    <?= $this->Html->css('main.min.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?= $this->Html->meta(['link' => '/img/favicon/apple-touch-icon.png', 'rel' => 'apple-touch-icon', 'sizes' => '180x180']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/favicon-32x32.png', 'rel' => 'icon', 'type' => 'image/png', 'sizes' => '32x32']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/favicon-16x16.png', 'rel' => 'icon', 'type' => 'image/png', 'sizes' => '16x16']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/manifest.json', 'rel' => 'manifest']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/safari-pinned-tab.svg', 'rel' => 'mask-icon', 'color' => '#151515']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/favicon.ico', 'rel' => 'shortcut icon']) ?>

    <meta name="apple-mobile-web-app-title" content="mySetup.co">
    <meta name="application-name" content="mySetup.co">
    <?= $this->Html->meta(['link' => '/img/favicon/browserconfig.xml', 'name' => 'msapplication-config']) ?>
    <meta name="theme-color" content="#151515">

    <?php
        echo $this->Html->meta(array('rel' => 'alternate', 'hreflang' => 'x-default', 'href' => $this->Url->build(null, true) ));
        echo $this->Html->meta(array('rel' => 'alternate', 'hreflang' => 'fr', 'href' => $this->Url->build(null, true)."?lang=fr"));
        echo $this->Html->meta(array('rel' => 'alternate', 'hreflang' => 'en', 'href' => $this->Url->build(null, true)."?lang=us"));
        echo $this->Html->meta(array('rel' => 'alternate', 'hreflang' => 'es', 'href' => $this->Url->build(null, true)."?lang=es"));
        echo $this->Html->meta(array('rel' => 'alternate', 'hreflang' => 'it', 'href' => $this->Url->build(null, true)."?lang=it"));
        echo $this->Html->meta(array('rel' => 'alternate', 'hreflang' => 'de', 'href' => $this->Url->build(null, true)."?lang=de"));
    ?>

    <meta name="twitter:card" value="summary_large_image">
    <meta property="og:type" content="article" />
    <meta name="twitter:site" content="@mysetup_co">
    <meta property="og:site_name" content="mySetup.co" />
    <meta property="fb:admins" content="1912097312403661" />
    <meta name="google-site-verification" content="8eCzlQ585iC5IG3a4-fENYChl1AaEUaW7VeBj2NiFJQ" />

</head>
<body>
    <nav class="heavy-nav">
        <div class="row container">
            <div class="column column-20">

                <a href="<?= $this->Url->build('/', true); ?>"><?php echo $this->Html->image('mySetup_logo.svg', array('alt' => 'mySetup', 'class' => 'ms-logo', 'height' => '100%')); ?></a>

            </div>
            <div class="column column-80">
                <div class="right-nav">

                    <ul>
                        <?php if($authUser): ?>
                            <li>
                                <a id="notifications-trigger"><i class="fa fa-bell-o fa-fw" aria-hidden="true"></i></a>
                            </li>
                            <li>
                                <a id="menu_trigger_add_modal" href="#add_setup_modal" data-lity><?= __('Add Setup') ?></a>
                            </li>
                            <?php if($authUser['admin']): ?>
                                <li>
                                    <a href="<?= $this->Url->build('/admin'); ?>"><?= __('Admin Panel') ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <li>
                            <a><?= __('Categories') ?> <i class="fa fa-caret-down"></i></a>
                            <ul>
                                <li><a href="<?= $this->Url->build('/recent'); ?>"><?= __('Most recent') ?></a></li>
                            </ul>
                        </li>
                        <?php if($authUser): ?>
                        <li style="margin-right: 19px;">
                                <a class="navbar-user"><?= h($authUser['name']) ?> <img class="current-profile-user" alt="<?= __('Profile picture of') ?> <?= h($authUser['name']) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $authUser['id'] . '.png?' . $authUser['modificationDate']->format('is')) ?>"></a>
                                <ul style="left: auto;right: -20px;">
                                    <li><a href="<?=$this->Url->build('/likes')?>"><?= __('My Likes') ?></a></li>
                                    <li><a href="<?=$this->Url->build('/users/'. $authUser['id'])?>"><?= __('My Setups') ?></a></li>
                                    <li><a href="#edit_profile_modal" data-lity><?= __('Edit Profile') ?></a></li>
                                    <li><a href="<?= $this->Url->build('/logout'); ?>"><?= __('Logout') ?></a></li>
                                </ul>

                                <?= $this->element('Modal/edit-profile') ?>

                            </li>
                            <?php else: ?>
                            <li>
                                <a><i class="fa fa-user"></i> <i class="fa fa-caret-down"></i></a>
                                <ul style="right: 0;left: auto;text-align: right;">
                                    <li><a href="<?= $this->Url->build('/login'); ?>"><?= __('Sign In / Up') ?></a></li>
                                    <li><a href="<?= $this->Url->build('/pages/q&a')?>"><?= __('Help - Q&amp;A') ?></a></li>
                                </ul>
                            </li>
                            <li><a class="twitch-login" onclick="logTwitch('<?= $lang ?>')"><?= __('Connect with') ?> <i class="fa fa-twitch"></i> </a></li>
                            <?php endif; ?>

                    </ul>

                </div>

                <?php if($authUser): ?>

                    <?= $this->element('Modal/add-setup') ?>

                <?php endif; ?>

            </div>

            <div class="mobile-nav float-right">
                <a href="#mobile-nav" data-lity><i class="fa fa-bars fa-4"></i></a>
            </div>

            <div id="mobile-nav" class="lity-hide">

                <ul>
                    <?php if($authUser): ?>
                        <li>
                            <a href="#add_setup_modal" data-lity><i class="fa fa-plus"></i> <?= __('Add Setup') ?></a>
                        </li>
                        <?php if($authUser['admin']): ?>
                            <li>
                                <a href="<?= $this->Url->build('/admin'); ?>"><?= __('Admin Panel') ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li>
                        <ul>
                            <li><a href="<?= $this->Url->build('/recent'); ?>"><?= __('Most recent') ?></a></li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <?php if($authUser): ?>
                                <li><a href="<?=$this->Url->build('/likes')?>"><?= __('My Likes') ?></a></li>
                                <li><a href="<?=$this->Url->build('/users/'. $authUser['id'])?>"><?= __('My Setups') ?></a></li>
                                <li><a href="#edit_profile_modal" data-lity><?= __('Edit Profile') ?></a></li>
                                <li><a href="<?= $this->Url->build('/logout'); ?>"><?= __('Logout') ?></a></li>
                            <?php else: ?>
                                <li><a href="<?= $this->Url->build('/login'); ?>"><?= __('Log in') ?></a></li>
                                <li><a onclick="logTwitch('<?= $lang ?>')"><?= __('Connect with') ?> <i class="fa fa-twitch"></i></a></li>
                                <li><a href="<?=$this->Url->build('/pages/q&a')?>">Help - Q&amp;A</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>

            </div>

        </div>
    </nav>

    <?= $this->fetch('content') ?>

    <?php if(!$authUser && $this->request->url != 'login'): ?>

        <section class="colored-box before-footer calltosignin">
            <svg class="decorative-divider" preserveAspectRatio="none" viewBox="0 0 100 100">
                <polygon points="0 0 100 0 100 100" fill="#151515"></polygon>
            </svg>
            <div class="container">
                <div class="row">
                    <div class="column">
                        <h3><?= __('Join us and start building your setup') ?> <i class="fa fa-diamond"></i></h3>
                        <a href="<?=$this->Url->build('/login')?>" class="hero_calltoaction"><?= __('Sign me in !') ?></a>
                    </div>
                </div>
            </div>
        </section>

    <?php endif; ?>

    <footer>
        <div class="row partners-row">
            <div class="column"><a href="https://ledenicheur.fr/?ref=61490" target="_blank" class="item"><img alt="Partner LeDenicheur" src="<?=$this->Url->build('/img/partners/ledenicheur.png')?>"></a></div>
            <div class="column"><a href="https://www.twitch.tv/" target="_blank" class="item"><img alt="Partner Twitch" src="<?=$this->Url->build('/img/partners/twitch-white.png')?>"></a></div>
            <div class="column"><a href="https://geek-mexicain.net/" target="_blank" class="item"><img alt="Partner Geek Mexicain" src="<?=$this->Url->build('/img/partners/geekmexicain.png')?>"></a></div>
            <div class="column"><a href="https://www.ikoula.com/" target="_blank" class="item"><img alt="Partner Ikoula" src="<?=$this->Url->build('/img/partners/ikoula-white.png')?>"></a></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="column column-25">
                    <div class="footer-title"><?= __('About us') ?></div>
                    <ul>
                        <li><a href="<?=$this->Url->build('/blog/')?>" class="item"><?= __('Our Blog') ?></a></li>
                        <li><a href="<?= $this->Url->build('/pages/team'); ?>"><?= __('Our Team') ?></a></li>
                        <li><a href="<?= $this->Url->build('/pages/legals'); ?>"><?= __('Legal Mentions') ?></a></li>
                    </ul>
                </div>
                <div class="column column-25">
                    <div class="footer-title"><?= __('Support') ?></div>
                    <ul>
                        <li><a href="<?=$this->Url->build('/pages/q&a')?>"><?= __('Help - Q&amp;A') ?></a></li>
                        <li><a href="<?= $this->Url->build('/bugReport') ?>"><?= __('Report a bug') ?></a></li>
                    </ul>
                </div>
                <div class="column column-25">
                </div>
                <div class="column column-25 logo_footer">
                    <?php echo $this->Html->image('logo_footer.svg', array('alt' => 'mysetup.co')); ?>
                    <p><?= __('All rights reserved') ?> – mySetup.co<br> © 2017 - 2018</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const webRootJs = "<?= $this->Url->build('/', true); ?>";
        const twitchClientId = "<?= Configure::read('Credentials.Twitch.id'); ?>";
    </script>

    <div id="notifications-pop" style="display: none;"><div id="notif-container"></div><div id="no-notif">You have no notifications.</div></div>

    <!-- App Js async load -->
    <?= $this->Html->script('app.min.js') ?>

    <!-- Emoji handling -->
    <?php if($authUser): ?>
        <?= $this->Html->script('emojiarea.min.js') ?>
    <?php endif; ?>

    <script>const toast = new siiimpleToast();</script>
    <?php if($authUser): ?>
        <script>const instance = new tippy('#notifications-trigger', {html: '#notifications-pop',sticky: false,flipDuration:0,position:'bottom',arrow: true,appendTo: document.body,trigger: 'click',interactive: true,animation: 'fade',hideOnClick: false,performance: true});const notificationcenter = instance.getPopperElement(document.querySelector('#notifications-trigger'));checknotification();tippy('.button.draft');tippy('.setup-unpublished');tippy('.setup-rejected');tippy('.setup-default');tippy('.setup-star');</script>
    <?php endif ?>

    <?= $this->Flash->render() ?>

    <?= $this->fetch('scriptBottom') ?>

    <!-- Analytics -->
    <script type="text/javascript">var _paq=_paq||[];_paq.push(['trackPageView']);_paq.push(['enableLinkTracking']);(function(){var u="//analytics.geek-mexicain.net/";_paq.push(['setTrackerUrl',u+'piwik.php']);_paq.push(['setSiteId','2']);var d=document,g=d.createElement('script'),s=d.getElementsByTagName('script')[0];g.type='text/javascript';g.async=!0;g.defer=!0;g.src=u+'piwik.js';s.parentNode.insertBefore(g,s)})();</script>
    <noscript><p><img src="//analytics.geek-mexicain.net/piwik.php?idsite=2&rec=1" style="border:0;" alt="" /></p></noscript>

</body>
</html>
