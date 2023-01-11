<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerDemo\Zed\ImportProcessSpreadsheetGui\Communication\ImportProcessSpreadsheetGuiCommunicationFactory getFactory()
 */
class ImportSheetForm extends AbstractType
{
    public const FIELD_IMPORT_TYPES = 'importTypes';
    public const BLOCK_PREFIX = 'ImportSheetForm';

    /**
     * @var string
     */
    public const OPTION_IMPORT_TYPES = 'import_types';

    /**
     * @var string
     */
    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_IMPORT_TYPES);

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
        $this->addImportTypesField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addImportTypesField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_IMPORT_TYPES,
            Select2ComboBoxType::class,
            [
                'label' => 'Import Types',
                'required' => false,
                'choices' => array_flip($options[static::OPTION_IMPORT_TYPES]),
                'multiple' => true,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ],
        );

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }
}
