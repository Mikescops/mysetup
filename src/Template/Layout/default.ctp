<!DOCTYPE html>
<?php
    if(!$lang)
    {
      $lang = ($authUser && $authUser['preferredStore'] !== "US" && $authUser['preferredStore'] !== "UK" ? strtolower($authUser['preferredStore']) : "en");
    }
?>
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

    <?= $this->Html->css('app.min.css?v=13') ?>
    <?= $this->Html->css('emoji.min.css') ?>
    <?= $this->Html->css('tippy.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <link rel="apple-touch-icon" sizes="180x180" href="<?= $this->Url->build('/'); ?>img/favicon/apple-touch-icon.png?v=LbGvygO5bN">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $this->Url->build('/'); ?>img/favicon/favicon-32x32.png?v=LbGvygO5bN">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $this->Url->build('/'); ?>img/favicon/favicon-16x16.png?v=LbGvygO5bN">
    <link rel="manifest" href="<?= $this->Url->build('/'); ?>img/favicon/manifest.json?v=LbGvygO5bN">
    <link rel="mask-icon" href="<?= $this->Url->build('/'); ?>img/favicon/safari-pinned-tab.svg?v=LbGvygO5bN" color="#151515">
    <link rel="shortcut icon" href="<?= $this->Url->build('/'); ?>img/favicon/favicon.ico?v=LbGvygO5bN">
    <meta name="apple-mobile-web-app-title" content="mySetup.co">
    <meta name="application-name" content="mySetup.co">
    <meta name="msapplication-config" content="<?= $this->Url->build('/'); ?>img/favicon/browserconfig.xml?v=LbGvygO5bN">
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
                
                <a href="<?= $this->Url->build('/', true); ?>"><?php echo $this->Html->image('mySetup_logo.svg?v=2', array('alt' => 'mySetup', 'class' => 'ms-logo')); ?></a>

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
                                <li><a href="<?= $this->Url->build('/recents'); ?>"><?= __('Most recent') ?></a></li>
                                <li><a href="<?= $this->Url->build('/popular'); ?>"><?= __('Popular this week') ?></a></li>
                            </ul>
                        </li>
                        <?php if($authUser): ?>
                        <li style="margin-right: 19px;">
                                <a class="navbar-user"><?= $authUser['name'] ?> <img class="current-profile-user" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$authUser['id'].'.png') ?>"></a>
                                <ul style="left: auto;right: -20px;">
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
                                       <img id="profileImage" src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $authUser['id'] ?>.png" />
                                    </div>

                                    <div class="profilepicup">
                                        <?php
                                        echo $this->Form->input('picture', ['label' => __("Change my profile picture"), 'type' => 'file', 'class' => 'inputfile', 'id' => 'profileUpload']);
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
                                            echo $this->Form->control('name', ['required' => true, 'label' => '', 'placeholder' => __("Name"), 'default' => $authUser['name']]);
                                            echo $this->Form->control('mail', ['required' => true, 'type' => 'email', 'label' => '', 'placeholder' => __("Email address"), 'default' => $authUser['mail']]);
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
                                <a class="twitch-login" onclick="logTwitch('<?= $lang ?>')"><?= __('Connect with') ?> <i class="fa fa-twitch"></i> </a></li>
                                <li>
                                    <a><i class="fa fa-user"></i> <i class="fa fa-caret-down"></i></a>
                                    <ul style="right: 0;left: auto;text-align: right;">
                                        <li><a href="<?= $this->Url->build('/login'); ?>"><?= __('Sign In / Up') ?></a></li>
                                        <li><a href="<?=$this->Url->build('/pages/q&a')?>">Help - Q&A</a></li>
                                    </ul>
                            </li>
                            <?php endif; ?>
                        
                    </ul>

                </div>

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
                                echo $this->Form->control('title', ['label' => __('Title *'), 'id' => 'title', 'maxLength' => 48, 'required' => 'true']);
                                echo $this->Form->control('description', ['label' => __('Description'), 'id' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxLength' => 5000, 'placeholder'=> 'What is the purpose of your setup ? Tell us your setup\'s story...']);
                            ?>
                            <span class="float-right link-marksupp"><a href="<?=$this->Url->build('/pages/q&a')?>"><i class="fa fa-info-circle"></i> Markdown supported</a></span>
                            <br>
                            <?php
                                echo $this->Form->input('featuredImage', ['type' => 'file', 'label' => array('class' => 'label_fimage label_fimage_add','text' => __('Click to add a featured image *')), 'class' => 'inputfile', 'required' => 'true']);
                            ?>
                            <img id="featuredimage_preview">
                            <div class="hidden_five_inputs">
                                <?php
                                    echo $this->Form->input('gallery0', ['id' => 'gallery0add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                    echo $this->Form->input('gallery1', ['id' => 'gallery1add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                    echo $this->Form->input('gallery2', ['id' => 'gallery2add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                    echo $this->Form->input('gallery3', ['id' => 'gallery3add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                    echo $this->Form->input('gallery4', ['id' => 'gallery4add', 'type' => 'file', 'hidden', 'class' => 'inputfile', 'label' => '']);
                                ?>
                            </div>

                            <div class="gallery-holder">
                            <?php for($i = 0; $i < 5; $i++): ?>
                                <img alt="<?= __('Gallery Preview') ?>" class="gallery_add_preview" id="gallery<?= $i ?>image_preview_add" src="<?= $this->Url->build('/img/add_gallery_default.png')?>">
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
                                echo $this->Form->control(__('author'), ['class' => 'setup_author', 'label' => false]);

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
                            <li><a href="<?= $this->Url->build('/recents'); ?>"><?= __('Most recent') ?></a></li>
                            <li><a href="<?= $this->Url->build('/popular'); ?>"><?= __('Popular this week') ?></a></li>
                        </ul>
                    </li>
                    <li>
                        <?php if($authUser): ?>
                            <ul>
                                <li><a href="<?=$this->Url->build('/users/'. $authUser['id'])?>"><?= __('My Setups') ?></a></li>
                                <li><a href="#edit_profile_modal" data-lity><?= __('Edit Profile') ?></a></li>
                                <li><a href="<?= $this->Url->build('/logout'); ?>"><?= __('Logout') ?></a></li>
                            </ul>
                        <?php else: ?>
                            <a href="<?= $this->Url->build('/login'); ?>"><?= __('Log in') ?></a>
                            <a onclick="logTwitch('<?= $lang ?>')"><?= __('Connect with') ?> <i class="fa fa-twitch"></i></a>
                            <li><a href="<?=$this->Url->build('/pages/q&a')?>">Help - Q&A</a></li>
                        <?php endif; ?>
                    </li>
                </ul>

            </div>

        </div>
    </nav>


    <div class="container sitecontainer">
        <?= $this->fetch('content') ?>
    </div>


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
                        <li><a href="<?=$this->Url->build('/blog/')?>" target="_blank" class="item"><?= __('Our Blog') ?></a></li>
                        <li><a href="<?= $this->Url->build('/pages/team'); ?>"><?= __('Our Team') ?></a></li>
                        <li><a href="<?= $this->Url->build('/pages/legals'); ?>"><?= __('Legal Mentions') ?></a></li>
                      </ul>
                    </div>
                    <div class="column column-25">
                      <div class="footer-title"><?= __('Support') ?></div>
                      <ul>
                        <li><a href="<?=$this->Url->build('/pages/q&a')?>"><?= __('Help - Q&A') ?></a></li>
                        <li><a href="mailto:support@mysetup.co"><?= __('Report a bug') ?></a></li>
                      </ul>
                    </div>
                    <div class="column column-25 logo_footer">
                        <?php echo $this->Html->image('logo_footer.svg', array('alt' => 'mysetup.co')); ?>
                        <p><?= __('All rights reserved') ?> – mysetup.co<br> © 2017</p>
                    </div>
              </div>
          </div>
    </footer>
</body>

<script>var webRootJs = "<?= $this->Url->build('/'); ?>";</script>

<div id="notifications-pop" style="display: none;"><div id="notif-container"></div><div id="no-notif">You have no notifications.</div></div>

<!-- Jquery async load -->
<?= $this->Html->script('jquery-3.2.0.min.js') ?>
<?= $this->Html->script('lib.min.js') ?>

<?= $this->Html->script('tippy.min.js') ?>

<?php if($authUser): ?>
    <?= $this->Html->script('emoji.min.js') ?>
<?php endif; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojione/2.1.4/lib/js/emojione.min.js"></script>

<!-- App Js async load -->
<?= $this->Html->script('app.min.js?v=15') ?>
<script>const toast = new siiimpleToast();</script>
<?php if($authUser): ?>
    <script>const instance = new Tippy('#notifications-trigger', {html: '#notifications-pop',arrow: true,trigger: 'click',interactive: true,animation: 'fade',hideOnClick: false});const popper = instance.getPopperElement(document.querySelector('#notifications-trigger'));checknotification(); Tippy('.button.draft'); Tippy('.setup-unpublished');</script>
<?php endif ?>

<?= $this->Flash->render() ?>

<?= $this->fetch('scriptBottom') ?>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
<script>window.addEventListener("load",function(){window.cookieconsent.initialise({"palette":{"popup":{"background":"#000"},"button":{"background":"#328fea"}},"theme":"classic","position":"bottom-left","content":{"href":"https://mysetup.co/pages/legals"}})});</script>
<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create','UA-98637133-1','auto');ga('send','pageview');</script>
</html>
