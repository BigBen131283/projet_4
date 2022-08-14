<div class="site">
    <section class="sidebar">
        <div class="side-navigation">
            <div class="sidebarToggle"></div>
            <ul>
                <li class="list active" style="--clr:#f44336">
                    <a href="#">
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="text">Home</span>
                    </a>
                </li>
                <li class="list" style="--clr:#ffa117">
                    <a href="#">
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <span class="text">Membres</span>
                    </a>
                </li>
                <li class="list" style="--clr:#0fc70f">
                    <a href="#">
                        <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                        <span class="text">Commentaires</span>
                    </a>
                </li>
                <li class="list" style="--clr:#2196f3">
                    <a href="#">
                        <span class="icon"><ion-icon name="book-outline"></ion-icon></span>
                        <span class="text">Chapitres</span>
                    </a>
                </li>
                <li class="list" style="--clr:#b145e9">
                    <a href="#">
                        <span class="icon"><ion-icon name="pencil-outline"></ion-icon></span>
                        <span class="text">Rédaction</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>
    <section class="dashboard">
        <h1>Statistiques</h1>
        <div class="statistics">
            <div class="statbox" id="billets_stats">
                <h2 class="box-title">Billets publiés</h2>
                <p class="data"><?= '18'?></p>
            </div>
            <div class="statbox" id="member_stats">
                <h2 class="box-title">Membres inscrits</h2>
                <p class="data"><?= '1675'?></p>
            </div>
            <div class="statbox" id="comments_stats">
                <h2 class="box-title">Commentaires publiés</h2>
                <p class="data"><?= '253'?></p>
            </div>
            <div class="statbox" id="modo_stats">
                <h2 class="box-title">Modération en attente</h2>
                <p class="data"><?= '14'?></p>
            </div>
        </div>
        <div class="siteadmin">
            <div class="adminbox" id="members"></div>
            <div class="adminbox" id="moderate">
                <?php foreach($signaledComments as $comment):?>
                    <article>
                        <h2><a href="/billets/chapitre/<?= $comment->billet_id?>"><?= $comment->title?></a></h2>
                        <div class="comment"><?= html_entity_decode(stripslashes($comment->content))?></div>
                        <div class="auth">
                            <p class="pseudo"><?= $comment->pseudo?></p>    
                            <p class="date"><?= $comment->publish_at?></p>
                        </div>
                        <div class="advice">
                            <a href="/admin/acceptcomment/<?= $comment->id?>">Autoriser</a>
                            <a href="/admin/rejectcomment/<?= $comment->id?>">Supprimer</a>
                        </div>
                    </article>
                <?php endforeach?>
            </div>
            <div class="adminbox" id="chapters"></div>
        </div>
        <div id="write">
            <form action="/billets/createbillet" method="post" novalidate>
                <?php echo $errorHandler->getFirstError('flashmessage'); ?>
                <div class="inputBox">
                    <label for="title">Titre</label>
                    <input type="text" name="title" required id="title" value="<?php echo $errorHandler->getValue('title')?>">
                    <?php echo $errorHandler->getFirstError('title'); ?>
                </div>
                <div class="inputBox">
                    <label for="abstract">Résumé</label>
                    <textarea name="abstract" id="abstract"><?php echo $errorHandler->getValue('abstract')?></textarea>
                    <?php echo $errorHandler->getFirstError('abstract'); ?>
                </div>
                <div class="inputBox">
                    <label for="chapterpicture">Photo</label>
                    <input type="file" name="chapterpicture" id="chapterpicture">
                </div>
                <div class="inputBox">
                    <label for="chapter">Texte</label>
                    <textarea name="chapter" id="chapter"><?php echo $errorHandler->getValue('chapter')?></textarea>
                    <?php echo $errorHandler->getFirstError('chapter'); ?>
                </div>
                <div class="inputBox">
                    <label for="publish_at">Date de publication</label>
                    <input type="datetime-local" name="publish_at" id="publish_at" value="<?php echo $errorHandler->getValue('publish_at')?>">
                    <?php echo $errorHandler->getFirstError('publish_at'); ?>
                </div>
                <button class="publish" type="submit">Publier</button>
            </form>
        </div>
    </section>
</div>