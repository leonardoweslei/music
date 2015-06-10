<ol class="breadcrumb">
    <li><span class="artist-list-action">Artists</span></li>
    <li>
        <span class="album-list-action" data-id="<?php echo $artist->idartist; ?>"><?php echo $artist->name; ?></span>
    </li>
    <li><?php echo $album->name; ?></li>
    <li class="active">Tracks</li>
</ol>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <?php foreach ($dataTable['label'] as $label): ?>
                <th><?php echo $label; ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($dataTable['data'] as $line): ?>
            <tr>
                <?php foreach ($dataTable['label'] as $key => $label): ?>
                    <td><?php echo $line[$key]; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>