<?php

$this->layout = 'default';
$this->assign('title', __('Our Team | mySetup.co'));

echo $this->Html->meta('description', __('Who is behind mySetup.co ?'), ['block' => true]);

?>
<div class="colored-container">
    <div class="container">
        <br><h2><?= __('Our Team') ?></h2><br>
    </div>
</div>
<div class="container">
<div class="maincontainer team-page">

    <h3>HeadStaff</h3>

    <div class="member">

        <?= $this->Html->image('/img/contributors/corentin-mors.png', ['alt' => 'Corentin Mors']) ?>

        <p><strong>Corentin Mors</strong>: Student at INSA Centre Val de Loire, president of Geek Mexicain et developer of website like Uzzy.me and Pixelswap. I started to work on web development very young and now I manage plenty of projects over the net. I've designed mySetup.co as long as its Twitch extension from scratch.</p>
        <a href="https://pixelswap.fr/" target="_blank"><i class="fa fa-globe"></i> PixelSwap</a> |
        <a href="https://twitter.com/MikeScops" target="_blank"><i class="fa fa-twitter"></i> mikescops</a> |
        <a href="https://www.linkedin.com/in/corentinmors/" target="_blank"><i class="fa fa-linkedin"></i> Hire me</a>

    </div>
    <br/>

    <div class="member">

        <?= $this->Html->image('/img/contributors/samuel-forestier.png', ['alt' => 'Samuel Forestier']) ?>

        <p><strong>Samuel Forestier</strong> : Treasurer and writer at Geek Mexicain, I manage a personal blog for many years now where you can find all my development activities about my projects. During some of my spare time, I handle system administration and back-end development of this website.</p>
        <a href="https://blog.samuel.domains/" target="_blank"><i class="fa fa-globe"></i> HorlogeSkynet</a> |
        <a href="https://github.com/HorlogeSkynet/" target="_blank"><i class="fa fa-github"></i> GitHub</a> |
        <a href="https://www.linkedin.com/in/samuelforestier/" target="_blank"><i class="fa fa-linkedin"></i> LinkedIn</a>

    </div>
    <br />

    <h3><?= __('Graphics') ?></h3>

    <div class="member">

        <?= $this->Html->image('/img/contributors/jeff-gbeho.png', ['alt' => 'Jeff Gbeho']) ?>

        <p><strong>Jeff Gbeho</strong> : Young graphic designer and illustrator based at Toulouse. I'm specialized in branding and illustration but I like to diversify by realizing projects such as typography, photography, web design, etc... I like challenges and finding solutions to problems through image.</p>
        <a href="https://www.jeffgbeho.com/" target="_blank"><i class="fa fa-globe"></i> Jeff website</a> |
        <a href="https://www.behance.net/JeffGbeho" target="_blank"><i class="fa fa-behance"></i> Behance</a> |
        <a href="https://www.facebook.com/designjeff" target="_blank"><i class="fa fa-facebook"></i> Facebook</a>

    </div>
    <br />

    <h3><?= __('Translation') ?></h3>

    <div class="member">

        <?= $this->Html->image('/img/contributors/romain-chevy.png', ['alt' => 'Romain Chevy']) ?>

        <p><strong>Romain Chevy</strong> : INSA student with the head-staff above, I'm a passionate of IT-hardware on my spare time. The Spanish translations you'll encounter here are mostly mine !</p>
        <a href="https://www.linkedin.com/in/romain-chevy/" target="_blank"><i class="fa fa-linkedin"></i> LinkedIn</a>

    </div>
    <br />

    <div class="member">

        <?= $this->Html->image('/img/contributors/anna.png', ['alt' => 'Anna']) ?>

        <p><strong>Anna</strong> : Engineering student in another school, I enjoy taking pictures of the world during my journeys around the globe. Italian translations come from me !</p>
        <a href="https://ello.co/fiertedecactus" target="_blank"><i class="fa  fa-camera-retro "></i> Ello</a> |
        <a href="https://unsplash.com/@fiertedecactus" target="_blank"><i class="fa  fa-camera "></i> Unsplash</a>

    </div>
</div>
</div>
