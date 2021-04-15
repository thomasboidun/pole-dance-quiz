<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412133004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE difficulty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE move (id INT AUTO_INCREMENT NOT NULL, difficulty_id INT NOT NULL, name VARCHAR(255) NOT NULL, video_uri VARCHAR(255) NOT NULL, INDEX IDX_EF3E3778FCFA9DAE (difficulty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE move_category (move_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_F3B110506DC541A8 (move_id), INDEX IDX_F3B1105012469DE2 (category_id), PRIMARY KEY(move_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_EF3E3778FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulty (id)');
        $this->addSql('ALTER TABLE move_category ADD CONSTRAINT FK_F3B110506DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE move_category ADD CONSTRAINT FK_F3B1105012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE move_category DROP FOREIGN KEY FK_F3B1105012469DE2');
        $this->addSql('ALTER TABLE move DROP FOREIGN KEY FK_EF3E3778FCFA9DAE');
        $this->addSql('ALTER TABLE move_category DROP FOREIGN KEY FK_F3B110506DC541A8');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE difficulty');
        $this->addSql('DROP TABLE move');
        $this->addSql('DROP TABLE move_category');
    }
}
