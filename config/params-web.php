<?php

declare(strict_types=1);

use BeastBytes\Yii\Tracy\Panel\View\Panel as ViewPanel;
use Yiisoft\Definitions\Reference;
use Yiisoft\Yii\View\Renderer\Debug\WebViewCollector;

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