<?php

$this->layout = 'default';
$seo_title = h($article->title) . ' | mySetup.co';
$seo_description = $this->Text->truncate($this->MySetupTools->getPlainTextIntroFromHTML($this->Markdown->transform($article->content)), 150, ['ellipsis' => '..', 'exact' => true]);

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
        <div class="row justify-content-center">
            <div class="column">
                <div class="breadcrumb">
                    <a href="<?= $this->Url->build('/') ?>"><?= __('Home') ?></a> <i class="fa fa-chevron-right"></i> <a href="<?= $this->Url->build('/blog') ?>"><?= __('Blog') ?></a> <i class="fa fa-chevron-right"></i> <?= h($article->title) ?>

                    <?php if ($authUser['admin']) : ?>&nbsp;&nbsp;
                    <a title="<?= __('Edit this article') ?>" href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'articles_edit', $article->id]) ?>"><i class="fa fa-pencil-alt"></i></a>
                    &nbsp;
                    <?php if ($authUser['id'] == $article->user_id) : ?>
                        <?= $this->Form->postLink('', ['action' => 'delete', $article->id], ['confirm' => __('Are you sure you want to delete this article ?'), 'class' => 'fa fa-trash', 'title' => __('Delete this article')]) ?>
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="maincontainer">

        <div class="row">
            <div class="column column-75 column-middle article-post">

                <div class="post-image">

                    <img src="<?= $this->Url->build('/') . $article->picture ?>" alt="<?= h($article->title) ?>">

                </div>

                <em class="category float-right">#<?= h($article->category) ?></em>

                <h2><?= h($article->title) ?></h2>

                <?= $this->Markdown->transform($article->content) ?>

                <div id="social-networks"></div>

                <br />

                <p class="setup-date">
                    <i class='fa fa-clock'></i> <?= __('Published on') ?> <?= $this->Time->format($article->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $article->dateTime, $authUser['timeZone']);
                                                                            if (!$authUser) : echo ' (GMT)';
                                                                            endif; ?> <?= __('by') ?> <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'view', $article->user->id]); ?>"><?= h($article->user->name) ?></a>
                </p>

            </div>
        </div>

    </div>
</div>