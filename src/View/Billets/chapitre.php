<div class="site">
    <?php echo $errorHandler->getFirstError('likestatus'); ?>
    <article>
        <h1> <a href="/billets/chapitre/<?= $billet->id?>"><?= $billet->title?></a></h1>
        <img src="/images/chapter_pictures/<?= 'default.jpg'?>" alt="Illustration">
        <p><?= html_entity_decode(stripslashes($billet->chapter))?></p>
        <p class="publish_at">Publié le : <?= $billet->publish_at?></p>

        <?php if($loggedUser->isLogged()):?>
        <ul class="likes">
            <li id="like">
                <ion-icon name="thumbs-up-outline" id="iconlike"></ion-icon>
                <p>J'aime</p>
                <p class="likescount"></p>
            </li>
            <li id="dislike">
                <ion-icon name="thumbs-down-outline" id="icondislike"></ion-icon>
                <p>Je n'aime pas</p>
                <p class="dislikescount"></p>
            </li>
        </ul>
        <?php else:?>
            <ul class="likes">
            <li>
                <ion-icon name="thumbs-up-outline"></ion-icon>
                <p>J'aime</p>
                <p class="likescount"></p>
            </li>
            <li>
                <ion-icon name="thumbs-down-outline"></ion-icon>
                <p>Je n'aime pas</p>
                <p class="dislikescount"></p>
            </li>
        </ul>
        <?php endif;?>        

        <?php if($loggedUser->isLogged() && $loggedUser->isAdmin()):?>
            <ul>
                <li><a href="/billets/editbillet/<?= $billet->id?>"><ion-icon name="clipboard-outline"></ion-icon>Editer</a></li>
                <li><a href="/billets/deletebillet/<?= $billet->id?>"><ion-icon name="trash-outline"></ion-icon>Effacer</a></li>
                <!-- <li><a href="/comments/createcomment/<?= $billet->id?>">Commenter</a></li> -->
        <?php endif;?>        
            </ul>   
    </article>
    <?php if($loggedUser->isLogged()):?>
        <form method="post" action="/comments/createcomment/<?= $billet->id?>" novalidate>
            <div class="inputBox">
                <label for="content">Votre commentaire : </label>
                <textarea name="content" id="content"></textarea>
                <?php echo $errorHandler->getFirstError('content'); ?>
            </div>
            <button type="submit">Commenter</button>
        </form>
    <?php endif;?>
    <div class="comments">
        <h2>Commentaires des lecteurs</h2>
        <?php foreach($comments as $comment): ?>
            <div class="comment">
                <p class="content"><?= html_entity_decode(stripslashes($comment->content))?></p>
                <div class="identity">
                    <h3><?= $comment->pseudo?></h3>
                    <p class="publication"><?= $comment->publish_at?></p>
                </div>
                <?php if($comment->report === '30'):?>
                    <a class="signal" href="/comments/signalcomment/<?= $billet->id?>/<?= $comment->id?>">Signaler</a>
                <?php else:?>
                    <p class="modo">Ce commentaire a été modéré</p>
                <?php endif;?>
            </div>
        <?php endforeach ?>
    </div>
</div>
<script>
    // Pass some identifiers to chapitre.js
    let userid = '<?php echo $loggedUser->getID()?>';
    let useremail = '<?php echo $loggedUser->getEmail()?>';
    let billetid = '<?php echo $billet->id?>';
</script>

