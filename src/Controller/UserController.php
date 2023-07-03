<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\User\FiltroType;
use App\Form\User\DatosPersonalesType;
use App\Form\User\PermisosType;
use App\Form\User\ClaveType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/user")
 * @IsGranted({"ROLE_SUPER_ADMIN"})
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $formFiltro = $this->createForm(FiltroType::class);
        if ($request->query->get($formFiltro->getName())) {
            $formFiltro->handleRequest($request);
        }

        $queryBuilder = $userRepository->findForActionIndex($formFiltro->getData());
        $pagination = $paginator->paginate(
          $queryBuilder,
          $request->query->get('page', 1),
          12
        );

        return $this->render('user/index.html.twig', [
          'pagination' => $pagination,
          'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setEnabled(true);
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', '¡Usuario agregado correctamente!');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(DatosPersonalesType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', '¡Registro actualizado correctamente!');

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', '¡Usuario eliminado correctamente!');
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/edit-clave", name="user_edit_clave", methods={"GET","POST"})
     */
    public function editClave(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
      $form = $this->createForm(ClaveType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

        $encoded = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash('success', '¡Clave actualizada correctamente!');

        return $this->redirectToRoute('user_index', [
            'id' => $user->getId(),
        ]);
      }

      return $this->render('user/edit_clave.html.twig', [
          'user' => $user,
          'form' => $form->createView(),
      ]);
    }

    /**
     * @Route("/{id}/update-estado", name="user_update_estado", methods={"GET"})
     */
    public function editUpdateEstado(Request $request, User $user)
    {

      $user->setEnabled(!$user->isEnabled());
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();

      $this->addFlash('success', '¡Estado actualizado correctamente!');

      return $this->redirectToRoute('user_index', [
          'id' => $user->getId(),
      ]);
    }

    /**
     * @Route("/{id}/edit-permisos", name="user_edit_permisos", methods={"GET","POST"})
     */
    public function editPermisos(Request $request, User $user): Response
    {
        $form = $this->createForm(PermisosType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', '¡Registro actualizado correctamente!');

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit_permisos.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
