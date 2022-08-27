<div class="site">
    <section class="sidebar">
        <div class="side-navigation">
            <div class="sidebarToggle"></div>
            <ul>
                <li class="list active btnAll" style="--clr:#f44336">
                    <a href="#">
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="text">Home</span>
                    </a>
                </li>
                <li class="list " style="--clr:#ffa117">
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
                <p class="data"><?= $statistics->publishedBillets?></p>
            </div>
            <div class="statbox" id="member_stats">
                <h2 class="box-title">Membres inscrits</h2>
                <p class="data"><?= $statistics->allUsers?></p>
            </div>
            <div class="statbox" id="comments_stats">
                <h2 class="box-title">Commentaires publiés</h2>
                <p class="data"><?= $statistics->allComments?></p>
            </div>
            <div class="statbox" id="modo_stats">
                <h2 class="box-title">Modération en attente</h2>
                <p class="data"><?= $statistics->allModerate?></p>
            </div>
        </div>
        <div class="siteadmin">
            <div class="adminbox members" id="members" style="--clr:#ffa117">
                <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
            </div>
            <div class="adminbox moderate" id="moderate"  style="--clr:#0fc70f">
                <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                <?php foreach($signaledComments as $comment):?>
                    <article class="<?= "comment".$comment->billet_id?>">
                        <h2><a href="/billets/chapitre/<?= $comment->billet_id?>"><?= $comment->title?></a></h2>
                        <div class="comment"><?= html_entity_decode(stripslashes($comment->content))?></div>
                        <div class="auth">
                            <p class="pseudo"><?= $comment->pseudo?></p>    
                            <p class="date"><?= $comment->formatted_date?></p>
                        </div>
                        <div class="advice">
                            <a href="/admin/acceptcomment/<?= $comment->id?>">Autoriser</a>
                            <a href="/admin/rejectcomment/<?= $comment->id?>">Supprimer</a>
                        </div>
                    </article>
                <?php endforeach?>
            </div>
            <div class="adminbox chapters" id="chapters" style="--clr:#2196f3">
                <span class="icon"><ion-icon name="book-outline"></ion-icon></span>
                <?php foreach($adminBillets as $billet):?>
                    <article id="<?= "chapter".$billet->id?>">
                        <div class="desc">
                            <h2><a href="/billets/chapitre/<?= $billet->id?>"><?= $billet->title?></a></h2>    
                            <p class="date"><?= $billet->formatted_date?></p>
                        </div>
                        <ul class="modify">
                                <li class="selectbillet notvisible" id="editbillet-<?= $billet->id?>">Editer</li>
                                <li class="selectbillet" id="deletebillet-<?= $billet->id?>">Supprimer</li>
                        </ul>
                    </article>
                <?php endforeach?>
            </div>
        </div>
        <div class="write" id="write" style="--clr:#b145e9">
            <span class="icon"><ion-icon name="pencil-outline"></ion-icon></span>
            <form action="/billets/createbillet" method="post" novalidate enctype="multipart/form-data">
                <?php echo $errorHandler->getFirstError('flashmessage'); ?>
                <div class="inputBox">
                    <label for="title">Titre</label>
                    <input type="text" name="title" required id="titleid" value="<?php echo $errorHandler->getValue('title')?>">
                    <?php echo $errorHandler->getFirstError('title'); ?>
                </div>
                <div class="inputBox">
                    <label for="abstract">Résumé</label>
                    <textarea name="abstract" id="abstractid"><?php echo $errorHandler->getValue('abstract')?></textarea>
                    <?php echo $errorHandler->getFirstError('abstract'); ?>
                </div>
                <div class="inputBox">
                    <label for="chapter_picture">Photo</label>
                    <input type="file" name="chapter_picture" id="fileid">
                    <?php echo $errorHandler->getFirstError('uploadError'); ?>
                    <?php echo $errorHandler->getFirstError('chapter_picture'); ?>
                </div>
                <div class="inputBox">
                    <label for="chapter">Texte</label>
                    <textarea name="chapter" id="chapterid"><?php echo $errorHandler->getValue('chapter')?></textarea>
                    <?php echo $errorHandler->getFirstError('chapter'); ?>
                </div>
                <div class="inputBox">
                    <label for="publish_at">Date de publication</label>
                    <input type="datetime-local" name="publish_at" id="dateid" value="<?php echo $errorHandler->getValue('publish_at')?>">
                    <?php echo $errorHandler->getFirstError('publish_at'); ?>
                </div>
                <div id="publish" class="controls active">
                    <button class="publish" type="submit">Publier</button>
                </div>
                <div id="edit" class="controls">
                    <button class="edit" type="submit">Modifier</button>
                    <button id="clearbutton" class="edit" type="submit">Annuler</button>
                </div>
            </form>
        </div>
    </section>
</div>