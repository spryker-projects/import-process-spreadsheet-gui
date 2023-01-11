<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form\DataProvider;

use Google\Service\Exception;
use SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessSpreadsheetFacadeInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Exception\SpreadsheetAccessDeniedException;
use SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form\ImportSheetForm;

class ImportSheetFormDataProvider
{
    /**
     * @var \SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessSpreadsheetFacadeInterface
     */
    protected ImportProcessSpreadsheetFacadeInterface $importProcessSpreadsheetFacade;

    /**
     * @var \SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface
     */
    protected ImportProcessFacadeInterface $importProcessFacade;

    /**
     * @param \SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessSpreadsheetFacadeInterface $importProcessSpreadsheetFacade
     * @param \SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface $importProcessFacade
     */
    public function __construct(
        ImportProcessSpreadsheetFacadeInterface $importProcessSpreadsheetFacade,
        ImportProcessFacadeInterface $importProcessFacade
    ) {
        $this->importProcessSpreadsheetFacade = $importProcessSpreadsheetFacade;
        $this->importProcessFacade = $importProcessFacade;
    }

    /**
     * @param string $spreadsheetUrl
     *
     * @return array[]
     */
    public function getOptions(string $spreadsheetUrl): array
    {
        return [
            ImportSheetForm::OPTION_IMPORT_TYPES => $this->getAvailableImportTypes($spreadsheetUrl),
        ];
    }

    /**
     * @param string $spreadsheetUrl
     *
     * @throws \SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Exception\SpreadsheetAccessDeniedException
     *
     * @return array
     */
    protected function getAvailableImportTypes(string $spreadsheetUrl): array
    {
        try {
            $spreadsheetId = $this->importProcessSpreadsheetFacade->getSheetIdFromUrl($spreadsheetUrl);
            $sheetsTitles = $this->importProcessSpreadsheetFacade->getSheetsTitles($spreadsheetId);
        } catch (Exception $e) {
            if ($e->getCode() === 403) {
                throw new SpreadsheetAccessDeniedException();
            }

            throw $e;
        }

        $importTypes = $this->filterImportTypesByAvailableImportTypes($sheetsTitles);

        return array_combine($importTypes, $importTypes);
    }

    /**
     * @param array $importTypes
     *
     * @return array
     */
    protected function filterImportTypesByAvailableImportTypes(array $importTypes): array
    {
        $availableImportTypes = $this->importProcessFacade->getAvailableOrderedImportTypes();
        $importTypes = array_filter($importTypes, static function (string $importType) use ($availableImportTypes) {
            return in_array($importType, $availableImportTypes, true);
        });

        return $importTypes;
    }
}
