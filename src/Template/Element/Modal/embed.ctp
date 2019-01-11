<div id="embed_website_script" class="lity-hide">
    <h4><?= __('How to embed the setup on my website ?') ?></h4>
    <?= __("It's pretty easy, just add the code below to your page (and set the setup id accordingly)") ?> :

    <div class="input text"><input readonly name="embedcode" id="embedcode" value='<script src="<?= $this->Url->build('/api/widgets.js', true) ?>"></script><div class="mysetup-embed" ms-setup="<?= $setup->id ?>" ms-width="responsive"><?= __('Setup shared by') ?> <?= $this->Html->link($setup->user['name'], ['controller' => 'users', 'action' => 'view', $setup->user['id'], '_full' => true]) ?> <?= __x('"sur" un site', 'on') ?> <a href="<?= $this->Url->build('/', true) ?>">mySetup.co</a></div>' type="text"></div>

    <h5><?= __('Preview') ?> :</h5>

    <div class="display-preview">

        <script async src="<?= $this->Url->build('/api/widgets.js') ?>"></script>
        <div class="mysetup-embed" <?= ($debug ? 'dev="on"' : '') ?> ms-setup="<?= $setup->id ?>" ms-width="responsive"><?= __('Setup shared by') ?> <?= $this->Html->link($setup->user['name'], ['controller' => 'users', 'action' => 'view', $setup->user['id'], '_full' => true]) ?> <?= __x('"sur" un site', 'on') ?> <a href="<?= $this->Url->build('/', true) ?>">mySetup.co</a></div>

    </div>
    <br>
    <p><?= __('The embedded setup is responsive by default. You can set a fixed size by editing the attribute ms-width with the width of your choice.') ?></p>
</div>