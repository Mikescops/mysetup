<?php

$this->layout = 'default';
$this->assign('title', __('Weekly Picks') . ' | mySetup.co');

echo $this->Html->meta('description', __('See the best setups selected by our staff on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Staff Picks | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'See the best setups selected by our staff on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'See the best setups selected by our staff on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/recent', true)], null ,['block' => true]);

?>
<div class="weeklypicks">
    <div class="container">
        <h2><?= __('Weekly Picks') ?></h2>
        <p><?= __('The best setups of the week') ?> n°<?= $week ?></p>
        <hr>
    </div>
</div>

<?php foreach ($setups as $setup): ?>

    <?= $this->element('List/showcase', ['setup' => $setup]) ?>

<?php endforeach ?>

<br>
<a class="button previous-weekly" href=""><i class="fas fa-chevron-left"></i> Previous week</a>

