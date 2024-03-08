<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306085513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD consultant_id INT DEFAULT NULL, ADD candidat_id INT DEFAULT NULL, ADD recruteur_id INT DEFAULT NULL, ADD administrateur_id INT DEFAULT NULL, ADD en_service TINYINT(1) DEFAULT NULL, ADD connec_recruteur TINYINT(1) DEFAULT NULL, ADD connec_candidat TINYINT(1) DEFAULT NULL, ADD connec_consultant TINYINT(1) DEFAULT NULL, ADD connec_administrateur TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64944F779A2 FOREIGN KEY (consultant_id) REFERENCES consultant (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BB0859F1 FOREIGN KEY (recruteur_id) REFERENCES recruteur (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64944F779A2 ON user (consultant_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498D0EB82 ON user (candidat_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649BB0859F1 ON user (recruteur_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497EE5403C ON user (administrateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64944F779A2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498D0EB82');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BB0859F1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497EE5403C');
        $this->addSql('DROP INDEX UNIQ_8D93D64944F779A2 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6498D0EB82 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649BB0859F1 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6497EE5403C ON user');
        $this->addSql('ALTER TABLE user DROP consultant_id, DROP candidat_id, DROP recruteur_id, DROP administrateur_id, DROP en_service, DROP connec_recruteur, DROP connec_candidat, DROP connec_consultant, DROP connec_administrateur');
    }
}
