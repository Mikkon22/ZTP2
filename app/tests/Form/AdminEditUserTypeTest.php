<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

declare(strict_types=1);

namespace App\Tests\Form;

use App\Form\AdminEditUserType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

/**
 * Test class for AdminEditUserType.
 */
class AdminEditUserTypeTest extends TypeTestCase
{
    /**
     * Test form submission with valid data.
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'email' => 'test@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];

        $form = $this->factory->create(AdminEditUserType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    /**
     * Test form submission with empty data.
     */
    public function testSubmitEmptyData(): void
    {
        $form = $this->factory->create(AdminEditUserType::class);
        $form->submit([]);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    /**
     * Get form extensions for testing.
     *
     * @return array
     */
    protected function getExtensions(): array
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
        ];
    }
}
