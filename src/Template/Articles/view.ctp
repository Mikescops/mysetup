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
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/popular', true)], null ,['block' => true]);



?>
    <div class="maincontainer">

    <div class="row">
        <div class="column column-75 article-post">

        <div class="post-image">

            <img src="<?= $this->Url->build('/') . $article->picture ?>" alt="<?= $article->title ?>">

        </div>

        <h2><?= $article->title ?></h2>


         <?= $this->Markdown->transform(h($article->content))?>


         <div id="social-networks"></div>


        </div>
        <div class="column column-25 sidebar">

            <a class="button" href="<?= $this->Url->build('/blog/') ?>">Go back to list</a>

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