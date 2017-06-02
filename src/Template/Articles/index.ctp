<?php

$this->layout = 'default';
$this->assign('title', 'Blog | mySetup.co');

echo $this->Html->meta('description', 'Our latest posts on mySetup.co blog', ['block' => true]);


echo $this->Html->meta(['property' => 'og:title', 'content' => 'Blog | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'Our latest posts on mySetup.co blog'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'Our latest posts on mySetup.co blog'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/popular', true)], null ,['block' => true]);


?>
    <div class="maincontainer">

    <div class="row">
        <div class="column column-75 article-list">

        <?php foreach ($articles as $article): ?>

            <a href="<?= $this->Url->build('/blog/'. $article->id . '-' . $this->Text->slug($article->title)) ?>">

                <div class="article-img">

                    <img src="<?= $this->Url->build('/') . $article->picture ?>" alt="<?= $article->title ?>">

                </div>

                <h3><?= $article->title ?></h3>

            </a>
        <?php endforeach ?>

        </div>
        <div class="column column-25 sidebar">

            <div class="twitter-feed">
              <a class="twitter-timeline" data-chrome="noscrollbar nofooter noboders" data-height="781" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co"><?= __('Tweets by @mysetup_co') ?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>

            <div class="social-networks">
                <a href="https://www.facebook.com/mysetup.co" target="_blank"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://twitter.com/mysetup_co" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="https://medium.com/mysetup-co" target="_blank"><i class="fa fa-medium fa-2x"></i></a>
                <a href="mailto:support@mysetup.co" title="Report a bug !"><i class="fa fa-bug fa-2x"></i></a>
            </div>

        </div>
    </div>

</div>