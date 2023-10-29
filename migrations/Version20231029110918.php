<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029110918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversations (id CHAR(36) NOT NULL COMMENT \'(DC2Type:conversation_id)\', created_at DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime)\', INDEX conversations_created_at_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id CHAR(36) NOT NULL COMMENT \'(DC2Type:message_id)\', conversation_id CHAR(36) NOT NULL COMMENT \'(DC2Type:conversation_id)\', sent_by CHAR(36) NOT NULL COMMENT \'(DC2Type:participant_id)\', content LONGTEXT NOT NULL COMMENT \'(DC2Type:message_content)\', sent_at DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime)\', INDEX IDX_DB021E969AC0396 (conversation_id), INDEX IDX_DB021E96C378DCF6 (sent_by), INDEX messages_sent_at_idx (sent_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participants (id CHAR(36) NOT NULL COMMENT \'(DC2Type:participant_id)\', conversation_id CHAR(36) NOT NULL COMMENT \'(DC2Type:conversation_id)\', name VARCHAR(255) NOT NULL COMMENT \'(DC2Type:participant_name)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:user_id)\', INDEX IDX_716970929AC0396 (conversation_id), INDEX participants_user_id_idx (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:user_id)\', first_name VARCHAR(255) NOT NULL COMMENT \'(DC2Type:firstname)\', last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL COMMENT \'(DC2Type:email)\', created_at DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime)\', removed_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime)\', UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX users_email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_credentials (id CHAR(36) NOT NULL COMMENT \'(DC2Type:user_id)\', username VARCHAR(255) NOT NULL COMMENT \'(DC2Type:username)\', hashed_password VARCHAR(255) NOT NULL COMMENT \'(DC2Type:hashed_password)\', UNIQUE INDEX UNIQ_DB014FCF85E0677 (username), INDEX users_credentials_username_idx (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969AC0396 FOREIGN KEY (conversation_id) REFERENCES conversations (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96C378DCF6 FOREIGN KEY (sent_by) REFERENCES participants (id)');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_716970929AC0396 FOREIGN KEY (conversation_id) REFERENCES conversations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969AC0396');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96C378DCF6');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_716970929AC0396');
        $this->addSql('DROP TABLE conversations');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE participants');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_credentials');
    }
}
