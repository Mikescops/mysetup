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
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('normalize.css') ?>
    <?= $this->Html->css('milligram.min.css') ?>
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('slick.css') ?>
    <?= $this->Html->css('lity.min.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="heavy-nav">
        <div class="row">
            <div class="column column-20">
                
                <a href="<?= $this->Url->build('/', true); ?>"><img src="img/mysetup_menu.png" alt="mySetup"></a>

            </div>
            <div class="column column-80">
                <div class="right-nav">
                    
                    <ul>
                        <li>
                            <a href="#add_setup_modal" data-lity><i class="fa fa-plus"></i> Add Setup</a>
                        </li>
                        <li>
                            <a>Categories <i class="fa fa-caret-down"></i></a>
                            <ul>
                                <li><a href="#">Most recent</a></li>
                                <li><a href="#">Most popular</a></li>
                                <li><a href="#">Most commented</a></li>
                            </ul>
                        </li>
                        <li>
                            <a>Profile <i class="fa fa-caret-down"></i></a>
                            <ul>
                                <li><a href="#">My Setups</a></li>
                                <li><a href="#">Edit Profile</a></li>
                                <li><a href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>

                </div>

                <div id="add_setup_modal" class="lity-hide">
                        <form>
                            <fieldset style="border:0;">
                                <ul style="list-style:none">
                                    <li>
                                        <label for="input">Title</label>
                                        <input type="text" required="" name="name" id="input">
                                    </li>
                                    <li>
                                        <label for="textarea" style="width:100%">Description</label>
                                        <textarea id="textarea" rows="10" style="width:100%"></textarea>
                                    </li>
                                    <li>
                                        <input type="text" id="myInput" onkeyup="" placeholder="Search for components..">
                                    </li>
                                    <li>
                                        <label for="fileselect">Images to upload:</label>
                                        <input type="file" id="fileselect" name="fileselect[]" multiple="multiple" />
                                        <div id="filedrag">or drop files here</div>

                                        <div id="messages"></div>
                                    </li>
                                        <input class="float-right" type="submit">
                                </ul>
                            </fieldset>
                        </form>
                </div>
            </div>

            <div class="mobile-nav float-right">
                <a href="#mobile-nav" data-lity><i class="fa fa-bars fa-4"></i></a>
            </div>

            <div id="mobile-nav" class="lity-hide">
                
                <ul>
                    <li>
                        <a href="#add_setup_modal" data-lity><i class="fa fa-plus"></i> Add Setup</a>
                    </li>
                    <li>
                        <span>Categories</span>
                        <ul>
                            <li><a href="#">Most recent</a></li>
                            <li><a href="#">Most popular</a></li>
                            <li><a href="#">Most commented</a></li>
                        </ul>
                    </li>
                    <li>
                        <span>Profile</span>
                        <ul>
                            <li><a href="#">My Setups</a></li>
                            <li><a href="#">Edit Profile</a></li>
                            <li><a href="#">Logout</a></li>
                        </ul>
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
                        <li><a href="http://pixelswap.fr/" target="_blank" class="item">PixelSwap</a></li>
                        <li><a href="http://geek-mexicain.net/" target="_blank">Geek-Mexicain</a></li>
                        <li><a href="https://horlogeskynet.github.io/" target="_blank" class="item">HorlogeSkynet</a></li>
                      </ul>
                    </div>
                    <div class="column column-25">
                      <div class="footer-title">About us</div>
                      <ul>
                        <li><a href="#" target="_blank" class="item">Github</a></li>
                        <li><a href="#" target="_blank">Bolt CMS</a></li>
                        <li><a href="#" target="_blank">Uzzy.me</a></li>
                      </ul>
                    </div>
                    <div class="column column-25">
                      <div class="footer-title">Social</div>
                      <ul>
                        <li><a href="https://twitter.com/mysetup" target="_blank" class="item">Twitter</a></li>
                        <li><a href="https://fr.linkedin.com/in/mysetup" target="_blank">LinkedIn</a></li>
                        <li><a href="https://plus.google.com/u/0/+mysetup/posts" target="_blank">Google +</a></li>
                      </ul>
                    </div>
                    <div class="column column-25">
                        <img class="float-right" src="img/mysetup_menu.png" alt="mySetup">
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
</body>
</html>
