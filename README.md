# ImportProcessSpreadsheetGui Module
[![Build Status](https://travis-ci.org/spryker/import-process-spreadsheet-gui.svg)](https://travis-ci.org/spryker/import-process-spreadsheet-gui)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

CategoryExporter contains the client to read categories from key-value storage.

## Installation

```
composer require spryker-projects/import-process-spreadsheet-gui
```

## Configuration

Register the `SprykerDemo` namespace.

```
// config/Shared/config_default.php

$config[KernelConstants::CORE_NAMESPACES] = [
    // ...
    'SprykerDemo',
    // ...
];
```

## Integration

Add a link in the project to access page selecting spreadsheet with the demo data. E.g.:
```
// src/Pyz/Zed/ProductManagement/Presentation/Index/index.twig

{% block action %}
    {{ createActionButton('/import-process-spreadsheet-gui/index/select-sheet', 'Product Import from Spreadsheet' | trans) }}
    // ...
{% endblock %}
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/modules.html)
