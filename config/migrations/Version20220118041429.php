<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118041429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_room ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_room ADD CONSTRAINT FK_D403CCDAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_D403CCDAA76ED395 ON chat_room (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_room DROP FOREIGN KEY FK_D403CCDAA76ED395');
        $this->addSql('DROP INDEX IDX_D403CCDAA76ED395 ON chat_room');
        $this->addSql('ALTER TABLE chat_room DROP user_id');
    }
}
