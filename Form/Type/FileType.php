<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * File form type
 */
class FileType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $fieldOptions = [
            'label'    => false,
            'required' => false,
        ];

        if (null !== $options['accept']) {
            $fieldOptions['attr'] = [
                'accept' => $options['accept'],
            ];
        }
        if (null !== $options['upload_max_size_mb']) {
            $fieldOptions['upload_max_size_mb'] = $options['upload_max_size_mb'];
        }

        $builder->add('file', \Symfony\Component\Form\Extension\Core\Type\FileType::class, $fieldOptions);
    }

    /**
     * {@inheritDoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars = array_merge($view->vars, [
            'disableable' => $options['disableable'],
            'editable'    => $options['editable'],
        ]);

        $help = (string)$view->children['file']->vars['help'];

        if ('' === $help) {
            return;
        }

        $view->children['file']->vars['help'] = null;

        $view->vars['help'] = (string)$view->vars['help'];

        if ('' !== $view->vars['help']) {
            $view->vars['help'] .= '<br>';
        }

        $view->vars['help'] .= $help;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'accept'             => null,
                'csrf_protection'    => false,
                'disableable'        => true,
                'editable'           => true,
                'required'           => false,
                'upload_max_size_mb' => null,
            ])
            ->setAllowedTypes('accept', ['string', 'null'])
            ->setAllowedTypes('disableable', 'boolean')
            ->setAllowedTypes('editable', 'boolean')
            ->setAllowedTypes('upload_max_size_mb', ['integer', 'null'])
            ->remove('data_class')
            ->setRequired('data_class');
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'darvin_file';
    }
}
