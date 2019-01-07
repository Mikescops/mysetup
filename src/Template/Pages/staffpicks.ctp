<?php

$this->layout = 'default';
$this->assign('title', __('Staff Picks') . ' | mySetup.co');

echo $this->Html->meta('description', __('See the best setups selected by our staff on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Staff Picks | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'See the best setups selected by our staff on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'See the best setups selected by our staff on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/recent', true)], null ,['block' => true]);

?>
<div class="colored-container">
    <div class="container">
        <br><h2><?= __('Staff Picks') ?></h2>
        <p><?= __('The best setups selected by our staff') ?></p>
        <br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">
        <div class="row">
            <div class="column column-100">

                <div class="feeditem">

                <?php foreach ($setups as $setup): ?>

                    <?= $this->element('List/cards', ['setup' => $setup]) ?>

                <?php endforeach ?>

                </div>

            </div>
        </div>
        
        <br>

        <div class="rowsocial" style="column-count: auto;">

            <?= $this->element('Structure/social_networks_tiles') ?>

        </div>
    </div>
</div>
