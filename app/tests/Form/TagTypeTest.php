<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Form;

use App\Entity\Tag;
use App\Form\TagType;
use App\Tests\AbstractBaseTestCase;

/**
 * Test class for TagType.
 */
class TagTypeTest extends AbstractBaseTestCase
{
    /**
     * Test form submission with valid data.
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => 'Test Tag',
        ];

        $tag = new Tag();
        $form = static::getContainer()->get('form.factory')->create(TagType::class, $tag);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData['name'], $tag->getName());
    }

    /**
     * Test form with empty data.
     */
    public function testSubmitEmptyData(): void
    {
        $tag = new Tag();
        $form = static::getContainer()->get('form.factory')->create(TagType::class, $tag);
        $form->submit([]);

        $this->assertTrue($form->isSynchronized());
        $this->assertNull($tag->getName());
    }
}
