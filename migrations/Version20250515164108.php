<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515164108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, dossierpatient_id INT DEFAULT NULL, datefacture DATETIME NOT NULL, estpaye TINYINT(1) NOT NULL, INDEX IDX_FE866410E85C9719 (dossierpatient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lignefacture (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, description VARCHAR(150) NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_216F1FD57F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE866410E85C9719 FOREIGN KEY (dossierpatient_id) REFERENCES dossierpatient (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lignefacture ADD CONSTRAINT FK_216F1FD57F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE866410E85C9719
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lignefacture DROP FOREIGN KEY FK_216F1FD57F2DEE08
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE facture
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lignefacture
        SQL);
    }
}
