<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200421141801 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, zipcode INT NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, INDEX IDX_D4E6F81A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, shipping_address_id INT NOT NULL, billing_address_id INT NOT NULL, number INT NOT NULL, status INT DEFAULT NULL, payment_token VARCHAR(255) DEFAULT NULL, tracking_number VARCHAR(255) DEFAULT NULL, INDEX IDX_F5299398A76ED395 (user_id), INDEX IDX_F52993984D4CFF2B (shipping_address_id), INDEX IDX_F529939879D0C0E4 (billing_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_has_product (id INT AUTO_INCREMENT NOT NULL, order_id_id INT NOT NULL, state_product_id_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_AF0913F0FCDAEAAA (order_id_id), INDEX IDX_AF0913F0ED0EE9E7 (state_product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, picture VARCHAR(255) NOT NULL, INDEX IDX_14B784184584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT DEFAULT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_A393D2FB4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, ent_at DATETIME NOT NULL, INDEX IDX_5F37A13BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(50) DEFAULT NULL, lastname VARCHAR(50) DEFAULT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, role LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', loyalty INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939879D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE order_has_product ADD CONSTRAINT FK_AF0913F0FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_has_product ADD CONSTRAINT FK_AF0913F0ED0EE9E7 FOREIGN KEY (state_product_id_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784184584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE state ADD CONSTRAINT FK_A393D2FB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984D4CFF2B');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939879D0C0E4');
        $this->addSql('ALTER TABLE order_has_product DROP FOREIGN KEY FK_AF0913F0FCDAEAAA');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784184584665A');
        $this->addSql('ALTER TABLE state DROP FOREIGN KEY FK_A393D2FB4584665A');
        $this->addSql('ALTER TABLE order_has_product DROP FOREIGN KEY FK_AF0913F0ED0EE9E7');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_has_product');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE user');
    }
}
