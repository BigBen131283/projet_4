<div class="site">
    <h1>Espace lecture</h1>

    <?php foreach($billets as $billet):?>
        <article>
            <h2> <a href="/billets/chapitre/<?= $billet->id?>"><?= $billet->title?></a></h2>
            <img src="/images/chapter_pictures/default.jpg" alt="illustration">
            <p>Publié le : <?= $billet->publish_at?></p>
        </article>
    <?php endforeach?>
</div>