<?php

use yii\db\Migration;

class m260518_000001_create_ecitizen_payment_tables extends Migration
{
    public function safeUp()
    {
        $this->createPortalTables();
        $this->createSmisTables();
    }

    public function safeDown()
    {
        $this->execute('DROP TABLE IF EXISTS smisportal.ecitizen CASCADE');
        $this->execute('DROP FUNCTION IF EXISTS smisportal.ecitizen_require_app_context() CASCADE');

        $smisDb = Yii::$app->get('smisDb');
        $smisDb->createCommand('DROP TABLE IF EXISTS smis.ecitizen CASCADE')->execute();
        $smisDb->createCommand('DROP FUNCTION IF EXISTS smis.ecitizen_require_app_context() CASCADE')->execute();
    }

    private function createPortalTables(): void
    {
        $this->execute('CREATE SCHEMA IF NOT EXISTS smisportal');

        $this->execute($this->createEcitizenTableSql('smisportal'));
        $this->execute($this->createRegistrationIndexSql('smisportal'));
        $this->execute($this->createBillRefIndexSql('smisportal'));
        $this->execute($this->createWriteGuardFunctionSql('smisportal'));
        $this->execute($this->dropWriteGuardTriggerSql('smisportal'));
        $this->execute($this->createWriteGuardTriggerSql('smisportal'));
    }

    private function createSmisTables(): void
    {
        $smisDb = Yii::$app->get('smisDb');
        $smisDb->createCommand($this->createEcitizenTableSql('smis'))->execute();
        $smisDb->createCommand($this->createRegistrationIndexSql('smis'))->execute();
        $smisDb->createCommand($this->createBillRefIndexSql('smis'))->execute();
        $smisDb->createCommand($this->createWriteGuardFunctionSql('smis'))->execute();
        $smisDb->createCommand($this->dropWriteGuardTriggerSql('smis'))->execute();
        $smisDb->createCommand($this->createWriteGuardTriggerSql('smis'))->execute();
    }

    private function createEcitizenTableSql(string $schema): string
    {
        return <<<SQL
CREATE TABLE IF NOT EXISTS {$schema}.ecitizen (
    payment_id bigserial PRIMARY KEY,
    "apiClientID" bigint DEFAULT NULL,
    "secureHash" varchar(255) DEFAULT NULL,
    "billDesc" varchar(100) DEFAULT NULL,
    "billRefNumber" varchar(100) DEFAULT NULL,
    currency varchar(3) DEFAULT NULL,
    "serviceID" varchar(8) DEFAULT NULL,
    "clientMSISDN" varchar(20) DEFAULT NULL,
    "clientName" varchar(100) DEFAULT NULL,
    "clientIDNumber" varchar(30) DEFAULT NULL,
    "clientEmail" varchar(150) DEFAULT NULL,
    "callBackURLOnSuccess" varchar(255) DEFAULT NULL,
    "pictureURL" varchar(255) DEFAULT NULL,
    "notificationURL" varchar(255) DEFAULT NULL,
    "amountExpected" numeric(10, 2) DEFAULT NULL,
    registration_number varchar(30) DEFAULT NULL,
    trans_date timestamp without time zone NOT NULL DEFAULT now(),
    response text DEFAULT NULL,
    status varchar(10) DEFAULT 'Pending'
)
SQL;
    }

    private function createRegistrationIndexSql(string $schema): string
    {
        return <<<SQL
CREATE INDEX IF NOT EXISTS idx_ecitizen_registration_number
    ON {$schema}.ecitizen (registration_number)
SQL;
    }

    private function createBillRefIndexSql(string $schema): string
    {
        return <<<SQL
CREATE INDEX IF NOT EXISTS idx_ecitizen_bill_ref_number
    ON {$schema}.ecitizen ("billRefNumber")
SQL;
    }

    private function createWriteGuardFunctionSql(string $schema): string
    {
        return <<<SQL
CREATE OR REPLACE FUNCTION {$schema}.ecitizen_require_app_context()
RETURNS trigger
LANGUAGE plpgsql
AS \$\$
BEGIN
    IF current_setting('smisportal.ecitizen_app_write', true) IS DISTINCT FROM '1' THEN
        RAISE EXCEPTION 'Direct writes to %.ecitizen are blocked. Use the eCitizen application workflow.', TG_TABLE_SCHEMA
            USING ERRCODE = '42501';
    END IF;

    RETURN COALESCE(NEW, OLD);
END;
\$\$
SQL;
    }

    private function dropWriteGuardTriggerSql(string $schema): string
    {
        return <<<SQL
DROP TRIGGER IF EXISTS ecitizen_app_write_guard ON {$schema}.ecitizen
SQL;
    }

    private function createWriteGuardTriggerSql(string $schema): string
    {
        return <<<SQL
CREATE TRIGGER ecitizen_app_write_guard
BEFORE INSERT OR UPDATE OR DELETE ON {$schema}.ecitizen
FOR EACH ROW
EXECUTE FUNCTION {$schema}.ecitizen_require_app_context()
SQL;
    }
}
