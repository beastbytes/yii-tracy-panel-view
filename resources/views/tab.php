<?php
/**
 * @var int $total
 * @var TranslatorInterface $translator
 */

use BeastBytes\Yii\Tracy\Panel\View\Panel;
use Yiisoft\Translator\TranslatorInterface;

$translator = $translator->withDefaultCategory(Panel::MESSAGE_CATEGORY);

echo $translator->translate('view.views', ['total' => $total]);