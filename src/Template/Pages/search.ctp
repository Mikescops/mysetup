<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', __('Search for') . ' "' . ($this->request->getQuery('q') ? $this->request->getQuery('q') : "") . '" | mySetup.co');
?>
<div class="container sitecontainer">
<div class="maincontainer">
    <div class="row">
        <div class="column column-75">

            <div class="large_search">
                <input type="text" id="keyword-search" value="<?= h(($this->request->getQuery('q') ? $this->request->getQuery('q') : "")) ?>" placeholder="<?= __('Search a component... Find a cool setup !') ?>" />
                <?= $this->Html->scriptBlock('let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>
            </div>

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

            <?php if(isset($results["resources"])): $resources = $results["resources"]; ?>
                <div class="rowfeed">
                    <?php if(count($resources, COUNT_RECURSIVE) == 1): ?>
                        <a href="<?= $this->Url->build('/search/?q=' . $resources[0]->title) ?>"><div class="item_box" style="background-image: url(<?= urldecode($resources[0]->src) ?>)"></div></a>
                        <h3><?= __('All setups related to :') ?> </br><?= urldecode($resources[0]->title) ?></h3>
                    <?php else: foreach ($resources as $item): ?>
                        <a href="<?= $this->Url->build('/search/?q=' . $item->title) ?>"><div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div></a>
                    <?php endforeach; endif;?>
                </div>
            <?php endif;?>

             <br clear='all'>

            <?php if(isset($results["setups"])): foreach ($results["setups"] as $setup): ?>
                <?= $this->element('List/tiles', ['setup' => $setup]) ?>
            <?php endforeach; endif;?>

             <br clear='all'>

            <?php if(isset($results["users"])): $foundUsers = $results["users"]; ?>
                <div class="activeUsers">
                    <?php foreach($foundUsers as $foundUser): ?>

                        <a class="featured-user" href="<?=$this->Url->build('/users/'.$foundUser->id)?>">
                            <img alt="<?= __('Profile picture of') ?> <?= $foundUser->name ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $foundUser->id . '.png?' . $this->Time->format($foundUser->modificationDate, 'mmss', null, null)); ?>">
                        </a>

                    <?php endforeach ?>
                </div>
            <?php endif;?>

            <?php }?>
        </div>

        <div class="column column-25 sidebar sidebar-search">
            Here will be the sorting :)
        </div>
    </div>
</div>
</div>
