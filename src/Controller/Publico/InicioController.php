<?php

namespace App\Controller\Publico;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    /**
     * @Route("/", name="publico_inicio")
     */
    public function index()
    {
        return $this->render('publico/inicio/index.html.twig', []);
    }

    /**
     * Sirve para redireccionar al usuario a la dirección más adecuada 
     * cuando se desconoce su destino principal 
     * 
     * @Route("/redireccionar/", name="publico_redireccionar")
     */
    public function redireccionar()
    {
        if ($this->getUser() && $this->getUser()->getId()) return $this->redirectToRoute('inicio');

        return $this->redirectToRoute('publico_inicio');
    }    
}
