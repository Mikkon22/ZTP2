<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Security\Voter\TagVoter;
use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller responsible for managing tags.
 */
#[Route('/tag')]
#[IsGranted('ROLE_USER')]
class TagController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param TagService $tagService the tag service
     */
    public function __construct(private TagService $tagService)
    {
    }

    /**
     * Displays a list of tags for the current user.
     *
     * @return Response the response object
     */
    #[Route('/', name: 'app_tag_index', methods: ['GET'])]
    public function index(): Response
    {
        $tags = $this->tagService->getTagsByUser($this->getUser());

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * Handles creation of a new tag via form.
     *
     * @param Request $request the HTTP request
     *
     * @return Response the response object
     */
    #[Route('/new', name: 'app_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $tag->setOwner($this->getUser());

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->createTag($tag);

            $this->addFlash('success', 'common.success_tag_created');

            return $this->redirectToRoute('app_tag_index');
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    /**
     * Handles creation of a new tag via AJAX.
     *
     * @param Request $request the HTTP request
     *
     * @return JsonResponse the JSON response object
     */
    #[Route('/ajax/new', name: 'app_tag_ajax_new', methods: ['POST'])]
    public function ajaxNew(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->isCsrfTokenValid('ajax-tag', $request->headers->get('X-CSRF-TOKEN'))) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid CSRF token'], 400);
        }

        if (empty($data['name']) || '' === trim($data['name'])) {
            return new JsonResponse(['success' => false, 'error' => 'common.please_enter_tag_name'], 400);
        }

        $tag = new Tag();
        $tag->setName($data['name']);
        $tag->setOwner($this->getUser());

        $this->tagService->createTag($tag);

        return new JsonResponse([
            'success' => true,
            'tag' => [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ],
        ]);
    }

    /**
     * Handles editing of an existing tag.
     *
     * @param Request $request the HTTP request
     * @param Tag     $tag     the tag entity
     *
     * @return Response the response object
     */
    #[Route('/{id}/edit', name: 'app_tag_edit', methods: ['GET', 'POST'])]
    #[IsGranted(TagVoter::EDIT, subject: 'tag')]
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->updateTag($tag);

            $this->addFlash('success', 'common.success_tag_updated');

            return $this->redirectToRoute('app_tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    /**
     * Handles deletion of a tag.
     *
     * @param Request $request the HTTP request
     * @param Tag     $tag     the tag entity
     *
     * @return Response the response object
     */
    #[Route('/{id}', name: 'app_tag_delete', methods: ['POST'])]
    #[IsGranted(TagVoter::DELETE, subject: 'tag')]
    public function delete(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $this->tagService->deleteTag($tag);

            $this->addFlash('success', 'common.success_tag_deleted');
        }

        return $this->redirectToRoute('app_tag_index');
    }
}
