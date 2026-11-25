<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261113125359 extends AbstractMigration
{
     public function getDescription(): string
    {
        return 'Contraintes CHECK du modele (types, actions, statuts)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE sensors ADD CONSTRAINT sensors_type_check
            CHECK (type IN ('temperature','air_humidity','light','soil_moisture'))");
        $this->addSql("ALTER TABLE commands ADD CONSTRAINT commands_action_check
            CHECK (action IN ('on','off'))");
        $this->addSql("ALTER TABLE commands ADD CONSTRAINT commands_status_check
            CHECK (status IN ('pending','executed','failed','expired'))");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sensors DROP CONSTRAINT sensors_type_check');
        $this->addSql('ALTER TABLE commands DROP CONSTRAINT commands_action_check');
        $this->addSql('ALTER TABLE commands DROP CONSTRAINT commands_status_check');
    }
}
