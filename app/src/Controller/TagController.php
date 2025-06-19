<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your.email@example.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use Doctrine\ORM\EntityManagerInterface;
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
     * Displays a list of tags for the current user.
     */
    #[Route('/', name: 'app_tag_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tags = $entityManager->getRepository(Tag::class)->findBy(['owner' => $this->getUser()]);

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * Handles creation of a new tag via form.
     */
    #[Route('/new', name: 'app_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tag = new Tag();
        $tag->setOwner($this->getUser());

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tag);
            $entityManager->flush();

            $this->addFlash('success', 'Tag created successfully.');

            return $this->redirectToRoute('app_tag_index');
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    /**
     * Handles creation of a new tag via AJAX.
     */
    #[Route('/ajax/new', name: 'app_tag_ajax_new', methods: ['POST'])]
    public function ajaxNew(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->isCsrfTokenValid('ajax-tag', $request->headers->get('X-CSRF-TOKEN'))) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid CSRF token'], 400);
        }

        if (empty($data['name'])) {
            return new JsonResponse(['success' => false, 'error' => 'Tag name is required'], 400);
        }

        $tag = new Tag();
        $tag->setName($data['name']);
        $tag->setOwner($this->getUser());

        $entityManager->persist($tag);
        $entityManager->flush();

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
     */
    #[Route('/{id}/edit', name: 'app_tag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        if ($tag->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot edit this tag.');
        }

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Tag updated successfully.');

            return $this->redirectToRoute('app_tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    /**
     * Handles deletion of a tag.
     */
    #[Route('/{id}', name: 'app_tag_delete', methods: ['POST'])]
    public function delete(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        if ($tag->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete this tag.');
        }

        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tag);
            $entityManager->flush();

            $this->addFlash('success', 'Tag deleted successfully.');
        }

        return $this->redirectToRoute('app_tag_index');
    }
}
