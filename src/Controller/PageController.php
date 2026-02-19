<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\RevisionHistory;
use App\Form\PageType;
use App\Form\RevisionType;
use App\Form\PageUpdateType;
use App\Repository\PageRepository;
use App\Repository\RevisionHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\MarkupParserService;

final class PageController extends AbstractController
{
    #[Route('/Special:Create_Page', name: 'create_page')]
    public function index(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $page = new Page();

		$form = $this->createForm(Pagetype::class, $page);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $user = $security->getUser();

            $page->setCurrentRevision(0); // Sera modifié après
            $page->setPageType(0);
            $title = str_replace(' ', '_', $form->get('title')->getData());
            $page->setTitle($title);
            $em->persist($page);
            $em->flush();

            $revision = new RevisionHistory();
            $revision->setPageId($page);
            $revision->setWikitext($form->get('wikitext')->getData());
            $revision->setDate(new \DateTime());
            $revision->setAuthorId($user);
            $revision->setChanges(0);
            $em->persist($revision);
            $em->flush();

            $page->setCurrentRevision($revision->getId());

            $categories = $form->get('categories')->getData();
            foreach ($categories as $category) {
                $page->addCategory($category);
            }

            $em->flush();
            
            return $this->redirectToRoute('show_page',[
                'title' => $title
            ]);
		}

		return $this->render("page/index.html.twig", [
			"form" => $form->createView()
		]);
    }

    #[Route("/page/{title}", name: "show_page")]
    public function show(string $title, PageRepository $pageRepository, RevisionHistoryRepository $revisionHistoryRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
        
        $page = $pageRepository->findOneBy(['title' => $title]);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        
		

        $revision = $revisionHistoryRepository->findOneBy(['id' => $page->getCurrentRevision()]);
        $markupParser = new MarkupParserService();
        $text = $revision->getWikitext();
		$html = $markupParser->parse($text);

        return $this->render('page/show.html.twig', [
            'page' => $page,
            'title' => str_replace('_', ' ', $page->getTitle()),
            'revision' => $revision,
            'html' => $html
        ]);
    }

    #[Route("/page/{title}/delete", name: "delete_page")]
    public function delete(ManagerRegistry $doctrine, PageRepository $pageRepository, string $title): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $page = $pageRepository->findOneBy(['title' => $title]);
        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }
        $em = $doctrine->getManager();
        
        foreach ($page->getRevisionHistories() as $revision) {
            $em->remove($revision);
        }

        foreach ($page->getCategories() as $category) {
            $page->removeCategory($category);
        }

        $em->remove($page);
        $em->flush();

        return $this->redirectToRoute("index");
    }

    #[Route("/page/{title}/edit", name: "edit_page")]
    public function edit(Request $request, ManagerRegistry $doctrine, string $title, Security $security): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $doctrine->getManager();
        $user = $security->getUser();
        
        $page = $em->getRepository(Page::class)->findOneBy(['title' => $title]);
        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }


        $currentRevision = $em->getRepository(RevisionHistory::class)->find($page->getCurrentRevision());
        if (!$currentRevision) {
            throw $this->createNotFoundException('Page not found');
        }

        $revision = new RevisionHistory();
        $revision->setWikitext($currentRevision->getWikitext());
        $revision->setPageId($currentRevision->getPageId());
        $revision->setChanges($currentRevision->getChanges());
        $form = $this->createForm(RevisionType::class, $revision);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $revision->setDate(new \DateTime());
            $revision->setAuthorId($user);
            $em->persist($revision);
            $em->flush();

            $page->setCurrentRevision($revision->getId());
            $em->flush();
            return $this->redirectToRoute("index");
        }

        return $this->render("page/edit.html.twig", [
            "form" => $form->createView(),
            "page" => $page
        ]);
    }

    #[Route("/page/{title}/meta", name: "meta_page")]
    public function update(Request $request, ManagerRegistry $doctrine, string $title): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $doctrine->getManager();
        
        $page = $em->getRepository(Page::class)->findOneBy(['title' => $title]);
        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }

        $form = $this->createForm(PageUpdateType::class, $page);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = str_replace(' ', '_', $form->get('title')->getData());
            $page->setTitle($title);
            $em->flush();

            return $this->render('page/show.html.twig', [
                'page' => $page,
                'title' => str_replace('_', ' ', $page->getTitle()),
                'revision' => $em->getRepository(RevisionHistory::class)->findOneBy(['id' => $page->getCurrentRevision()])
            ]);
        }

        return $this->render("page/meta.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
