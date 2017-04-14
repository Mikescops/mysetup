<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="featured-container">

    <img width="1120" src="https://i.ytimg.com/vi/4kBLJK4FdfQ/maxresdefault.jpg" alt="">

    <div class="featured-inner">

        <div class="row">

            <div class="column column-75">
                <a class="featured-user" href="#">
                    <img src="https://avatars1.githubusercontent.com/u/4266283?v=3&s=460">
                </a>

                <h3><?= $setup->title ?></h3>
                <p>Shared by <?= $userNames['owner'] ?></p>

            </div>
                
                <a class="labeled_button float-right" tabindex="0">
                  <div class="red_button">
                    <i class="fa fa-heart"></i> Like
                  </div>
                  <span class="pointing_label">
                    1,048
                  </span>
                </a>

        </div>

    </div>

</div>


<div class="maincontainer">

    <div class="row config-post">
        <div class="column column-67">

            <div class="config-items">

            <?php foreach ($products as $item): ?>

                        <a href="<?= urldecode($item->href) ?>" target="_blank">
                            <div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div>
                        </a>

            <?php endforeach ?>

            </div>

        </div>
        <div class="column column-33 item-meta">

            <?= $this->Text->autoParagraph(h($setup->description)); ?>

            <p>Created : <?= h($setup->creationDate) ?></p>

            <div class="social-networks">
                <a href="#" class="button button-clear"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="#" class="button button-clear"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="#" class="button button-clear"><i class="fa fa-youtube fa-2x"></i></a>
            </div>

            <a class="button item-youtube" href="https://www.youtube.com/watch?v=rZQXp_OUp9s" data-lity>Voir la vidéo Youtube</a>


        </div>
    </div>

    <br>

    <div class="post_slider">
        
        <div class="slider-item">
            <div class="slider-item-inner">
                <a href="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg" data-lity data-lity-desc="Photo of Config'">
                    <img width="1120" src="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg">
                </a>
            </div>
        </div>

        <div class="slider-item">
            <div class="slider-item-inner">
                <a href="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg" data-lity data-lity-desc="Photo of Config'">
                    <img width="1120" src="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg">
                </a>
            </div>
        </div>
        <div class="slider-item">
            <div class="slider-item-inner">
                <a href="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg" data-lity data-lity-desc="Photo of Config'">
                    <img width="1120" src="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg">
                </a>
            </div>
        </div>
        <div class="slider-item">
            <div class="slider-item-inner">
                <a href="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg" data-lity data-lity-desc="Photo of Config'">
                    <img width="1120" src="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg">
                </a>
            </div>
        </div>
        <div class="slider-item">
            <div class="slider-item-inner">
                <a href="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg" data-lity data-lity-desc="Photo of Config'">
                    <img width="1120" src="https://i.ytimg.com/vi/GGYlBDdSpvg/maxresdefault.jpg">
                </a>
            </div>
        </div>


    </div>

    <div class="row comment-section">
        
        <div class="column column-50 column-offset-25">
        <h4>Wanna share your opinion ?</h4>

        <section class="comments">
        <?php if (!empty($setup->comments)): ?>
            <?php foreach ($setup->comments as $comments): ?>
            <article class="comment">
                <a class="comment-img" href="#non">
                    <img src="https://avatars1.githubusercontent.com/u/4266283?v=3&s=460" alt="" width="50" height="50" />
                </a>
                    
                <div class="comment-body">
                    <div class="text">
                      <p><?= h($comments->content) ?></p>
                    </div>
                    <p class="attribution">by <a href="#non"><?= h($userNames[$comments->user_id]) ?></a> at <?= h($comments->dateTime) ?></p>
                </div>
            </article>

            <?php endforeach; ?>
        <?php endif; ?>

        </section>
            
        <form>
            <label for="commentField">Comment</label>
            <textarea placeholder="Nice config' …" id="commentField"></textarea>
            <input class="button-primary float-right" type="submit" value="Send">
          </fieldset>
        </form>

        </div>

    </div>

</div>
