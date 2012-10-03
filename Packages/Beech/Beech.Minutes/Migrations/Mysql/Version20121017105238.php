<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121017105238 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_minutes_domain_model_minute (persistence_object_identifier VARCHAR(40) NOT NULL, personsubject VARCHAR(40) DEFAULT NULL, personinitiator VARCHAR(40) DEFAULT NULL, title VARCHAR(255) NOT NULL, minutetype VARCHAR(255) DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, creationdatetime DATETIME NOT NULL, INDEX IDX_42CEA5FD9E58E94F (personsubject), INDEX IDX_42CEA5FDFF9D15C0 (personinitiator), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_minutes_domain_model_minute ADD CONSTRAINT FK_42CEA5FD9E58E94F FOREIGN KEY (personsubject) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_minutes_domain_model_minute ADD CONSTRAINT FK_42CEA5FDFF9D15C0 FOREIGN KEY (personinitiator) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_minutes_domain_model_minute");
	}
}

?>