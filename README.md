The Yii Tracy Panel View package integrates the [Tracy debugging tool](https://tracy.nette.org/)
into [Yii3](https://www.yiiframework.com/) by providing the necessary configuration.

## Requirements
- PHP 8.1 or higher

## Installation
Install the package using [Composer](https://getcomposer.org):

Either:
```shell
composer require-dev beastbytes/yii-tracy-panel-view
```
or add the following to the `require-dev` section of your `composer.json`
```json
"beastbytes/yii-tracy-panel-view": "<version_constraint>"
```

## Information Displayed
Provides information about the rendered view.
#### Tab
Does not show data on the tab.
#### Panel
Names of the rendered view, included partial views, and layout, and their parameters.

## License
The BeastBytes Yii Tracy Panel View package is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.