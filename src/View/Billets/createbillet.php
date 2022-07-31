<h1>Espace rédaction</h1>
<form method="post" novalidate action="">
        
        
        <?php echo $errorHandler->getFirstError('flashmessage'); ?>
        <div class="inputBox">
            <label for="title">Titre</label>    
            <input type="text" name="title" required id="title" value="<?php echo $errorHandler->getValue('title') ?>">
            <?php echo $errorHandler->getFirstError('title'); ?>
        </div>
        <div class="inputBox">
            <label for="abstract">Résumé</label>
            <textarea id="abstract" name="abstract"rows="5" cols="33"><?php echo $errorHandler->getValue('abstract') ?></textarea>
            <?php echo $errorHandler->getFirstError('abstract'); ?>
        </div>
        <div class="inputBox">
            <label for="chapter">Texte</label>
            <textarea id="chapter" name="chapter"><?php echo $errorHandler->getValue('chapter') ?></textarea>
            <?php echo $errorHandler->getFirstError('chapter'); ?>
        </div>
        <div class="inputBox">
            <label for="publish_at">Date de publication</label>
            <input type="datetime-local" name="publish_at" id="publish_at" value="<?php echo $errorHandler->getValue('publish_at') ?>"></input>
            <?php echo $errorHandler->getFirstError('publish_at'); ?>
        </div>
        <div class="links">
            <button type="submit">Publier</button>
            <ul>
                <li><a href="/home/index" data-text="Accueil">Accueil</a></li>
            </ul>
        </div>
    </form>