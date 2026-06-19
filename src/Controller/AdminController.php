<?php

namespace App\Controller;

use App\Repository\ClockingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Vue réservée au responsable hiérarchique.
 * Accessible uniquement via une URL confidentielle (non listée dans l'interface tablette).
 * Le responsable bookmarke cette URL sur son propre appareil.
 */
class AdminController extends AbstractController
{
    /** Affiche tous les pointages enregistrés aujourd'hui, du plus récent au plus ancien. */
    #[Route('/admin', name: 'app_admin')]
    public function index(ClockingRepository $repo): Response
    {
        return $this->render('admin/index.html.twig', [
            'clockings' => $repo->findForToday(),
        ]);
    }
}
