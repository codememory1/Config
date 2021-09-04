# Configuration

## Установка

```
composer require codememory/config
```

> Обязательно выполняем следующие команды, после уставки пакета

* Создание глобальной конфигурации, если ее не существует
    * `php vendor/bin/gc-cdm g-config:init`
* Merge всей конфигурации
    * `php vendor/bin/gc-cdm g-config:merge --all`

> Папка **.config** хранит в себе глобальную конфигурацию пакетов **codememory**

## Обзор global config

```json
{
  "configuration": {
    "pathWithConfigs": "configs/",  // Путь к папке со всеми режимами и их конфигами
    "mode": "development"          // Активный режим конфигурации
  }
}
```

## Использование

#### Test конфиг
```yaml
# Конфигурация должна начинаться с ключа, который соответствует с именем файла
test:
  # Бинды, которые будут доступны во всех файла конфигурации
  binds:
    configPath: "configs"
  paths:
    config: "%configPath%"
```

#### Получение данных

```php
<?php

use Codememory\Components\Configuration\Configuration;

require_once 'vendor/autoload.php';

// Открывает определенный конфиг
$testConfig = Configuration::getInstance()->open('test');

// Получаем значение по ключу
echo $testConfig->get('paths.config'); // codememory/configs
```

### Создание Custom режима
```php
use Codememory\Components\Configuration\Modes\AbstractMode;

class MyMode extends AbstractMode
{

    public function getModeName() : string
    {
    
        return 'myMode';
      
    }
    
    public function getSubdirectory() : string
    {
    
        return '/my_mode';
        
    }
    
    public function getConfigsWithData() : array
    {
    
        // Обработчик получение всей конфигурации для данного режима
    
    }

}

Configuration::getInstance()->addModeHandler('MyMode');

// Осталось в глобальной конфигурации у опции "configuration -> mode"
// поставить значение "myMode"
```