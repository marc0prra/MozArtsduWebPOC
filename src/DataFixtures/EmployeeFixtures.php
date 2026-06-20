<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/** Génère des salariés de test avec leur code PIN haché.*/
class EmployeeFixtures extends Fixture
{
    // Constantes utilisées pour référencer chaque salarié dans les autres fixtures
    public const EMPLOYEE_ALICE   = 'employee-alice';
    public const EMPLOYEE_BOB     = 'employee-bob';
    public const EMPLOYEE_CAMILLE = 'employee-camille';

    public function load(ObjectManager $manager): void
    {
        $employees = [
            self::EMPLOYEE_ALICE   => ['firstName' => 'Alice',   'lastName' => 'Martin',  'pin' => '123456'],
            self::EMPLOYEE_BOB     => ['firstName' => 'Bob',     'lastName' => 'Dupont',  'pin' => '234567'],
            self::EMPLOYEE_CAMILLE => ['firstName' => 'Camille', 'lastName' => 'Bernard', 'pin' => '345678'],
        ];

        foreach ($employees as $reference => $data) {
            $employee = new Employee();
            $employee->setFirstName($data['firstName']);
            $employee->setLastName($data['lastName']);
            // Le PIN est haché avec bcrypt — jamais stocké en clair
            $employee->setPinHash(password_hash($data['pin'], PASSWORD_BCRYPT));

            $manager->persist($employee);
            // addReference() permet à ClockingFixtures de récupérer cet objet
            $this->addReference($reference, $employee);
        }

        $manager->flush();
    }
}
