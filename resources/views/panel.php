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
                <?php if (!empty($render['parameters'])): ?>
                <ul>
                    <?php foreach ($render['parameters'] as $key => $value): ?>
                        <li>
                            <?= $key ?>: <?= get_debug_type($value) ?>
                            <?php if (is_scalar($value) && $key !== 'content'): ?>
                                (<?= $value ?>)
                            <?php endif;?>
                        </li>
                    <?php endforeach;?>
                </ul>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>