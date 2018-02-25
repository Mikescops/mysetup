<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', __('Search for') . ' "' . ($this->request->getQuery('q') ? $this->request->getQuery('q') : "") . '" | mySetup.co');
?>

<div class="colored-container">
    <div class="container">
        <h2><?= __('Search for') ?> :</h2>

        <div class="large_search">
            <input type="text" id="keyword-search" value="<?= h(($this->request->getQuery('q') ? $this->request->getQuery('q') : "")) ?>" placeholder="<?= __('Search a component... Find a cool setup !') ?>" />
            <?= $this->Html->scriptBlock('let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>
        </div>

        <br clear="all">
    </div>
</div>

<div class="container">
    <div class="maincontainer">
        <div class="row">
            <div class="column column-75 search-container">

                <?php dump($results) ?>

                <?php
                    if(isset($results["error"]))
                    {
                        if($results["error"] == "noquery")
                        {
                            echo "<h4>" . __("No search query, no results :(") . "</h4>";
                        }

                        elseif($results["error"] == "noresult")
                        {
                            echo "<h4>" . __("We haven't found any results for this query :(") . "</h4>";
                        }

                    }
                    else
                    {
                ?>

                <?php if(isset($results["resources"]) && count($results["resources"]) > 0): $resources = $results["resources"]; ?>
                    <div class="config-items">
                        <?php if(count($resources, COUNT_RECURSIVE) == 1): ?>
                            <a href="<?= urldecode($resources[0]->href) ?>" target="_blank"><div class="item_box" style="background-image: url(<?= urldecode($resources[0]->src) ?>)"></div></a>
                            <span><?= __('All setups related to :') ?></span>
                            <h4><?= urldecode($resources[0]->title) ?> <a href="<?= urldecode($resources[0]->href) ?>" target="_blank"> <i class="fa fa-shopping-basket"></i></a></h4>
                        <?php else:?>
                            <h3><?= __('Found components') ?></h3>
                        <?php foreach ($resources as $item): ?>
                            <a href="<?= $this->Url->build('/search/?q=' . $item->title) ?>"><div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div></a>
                        <?php endforeach; endif;?>
                        <br clear="all">
                    </div>
                    <br clear="all">
                <?php endif;?>

                <?php if(isset($results["setups"]) && count($results["setups"]) > 0): ?>
                    <h3><?= __('Found setups') ?></h3>
                    <?php foreach ($results["setups"] as $setup): ?>
                        <?= $this->element('List/tiles', ['setup' => $setup]) ?>
                    <?php endforeach;?>
                    <br clear='all'>
                <?php endif;?>

                <?php if(isset($results["users"]) && count($results["users"]) > 0): $foundUsers = $results["users"]; ?>
                    <h3><?= __('Found users') ?></h3>
                    <div class="activeUsers">
                        <?php foreach($foundUsers as $foundUser): ?>
                            <div class="featured-user">
                                <a href="<?=$this->Url->build('/users/'.$foundUser->id)?>">
                                    <img alt="<?= __('Profile picture of') ?> <?= $foundUser->name ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $foundUser->id . '.png?' . $this->Time->format($foundUser->modificationDate, 'mmss', null, null)); ?>">
                                <span>
                                    <strong><?= $foundUser->name ?></strong>
                                    <span></span>
                                </span>
                                </a>
                            </div>

                        <?php endforeach ?>
                    </div>
                <?php endif;?>

                <?php }?>
            </div>

            <div class="column column-25 sidebar search-sidebar">
                Here will be the sorting :)
            </div>
        </div>
    </div>
</div>
