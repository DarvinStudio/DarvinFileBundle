<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\Form\Type;

use Darvin\FileBundle\Entity\AbstractFile;
use Darvin\Utils\ObjectNamer\ObjectNamerInterface;
use Darvin\Utils\Strings\StringsUtil;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * File edit form type
 */
class FileEditType extends AbstractType
{
    private const FALLBACK_TRANSLATION_DOMAIN = 'darvin_file';

    /**
     * @var \Darvin\Utils\ObjectNamer\ObjectNamerInterface
     */
    private $objectNamer;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var string
     */
    private $translationDomain;

    /**
     * @param \Darvin\Utils\ObjectNamer\ObjectNamerInterface     $objectNamer       Object namer
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator        Translator
     * @param array                                              $fields            Fields
     * @param string                                             $translationDomain Translation domain
     */
    public function __construct(ObjectNamerInterface $objectNamer, TranslatorInterface $translator, array $fields, string $translationDomain)
    {
        $this->objectNamer = $objectNamer;
        $this->translator = $translator;
        $this->fields = $fields;
        $this->translationDomain = $translationDomain;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $class = null !== $builder->getData() ? ClassUtils::getClass($builder->getData()) : AbstractFile::class;

        foreach ($this->fields[$class] ?? $this->fields[AbstractFile::class] as $name => $attr) {
            if ($attr['enabled']) {
                $builder->add($name, $attr['type'], $attr['options']);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $genericPrefix = 'file.entity.';

        $currentPrefix = null !== $form->getData() ? sprintf('%s.entity.', $this->objectNamer->name($form->getData())) : $genericPrefix;

        foreach ($view->children as $name => $field) {
            if (null !== $field->vars['label']) {
                continue;
            }

            $prefix = array_key_exists($name, $this->fields[AbstractFile::class]) ? $genericPrefix : $currentPrefix;

            $label = $prefix.StringsUtil::toUnderscore($name);

            $translated = $this->translator->trans($label, [], $this->translationDomain);

            if ($translated === $label) {
                $translated = $this->translator->trans($label, [], self::FALLBACK_TRANSLATION_DOMAIN);
            }

            $field->vars['label'] = $translated;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => AbstractFile::class,
            'csrf_protection'    => false,
            'translation_domain' => $this->translationDomain,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'darvin_file_edit';
    }
}
