<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415133844 extends AbstractMigration
{
    public function up(Schema $schema) : void
{
    $table = $schema->getTable('annonce');

    // Vérifier si la colonne created_at existe avant de la supprimer
    if ($table->hasColumn('created_at')) {
        // Supprimer la colonne created_at de la table annonce
        $this->addSql('ALTER TABLE annonce DROP COLUMN created_at');
    }

    // Vérifier si la colonne updated_at existe avant de la supprimer
    if ($table->hasColumn('updated_at')) {
        // Supprimer la colonne updated_at de la table annonce
        $this->addSql('ALTER TABLE annonce DROP COLUMN updated_at');
    }

    // Ajouter la colonne email à la table recruteur
    $this->addSql('ALTER TABLE recruteur ADD COLUMN email VARCHAR(255) DEFAULT NULL');
}


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recruteur DROP email');
    }
}
