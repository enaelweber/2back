<?php
namespace App\Controller;

use App\Entity\File;
use App\Form\FileType;
use App\Repository\FileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/file')]
class FileController extends AbstractController
{
    #[Route('/', name: 'file_index')]
    public function index(FileRepository $fileRepository): Response
    {
        return $this->render('file/index.html.twig', [
            'files' => $fileRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'file_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        $file = new File();
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);
		$customName = $form->get('customName')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('file')->getData();

            if ($uploadedFile) {
                $newFilename = $customName.'.'.$uploadedFile->getClientOriginalExtension();

                $uploadedFile->move(
                    $this->getParameter('files_directory'),
                    $newFilename
                );

                $file->setFileName($newFilename);
            }

            $file->setDate(new \DateTime());
            $file->setAuthor($security->getUser());

            $em->persist($file);
            $em->flush();

            return $this->redirectToRoute('file_index');
        }

        return $this->render('file/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'file_show')]
    public function show(File $file): Response
    {
        return $this->render('file/show.html.twig', [
            'file' => $file,
        ]);
    }

    #[Route('/{id}/delete', name: 'file_delete')]
    public function delete(
        Request $request,
        File $file,
        EntityManagerInterface $em
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $em->remove($file);
            $em->flush();
        }

        return $this->redirectToRoute('file_index');
    }
}