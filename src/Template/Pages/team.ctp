<?php

$this->layout = 'default';
$seo_title = __('Our Team | mySetup.co');
$seo_description = __('Who is behind mySetup.co? Here is the team behind this awesome community.');

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
<div class="colored-container">
    <div class="container">
        <h2><?= __('Our Team') ?></h2><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer team-page">

        <h3>HeadStaff</h3>
        <div class="member">
            <?= $this->Html->image('/img/contributors/corentin-mors.jpeg', ['alt' => 'Corentin Mors']) ?>

            <p><strong>Corentin Mors</strong> : Lead full-stack developer. I designed mysetup.co to get more inspiration from this amazing community. I currently work for Dashlane as backend software engineer.</p>
            <a href="https://pixelswap.fr/" target="_blank"><i class="fa fa-globe"></i> PixelSwap Blog</a> |
            <a href="https://dev.pixelswap.fr/" target="_blank"><i class="fas fa-briefcase"></i> My Works</a> |
            <a href="https://twitter.com/MikeScops" target="_blank"><i class="fab fa-twitter"></i> mikescops</a> |
            <a href="https://www.linkedin.com/in/corentinmors/" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn</a>
        </div>
        <br />
        <div class="member">
            <?= $this->Html->image('/img/contributors/samuel-forestier.png', ['alt' => 'Samuel Forestier']) ?>

            <p><strong>Samuel Forestier</strong> : Lead backend developer. I manage a personal blog for many years now where you can find all my development activities about my projects. During some of my spare time, I handle system administration and backend development of this website.</p>
            <a href="https://blog.samuel.domains/" target="_blank"><i class="fa fa-globe"></i> Blog</a> |
            <a href="https://mastodon.social/@HorlogeSkynet" target="_blank"><i class="fab fa-mastodon"></i> Mastodon</a> |
            <a href="https://github.com/HorlogeSkynet/" target="_blank"><i class="fab fa-github"></i> GitHub</a> |
            <a href="https://www.linkedin.com/in/samuelforestier/" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn</a>
        </div>

        <br />

        <h3><?= __('Graphics') ?></h3>
        <div class="member">
            <?= $this->Html->image('/img/contributors/jeff-gbeho.png', ['alt' => 'Jeff Gbeho']) ?>

            <p><strong>Jeff Gbeho</strong> : Graphic designer and illustrator based at Toulouse. I'm specialized in branding and illustration but I like to diversify by realizing projects such as typography, photography, web design, etc... I like challenges and finding solutions to problems through image.</p>
            <a href="https://www.jeffgbeho.com/" target="_blank"><i class="fa fa-globe"></i> Jeff website</a> |
            <a href="https://www.behance.net/JeffGbeho" target="_blank"><i class="fab fa-behance"></i> Behance</a> |
            <a href="https://www.facebook.com/designjeff" target="_blank"><i class="fab fa-facebook"></i> Facebook</a>
        </div>

        <br />

        <h3><?= __('Translation') ?></h3>
        <div class="member">
            <?= $this->Html->image('/img/contributors/romain-chevy.png', ['alt' => 'Romain Chevy']) ?>

            <p><strong>Romain Chevy</strong> : Spanish translations contributor. I'm a passionate of IT-hardware on my spare time. The Spanish translations you'll encounter here are mostly mine!</p>
            <a href="https://www.linkedin.com/in/romain-chevy/" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn</a>
        </div>
        <br />
        <div class="member">
            <?= $this->Html->image('/img/contributors/anna.png', ['alt' => 'Anna']) ?>

            <p><strong>Anna</strong> : Italian translations contributor. I enjoy taking pictures of the world during my journeys around the globe. Italian translations come from me!</p>
            <a href="https://ello.co/fiertedecactus" target="_blank"><i class="fa fa-camera-retro "></i> Ello</a> |
            <a href="https://unsplash.com/@fiertedecactus" target="_blank"><i class="fa fa-camera "></i> Unsplash</a>
        </div>

        <br />

        <h3>Community Manager</h3>
        <div class="member">
            <?= $this->Html->image('/img/contributors/thea-forestier.png', ['alt' => 'Théa Forestier']) ?>

            <p><strong>Théa Forestier</strong> : Community contributor. I love capturing nature with my camera and listen to some classics.</p>
            <a href="https://twitter.com/thea_frst" target="_blank"><i class="fab fa-twitter"></i> Twitter</a> |
            <a href="https://unsplash.com/@freshcookie" target="_blank"><i class="fa fa-camera "></i> Unsplash</a>
        </div>

        <br />
    </div>
</div>