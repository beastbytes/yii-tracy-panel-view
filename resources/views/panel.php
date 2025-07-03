<?php
/** @var array $renders */
?>
<h2>View</h2>
<table>
    <thead>
    <tr>
        <th>File</th>
        <th>Parameters</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($renders as $render): ?>
    <tr>
        <td><?=$render['file']?></td>
        <td>
            <ul>
                <?php foreach ($render['params'] as $key => $value): ?>
                <li><?=$key?>: <?=$value?></li>
                <?php endforeach;?>
            </ul>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>