<div class="site">
    <?php
        $elements = explode('/', $_SERVER['REQUEST_URI']);
        if(count($elements) < 4)
        {
            $message = "Oups, vous devriez faire demi-tour...";
        }
        else
        {
            $mess = explode('=', $elements[3]);
            $message = urldecode($mess[1]);
        }
    ?>  
    <h1><?= $message?></h1>
</div>