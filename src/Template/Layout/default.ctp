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

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <?= $this->Html->css('app.min.css') ?>
    <?= $this->Html->css('emoji.min.css') ?>
    <?= $this->Html->css('tippy.css') ?>

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

    <meta name="twitter:card" value="summary">
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
                                <a href="#add_setup_modal" data-lity><?= __('Add Setup') ?></a>
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
                                <li><a href="<?= $this->Url->build('/popular'); ?>"><?= __('Popular this week') ?></a></li>
                            </ul>
                        </li>
                        <?php if($authUser): ?>
                        <li style="margin-right: 19px;">
                                <a class="navbar-user"><?= h($authUser['name']) ?> <img class="current-profile-user" alt="<?= __('Profile picture of') ?> <?= $authUser['name'] ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $authUser['id'] . '.png?' . $authUser['modificationDate']->format('is')) ?>"></a>
                                <ul style="left: auto;right: -20px;">
                                    <li><a href="<?=$this->Url->build('/likes')?>"><?= __('My Likes') ?></a></li>
                                    <li><a href="<?=$this->Url->build('/users/'. $authUser['id'])?>"><?= __('My Setups') ?></a></li>
                                    <li><a href="#edit_profile_modal" data-lity><?= __('Edit Profile') ?></a></li>
                                    <li><a href="<?= $this->Url->build('/logout'); ?>"><?= __('Logout') ?></a></li>
                                </ul>

                                <div id="edit_profile_modal" class="lity-hide">
                                    <?= $this->Form->create(null, ['type' => 'file', 'url' => ['controller' => 'Users', 'action' => 'edit', $authUser['id']]]); ?>
                                    <fieldset style="border:0;">
                                    <h4><?= __('Change only what you want !') ?></h4>
                                    <div class="row">
                                    <div class="column column-25">
                                    <div class="profile-container">
                                       <img id="profileImage" alt="<?= __('Profile picture of') ?> <?= $authUser['name'] ?>" src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $authUser['id'] ?>.png?<?= $authUser['modificationDate']->format('is') ?>" />
                                    </div>

                                    <div class="profilepicup">
                                        <?php
                                            echo $this->Form->control('picture', ['type' => 'file', 'label' => __("Change my profile picture"), 'class' => 'inputfile', 'id' => 'profileUpload']);
                                        ?>
                                    </div>

                                    <br>

                                    <?php
                                        echo $this->Form->select('preferredStore', ["US" => "US", "UK" => "UK", "ES" => "ES", "IT" => "IT", "FR" => "FR", "DE" => "DE"], ['default' => $authUser['preferredStore']]);
                                        echo $this->Form->select('timeZone', $timezones, ['default' => $authUser['timeZone']]);
                                    ?>
                                    </div>
                                    <div class="column column-75">
                                        <?php
                                            echo $this->Form->control('name', ['required' => true, 'label' => ['text' => '', 'class' => 'fa fa-user'], 'placeholder' => __("Name"), 'default' => $authUser['name']]);
                                            echo $this->Form->control('mail', ['disabled' => true, 'type' => 'email', 'label' => ['text' => '', 'class' => 'fa fa-envelope'], 'placeholder' => __("Email address"), 'default' => $authUser['mail']]);
                                        ?>

                                        <?php
                                            echo $this->Form->control('uwebsite', ['label' => ['text' => '', 'class' => 'fa fa-globe'], 'placeholder' => "https://website.me", 'default' => $authUser['uwebsite']]);
                                            echo $this->Form->control('ufacebook', ['label' => ['text' => '', 'class' => 'fa fa-facebook'], 'placeholder' => "https://facebook.com/me", 'default' => $authUser['ufacebook']]);
                                            echo $this->Form->control('utwitter', ['label' => ['text' => '', 'class' => 'fa fa-twitter'], 'placeholder' => "https://twitter.com/me", 'default' => $authUser['utwitter']]);
                                            echo $this->Form->control('utwitch', ['label' => ['text' => '', 'class' => 'fa fa-twitch'], 'placeholder' => "https://go.twitch.tv/me", 'default' => $authUser['utwitch']]);
                                        ?>

                                        <span><?= __('Choose your main setup : ') ?></span>
                                        <?php
                                            echo $this->Form->select('mainSetup_id', $setupsList, ['default' => $authUser['mainSetup_id'], 'class' => 'form-control']);
                                        ?>

                                        <?php
                                            echo $this->Form->control('secret', ['pattern' => '.{8,}', 'type' => 'password', 'placeholder' => __("Password"), 'class' => 'pwd_field', 'label' => '']);
                                            echo $this->Form->control('secret2', ['type' => 'password', 'placeholder' => __("Confirm password"), 'class' => 'pwd_field', 'label' => '']);
                                        ?>
                                        <a class="reset_pwd float-right"><i class="fa fa-repeat"></i> <?= __('Change my password') ?></a>
                                    </div>
                                    </div>

                                    </fieldset>
                                    <?= $this->Form->submit(__('Submit'), ['class' => 'float-right']); ?>
                                    <?= $this->Form->end(); ?>

                                    <?= $this->Form->postLink(__('Delete my account'), array('controller' => 'Users','action' => 'delete', $authUser['id']),array('confirm' => 'You are going to delete your account and all its content (profile, setups, comments, likes) ! Are you sure ?')) ?>
                                </div>
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

                <div id="add_setup_modal" class="lity-hide">
                    <?= $this->Form->create($newSetupEntity, ['type' => 'file', 'url' => ['controller' => 'Setups', 'action' => 'add']]); ?>
                    <fieldset style="border:0;">

                        <div class="add-form">
                        <ul class="tabs">
                            <li>
                                <a id="basics-tab" href="#basics" class="active"><?= __('Basics') ?></a>
                            </li>
                            <li>
                                <a id="components-tab" href="#components"><?= __('Components') ?></a>
                            </li>
                            <li>
                                <a id="infos-tab" href="#infos"><?= __('More infos') ?></a>
                            </li>
                        </ul>
                        <div id="basics" class="form-action show">

                            <?php
                                echo $this->Form->control('title', ['label' => __('Title *'), 'id' => 'title', 'maxlength' => 48, 'required' => 'true']);
                                echo $this->Form->control('description', ['label' => __('Description'), 'id' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxlength' => 5000, 'placeholder' => __('What is the purpose of your setup ? Tell us your setup\'s story...')]);
                            ?>
                            <span class="float-right link-marksupp"><a target="_blank" href="<?=$this->Url->build('/pages/q&a#q-6')?>"><i class="fa fa-info-circle"></i> Markdown supported</a></span>
                            <br>
                            <?php
                                echo $this->Form->control('featuredImage', ['type' => 'file', 'label' => ['class' => 'label_fimage label_fimage_add', 'text' => __('Click to add a featured image *')], 'class' => 'inputfile', 'required' => 'true']);
                            ?>
                            <img id="featuredimage_preview">
                            <div class="hidden_five_inputs">
                                <?php
                                    echo $this->Form->control('gallery0', ['id' => 'gallery0add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                    echo $this->Form->control('gallery1', ['id' => 'gallery1add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                    echo $this->Form->control('gallery2', ['id' => 'gallery2add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                    echo $this->Form->control('gallery3', ['id' => 'gallery3add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                    echo $this->Form->control('gallery4', ['id' => 'gallery4add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                ?>
                            </div>

                            <div class="gallery-holder homide">
                            <?php for($i = 0; $i < 5; $i++): ?>
                                <img alt="<?= __('Gallery Preview') ?>" title="<?= __('Add gallery image') ?>" class="gallery_add_preview" id="gallery<?= $i ?>image_preview_add" src="<?= $this->Url->build('/img/add_gallery_default.png')?>">
                            <?php endfor ?>
                            </div>

                            <span class="float-right">* <?= __('required fields') ?></span>
                            <br/>

                            <div class="modal-footer">
                                <a href="#components" class="button next float-right"><?= __('Next step') ?></a>

                                <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>
                            </div>

                        </div>

                        <div id="components" class="form-action hide">

                            <input type="text" class="liveInput add_setup" onkeyup="searchItem(this.value, '<?= $authUser['preferredStore'] ?>','add_setup');" placeholder="<?= __('Search for components...') ?>">
                            <ul class="search_results add_setup"></ul>
                            <ul class="basket_items add_setup"></ul>

                            <div class="modal-footer">

                                <a href="#infos" class="button next float-right"><?= __('Next step') ?></a>
                                <a href="#basics" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                                <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>

                            </div>

                        </div>

                        <div id="infos" class="form-action hide">

                            <?php
                                echo $this->Form->control('video', ['label' => __('Video (Youtube, Dailymotion, Twitch, ...)')]);

                                // A hidden entry to gather the item resources
                                echo $this->Form->control('resources', ['class' => 'hiddenInput add_setup', 'type' => 'hidden']);
                            ?>
                            <a class="is_author"><i class="fa fa-square-o"></i> <?= __("It's not my setup !") ?></a>
                            <label for="author" class="setup_author"><?= __("Setup's owner") ?></label>
                            <?php
                                echo $this->Form->control('author', ['class' => 'setup_author', 'label' => false]);

                                echo $this->Form->select('status', $status, ['id' => 'status-add', 'class' => 'hidden']);
                            ?>

                            <div class="modal-footer">

                                <?= $this->Form->submit(__('Publish'), ['class' => 'float-right button', 'id' => 'publish-add']); ?>
                                <a href="#components" class="button next float-right"><i class="fa fa-chevron-left"></i></a>
                                <a class="button draft float-left fa fa-file-text-o" title="<?= __('Save as draft') ?>" onclick="saveasdraftadd()"></a>

                            </div>

                        </div>

                        </div>

                    </fieldset>

                    <?= $this->Form->end(); ?>

                </div>

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
                            <li><a href="<?= $this->Url->build('/popular'); ?>"><?= __('Popular this week') ?></a></li>
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

    <footer>
        <div class="container">

            <div class="row">
                    <div class="column column-25">
                      <div class="footer-title"><?= __('Partners') ?></div>
                      <ul>
                        <li><a href="https://pixelswap.fr/" target="_blank" class="item">PixelSwap</a></li>
                        <li><a href="https://geek-mexicain.net/" target="_blank">Geek Mexicain</a></li>
                        <li><a href="https://horlogeskynet.github.io/" target="_blank" class="item">HorlogeSkynet</a></li>
                      </ul>
                    </div>
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
                    <div class="column column-25 logo_footer">
                        <?php echo $this->Html->image('logo_footer.svg', array('alt' => 'mysetup.co')); ?>
                        <p><?= __('All rights reserved') ?> – mysetup.co<br> © 2017 - 2018</p>
                    </div>
              </div>
          </div>
    </footer>

    <script>var webRootJs = "<?= $this->Url->build('/'); ?>";</script>

    <div id="notifications-pop" style="display: none;"><div id="notif-container"></div><div id="no-notif">You have no notifications.</div></div>

    <!-- Jquery async load -->
    <?= $this->Html->script('jquery-3.2.0.min.js') ?>
    <?= $this->Html->script('lib.min.js?v=2') ?>
    <?= $this->Html->script('tippy.min.js') ?>

    <!-- Emoji handling -->
    <?php if($authUser): ?>
        <?= $this->Html->script('emoji.min.js') ?>
    <?php endif; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/lib/js/emojione.min.js"></script>

    <!-- App Js async load -->
    <?= $this->Html->script('app.min.js') ?>
    <script>const toast = new siiimpleToast();</script>
    <?php if($authUser): ?>
        <script>const instance = new tippy('#notifications-trigger', {html: '#notifications-pop',sticky: false,flipDuration:0,position:'bottom',arrow: true,appendTo: document.body,trigger: 'click',interactive: true,animation: 'fade',hideOnClick: false,performance: true});const notificationcenter = instance.getPopperElement(document.querySelector('#notifications-trigger'));checknotification();tippy('.button.draft');tippy('.setup-unpublished');tippy('.setup-default');tippy('.setup-star');</script>
    <?php endif ?>

    <?= $this->Flash->render() ?>

    <?= $this->fetch('scriptBottom') ?>

    <!-- CookieConsent -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.4/cookieconsent.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.4/cookieconsent.min.js"></script>
    <script>window.addEventListener("load",function(){window.cookieconsent.initialise({"palette":{"popup":{"background":"#000"},"button":{"background":"#328fea"}},"theme":"classic","position":"bottom-left","content":{"href":"https://mysetup.co/pages/legals"}})});</script>

    <!-- Piwik -->
    <script type="text/javascript">
      var _paq = _paq || [];
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//analytics.geek-mexicain.net/";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', '2']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    <noscript><p><img src="//analytics.geek-mexicain.net/piwik.php?idsite=2&rec=1" style="border:0;" alt="" /></p></noscript>
    <!-- End Piwik Code -->

</body>
</html>
