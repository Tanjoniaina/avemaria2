<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514175951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE parametreentre (id INT AUTO_INCREMENT NOT NULL, tensionarteriel VARCHAR(10) NOT NULL, temperature VARCHAR(5) NOT NULL, fc VARCHAR(10) NOT NULL, fr VARCHAR(10) NOT NULL, glycemie NUMERIC(5, 2) NOT NULL, poids VARCHAR(5) NOT NULL, spo2 VARCHAR(5) NOT NULL, remarque LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE parametreentre
        SQL);
    }
}
