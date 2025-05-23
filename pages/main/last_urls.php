<?php

function echoURL($row)
{
    echo '<div class="row"><a class="w-auto" href="' . $row['short'] . '" target="_blank">' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $row['short'] . '</a><span class="w-auto"> ' . $row['url'] . '</span></div>';
}

?>

<div class="row mt-5" id="url-div-last">
    <h5>Последние ссылки:</h5>
    <div id="url-div-last-list">
        <?php
        foreach ($urls as $row) {
            echoURL($row);
        }
        ?>
    </div>
</div>