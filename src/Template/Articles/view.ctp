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

echo $this->Html->meta(array('rel' => 'canonical', 'href' => $this->Url->build("/blog/".$article->id."-".$this->Text->slug($article->title), true)), null, ['block' => true]);


echo $this->Html->meta(['property' => 'og:title', 'content' =>  $article->title. ' | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $this->Text->truncate(getplaintextintrofromhtml($this->Markdown->transform($article->content)),150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => $this->Text->truncate(getplaintextintrofromhtml($this->Markdown->transform($article->content)),150,['ellipsis' => '..','exact' => true])], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build("/blog/".$article->id."-".$this->Text->slug($article->title), true)], null ,['block' => true]);



?>
    <div class="maincontainer">

    <div class="row">
        <div class="column column-75 article-post">

        <div class="post-image">

            <img src="<?= $this->Url->build('/') . $article->picture ?>" alt="<?= $article->title ?>">

        </div>

        <em class="float-right">#<?= $article->category ?></em>

        <h2><?= $article->title ?></h2>

        <?= $this->Markdown->transform(h($article->content))?>

        <div id="social-networks"></div>

        <br />

        <p class="setup-date">
            <i class='fa fa-clock-o'></i> <?= __('Published on') ?> <?= $this->Time->format($article->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $article->dateTime, $authUser['timeZone']); if(!$authUser): echo ' (GMT)'; endif; ?> <?= __('by') ?> <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'view', $article->user->id]); ?>"><?= $article->user->name ?></a>
        </p>

        </div>
        <div class="column column-25 sidebar">

        <?php if($authUser['admin']): ?>
            <a class="button" href="<?= $this->Url->build(['action' => 'edit', $article->id]) ?>"><?= __('Edit this article') ?></a>
            <?php if($authUser['id'] == $article->user_id): ?>
                <?= $this->Form->postLink(__('Delete this article'), ['action' => 'delete', $article->id], ['confirm' => __('Are you sure you want to delete this article ?'), 'class' => 'button']) ?>
            <?php endif; ?>
        <?php endif; ?>

            <a class="button button-backtolist" href="<?= $this->Url->build('/blog/') ?>"><i class="fa fa-share"></i> <?= __('Go back to list') ?></a>

            <div class="social-networks">
                <a href="https://www.facebook.com/mysetup.co" target="_blank" style="background-color: #3b5998"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://twitter.com/mysetup_co" target="_blank" style="background-color: #55acee"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="https://geeks.one/@mysetup_co" title="Mastodon" target="_blank" style="background-color: #45668e"><i class="fa fa-2x">M</i></a>
            </div>

        </div>
    </div>

</div>