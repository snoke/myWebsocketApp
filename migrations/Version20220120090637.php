<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120090637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_room_user DROP FOREIGN KEY FK_C87A2E561819BCFA');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE chat_room');
        $this->addSql('DROP TABLE chat_room_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_room (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE chat_room_user (chat_room_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C87A2E56A76ED395 (user_id), INDEX IDX_C87A2E561819BCFA (chat_room_id), PRIMARY KEY(chat_room_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE chat_room_user ADD CONSTRAINT FK_C87A2E561819BCFA FOREIGN KEY (chat_room_id) REFERENCES chat_room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_room_user ADD CONSTRAINT FK_C87A2E56A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_user');
    }
}
