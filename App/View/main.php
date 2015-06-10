<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Music Player</title>

    <link rel="shortcut icon" href="favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="assets/jquery-ui/themes/base/minified/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="assets/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/app.css"/>

    <!--[if lt IE 9]>
    <script src="assets/html5shiv/dist/html5shiv.min.js"></script>
    <script src="assets/respond/dest/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Music Player</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" placeholder="Email" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>
            </form>
        </div>
        <!--/.navbar-collapse -->
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="#artists" class="artist-list-action">Artists</a></li>
                <li><a href="#favorites" class="favorites-list-action">Favorites</a></li>
            </ul>
            <a href="#playlists" class="playlist-anchor-list">Playlists</a>
            <ul class="nav nav-sidebar">
                <li><a href="">1</a></li>
            </ul>
            <p>Import</p>
            <ul class="nav nav-sidebar">
                <li><a href="#local" class="import-local-action">Local folder in server</a></li>
                <li><a href="#legacy" class="import-legacy-action">Legacy table in database</a></li>
            </ul>
        </div>
        <div class="col-sm-3  col-md-2 player-container">
            <div class="thumbnail">
                <img src="" alt="">
                <a href="#" id="artist-detail-action">Artist name</a><br>
                <a href="#" id="album-detail-action">Album name</a>
            </div>
            <a id="player-prev-action" class="player-button glyphicon glyphicon-backward"></a>
            <a id="player-play-action" class="player-button glyphicon glyphicon-play"></a>
            <a id="player-pause-action" class="player-button glyphicon glyphicon-pause"></a>
            <a id="player-next-action" class="player-button glyphicon glyphicon-forward"></a>
            <a id="player-random-action" class="player-button glyphicon glyphicon-random"></a>
            <a id="player-repeat-action" class="player-button glyphicon glyphicon-repeat"></a>
            <a id="player-favorite-action" class="player-button glyphicon glyphicon-star"></a>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" id="main">

        </div>
    </div>
</div>
<script src="assets/jquery/dist/jquery.min.js"></script>
<script src="assets/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="js/jquery.musicplayer.js"></script>
<script type="text/javascript" src="js/app.js"></script>
</body>
</html>