<?php

$this->layout = 'default';
$seo_title = 'Blog | mySetup.co';
$seo_description = 'Our latest posts on mySetup.co blog';

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
        <h2><?= __('Blog') ?></h2>
        <br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">

        <div class="row">
            <div class="column column-100">

                <div class="article-list">

                    <?php foreach ($articles as $article) : ?>
                        <div class="article-item">

                            <a href="<?= $this->Url->build('/blog/' . $article->id . '-' . $this->Text->slug($article->title)) ?>">

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