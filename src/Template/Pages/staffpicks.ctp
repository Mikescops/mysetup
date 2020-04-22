<?php

$this->layout = 'default';
$seo_title = __('Staff Picks') . ' | mySetup.co';
$seo_description = __('See the best setups selected by our staff on mySetup.co');

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
        <h2><?= __('Staff Picks') ?></h2>
        <p><?= __('The best setups selected by our staff') ?></p>
        <br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">
        <div class="row">
            <div class="column column-100">

                <div class="card-grid">

                    <?php foreach ($setups as $setup) : ?>

                        <?= $this->element('List/card-item', ['setup' => $setup]) ?>

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