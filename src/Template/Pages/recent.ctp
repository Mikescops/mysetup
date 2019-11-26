<?php

$this->layout = 'default';
$this->assign('title', __('Latest Setups | mySetup.co'));

echo $this->Html->meta('description', __('See the most recent setups published on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Latest Setups | mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'See the most recent setups published on mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'See the most recent setups published on mySetup.co'], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/recent', true)], null, ['block' => true]);

?>
<div class="colored-container">
    <div class="container">
        <br>
        <h2><?= __('Latest setups') ?></h2><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">
        <div class="row">
            <div class="column column-75">

                <div class="card-grid" id="grid-holder">

                    <?php foreach ($setups as $setup) : ?>

                        <?php $rgb_colors = json_decode($setup->main_colors)[0]; ?>

                        <?= $this->element('List/card-item', [
                                'setup' => $setup,
                                'css_style' => 'background: rgba(' . $rgb_colors[0] . ', ' . $rgb_colors[1] . ', ' . $rgb_colors[2] . ', 0.4)'
                            ]) ?>

                    <?php endforeach ?>

                </div>

                <br clear="all">

                <p class="no_more_setups"></p>

                <?= $this->Html->scriptBlock('infiniteScroll(16);', array('block' => 'scriptBottom')); ?>

                <script id="template-list-item" type="text/template">
                    <?= $this->element('List/card-item-js') ?>
                </script>

            </div>
            <div class="column column-25 sidebar">
                <?= $this->element('Structure/sidebar') ?>
            </div>
        </div>
    </div>
</div>