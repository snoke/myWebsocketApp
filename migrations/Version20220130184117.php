<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220130184117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat ADD blocked_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA1DA661F8 FOREIGN KEY (blocked_by_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA1DA661F8 ON chat (blocked_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA1DA661F8');
        $this->addSql('DROP INDEX IDX_659DF2AA1DA661F8 ON chat');
        $this->addSql('ALTER TABLE chat DROP blocked_by_id');
    }
}
