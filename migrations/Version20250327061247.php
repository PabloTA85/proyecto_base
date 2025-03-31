<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327061247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orderdetail (id_order_detail INT AUTO_INCREMENT NOT NULL, id_order INT NOT NULL, id_product INT NOT NULL, quantity INT NOT NULL, subtotal NUMERIC(10, 2) NOT NULL, INDEX IDX_27A0E7F21BACD2A8 (id_order), INDEX IDX_27A0E7F2DD7ADDD (id_product), PRIMARY KEY(id_order_detail)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productcategorie (id_product INT NOT NULL, id_category INT NOT NULL, INDEX IDX_AC0DF9C3DD7ADDD (id_product), INDEX IDX_AC0DF9C35697F554 (id_category), PRIMARY KEY(id_product, id_category)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orderdetail ADD CONSTRAINT FK_27A0E7F21BACD2A8 FOREIGN KEY (id_order) REFERENCES orders (id_order)');
        $this->addSql('ALTER TABLE orderdetail ADD CONSTRAINT FK_27A0E7F2DD7ADDD FOREIGN KEY (id_product) REFERENCES product (id_product)');
        $this->addSql('ALTER TABLE productcategorie ADD CONSTRAINT FK_AC0DF9C3DD7ADDD FOREIGN KEY (id_product) REFERENCES product (id_product)');
        $this->addSql('ALTER TABLE productcategorie ADD CONSTRAINT FK_AC0DF9C35697F554 FOREIGN KEY (id_category) REFERENCES categorie (id_category)');
        $this->addSql('ALTER TABLE product_categorie_product DROP FOREIGN KEY FK_384C985C3151C546');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F461BACD2A8');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46DD7ADDD');
        $this->addSql('DROP TABLE product_categorie_product');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('DROP TABLE product_categorie');
        $this->addSql('DROP TABLE product_categorie_categorie');
        $this->addSql('ALTER TABLE orders CHANGE id_client id_client INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_categorie_product (product_categorie_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_384C985C3151C546 (product_categorie_id), INDEX IDX_384C985C4584665A (product_id), PRIMARY KEY(product_categorie_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, id_order INT NOT NULL, id_product INT NOT NULL, quantity INT NOT NULL, subtotal NUMERIC(10, 2) NOT NULL, INDEX IDX_ED896F46DD7ADDD (id_product), INDEX IDX_ED896F461BACD2A8 (id_order), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_categorie (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_categorie_categorie (product_categorie_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_BD6332A13151C546 (product_categorie_id), INDEX IDX_BD6332A1BCF5E72D (categorie_id), PRIMARY KEY(product_categorie_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_categorie_product ADD CONSTRAINT FK_384C985C3151C546 FOREIGN KEY (product_categorie_id) REFERENCES product_categorie (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F461BACD2A8 FOREIGN KEY (id_order) REFERENCES orders (id_order) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46DD7ADDD FOREIGN KEY (id_product) REFERENCES product (id_product) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE orderdetail DROP FOREIGN KEY FK_27A0E7F21BACD2A8');
        $this->addSql('ALTER TABLE orderdetail DROP FOREIGN KEY FK_27A0E7F2DD7ADDD');
        $this->addSql('ALTER TABLE productcategorie DROP FOREIGN KEY FK_AC0DF9C3DD7ADDD');
        $this->addSql('ALTER TABLE productcategorie DROP FOREIGN KEY FK_AC0DF9C35697F554');
        $this->addSql('DROP TABLE orderdetail');
        $this->addSql('DROP TABLE productcategorie');
        $this->addSql('ALTER TABLE orders CHANGE id_client id_client INT DEFAULT NULL');
    }
}
