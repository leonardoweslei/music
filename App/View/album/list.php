<ol class="breadcrumb">
    <li><span class="artist-list-action">Artists</span></li>
    <li><?php echo $artist->name; ?></li>
    <li class="active">Albums</li>
</ol>

<div class="row placeholders">
    <?php foreach ($albums as $album): ?>
        <div class="col-xs-6 col-sm-3">
            <div class="album-cover track-list-action" data-id="<?php echo $album->idartist ?>">

                <img src="<?php echo $this->getBasePath(
                    array('album', 'art', $album->idalbum, $artist->name, $album->name)
                ); ?>"
                     alt="<?php echo $album->name; ?>">

                <div class="art-caption"><span><?php echo $album->name; ?></span></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>