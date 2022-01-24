<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124105056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_message ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC1693CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_FAB3FC1693CB796C ON chat_message (file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC1693CB796C');
        $this->addSql('DROP INDEX IDX_FAB3FC1693CB796C ON chat_message');
        $this->addSql('ALTER TABLE chat_message DROP file_id');
    }
}
