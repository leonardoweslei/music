<ol class="breadcrumb">
    <li class="active">Artists</li>
</ol>

<div class="row placeholders">
    <?php foreach ($artists as $artist): ?>
        <div class="col-xs-6 col-sm-3">
            <div class="artist-cover album-list-action" data-id="<?php echo $artist->idartist ?>">

                <img src="<?php echo $this->getBasePath(array('artist', 'art', $artist->idartist, $artist->name)); ?>"
                     alt="<?php echo $artist->name; ?>">

                <div class="art-caption"><span><?php echo $artist->name; ?></span></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>