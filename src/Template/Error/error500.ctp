<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'default';

if ($debug):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error500.ctp');

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
<?php if ($error instanceof Error) : ?>
        <strong>Error in: </strong>
        <?= sprintf('%s, line %s', str_replace(ROOT, 'ROOT', $error->getFile()), $error->getLine()) ?>
<?php endif; ?>
<?php
    echo $this->element('auto_table_warning');

    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
<div class="colored-container">
    <div class="container">
        <br><h3>500 - <?= __('Something went wrong') ?></h3><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">
    <h2><?= __('An Internal Error Has Occurred :(') ?></h2>
    <p class="error">
        <strong><?= __('Error') ?>: </strong>
        <?= h($message) ?><br>

        You may cry or call an <a href="mailto:support@mysetup.co">ambulance</a> !
    </p>
    </div>
</div>
