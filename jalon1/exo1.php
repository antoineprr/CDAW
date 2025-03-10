<!doctype html>
<html>
    <head>
        <title>Exercice 1</title>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>Exercice 1</h1>
        Heure actuelle : 
        <?php
            date_default_timezone_set('Europe/Paris');
            echo date('H:i');        
        ?>
    </body>
</html>