<?php

$this->layout = 'default';
$this->assign('title', __('Popular this week | mySetup.co'));

echo $this->Html->meta('description', __('The most popular setups of the week on mySetup.co'), ['block' => true]);


echo $this->Html->meta(['property' => 'og:title', 'content' => 'Popular this week | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'The most popular setups of the week on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'The most popular setups of the week on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/popular', true)], null ,['block' => true]);

?>

<div class="colored-container">
    <div class="container">
        <br><h2><?= __('Popular this week') ?></h2><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">

    <div class="row">
        <div class="column column-75">
            <div class="fullitem_holder">

            <?php foreach ($setups as $setup): ?>

                <?= $this->element('List/tiles', ['setup' => $setup]) ?>

            <?php endforeach ?>

            </div>

        </div>
        <div class="column column-25 sidebar">
            <?= $this->element('Structure/sidebar') ?>
        </div>
    </div>

    </div>
</div>
