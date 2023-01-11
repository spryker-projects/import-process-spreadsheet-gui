<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication;

use SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessSpreadsheetFacadeInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form\DataProvider\ImportSheetFormDataProvider;
use SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form\ImportSheetForm;
use SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form\SelectSheetForm;
use SprykerDemo\Zed\ImportProcessSpreadsheetGui\ImportProcessSpreadsheetGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class ImportProcessSpreadsheetGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSelectSheetForm(): FormInterface
    {
        return $this
            ->getFormFactory()
            ->create(SelectSheetForm::class);
    }

    /**
     * @param string $spreadsheetUrl
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getImportSheetForm(string $spreadsheetUrl): FormInterface
    {
        $dataProvider = $this->createImportSheetFormDataProvider();

        return $this
            ->getFormFactory()
            ->create(ImportSheetForm::class, [], $dataProvider->getOptions($spreadsheetUrl));
    }

    /**
     * @return \SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form\DataProvider\ImportSheetFormDataProvider
     */
    public function createImportSheetFormDataProvider(): ImportSheetFormDataProvider
    {
        return new ImportSheetFormDataProvider(
            $this->getImportProcessSpreadsheetFacade(),
            $this->getImportProcessFacade()
        );
    }

    /**
     * @return \SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface
     */
    public function getImportProcessFacade(): ImportProcessFacadeInterface
    {
        return $this->getProvidedDependency(ImportProcessSpreadsheetGuiDependencyProvider::FACADE_IMPORT_PROCESS);
    }

    /**
     * @return \SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessSpreadsheetFacadeInterface
     */
    public function getImportProcessSpreadsheetFacade(): ImportProcessSpreadsheetFacadeInterface
    {
        return $this->getProvidedDependency(ImportProcessSpreadsheetGuiDependencyProvider::FACADE_IMPORT_PROCESS_SPREADSHEET);
    }
}
