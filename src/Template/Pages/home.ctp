<?php

$this->layout = 'default';
$this->assign('title', 'Home');

?>

<?php
$url=$this->Url->build('/', true) . "app/getsetups?f=1&n=5"; 
$options=array(
      CURLOPT_URL            => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER         => false,
      CURLOPT_FAILONERROR    => true, 
      CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json')
);
$CURL=curl_init();
if(empty($CURL)){die("ERREUR curl_init : Il semble que cURL ne soit pas disponible.");}
      curl_setopt_array($CURL,$options);
      $fsetups=curl_exec($CURL);
      if(curl_errno($CURL)){
            echo "ERREUR curl_exec : ".curl_error($CURL);
      }
      $fsetups = json_decode($fsetups);
      //var_dump($fsetups);
curl_close($CURL);
 
?>

<div class="home_slider">

<?php foreach ($fsetups as $fsetup): ?>
            
    <div class="slider-item">
        <a href="setups/view/<?= $fsetup->id ?>"><img src="<?= $fsetup->resources[0]->src ?>"></a>
        <a class="slider-item-inner featured-user" href="user.html">
            <img src="https://horlogeskynet.github.io/img/portrait.jpg">
        </a>
        <div class="red_like"><i class="fa fa-heart"></i> <?php if(!empty($fsetup->likes[0])){echo $fsetup->likes[0]->total;}else{echo 0;} ?></div>
    </div>

<?php endforeach ?>

</div>

    <div class="maincontainer">

      <div class="large_search">
        
        <input type="text" id="keyword-search" placeholder="Search a component... Find a cool setup !" /> 
        <?= $this->Html->scriptBlock(' let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => console.log(`searching for ${word}...`));', array('block' => 'scriptBottom')); ?>

      </div>


    <div class="row">
        <div class="column column-75">

<?php
$url="localhost/mysetup/app/getsetups?t=like"; 
 
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
            <?php foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="setups/view/<?= $setup->id ?>">
                    <img src="<?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="#">
                                <img src="https://avatars1.githubusercontent.com/u/4266283?v=3&s=460">
                            </a>

                            <a href="setups/view/<?= $setup->id ?>"><h3><?= $setup->title ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach ?>


        </div>
        <div class="column column-25 sidebar">

            <div class="social-networks">
                <a href="#" class="button button-clear"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="#" class="button button-clear"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="#" class="button button-clear"><i class="fa fa-youtube fa-2x"></i></a>
            </div>

        </div>
    </div>

</div>