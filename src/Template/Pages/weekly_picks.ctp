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

<?php if($setups) : foreach ($setups as $setup): ?>

    <?= $this->element('List/showcase', ['setup' => $setup]) ?>

<?php endforeach; else: ?>

    <p class="no-showcase">No featured setups this week, sorry !</p>

<?php endif ?>

<br>

<?php
    if ($week == 1){$prev_week = 52; $prev_year = $year-1;}
    else {$prev_week = $week -1; $prev_year = $year;}
?>
<a class="button previous-weekly" href="<?= $this->Url->build('/weekly/'.$prev_year.'-'.$prev_week, true) ?>"><i class="fas fa-chevron-left"></i> Previous week</a>

