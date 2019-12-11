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
        <h2><?= __('Blog') ?></h2>
        <br>
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

        <?= $this->element('Structure/social_networks_tiles') ?>

    </div>

</div>
</div>
