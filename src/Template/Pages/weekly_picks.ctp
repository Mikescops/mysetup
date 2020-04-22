<?php

$this->layout = 'default';
$seo_title = __('Weekly Picks #' . $week) . ' | mySetup.co';
$seo_description = __('The best setups of the week n°' . $week . ' selected by our staff.');

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
<div class="weeklypicks">
    <div class="container">
        <h2><?= __('Weekly Picks') ?></h2>
        <p><?= __('The best setups of the week') ?> n° <?= $this->Number->format($week) ?></p>
        <hr class="hr-gradient">

        <?php if ($setups) : foreach ($setups as $setup) : ?>

                <div class="showcase-holder">
                    <?= $this->element('List/showcase', ['setup' => $setup]) ?>
                </div>

                <br>

            <?php endforeach;
        else : ?>

            <p class="no-showcase"><?= __('No featured setups this week, sorry !') ?></p>

        <?php endif ?>

        <br>

        <?php
        if ((int) $week === 1) {
            $prev_week = 52;
            $prev_year = $year - 1;
        } else {
            $prev_week = $week - 1;
            $prev_year = $year;
        }
        ?>
        <a class="button previous-weekly" href="<?= $this->Url->build('/weekly/' . $prev_year . '-' . $prev_week, true) ?>"><i class="fas fa-chevron-left"></i> <?= __('Previous week') ?></a>

        <?php if ((new \DateTime())->setISODate($year, $week) < (new \DateTime("-1 week"))) : ?>
            <?php
            if ($week >= 52) {
                $next_week = 1;
                $next_year = $year + 1;
            } else {
                $next_week = $week + 1;
                $next_year = $year;
            }
            ?>
            <br />
            <a class="button previous-weekly" href="<?= $this->Url->build('/weekly/' . $next_year . '-' . $next_week, true) ?>"><?= __('Next week') ?> <i class="fas fa-chevron-right"></i></a>
        <?php endif;
        ?>

    </div>
</div>