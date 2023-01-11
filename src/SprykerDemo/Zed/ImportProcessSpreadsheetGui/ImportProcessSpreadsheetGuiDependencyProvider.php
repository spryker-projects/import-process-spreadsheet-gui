<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheetGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ImportProcessSpreadsheetGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const FACADE_IMPORT_PROCESS = 'FACADE_IMPORT_PROCESS';
    public const FACADE_IMPORT_PROCESS_SPREADSHEET = 'FACADE_IMPORT_PROCESS_SPREADSHEET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addImportProcessFacade($container);
        $container = $this->addImportProcessSpreadsheetsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addImportProcessFacade(Container $container)
    {
        $container->set(static::FACADE_IMPORT_PROCESS, function (Container $container) {
            return $container->getLocator()->importProcess()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addImportProcessSpreadsheetsFacade(Container $container)
    {
        $container->set(static::FACADE_IMPORT_PROCESS_SPREADSHEET, function (Container $container) {
            return $container->getLocator()->importProcessSpreadsheet()->facade();
        });

        return $container;
    }
}
