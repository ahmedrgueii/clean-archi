<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251019204124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_items (id CHAR(36) NOT NULL COMMENT \'(DC2Type:order_item_id)\', order_id CHAR(36) NOT NULL COMMENT \'(DC2Type:order_id)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:product_id)\', product_name VARCHAR(255) NOT NULL COMMENT \'(DC2Type:product_name)\', unit_price JSON NOT NULL COMMENT \'(DC2Type:money)\', quantity INT NOT NULL COMMENT \'(DC2Type:quantity)\', subtotal JSON NOT NULL COMMENT \'(DC2Type:money)\', INDEX order_items_order_idx (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id CHAR(36) NOT NULL COMMENT \'(DC2Type:order_id)\', status VARCHAR(255) NOT NULL COMMENT \'(DC2Type:order_status)\', created_at DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime)\', total JSON NOT NULL COMMENT \'(DC2Type:money)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id CHAR(36) NOT NULL COMMENT \'(DC2Type:product_id)\', name VARCHAR(255) NOT NULL COMMENT \'(DC2Type:product_name)\', description LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:product_description)\', price JSON NOT NULL COMMENT \'(DC2Type:money)\', stock INT NOT NULL COMMENT \'(DC2Type:stock)\', created_at DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime)\', updated_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime)\', INDEX products_created_at_idx (created_at), INDEX products_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB08D9F6D38');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE products');
    }
}
