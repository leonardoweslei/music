<h1 class="page-header">Artists</h1>

<div class="row placeholders">
    <?php foreach ($artists as $artist): ?>
        <div class="col-xs-6 col-sm-3 placeholder">
            <img src="<?php echo $this->getBasePath(array('artist', 'art', $artist->idartist, $artist->name)); ?>"
                 class="img-responsive"
                 alt="<?php echo $artist->name; ?>">
            <h4><?php echo $artist->name; ?></h4>
        </div>
    <?php endforeach; ?>
</div>