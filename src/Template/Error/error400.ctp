<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'default';

if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error400.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?php Debugger::dump($error->params) ?>
<?php endif; ?>
<?= $this->element('auto_table_warning') ?>
<?php
    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
<div class="colored-container">
    <div class="container">

        <a href="<?= $this->Url->build('/', true); ?>"><?php echo $this->Html->image('404_page.png', ['alt' => __('404 page'), 'style' => 'width: 175px; max-width: 50%; float: right']); ?></a>

        <br><h3>404 - <?= __('This page does not exist') ?></h3><br>

        <br>

        <p><?= __('Wrong page, wrong link ? Try to search something :') ?></p>

        <br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">

        <div class="large_search" style="margin-top: -60px"> <i class="fa fa-search"></i>

            <input type="text" id="keyword-search" placeholder="<?= __('Search component, user or setup...') ?>" />
            <?= $this->Html->scriptBlock('let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`setups/search?q=${word}`, "_self"));', ['block' => 'scriptBottom']); ?>

        </div>
        <br><br><br><br>

    </div>
</div>
