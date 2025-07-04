<?php
/**
 * @var array $renders
 * @var TranslatorInterface $translator
 */

use BeastBytes\Yii\Tracy\Panel\View\Panel;
use Yiisoft\Translator\TranslatorInterface;

$translator = $translator->withDefaultCategory(Panel::MESSAGE_CATEGORY);
?>
<table>
    <thead>
    <tr>
        <th><?= $translator->translate('view.heading.file') ?></th>
        <th><?= $translator->translate('view.heading.parameters') ?></th>
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