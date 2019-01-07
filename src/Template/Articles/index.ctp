<?php

$this->layout = 'default';
$this->assign('title', 'Blog | mySetup.co');

echo $this->Html->meta('description', 'Our latest posts on mySetup.co blog', ['block' => true]);


echo $this->Html->meta(['property' => 'og:title', 'content' => 'Blog | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'Our latest posts on mySetup.co blog'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'Our latest posts on mySetup.co blog'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/blog', true)], null ,['block' => true]);


?>
<div class="colored-container">
    <div class="container">
        <h2><?= __('Our latest updates') ?></h2><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">

    <div class="row">
        <div class="column column-100">

            <div class="article-list">

                <?php foreach ($articles as $article): ?>
                    <div class="article-item">

                        <a href="<?= $this->Url->build('/blog/'. $article->id . '-' . $this->Text->slug($article->title)) ?>">

                            <div class="article-img">

                                <img src="<?= $this->Url->build('/') . $article->picture ?>" alt="<?= h($article->title) ?>">

                            </div>

                            <h3><?= h($article->title) ?></h3>

                        </a>
                    </div>
                <?php endforeach ?>

            </div>

            <br>   

        </div>
    </div>

    <br>

    <div class="rowsocial" style="column-count: auto;">

        <div class="social-networks">
            <a href="https://www.facebook.com/mysetup.co" target="_blank" style="background: #00c6ff;
            background: -webkit-linear-gradient(to right, #0072ff, #00c6ff);
            background: linear-gradient(to right, #0072ff, #00c6ff);
            "><i class="fab fa-facebook-f fa-2x"></i></a>
            <a href="https://twitter.com/mysetup_co" target="_blank" style="background-color: #55acee"><i class="fab fa-twitter fa-2x"></i></a>
            <a href="https://geeks.one/@mysetup_co" title="Mastodon" target="_blank" style="background-color: #282c37"><i class="fab fa-mastodon fa-2x"></i></a>
            <a href="https://www.instagram.com/mysetup.co/" target="_blank" style="background:
            radial-gradient(circle farthest-corner at 35% 90%, #fec564, transparent 50%),
            radial-gradient(circle farthest-corner at 0 140%, #fec564, transparent 50%),
            radial-gradient(ellipse farthest-corner at 0 -25%, #5258cf, transparent 50%),
            radial-gradient(ellipse farthest-corner at 20% -50%, #5258cf, transparent 50%),
            radial-gradient(ellipse farthest-corner at 100% 0, #893dc2, transparent 50%),
            radial-gradient(ellipse farthest-corner at 60% -20%, #893dc2, transparent 50%),
            radial-gradient(ellipse farthest-corner at 100% 100%, #d9317a, transparent),
            linear-gradient(#6559ca, #bc318f 30%, #e33f5f 50%, #f77638 70%, #fec66d 100%);
            "><i class="fab fa-instagram fa-2x"></i></a>
        </div>

    </div>

</div>
</div>
