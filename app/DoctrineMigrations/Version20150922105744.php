<?php
/**
 * This file is part of the 1001 Pharmacies Kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20150922105744
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class Version20150922105744 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE `sku_registry`
            ADD UNIQUE INDEX `UNIQ_SKU` (`project` ASC, `foreign_type` ASC, `foreign_id` ASC);
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE `sku_registry` DROP INDEX `UNIQ_SKU`;");
    }
}
