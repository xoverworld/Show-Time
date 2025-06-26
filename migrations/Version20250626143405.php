<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626143405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE purchased (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, festival_id INT NOT NULL, INDEX IDX_29B7B439A76ED395 (user_id), INDEX IDX_29B7B4398AEBAF57 (festival_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased ADD CONSTRAINT FK_29B7B439A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased ADD CONSTRAINT FK_29B7B4398AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD CONSTRAINT FK_E68F0A78B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist ADD CONSTRAINT FK_E68F0A788AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E68F0A78B7970CF8 ON festival_artist (artist_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E68F0A788AEBAF57 ON festival_artist (festival_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD user_details_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D6491C7DC1CE FOREIGN KEY (user_details_id) REFERENCES user_details (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D6491C7DC1CE ON user (user_details_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased DROP FOREIGN KEY FK_29B7B439A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purchased DROP FOREIGN KEY FK_29B7B4398AEBAF57
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE purchased
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP FOREIGN KEY FK_E68F0A78B7970CF8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE festival_artist DROP FOREIGN KEY FK_E68F0A788AEBAF57
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E68F0A78B7970CF8 ON festival_artist
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E68F0A788AEBAF57 ON festival_artist
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491C7DC1CE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D6491C7DC1CE ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP user_details_id
        SQL);
    }
}
