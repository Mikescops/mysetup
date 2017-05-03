<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="fr-FR"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="fr-FR"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="fr-FR"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html lang="fr_FR">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <?= $this->Html->css('app.min.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <link rel="apple-touch-icon" sizes="180x180" href="<?= $this->Url->build('/'); ?>img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $this->Url->build('/'); ?>img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $this->Url->build('/'); ?>img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= $this->Url->build('/'); ?>img/favicon/manifest.json">
    <link rel="mask-icon" href="<?= $this->Url->build('/'); ?>img/favicon/safari-pinned-tab.svg" color="#328fea">
    <link rel="shortcut icon" href="<?= $this->Url->build('/'); ?>img/favicon/favicon.ico">
    <meta name="msapplication-config" content="<?= $this->Url->build('/'); ?>img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#151515">

    <meta name="twitter:card" value="summary"> 
    <meta property="og:type" content="article" />
    <meta name="twitter:site" content="@mysetup_co">
    <meta property="og:site_name" content="mySetup.co" />
    <meta property="fb:admins" content="1912097312403661" /> 

    <meta name="google-site-verification" content="8eCzlQ585iC5IG3a4-fENYChl1AaEUaW7VeBj2NiFJQ" />
</head>
<body>
    <nav class="heavy-nav">
        <div class="row">
            <div class="column column-20">
                
                <a href="<?= $this->Url->build('/', true); ?>"><?php echo $this->Html->image('mySetup_logo.svg', array('alt' => 'mySetup')); ?></a>

            </div>
            <div class="column column-80">
                <div class="right-nav">
                    
                    <ul>
                        <?php if($authUser): ?>
                            <li>
                                <a href="#add_setup_modal" data-lity><i class="fa fa-plus"></i> Add Setup</a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a>Categories <i class="fa fa-caret-down"></i></a>
                            <ul>
                                <li><a href="<?= $this->Url->build('/recents'); ?>">Most recent</a></li>
                                <li><a href="<?= $this->Url->build('/popular'); ?>">Popular this week</a></li>
                            </ul>
                        </li>
                        <li>
                            <?php if($authUser): ?>
                                <a>Profile <i class="fa fa-caret-down"></i></a>
                                <ul>
                                    <li><a href="<?=$this->Url->build('/users/'. $authUser['id'])?>">My Setups</a></li>
                                    <li><a href="#edit_profile_modal" data-lity>Edit Profile</a></li>
                                    <li><a href="<?= $this->Url->build('/logout'); ?>">Logout</a></li>
                                </ul>

                                <div id="edit_profile_modal" class="lity-hide">
                                    <?= $this->Form->create(null, ['type' => 'file', 'url' => ['controller' => 'Users', 'action' => 'edit', $authUser['id']]]); ?>
                                    <fieldset style="border:0;">
                                    <h4>Change only what you want !</h4>
                                    <div class="row">
                                    <div class="column column-25">
                                    <div class="profile-container">
                                       <image id="profileImage" src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $authUser['id'] ?>.png" />
                                    </div>

                                    <div class="profilepicup">
                                        <?php
                                        echo $this->Form->input('picture. ', ['label' => __("Change my profile picture"), 'type' => 'file', 'class' => 'inputfile', 'id' => 'profileUpload']);
                                        ?>
                                    </div>

                                    <br>

                                    <?php
                                        echo $this->Form->select('preferredStore', ["US" => "US", "UK" => "UK", "ES" => "ES", "IT" => "IT", "FR" => "FR", "DE" => "DE"], ['default' => $authUser['preferredStore']]);
                                        ?>
                                    </div>
                                    <div class="column column-75">
                                    <?php
                                        echo $this->Form->control('name', ['required' => true, 'label' => '', 'placeholder' => __("Name"), 'default' => $authUser['name']]);
                                        echo $this->Form->control('mail', ['required' => true, 'type' => 'email', 'label' => '', 'placeholder' => __("Email address"), 'default' => $authUser['mail']]);
                                    ?>

                                     <?php
                                        echo $this->Form->control('secret', ['type' => 'password', 'placeholder' => __("Password"), 'class' => 'pwd_field', 'label' => '']);
                                        echo $this->Form->control('secret2', ['type' => 'password', 'placeholder' => __("Confirm password"), 'class' => 'pwd_field', 'label' => '']);
                                    ?>
                                    <a class="reset_pwd float-right"><i class="fa fa-repeat"></i> Change my password</a>

                                    </div>

                                    </div>
                                    
                                    </fieldset>
                                    <?= $this->Form->submit(__('Submit'), ['class' => 'float-right']); ?>
                                    <?= $this->Form->end(); ?>

                                    <?= $this->Form->postLink('Delete my account', array('controller' => 'Users','action' => 'delete', $authUser['id']),array('confirm' => 'You are going to delete your account and all his content! Are You Sure?')) ?>
                                </div>

                            <?php else: ?>
                                <a href="<?= $this->Url->build('/login'); ?>"><i class="fa fa-user"></i> Sign In / Up</a>
                            <?php endif; ?>
                        </li>
                    </ul>

                </div>

                <div id="add_setup_modal" class="lity-hide">
                    <?= $this->Form->create($newSetupEntity, ['type' => 'file', 'url' => ['controller' => 'Setups', 'action' => 'add']]); ?>
                    <fieldset style="border:0;">
                        <?php
                            echo $this->Form->control('title', ['label' => __('Title *'), 'required' => true, 'id' => 'title', 'maxLength' => 48]);
                            echo $this->Form->control('description', ['label' => __('Description'), 'id' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxLength' => 500]);
                        ?>
                        <input type="text" class="liveInput add_setup" onkeyup="searchItem(this.value, '<?= $authUser['preferredStore'] ?>','add_setup');" placeholder="Search for components..">
                        <ul class="search_results add_setup"></ul>
                        <ul class="basket_items add_setup"></ul>
                        <br />
                        <?php
                            echo $this->Form->input('featuredImage. ', ['required' => true, 'type' => 'file', 'label' => array('class' => 'label_fimage','text' => __('Add featured image *')), 'class' => 'inputfile']);
                        ?>
                        <img id="featuredimage_preview">
                        <?php
                            echo $this->Form->input('fileselect. ', ['type' => 'file', 'multiple', 'label' => array('class' => 'label_gimage','text' => 'Add up to 5 images (5 MB / images)'), 'class' => 'inputfile']);
                        ?>
                        <div id="images_holder"></div>
                        <br />
                        <?php
                            echo $this->Form->control('video', ['label' => __('Video (Youtube, Dailymotion...)')]);

                            // A hidden entry to gather the item resources
                            echo $this->Form->control('resources', ['class' => 'hiddenInput add_setup', 'type' => 'hidden']);
                        ?>
                        <a class="is_author"><i class="fa fa-square-o"></i> It's not my setup !</a>
                        <p class="float-right">* required fields</p>
                        <label for="author" class="setup_author">Setup's owner</label>
                        <?php
                            echo $this->Form->control(__('author'), ['class' => 'setup_author', 'label' => '']);
                        ?>
                    </fieldset>
                    <?= $this->Form->submit(__('Submit'), ['class' => 'float-right']); ?>
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
                            <a href="#add_setup_modal" data-lity><i class="fa fa-plus"></i> Add Setup</a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <ul>
                            <li><a href="<?= $this->Url->build('/recents'); ?>">Most recent</a></li>
                            <li><a href="<?= $this->Url->build('/popular'); ?>">Popular this week</a></li>
                        </ul>
                    </li>
                    <li>
                        <?php if($authUser): ?>
                            <ul>
                                <li><a href="<?=$this->Url->build('/users/'. $authUser['id'])?>">My Setups</a></li>
                                <li><a href="#edit_profile_modal" data-lity>Edit Profile</a></li>
                                <li><a href="<?= $this->Url->build('/logout'); ?>">Logout</a></li>
                            </ul>
                        <?php else: ?>
                            <a href="<?= $this->Url->build('/login'); ?>">Login</a>
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
                      <div class="footer-title">Partners</div>
                      <ul>
                        <li><a href="https://pixelswap.fr/" target="_blank" class="item">PixelSwap</a></li>
                        <li><a href="https://geek-mexicain.net/" target="_blank">Geek Mexicain</a></li>
                        <li><a href="https://horlogeskynet.github.io/" target="_blank" class="item">HorlogeSkynet</a></li>
                      </ul>
                    </div>
                    <div class="column column-25">
                      <div class="footer-title">About us</div>
                      <ul>
                        <li><a href="https://medium.com/mysetup-co" target="_blank" class="item">Our stories</a></li>
                        <li><a href="<?= $this->Url->build('/pages/team'); ?>" target="_blank">Our team</a></li>
                        <li><a href="<?= $this->Url->build('/pages/legals'); ?>" target="_blank">Legal Mentions</a></li>
                      </ul>
                    </div>
                    <div class="column column-25">
                      <div class="footer-title">Social</div>
                      <ul>
                        <li><a href="https://twitter.com/mysetup_co" target="_blank" class="item">Twitter</a></li>
                        <li><a href="https://www.facebook.com/mysetup.co" target="_blank">Facebook</a></li>
                        <li><a href="https://geeks.one/@mysetup_co" target="_blank">Mastodon</a></li>
                      </ul>
                    </div>
                    <div class="column column-25 logo_footer">
                        <?php echo $this->Html->image('logo_footer.svg', array('alt' => 'mysetup.co')); ?>
                        <p>All rights reserved – mysetup.co<br> © 2017</p>
                    </div>
              </div>
          </div>

        <script>var webRootJs = "<?= $this->Url->build('/'); ?>";</script>

        <!-- Jquery async load -->
        <?= $this->Html->script('jquery-3.2.0.min.js') ?>

        <!-- Slider Js async load -->
        <?= $this->Html->script('slick.min.js') ?>

        <!-- Lightbox Js async load -->
        <?= $this->Html->script('lity.min.js') ?>

        <!-- Amazon Query Js async load -->
        <?= $this->Html->script('amazon-autocomplete.js') ?>

        <?= $this->Html->script('jssocials.min.js') ?>

        <!-- App Js async load -->
        <?= $this->Html->script('app.min.js') ?>

        <script> /* Define toast once */const toast = new siiimpleToast();</script>

        <?= $this->Flash->render() ?>

        <?= $this->fetch('scriptBottom') ?>
    </footer>

</body>
</html>
