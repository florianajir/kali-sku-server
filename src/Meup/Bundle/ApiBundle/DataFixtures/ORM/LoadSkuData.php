<?php
/**
 * This file is part of kali-server
 *
 * (c) Florian Ajir <https://github.com/florianajir/kali-server>
 */

namespace Meup\Bundle\ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Meup\Bundle\ApiBundle\Entity\Sku;

class LoadSkuData extends AbstractFixture implements OrderedFixtureInterface
{
    const CODE = '1234567';
    const PROJECT = 'project_test';
    const TYPE = 'type_test';
    const OBJECT_ID = 'id_test';

    public function load(ObjectManager $manager)
    {
        $sku = new Sku(self::CODE, self::PROJECT);
        $sku->setForeignType(self::TYPE);
        $sku->setForeignId(self::OBJECT_ID);

        $manager->persist($sku);
        $manager->flush();

        $this->addReference('sku1', $sku);
    }

    public function getOrder()
    {
        return 1;
    }
}
