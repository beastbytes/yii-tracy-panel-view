<?php

declare(strict_types=1);

namespace BeastBytes\Yii\Tracy\Panel\View;

use BeastBytes\Yii\Tracy\Panel\CollectorPanel;
use BeastBytes\Yii\Tracy\ViewTrait;

class Panel extends CollectorPanel
{
    use ViewTrait;

    private const ICON = <<<ICON
<svg
    xmlns="http://www.w3.org/2000/svg"
    height="24px" 
    viewBox="0 -960 960 960"
    width="24px" 
    fill="#0f49bf"
>
    <path 
        d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 
        127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 
        76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 
        218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 
        101 144.5 160.5T480-280Z"
    />
</svg>
ICON;

    private const TITLE = 'View';

    protected function panelParameters(): array
    {
        return ['renders' => $this->getCollected()];
    }

    protected function panelTitle(): string
    {
        return self::TITLE;
    }

    protected function tabIcon(array $parameters): string
    {
        return self::ICON;
    }

    protected function tabParameters(): array
    {
        return $this->getSummary();
    }

    protected function tabTitle(): string
    {
        return self::TITLE;
    }
}