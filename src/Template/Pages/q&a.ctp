<?php

$this->layout = 'default';
$this->assign('title', __('Q&amp;A').' | mySetup.co');

echo $this->Html->meta('description', __('We answer common questions and issues about mySetup. Find some tutorials to start sharing your setup now.'), ['block' => true]);

?>
<div class="colored-container">
    <div class="container">
        <br><h3><?= __('Q&amp;A - We answer common questions') ?></h3><br>
    </div>
</div>
<div class="container">
<div class="maincontainer">

    <h3><?= __("Questions") ?></h3>

    <ol class="faq-questions">
        <li><a href="#q-1"><?= __("I didn't receive the verification mail") ?></a></li>
        <li><a href="#q-2"><?= __("I can't find a product with the product search") ?></a></li>
        <li><a href="#q-3"><?= __("The product search is not responding") ?></a></li>
        <li><a href="#q-4"><?= __("My setup was not created due to the images I uploaded") ?></a></li>
        <li><a href="#q-5"><?= __("I can't add a Youtube / Twitch video on my setup") ?></a></li>
        <li><a href="#q-6"><?= __("How to use the Markdown") ?></a></li>
        <li><a href="#q-7"><?= __("My setup has been refused, why ?") ?></a></li>
        <li><a href="#q-8"><?= __("How can I use the API you provide ?") ?></a></li>
    </ol>

    <br>

    <div class="faq-answers">

        <h3><?= __("Answers") ?></h3>

        <h4 id="q-1"><?= __("I didn't receive the verification mail") ?></h4>
        <p><?= __("Check your spam folder, your mailbox may have dropped it there. If you didn't receive it within 10 minutes please contact us on our social networks.") ?></p>

        <h4 id="q-2"><?= __("I can't find a product with the product search") ?></h4>
        <p><?= __('Some products does not exist in our database, because it is a special edition or it is too old. Try to find a replacement available.') ?></p>

        <h4 id="q-3"><?= __("The product search is not responding") ?></h4>
        <p><?= __('Sometimes the product search is busy with lot of request, please wait few moments and retry your search.') ?></p>

        <h4 id="q-4"><?= __("My setup was not created due to the images I uploaded") ?></h4>
        <p><?= __('We use some rules on images to prevent abuses. Images are limited to 5Mo per image and the format should be jpg, jpeg or png.') ?></p>

        <h4 id="q-5"><?= __("I can't add a Youtube / Twitch video on my setup") ?></h4>
        <p><?= __('Video links must be full URI links like : "https://www.youtube.com/watch?v=dQw4w9WgXcQ" or "https://player.twitch.tv/?channel=mikescops"') ?></p>

        <h4 id="q-6"><?= __("How to use the Markdown") ?></h4>
        <p><?= __('The Markdown system is similar to Github\'s one, please read more on the') ?> : <a href="https://guides.github.com/features/mastering-markdown/">Github Markdown Guide</a></p>

        <h4 id="q-7"><?= __("My setup has been refused, why ?") ?></h4>
        <p><?= __('If your setup has been moderated and refused, you can read why in your setup description. Please create another setup and follow the moderators guidelines.') ?></p>

        <h4 id="q-8"><?= __("How can I use the API you provide ?") ?></h4>
        <p>
            <?= __("It's pretty easy, just add the code below to your page (and set the setup id accordingly).") ?><br />
            <?= __("You may also retrieve the setups posted on the website with HTTP calls (details are provided below too)") ?> :
        </p>
        <style>.gist{width:990px!important}.gist-file .gist-data{max-height:990px;max-width:100%}</style>
        <script src="https://gist.github.com/HorlogeSkynet/e9a5f7a0a4da8014035238786c288f2a.js"></script>

    </div>

    <br>

</div>
</div>
