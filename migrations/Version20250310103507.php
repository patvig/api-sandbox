<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310103507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_sale DROP FOREIGN KEY FK_68A3E2A47DC7170A');
        $this->addSql('DROP INDEX IDX_68A3E2A47DC7170A ON product_sale');
        $this->addSql('ALTER TABLE product_sale CHANGE vente_id sale_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_sale ADD CONSTRAINT FK_68A3E2A44A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id)');
        $this->addSql('CREATE INDEX IDX_68A3E2A44A7E4868 ON product_sale (sale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_sale DROP FOREIGN KEY FK_68A3E2A44A7E4868');
        $this->addSql('DROP INDEX IDX_68A3E2A44A7E4868 ON product_sale');
        $this->addSql('ALTER TABLE product_sale CHANGE sale_id vente_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_sale ADD CONSTRAINT FK_68A3E2A47DC7170A FOREIGN KEY (vente_id) REFERENCES sale (id)');
        $this->addSql('CREATE INDEX IDX_68A3E2A47DC7170A ON product_sale (vente_id)');
    }
}
