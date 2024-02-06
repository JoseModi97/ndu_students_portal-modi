<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 11/30/2023
 * @time: 9:02 PM
 */

/* @var string $errorMessage */
$webRoot = dirname(__DIR__, 2);
$webRoot = dirname($webRoot . '/web');
echo $webRoot;
?>

<!DOCTYPE html>
<html class="h-100" lang="eng">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="http://localhost:81/ndu_students_portal/web/img/logo.png" type="image/x-icon">
        <link rel="icon" href="http://localhost:81/ndu_students_portal/web/img/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="http://localhost:81/ndu_students_portal/web/css/error.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poiret+One">
        <title>SMIS</title>
    </head>
    <body>
        <h5>Error !</h5>
        <p>
            <?=$errorMessage?>
            <br/>
            <br/>
            Do you need help? Send a message to smis_support@ndu.ac.ke
            <br/>
            <br/>
        </p>
    </body>
</html>
