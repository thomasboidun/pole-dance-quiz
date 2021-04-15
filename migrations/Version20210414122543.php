<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210414122543 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, choices LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', replies LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', player VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_move (quiz_id INT NOT NULL, move_id INT NOT NULL, INDEX IDX_25CFCB23853CD175 (quiz_id), INDEX IDX_25CFCB236DC541A8 (move_id), PRIMARY KEY(quiz_id, move_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_move ADD CONSTRAINT FK_25CFCB23853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_move ADD CONSTRAINT FK_25CFCB236DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_move DROP FOREIGN KEY FK_25CFCB23853CD175');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_move');
    }
}
