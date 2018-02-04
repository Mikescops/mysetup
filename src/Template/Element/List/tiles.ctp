<?php
$rgb_colors = [0, 0, 0];
foreach(json_decode($setup->main_colors) as $colors)
{
    $rgb_colors[0] += $colors[0]**2;
    $rgb_colors[1] += $colors[1]**2;
    $rgb_colors[2] += $colors[2]**2;
}
?>

<div class="setup-item" style="background: rgb(<?= sqrt($rgb_colors[0] / 5).', '.sqrt($rgb_colors[1] / 5).', '.sqrt($rgb_colors[2] / 5) ?>);">
    <a class="setup-pic" href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
        <img alt="<?= h($setup->title) ?>" src="<?= $this->Url->build('/' . (!empty($setup->resources[0]) ? $setup->resources[0]->src : 'img/not_found.jpg' )) ?>">
    </a>
    <div class="red_like"><i class="fa fa-heart"></i> <?= $setup->like_count ?></div>

    <div class="item-inner">
        <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
            <img alt="<?= __('Profile picture of') ?> <?= $setup->user->name ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user->modificationDate, 'mmss', null, null)); ?>">
        </a>

        <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>
    </div>
</div>