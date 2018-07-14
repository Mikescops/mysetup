<?php

$this->layout = 'default';
$this->assign('title', __('Latest Setups | mySetup.co'));

echo $this->Html->meta('description', __('See the most recent setups published on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Latest Setups | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'See the most recent setups published on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'See the most recent setups published on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/recent', true)], null ,['block' => true]);

?>
<div class="colored-container">
    <div class="container">
        <br><h2><?= __('Latest setups') ?></h2><br>
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

            <p class="no_more_setups"></p>

            <?= $this->Html->scriptBlock('infiniteScroll(20);', array('block' => 'scriptBottom')); ?>

            <script id="template-list-item" type="text/template">
                <?= $this->element('List/tiles-js-template') ?>
            </script>

        </div>
        <div class="column column-25 sidebar">
            <?= $this->element('Structure/sidebar') ?>
        </div>
    </div>
    </div>
</div>
