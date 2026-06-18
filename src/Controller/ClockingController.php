<?php

namespace App\Controller;

use App\Entity\Clocking;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClockingController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EmployeeRepository $repo): Response
    {
        return $this->render('clocking/index.html.twig', [
            'employees' => $repo->findAll(),
        ]);
    }

    #[Route('/clock/{id}', name: 'app_pin', methods: ['GET'])]
    public function pin(int $id, EmployeeRepository $repo): Response
    {
        $employee = $repo->find($id);
        if (!$employee) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('clocking/pin.html.twig', [
            'employee' => $employee,
            'error'    => false,
        ]);
    }

    #[Route('/clock/{id}', name: 'app_pin_verify', methods: ['POST'])]
    public function verifyPin(int $id, Request $request, EmployeeRepository $repo): Response
    {
        $employee = $repo->find($id);
        if (!$employee) {
            return $this->redirectToRoute('app_index');
        }

        $pin = $request->request->get('pin', '');

        if (!password_verify($pin, $employee->getPinHash())) {
            return $this->render('clocking/pin.html.twig', [
                'employee' => $employee,
                'error'    => true,
            ]);
        }

        return $this->render('clocking/action.html.twig', [
            'employee' => $employee,
            'pin'      => $pin,
        ]);
    }

    #[Route('/clock/{id}/record', name: 'app_record', methods: ['POST'])]
    public function record(int $id, Request $request, EmployeeRepository $repo, EntityManagerInterface $em): Response
    {
        $employee = $repo->find($id);
        if (!$employee) {
            return $this->redirectToRoute('app_index');
        }

        $pin  = $request->request->get('pin', '');
        $type = $request->request->get('type', '');

        if (!password_verify($pin, $employee->getPinHash()) || !in_array($type, ['in', 'out'], true)) {
            return $this->redirectToRoute('app_pin', ['id' => $id]);
        }

        $clocking = new Clocking();
        $clocking->setEmployee($employee);
        $clocking->setType($type);
        $em->persist($clocking);
        $em->flush();

        $this->addFlash('success', sprintf(
            '%s %s — %s',
            $employee->getFirstName(),
            $employee->getLastName(),
            $type === 'in' ? 'Arrivée enregistrée' : 'Départ enregistré'
        ));

        return $this->redirectToRoute('app_index');
    }
}
