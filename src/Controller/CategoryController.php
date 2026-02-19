<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

final class CategoryController extends AbstractController
{
    #[Route('/Categories', name: 'categories')]
    public function category(Request $request, ManagerRegistry $doctrine, CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $categories = $categoryRepository->findAll();

        $category = new Category();

		$form = $this->createForm(CategoryType::class, $category);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $name = str_replace(' ', '_', $form->get('name')->getData());
            $category->setName($name);
            $em->persist($category);
            $em->flush();
            
            return $this->redirectToRoute('show_category',[
                'name' => $name
            ]);
		}

		return $this->render("category/index.html.twig", [
			"form" => $form->createView(),
            "categories" => $categories
		]);
    }

    #[Route("/Categories/{name}", name: "show_category")]
    public function show(string $name, CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
        
        $category = $categoryRepository->findOneBy(['name' => $name]);

        if (!$category) {
            throw $this->createNotFoundException();
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'name' => str_replace('_', ' ', $category->getName()),
            'pages' => $category->getPages()
        ]);
    }

    #[Route("/Categories/{name}/delete", name: "delete_category")]
    public function delete(ManagerRegistry $doctrine, CategoryRepository $categoryRepository, string $name): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); 
        
        $category = $categoryRepository->findOneBy(['name' => $name]);

        if (!$category) {
            throw $this->createNotFoundException();
        }
        $em = $doctrine->getManager();

        foreach ($category->getPages() as $page) {
            $page->removeCategory($category);
        }

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute("categories");
    }

    #[Route("/Categories/{name}/edit", name: "edit_category")]
    public function edit(Request $request, ManagerRegistry $doctrine, string $name): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');        
        $em = $doctrine->getManager();
        
        $category = $em->getRepository(Category::class)->findOneBy(['name' => $name]);
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

		$form = $this->createForm(CategoryType::class, $category);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
            $name = str_replace(' ', '_', $form->get('name')->getData());
            $category->setName($name);
            $em->flush();
            
            return $this->redirectToRoute('show_category',[
                'name' => $name
            ]);
		}

		return $this->render("category/edit.html.twig", [
            "form" => $form->createView(),
            "category" => $category
        ]);
    }
}
