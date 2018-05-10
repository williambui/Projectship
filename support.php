<?php

function generatePage($body, $title="Example") {
    $page = <<<EOPAGE
<!doctype html>
<html>
    <head> 
        <meta charset="utf-8" />
        <link rel="stylesheet" href="projectship.css" />
        <title>$title</title>	
    </head>
            
    <body class=login>
            $body
    </body>
</html>
EOPAGE;

    return $page;
}
?>