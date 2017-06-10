<?php

$this->layout = 'default';
$this->assign('title', __('Latest Setups | mySetup.co'));

echo $this->Html->meta('description', __('See the most recent setups published on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Latest Setups | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'See the most recent setups published on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'See the most recent setups published on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/recents', true)], null ,['block' => true]);

?>
    <div class="maincontainer">

    <div class="row">
        <div class="column column-75">

<?php
$url= $this->Url->build('/', true) . "app/getsetups?o=DESC&n=6"; 
 
// Tableau contenant les options de téléchargement
$options=array(
      CURLOPT_URL            => $url,  // Url cible (l'url la page que vous voulez télécharger)
      CURLOPT_RETURNTRANSFER => true,  // Retourner le contenu téléchargé dans une chaine (au lieu de l'afficher directement)
      CURLOPT_HEADER         => false, // Ne pas inclure l'entête de réponse du serveur dans la chaine retournée
      CURLOPT_FAILONERROR    => true,   // Gestion des codes d'erreur HTTP supérieurs ou égaux à 400
      CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json'
) // Gestion du json
);
 
// Création d'un nouvelle ressource cURL
$CURL=curl_init();
// Erreur suffisante pour justifier un die()
if(empty($CURL)){die("ERREUR curl_init : Il semble que cURL ne soit pas disponible.");}
 
      // Configuration des options de téléchargement
      curl_setopt_array($CURL,$options);
 
      // Exécution de la requête
      $setups=curl_exec($CURL);       // Le contenu téléchargé est enregistré dans la variable $content.
 
      // Si il s'est produit une erreur lors du téléchargement
      if(curl_errno($CURL)){
            // Le message d'erreur correspondant est affiché
            echo "ERREUR curl_exec : ".curl_error($CURL);
      }

      $setups = json_decode($setups);

      //var_dump($setups);
 
// Fermeture de la session cURL
curl_close($CURL);
 
?>


            <h3><?= __('Latest setups') ?></h3>

            <div class="fullitem_holder">

            <?php foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= $setup->title ?>" src="<?= $this->Url->build('/', true)?><?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> #<?= $setup->user_id ?>" src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $setup->user_id ?>.png">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= $setup->title ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach ?>

            </div>

            <p class="no_more_setups"></p>

            <?= $this->Html->scriptBlock('infiniteScroll(6);', array('block' => 'scriptBottom')); ?>


        </div>
        <div class="column column-25 sidebar sidebar-feed">

            <div class="blog-advert">
              <a href="<?=$this->Url->build('/blog/')?>">
                <h5><i class="fa fa-newspaper-o"></i><br><?= __('Read our latest news') ?></h5>
              </a>
            </div>

            <div class="twitter-feed">
              <a class="twitter-timeline" data-chrome="noscrollbar nofooter noboders" data-height="781" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co"><?= __('Tweets by @mysetup_co') ?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>

            <div class="social-networks">
                <a href="https://www.facebook.com/mysetup.co" target="_blank"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://twitter.com/mysetup_co" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="mailto:support@mysetup.co" title="Report a bug !"><i class="fa fa-bug fa-2x"></i></a>
            </div>

        </div>
    </div>

</div>