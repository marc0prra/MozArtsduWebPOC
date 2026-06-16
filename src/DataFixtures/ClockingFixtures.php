<?php

namespace App\DataFixtures;

use App\Entity\Clocking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ClockingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $employeeRefs = [
            EmployeeFixtures::EMPLOYEE_ALICE,
            EmployeeFixtures::EMPLOYEE_BOB,
            EmployeeFixtures::EMPLOYEE_CAMILLE,
        ];

        foreach ($employeeRefs as $ref) {
            $employee = $this->getReference($ref, \App\Entity\Employee::class);

            foreach (['in', 'out', 'in', 'out'] as $type) {
                $clocking = new Clocking();
                $clocking->setEmployee($employee);
                $clocking->setType($type);
                $manager->persist($clocking);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [EmployeeFixtures::class];
    }
}
