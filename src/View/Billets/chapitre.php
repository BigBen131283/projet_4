<article>
    <h1> <a href="/billets/chapitre/<?= $billet->id?>"><?= $billet->title?></a></h1>
    <p><?= html_entity_decode(stripslashes($billet->chapter))?></p>
    <p>Publié le : <?= $billet->publish_at?></p>
    <?php if($loggedUser->isLogged() && $loggedUser->isAdmin()):?>
        <ul>
            <li><a href="/billets/editbillet/<?= $billet->id?>"><ion-icon name="clipboard-outline"></ion-icon>Editer</a></li>
            <li><a href="/billets/deletebillet/<?= $billet->id?>"><ion-icon name="trash-outline"></ion-icon>Effacer</a></li>
    <?php endif;?>        
            <li><a href="/billets/chapterlist/<?= $billet->id?>"><ion-icon name="arrow-back-outline"></ion-icon></ion-icon>Retour à la liste</a></li>
            
        </ul>
    
</article>

<!-- https://stackoverflow.com/questions/15976357/php-text-encoding-decoding-tinymce -->
