<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241225130356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, birth_date DATE DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_genres (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_genres_books (book_genres_id INT NOT NULL, books_id INT NOT NULL, INDEX IDX_7A6C6F9E78953045 (book_genres_id), INDEX IDX_7A6C6F9E7DD8AC20 (books_id), PRIMARY KEY(book_genres_id, books_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_publishers (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_publishers_publishers (book_publishers_id INT NOT NULL, publishers_id INT NOT NULL, INDEX IDX_A198F1CFFE04A2F1 (book_publishers_id), INDEX IDX_A198F1CF9BED9AFD (publishers_id), PRIMARY KEY(book_publishers_id, publishers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, loans_id INT DEFAULT NULL, book_publishers_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, genre VARCHAR(255) DEFAULT NULL, publish_year DATE DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_4A1B2A92F675F31B (author_id), INDEX IDX_4A1B2A929AB85012 (loans_id), INDEX IDX_4A1B2A92FE04A2F1 (book_publishers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE branches (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, book_genres_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A8EBE51678953045 (book_genres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE librarians (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, hire_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE loans (id INT AUTO_INCREMENT NOT NULL, loan_date DATE DEFAULT NULL, due_date DATE DEFAULT NULL, return_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publishers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE readers (id INT AUTO_INCREMENT NOT NULL, loans_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, registration_date DATE DEFAULT NULL, INDEX IDX_34AD8C059AB85012 (loans_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_genres_books ADD CONSTRAINT FK_7A6C6F9E78953045 FOREIGN KEY (book_genres_id) REFERENCES book_genres (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_genres_books ADD CONSTRAINT FK_7A6C6F9E7DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_publishers_publishers ADD CONSTRAINT FK_A198F1CFFE04A2F1 FOREIGN KEY (book_publishers_id) REFERENCES book_publishers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_publishers_publishers ADD CONSTRAINT FK_A198F1CF9BED9AFD FOREIGN KEY (publishers_id) REFERENCES publishers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A929AB85012 FOREIGN KEY (loans_id) REFERENCES loans (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92FE04A2F1 FOREIGN KEY (book_publishers_id) REFERENCES book_publishers (id)');
        $this->addSql('ALTER TABLE genres ADD CONSTRAINT FK_A8EBE51678953045 FOREIGN KEY (book_genres_id) REFERENCES book_genres (id)');
        $this->addSql('ALTER TABLE readers ADD CONSTRAINT FK_34AD8C059AB85012 FOREIGN KEY (loans_id) REFERENCES loans (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_genres_books DROP FOREIGN KEY FK_7A6C6F9E78953045');
        $this->addSql('ALTER TABLE book_genres_books DROP FOREIGN KEY FK_7A6C6F9E7DD8AC20');
        $this->addSql('ALTER TABLE book_publishers_publishers DROP FOREIGN KEY FK_A198F1CFFE04A2F1');
        $this->addSql('ALTER TABLE book_publishers_publishers DROP FOREIGN KEY FK_A198F1CF9BED9AFD');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92F675F31B');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A929AB85012');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92FE04A2F1');
        $this->addSql('ALTER TABLE genres DROP FOREIGN KEY FK_A8EBE51678953045');
        $this->addSql('ALTER TABLE readers DROP FOREIGN KEY FK_34AD8C059AB85012');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE book_genres');
        $this->addSql('DROP TABLE book_genres_books');
        $this->addSql('DROP TABLE book_publishers');
        $this->addSql('DROP TABLE book_publishers_publishers');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE branches');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE librarians');
        $this->addSql('DROP TABLE loans');
        $this->addSql('DROP TABLE publishers');
        $this->addSql('DROP TABLE readers');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
