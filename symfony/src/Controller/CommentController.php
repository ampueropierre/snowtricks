<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("comment/{id}/edit", name="edit_comment")
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    public function edit(Comment $comment, Request $request)
    {
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('show_trick', array('id' => $comment->getTrick()->getId()));
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("comment/{id}/delete", name="delete_comment", methods="DELETE")
     * @param Comment $comment
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Comment $comment, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->get('_token'))) {
            $this->em->remove($comment);
            $this->em->flush();
        }

        return $this->redirectToRoute('show_trick', array('id' => $comment->getTrick()->getId()));
    }
}
