<?php

namespace BeastBytes\Yii\Tracy\Panel\View\Tests;

use BeastBytes\Yii\Tracy\ContainerProxy;
use BeastBytes\Yii\Tracy\Panel\View\Panel;
use HttpSoft\Message\ResponseFactory;
use HttpSoft\Message\StreamFactory;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\DataResponse\DataResponseFactory;
use Yiisoft\Strings\Inflector;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;
use Yiisoft\Translator\IntlMessageFormatter;
use Yiisoft\Translator\CategorySource;
use Yiisoft\Translator\Message\Php\MessageSource;
use Yiisoft\Translator\Translator;
use Yiisoft\View\Event\WebView\AfterRender;
use Yiisoft\View\View;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Debug\WebViewCollector;
use Yiisoft\Yii\View\Renderer\InjectionContainer\InjectionContainerInterface;
use Yiisoft\Yii\View\Renderer\ViewRenderer;

class PanelTest extends TestCase
{
    private const COLOUR_NO_VIEWS = '#404040';
    private const COLOUR_VIEWS = '#0f49bf';
    private const PANEL = <<<HTML
<h1>View</h1>
<div class="tracy-inner"><div class="tracy-inner-container">
<table>
    <thead>
    <tr>
        <th>File</th>
        <th>Parameters</th>
    </tr>
    </thead>
    <tbody>{body}</tbody>
</table>
</div></div>
HTML;
    private const TAB = <<<TAB
<span title="View"><svg
    xmlns="http://www.w3.org/2000/svg"
    height="24px"
    viewBox="0 -960 960 960"
    width="24px"
    fill="{iconColour}"
>
    <path 
        d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 
        127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 
        76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 
        218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 
        101 144.5 160.5T480-280Z"
    />
</svg><span class="tracy-label">{viewCount}&nbsp;View{plural}</span></span>
TAB;

    private const LOCALE = 'en-GB';

    private static WebViewCollector $collector;
    private static ContainerInterface $container;
    private static ContainerInterface $containerProxy;

    private ?Panel $panel = null;

    #[After]
    public function tearDown(): void
    {
        $this->panel->shutdown();
    }

    #[Before]
    public function setUp(): void
    {
        self::$collector = new WebViewCollector();
        self::$container = new SimpleContainer([
            View::class => (new View())
                ->setParameter(
                    'translator',
                    (new Translator())
                        ->withLocale(self::LOCALE)
                        ->addCategorySources(new CategorySource(
                            Panel::MESSAGE_CATEGORY,
                            new MessageSource(
                                dirname(__DIR__)
                                . DIRECTORY_SEPARATOR . 'resources'
                                . DIRECTORY_SEPARATOR . 'messages',
                            ),
                            new IntlMessageFormatter(),
                        )),
                )
            ,
        ]);

        $this->panel = (new Panel(self::$collector));

        self::$containerProxy = new ContainerProxy(self::$container);
        $this->panel = $this->panel->withContainer(self::$containerProxy);
        $this->panel->startup();
    }

    #[Test]
    public function viewPath(): void
    {
        $this->assertSame(
            dirname(__DIR__)
            . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
            $this->panel->getViewPath());
    }

    #[DataProvider('viewsProvider')]
    #[Test]
    public function views(string $view, bool $withLayout, array $params, int $viewCount): void
    {
        $viewRenderer = $this->getRenderer()
            ->withLayout($withLayout ? $this->getViewPath() . DIRECTORY_SEPARATOR . 'layout.php' : null)
        ;

        $viewRenderer->renderAsString($view, $params);
        
        $this->assertStringMatchesFormat(
            strtr(
                self::TAB,
                [
                    '{iconColour}' => ($viewCount === 0 ? self::COLOUR_NO_VIEWS : self::COLOUR_VIEWS),
                    '{plural}' => ($viewCount === 1 ? '' : 's'),
                    '{viewCount}' => $viewCount,
                ],
            ),
            $this->panel->getTab(),
        );

        $this->assertSame(
            $this->stripWhitespace(strtr(
                self::PANEL,
                [
                    '{body}' => $this->getPanelBody($view, $withLayout, $params)
                ],
            )),
            $this->stripWhitespace($this->panel->getPanel()),
        );
    }

