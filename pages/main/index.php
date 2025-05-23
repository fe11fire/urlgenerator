<?php

use Api\Root\DB;

$urls = DB::query('SELECT `short`, `url` FROM `urls` ORDER BY `date_create` DESC LIMIT 5', fetch: true);


?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Generator</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>

<body class="mb-5">
    <div class="container">

        <form id="url-form" action="url">
            <div class="row mt-5">
                <div class="col-9">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">URL address</span>
                        <input type="text" class="form-control" id="url-form-input" name="url" aria-describedby="url" placeholder="http://" value="<?php echo isset($_GET['url']) ? substr($_GET['url'], 1) : 'http://' ?>">
                    </div>
                    <span id="url-span-error" class="text-danger d-none"></span>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
        <div class="row mt-5 d-none" id="url-div-current">
            <h5>Ссылка для <span id="url-span-current"></span></h5>
            <p><a target="_blank" id="url-a-current"></a></p>
        </div>
        <?php
        if (count($urls) > 0) {
            require 'pages/main/last_urls.php';
        }
        ?>
    </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $("#url-form").submit(function(e) {
        e.preventDefault();

        $('#url-span-error, #url-div-current').addClass('d-none');
        $('#url-span-error, #url-a-current, #url-span-current').html('');

        let form = $(this);
        var actionUrl = form.attr('action');
        var url = form.find('input[name="url"]').val();

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            success: function(data) {
                let d = JSON.parse(data);

                if (d[0] == 'OK') {
                    $('#url-a-current').html('<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/'; ?>' + d[1]);
                    $('#url-a-current').attr('href', d[1]);
                    $('#url-span-current').html(url);
                    $('#url-div-current').removeClass('d-none');
                    $('#url-form-input').val('http://');

                    if ($('#url-div-last')) {
                        $('#url-div-last-list').prepend('<div class="row"><a class="w-auto" href="' + d[1] + '" target="_blank"><?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/'; ?>' + d[1] + '</a><span class="w-auto"> ' + url + '</span></div>');
                    }
                }

                if (d[0] == 'error') {
                    $('#url-span-error').html(d[1]);
                    $('#url-span-error').removeClass('d-none');
                }
            }
        });

    });

    $(document).ready(function() {
        window.history.replaceState({}, document.title, '/');
    });
</script>