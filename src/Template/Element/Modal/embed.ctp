<div id="embed_website_script" class="lity-hide">
    <h4><?= __('How to embed the setup on my website ?') ?></h4>
    <?= __("It's pretty easy, just add the code below to your page (and set the setup id accordingly) :") ?>

    <div class="input text"><input readonly name="embedcode" id="embedcode" value='<script src="https://mysetup.co/api/widgets.js"></script><div id="mysetup-embed" ms-setup="<?= $setup->id ?>" ms-width="350">Setup shared by <?php if($setup->user['name']){echo $this->Html->link($setup->user['name'], ['controller' => 'users', 'action' => 'view', $setup->user['id']]);}else{echo "Unknown";} ?> at <a href="https://mysetup.co/">mySetup.co</a></div>' type="text"></div>

    <h5>Preview :</h5>

    <div class="display-preview">
        
        <script async src="https://mysetup.co/api/widgets.js"></script>
        <div id="mysetup-embed" ms-setup="<?= $setup->id ?>" ms-width="350">Setup shared by <?php if($setup->user['name']){echo $this->Html->link($setup->user['name'], ['controller' => 'users', 'action' => 'view', $setup->user['id']]);}else{echo "Unknown";} ?> at <a href="https://mysetup.co/">mySetup.co</a></div>

    </div>

    <br>
    <p><?= __('You can customize the size of your embedded setup by editing the value of ms-width.') ?></p>
</div>