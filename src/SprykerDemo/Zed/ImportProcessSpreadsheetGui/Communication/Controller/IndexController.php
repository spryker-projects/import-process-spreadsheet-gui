<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Controller;

use SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Exception\SpreadsheetAccessDeniedException;
use SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form\ImportSheetForm;
use SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form\SelectSheetForm;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\ImportProcessSpreadsheetGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    use BundleConfigResolverAwareTrait;

    protected const SPREADSHEET_ACCESS_DENIED_ERROR_MESSAGE = 'Access denied to the provided spreadsheet. Please grant access by link to the provided spreadsheet.';
    protected const PARAM_ID_PROCESS = 'id-process';
    protected const PARAM_SHEET_URL = 'sheet-url';
    protected const IMPORT_PROCESS_GUI_VIEW_URL = '/import-process-gui/index/view';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function selectSheetAction(Request $request)
    {
        $form = $this->getFactory()->getSelectSheetForm();
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->viewResponse([
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectResponse(Url::generate('import-from-sheet', [
            static::PARAM_SHEET_URL => $form->getData()[SelectSheetForm::FIELD_SHEET_URL],
        ])->build());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function importFromSheetAction(Request $request)
    {
        $spreadsheetUrl = $request->query->get(static::PARAM_SHEET_URL);

        try {
            $form = $this->getFactory()->getImportSheetForm($spreadsheetUrl);
        } catch (SpreadsheetAccessDeniedException $e) {
            $this->addErrorMessage(self::SPREADSHEET_ACCESS_DENIED_ERROR_MESSAGE);

            return $this->redirectResponse(Url::generate('select-sheet')->build());
        }
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->viewResponse([
                'form' => $form->createView(),
            ]);
        }

        $importProcessTransfer = $this->getFactory()->getImportProcessSpreadsheetFacade()
            ->createImportProcess($spreadsheetUrl, $form->getData()[ImportSheetForm::FIELD_IMPORT_TYPES]);

        $this->getFactory()->getImportProcessFacade()->runDetachedImportProcess($importProcessTransfer);

        $this->addSuccessMessage('Import process started successfully.');

        return $this->redirectResponse(Url::generate(static::IMPORT_PROCESS_GUI_VIEW_URL, [
            static::PARAM_ID_PROCESS => $importProcessTransfer->getIdImportProcess(),
        ])->build());
    }
}
