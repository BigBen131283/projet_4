<form method="post" novalidate action="">    
    <h1>Saisissez votre password</h1>
    <?php echo $errorHandler->getFirstError('flashmessage'); ?></p>
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
        <button type="submit">Réinitialiser</button>
    </div>
</form>