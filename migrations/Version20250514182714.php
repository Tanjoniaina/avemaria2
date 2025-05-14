<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514182714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE parametreentre ADD dossierpatient_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametreentre ADD CONSTRAINT FK_71A12242E85C9719 FOREIGN KEY (dossierpatient_id) REFERENCES dossierpatient (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_71A12242E85C9719 ON parametreentre (dossierpatient_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE parametreentre DROP FOREIGN KEY FK_71A12242E85C9719
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_71A12242E85C9719 ON parametreentre
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametreentre DROP dossierpatient_id
        SQL);
    }
}
