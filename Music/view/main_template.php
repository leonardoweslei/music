<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Music</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <link rel="stylesheet" type="text/css"
          href="http://yui.yahooapis.com/combo?2.9.0/build/datatable/assets/skins/sam/datatable.css">
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css"
          type="text/css" media="all"/>
    <link rel="stylesheet" href="assets/blueimp-file-upload.jquery/css/jquery.fileupload-ui.css">
    <link href="content/css/style.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery.min.js"></script>
    <script src="assets/jquery-ui/jquery-ui.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.3/jquery.xdomainrequest.min.js"></script>
    <script type="text/javascript"
            src="http://yui.yahooapis.com/combo?2.9.0/build/yahoo-dom-event/yahoo-dom-event.js&2.9.0/build/connection/connection_core-min.js&2.9.0/build/datasource/datasource-min.js&2.9.0/build/element/element-min.js&2.9.0/build/datatable/datatable-min.js&2.9.0/build/json/json-min.js"></script>

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-theme.min.css">
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    <script src="assets/blueimp-file-upload.jquery/js/vendor/jquery.ui.widget.js"></script>
    <script src="assets/blueimp-file-upload.jquery/js/jquery.iframe-transport.js"></script>
    <script src="assets/blueimp-file-upload.jquery/js/jquery.fileupload.js"></script>
    <script src="assets/blueimp-file-upload.jquery/js/jquery.fileupload-ui.js"></script>

    <script type="text/javascript" src="content/js/site.js"></script>
    <script type="text/javascript" src="content/js/seedrandom.js"></script>
    <script language="Javascript" type="text/javascript">
        $(document).ready(function () {
            app.init();
        });
    </script>
</head>
<body>
<div id="content" class="yui-skin-sam">
    <div id="tabs">
        <div id="player-holder">
            <audio id="player" controls="controls" autoplay="autoplay"></audio>
            <table cellspacing="0">
                <tr>
                    <td class="cover">
                        <span class="artist">-</span>
                        <span class="title">-</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="progressbar"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0">
                            <tr>
                                <td><a href="#" class="btnPrevious"><span>Previous</span></a></td>
                                <td>
                                    <a href="#pause" class="btnPause hide"><span>Pause</span></a>
                                    <a href="#play" class="btnPlay"><span>Play</span></a>
                                </td>
                                <td><a href="#" class="btnNext"><span>Next</span></a></td>
                            </tr>
                        </table>
                    </td>
                    <td><a href="#" class="btnShuffle"><span>Shuffle</span></a></td>
                </tr>
                <tr>
                    <td class="actions">
                    </td>
                </tr>
            </table>
        </div>
        <ul class="main">
            <li><a href="#tabs-br">Browse</a></li>
            <li><a href="#tabs-pl">Playlist</a></li>
            <li><a href="#tabs-up">Upload</a></li>
            <li><a href="#tabs-ly">Lyrics</a></li>
        </ul>
        <div id="tabs-br" class="artists">
            <div id="artists-table"></div>
        </div>
        <div id="tabs-pl">
            <div id="search-container">
                Search: <input type="text" name="s" id="s" value=""/>
                <img src="content/images/help16.png" alt=""
                     title="Supported syntax: regex, ||, &&, and ! for negative expressions. Example: 'scott pilgrim && !beck || the beatles'; Parentheses are also supported."/>
                <img src="content/images/loading.gif" alt="" class="search-loading hide"/>

                <div class="update-meta-container">
                    <div class="edit-meta">
                        <a href="#" class="link">Edit meta data</a>
                    </div>
                    <div class="edit-form hide">
                        <form method="post" action="update" id="update-meta-form">
                            Folder: <input type="text" name="folder" id="folder" style="width:200px"/>
                            Artist: <input type="text" name="artist" id="artist" style="width:200px"/>
                            Album: <input type="text" name="album" id="album" style="width:200px"/>
                            Track: <input type="text" name="track" id="track" style="width:50px"/>
                            Title: <input type="text" name="title" id="title" style="width:250px"/>
                            <input type="submit" name="save" value="Save" class="save-button"/>
                            <a href="#" class="cancel-button">cancel</a>
                        </form>
                    </div>
                </div>
            </div>
            <div id="songs-table"></div>
        </div>
        <div id="tabs-up">
            <p>Drag files here to upload.</p>

            <p>Supported: MP3</p>

            <div id="fileupload">
                <form action="up" method="POST" enctype="multipart/form-data">
                    <div class="fileupload-buttonbar">
                        <label class="fileinput-button">
                            <span>Add files...</span>
                            <input type="file" name="files[]" multiple>
                        </label>
                    </div>
                </form>
                <div class="fileupload-content">
                    <table class="files"></table>
                    <div class="fileupload-progressbar"></div>
                </div>
            </div>
            <script id="template-upload" type="text/x-jquery-tmpl">
                <tr class="template-upload{{if error}} ui-state-error{{/if}}">
                    <td class="name">${name}</td>
                    <td class="size">${sizef}</td>
                    {{if error}}
                        <td class="error" colspan="2">Error:
                            {{if error === 'maxFileSize'}}File is too big
                            {{else error === 'minFileSize'}}File is too small
                            {{else error === 'acceptFileTypes'}}Filetype not allowed
                            {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                            {{else}}${error}
                            {{/if}}
                        </td>
                    {{else}}
                        <td class="progress"><div></div></td>
                        <td class="start"><button>Start</button></td>
                    {{/if}}
                    <td class="cancel"><button>Cancel</button></td>
                </tr>




            </script>
            <script id="template-download" type="text/x-jquery-tmpl">
                <tr class="template-download{{if error}} ui-state-error{{/if}}">
                    {{if error}}
                        <td></td>
                        <td class="name">${name}</td>
                        <td class="size">${sizef}</td>
                        <td class="error" colspan="2">Error:
                            {{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
                            {{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
                            {{else error === 3}}File was only partially uploaded
                            {{else error === 4}}No File was uploaded
                            {{else error === 5}}Missing a temporary folder
                            {{else error === 6}}Failed to write file to disk
                            {{else error === 7}}File upload stopped by extension
                            {{else error === 'maxFileSize'}}File is too big
                            {{else error === 'minFileSize'}}File is too small
                            {{else error === 'acceptFileTypes'}}Filetype not allowed
                            {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                            {{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
                            {{else error === 'emptyResult'}}Empty file upload result
                            {{else}}${error}
                            {{/if}}
                        </td>
                    {{else}}
                        <td class="name">
                            <a href="${url}" onclick="window.location='${url}'">${name}</a>
                        </td>
                        <td class="size">${sizef}</td>
                        <td colspan="2"></td>
                    {{/if}}
                </tr>




            </script>

        </div>
        <div id="tabs-ly" class="lyrics">
            <h1></h1>

            <div id="capa"></div>
            <div id="conteudo">
                <h1 id="titulo"></h1>

                <h2 id="artista"></h2>

                <div id="letra"></div>
            </div>
            <div id="controle">
                <a href="#" id="diminuir">A-</a>
                <a href="#" id="normal">A</a>
                <a href="#" id="aumentar">A+</a>
            </div>
        </div>
    </div>
</div>
<div class="preloadNotice"></div>
</body>
</html>