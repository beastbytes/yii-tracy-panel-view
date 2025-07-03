<?php

declare(strict_types=1);

use BeastBytes\Yii\Tracy\Panel\View\Panel as ViewPanel;
use BeastBytes\Yii\Tracy\Panel\View\WebViewCollector;
use Yiisoft\Definitions\Reference;

return [
    'beastbytes/yii-tracy' => [
        'panelConfig' => [
            'view' => [
                'class' => ViewPanel::class,
                '__construct()' => [Reference::to(WebViewCollector::class)]
            ],
        ],
    ],
];