<h1 class="page-header">Artists</h1>

<div class="row placeholders">
    <?php foreach ($artists as $artist): ?>
        <div class="col-xs-6 col-sm-3">
            <div class="artist-cover">

                <img src="<?php echo $this->getBasePath(array('artist', 'art', $artist->idartist, $artist->name)); ?>"
                     alt="<?php echo $artist->name; ?>">

                <div class="art-caption"><span><?php echo $artist->name; ?></span></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>