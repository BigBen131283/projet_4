<form method="post" novalidate action="">
        
        <h1>Inscription</h1>
        <?php echo $errorHandler->getFirstError('flashmessage'); ?></p>
        <div class="inputBox">
            <input type="email" name="email" required id="email" value="<?php echo $errorHandler->getValue('email') ?>">
            <label for="email">email</label>
            <?php echo $errorHandler->getFirstError('email'); ?></p>
            
        </div>
        <div class="inputBox">
            <input type="text" name="pseudo" required id="pseudo" value="<?php echo $errorHandler->getValue('pseudo') ?>">
            <label for="pseudo">Pseudo</label>
            <?php echo $errorHandler->getFirstError('pseudo'); ?>
        </div>
        <div class="inputBox">
            <input type="password" name="pass" required id="pass" value="<?php echo $errorHandler->getValue('pass') ?>">
            <label for="pass">Mot de passe</label>
            <p class="myerror"> <?php echo $errorHandler->getFirstError('pass'); ?>
        </div>
        <div class="inputBox">
            <input type="password" name="confirm-pass" required id="confirm-pass" value="<?php echo $errorHandler->getValue('confirm-pass') ?>">
            <label for="confirm-pass">Confirmer le mot de passe</label>
            <?php echo $errorHandler->getFirstError('confirm-pass'); ?>
        </div>
        <div class="links">
            <button type="submit">S'inscrire</button>
            <ul>
                <li><a href="#" data-text="Mot de passe oublié">Mot de passe oublié</a></li>
            </ul>
        </div>
    </form>