    public static function viewsProvider(): array
    {
        $inflector = new Inflector();

        return [
            'View with layout, no params' => [
                'view' => 'view1',
                'withLayout' => true,
                'params' => [],
                'viewCount' => 2,
            ],
            'View with layout and params' => [
                'view' => 'view1',
                'withLayout' => true,
                'params' => [
                    'skjfj' => 'nmbsr',
                    'psfjjk' => 99,
                    'inflector' => $inflector,
                ],
                'viewCount' => 2,
            ],
            'View no layout or params' => [
                'view' => 'view1',
                'withLayout' => false,
                'params' => [],
                'viewCount' => 1,
            ],
            'View with params, no layout' => [
                'view' => 'view1',
                'withLayout' => false,
                'params' => [
                    'poncbsx' => 'sdfj',
                    'nsbeuhu' => 99,
                    'inflector' => $inflector,
                ],
                'viewCount' => 1,
            ],
            'View partial view, layout and params' => [
                'view' => 'view2',
                'withLayout' => true,
                'params' => [
                    'bcseuk' => 'kjht55hu',
                    'mnbcfib' => 66,
                    'inflector' => $inflector,
                ],
                'viewCount' => 3,
            ],
        ];
    }

    private function array2String(array $arguments): string
    {
        $result = [];
        foreach ($arguments as $key => $value) {
            $result[] = is_int($key)
                ? $value
                : sprintf('%s&nbsp;=&nbsp;%s', $key, $value)
            ;
        }

        return empty($result)
            ? ''
            : '<ul><li>' . implode('</li><li>', $result) . '</li></ul>'
        ;
    }

    private function getPanelBody(string $view, bool $withLayout, array $params): string
    {
        return strtr(
            '{partial}<tr><td>{view}</td><td>{params}</td></tr>{layout}',
            [
                '{layout}' => $withLayout
                    ? strtr(
                        '<tr><td>{view}</td><td><ul><li>content: string</li></ul></td></tr>',
                        [
                            '{view}' => $this->getViewPath() . DIRECTORY_SEPARATOR . 'layout.php',
                        ]
                    )
                    : ''
                ,
                '{params}' => $this->getParams($params),
                '{partial}' => $view === 'view2'
                    ? strtr(
                        '<tr><td>{view}</td><td></td></tr>',
                        [
                            '{view}' => $this->getViewPath() . DIRECTORY_SEPARATOR . '_partial_view.php',
                        ]
                    )
                    : ''
                ,
                '{view}' => $this->getViewPath() . DIRECTORY_SEPARATOR . $view . '.php',
            ]
        );
    }

    private function getParams(array $params): string
    {
        if (empty($params)) {
            return '';
        }

        $result = [];
        foreach ($params as $key => $value) {
            $result[] = $key
                . ': '
                . get_debug_type($value)
                . ((is_scalar($value) && $key !== 'content')
                    ? " ($value)"
                    : ''
                )
            ;
        }
        return '<ul><li>' . implode('</li><li>', $result) . '</li></ul>';
    }

    private function stripWhitespace(string $string): string
    {
        return preg_replace('/>\s+</', '><', $string);
    }

    private function getRenderer(
        ?InjectionContainerInterface $injectionContainer = null,
    ): ViewRenderer {
        $collector = self::$collector;

        return new ViewRenderer(
            new DataResponseFactory(new ResponseFactory(), new StreamFactory()),
            new Aliases(['@views' => $this->getViewPath()]),
            new WebView('@views', new SimpleEventDispatcher(
                static function ($event) use ($collector) {
                    if ($event instanceof AfterRender) {
                        $collector->collect($event);
                    }
                }
            )),
            '@views',
            '@views/layout.php',
            injectionContainer: $injectionContainer
        );
    }

    private function getViewPath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Support' . DIRECTORY_SEPARATOR . 'views';
    }
}