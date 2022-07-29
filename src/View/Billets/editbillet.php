<h1>Espace mise à jour</h1>
<form method="post" novalidate action="">
        
        
        <?php echo $workInProgress->getFirstError('flashmessage'); ?>
        <div class="inputBox">
            <label for="title">Titre</label>    
            <input type="text" name="title" required id="title" readonly value="<?php echo $workInProgress->getValue('title') ?>">
            <?php echo $workInProgress->getFirstError('title'); ?>
        </div>
        <div class="inputBox">
            <label for="abstract">Résumé</label>
            <textarea id="abstract" name="abstract"rows="5" cols="33"><?php echo $workInProgress->getValue('abstract') ?></textarea>
            <?php echo $workInProgress->getFirstError('abstract'); ?>
        </div>
        <div class="inputBox">
            <label for="chapter">Texte</label>
            <textarea id="chapter" name="chapter"><?php echo $workInProgress->getValue('chapter') ?></textarea>
            <?php echo $workInProgress->getFirstError('chapter'); ?>
        </div>
        <div class="links">
            <button type="submit">Publier</button>
            <ul>
                <li><a href="/billet/updatebillet" data-text="Accueil">Accueil</a></li>
            </ul>
        </div>
    </form>