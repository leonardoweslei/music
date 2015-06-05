<h1 class="page-header"><?php echo $dataTable['header']; ?></h1>
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
                <?php foreach ($line as $lineInfo): ?>
                    <td><?php echo $lineInfo; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>