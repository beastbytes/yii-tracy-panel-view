<?php

declare(strict_types=1);

namespace BeastBytes\Yii\Tracy\Panel\View;

use Yiisoft\View\Event\WebView\AfterRender;
use Yiisoft\Yii\Debug\Collector\CollectorTrait;
use Yiisoft\Yii\Debug\Collector\SummaryCollectorInterface;

final class WebViewCollector implements SummaryCollectorInterface
{
    use CollectorTrait;

    private array $renders = [];

    public function getCollected(): array
    {
        return $this->renders;
    }

    public function collect(AfterRender $event): void
    {
        if (!$this->isActive()) {
            return;
        }

        $this->renders[] = [
            'output' => $event->getResult(),
            'file' => $event->getFile(),
            'parameters' => $event->getParameters(),
            'locale' => $event->getView()->getLocale(),
            'theme' => $event->getView()->getTheme(),
        ];
    }

    public function getSummary(): array
    {
        if (!$this->isActive()) {
            return [];
        }

        return [
            'total' => count($this->renders),
        ];
    }

    private function reset(): void
    {
        $this->renders = [];
    }
}