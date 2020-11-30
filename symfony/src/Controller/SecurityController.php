<?php

namespace App\Controller;

use App\Form\EditProfilFormType;
use App\Form\ForgetPasswordType;
use App\Form\ResetPasswordType;
use App\Manager\MailerManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\ValidationData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Securite;
use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\DateTime;

class SecurityController extends AbstractController
{
    private $mailer;

    /**
     * SecurityController constructor.
     * @param MailerManager $mailer
     */
    public function __construct(MailerManager $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/login", name="security_login")
     *
     * @param $authenticationUtils AuthenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("security/login.html.twig", [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * @Route("/register", name="app_register")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $time = time();
            dd($time,new DateTime());
            $signer = new Sha256();
            $token = (new Builder())
                ->withClaim('mail', $user->mail)
                ->expiresAt($time + 600)
                ->getToken($signer, new Key($this->getParameter('key_token')));

            $this->mailer->registrationMessage($user, $token);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Votre demande de création de compte a bien été prise en compte, veuillez vérifier dans votre boite mail afin de valider votre compte');
            return $this->redirectToRoute('security_login');
        }

        return $this->render("security/register.html.twig", [
            'formRegister' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-profil", name="app_edit")
     *
     * @param Request $request
     * @param Security $security
     * @param EntityManagerInterface $em
     * @param Filesystem $filesystem
     *
     * @return Response
     *
     * @throws \Exception
     * @Securite("is_granted('ROLE_USER') and user.isActive() == 1")
     */
    function edit(Request $request, Security $security, EntityManagerInterface $em, Filesystem $filesystem): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        if (!$user) {
            $this->addFlash('danger','Vous n\'etes pas connecté');
            return $this->redirectToRoute('security_login');
        }

        $form = $this->createForm(EditProfilFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldImg = $user->getImg();
            $imgFile = $form['img']->getData();
            if ($imgFile) {
                $filename = md5(uniqid()).'.'.$imgFile->guessExtension();
                $imgFile->move(
                    $this->getParameter('profil_img_directory'),
                    $filename
                );
                if ($oldImg) {
                    $filesystem->remove($this->getParameter('profil_img_directory').'/'.$oldImg);
                }
                $user->setImg($filename);
            }

            $user->setModifiedAt(new \DateTime());
            $em->flush();
            $this->addFlash('success','Votre profil a bien été modifié');
        }

        return $this->render('security/edit.html.twig', [
            'formEdit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     * @throws \Exception
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached');
    }

    /**
     * @Route("/confirmation", name="security_confirmation")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function confirmationToken(Request $request,EntityManagerInterface $em)
    {
        $signer = new Sha256();
        $token = $request->query->get('token');
        $token = (new Parser())->parse((string) $token);
        $data = new ValidationData();

        if ($token->validate($data) && $token->verify($signer, $this->getParameter('key_token'))) {
            $mail = $token->getClaim('mail');
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['mail' => $mail]);

            if ($user && !$user->isActive()) {
                $user->setActive(true);
                $em->flush();
                $this->addFlash('success','Votre compte est valide');
                return $this->redirectToRoute('security_login');
            }
        }
        $this->addFlash('danger','Un problème est survenue');
        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/forget_password", name="securite_forget_password")
     *
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function forgetPassword(Request $request)
    {
        $user = new User();
        $form = $this->createForm(ForgetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['mail' => $user->mail]);
            if (!$user) {
                $this->addFlash('danger', 'ce mail n\'est pas enregistré dans notre base');
            } else {
                $time = time();
                $date = new \DateTime();
                $signer = new Sha256();
                $token = (new Builder())
                    ->withClaim('mail', $user->mail)
                    ->expiresAt($time + 600)
                    ->getToken($signer,new Key($this->getParameter('key_token')));
                $this->mailer->forgetPasswordMessage($user,$token);
                $this->addFlash('success','le mail a bien été envoyé');
            }
        }

        return $this->render('security/forget_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset_password", name="securite_reset_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $em)
    {
        $token = $request->query->get('token');
        $token = (new Parser())->parse((string) $token);
        $signer = new Sha256();
        $data = new ValidationData();
        if ($token->validate($data) && $token->verify($signer, $this->getParameter('key_token'))) {
            $mail = $token->getClaim('mail');
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['mail' => $mail]);
            if ($user) {
                $form = $this->createForm(ResetPasswordType::class, $user);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    );
                    $em->flush();
                    $this->addFlash('success', 'Le mot de passe a bien ete modifie');
                    return $this->redirectToRoute('security_login');
                }
                return $this->render('security/reset_password.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        $this->addFlash('danger', 'Le token est expiré ou n\'est pas valide');
        return $this->redirectToRoute('security_login');
    }
}

