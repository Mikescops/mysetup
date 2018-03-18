<?php

$this->layout = 'default';
$this->assign('title', $article->title. ' | mySetup.co');

function getplaintextintrofromhtml($html) {
    // Remove the HTML tags
    $html = strip_tags($html);
    // Convert HTML entities to single characters
    $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');

    $html = str_replace("\n", " ", $html);
    return $html;
}

echo $this->Html->meta('description', $this->Text->truncate(getplaintextintrofromhtml($this->Markdown->transform($article->content)),150,['ellipsis' => '..','exact' => true]), ['block' => true]);

echo $this->Html->meta(['rel' => 'canonical', 'href' => $this->Url->build("/blog/".$article->id."-".$this->Text->slug($article->title), true)], null, ['block' => true]);
echo $this->Html->meta(['name' => 'canonical', 'content' => 'summary_large_image'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:title', 'content' =>  $article->title. ' | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $this->Text->truncate(getplaintextintrofromhtml($this->Markdown->transform($article->content)),150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['name' => 'twitter:description', 'content' => $this->Text->truncate(getplaintextintrofromhtml($this->Markdown->transform($article->content)),150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/'.$article->picture, true)], null ,['block' => true]);
echo $this->Html->meta(['name' => 'twitter:image', 'content' => $this->Url->build('/'.$article->picture, true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build("/blog/".$article->id."-".$this->Text->slug($article->title), true)], null ,['block' => true]);

?>

<div class="colored-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="column">
                <div class="breadcrumb">
                    <a href="<?= $this->Url->build('/') ?>"><?= __('Home') ?></a> <i class="fa fa-chevron-right"></i> <a href="<?= $this->Url->build('/blog') ?>"><?= __('Blog') ?></a> <i class="fa fa-chevron-right"></i> <?= h($article->title) ?>

                    <?php if($authUser['admin']): ?>&nbsp;&nbsp;
                        <a title="<?= __('Edit this article') ?>" href="<?= $this->Url->build(['action' => 'edit', $article->id]) ?>"><i class="fa fa-pencil"></i></a>
                        &nbsp;
                        <?php if($authUser['id'] == $article->user_id): ?>
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
            <i class='fa fa-clock-o'></i> <?= __('Published on') ?> <?= $this->Time->format($article->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $article->dateTime, $authUser['timeZone']); if(!$authUser): echo ' (GMT)'; endif; ?> <?= __('by') ?> <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'view', $article->user->id]); ?>"><?= h($article->user->name) ?></a>
        </p>

        </div>
    </div>

</div>
</div>
