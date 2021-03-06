<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\Controller\File;

use Darvin\FileBundle\Entity\AbstractFile;
use Darvin\FileBundle\Form\Type\FileEditType;
use Darvin\Utils\Flash\FlashNotifierInterface;
use Darvin\Utils\HttpFoundation\AjaxResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * File edit controller
 */
class EditController extends AbstractFileController
{
    /**
     * @var \Darvin\Utils\Flash\FlashNotifierInterface
     */
    private $flashNotifier;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $fullTemplate;

    /**
     * @var string
     */
    private $partialTemplate;

    /**
     * @param \Darvin\Utils\Flash\FlashNotifierInterface         $flashNotifier   Flash notifier
     * @param \Symfony\Component\Form\FormFactoryInterface       $formFactory     Form factory
     * @param \Symfony\Component\Routing\RouterInterface         $router          Router
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator      Translator
     * @param \Twig\Environment                                  $twig            Twig
     * @param string                                             $fullTemplate    Full template
     * @param string                                             $partialTemplate Partial template
     */
    public function __construct(
        FlashNotifierInterface $flashNotifier,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        TranslatorInterface $translator,
        Environment $twig,
        string $fullTemplate,
        string $partialTemplate
    ) {
        $this->flashNotifier = $flashNotifier;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->translator = $translator;
        $this->twig = $twig;
        $this->fullTemplate = $fullTemplate;
        $this->partialTemplate = $partialTemplate;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     * @param mixed                                     $id      File ID
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __invoke(Request $request, $id): Response
    {
        $file     = $this->getFile($id);
        $template = $request->isXmlHttpRequest() ? $this->partialTemplate : $this->fullTemplate;
        $twig     = $this->twig;

        $render = function (FormInterface $form) use ($file, $template, $twig) {
            return $twig->render($template, [
                'file' => $file,
                'form' => $form->createView(),
            ]);
        };

        $form = $this->createForm($file)->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($render($form));
        }
        if (!$form->isValid()) {
            if ($request->isXmlHttpRequest()) {
                return new AjaxResponse($render($form), false, FlashNotifierInterface::MESSAGE_FORM_ERROR);
            }

            $this->flashNotifier->formError();

            return new Response($render($form));
        }

        $this->em->flush();

        $message = $this->translator->trans('file.edit.success', [], 'darvin_file');

        if ($request->isXmlHttpRequest()) {
            return new AjaxResponse($render($this->createForm($file)), true, $message);
        }

        $this->flashNotifier->success($message);

        return new RedirectResponse($this->router->generate('darvin_file_edit', [
            'id' => $id,
        ]));
    }

    /**
     * @param \Darvin\FileBundle\Entity\AbstractFile $file File
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createForm(AbstractFile $file): FormInterface
    {
        return $this->formFactory->create(FileEditType::class, $file, [
            'action' => $this->router->generate('darvin_file_edit', [
                'id' => $file->getId(),
            ]),
        ]);
    }
}
