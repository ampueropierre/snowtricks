<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $repository = $this->getDoctrine()->getRepository(Trick::class);
        $tricks = $repository->threeLastTrick();

        return $this->render('home.html.twig',[
            'tricks' => $tricks
        ]);
    }
}
