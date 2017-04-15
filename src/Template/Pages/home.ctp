<?php

$this->layout = 'default';
$this->assign('title', 'Home');

?>

<div class="home_slider">
            
    <div class="slider-item">
        <a href="post.html"><img src="https://i.ytimg.com/vi/iN_Q5i7J-Vg/maxresdefault.jpg"></a>
        <a class="slider-item-inner featured-user" href="user.html">
            <img src="https://horlogeskynet.github.io/img/portrait.jpg">
        </a>
        <div class="red_like"><i class="fa fa-heart"></i> 2</div>
    </div>
    <div class="slider-item">
        <a href="post.html"><img src="https://i.ytimg.com/vi/4kBLJK4FdfQ/maxresdefault.jpg"></a>
        <a class="slider-item-inner featured-user" href="user.html">
            <img src="https://horlogeskynet.github.io/img/portrait.jpg">
        </a>
        <div class="red_like"><i class="fa fa-heart"></i> 1553</div>
    </div>
    <div class="slider-item">
        <a href="post.html"><img src="https://i.ytimg.com/vi/ZelaJ5ukwGo/maxresdefault.jpg"></a>
        <a class="slider-item-inner featured-user" href="user.html">
            <img src="https://horlogeskynet.github.io/img/portrait.jpg">
        </a>
        <div class="red_like"><i class="fa fa-heart"></i> 50</div>
    </div>
    <div class="slider-item">
        <a href="post.html"><img src="https://i.ytimg.com/vi/4kBLJK4FdfQ/maxresdefault.jpg"></a>
        <a class="slider-item-inner featured-user" href="user.html">
            <img src="https://horlogeskynet.github.io/img/portrait.jpg">
        </a>
        <div class="red_like"><i class="fa fa-heart"></i> 38</div>
    </div>

    </div>

    <div class="maincontainer">

    <div class="row">
        <div class="column column-75">

            <h4>Popular this week</h4>

            <?php foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="#">
                    <img src="https://i.ytimg.com/vi/4kBLJK4FdfQ/maxresdefault.jpg">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i> 50</div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="#">
                                <img src="https://avatars1.githubusercontent.com/u/4266283?v=3&s=460">
                            </a>

                            <a href="post.html"><h3>Ma config perso #1</h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach ?>


        </div>
        <div class="column column-25 sidebar">

            <h4>Nos réseaux sociaux</h4>

            <div class="social-networks">
                <a href="#" class="button button-clear"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="#" class="button button-clear"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="#" class="button button-clear"><i class="fa fa-youtube fa-2x"></i></a>
            </div>

        </div>
    </div>

</div>