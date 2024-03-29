<?php

/**
 * @var \App\View\AppView $this
 */

$seo_title = __('Search for') . ' "' . h($this->request->getQuery('q') ? $this->request->getQuery('q') : "") . '" | mySetup.co';

$seo_description = __('Find all setups, components or users related to ') . h($this->request->getQuery('q') ? $this->request->getQuery('q') : "");

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
        <h2><?= __('Search for') ?> :</h2>

        <div class="large_search">
            <i class="fa fa-search"></i>
            <form action="">
                <input type="search" name="q" id="keyword-search" value="<?= h(($this->request->getQuery('q') ? $this->request->getQuery('q') : "")) ?>" placeholder="<?= __('Search a component... Find a cool setup !') ?>" />
            </form>
            <?= $this->Html->scriptBlock('let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>
        </div>

        <br clear="all">
    </div>
</div>

<div class="search-tabs">
    <ul>
        <span class="pushli"></span>
        <?php
        $queries = '';
        foreach ($this->request->getQuery() as $id => $query) {
            if ($queries) {
                $queries .= '&';
            }

            $queries .= $id . '=' . $query;
        }

        if ($queries) {
            $queries = '?' . $queries;
        }
        ?>
        <li <?php if ($this->request->getPath() == '/search/') : ?>class="active" <?php endif; ?>>
            <a href="<?= $this->Url->build('/search/' . h($queries)) ?>">
                <?= __('All') ?>
            </a>
        </li>
        <li <?php if ($this->request->getPath() == '/search/setups') : ?>class="active" <?php endif; ?>>
            <a href="<?= $this->Url->build('/search/setups' . h($queries)) ?>">
                <?= __('Setups') ?>
            </a>
        </li>
        <li <?php if ($this->request->getPath() == '/search/resources') : ?>class="active" <?php endif; ?>>
            <a href="<?= $this->Url->build('/search/resources' . h($queries)) ?>">
                <?= __('Components') ?>
            </a>
        </li>
        <li <?php if ($this->request->getPath() == '/search/users') : ?>class="active" <?php endif; ?>>
            <a href="<?= $this->Url->build('/search/users' . h($queries)) ?>">
                <?= __('Users') ?>
            </a>
        </li>
        <span class="pushli"></span>
    </ul>
</div>

<div class="container">
    <div class="maincontainer">
        <div class="row">
            <div class="column column-75 search-container">

                <?php if (isset($results["error"])) {
                    if ($results["error"] == "noquery") {
                        echo "<h4>" . __("No search query, no results :(") . "</h4>";
                    } elseif ($results["error"] == "noresult") {
                        echo "<h4>" . __("We haven't found any results for this query :(") . "</h4><br>";
                    }
                } else {
                ?>

                    <?php if (isset($results["resources"]) && count($results["resources"]) > 0) : $resources = $results["resources"]; ?>

                        <?php if (count($resources, COUNT_RECURSIVE) == 1) : ?>
                            <div class="search-single-component">
                                <a href="<?= urldecode($resources[0]->href) ?>" target="_blank">
                                    <div class="item_box float-left" style="background-image: url(<?= urldecode($resources[0]->src) ?>)"></div>
                                </a>
                                <div>
                                    <span><?= __('All setups related to') ?> :</span>
                                    <h4><?= urldecode(h($resources[0]->title)) ?> <a href="<?= urldecode($resources[0]->href) ?>" target="_blank"> <i class="fa fa-shopping-bag"></i></a></h4>
                                </div>
                            </div>
                        <?php else : ?>
                            <h3><?= __('Found components') ?></h3>
                            <div class="config-items">
                                <?php foreach ($resources as $item) : ?>
                                    <a href="<?= $this->Url->build('/search/?q=' . h($item->title)) ?>">
                                        <div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->request->getPath() == '/search/resources' && $this->Paginator->counter(['format' => '{{pages}}']) > 1) : ?>
                            <ul class="pagination">
                                <?= $this->Paginator->first('<< ' . __('first')) ?>
                                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                                <?= $this->Paginator->numbers(['first' => 1, 'last' => 1, 'modulus' => 3]) ?>
                                <?= $this->Paginator->next(__('next') . ' >') ?>
                                <?= $this->Paginator->last(__('last') . ' >>') ?>
                            </ul>
                        <?php endif; ?>
                        <br clear="all">

                    <?php endif; ?>

                    <?php if (isset($results["setups"]) && count($results["setups"]) > 0) : ?>
                        <div>
                            <h3><?= __('Found setups') ?></h3>
                            <div class="card-grid">
                                <?php foreach ($results["setups"] as $setup) : ?>
                                    <?= $this->element('List/card-item', ['setup' => $setup]) ?>
                                <?php endforeach; ?>
                                <?php if ($this->request->getPath() == '/search/setups' && $this->Paginator->counter(['format' => '{{pages}}']) > 1) : ?>
                                    <ul class="pagination">
                                        <?= $this->Paginator->first('<< ' . __('first')) ?>
                                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                                        <?= $this->Paginator->numbers(['first' => 1, 'last' => 1, 'modulus' => 3]) ?>
                                        <?= $this->Paginator->next(__('next') . ' >') ?>
                                        <?= $this->Paginator->last(__('last') . ' >>') ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        <br clear='all'>
                    <?php endif; ?>

                    <?php if (isset($results["users"]) && count($results["users"]) > 0) : ?>
                        <h3><?= __('Found users') ?></h3>
                        <div class="user-grid">
                            <?php foreach ($results["users"] as $user) : ?>
                                <div class="item-grid">
                                    <a href="<?= $this->Url->build('/users/' . $user->id) ?>">
                                        <img alt="<?= __('Profile picture of') ?> <?= h($user->name) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
                                        <span>
                                            <strong><?= h($user->name) ?> <?= $user->verified ? '<i class="fa fa-check-circle verified_account"></i>' : '' ?></strong>
                                            <span><?= h($this->MySetupTools->urlPrettifying($user->utwitch)) ?></span>
                                        </span>
                                    </a>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif; ?>

                <?php } ?>
            </div>
        </div>
    </div>
</div>