<?php

namespace App\Controller\Back;

use App\Entity\Command;
use App\Form\CommandType;
use App\Entity\ProductCommand;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/command")
 */
class CommandController extends AbstractController
{
    /**
     * @Route("/", name="back_command_index", methods={"GET"})
     */
    public function index(CommandRepository $commandRepository): Response
    {
        return $this->render('back/command/index.html.twig', [
            'commands' => $commandRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_command_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $command = new Command();
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($command);
            $entityManager->flush();

            return $this->redirectToRoute('back_command_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/command/new.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_command_show", methods={"GET"})
     */
    public function show(Command $command): Response
    {
        return $this->render('back/command/show.html.twig', [
            'command' => $command,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_command_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Command $command, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('back_command_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/command/edit.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
        
    }

    /**
     * @Route("/{id}", name="back_command_delete", methods={"POST"})
     */
    public function delete(Request $request, Command $command, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$command->getId(), $request->request->get('_token'))) {
            $entityManager->remove($command);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_command_index', [], Response::HTTP_SEE_OTHER);
    }
}
