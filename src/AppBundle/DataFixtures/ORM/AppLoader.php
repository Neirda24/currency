<?php

namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Nelmio\Alice\Fixtures;

/**
 * App Fixture Loader.
 */
class AppLoader extends DataFixtureLoader
{
    /**
     * @const FIXTURES_FOLDER
     */
    const FIXTURES_FOLDER = 'fixtures';

    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return [
            __DIR__ . '/' . self::FIXTURES_FOLDER . '/currency.yml',
            __DIR__ . '/' . self::FIXTURES_FOLDER . '/product.yml',
        ];
    }
}
