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
        <?= $this->fetch('title') ?> | MySetup
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('normalize.css') ?>
    <?= $this->Html->css('milligram.min.css') ?>
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('slick.css') ?>
    <?= $this->Html->css('lity.min.css') ?>
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
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
                        <?php if($this->request->session()->read('Auth.User.id') != null): ?>
                            <li>
                                <a href="#add_setup_modal" data-lity><i class="fa fa-plus"></i> Add Setup</a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a>Categories <i class="fa fa-caret-down"></i></a>
                            <ul>
                                <li><a href="#">Most recent</a></li>
                                <li><a href="#">Most popular</a></li>
                                <li><a href="#">Most commented</a></li>
                            </ul>
                        </li>
                        <li>
                            <?php if($this->request->session()->read('Auth.User.id') != null): ?>
                                <a>Profile <i class="fa fa-caret-down"></i></a>
                                <ul>
                                    <li><a href="#">My Setups</a></li>
                                    <li><a href="#">Edit Profile</a></li>
                                    <li><a href="<?= $this->Url->build('/users/logout'); ?>">Logout</a></li>
                                </ul>
                            <?php else: ?>
                                <a href="<?= $this->Url->build('/users/login'); ?>"><i class="fa fa-user"></i> Sign In / Up</a>
                            <?php endif; ?>
                        </li>
                    </ul>

                </div>

                <div id="add_setup_modal" class="lity-hide">
                    <?= $this->Form->create($newSetupEntity, ['type' => 'file', 'url' => ['controller' => 'Setups', 'action' => 'add']]); ?>
                    <fieldset style="border:0;">
                        <?php
                            echo $this->Form->control('title', ['id' => 'title']);
                            echo $this->Form->control('description', ['id' => 'textarea', 'rows' => 10, 'style' => 'width:100%']);
                        ?>
                        <input type="text" class="liveInput" onkeyup="searchItem(this.value);" placeholder="Search for components..">
                        <ul class="search_results"></ul>
                        <ul class="basket_items"></ul>
                        <br />
                        <?php
                            echo $this->Form->input('featuredImage. ', ['type' => 'file', 'label' => array('class' => 'label_fimage','text' => 'Add featured image'), 'class' => 'inputfile']);
                        ?>
                        <img id="featuredimage_preview">
                        <?php
                            echo $this->Form->input('fileselect. ', ['type' => 'file', 'multiple', 'label' => array('class' => 'label_gimage','text' => 'Add up to 5 images (5 MB / images)'), 'class' => 'inputfile']);
                        ?>
                        <div id="images_holder"></div>
                        <br />
                        <?php
                            echo $this->Form->control('video');

                            // A hidden entry to gather the item resources
                            echo $this->Form->control('resources', ['class' => 'hiddenInput', 'type' => 'hidden']);
                        ?>
                        <a class="is_author"><i class="fa fa-square-o"></i> I'm not the owner of this setup !</a>
                        <label for="author" class="setup_author">Author of the setup</label>
                        <?php
                            echo $this->Form->control('author', ['class' => 'setup_author', 'label' => '']);
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
                    <?php if($this->request->session()->read('Auth.User.id') != null): ?>
                        <li>
                            <a href="#add_setup_modal" data-lity><i class="fa fa-plus"></i> Add Setup</a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <span>Categories</span>
                        <ul>
                            <li><a href="#">Most recent</a></li>
                            <li><a href="#">Most popular</a></li>
                            <li><a href="#">Most commented</a></li>
                        </ul>
                    </li>
                    <li>
                        <?php if($this->request->session()->read('Auth.User.id') != null): ?>
                            <span>Profile</span>
                            <ul>
                                <li><a href="#">My Setups</a></li>
                                <li><a href="#">Edit Profile</a></li>
                                <li><a href="<?= $this->Url->build('/users/logout'); ?>">Logout</a></li>
                            </ul>
                        <?php else: ?>
                            <a href="<?= $this->Url->build('/users/login'); ?>">Login</a>
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
                      <div class="footer-title">Partenaires</div>
                      <ul>
                        <li><a href="https://pixelswap.fr/" target="_blank" class="item">PixelSwap</a></li>
                        <li><a href="https://geek-mexicain.net/" target="_blank">Geek Mexicain</a></li>
                        <li><a href="https://horlogeskynet.github.io/" target="_blank" class="item">HorlogeSkynet</a></li>
                      </ul>
                    </div>
                    <div class="column column-25">
                      <div class="footer-title">About us</div>
                      <ul>
                        <li><a href="#" target="_blank" class="item">Our story</a></li>
                        <li><a href="#" target="_blank">Our team</a></li>
                        <li><a href="#" target="_blank">Legals</a></li>
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
                        <?php echo $this->Html->image('logo_footer.svg', array('alt' => 'mySetup')); ?>
                        <p>All rights reserved mySetup.co © 2017</p>
                    </div>
              </div>
          </div>
    </footer>


    <!-- Jquery async load -->
    <?= $this->Html->script('jquery-3.2.0.min.js') ?>

    <!-- Slider Js async load -->
    <?= $this->Html->script('slick.min.js') ?>

    <!-- Lightbox Js async load -->
    <?= $this->Html->script('lity.min.js') ?>

    <!-- App Js async load -->
    <?= $this->Html->script('app.js') ?>

    <?= $this->Flash->render() ?>

    <?= $this->fetch('scriptBottom') ?>

</body>
</html>
