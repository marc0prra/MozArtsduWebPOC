<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EmployeeFixtures extends Fixture
{
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
            $employee->setPinHash(password_hash($data['pin'], PASSWORD_BCRYPT));

            $manager->persist($employee);
            $this->addReference($reference, $employee);
        }

        $manager->flush();
    }
}
