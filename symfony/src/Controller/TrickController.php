<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Video;
use App\Form\CommentFormType;
use App\Form\TrickFormType;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Securite;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
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
     * @Route("tricks", name="index_trick")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $page = (int) $request->query->get ('page', 1);
        $allTricks = count($this->em->getRepository(Trick::class)->findAll());
        $pagination = ceil($allTricks/ $this->getParameter('app.limit_trick'));
        $pagination = ($pagination == 0) ? 1 : $pagination;
        $page = ($page > $pagination || $page == 0) ? 1 : $page;
        $tricks = $this->em->getRepository(Trick::class)->pagination($this->getParameter('app.limit_trick'),$page);

        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
            'pagination' => $pagination,
            'page' => $page,
        ]);
    }

    /**
     * @Route("trick/new", name="new_trick")
     * @param Request $request
     * @param Security $security
     * @param FileUploader $fileUploader
     * @return Response
     * @throws \Exception
     * @Securite("is_granted('ROLE_USER') and user.isActive() == 1")
     */
    public function new(Request $request, Security $security, FileUploader $fileUploader)
    {
        /** @var User $user */
        $user = $security->getUser();

        $trick = new Trick();

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('img')->getData()) {
                $fileImgMain = $form->get('img')->getData();
                $imgMain = $fileUploader->upload($fileImgMain);
                $trick->setImgFilename($imgMain);
            }

            $imageCollection = $trick->getImages();

            foreach ($imageCollection as $img) {
                $file = $img->getFile();
                if (!$file){
                    $imageCollection->removeElement($img);
                }
                $imgName = $fileUploader->upload($file);
                $img->setFileName($imgName);
            }

            $trick->setUser($user);
            $trick->setCreatedAt(new \DateTime());
            $trick->setModifiedAt(new \DateTime());
            $this->em->persist($trick);
            $this->em->flush();
            $this->addFlash('success','Votre figure a bien été enregistrée');
            return $this->redirectToRoute('index_trick');
        }

        return $this->render('trick/new.html.twig',[
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("trick/{slug}", name="show_trick")
     * @param Trick $trick
     * @param Request $request
     * @param Security $security
     * @return Response
     * @throws \Exception
     */
    public function show(Trick $trick, Request $request, Security $security)
    {
        $page = (int) $request->query->get ('page', 1);
        $allComment = count($this->em->getRepository(Comment::class)->findBy(['trick' => $trick]));
        $pagination = ceil($allComment/$this->getParameter('app.limit_comment'));
        $pagination = ($pagination == 0) ? 1 : $pagination;
        $page = ($page > $pagination || $page == 0) ? 1 : $page;
        $comments = $this->em->getRepository(Comment::class)->pagination($this->getParameter('app.limit_comment'),$page,$trick->getId());

        /** @var User $user */
        $user = $security->getUser();
        $comment = new Comment();
        $formComment = $this->createForm(CommentFormType::class, $comment);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setUser($user);
            $comment->setTrick($trick);
            $comment->setCreatedAt(new \DateTime);
            $this->em->persist($comment);
            $this->em->flush();
            $this->addFlash('success','Le commentaire a bien été ajouté');
            return $this->redirectToRoute('show_trick', ['slug' => $trick->getSlug()]);
        }

        $images = $this->em->getRepository(Image::class)->findBy(['trick' => $trick]);
        $videos = $this->em->getRepository(Video::class)->findBy(['trick' => $trick]);
        $gallery = array_merge($images,$videos);

        return $this->render('trick/show.html.twig',[
            'trick' => $trick,
            'formComment' => $formComment->createView(),
            'pagination' => $pagination,
            'page' => $page,
            'comments' => $comments,
            'gallery' => $gallery
        ]);
    }

    /**
     * @Route("trick/{id}/edit", name="edit_trick")
     * @param Trick $trick
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param Filesystem $filesystem
     * @return Response
     * @Securite("is_granted('ROLE_USER') and user == trick.getUser()")
     */
    public function edit(Trick $trick, Request $request,  FileUploader $fileUploader, Filesystem $filesystem)
    {
        $originalVideo = new ArrayCollection();
        foreach ($trick->getVideos() as $video) {
            $originalVideo->add($video);
        }

        $originalImage = new ArrayCollection();
        foreach ($trick->getImages() as $image) {
            $originalImage->add($image);
        }

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldImg = $trick->getImgFilename();
            if ($form->get('img')->getData()) {
                $fileImgMain = $form->get('img')->getData();
                $imgMain = $fileUploader->upload($fileImgMain);
                $trick->setImgFilename($imgMain);
                if ($oldImg) {
                    $filesystem->remove($this->getParameter('trick_img_directory').'/'.$oldImg);
                }
            }

            $images = $form->getData()->getImages()->toArray();
            foreach ($images as $image) {
                $file = $image->getFile();
                if ($file){
                    $imgName = $fileUploader->upload($file);
                    $image->setFileName($imgName);
                }
            }

            foreach ($originalImage as $image) {
                if ($trick->getImages()->contains($image) === false) {
                    $this->em->remove($image);
                    $filesystem->remove($this->getParameter('trick_img_directory').'/'.$image->getFilename());
                }
            }

            foreach ($originalVideo as $video) {
                if ($trick->getVideos()->contains($video) === false) {
                    $this->em->remove($video);
                }
            }
            $this->em->flush();
            $this->addFlash('success','La figure a bien été modifiée');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("trick/{id}/delete", name="delete_trick", methods="DELETE")
     * @param Trick $trick
     * @param Request $request
     * @param Filesystem $filesystem
     * @return RedirectResponse
     * @Securite("is_granted('ROLE_USER') and user == trick.getUser()")
     */
    public function delete(Trick $trick, Request $request, Filesystem $filesystem)
    {
        $images = $this->em->getRepository(Image::class)->findBy(['trick' => $trick]);

        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->get('_token'))) {
            $this->em->remove($trick);
            $this->em->flush();

            if ($trick->getImgFilename()) {
                $filesystem->remove($this->getParameter('trick_img_directory').'/'.$trick->getImgFilename());
            }

            foreach ($images as $image) {
                $filesystem->remove($this->getParameter('trick_img_directory').'/'.$image->getFileName());
            }
        }

        $this->addFlash('success','La figure a bien été supprimée');

        return $this->redirectToRoute('index_trick');
    }
}

