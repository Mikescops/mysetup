
<p style="color: #dddddd; font-size: 18px; font-family: sans-serif; line-height: 20px">
    Hello <?= $name ?> !
    <br />
    <br />
    Your account has just been successfully created on <a href="<?= $this->Url->build('/', true) ?>" target="_blank" style="color: #4a8ac9;">mySetup.co</a>.
    <br />
    We are so glad you joined us :)
</p>
<a href="<?= $this->Url->build('/', true) ?>" target="_blank" class="button-a">
    <span style="color:#dddddd;" class="button-link">Come and add your first setup !</span>
</a>
