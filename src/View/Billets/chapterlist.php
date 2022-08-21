<div class="site">
    <h1>Espace lecture</h1>

    <?php foreach($billets as $billet):?>
        <article>
            <h2> <a href="/billets/chapitre/<?= $billet->id?>"><?= $billet->title?></a></h2>
            <img src="/images/chapter_pictures/<?= $billet->chapter_picture?>" alt="illustration">
            <p>Publié le <?= $billet->formatted_date?></p>
        </article>
    <?php endforeach?>
</div>