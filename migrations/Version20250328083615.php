<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328083615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, id_order INT NOT NULL, id_product INT NOT NULL, quantity INT NOT NULL, subtotal NUMERIC(10, 2) NOT NULL, INDEX IDX_ED896F461BACD2A8 (id_order), INDEX IDX_ED896F46DD7ADDD (id_product), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F461BACD2A8 FOREIGN KEY (id_order) REFERENCES orders (id_order)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46DD7ADDD FOREIGN KEY (id_product) REFERENCES product (id_product)');
        $this->addSql('ALTER TABLE orderdetail DROP FOREIGN KEY FK_27A0E7F21BACD2A8');
        $this->addSql('ALTER TABLE orderdetail DROP FOREIGN KEY FK_27A0E7F2DD7ADDD');
        $this->addSql('DROP TABLE orderdetail');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orderdetail (id_order_detail INT AUTO_INCREMENT NOT NULL, id_order INT NOT NULL, id_product INT NOT NULL, quantity INT NOT NULL, subtotal NUMERIC(10, 2) NOT NULL, INDEX IDX_27A0E7F21BACD2A8 (id_order), INDEX IDX_27A0E7F2DD7ADDD (id_product), PRIMARY KEY(id_order_detail)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE orderdetail ADD CONSTRAINT FK_27A0E7F21BACD2A8 FOREIGN KEY (id_order) REFERENCES orders (id_order) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE orderdetail ADD CONSTRAINT FK_27A0E7F2DD7ADDD FOREIGN KEY (id_product) REFERENCES product (id_product) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F461BACD2A8');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46DD7ADDD');
        $this->addSql('DROP TABLE order_detail');
        $this->addSql('DROP TABLE user');
    }
}
