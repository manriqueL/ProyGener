<?php

namespace App\Controller;

use Gedmo\Loggable\Entity\LogEntry ;
use App\Form\LogType;
use App\Form\Log\FiltroType;
use App\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/log")
 * @IsGranted({"ROLE_SUPER_ADMIN"})
 */
class LogController extends AbstractController
{
    /**
     * @Route("/", name="log_index", methods={"GET"})
     */
    public function index(Request $request, LogRepository $logRepository, PaginatorInterface $paginator): Response
    {
        // Creo el formulario del filtro.
        // Luego consulto si viene el formulario en el $request para
        // asociar esos valores en el formulario
        $formFiltro = $this->createForm(FiltroType::class);
        if ($request->query->get($formFiltro->getName())) {
            $formFiltro->handleRequest($request);
        }

        // Creo el $queryBuilder a trevés del cual se proporcionaran las entidades para
        // ser listadas. Como parametro paso el formulario con los filtros seleccionados
        $queryBuilder = $logRepository->findForActionIndex($formFiltro->getData());
        $pagination = $paginator->paginate(
          $queryBuilder,
          $request->query->get('page', 1),
          12
        );

        return $this->render('log/index.html.twig', [
          'pagination' => $pagination,
          'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="log_show", methods={"GET"})
     */
    public function show(LogEntry $log): Response
    {
        return $this->render('log/show.html.twig', [
            'log' => $log,
        ]);
    }
}
