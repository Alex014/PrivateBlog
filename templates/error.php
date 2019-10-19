<html>
    <body>
        <center>
            <h1>PHP Error</h1>
            <?foreach($errors as $error):?>
            <pre><?=$error?></pre>
            <?endforeach;?>
        </center>
    </body>
</html>