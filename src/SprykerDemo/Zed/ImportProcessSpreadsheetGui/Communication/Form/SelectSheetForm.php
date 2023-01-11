<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\ImportProcessSpreadsheetGuiCommunicationFactory getFactory()
 */
class SelectSheetForm extends AbstractType
{
    public const FIELD_SHEET_URL = 'sheetUrl';
    public const BLOCK_PREFIX = 'selectSheetForm';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addSheetUrlField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSheetUrlField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_SHEET_URL, TextType::class, [
            'label' => 'Spreadsheet URL',
            'required' => true,
            'attr' => [
                'placeholder' => '',
            ],
            'constraints' => [
                new Regex([
                    'pattern' => '/^https:\/\/docs.google.com\/spreadsheets\/d\/[a-zA-Z0-9_-]+\/edit(.*)$/',
                    'message' => 'Please enter a valid Google Spreadsheet URL',
                ]),
            ],
        ]);

        return $this;
    }
}
