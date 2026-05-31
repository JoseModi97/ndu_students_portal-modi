<?php

use yii\db\Migration;

/**
 * Aligns the smisportal FSS refund schema with the SMIS DDL without dropping
 * existing portal tables or data.
 */
class m260529_024348_create_fss_refund_tables_portal extends Migration
{
    public function safeUp()
    {
        $this->execute(<<<'SQL'
CREATE TABLE IF NOT EXISTS smisportal.fss_banks (
    bank_code varchar(40) NULL,
    bank_name varchar(255) NULL,
    brank_id int4 GENERATED ALWAYS AS IDENTITY(
        INCREMENT BY 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1 NO CYCLE
    ) NOT NULL,
    status int4 DEFAULT 1 NULL,
    CONSTRAINT fss_banks_pk PRIMARY KEY (brank_id)
);

CREATE TABLE IF NOT EXISTS smisportal.fss_bank_branches (
    branch_code varchar(40) NULL,
    branch_name varchar(255) NULL,
    bank_code varchar(40) NULL,
    branch_id int4 GENERATED ALWAYS AS IDENTITY(
        INCREMENT BY 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1 NO CYCLE
    ) NOT NULL,
    CONSTRAINT fss_bank_branches_pkey PRIMARY KEY (branch_id)
);

CREATE TABLE IF NOT EXISTS smisportal.fss_refund_types (
    refund_type_id int4 NOT NULL,
    refund_type_name varchar NOT NULL,
    refund_type_status bool NOT NULL,
    CONSTRAINT fss_refund_types_pkey PRIMARY KEY (refund_type_id)
);

CREATE TABLE IF NOT EXISTS smisportal."fss_refund_approval levels" (
    approval_level_id int4 NOT NULL,
    approval_order varchar(30) NOT NULL,
    description varchar(150) NOT NULL,
    CONSTRAINT "fss_refund_approval levels_pkey" PRIMARY KEY (approval_level_id)
);

CREATE TABLE IF NOT EXISTS smisportal.fss_refund_approvers (
    approver_id int4 NOT NULL,
    user_id varchar(30) NOT NULL,
    approval_level_id int4 NOT NULL,
    approver_status varchar NOT NULL,
    CONSTRAINT fss_refund_approvers_pkey PRIMARY KEY (approver_id)
);

CREATE TABLE IF NOT EXISTS smisportal.sm_student_programme_curriculum (
    student_prog_curriculum_id int8 GENERATED ALWAYS AS IDENTITY(
        INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE
    ) NOT NULL,
    student_id int8 NOT NULL,
    registration_number varchar(20) NOT NULL,
    prog_curriculum_id int8 NOT NULL,
    student_category_id int4 NOT NULL,
    adm_refno int4 NOT NULL,
    status_id int4 NOT NULL,
    userid varchar(20) NULL,
    ip_address varchar(50) NULL,
    user_action varchar(10) NULL,
    last_update timestamp NULL,
    CONSTRAINT student_programme_curriculum_pkey PRIMARY KEY (student_prog_curriculum_id)
);

CREATE TABLE IF NOT EXISTS smisportal.fss_refund_requests (
    request_id int8 NOT NULL,
    student_prog_curriculum_id int8 NOT NULL,
    mobile_no varchar(20) NOT NULL,
    email varchar(100) NOT NULL,
    application_date timestamp NOT NULL,
    refund_status varchar(30) NOT NULL,
    account_no varchar(50) NULL,
    account_name varchar(120) NULL,
    bank_id int4 NULL,
    branch_id int4 NULL,
    passport_id varchar(30) NOT NULL,
    declaration_status varchar(3) NOT NULL,
    amount_requested numeric NOT NULL,
    approval_status varchar(50) NOT NULL,
    voucher_no int8 NULL,
    amount_approved numeric NULL,
    refund_type int4 NOT NULL,
    CONSTRAINT fss_refund_requests_pkey PRIMARY KEY (request_id)
);

CREATE TABLE IF NOT EXISTS smisportal.fss_refund_approval_process (
    approval_process_id int8 NOT NULL,
    request_id int8 NOT NULL,
    approval_status varchar(30) NOT NULL,
    remarks varchar(150) NULL,
    approval_date timestamp NOT NULL,
    approver_id int4 NOT NULL,
    CONSTRAINT fss_refund_approval_process_pkey PRIMARY KEY (approval_process_id)
);
SQL);

        $this->execute(<<<'SQL'
ALTER TABLE smisportal.fss_banks
    ADD COLUMN IF NOT EXISTS bank_code varchar(40) NULL,
    ADD COLUMN IF NOT EXISTS bank_name varchar(255) NULL,
    ADD COLUMN IF NOT EXISTS status int4 DEFAULT 1 NULL;

ALTER TABLE smisportal.fss_banks
    ALTER COLUMN bank_code TYPE varchar(40),
    ALTER COLUMN bank_name TYPE varchar(255),
    ALTER COLUMN status TYPE int4,
    ALTER COLUMN status SET DEFAULT 1;

ALTER TABLE smisportal.fss_bank_branches
    ADD COLUMN IF NOT EXISTS branch_code varchar(40) NULL,
    ADD COLUMN IF NOT EXISTS branch_name varchar(255) NULL,
    ADD COLUMN IF NOT EXISTS bank_code varchar(40) NULL;

ALTER TABLE smisportal.fss_bank_branches
    ALTER COLUMN branch_code TYPE varchar(40),
    ALTER COLUMN branch_name TYPE varchar(255),
    ALTER COLUMN bank_code TYPE varchar(40);

ALTER TABLE smisportal.fss_refund_types
    ADD COLUMN IF NOT EXISTS refund_type_name varchar NOT NULL,
    ADD COLUMN IF NOT EXISTS refund_type_status bool NOT NULL;

ALTER TABLE smisportal.fss_refund_types
    ALTER COLUMN refund_type_name TYPE varchar,
    ALTER COLUMN refund_type_name SET NOT NULL,
    ALTER COLUMN refund_type_status TYPE bool,
    ALTER COLUMN refund_type_status SET NOT NULL;

ALTER TABLE smisportal."fss_refund_approval levels"
    ADD COLUMN IF NOT EXISTS approval_order varchar(30) NOT NULL,
    ADD COLUMN IF NOT EXISTS description varchar(150) NOT NULL;

ALTER TABLE smisportal."fss_refund_approval levels"
    ALTER COLUMN approval_order TYPE varchar(30),
    ALTER COLUMN approval_order SET NOT NULL,
    ALTER COLUMN description TYPE varchar(150),
    ALTER COLUMN description SET NOT NULL;

ALTER TABLE smisportal.fss_refund_approvers
    ADD COLUMN IF NOT EXISTS user_id varchar(30) NOT NULL,
    ADD COLUMN IF NOT EXISTS approval_level_id int4 NOT NULL,
    ADD COLUMN IF NOT EXISTS approver_status varchar NOT NULL;

ALTER TABLE smisportal.fss_refund_approvers
    ALTER COLUMN user_id TYPE varchar(30),
    ALTER COLUMN user_id SET NOT NULL,
    ALTER COLUMN approval_level_id TYPE int4,
    ALTER COLUMN approval_level_id SET NOT NULL,
    ALTER COLUMN approver_status TYPE varchar,
    ALTER COLUMN approver_status SET NOT NULL;

ALTER TABLE smisportal.sm_student_programme_curriculum
    ADD COLUMN IF NOT EXISTS student_id int8 NOT NULL,
    ADD COLUMN IF NOT EXISTS registration_number varchar(20) NOT NULL,
    ADD COLUMN IF NOT EXISTS prog_curriculum_id int8 NOT NULL,
    ADD COLUMN IF NOT EXISTS student_category_id int4 NOT NULL,
    ADD COLUMN IF NOT EXISTS adm_refno int4 NOT NULL,
    ADD COLUMN IF NOT EXISTS status_id int4 NOT NULL,
    ADD COLUMN IF NOT EXISTS userid varchar(20) NULL,
    ADD COLUMN IF NOT EXISTS ip_address varchar(50) NULL,
    ADD COLUMN IF NOT EXISTS user_action varchar(10) NULL,
    ADD COLUMN IF NOT EXISTS last_update timestamp NULL;

ALTER TABLE smisportal.sm_student_programme_curriculum
    ALTER COLUMN student_id TYPE int8,
    ALTER COLUMN registration_number TYPE varchar(20),
    ALTER COLUMN prog_curriculum_id TYPE int8,
    ALTER COLUMN student_category_id TYPE int4,
    ALTER COLUMN adm_refno TYPE int4,
    ALTER COLUMN status_id TYPE int4,
    ALTER COLUMN userid TYPE varchar(20),
    ALTER COLUMN ip_address TYPE varchar(50),
    ALTER COLUMN user_action TYPE varchar(10),
    ALTER COLUMN last_update TYPE timestamp,
    ALTER COLUMN student_id SET NOT NULL,
    ALTER COLUMN registration_number SET NOT NULL,
    ALTER COLUMN prog_curriculum_id SET NOT NULL,
    ALTER COLUMN student_category_id SET NOT NULL,
    ALTER COLUMN adm_refno SET NOT NULL,
    ALTER COLUMN status_id SET NOT NULL;

ALTER TABLE smisportal.fss_refund_requests
    ADD COLUMN IF NOT EXISTS student_prog_curriculum_id int8 NOT NULL,
    ADD COLUMN IF NOT EXISTS mobile_no varchar(20) NOT NULL,
    ADD COLUMN IF NOT EXISTS email varchar(100) NOT NULL,
    ADD COLUMN IF NOT EXISTS application_date timestamp NOT NULL,
    ADD COLUMN IF NOT EXISTS refund_status varchar(30) NOT NULL,
    ADD COLUMN IF NOT EXISTS account_no varchar(50) NULL,
    ADD COLUMN IF NOT EXISTS account_name varchar(120) NULL,
    ADD COLUMN IF NOT EXISTS bank_id int4 NULL,
    ADD COLUMN IF NOT EXISTS branch_id int4 NULL,
    ADD COLUMN IF NOT EXISTS passport_id varchar(30) NOT NULL,
    ADD COLUMN IF NOT EXISTS declaration_status varchar(3) NOT NULL,
    ADD COLUMN IF NOT EXISTS amount_requested numeric NOT NULL,
    ADD COLUMN IF NOT EXISTS approval_status varchar(50) NOT NULL,
    ADD COLUMN IF NOT EXISTS voucher_no int8 NULL,
    ADD COLUMN IF NOT EXISTS amount_approved numeric NULL,
    ADD COLUMN IF NOT EXISTS refund_type int4 NOT NULL;

ALTER TABLE smisportal.fss_refund_requests
    ALTER COLUMN request_id TYPE int8,
    ALTER COLUMN student_prog_curriculum_id TYPE int8,
    ALTER COLUMN mobile_no TYPE varchar(20),
    ALTER COLUMN email TYPE varchar(100),
    ALTER COLUMN application_date TYPE timestamp,
    ALTER COLUMN refund_status TYPE varchar(30),
    ALTER COLUMN account_no TYPE varchar(50),
    ALTER COLUMN account_name TYPE varchar(120),
    ALTER COLUMN bank_id TYPE int4,
    ALTER COLUMN branch_id TYPE int4,
    ALTER COLUMN passport_id TYPE varchar(30),
    ALTER COLUMN declaration_status TYPE varchar(3),
    ALTER COLUMN amount_requested TYPE numeric,
    ALTER COLUMN approval_status TYPE varchar(50),
    ALTER COLUMN voucher_no TYPE int8,
    ALTER COLUMN amount_approved TYPE numeric,
    ALTER COLUMN refund_type TYPE int4,
    ALTER COLUMN request_id SET NOT NULL,
    ALTER COLUMN student_prog_curriculum_id SET NOT NULL,
    ALTER COLUMN mobile_no SET NOT NULL,
    ALTER COLUMN email SET NOT NULL,
    ALTER COLUMN application_date SET NOT NULL,
    ALTER COLUMN refund_status SET NOT NULL,
    ALTER COLUMN passport_id SET NOT NULL,
    ALTER COLUMN declaration_status SET NOT NULL,
    ALTER COLUMN amount_requested SET NOT NULL,
    ALTER COLUMN approval_status SET NOT NULL,
    ALTER COLUMN refund_type SET NOT NULL;

ALTER TABLE smisportal.fss_refund_approval_process
    ADD COLUMN IF NOT EXISTS request_id int8 NOT NULL,
    ADD COLUMN IF NOT EXISTS approval_status varchar(30) NOT NULL,
    ADD COLUMN IF NOT EXISTS remarks varchar(150) NULL,
    ADD COLUMN IF NOT EXISTS approval_date timestamp NOT NULL,
    ADD COLUMN IF NOT EXISTS approver_id int4 NOT NULL;

ALTER TABLE smisportal.fss_refund_approval_process
    ALTER COLUMN approval_process_id TYPE int8,
    ALTER COLUMN request_id TYPE int8,
    ALTER COLUMN approval_status TYPE varchar(30),
    ALTER COLUMN remarks TYPE varchar(150),
    ALTER COLUMN approval_date TYPE timestamp,
    ALTER COLUMN approver_id TYPE int4,
    ALTER COLUMN approval_process_id SET NOT NULL,
    ALTER COLUMN request_id SET NOT NULL,
    ALTER COLUMN approval_status SET NOT NULL,
    ALTER COLUMN approval_date SET NOT NULL,
    ALTER COLUMN approver_id SET NOT NULL;

ALTER TABLE smisportal.fss_refund_requests
    DROP COLUMN IF EXISTS payment_method,
    DROP COLUMN IF EXISTS sync_status,
    DROP COLUMN IF EXISTS sync_error,
    DROP COLUMN IF EXISTS last_synced_at;
SQL);

        $this->execute(<<<'SQL'
DO $$
BEGIN
    ALTER TABLE smisportal.fss_refund_approvers DROP CONSTRAINT IF EXISTS fk_fss_refund_approvers_approval_level_id;
    ALTER TABLE smisportal.fss_refund_requests DROP CONSTRAINT IF EXISTS fk_fss_refund_requests_bank_id;
    ALTER TABLE smisportal.fss_refund_requests DROP CONSTRAINT IF EXISTS fk_fss_refund_requests_branch_id;
    ALTER TABLE smisportal.fss_refund_requests DROP CONSTRAINT IF EXISTS fk_fss_refund_requests_refund_type;
    ALTER TABLE smisportal.fss_refund_requests DROP CONSTRAINT IF EXISTS fk_fss_refund_requests_student_prog_curr;
    ALTER TABLE smisportal.fss_refund_approval_process DROP CONSTRAINT IF EXISTS fk_fss_refund_approval_process_request_id;
    ALTER TABLE smisportal.fss_refund_approval_process DROP CONSTRAINT IF EXISTS fk_fss_refund_approval_process_approver_id;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_approval_level_id' AND conrelid = 'smisportal.fss_refund_approvers'::regclass) THEN
        ALTER TABLE smisportal.fss_refund_approvers
            ADD CONSTRAINT fk_approval_level_id FOREIGN KEY (approval_level_id)
            REFERENCES smisportal."fss_refund_approval levels"(approval_level_id);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_bank_id' AND conrelid = 'smisportal.fss_refund_requests'::regclass) THEN
        ALTER TABLE smisportal.fss_refund_requests
            ADD CONSTRAINT fk_bank_id FOREIGN KEY (bank_id)
            REFERENCES smisportal.fss_banks(brank_id);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_branch_id' AND conrelid = 'smisportal.fss_refund_requests'::regclass) THEN
        ALTER TABLE smisportal.fss_refund_requests
            ADD CONSTRAINT fk_branch_id FOREIGN KEY (branch_id)
            REFERENCES smisportal.fss_bank_branches(branch_id);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_refund_type' AND conrelid = 'smisportal.fss_refund_requests'::regclass) THEN
        ALTER TABLE smisportal.fss_refund_requests
            ADD CONSTRAINT fk_refund_type FOREIGN KEY (refund_type)
            REFERENCES smisportal.fss_refund_types(refund_type_id);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_student_prog_curriculum_id' AND conrelid = 'smisportal.fss_refund_requests'::regclass) THEN
        ALTER TABLE smisportal.fss_refund_requests
            ADD CONSTRAINT fk_student_prog_curriculum_id FOREIGN KEY (student_prog_curriculum_id)
            REFERENCES smisportal.sm_student_programme_curriculum(student_prog_curriculum_id);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_request_id' AND conrelid = 'smisportal.fss_refund_approval_process'::regclass) THEN
        ALTER TABLE smisportal.fss_refund_approval_process
            ADD CONSTRAINT fk_request_id FOREIGN KEY (request_id)
            REFERENCES smisportal.fss_refund_requests(request_id);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_approver_id' AND conrelid = 'smisportal.fss_refund_approval_process'::regclass) THEN
        ALTER TABLE smisportal.fss_refund_approval_process
            ADD CONSTRAINT fk_approver_id FOREIGN KEY (approver_id)
            REFERENCES smisportal.fss_refund_approvers(approver_id);
    END IF;
END $$;
SQL);

        $this->batchInsertMissing(
            'smisportal.fss_refund_types',
            'refund_type_id',
            ['refund_type_id', 'refund_type_name', 'refund_type_status'],
            [
                [1, 'STANDARD', true],
                [2, 'CHSS', true],
            ]
        );

        $this->batchInsertMissing(
            'smisportal."fss_refund_approval levels"',
            'approval_level_id',
            ['approval_level_id', 'approval_order', 'description'],
            [
                [1, '1', 'Dean of Students'],
                [2, '2', 'Finance Office'],
                [3, '3', 'Audit Office'],
            ]
        );
    }

    public function safeDown()
    {
        echo "m260529_024348_create_fss_refund_tables_portal is non-destructive and cannot be reverted safely.\n";
        return false;
    }

    private function batchInsertMissing(string $table, string $keyColumn, array $columns, array $rows): void
    {
        foreach ($rows as $row) {
            $key = $row[array_search($keyColumn, $columns, true)];
            $exists = (new yii\db\Query())
                ->from($table)
                ->where([$keyColumn => $key])
                ->exists($this->db);

            if (!$exists) {
                $this->insert($table, array_combine($columns, $row));
            }
        }
    }
}
