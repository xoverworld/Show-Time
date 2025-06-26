<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626144437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased DROP FOREIGN KEY FK_29B7B439A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_29B7B439A76ED395 ON purchased
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased CHANGE user_id user_details_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased ADD CONSTRAINT FK_29B7B4391C7DC1CE FOREIGN KEY (user_details_id) REFERENCES user_details (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_29B7B4391C7DC1CE ON purchased (user_details_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased DROP FOREIGN KEY FK_29B7B4391C7DC1CE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_29B7B4391C7DC1CE ON purchased
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased CHANGE user_details_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased ADD CONSTRAINT FK_29B7B439A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_29B7B439A76ED395 ON purchased (user_id)
        SQL);
    }
}
