<?php
    $rgb_colors = json_decode($setup->main_colors)[0];
?>

<div class="setup-item" style="background: rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,1);">
    <a class="setup-pic" href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
        <div class="tile-gradient" style="
            background: -moz-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,0.9) 80%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,1) 100%);
            /* FF3.6+ */
            background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(255, 255, 255, 1)), color-stop(80%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,0.9)), color-stop(100%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,1)));
            /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,0.9) 80%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,1) 100%);
            /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,0.9) 80%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,1) 100%);
            /* Opera 11.10+ */
            background: -ms-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,0.9) 80%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,1) 100%);
            /* IE10+ */
              background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,0.9) 80%, rgba(<?= $rgb_colors[0].', '.$rgb_colors[1].', '.$rgb_colors[2] ?>,1) 100%);
            /* W3C */
            filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ffffff', GradientType=1);
            /* IE6-9 */
        "></div>
        <img alt="<?= h($setup->title) ?>" src="<?= $this->Url->build('/' . (!empty($setup->resources[0]) ? $setup->resources[0]->src : 'img/not_found.jpg' )) ?>">
    </a>
    <div class="red_like"><i class="fa fa-heart"></i> <?= $setup->like_count ?></div>

    <div class="item-inner">
        <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
            <img alt="<?= __('Profile picture of') ?> <?= h($setup->user->name) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user->modificationDate, 'mmss', null, null)); ?>">
        </a>

        <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
            <h3>
                <?= h($setup->title) ?>
                <?php if($setup->status == 'DRAFT'): ?>
                    <i title="<?= __('Only you can see this setup') ?>" class="fa fa-eye-slash setup-unpublished"></i>
                <?php endif ?>
                <?php if($setup->id == $setup->user->mainSetup_id): ?>
                    <i title="<?= ($authUser['id'] != $setup->user_id ? __('This is the main setup of') . ' ' . h($user->name) : __('This is your main setup')) ?>" class="fa fa-diamond setup-default"></i>
                <?php endif ?>
                <?php if($setup->featured): ?>
                    <i title="<?= __('This setup is featured on mySetup.co !')?>" class="fa fa-star setup-star"></i>
                <?php endif ?>
            </h3>
        </a>
    </div>
</div>