/*
 Navicat Premium Data Transfer

 Source Server         : POSTGRESS_PORTAL_DEV
 Source Server Type    : PostgreSQL
 Source Server Version : 140005
 Source Host           : 41.89.92.189:5432
 Source Catalog        : devdb2
 Source Schema         : smisportal

 Target Server Type    : PostgreSQL
 Target Server Version : 140005
 File Encoding         : 65001

 Date: 03/03/2023 14:00:48
*/


-- ----------------------------
-- Sequence structure for sm_id_request_status_status_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_id_request_status_status_id_seq";
CREATE SEQUENCE "smisportal"."sm_id_request_status_status_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_id_request_type_request_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_id_request_type_request_type_id_seq";
CREATE SEQUENCE "smisportal"."sm_id_request_type_request_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_intake_source_source_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_intake_source_source_id_seq";
CREATE SEQUENCE "smisportal"."sm_intake_source_source_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_intakes_intake_code_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_intakes_intake_code_seq";
CREATE SEQUENCE "smisportal"."sm_intakes_intake_code_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_name_change_name_change_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_name_change_name_change_id_seq";
CREATE SEQUENCE "smisportal"."sm_name_change_name_change_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_reg_documents_document_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_reg_documents_document_id_seq";
CREATE SEQUENCE "smisportal"."sm_reg_documents_document_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_reg_required_document_required_document_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_reg_required_document_required_document_id_seq";
CREATE SEQUENCE "smisportal"."sm_reg_required_document_required_document_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_student_category_std_category_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_student_category_std_category_id_seq";
CREATE SEQUENCE "smisportal"."sm_student_category_std_category_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_student_id_request_request_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_student_id_request_request_id_seq";
CREATE SEQUENCE "smisportal"."sm_student_id_request_request_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_withdrawal_request_withdrawal_request_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_withdrawal_request_withdrawal_request_id_seq";
CREATE SEQUENCE "smisportal"."sm_withdrawal_request_withdrawal_request_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_withdrawal_type_withdrawal_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."sm_withdrawal_type_withdrawal_type_id_seq";
CREATE SEQUENCE "smisportal"."sm_withdrawal_type_withdrawal_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for student_submitted_documents_student_document_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smisportal"."student_submitted_documents_student_document_id_seq";
CREATE SEQUENCE "smisportal"."student_submitted_documents_student_document_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Table structure for cr_course_category
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."cr_course_category";
CREATE TABLE "smisportal"."cr_course_category" (
  "category_id" int4 NOT NULL,
  "category_name" varchar COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."cr_course_category" IS 'eg  teaching practice, lab, project, ordinary course unit';

-- ----------------------------
-- Records of cr_course_category
-- ----------------------------

-- ----------------------------
-- Table structure for cr_course_reg_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."cr_course_reg_status";
CREATE TABLE "smisportal"."cr_course_reg_status" (
  "course_reg_status_id" int4 NOT NULL,
  "course_reg_status_name" varchar COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."cr_course_reg_status" IS 'eg provisional, confirmed, transferred';

-- ----------------------------
-- Records of cr_course_reg_status
-- ----------------------------

-- ----------------------------
-- Table structure for cr_course_reg_type
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."cr_course_reg_type";
CREATE TABLE "smisportal"."cr_course_reg_type" (
  "course_reg_type_id" int8 NOT NULL,
  "course_reg_type_code" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "course_reg_type_name" varchar(20) COLLATE "pg_catalog"."default",
  "course_reg_entry_type" varchar(20) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON COLUMN "smisportal"."cr_course_reg_type"."course_reg_type_code" IS 'FA,SUPP,RETAKE';
COMMENT ON COLUMN "smisportal"."cr_course_reg_type"."course_reg_type_name" IS 'FIRST ATTEMPT, SUPPLIMENTARY,RETAKE';
COMMENT ON COLUMN "smisportal"."cr_course_reg_type"."course_reg_entry_type" IS 'ORIGINAL,PASSMARK';
COMMENT ON TABLE "smisportal"."cr_course_reg_type" IS 'Course registration types';

-- ----------------------------
-- Records of cr_course_reg_type
-- ----------------------------

-- ----------------------------
-- Table structure for cr_course_registration
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."cr_course_registration";
CREATE TABLE "smisportal"."cr_course_registration" (
  "student_course_reg_id" int8 NOT NULL,
  "timetable_id" int4 NOT NULL,
  "student_semester_session_id" int8 NOT NULL,
  "course_registration_type_id" int4 NOT NULL,
  "registration_date" timestamptz(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "course_reg_status_id" int4 NOT NULL,
  "source_ipaddress" varchar(100) COLLATE "pg_catalog"."default",
  "userid" varchar(30) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."cr_course_registration" IS 'Courses registered by student in a timetable';

-- ----------------------------
-- Records of cr_course_registration
-- ----------------------------

-- ----------------------------
-- Table structure for cr_prog_curr_timetable
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."cr_prog_curr_timetable";
CREATE TABLE "smisportal"."cr_prog_curr_timetable" (
  "timetable_id" int8 NOT NULL,
  "prog_curriculum_course_id" int8 NOT NULL,
  "prog_curriculum_sem_group_id" int8 NOT NULL,
  "exam_date" timestamptz(6),
  "exam_venue" int4,
  "exam_mode" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."cr_prog_curr_timetable" IS 'programme curriculum timetable';

-- ----------------------------
-- Records of cr_prog_curr_timetable
-- ----------------------------

-- ----------------------------
-- Table structure for cr_programme_curr_lecture_timetable
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."cr_programme_curr_lecture_timetable";
CREATE TABLE "smisportal"."cr_programme_curr_lecture_timetable" (
  "lecture_timetable_id" int8 NOT NULL,
  "timetable_id" int8 NOT NULL,
  "lecture_room_id" int4,
  "day_id" int4,
  "start_time" timestamptz(6),
  "end_time" timestamptz(6),
  "class_code" int4
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."cr_programme_curr_lecture_timetable" IS 'lecture tiemtable';

-- ----------------------------
-- Records of cr_programme_curr_lecture_timetable
-- ----------------------------

-- ----------------------------
-- Table structure for ex_grading_system
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."ex_grading_system";
CREATE TABLE "smisportal"."ex_grading_system" (
  "grading_system_id" int8 NOT NULL,
  "grading_system_name" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "grading_system_desc" varchar(60) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of ex_grading_system
-- ----------------------------
INSERT INTO "smisportal"."ex_grading_system" VALUES (1, 'MASTERS', 'MASTERS', 'ACTIVE');
INSERT INTO "smisportal"."ex_grading_system" VALUES (2, 'PGD', 'POSTGRADUATE DIPLOMA', 'ACTIVE');
INSERT INTO "smisportal"."ex_grading_system" VALUES (3, 'BACHELORS', 'BACHELORS', 'ACTIVE');
INSERT INTO "smisportal"."ex_grading_system" VALUES (4, 'HND', 'HIGHER NATIONAL DIPLOMAS', 'ACTIVE');
INSERT INTO "smisportal"."ex_grading_system" VALUES (5, 'DIPLOMA', 'ORDINARY DIPLOMA', 'ACTIVE');
INSERT INTO "smisportal"."ex_grading_system" VALUES (6, 'Percentage Grading', 'From 0 to 100 Percent', 'ACTIVE');
INSERT INTO "smisportal"."ex_grading_system" VALUES (7, 'Letter grading', 'From A Grade to F Grade', 'ACTIVE');

-- ----------------------------
-- Table structure for ex_grading_system_detail
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."ex_grading_system_detail";
CREATE TABLE "smisportal"."ex_grading_system_detail" (
  "grading_system_detail_id" int8 NOT NULL,
  "grading_system_id" int2 NOT NULL,
  "lower_bound" float4 NOT NULL,
  "upper_bound" float4 NOT NULL,
  "grade" varchar(2) COLLATE "pg_catalog"."default" NOT NULL,
  "grade_point" float4,
  "is_active" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of ex_grading_system_detail
-- ----------------------------

-- ----------------------------
-- Table structure for ex_marksheet
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."ex_marksheet";
CREATE TABLE "smisportal"."ex_marksheet" (
  "marksheet_id" int4 NOT NULL,
  "student_course_reg_id" int4 NOT NULL,
  "course_work_mark" float8,
  "exam_mark" float8,
  "final_mark" int4
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."ex_marksheet" IS 'Stores student marks';

-- ----------------------------
-- Records of ex_marksheet
-- ----------------------------

-- ----------------------------
-- Table structure for ex_mode
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."ex_mode";
CREATE TABLE "smisportal"."ex_mode" (
  "exam_mode_id" int4 NOT NULL,
  "exam_mode_name" varchar(30) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."ex_mode" IS 'exam modes( Online, Physical)';

-- ----------------------------
-- Records of ex_mode
-- ----------------------------

-- ----------------------------
-- Table structure for org_acad_session_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_acad_session_status";
CREATE TABLE "smisportal"."org_acad_session_status" (
  "acad_session_status_id" int8 NOT NULL,
  "acad_session_status_name" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "session_status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_acad_session_status
-- ----------------------------

-- ----------------------------
-- Table structure for org_academic_levels
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_academic_levels";
CREATE TABLE "smisportal"."org_academic_levels" (
  "academic_level_id" int8 NOT NULL,
  "academic_level" int2 NOT NULL,
  "academic_level_name" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "academic_level_order" int2,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_academic_levels
-- ----------------------------
INSERT INTO "smisportal"."org_academic_levels" VALUES (41, 3, 'Three', 3, 'ACTIVE');
INSERT INTO "smisportal"."org_academic_levels" VALUES (61, 4, 'Four', 4, 'ACTIVE');
INSERT INTO "smisportal"."org_academic_levels" VALUES (81, 5, 'Five', 5, 'ACTIVE');
INSERT INTO "smisportal"."org_academic_levels" VALUES (1, 1, 'One', 1, 'ACTIVE');
INSERT INTO "smisportal"."org_academic_levels" VALUES (21, 2, 'Two', 2, 'ACTIVE');
INSERT INTO "smisportal"."org_academic_levels" VALUES (101, 6, 'Six', 6, 'ACTIVE');

-- ----------------------------
-- Table structure for org_academic_session
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_academic_session";
CREATE TABLE "smisportal"."org_academic_session" (
  "acad_session_id" int4 NOT NULL,
  "acad_session_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "acad_session_desc" varchar(150) COLLATE "pg_catalog"."default",
  "start_date" date NOT NULL,
  "end_date" date NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_academic_session
-- ----------------------------
INSERT INTO "smisportal"."org_academic_session" VALUES (21, 'April 2022 Session', 'April session of 2021/2022 academic year', '2022-04-01', '2022-07-31');
INSERT INTO "smisportal"."org_academic_session" VALUES (61, 'Sep 2021 Session', 'Sept Session of 2021/2022 academic year', '2021-09-01', '2022-01-31');
INSERT INTO "smisportal"."org_academic_session" VALUES (81, 'Sep 2022 Session', 'Sept Session, start of academic year 2022/2023', '2022-09-19', '2023-01-31');
INSERT INTO "smisportal"."org_academic_session" VALUES (101, 'Aug 2022 Supplimentaries', 'August supplementary session for 2021/2022 academic year', '2022-08-01', '2022-09-30');
INSERT INTO "smisportal"."org_academic_session" VALUES (41, 'Apr 2023 Session', 'April session of 2022/2023 Academic year', '2023-04-01', '2023-08-08');
INSERT INTO "smisportal"."org_academic_session" VALUES (141, 'Sep 2023 session', 'September 2023 Session of 2022/2023 Academic year', '2023-09-04', '2023-12-31');
INSERT INTO "smisportal"."org_academic_session" VALUES (121, 'April 2023 Session', 'April 2023 Session of 2022/2023 Academic year', '2023-01-02', '2023-04-07');

-- ----------------------------
-- Table structure for org_academic_session_semester
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_academic_session_semester";
CREATE TABLE "smisportal"."org_academic_session_semester" (
  "acad_session_semester_id" int4 NOT NULL,
  "acad_session_id" int4 NOT NULL,
  "semester_code" int4 NOT NULL,
  "acad_session_semester_desc" varchar(50) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_academic_session_semester
-- ----------------------------
INSERT INTO "smisportal"."org_academic_session_semester" VALUES (41, 61, 1, 'May 2021/2022 First semester ');
INSERT INTO "smisportal"."org_academic_session_semester" VALUES (21, 21, 2, 'September 2023/2024 Second Semester');
INSERT INTO "smisportal"."org_academic_session_semester" VALUES (101, 141, 1, 'Sept 2023/2024 First Semester');
INSERT INTO "smisportal"."org_academic_session_semester" VALUES (81, 41, 2, 'April 2022/2023 - Second Semester');
INSERT INTO "smisportal"."org_academic_session_semester" VALUES (61, 21, 2, 'April  2021/2022 - Second Semester');
INSERT INTO "smisportal"."org_academic_session_semester" VALUES (121, 41, 2, 'April 2022/2023 - Second Semester');
INSERT INTO "smisportal"."org_academic_session_semester" VALUES (1, 121, 1, 'April 2022/2023 First Semester');

-- ----------------------------
-- Table structure for org_cohort
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_cohort";
CREATE TABLE "smisportal"."org_cohort" (
  "cohort_id" int8 NOT NULL,
  "cohort_desc" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "cohort_year" varchar(9) COLLATE "pg_catalog"."default",
  "adm_start_date" date,
  "adm_end_date" date,
  "cohort_status" varchar(30) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_cohort
-- ----------------------------
INSERT INTO "smisportal"."org_cohort" VALUES (81, '2025/2026', NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."org_cohort" VALUES (21, '2023/2024', '2023', '2023-01-12', '2023-03-01', 'ACTIVE');
INSERT INTO "smisportal"."org_cohort" VALUES (101, '2023/2024', '2023', '2023-01-01', '2023-12-31', 'ACTIVE');
INSERT INTO "smisportal"."org_cohort" VALUES (161, '2023/2024', '2024', '2023-01-01', '2024-01-01', 'ACTIVE');

-- ----------------------------
-- Table structure for org_cohort_session
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_cohort_session";
CREATE TABLE "smisportal"."org_cohort_session" (
  "cohort_session_id" int8 NOT NULL,
  "cohort_session_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "cohort_id" int8 NOT NULL,
  "prog_curriculum_semester_id" int8 NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_cohort_session
-- ----------------------------

-- ----------------------------
-- Table structure for org_country
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_country";
CREATE TABLE "smisportal"."org_country" (
  "country_code" varchar(5) COLLATE "pg_catalog"."default" NOT NULL,
  "country_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "continent" varchar(100) COLLATE "pg_catalog"."default",
  "region" varchar(100) COLLATE "pg_catalog"."default",
  "code2" varchar(5) COLLATE "pg_catalog"."default" NOT NULL,
  "nationality" varchar(100) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smis"
;

-- ----------------------------
-- Records of org_country
-- ----------------------------
INSERT INTO "smisportal"."org_country" VALUES ('CIV', 'Cote d''Ivoire', 'Africa', 'Western Africa', 'CI', 'Ivorian');
INSERT INTO "smisportal"."org_country" VALUES ('COD', 'Congo, The Democratic Republic of the', 'Africa', 'Central Africa', 'CD', 'Congolese');
INSERT INTO "smisportal"."org_country" VALUES ('COG', 'Congo, Republic of the', 'Africa', 'Central Africa', 'CG', 'Congolese');
INSERT INTO "smisportal"."org_country" VALUES ('ASM', 'American Samoa', 'Oceania', 'Polynesia', 'AS', 'American Samoan');
INSERT INTO "smisportal"."org_country" VALUES ('GMB', 'Gambia', 'Africa', 'Western Africa', 'GM', 'Gambian');
INSERT INTO "smisportal"."org_country" VALUES ('GLP', 'Guadeloupe', 'North America', 'Caribbean', 'GP', 'Guadeloupean');
INSERT INTO "smisportal"."org_country" VALUES ('ANT', 'Netherlands Antilles', 'North America', 'Caribbean', 'AN', 'Netherlands Antillean');
INSERT INTO "smisportal"."org_country" VALUES ('ATA', 'Antarctica', 'Antarctica', 'Antarctica', 'AQ', 'Antarctic');
INSERT INTO "smisportal"."org_country" VALUES ('FJI', 'Fiji Islands', 'Oceania', 'Melanesia', 'FJ', 'Fijian');
INSERT INTO "smisportal"."org_country" VALUES ('GIB', 'Gibraltar', 'Europe', 'Southern Europe', 'GI', 'Gibraltarian');
INSERT INTO "smisportal"."org_country" VALUES ('CXR', 'Christmas Island', 'Oceania', 'Australia and New Zealand', 'CX', 'Christmas Islander');
INSERT INTO "smisportal"."org_country" VALUES ('FLK', 'Falkland Islands', 'South America', 'South America', 'FK', 'Falkland Islander/Falklander');
INSERT INTO "smisportal"."org_country" VALUES ('GRL', 'Greenland', 'North America', 'North America', 'GL', 'Greenlander/Greenlandic');
INSERT INTO "smisportal"."org_country" VALUES ('ATF', 'French Southern territories', 'Antarctica', 'Antarctica', 'TF', 'French');
INSERT INTO "smisportal"."org_country" VALUES ('BVT', 'Bouvet Island', 'Antarctica', 'Antarctica', 'BV', 'Norwegian');
INSERT INTO "smisportal"."org_country" VALUES ('CCK', 'Cocos (Keeling) Islands', 'Oceania', 'Australia and New Zealand', 'CC', 'Australian');
INSERT INTO "smisportal"."org_country" VALUES ('COK', 'Cook Islands', 'Oceania', 'Polynesia', 'CK', 'Cook Islander');
INSERT INTO "smisportal"."org_country" VALUES ('FRO', 'Faroe Islands', 'Europe', 'Nordic Countries', 'FO', 'Faroe IslanderFaroese');
INSERT INTO "smisportal"."org_country" VALUES ('FSM', 'Micronesia, Federated States of', 'Oceania', 'Micronesia', 'FM', 'Micronesian');
INSERT INTO "smisportal"."org_country" VALUES ('AIA', 'Anguilla', 'North America', 'Caribbean', 'AI', 'Anguillan');
INSERT INTO "smisportal"."org_country" VALUES ('ALB', 'Albania', 'Europe', 'Southern Europe', 'AL', 'Albanian');
INSERT INTO "smisportal"."org_country" VALUES ('DZA', 'Algeria', 'Africa', 'Northern Africa', 'DZ', 'Algerian');
INSERT INTO "smisportal"."org_country" VALUES ('MMR', 'Myanmar', 'Asia', 'Southeast Asia', 'MM', 'Burmese');
INSERT INTO "smisportal"."org_country" VALUES ('GUM', 'Guam', 'Oceania', 'Micronesia', 'GU', 'Guamanian');
INSERT INTO "smisportal"."org_country" VALUES ('LBY', 'Libyan Arab Jamahiriya', 'Africa', 'Northern Africa', 'LY', 'Libyan');
INSERT INTO "smisportal"."org_country" VALUES ('KAZ', 'Kazakstan', 'Asia', 'Southern and Central Asia', 'KZ', 'Kazakhstani');
INSERT INTO "smisportal"."org_country" VALUES ('MAC', 'Macao', 'Asia', 'Eastern Asia', 'MO', 'Macanese');
INSERT INTO "smisportal"."org_country" VALUES ('KGZ', 'Kyrgyzstan', 'Asia', 'Southern and Central Asia', 'KG', 'Kyrgyz');
INSERT INTO "smisportal"."org_country" VALUES ('MYT', 'Mayotte', 'Africa', 'Eastern Africa', 'YT', 'Mahoran/Maorais');
INSERT INTO "smisportal"."org_country" VALUES ('PRI', 'Puerto Rico', 'North America', 'Caribbean', 'PR', 'Puerto Rican/Boricua');
INSERT INTO "smisportal"."org_country" VALUES ('PRK', 'North Korea', 'Asia', 'Eastern Asia', 'KP', 'North Korean/Korean');
INSERT INTO "smisportal"."org_country" VALUES ('PSE', 'State of Palestine', 'Asia', 'Middle East', 'PS', 'Palestinian');
INSERT INTO "smisportal"."org_country" VALUES ('KOR', 'South Korea', 'Asia', 'Eastern Asia', 'KR', 'South Korean/Korean');
INSERT INTO "smisportal"."org_country" VALUES ('PYF', 'French Polynesia', 'Oceania', 'Polynesia', 'PF', 'French Polynesian');
INSERT INTO "smisportal"."org_country" VALUES ('GUF', 'French Guiana', 'South America', 'South America', 'GF', 'French Guianan/French Guianese');
INSERT INTO "smisportal"."org_country" VALUES ('MSR', 'Montserrat', 'North America', 'Caribbean', 'MS', 'Montserratian');
INSERT INTO "smisportal"."org_country" VALUES ('NCL', 'New Caledonia', 'Oceania', 'Melanesia', 'NC', 'New Caledonian');
INSERT INTO "smisportal"."org_country" VALUES ('NFK', 'Norfolk Island', 'Oceania', 'Australia and New Zealand', 'NF', 'Norfolk Islander');
INSERT INTO "smisportal"."org_country" VALUES ('MNP', 'Northern Mariana Islands', 'Oceania', 'Micronesia', 'MP', 'Northern Mariana Islander');
INSERT INTO "smisportal"."org_country" VALUES ('MTQ', 'Martinique', 'North America', 'Caribbean', 'MQ', 'French');
INSERT INTO "smisportal"."org_country" VALUES ('NIU', 'Niue', 'Oceania', 'Polynesia', 'NU', 'Niuean');
INSERT INTO "smisportal"."org_country" VALUES ('IOT', 'British Indian Ocean Territory', 'Africa', 'Eastern Africa', 'IO', 'British');
INSERT INTO "smisportal"."org_country" VALUES ('HKG', 'Hong Kong', 'Asia', 'Eastern Asia', 'HK', 'Hong Konger/Hong Kongese');
INSERT INTO "smisportal"."org_country" VALUES ('HMD', 'Heard Island and McDonald Islands', 'Antarctica', 'Antarctica', 'HM', 'Australian');
INSERT INTO "smisportal"."org_country" VALUES ('PCN', 'Pitcairn', 'Oceania', 'Polynesia', 'PN', 'Pitcairn Islanders');
INSERT INTO "smisportal"."org_country" VALUES ('REU', 'RÃ©union', 'Africa', 'Eastern Africa', 'RE', 'RÃ©unionese');
INSERT INTO "smisportal"."org_country" VALUES ('QAT', 'Qatar', 'Asia', 'Middle East', 'QA', 'Qatari');
INSERT INTO "smisportal"."org_country" VALUES ('WLF', 'Wallis and Futuna', 'Oceania', 'Polynesia', 'WF', 'Wallisian/Futunan');
INSERT INTO "smisportal"."org_country" VALUES ('VIR', 'Virgin Islands, U.S.', 'North America', 'Caribbean', 'VI', 'American Virgin Islander');
INSERT INTO "smisportal"."org_country" VALUES ('VGB', 'Virgin Islands, British', 'North America', 'Caribbean', 'VG', 'British Virgin Islander');
INSERT INTO "smisportal"."org_country" VALUES ('SSD', 'South Sudan', 'Africa', 'East Africa', 'SS', 'South Sudanese');
INSERT INTO "smisportal"."org_country" VALUES ('VCT', 'Saint Vincent and the Grenadines', 'North America', 'Caribbean', 'VC', 'Saint Vincentian/Vincentian');
INSERT INTO "smisportal"."org_country" VALUES ('UMI', 'United States Minor Outlying Islands', 'Oceania', 'Micronesia/Caribbean', 'UM', 'American Islander');
INSERT INTO "smisportal"."org_country" VALUES ('RUS', 'Russian Federation', 'Europe', 'Eastern Europe', 'RU', 'Russian');
INSERT INTO "smisportal"."org_country" VALUES ('SHN', 'Saint Helena', 'Africa', 'Western Africa', 'SH', 'Saint Helenian/Helenian');
INSERT INTO "smisportal"."org_country" VALUES ('TKL', 'Tokelau', 'Oceania', 'Polynesia', 'TK', 'Tokelauan');
INSERT INTO "smisportal"."org_country" VALUES ('SJM', 'Svalbard and Jan Mayen', 'Europe', 'Nordic Countries', 'SJ', 'Norwegian');
INSERT INTO "smisportal"."org_country" VALUES ('TCA', 'Turks and Caicos Islands', 'North America', 'Caribbean', 'TC', 'Turks and Caicos Islander');
INSERT INTO "smisportal"."org_country" VALUES ('SPM', 'Saint Pierre and Miquelon', 'North America', 'North America', 'PM', 'Saint-Pierrais/Miquelonnais/Pierrian');
INSERT INTO "smisportal"."org_country" VALUES ('SGS', 'South Georgia and the South Sandwich Islands', 'Antarctica', 'Antarctica', 'GS', 'South Georgian/South Sandwich Islander');
INSERT INTO "smisportal"."org_country" VALUES ('VAT', 'Holy See (Vatican City State)', 'Europe', 'Southern Europe', 'VA', 'Papal Pontifical');
INSERT INTO "smisportal"."org_country" VALUES ('YUG', 'Yugoslavia', 'Europe', 'Southern Europe', 'YU', 'Yugoslav');
INSERT INTO "smisportal"."org_country" VALUES ('ABW', 'Aruba', 'North America', 'Caribbean', 'AW', 'Aruban');
INSERT INTO "smisportal"."org_country" VALUES ('AFG', 'Afghanistan', 'Asia', 'Southern and Central Asia', 'AF', 'Afghan');
INSERT INTO "smisportal"."org_country" VALUES ('AND', 'Andorra', 'Europe', 'Southern Europe', 'AD', 'Andorran');
INSERT INTO "smisportal"."org_country" VALUES ('AGO', 'Angola', 'Africa', 'Central Africa', 'AO', 'Angolan');
INSERT INTO "smisportal"."org_country" VALUES ('ATG', 'Antigua and Barbuda', 'North America', 'Caribbean', 'AG', 'Antiguans, Barbudans');
INSERT INTO "smisportal"."org_country" VALUES ('ARG', 'Argentina', 'South America', 'South America', 'AR', 'Argentinean');
INSERT INTO "smisportal"."org_country" VALUES ('ARM', 'Armenia', 'Asia', 'Middle East', 'AM', 'Armenian');
INSERT INTO "smisportal"."org_country" VALUES ('AUS', 'Australia', 'Oceania', 'Australia and New Zealand', 'AU', 'Australian');
INSERT INTO "smisportal"."org_country" VALUES ('AUT', 'Austria', 'Europe', 'Western Europe', 'AT', 'Austrian');
INSERT INTO "smisportal"."org_country" VALUES ('AZE', 'Azerbaijan', 'Asia', 'Middle East', 'AZ', 'Azerbaijani');
INSERT INTO "smisportal"."org_country" VALUES ('BHR', 'Bahrain', 'Asia', 'Middle East', 'BH', 'Bahraini');
INSERT INTO "smisportal"."org_country" VALUES ('BGD', 'Bangladesh', 'Asia', 'Southern and Central Asia', 'BD', 'Bangladeshi');
INSERT INTO "smisportal"."org_country" VALUES ('BRB', 'Barbados', 'North America', 'Caribbean', 'BB', 'Barbadian');
INSERT INTO "smisportal"."org_country" VALUES ('BLR', 'Belarus', 'Europe', 'Eastern Europe', 'BY', 'Belarusian');
INSERT INTO "smisportal"."org_country" VALUES ('BEL', 'Belgium', 'Europe', 'Western Europe', 'BE', 'Belgian');
INSERT INTO "smisportal"."org_country" VALUES ('BLZ', 'Belize', 'North America', 'Central America', 'BZ', 'Belizean');
INSERT INTO "smisportal"."org_country" VALUES ('BEN', 'Benin', 'Africa', 'Western Africa', 'BJ', 'Beninese');
INSERT INTO "smisportal"."org_country" VALUES ('BTN', 'Bhutan', 'Asia', 'Southern and Central Asia', 'BT', 'Bhutanese');
INSERT INTO "smisportal"."org_country" VALUES ('BOL', 'Bolivia', 'South America', 'South America', 'BO', 'Bolivian');
INSERT INTO "smisportal"."org_country" VALUES ('BIH', 'Bosnia and Herzegovina', 'Europe', 'Southern Europe', 'BA', 'Bosnian, Herzegovinian');
INSERT INTO "smisportal"."org_country" VALUES ('BWA', 'Botswana', 'Africa', 'Southern Africa', 'BW', 'Motswana (singular);, Batswana (plural);');
INSERT INTO "smisportal"."org_country" VALUES ('BRA', 'Brazil', 'South America', 'South America', 'BR', 'Brazilian');
INSERT INTO "smisportal"."org_country" VALUES ('BRN', 'Brunei', 'Asia', 'Southeast Asia', 'BN', 'Bruneian');
INSERT INTO "smisportal"."org_country" VALUES ('BGR', 'Bulgaria', 'Europe', 'Eastern Europe', 'BG', 'Bulgarian');
INSERT INTO "smisportal"."org_country" VALUES ('BHS', 'Bahamas', 'North America', 'Caribbean', 'BS', 'Bahamian');
INSERT INTO "smisportal"."org_country" VALUES ('BMU', 'Bermuda', 'North America', 'North America', 'BM', 'Bermudian');
INSERT INTO "smisportal"."org_country" VALUES ('BFA', 'Burkina Faso', 'Africa', 'Western Africa', 'BF', 'Burkinabe');
INSERT INTO "smisportal"."org_country" VALUES ('BDI', 'Burundi', 'Africa', 'Eastern Africa', 'BI', 'Burundian');
INSERT INTO "smisportal"."org_country" VALUES ('KHM', 'Cambodia', 'Asia', 'Southeast Asia', 'KH', 'Cambodian');
INSERT INTO "smisportal"."org_country" VALUES ('CMR', 'Cameroon', 'Africa', 'Central Africa', 'CM', 'Cameroonian');
INSERT INTO "smisportal"."org_country" VALUES ('CAN', 'Canada', 'North America', 'North America', 'CA', 'Canadian');
INSERT INTO "smisportal"."org_country" VALUES ('CPV', 'Cape Verde', 'Africa', 'Western Africa', 'CV', 'Cape Verdian');
INSERT INTO "smisportal"."org_country" VALUES ('CAF', 'Central African Republic', 'Africa', 'Central Africa', 'CF', 'Central African');
INSERT INTO "smisportal"."org_country" VALUES ('TCD', 'Chad', 'Africa', 'Central Africa', 'TD', 'Chadian');
INSERT INTO "smisportal"."org_country" VALUES ('CHL', 'Chile', 'South America', 'South America', 'CL', 'Chilean');
INSERT INTO "smisportal"."org_country" VALUES ('CHN', 'China', 'Asia', 'Eastern Asia', 'CN', 'Chinese');
INSERT INTO "smisportal"."org_country" VALUES ('COL', 'Colombia', 'South America', 'South America', 'CO', 'Colombian');
INSERT INTO "smisportal"."org_country" VALUES ('COM', 'Comoros', 'Africa', 'Eastern Africa', 'KM', 'Comoran');
INSERT INTO "smisportal"."org_country" VALUES ('CRI', 'Costa Rica', 'North America', 'Central America', 'CR', 'Costa Rican');
INSERT INTO "smisportal"."org_country" VALUES ('HRV', 'Croatia', 'Europe', 'Southern Europe', 'HR', 'Croatian');
INSERT INTO "smisportal"."org_country" VALUES ('CUB', 'Cuba', 'North America', 'Caribbean', 'CU', 'Cuban');
INSERT INTO "smisportal"."org_country" VALUES ('CYP', 'Cyprus', 'Asia', 'Middle East', 'CY', 'Cypriot');
INSERT INTO "smisportal"."org_country" VALUES ('CZE', 'Czech Republic', 'Europe', 'Eastern Europe', 'CZ', 'Czech');
INSERT INTO "smisportal"."org_country" VALUES ('DNK', 'Denmark', 'Europe', 'Nordic Countries', 'DK', 'Danish');
INSERT INTO "smisportal"."org_country" VALUES ('DJI', 'Djibouti', 'Africa', 'Eastern Africa', 'DJ', 'Djibouti');
INSERT INTO "smisportal"."org_country" VALUES ('DMA', 'Dominica', 'North America', 'Caribbean', 'DM', 'Dominican');
INSERT INTO "smisportal"."org_country" VALUES ('DOM', 'Dominican Republic', 'North America', 'Caribbean', 'DO', 'Dominican');
INSERT INTO "smisportal"."org_country" VALUES ('TMP', 'East Timor', 'Asia', 'Southeast Asia', 'TP', 'East Timorese');
INSERT INTO "smisportal"."org_country" VALUES ('ECU', 'Ecuador', 'South America', 'South America', 'EC', 'Ecuadorean');
INSERT INTO "smisportal"."org_country" VALUES ('EGY', 'Egypt', 'Africa', 'Northern Africa', 'EG', 'Egyptian');
INSERT INTO "smisportal"."org_country" VALUES ('SLV', 'El Salvador', 'North America', 'Central America', 'SV', 'Salvadoran');
INSERT INTO "smisportal"."org_country" VALUES ('GNQ', 'Equatorial Guinea', 'Africa', 'Central Africa', 'GQ', 'Equatorial Guinean');
INSERT INTO "smisportal"."org_country" VALUES ('ERI', 'Eritrea', 'Africa', 'Eastern Africa', 'ER', 'Eritrean');
INSERT INTO "smisportal"."org_country" VALUES ('EST', 'Estonia', 'Europe', 'Baltic Countries', 'EE', 'Estonian');
INSERT INTO "smisportal"."org_country" VALUES ('ETH', 'Ethiopia', 'Africa', 'Eastern Africa', 'ET', 'Ethiopian');
INSERT INTO "smisportal"."org_country" VALUES ('FIN', 'Finland', 'Europe', 'Nordic Countries', 'FI', 'Finnish');
INSERT INTO "smisportal"."org_country" VALUES ('FRA', 'France', 'Europe', 'Western Europe', 'FR', 'French');
INSERT INTO "smisportal"."org_country" VALUES ('GAB', 'Gabon', 'Africa', 'Central Africa', 'GA', 'Gabonese');
INSERT INTO "smisportal"."org_country" VALUES ('GEO', 'Georgia', 'Asia', 'Middle East', 'GE', 'Georgian');
INSERT INTO "smisportal"."org_country" VALUES ('DEU', 'Germany', 'Europe', 'Western Europe', 'DE', 'German');
INSERT INTO "smisportal"."org_country" VALUES ('GHA', 'Ghana', 'Africa', 'Western Africa', 'GH', 'Ghanaian');
INSERT INTO "smisportal"."org_country" VALUES ('GRC', 'Greece', 'Europe', 'Southern Europe', 'GR', 'Greek');
INSERT INTO "smisportal"."org_country" VALUES ('GRD', 'Grenada', 'North America', 'Caribbean', 'GD', 'Grenadian');
INSERT INTO "smisportal"."org_country" VALUES ('GTM', 'Guatemala', 'North America', 'Central America', 'GT', 'Guatemalan');
INSERT INTO "smisportal"."org_country" VALUES ('GIN', 'Guinea', 'Africa', 'Western Africa', 'GN', 'Guinean');
INSERT INTO "smisportal"."org_country" VALUES ('GNB', 'Guinea-Bissau', 'Africa', 'Western Africa', 'GW', 'Guinea-Bissauan');
INSERT INTO "smisportal"."org_country" VALUES ('GUY', 'Guyana', 'South America', 'South America', 'GY', 'Guyanese');
INSERT INTO "smisportal"."org_country" VALUES ('HTI', 'Haiti', 'North America', 'Caribbean', 'HT', 'Haitian');
INSERT INTO "smisportal"."org_country" VALUES ('HND', 'Honduras', 'North America', 'Central America', 'HN', 'Honduran');
INSERT INTO "smisportal"."org_country" VALUES ('HUN', 'Hungary', 'Europe', 'Eastern Europe', 'HU', 'Hungarian');
INSERT INTO "smisportal"."org_country" VALUES ('ISL', 'Iceland', 'Europe', 'Nordic Countries', 'IS', 'Icelander');
INSERT INTO "smisportal"."org_country" VALUES ('IND', 'India', 'Asia', 'Southern and Central Asia', 'IN', 'Indian');
INSERT INTO "smisportal"."org_country" VALUES ('IDN', 'Indonesia', 'Asia', 'Southeast Asia', 'ID', 'Indonesian');
INSERT INTO "smisportal"."org_country" VALUES ('IRN', 'Iran', 'Asia', 'Southern and Central Asia', 'IR', 'Iranian');
INSERT INTO "smisportal"."org_country" VALUES ('IRQ', 'Iraq', 'Asia', 'Middle East', 'IQ', 'Iraqi');
INSERT INTO "smisportal"."org_country" VALUES ('IRL', 'Ireland', 'Europe', 'British Islands', 'IE', 'Irish');
INSERT INTO "smisportal"."org_country" VALUES ('ISR', 'Israel', 'Asia', 'Middle East', 'IL', 'Israeli');
INSERT INTO "smisportal"."org_country" VALUES ('ITA', 'Italy', 'Europe', 'Southern Europe', 'IT', 'Italian');
INSERT INTO "smisportal"."org_country" VALUES ('JAM', 'Jamaica', 'North America', 'Caribbean', 'JM', 'Jamaican');
INSERT INTO "smisportal"."org_country" VALUES ('JPN', 'Japan', 'Asia', 'Eastern Asia', 'JP', 'Japanese');
INSERT INTO "smisportal"."org_country" VALUES ('JOR', 'Jordan', 'Asia', 'Middle East', 'JO', 'Jordanian');
INSERT INTO "smisportal"."org_country" VALUES ('KEN', 'Kenya', 'Africa', 'Eastern Africa', 'KE', 'Kenyan');
INSERT INTO "smisportal"."org_country" VALUES ('KIR', 'Kiribati', 'Oceania', 'Micronesia', 'KI', 'I-Kiribati');
INSERT INTO "smisportal"."org_country" VALUES ('KWT', 'Kuwait', 'Asia', 'Middle East', 'KW', 'Kuwaiti');
INSERT INTO "smisportal"."org_country" VALUES ('LAO', 'Laos', 'Asia', 'Southeast Asia', 'LA', 'Laotian');
INSERT INTO "smisportal"."org_country" VALUES ('LVA', 'Latvia', 'Europe', 'Baltic Countries', 'LV', 'Latvian');
INSERT INTO "smisportal"."org_country" VALUES ('LBN', 'Lebanon', 'Asia', 'Middle East', 'LB', 'Lebanese');
INSERT INTO "smisportal"."org_country" VALUES ('LSO', 'Lesotho', 'Africa', 'Southern Africa', 'LS', 'Mosotho');
INSERT INTO "smisportal"."org_country" VALUES ('LBR', 'Liberia', 'Africa', 'Western Africa', 'LR', 'Liberian');
INSERT INTO "smisportal"."org_country" VALUES ('LIE', 'Liechtenstein', 'Europe', 'Western Europe', 'LI', 'Liechtensteiner');
INSERT INTO "smisportal"."org_country" VALUES ('LTU', 'Lithuania', 'Europe', 'Baltic Countries', 'LT', 'Lithuanian');
INSERT INTO "smisportal"."org_country" VALUES ('LUX', 'Luxembourg', 'Europe', 'Western Europe', 'LU', 'Luxembourger');
INSERT INTO "smisportal"."org_country" VALUES ('MKD', 'Macedonia', 'Europe', 'Southern Europe', 'MK', 'Macedonian');
INSERT INTO "smisportal"."org_country" VALUES ('MDG', 'Madagascar', 'Africa', 'Eastern Africa', 'MG', 'Malagasy');
INSERT INTO "smisportal"."org_country" VALUES ('MWI', 'Malawi', 'Africa', 'Eastern Africa', 'MW', 'Malawian');
INSERT INTO "smisportal"."org_country" VALUES ('MYS', 'Malaysia', 'Asia', 'Southeast Asia', 'MY', 'Malaysian');
INSERT INTO "smisportal"."org_country" VALUES ('MDV', 'Maldives', 'Asia', 'Southern and Central Asia', 'MV', 'Maldivan');
INSERT INTO "smisportal"."org_country" VALUES ('MLI', 'Mali', 'Africa', 'Western Africa', 'ML', 'Malian');
INSERT INTO "smisportal"."org_country" VALUES ('MLT', 'Malta', 'Europe', 'Southern Europe', 'MT', 'Maltese');
INSERT INTO "smisportal"."org_country" VALUES ('MHL', 'Marshall Islands', 'Oceania', 'Micronesia', 'MH', 'Marshallese');
INSERT INTO "smisportal"."org_country" VALUES ('MRT', 'Mauritania', 'Africa', 'Western Africa', 'MR', 'Mauritanian');
INSERT INTO "smisportal"."org_country" VALUES ('MUS', 'Mauritius', 'Africa', 'Eastern Africa', 'MU', 'Mauritian');
INSERT INTO "smisportal"."org_country" VALUES ('MEX', 'Mexico', 'North America', 'Central America', 'MX', 'Mexican');
INSERT INTO "smisportal"."org_country" VALUES ('MDA', 'Moldova', 'Europe', 'Eastern Europe', 'MD', 'Moldovan');
INSERT INTO "smisportal"."org_country" VALUES ('MCO', 'Monaco', 'Europe', 'Western Europe', 'MC', 'Monegasque');
INSERT INTO "smisportal"."org_country" VALUES ('MNG', 'Mongolia', 'Asia', 'Eastern Asia', 'MN', 'Mongolian');
INSERT INTO "smisportal"."org_country" VALUES ('MAR', 'Morocco', 'Africa', 'Northern Africa', 'MA', 'Moroccan');
INSERT INTO "smisportal"."org_country" VALUES ('MOZ', 'Mozambique', 'Africa', 'Eastern Africa', 'MZ', 'Mozambican');
INSERT INTO "smisportal"."org_country" VALUES ('NAM', 'Namibia', 'Africa', 'Southern Africa', 'NA', 'Namibian');
INSERT INTO "smisportal"."org_country" VALUES ('NRU', 'Nauru', 'Oceania', 'Micronesia', 'NR', 'Nauruan');
INSERT INTO "smisportal"."org_country" VALUES ('NPL', 'Nepal', 'Asia', 'Southern and Central Asia', 'NP', 'Nepalese');
INSERT INTO "smisportal"."org_country" VALUES ('NLD', 'Netherlands', 'Europe', 'Western Europe', 'NL', 'Dutch');
INSERT INTO "smisportal"."org_country" VALUES ('NZL', 'New Zealand', 'Oceania', 'Australia and New Zealand', 'NZ', 'New Zealander');
INSERT INTO "smisportal"."org_country" VALUES ('NIC', 'Nicaragua', 'North America', 'Central America', 'NI', 'Nicaraguan');
INSERT INTO "smisportal"."org_country" VALUES ('NER', 'Niger', 'Africa', 'Western Africa', 'NE', 'Nigerien');
INSERT INTO "smisportal"."org_country" VALUES ('NGA', 'Nigeria', 'Africa', 'Western Africa', 'NG', 'Nigerian');
INSERT INTO "smisportal"."org_country" VALUES ('NOR', 'Norway', 'Europe', 'Nordic Countries', 'NO', 'Norwegian');
INSERT INTO "smisportal"."org_country" VALUES ('OMN', 'Oman', 'Asia', 'Middle East', 'OM', 'Omani');
INSERT INTO "smisportal"."org_country" VALUES ('PAK', 'Pakistan', 'Asia', 'Southern and Central Asia', 'PK', 'Pakistani');
INSERT INTO "smisportal"."org_country" VALUES ('PLW', 'Palau', 'Oceania', 'Micronesia', 'PW', 'Palauan');
INSERT INTO "smisportal"."org_country" VALUES ('PAN', 'Panama', 'North America', 'Central America', 'PA', 'Panamanian');
INSERT INTO "smisportal"."org_country" VALUES ('PNG', 'Papua New Guinea', 'Oceania', 'Melanesia', 'PG', 'Papua New Guinean');
INSERT INTO "smisportal"."org_country" VALUES ('PRY', 'Paraguay', 'South America', 'South America', 'PY', 'Paraguayan');
INSERT INTO "smisportal"."org_country" VALUES ('PER', 'Peru', 'South America', 'South America', 'PE', 'Peruvian');
INSERT INTO "smisportal"."org_country" VALUES ('PHL', 'Philippines', 'Asia', 'Southeast Asia', 'PH', 'Filipino');
INSERT INTO "smisportal"."org_country" VALUES ('POL', 'Poland', 'Europe', 'Eastern Europe', 'PL', 'Polish');
INSERT INTO "smisportal"."org_country" VALUES ('PRT', 'Portugal', 'Europe', 'Southern Europe', 'PT', 'Portuguese');
INSERT INTO "smisportal"."org_country" VALUES ('ROM', 'Romania', 'Europe', 'Eastern Europe', 'RO', 'Romanian');
INSERT INTO "smisportal"."org_country" VALUES ('RWA', 'Rwanda', 'Africa', 'Eastern Africa', 'RW', 'Rwandan');
INSERT INTO "smisportal"."org_country" VALUES ('KNA', 'Saint Kitts and Nevis', 'North America', 'Caribbean', 'KN', 'Kittian and Nevisian');
INSERT INTO "smisportal"."org_country" VALUES ('LCA', 'Saint Lucia', 'North America', 'Caribbean', 'LC', 'Saint Lucian');
INSERT INTO "smisportal"."org_country" VALUES ('WSM', 'Samoa', 'Oceania', 'Polynesia', 'WS', 'Samoan');
INSERT INTO "smisportal"."org_country" VALUES ('SMR', 'San Marino', 'Europe', 'Southern Europe', 'SM', 'Sammarinese');
INSERT INTO "smisportal"."org_country" VALUES ('STP', 'Sao Tome and Principe', 'Africa', 'Central Africa', 'ST', 'Sao Tomean');
INSERT INTO "smisportal"."org_country" VALUES ('SAU', 'Saudi Arabia', 'Asia', 'Middle East', 'SA', 'Saudi Arabian');
INSERT INTO "smisportal"."org_country" VALUES ('SEN', 'Senegal', 'Africa', 'Western Africa', 'SN', 'Senegalese');
INSERT INTO "smisportal"."org_country" VALUES ('SYC', 'Seychelles', 'Africa', 'Eastern Africa', 'SC', 'Seychellois');
INSERT INTO "smisportal"."org_country" VALUES ('SLE', 'Sierra Leone', 'Africa', 'Western Africa', 'SL', 'Sierra Leonean');
INSERT INTO "smisportal"."org_country" VALUES ('SGP', 'Singapore', 'Asia', 'Southeast Asia', 'SG', 'Singaporean');
INSERT INTO "smisportal"."org_country" VALUES ('SVK', 'Slovakia', 'Europe', 'Eastern Europe', 'SK', 'Slovak');
INSERT INTO "smisportal"."org_country" VALUES ('SVN', 'Slovenia', 'Europe', 'Southern Europe', 'SI', 'Slovene');
INSERT INTO "smisportal"."org_country" VALUES ('SLB', 'Solomon Islands', 'Oceania', 'Melanesia', 'SB', 'Solomon Islander');
INSERT INTO "smisportal"."org_country" VALUES ('SOM', 'Somalia', 'Africa', 'Eastern Africa', 'SO', 'Somali');
INSERT INTO "smisportal"."org_country" VALUES ('ZAF', 'South Africa', 'Africa', 'Southern Africa', 'ZA', 'South African');
INSERT INTO "smisportal"."org_country" VALUES ('ESP', 'Spain', 'Europe', 'Southern Europe', 'ES', 'Spanish');
INSERT INTO "smisportal"."org_country" VALUES ('LKA', 'Sri Lanka', 'Asia', 'Southern and Central Asia', 'LK', 'Sri Lankan');
INSERT INTO "smisportal"."org_country" VALUES ('SDN', 'Sudan', 'Africa', 'Northern Africa', 'SD', 'Sudanese');
INSERT INTO "smisportal"."org_country" VALUES ('SUR', 'Suriname', 'South America', 'South America', 'SR', 'Surinamer');
INSERT INTO "smisportal"."org_country" VALUES ('SWZ', 'Swaziland', 'Africa', 'Southern Africa', 'SZ', 'Swazi');
INSERT INTO "smisportal"."org_country" VALUES ('SWE', 'Sweden', 'Europe', 'Nordic Countries', 'SE', 'Swedish');
INSERT INTO "smisportal"."org_country" VALUES ('CHE', 'Switzerland', 'Europe', 'Western Europe', 'CH', 'Swiss');
INSERT INTO "smisportal"."org_country" VALUES ('SYR', 'Syria', 'Asia', 'Middle East', 'SY', 'Syrian');
INSERT INTO "smisportal"."org_country" VALUES ('TWN', 'Taiwan', 'Asia', 'Eastern Asia', 'TW', 'Taiwanese');
INSERT INTO "smisportal"."org_country" VALUES ('TJK', 'Tajikistan', 'Asia', 'Southern and Central Asia', 'TJ', 'Tadzhik');
INSERT INTO "smisportal"."org_country" VALUES ('TZA', 'Tanzania', 'Africa', 'Eastern Africa', 'TZ', 'Tanzanian');
INSERT INTO "smisportal"."org_country" VALUES ('THA', 'Thailand', 'Asia', 'Southeast Asia', 'TH', 'Thai');
INSERT INTO "smisportal"."org_country" VALUES ('TGO', 'Togo', 'Africa', 'Western Africa', 'TG', 'Togolese');
INSERT INTO "smisportal"."org_country" VALUES ('TON', 'Tonga', 'Oceania', 'Polynesia', 'TO', 'Tongan');
INSERT INTO "smisportal"."org_country" VALUES ('TTO', 'Trinidad and Tobago', 'North America', 'Caribbean', 'TT', 'Trinidadian');
INSERT INTO "smisportal"."org_country" VALUES ('TUN', 'Tunisia', 'Africa', 'Northern Africa', 'TN', 'Tunisian');
INSERT INTO "smisportal"."org_country" VALUES ('TUR', 'Turkey', 'Asia', 'Middle East', 'TR', 'Turkish');
INSERT INTO "smisportal"."org_country" VALUES ('TKM', 'Turkmenistan', 'Asia', 'Southern and Central Asia', 'TM', 'Turkmen');
INSERT INTO "smisportal"."org_country" VALUES ('TUV', 'Tuvalu', 'Oceania', 'Polynesia', 'TV', 'Tuvaluan');
INSERT INTO "smisportal"."org_country" VALUES ('UGA', 'Uganda', 'Africa', 'Eastern Africa', 'UG', 'Ugandan');
INSERT INTO "smisportal"."org_country" VALUES ('UKR', 'Ukraine', 'Europe', 'Eastern Europe', 'UA', 'Ukrainian');
INSERT INTO "smisportal"."org_country" VALUES ('ARE', 'United Arab Emirates', 'Asia', 'Middle East', 'AE', 'Emirian');
INSERT INTO "smisportal"."org_country" VALUES ('GBR', 'United Kingdom', 'Europe', 'British Islands', 'GB', 'British');
INSERT INTO "smisportal"."org_country" VALUES ('USA', 'United States', 'North America', 'North America', 'US', 'American');
INSERT INTO "smisportal"."org_country" VALUES ('URY', 'Uruguay', 'South America', 'South America', 'UY', 'Uruguayan');
INSERT INTO "smisportal"."org_country" VALUES ('UZB', 'Uzbekistan', 'Asia', 'Southern and Central Asia', 'UZ', 'Uzbekistani');
INSERT INTO "smisportal"."org_country" VALUES ('VUT', 'Vanuatu', 'Oceania', 'Melanesia', 'VU', 'Ni-Vanuatu');
INSERT INTO "smisportal"."org_country" VALUES ('VEN', 'Venezuela', 'South America', 'South America', 'VE', 'Venezuelan');
INSERT INTO "smisportal"."org_country" VALUES ('VNM', 'Vietnam', 'Asia', 'Southeast Asia', 'VN', 'Vietnamese');
INSERT INTO "smisportal"."org_country" VALUES ('YEM', 'Yemen', 'Asia', 'Middle East', 'YE', 'Yemeni');
INSERT INTO "smisportal"."org_country" VALUES ('ZMB', 'Zambia', 'Africa', 'Eastern Africa', 'ZM', 'Zambian');
INSERT INTO "smisportal"."org_country" VALUES ('ZWE', 'Zimbabwe', 'Africa', 'Eastern Africa', 'ZW', 'Zimbabwean');
INSERT INTO "smisportal"."org_country" VALUES ('ESH', 'Western Sahara', 'Africa', 'Northern Africa', 'EH', 'Sahrawi/Saharawi/Western Saharan');
INSERT INTO "smisportal"."org_country" VALUES ('CYM', 'Cayman Islands', 'North America', 'Caribbean', 'KY', 'Caymanian');

-- ----------------------------
-- Table structure for org_courses
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_courses";
CREATE TABLE "smisportal"."org_courses" (
  "course_id" int4 NOT NULL,
  "course_code" varchar(8) COLLATE "pg_catalog"."default" NOT NULL,
  "course_name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL,
  "level_of_study" int4 NOT NULL,
  "semester" int4 NOT NULL,
  "academic_hours" int4 NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying,
  "org_unit_id" int4 NOT NULL,
  "billing_factor" int4,
  "category_id" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_courses
-- ----------------------------

-- ----------------------------
-- Table structure for org_kuccps_prog_map
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_kuccps_prog_map";
CREATE TABLE "smisportal"."org_kuccps_prog_map" (
  "prog_map_id" int8 NOT NULL,
  "uon_prog_code" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "academic_year" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "kuccps_prog_code" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "cluster_id" int4,
  "capacity" int4,
  "cut_off_points" numeric(5,2),
  "admitted" int4,
  "reported" int4
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_kuccps_prog_map" IS 'This table keeps the kuccps programme code matching uon programme codes for purpose of uploading data in to admitted students table';

-- ----------------------------
-- Records of org_kuccps_prog_map
-- ----------------------------

-- ----------------------------
-- Table structure for org_prog_category
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_category";
CREATE TABLE "smisportal"."org_prog_category" (
  "prog_cat_id" int8 NOT NULL,
  "prog_cat_code" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "prog_cat_name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL,
  "prog_cat_order" int4 NOT NULL DEFAULT 0,
  "status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_prog_category" IS 'SCIENCE,SOCIAL SCIENCES, ARTS, MATHEMATICS, ENGINEERING';

-- ----------------------------
-- Records of org_prog_category
-- ----------------------------
INSERT INTO "smisportal"."org_prog_category" VALUES (26, 'SOC', 'SOCIAL SCIENCES', 0, 'ACTIVE');
INSERT INTO "smisportal"."org_prog_category" VALUES (226, 'MAT', 'MATHEMATICS', 0, 'ACTIVE');
INSERT INTO "smisportal"."org_prog_category" VALUES (326, 'ENG', 'ENGINEERING', 0, 'ACTIVE');
INSERT INTO "smisportal"."org_prog_category" VALUES (126, 'SCI', 'SCIENCE', 0, 'ACTIVE');

-- ----------------------------
-- Table structure for org_prog_curr_course
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_curr_course";
CREATE TABLE "smisportal"."org_prog_curr_course" (
  "prog_curriculum_course_id" int4 NOT NULL,
  "prog_curriculum_id" int4 NOT NULL,
  "course_id" int4 NOT NULL,
  "credit_factor" int4 NOT NULL DEFAULT 1,
  "credit_hours" numeric(38,0) NOT NULL,
  "level_of_study" int4 NOT NULL,
  "semester" int4,
  "prerequisite" int4,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying,
  "has_course_work" bool
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_prog_curr_course
-- ----------------------------

-- ----------------------------
-- Table structure for org_prog_curr_option
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_curr_option";
CREATE TABLE "smisportal"."org_prog_curr_option" (
  "option_id" int4 NOT NULL,
  "option_code" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "option_name" varchar(25) COLLATE "pg_catalog"."default" NOT NULL,
  "degree_id" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "option_desc" varchar(150) COLLATE "pg_catalog"."default",
  "option_status" varchar(12) COLLATE "pg_catalog"."default" NOT NULL,
  "progress_type" varchar(12) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_prog_curr_option" IS 'Programme curriculum options';

-- ----------------------------
-- Records of org_prog_curr_option
-- ----------------------------

-- ----------------------------
-- Table structure for org_prog_curr_option_courses
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_curr_option_courses";
CREATE TABLE "smisportal"."org_prog_curr_option_courses" (
  "option_course_id" int4 NOT NULL,
  "option_id" int4 NOT NULL,
  "course_id" int4 NOT NULL,
  "prog_cur_id" int4 NOT NULL,
  "course_type" varchar(15) COLLATE "pg_catalog"."default" NOT NULL,
  "degree_code" varchar(10) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_prog_curr_option_courses
-- ----------------------------

-- ----------------------------
-- Table structure for org_prog_curr_semester
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_curr_semester";
CREATE TABLE "smisportal"."org_prog_curr_semester" (
  "prog_curriculum_semester_id" int4 NOT NULL,
  "prog_curriculum_id" int4 NOT NULL,
  "acad_session_semester_id" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_prog_curr_semester
-- ----------------------------
INSERT INTO "smisportal"."org_prog_curr_semester" VALUES (21, 36, 1);

-- ----------------------------
-- Table structure for org_prog_curr_semester_group
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_curr_semester_group";
CREATE TABLE "smisportal"."org_prog_curr_semester_group" (
  "prog_curriculum_sem_group_id" int8 NOT NULL,
  "prog_curriculum_semester_id" int8 NOT NULL,
  "study_centre_group_id" int8 NOT NULL,
  "start_date" timestamp(6) NOT NULL,
  "end_date" timestamp(6),
  "registration_deadline" timestamp(6),
  "display_date" timestamp(6),
  "programme_level" int4 NOT NULL,
  "status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_prog_curr_semester_group
-- ----------------------------
INSERT INTO "smisportal"."org_prog_curr_semester_group" VALUES (1, 21, 1, '2023-01-02 00:00:00', '2023-04-07 00:00:00', '2022-11-23 00:00:00', '2022-11-24 00:00:00', 1, 'ACTIVE');

-- ----------------------------
-- Table structure for org_prog_curr_unit
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_curr_unit";
CREATE TABLE "smisportal"."org_prog_curr_unit" (
  "prog_curriculum_unit_id" int8 NOT NULL,
  "org_unit_history_id" int8 NOT NULL,
  "prog_curriculum_id" int8 NOT NULL,
  "start_date" date NOT NULL,
  "end_date" date,
  "status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_prog_curr_unit" IS 'Which organization unit own a programme curriculum';

-- ----------------------------
-- Records of org_prog_curr_unit
-- ----------------------------

-- ----------------------------
-- Table structure for org_prog_level
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_level";
CREATE TABLE "smisportal"."org_prog_level" (
  "programme_level_id" int4 NOT NULL,
  "programme_level_name" varchar(30) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_prog_level" IS 'Programme levels such as 1, first yearr';

-- ----------------------------
-- Records of org_prog_level
-- ----------------------------
INSERT INTO "smisportal"."org_prog_level" VALUES (1, 'Level 1');

-- ----------------------------
-- Table structure for org_prog_type
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_prog_type";
CREATE TABLE "smisportal"."org_prog_type" (
  "prog_type_id" int8 NOT NULL,
  "prog_type_code" varchar(15) COLLATE "pg_catalog"."default" NOT NULL,
  "prog_type_name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL,
  "prog_type_order" int4 NOT NULL,
  "status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_prog_type" IS 'CERTIFICATE, DIPLOMA,BACHELORS,MASTERS,FELLOWSHIP,PhD';

-- ----------------------------
-- Records of org_prog_type
-- ----------------------------
INSERT INTO "smisportal"."org_prog_type" VALUES (31, 'M', 'MASTERS', 4, 'ACTIVE');
INSERT INTO "smisportal"."org_prog_type" VALUES (231, 'DP', 'DIPLOMA', 2, 'ACTIVE');
INSERT INTO "smisportal"."org_prog_type" VALUES (631, 'PHD', 'PhD', 5, 'ACTIVE');
INSERT INTO "smisportal"."org_prog_type" VALUES (431, 'DG', 'DEGREE', 3, 'ACTIVE');

-- ----------------------------
-- Table structure for org_programme_curriculum
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_programme_curriculum";
CREATE TABLE "smisportal"."org_programme_curriculum" (
  "prog_curriculum_id" int8 NOT NULL,
  "prog_id" int8 NOT NULL,
  "prog_curriculum_desc" varchar(300) COLLATE "pg_catalog"."default" NOT NULL,
  "duration" int2 NOT NULL,
  "pass_mark" int2 NOT NULL,
  "annual_semesters" int2 NOT NULL DEFAULT 2,
  "max_units_per_semester" int2,
  "average_type" varchar(10) COLLATE "pg_catalog"."default",
  "award_rounding" varchar(20) COLLATE "pg_catalog"."default" DEFAULT 'TRUNCATE'::character varying,
  "start_date" date NOT NULL,
  "end_date" date,
  "prog_cur_prefix" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "date_created" date NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "grading_system_id" int8 NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying,
  "approval_date" date
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON COLUMN "smisportal"."org_programme_curriculum"."duration" IS 'ACADEMIC SESSIONS';
COMMENT ON COLUMN "smisportal"."org_programme_curriculum"."award_rounding" IS 'ROUNDOFF, TRUNCATE';
COMMENT ON COLUMN "smisportal"."org_programme_curriculum"."prog_cur_prefix" IS 'Programme curriculum prefix';

-- ----------------------------
-- Records of org_programme_curriculum
-- ----------------------------
INSERT INTO "smisportal"."org_programme_curriculum" VALUES (16, 36, 'Ecommerce Degree', 4, 45, 2, 7, '', 'TRUNCATE', '2022-11-01', NULL, 'BCOM', '2022-11-02', 6, 'ACTIVE', '2022-11-17');
INSERT INTO "smisportal"."org_programme_curriculum" VALUES (36, 16, 'Computer Science', 5, 70, 2, 7, '', 'TRUNCATE', '2022-11-02', '2022-11-26', 'COMP', '2022-11-09', 1, 'ACTIVE', '2022-11-01');

-- ----------------------------
-- Table structure for org_programmes
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_programmes";
CREATE TABLE "smisportal"."org_programmes" (
  "prog_id" int8 NOT NULL,
  "prog_code" varchar(6) COLLATE "pg_catalog"."default" NOT NULL,
  "prog_short_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "prog_full_name" varchar(200) COLLATE "pg_catalog"."default" NOT NULL,
  "prog_type_id" int8 NOT NULL,
  "prog_cat_id" int8 NOT NULL,
  "status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_programmes
-- ----------------------------
INSERT INTO "smisportal"."org_programmes" VALUES (16, 'P15', 'Comp Sci', 'Bachelor of Science Computer Science', 431, 126, 'ACTIVE');
INSERT INTO "smisportal"."org_programmes" VALUES (36, 'A24', 'BCom', 'Bachelor of Commerce ', 431, 26, 'ACTIVE');

-- ----------------------------
-- Table structure for org_semester_code
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_semester_code";
CREATE TABLE "smisportal"."org_semester_code" (
  "semester_code" int4 NOT NULL,
  "semster_name" varchar(30) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_semester_code
-- ----------------------------
INSERT INTO "smisportal"."org_semester_code" VALUES (3, 'Third Semester');
INSERT INTO "smisportal"."org_semester_code" VALUES (4, 'Fourth Semester');
INSERT INTO "smisportal"."org_semester_code" VALUES (1, 'First Semester');
INSERT INTO "smisportal"."org_semester_code" VALUES (2, 'Second Semester');
INSERT INTO "smisportal"."org_semester_code" VALUES (5, 'Fifth Semester');

-- ----------------------------
-- Table structure for org_semester_type
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_semester_type";
CREATE TABLE "smisportal"."org_semester_type" (
  "sem_type_id" int4 NOT NULL,
  "sem_type" varchar(15) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Records of org_semester_type
-- ----------------------------
INSERT INTO "smisportal"."org_semester_type" VALUES (1, 'TEACHING');

-- ----------------------------
-- Table structure for org_service
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_service";
CREATE TABLE "smisportal"."org_service" (
  "service_id" int8 NOT NULL,
  "service_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "country_code" varchar(5) COLLATE "pg_catalog"."default",
  "status" bool
)
;

-- ----------------------------
-- Records of org_service
-- ----------------------------

-- ----------------------------
-- Table structure for org_sponsor
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_sponsor";
CREATE TABLE "smisportal"."org_sponsor" (
  "sponsor_id" int8 NOT NULL,
  "sponsor_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "country_code" varchar(5) COLLATE "pg_catalog"."default",
  "status" bool
)
;

-- ----------------------------
-- Records of org_sponsor
-- ----------------------------
INSERT INTO "smisportal"."org_sponsor" VALUES (1, 'Ministry of defence', 'KEN', 't');

-- ----------------------------
-- Table structure for org_study_centre
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_study_centre";
CREATE TABLE "smisportal"."org_study_centre" (
  "study_centre_id" int8 NOT NULL,
  "study_centre_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_study_centre" IS 'Nairobi, Mombasa, Kisumu, Kenya Science etc';

-- ----------------------------
-- Records of org_study_centre
-- ----------------------------
INSERT INTO "smisportal"."org_study_centre" VALUES (405, 'KMA', 'ACTIVE');
INSERT INTO "smisportal"."org_study_centre" VALUES (105, 'IPSTC', 'ACTIVE');
INSERT INTO "smisportal"."org_study_centre" VALUES (205, 'DeFTeC', 'ACTIVE');
INSERT INTO "smisportal"."org_study_centre" VALUES (5, 'NIRUC', 'ACTIVE');
INSERT INTO "smisportal"."org_study_centre" VALUES (305, 'CSSS', 'ACTIVE');

-- ----------------------------
-- Table structure for org_study_centre_group
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_study_centre_group";
CREATE TABLE "smisportal"."org_study_centre_group" (
  "study_centre_group_id" int8 NOT NULL,
  "study_centre_id" int8 NOT NULL,
  "study_group_id" int8 NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_study_centre_group" IS 'References Study Centre and study group e.g Nairobi Regular, Nairobi Day , Kisumu Evening etc';

-- ----------------------------
-- Records of org_study_centre_group
-- ----------------------------
INSERT INTO "smisportal"."org_study_centre_group" VALUES (1, 105, 5, 'ACTIVE');
INSERT INTO "smisportal"."org_study_centre_group" VALUES (21, 305, 105, 'ACTIVE');
INSERT INTO "smisportal"."org_study_centre_group" VALUES (41, 5, 105, 'ACTIVE');

-- ----------------------------
-- Table structure for org_study_group
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_study_group";
CREATE TABLE "smisportal"."org_study_group" (
  "study_group_id" int8 NOT NULL,
  "study_group_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_study_group" IS 'Student groups eg Regular/Integrated, Evening, Day, Outreach,Blended etc';

-- ----------------------------
-- Records of org_study_group
-- ----------------------------
INSERT INTO "smisportal"."org_study_group" VALUES (5, 'REGULAR/INTEGRATED', 'ACTIVE');
INSERT INTO "smisportal"."org_study_group" VALUES (105, 'EVENING', 'ACTIVE');

-- ----------------------------
-- Table structure for org_unit
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_unit";
CREATE TABLE "smisportal"."org_unit" (
  "unit_id" int8 NOT NULL,
  "unit_code" varchar(6) COLLATE "pg_catalog"."default" NOT NULL,
  "unit_name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_unit" IS 'Organization units such as Faculties, Schools, Departments, sections';

-- ----------------------------
-- Records of org_unit
-- ----------------------------
INSERT INTO "smisportal"."org_unit" VALUES (2, 'CS', 'Joint Command and Staff College');
INSERT INTO "smisportal"."org_unit" VALUES (4, 'PS', 'International Peace Support Training College');
INSERT INTO "smisportal"."org_unit" VALUES (5, 'DE', 'Defence Forces Technical College');
INSERT INTO "smisportal"."org_unit" VALUES (6, 'DC', 'Defence College of Health Sciences');
INSERT INTO "smisportal"."org_unit" VALUES (7, 'NR', 'National Intelligence Research University College');
INSERT INTO "smisportal"."org_unit" VALUES (8, 'NP', 'National Police University College');
INSERT INTO "smisportal"."org_unit" VALUES (9, 'NDU-K', 'National Defense University of Kenya');
INSERT INTO "smisportal"."org_unit" VALUES (1, 'ND', 'National Defence College');
INSERT INTO "smisportal"."org_unit" VALUES (3, 'KMA', 'Kenya Military Academy');

-- ----------------------------
-- Table structure for org_unit_course
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_unit_course";
CREATE TABLE "smisportal"."org_unit_course" (
  "org_unit_course_id" int4 NOT NULL,
  "course_id" int4 NOT NULL,
  "org_unit_id" int4 NOT NULL,
  "org_teaching_id" int4 NOT NULL,
  "start_date" date NOT NULL,
  "end_date" date,
  "user_id" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of org_unit_course
-- ----------------------------

-- ----------------------------
-- Table structure for org_unit_head
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_unit_head";
CREATE TABLE "smisportal"."org_unit_head" (
  "unit_head_id" int8 NOT NULL,
  "unit_head_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON COLUMN "smisportal"."org_unit_head"."unit_head_name" IS 'PRINCIPAL,DIRECTOR,CHAIRMAN';

-- ----------------------------
-- Records of org_unit_head
-- ----------------------------
INSERT INTO "smisportal"."org_unit_head" VALUES (2, 'Deputy Vice Chancellor', 'ACTIVE');
INSERT INTO "smisportal"."org_unit_head" VALUES (3, 'Commandant', 'ACTIVE');
INSERT INTO "smisportal"."org_unit_head" VALUES (4, 'Dean', 'ACTIVE');
INSERT INTO "smisportal"."org_unit_head" VALUES (1, 'Vice Chancellor', 'ACTIVE');
INSERT INTO "smisportal"."org_unit_head" VALUES (24, 'Chairman', 'ACTIVE');

-- ----------------------------
-- Table structure for org_unit_history
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_unit_history";
CREATE TABLE "smisportal"."org_unit_history" (
  "org_unit_history_id" int8 NOT NULL,
  "org_unit_id" int8 NOT NULL,
  "org_type_id" int8 NOT NULL,
  "parent_id" int8,
  "successor_id" int8,
  "unit_head_id" int8,
  "unit_head_user_id" int8,
  "start_date" date NOT NULL,
  "end_date" date,
  "user_id" int8,
  "date_created" timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_unit_history" IS 'Mutations of an organization';

-- ----------------------------
-- Records of org_unit_history
-- ----------------------------
INSERT INTO "smisportal"."org_unit_history" VALUES (2, 2, 2, 9, NULL, 3, NULL, '2022-07-16', NULL, NULL, '2022-07-17 22:49:21.157982');
INSERT INTO "smisportal"."org_unit_history" VALUES (9, 8, 2, 9, NULL, 3, NULL, '2022-07-16', NULL, NULL, '2022-07-17 22:53:26.575394');
INSERT INTO "smisportal"."org_unit_history" VALUES (3, 1, 2, 9, NULL, 3, NULL, '2022-07-16', NULL, NULL, '2022-07-17 22:54:42.735134');
INSERT INTO "smisportal"."org_unit_history" VALUES (4, 3, 2, 9, NULL, 3, NULL, '2022-07-16', NULL, NULL, '2022-07-17 22:54:42.764167');
INSERT INTO "smisportal"."org_unit_history" VALUES (5, 4, 2, 9, NULL, 3, NULL, '2022-07-16', NULL, NULL, '2022-07-17 22:54:42.794649');
INSERT INTO "smisportal"."org_unit_history" VALUES (6, 5, 2, 9, NULL, 3, NULL, '2022-07-16', NULL, NULL, '2022-07-17 22:54:42.911589');
INSERT INTO "smisportal"."org_unit_history" VALUES (7, 6, 2, 9, NULL, 3, NULL, '2022-07-16', NULL, NULL, '2022-07-17 22:54:42.965766');
INSERT INTO "smisportal"."org_unit_history" VALUES (8, 7, 2, 9, NULL, 3, NULL, '2022-07-16', NULL, NULL, '2022-07-17 22:54:42.983476');
INSERT INTO "smisportal"."org_unit_history" VALUES (1, 9, 1, NULL, NULL, 1, NULL, '2022-07-01', NULL, NULL, '2022-07-16 13:23:10.248842');

-- ----------------------------
-- Table structure for org_unit_types
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."org_unit_types";
CREATE TABLE "smisportal"."org_unit_types" (
  "unit_type_id" int8 NOT NULL,
  "unit_type_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."org_unit_types" IS 'University, college, faculty, department';

-- ----------------------------
-- Records of org_unit_types
-- ----------------------------
INSERT INTO "smisportal"."org_unit_types" VALUES (1, 'UNIVERSITY', 'ACTIVE');
INSERT INTO "smisportal"."org_unit_types" VALUES (2, 'COLLEGE', 'ACTIVE');

-- ----------------------------
-- Table structure for sm_academic_progress
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_academic_progress";
CREATE TABLE "smisportal"."sm_academic_progress" (
  "academic_progress_id" int4 NOT NULL,
  "acad_session_id" int4 NOT NULL,
  "academic_level_id" int4 NOT NULL,
  "student_prog_curriculum_id" int4 NOT NULL,
  "progress_status_id" int4 NOT NULL,
  "current_status" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON COLUMN "smisportal"."sm_academic_progress"."student_prog_curriculum_id" IS 'how_the_student_acquired_the_status';
COMMENT ON TABLE "smisportal"."sm_academic_progress" IS 'For storing student academic progress in every academic session';

-- ----------------------------
-- Records of sm_academic_progress
-- ----------------------------
INSERT INTO "smisportal"."sm_academic_progress" VALUES (7, 121, 1, 240, 2, 1);

-- ----------------------------
-- Table structure for sm_academic_progress_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_academic_progress_status";
CREATE TABLE "smisportal"."sm_academic_progress_status" (
  "progress_status_id" int4 NOT NULL,
  "progress_status_name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_academic_progress_status" IS 'PROMOTED, REPEATED, PRE-REGISTRATION, RE-ADMITTED, RESUMPTION FROM DEFERRMENT, RESUMPTION FROM SUSPENSION, AMNESTY FROM THE VC';

-- ----------------------------
-- Records of sm_academic_progress_status
-- ----------------------------
INSERT INTO "smisportal"."sm_academic_progress_status" VALUES (1, 'PROMOTED');
INSERT INTO "smisportal"."sm_academic_progress_status" VALUES (2, 'REGISTERED');

-- ----------------------------
-- Table structure for sm_admissions_approval
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_admissions_approval";
CREATE TABLE "smisportal"."sm_admissions_approval" (
  "approval_comment" varchar COLLATE "pg_catalog"."default",
  "adm_approval_id" int4 NOT NULL,
  "approver" varchar COLLATE "pg_catalog"."default" NOT NULL,
  "approval_date" date NOT NULL,
  "intake_id" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_admissions_approval" IS 'admissions approval done on uploaded admissions';

-- ----------------------------
-- Records of sm_admissions_approval
-- ----------------------------

-- ----------------------------
-- Table structure for sm_admitted_student
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_admitted_student";
CREATE TABLE "smisportal"."sm_admitted_student" (
  "adm_refno" int4 NOT NULL,
  "kcse_index_no" varchar(20) COLLATE "pg_catalog"."default",
  "kcse_year" varchar(10) COLLATE "pg_catalog"."default",
  "primary_phone_no" varchar(20) COLLATE "pg_catalog"."default",
  "alternative_phone_no" varchar(20) COLLATE "pg_catalog"."default",
  "primary_email" varchar(50) COLLATE "pg_catalog"."default",
  "alternative_email" varchar(50) COLLATE "pg_catalog"."default",
  "post_code" varchar(20) COLLATE "pg_catalog"."default",
  "post_address" varchar(20) COLLATE "pg_catalog"."default",
  "town" varchar(30) COLLATE "pg_catalog"."default",
  "kuccps_prog_code" varchar(20) COLLATE "pg_catalog"."default",
  "uon_prog_code" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "national_id" varchar(20) COLLATE "pg_catalog"."default",
  "birth_cert_no" varchar(20) COLLATE "pg_catalog"."default",
  "source_id" int4 NOT NULL,
  "passport_no" varchar(20) COLLATE "pg_catalog"."default",
  "admission_status" varchar(30) COLLATE "pg_catalog"."default",
  "application_refno" int8,
  "intake_code" int4 NOT NULL,
  "student_category_id" int4 NOT NULL,
  "password" varchar(255) COLLATE "pg_catalog"."default",
  "doc_submission_status" bool,
  "primary_email_salt" varchar(255) COLLATE "pg_catalog"."default",
  "secondary_email_salt" varchar(255) COLLATE "pg_catalog"."default",
  "primary_email_verified_date" date,
  "secondary_email_verified_date" date,
  "surname" varchar(50) COLLATE "pg_catalog"."default",
  "other_names" varchar(150) COLLATE "pg_catalog"."default",
  "clearance_status" varchar(30) COLLATE "pg_catalog"."default",
  "password_changed_date" date,
  "service" varchar(20) COLLATE "pg_catalog"."default",
  "document_sync_status" bool,
  "service_number" varchar(30) COLLATE "pg_catalog"."default",
  "nationality" varchar(50) COLLATE "pg_catalog"."default",
  "date_of_birth" date,
  "profile_sync_status" bool,
  "sponsor" int2,
  "blood_group" varchar(5) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON COLUMN "smisportal"."sm_admitted_student"."admission_status" IS 'to take care of a case where an admission is revoked or recalled for the sake of module II';
COMMENT ON COLUMN "smisportal"."sm_admitted_student"."application_refno" IS 'to link to applicant incase a report of admitted student is required';
COMMENT ON COLUMN "smisportal"."sm_admitted_student"."clearance_status" IS 'Indicates clearance status of a student. PENDING, CLEARED, NOT CLEARED';
COMMENT ON TABLE "smisportal"."sm_admitted_student" IS 'this table holds students from kuccps intake or module applicants. it is used by students when doing self registration in to the system';

-- ----------------------------
-- Records of sm_admitted_student
-- ----------------------------
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6003, '01100003001', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '$2y$13$Jxic/pY5JZzYC/7GwB/GR.ezwd9QhPNRFDW3F4pwk/bPIzAmLyXJO', NULL, NULL, NULL, NULL, NULL, 'MWAURA', 'PAUL NYAGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6004, '01100003002', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6004', NULL, NULL, NULL, NULL, NULL, 'JAGERO', 'DONALD OMONDI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6005, '01100003003', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6005', NULL, NULL, NULL, NULL, NULL, 'KAMAU', 'BRYAN MUTUKU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6006, '01100003003', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6006', NULL, NULL, NULL, NULL, NULL, 'KIPNGETICH', 'BRIAN ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6007, '01100003003', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6007', NULL, NULL, NULL, NULL, NULL, 'OLOO', 'TONY OMONDI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6008, '01100003004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6008', NULL, NULL, NULL, NULL, NULL, 'MAINA', 'JACKSON MWANGI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6009, '01100003004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6009', NULL, NULL, NULL, NULL, NULL, 'LAGAT', 'BENARD KIPNGENO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6010, '01100003005', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6010', NULL, NULL, NULL, NULL, NULL, 'KIBUNJA', 'GLADYS WANJIKU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6011, '01100003006', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6011', NULL, NULL, NULL, NULL, NULL, 'KHISA', 'FRESNEL BARASA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6012, '01100003006', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6012', NULL, NULL, NULL, NULL, NULL, 'TOME', 'JOSPHAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6013, '01100003008', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6013', NULL, NULL, NULL, NULL, NULL, 'NGA''NGA''A', 'PETER MACHARIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6014, '01100003012', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6014', NULL, NULL, NULL, NULL, NULL, 'OTIENO', 'ROLEX OTIENO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6015, '01100003012', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6015', NULL, NULL, NULL, NULL, NULL, 'MAINA', 'BRIAN KARIUKI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6016, '01100003013', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6016', NULL, NULL, NULL, NULL, NULL, 'KIMANI', 'DAVID NJUNGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6018, '01100003018', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6018', NULL, NULL, NULL, NULL, NULL, 'NDUNGU', 'JUSTIN KURIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6019, '01100003018', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6019', NULL, NULL, NULL, NULL, NULL, 'LIONELL', 'LEMERIAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6020, '01100003019', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6020', NULL, NULL, NULL, NULL, NULL, 'WANJALA', 'PHASILA NAFULA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6021, '01100003020', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6021', NULL, NULL, NULL, NULL, NULL, 'NDIRANGU', 'BENSON KAHIGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6022, '01100003022', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6022', NULL, NULL, NULL, NULL, NULL, 'NJOKI', 'KELVIN MBURU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6023, '01100003024', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6023', NULL, NULL, NULL, NULL, NULL, 'ANDERSON', 'EMMACULATE REHEMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6024, '01100003025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6024', NULL, NULL, NULL, NULL, NULL, 'GABOW', 'FEISAL BISHAR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6025, '01100003025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6025', NULL, NULL, NULL, NULL, NULL, 'WANJIRU', 'PAUL THUKU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6026, '01100003025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6026', NULL, NULL, NULL, NULL, NULL, 'JOEL', 'IAN MIENCHA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6029, '01100003029', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6029', NULL, NULL, NULL, NULL, NULL, 'IBRAHIM', 'YAHYA ALI IBRAHIM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6030, '01100003032', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6030', NULL, NULL, NULL, NULL, NULL, 'NJIRUIBRAHIM', 'BREDA MUMANTHI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6031, '01100003033', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6031', NULL, NULL, NULL, NULL, NULL, 'KIPNGENO', 'ANDERSON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6032, '01100003033', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6032', NULL, NULL, NULL, NULL, NULL, 'ODEMBE', 'GORETY ACHIENG ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6033, '01100003036', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6033', NULL, NULL, NULL, NULL, NULL, 'NJAGI', 'DENIS MURIMI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6034, '01100003036', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6034', NULL, NULL, NULL, NULL, NULL, 'MATI', 'KAMENE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6035, '01100003039', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6035', NULL, NULL, NULL, NULL, NULL, 'MUTURI', 'JOHN NJENGAH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6037, '01100003043', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6037', NULL, NULL, NULL, NULL, NULL, 'KAMAU', 'FRANCIS MUGENDI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6038, '01100003044', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6038', NULL, NULL, NULL, NULL, NULL, 'MBALUKA', 'WINFRED WAENI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6039, '01100003045', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6039', NULL, NULL, NULL, NULL, NULL, 'KIRUI', 'VICTOR KIPROTICH ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6041, '01100003047', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6041', NULL, NULL, NULL, NULL, NULL, 'NYAGAKA', 'CLINTON OIGORO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6042, '01100003047', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6042', NULL, NULL, NULL, NULL, NULL, 'KINUTHIA', 'DENNIS NJOROGE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6043, '01100003047', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6043', NULL, NULL, NULL, NULL, NULL, 'MIDIKO', 'AMBUKA ASTONE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6044, '01100003047', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6044', NULL, NULL, NULL, NULL, NULL, 'LAGAT', 'REINHARD KIPKOECH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6045, '01100003048', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6045', NULL, NULL, NULL, NULL, NULL, 'KIPKIRUI', 'CALEB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6047, '01100003054', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6047', NULL, NULL, NULL, NULL, NULL, 'BOCHA', 'HARITH ABDI ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6048, '01100003055', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6048', NULL, NULL, NULL, NULL, NULL, 'MUTUGI', 'KELVIN MBURU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6049, '01100003055', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6049', NULL, NULL, NULL, NULL, NULL, 'SIMON', 'GRACE NASIEKU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6050, '01100003056', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6050', NULL, NULL, NULL, NULL, NULL, 'KIBET', 'OLIVER KIPCHUMBA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6051, '01100003056', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6051', NULL, NULL, NULL, NULL, NULL, 'OMAR', 'ISSA ABDI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6052, '01100003058', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6052', NULL, NULL, NULL, NULL, NULL, 'CHERUIYOT', 'AMOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6054, '01100003060', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6054', NULL, NULL, NULL, NULL, NULL, 'TWAGIRAEZU', 'ELYSE ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6055, '01100003064', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6055', NULL, NULL, NULL, NULL, NULL, 'IAN', 'MUGISHA MZIZA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6057, '01100003072', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6057', NULL, NULL, NULL, NULL, NULL, 'PAUL', 'KASULE JOVAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6058, '01100003079', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6058', NULL, NULL, NULL, NULL, NULL, 'RONALD', 'WASWA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6059, '01100003079', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6059', NULL, NULL, NULL, NULL, NULL, 'MACHAWA', 'MOISES FERNANDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6060, '01100003079', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6060', NULL, NULL, NULL, NULL, NULL, 'GUACHA', 'MANUEL CHEFE ALERTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6061, '01100003082', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6061', NULL, NULL, NULL, NULL, NULL, 'VENTURA', 'HISIDIN JOAQUIM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6062, '01100003084', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6062', NULL, NULL, NULL, NULL, NULL, 'SURANE', 'LICINIA AUGUSTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6064, '01100003087', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6064', NULL, NULL, NULL, NULL, NULL, 'JEREDI', 'PASCAL ERIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6065, '01100003091', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6065', NULL, NULL, NULL, NULL, NULL, 'WARIOBA', 'HAMISI ISSA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6067, '01100003103', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6067', NULL, NULL, NULL, NULL, NULL, 'MBUGUA', 'DAVIES KAMAU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6068, '01100003114', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6068', NULL, NULL, NULL, NULL, NULL, 'KANYOTU', 'FRANCIS BUNDI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6069, '01100003117', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6069', NULL, NULL, NULL, NULL, NULL, 'CHERUIYOT', 'HEZRA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6070, '01100003119', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6070', NULL, NULL, NULL, NULL, NULL, 'WANYONYI', 'ENOCK WANJALA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6071, '01100003126', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6071', NULL, NULL, NULL, NULL, NULL, 'OPIYO', 'DENIS KOLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6072, '01100003126', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6072', NULL, NULL, NULL, NULL, NULL, 'MUEMA', 'DENIS KIOKO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6073, '01100003130', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6073', NULL, NULL, NULL, NULL, NULL, 'MUHOHO', 'NIMROD MUNGAI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6077, '01100003168', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6077', NULL, NULL, NULL, NULL, NULL, 'MUCHIRI', 'GEOFREY MWANGI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6079, '01100003203', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6079', NULL, NULL, NULL, NULL, NULL, 'WACHIRA', 'JOHN MWANGI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6080, '01100003204', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6080', NULL, NULL, NULL, NULL, NULL, 'MUCHERU', 'ALFRED KABIRU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6081, '01100003206', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6081', NULL, NULL, NULL, NULL, NULL, 'ODOUR', 'EVANS ODHIAMBO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6082, '01100003208', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6082', NULL, NULL, NULL, NULL, NULL, 'ONDITI', 'FESTUS OUDIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6083, '01100004001', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6083', NULL, NULL, NULL, NULL, NULL, 'OJAKAA', 'TIMOTHY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6084, '01100004002', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6084', NULL, NULL, NULL, NULL, NULL, 'KANYUA', 'KEN KIMEMIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6086, '01100004002', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6086', NULL, NULL, NULL, NULL, NULL, 'NDUGULI', 'MORRIS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6087, '01100004003', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6087', NULL, NULL, NULL, NULL, NULL, 'MOHAMED', 'ISSACK HASSAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6088, '01100004003', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6088', NULL, NULL, NULL, NULL, NULL, 'KIRIA', 'KENNEDY KINGARA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6089, '01100004004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6089', NULL, NULL, NULL, NULL, NULL, 'EFEDHA', 'ALFRED KABIRU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6090, '01100004004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6090', NULL, NULL, NULL, NULL, NULL, 'KIRUI', 'FESTUS KIPCHIRCHIR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6091, '01100004004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6091', NULL, NULL, NULL, NULL, NULL, 'KIPLETING', 'PASCALIS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6093, '01100004005', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6093', NULL, NULL, NULL, NULL, NULL, 'OMORI', 'MORANGA IAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6094, '01100004006', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6094', NULL, NULL, NULL, NULL, NULL, 'KORIR', 'BRIAN KIPRUTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6095, '01100004014', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6095', NULL, NULL, NULL, NULL, NULL, 'SECHERE', 'JAMAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6097, '01100004016', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6097', NULL, NULL, NULL, NULL, NULL, 'MBURU', 'CLIFFORD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6098, '01100004021', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6098', NULL, NULL, NULL, NULL, NULL, 'MUTISYA', 'DANNIEL KATUA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6099, '01100004021', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6099', NULL, NULL, NULL, NULL, NULL, 'CHESEREK', 'JONAH KIBET', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6100, '01100004021', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6100', NULL, NULL, NULL, NULL, NULL, 'NJOROGE', 'LEON CHEGE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6101, '01100004022', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6101', NULL, NULL, NULL, NULL, NULL, 'KIPNGENO', 'VICTOR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6102, '01100004022', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6102', NULL, NULL, NULL, NULL, NULL, 'NYAGA', 'DENIS MUTHOMI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6103, '01100004022', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6103', NULL, NULL, NULL, NULL, NULL, 'MUSEE', 'DOMINIC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6105, '01100004025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6105', NULL, NULL, NULL, NULL, NULL, 'SIFUNA', 'JOSEPHAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6107, '01100004025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6107', NULL, NULL, NULL, NULL, NULL, 'RASHID', 'MOHAMED AMIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6108, '01100004029', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6108', NULL, NULL, NULL, NULL, NULL, 'AKWAM', 'MERCY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6109, '01100004041', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6109', NULL, NULL, NULL, NULL, NULL, 'GODE', 'WESLEY PAUL OBUYA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6110, '01100004042', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6110', NULL, NULL, NULL, NULL, NULL, 'MBUGUA', 'JANE WANJIRU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6111, '01100004045', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6111', NULL, NULL, NULL, NULL, NULL, 'MUINDI', 'PATRICK MWENDWA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6112, '01100004045', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6112', NULL, NULL, NULL, NULL, NULL, 'KIRUI', 'EMMANUEL KIPCHIRCHIR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6113, '01100004046', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6113', NULL, NULL, NULL, NULL, NULL, 'JUMA', 'DANIEL MUKABI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6114, '01100004047', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6114', NULL, NULL, NULL, NULL, NULL, 'MRABU', 'MARTIN  RAI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6115, '01100004050', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6115', NULL, NULL, NULL, NULL, NULL, 'MALIMU', 'LAUGRACIOURS ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6118, '01100004059', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6118', NULL, NULL, NULL, NULL, NULL, 'MBOYA', 'SYNTHIA AWUOR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6119, '01100004065', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6119', NULL, NULL, NULL, NULL, NULL, 'KIGEN', 'IAN KIPKOECH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6120, '01100004067', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6120', NULL, NULL, NULL, NULL, NULL, 'MWANGI', 'CATHERINE NYAMBURA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6121, '01100004072', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6121', NULL, NULL, NULL, NULL, NULL, 'JARSON', 'YUSUF MOHAMED', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6122, '01100004097', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6122', NULL, NULL, NULL, NULL, NULL, 'MWANIKI', 'WILSON MUTUGI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6123, '01100004097', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6123', NULL, NULL, NULL, NULL, NULL, 'CHISHENGA', 'VINCENT ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6124, '01100004111', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6124', NULL, NULL, NULL, NULL, NULL, 'GITAU', 'EDWARD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6126, '01100004128', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6126', NULL, NULL, NULL, NULL, NULL, 'MUNGANIA', 'ROBERT MUTEMBEI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6127, '01100004129', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6127', NULL, NULL, NULL, NULL, NULL, 'IMENYI', 'LOISE KATHURE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6128, '01100004136', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6128', NULL, NULL, NULL, NULL, NULL, 'SHEMAKA', 'ABDULAZIZ ABDU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6130, '01100004142', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6130', NULL, NULL, NULL, NULL, NULL, 'DANIEL', 'KELAI ESOKON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6131, '01100004156', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6131', NULL, NULL, NULL, NULL, NULL, 'KIRETAI', 'LAWRENCE KAGUMBA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6132, '01100004179', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6132', NULL, NULL, NULL, NULL, NULL, 'KARIUKI', 'ISAAC NGURI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6133, '01101101001', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6133', NULL, NULL, NULL, NULL, NULL, 'NYALAMIA', 'JONAH KATEI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6134, '01101101002', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6134', NULL, NULL, NULL, NULL, NULL, 'LIHANDA', 'VICTOR ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6136, '01101101003', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6136', NULL, NULL, NULL, NULL, NULL, 'GALGALLO', 'KUSHE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6137, '01101101004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6137', NULL, NULL, NULL, NULL, NULL, 'HADULO', 'KEVIN OSIR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6138, '01101101004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6138', NULL, NULL, NULL, NULL, NULL, 'MOSAREMO', 'ONESMUS OMBATI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6139, '01101101004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6139', NULL, NULL, NULL, NULL, NULL, 'TUITOEK', 'DICKSON KIPKOECH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6141, '01101101006', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6141', NULL, NULL, NULL, NULL, NULL, 'KIPLAGAT', 'ERICK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6142, '01101101006', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6142', NULL, NULL, NULL, NULL, NULL, 'GODANA', 'ABDULLAHI ABECHO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6143, '01101101008', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6143', NULL, NULL, NULL, NULL, NULL, 'LENAPEER', 'PATRICK LTORIAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6145, '01101101012', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6145', NULL, NULL, NULL, NULL, NULL, 'MWANYENGELA', 'DAVID NYAMBU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6146, '01101101015', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6146', NULL, NULL, NULL, NULL, NULL, 'KARARI', 'TRACY MARGARET WANGARI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6147, '01101101017', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6147', NULL, NULL, NULL, NULL, NULL, 'PERESIA', 'NKANINI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6148, '01101101019', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6148', NULL, NULL, NULL, NULL, NULL, 'MUIRURI', 'GABRIEL NYAGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6149, '01101101021', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6149', NULL, NULL, NULL, NULL, NULL, 'NICKSON', 'LAGAT CHERUIYOT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6150, '01101101023', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6150', NULL, NULL, NULL, NULL, NULL, 'PARTOIP', 'ALTON SEMPELE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6075, '01100003152', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6075', NULL, NULL, NULL, NULL, NULL, 'MASAI', 'RODGERS CHEPKECH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6152, '01101101025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6152', NULL, NULL, NULL, NULL, NULL, 'WANJOHI', 'DAVID MUGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6155, '01101101032', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6155', NULL, NULL, NULL, NULL, NULL, 'MARWA', 'FERDINAND MANGITENI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6156, '01101101037', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6156', NULL, NULL, NULL, NULL, NULL, 'KAMAU', 'ROBERT GITHAIGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6157, '01101101038', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6157', NULL, NULL, NULL, NULL, NULL, 'ROTIKEN', 'DENNIS KIYIAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6158, '01101101039', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6158', NULL, NULL, NULL, NULL, NULL, 'JOHN', 'BERNARD MULU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6159, '01101101044', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6159', NULL, NULL, NULL, NULL, NULL, 'ISAM', 'JOSHUA MWAODA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6160, '01101101056', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6160', NULL, NULL, NULL, NULL, NULL, 'MUIA', 'DENNIS KYAMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6161, '01101101056', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6161', NULL, NULL, NULL, NULL, NULL, 'KARIUKI', 'JOHN BOSCO NJUGUNA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6162, '01101101059', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6162', NULL, NULL, NULL, NULL, NULL, 'NYAKWAMA', 'HENRY OMONDI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6163, '01101101063', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6163', NULL, NULL, NULL, NULL, NULL, 'KEMBOI', 'BENJAMIN KIPYEGON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6164, '01101101066', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6164', NULL, NULL, NULL, NULL, NULL, 'WASIKE', 'SHADDAD AYUB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6165, '01101101066', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6165', NULL, NULL, NULL, NULL, NULL, 'DULLU', 'DENIS KOMORA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6166, '01101101067', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6166', NULL, NULL, NULL, NULL, NULL, 'KIRUI', 'MOSES KIPKOECH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6167, '01101101070', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6167', NULL, NULL, NULL, NULL, NULL, 'MASESE', 'VERONICAH MUTIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6168, '01101101075', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6168', NULL, NULL, NULL, NULL, NULL, 'KOSGEI', 'COLLINS KIMUTAI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6169, '01101101075', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6169', NULL, NULL, NULL, NULL, NULL, 'ROTICH', 'FELIX KIPLANGAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6170, '01101101076', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6170', NULL, NULL, NULL, NULL, NULL, 'MAHABA', 'RAMADHAN OMAR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6171, '01101101079', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6171', NULL, NULL, NULL, NULL, NULL, 'MULI', 'OSAAC KIM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6172, '01101101082', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6172', NULL, NULL, NULL, NULL, NULL, 'ISSACK', 'ABDIKHAFAR MOHAMED', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6173, '01101101085', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6173', NULL, NULL, NULL, NULL, NULL, 'CHEPTOO', 'MERCY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6174, '01101101085', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6174', NULL, NULL, NULL, NULL, NULL, 'AMANMAN', 'MARIO LOIU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6175, '01101101087', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6175', NULL, NULL, NULL, NULL, NULL, 'MNANGAT', 'HILLARY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6176, '01101101087', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6176', NULL, NULL, NULL, NULL, NULL, 'WANJUGU', 'BENHADAD NDUG''U', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6177, '01101101096', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6177', NULL, NULL, NULL, NULL, NULL, 'MOHAMED', 'ABDIMALIK AHMED', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6178, '01101101111', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6178', NULL, NULL, NULL, NULL, NULL, 'CHEGE', 'JONANA MURAGU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6000, '01100003001', '2022', '0714385056', '0714385057', 'idachirufus@gmail.com', '', '00205', '1236', 'NGONG HILLS', NULL, 'P15', '33116529', '123567899', 2, '123547898', 'PRE-REGISTRATION', NULL, 1, 1, '$2y$13$YbDqiVZUIjQjQjV6lzvzwurwhlr/FwXGRyXCrxvzGXAlk61bx6W/y', 't', '$2y$13$IEwHPqttdFaqjeRvkeIsD.oT//C8kLBhPUsfWqZPlSwpkv3RiofUe', NULL, '2022-12-15', NULL, 'KIPROTICH', 'HILLARY', 'PENDING', '2022-11-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7224, '39701038074', '2021', '254792215010', '254716665079', 'georgeaoko@gmail.com', 'joanesogot01@gmail.com', '40100', '4011', 'KONDELE', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b11b7e3409b27e5c6e332399362105f8', NULL, NULL, NULL, NULL, NULL, 'GEORGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6002, '01100003001', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '$2y$13$pGIssOtefLvNXICS4kd0sOZNI0L5MR261rCSMh1qjezq/RUxFXaXm', NULL, NULL, NULL, NULL, NULL, 'MISIKO', 'MOSES WANYONYI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6092, '01100004005', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6092', NULL, NULL, NULL, NULL, NULL, 'AKEYO', 'OKINYI MOLLINE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6104, '01100004024', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6104', NULL, NULL, NULL, NULL, NULL, 'KANDOLLE', 'PINKI ENGEFU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7225, '39714105052', '2021', '254769678917', '254721949205', 'aukomichael20@gmail.com', 'mathewauko@gmail.com', '40102', '110', 'KOMBEWA', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f629ed9325990b10543ab5946c1362fb', NULL, NULL, NULL, NULL, NULL, 'MICHAEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6017, '01100003014', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6017', NULL, NULL, NULL, NULL, NULL, 'KIIRU', 'MBARE PHIL DANCAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6117, '01100004053', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6117', NULL, NULL, NULL, NULL, NULL, 'MOHAMMED', 'MOHAMED ABDALLAH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6028, '01100003026', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6028', NULL, NULL, NULL, NULL, NULL, 'OGOLLA', 'KYLE KISSACHE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6040, '01100003045', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6040', NULL, NULL, NULL, NULL, NULL, 'BARASA', 'DEBORA MWABE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6053, '01100003058', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6053', NULL, NULL, NULL, NULL, NULL, 'ABIZERA', 'JOLI LANDDRY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6063, '01100003085', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6063', NULL, NULL, NULL, NULL, NULL, 'ANTONIO', 'BEATRIZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6078, '01100003181', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6078', NULL, NULL, NULL, NULL, NULL, 'AYIERA', 'DANCAN OCHIENG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6129, '01100004142', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6129', NULL, NULL, NULL, NULL, NULL, 'KORIR', 'ELIAS KIPKOECH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6140, '01101101004', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6140', NULL, NULL, NULL, NULL, NULL, 'LABAT', 'ANDREW KIPCHUMBA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6027, '01100003025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6027', NULL, NULL, NULL, NULL, NULL, 'MUTUKU', 'STEPHEN MUTETI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6036, '01100003041', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6036', NULL, NULL, NULL, NULL, NULL, 'MAKOKHA', 'SYDNEY BWONYA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6046, '01100003051', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6046', NULL, NULL, NULL, NULL, NULL, 'MWANZIA', 'FANUEL NYAMAI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6056, '01100003071', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6056', NULL, NULL, NULL, NULL, NULL, 'SEMU', 'MFURANKUNDA HENRY KEVIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6066, '01100003097', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6066', NULL, NULL, NULL, NULL, NULL, 'MOKAYA', 'FAITH NYATICHI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6074, '01100003144', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6074', NULL, NULL, NULL, NULL, NULL, 'ODIWUOR', 'TEDD EDWIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6076, '01100003161', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6076', NULL, NULL, NULL, NULL, NULL, 'IRERI', 'GIDEON KINYUA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6085, '01100004002', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6085', NULL, NULL, NULL, NULL, NULL, 'WAITHERERO', 'JEREMIAH GACHERU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6096, '01100004014', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6096', NULL, NULL, NULL, NULL, NULL, 'KINYUA', 'GERRALD KIMENYI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6106, '01100004025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6106', NULL, NULL, NULL, NULL, NULL, 'LENTIMALEI', 'DAVID LPIRIATO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6116, '01100004051', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6116', NULL, NULL, NULL, NULL, NULL, 'ACHIENG', 'WINNIE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6125, '01100004117', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6125', NULL, NULL, NULL, NULL, NULL, 'LEMAYAN', 'REUBEN LENGISHON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6135, '01101101003', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6135', NULL, NULL, NULL, NULL, NULL, 'OLESAIGULU', 'LEONARD SUNKULI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6144, '01101101008', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6144', NULL, NULL, NULL, NULL, NULL, 'WAKHUNGU', 'DORCUS NALIAKA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6151, '01101101025', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6151', NULL, NULL, NULL, NULL, NULL, 'MORARA', 'PURITY KEMUNTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6153, '01101101027', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6153', NULL, NULL, NULL, NULL, NULL, 'KIPROP', 'FILEX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6154, '01101101031', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6154', NULL, NULL, NULL, NULL, NULL, 'ADAM', 'MOFFAT MORARA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7226, '40727127150', '2021', '254758835534', '254722820757', 'davimwita0@gmail.com', 'danielmarwa2012@gmail.com', '40416', '7', 'KEGONGA', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5ea363a74cddf7e0b3110d79212cc89c', NULL, NULL, NULL, NULL, NULL, 'MARWA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (363, '26500001092', '2021', '0722367793', '254724704952', 'cckiptoo@gmail.com', 'chepkoechc@gmail.com', '00100', '215, ICT Center', 'Nairobi', '1263119', 'P15', '123456789', '123456789', 2, 'A123456789', 'PRE-REGISTRATION', NULL, 2, 1, '$2y$13$X1E08rCuZUvBZLPKcr5NteeL9OLqUA/8VmRgvVOJll3AyxlLB/986', NULL, '$2y$13$0s2wvsbqS34H4L.cZwDMMO4Pp03AggBveBPXoz0P9t4VaWXp9aBqO', '$2y$13$4GpETtJuzY/.eQOeL2DZa.bR.ArCaPVg9jDvuobMzNV6AgEixKJiy', NULL, NULL, 'CHEPTOO', '', NULL, '2023-01-30', 'Kenya navy', NULL, '987654321', 'kenyan', '2002-01-30', 't', 1, '0+');
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7233, '13317112008', '2021', '254757542286', '254792550048', 'idachirufus@gmail.com', 'rambasu@uonbi.ac.ke', '90220', '8-90220', 'MUTOMO', '1263110', 'P15', '33116529', '33116529', 2, '33116529', 'REGISTERED', NULL, 2, 1, '$2y$13$JviTjmroW8AwJchUOnn6veYmpTVykXHUF61.BZU3c3yzC9B7DPn9y', 't', '$2y$13$AWySb2Tf9c1oe/CWwEbeFew9gLk7fGtwjPN1hfCZ9dEe7Tomk6tCW', '$2y$13$J39KMI0ZlXpW6CwtGrzSteZocNQKY.xIbZ80WW789iwlY0q9e/rGK', '2023-01-19', '2023-01-19', 'JOHN', 'DOE', 'CLEARED', '2023-01-19', NULL, 't', NULL, NULL, '2023-01-20', NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (744, '24505104100', '2021', '254704785506', '254721230308', 'rambasu@uonbi.ac.ke', '', '30100', '8565', 'ELDORET', '1263127', 'P15', NULL, NULL, 2, NULL, 'REGISTERED', NULL, 2, 1, '$2y$13$fSWCi82aUbPt1TU06gtcgeRs2Nip3J23QrCuBRyIFr.USWwOJqxq.', 't', '$2y$13$w0r/2dJ3oB5ukpAxBRw7kurE0imScj1S1iXcwjMMlgOMd86CDmjyS', NULL, '2023-01-24', NULL, 'ALLAN', 'ALLAN', 'CLEARED', '2023-01-24', NULL, 't', NULL, NULL, '2023-01-24', 't', NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7203, '15361115115', '2021', '254741242461', '254703984136', 'meridahgakii@gmail.com', '', '60202', '231', '60200', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '118921efba23fc329e6560b27861f0c2', NULL, NULL, NULL, NULL, NULL, 'MERIDAH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7204, '18300003113', '2021', '254798714876', '254716615386', 'khergsamkargzams@gmail.com', 'khergsamkargzams@gmail.com', '90300', '20', 'WOTE', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ae3d525daf92cee0003a7f2d92c34ea3', NULL, NULL, NULL, NULL, NULL, 'WILLIAM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7205, '18307205011', '2021', '254769499992', '254711984903', 'fidelmusumbi@gmail.com', 'fidelmusumbi@gmail.com', '90305', '16', 'KILALA', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '75da5036f659fe64b53f3d9b39412967', NULL, NULL, NULL, NULL, NULL, 'MUTISYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7206, '19300004308', '2021', '254727463635', '254722783391', 'sashandutamurathe@gmail.com', 'stellawmugo@gmail.com', '00100', '24844', 'NAIROBI', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bd48f59a9f04aefd7708058b717453af', NULL, NULL, NULL, NULL, NULL, 'MURATHE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7207, '20400001226', '2021', '254741742060', '254710502848', 'amonibashir@gmail.com', 'asumanagoya@gmail.com', '', '', 'TURBO', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2d45cbe914655ca562553cb81fdfc464', NULL, NULL, NULL, NULL, NULL, 'AMONI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7208, '20401001182', '2021', '254113992868', '254721323353', 'kilelemax@gmail.com', '', '', '', '', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '589f763b060f8c19170cdf5196e2bf87', NULL, NULL, NULL, NULL, NULL, 'KILELE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7209, '20403004298', '2021', '254712167146', '254720727869', 'debrasp67@gmail.com', 'uzimatelenairobi@yahoo.co.uk', '00101', '101066', 'NAIROBI', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e904831f48e729f9ad8355a894334700', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7210, '20405001004', '2021', '254111242589', '254723448187', 'luqmanhared33@gmail.com', 'haredhussein18@gmail.com', '00100', '34567', 'NAIROBI', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'df308fd90635b28d82558cf580c73ed9', NULL, NULL, NULL, NULL, NULL, 'HARED', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7211, '20406020065', '2021', '', '', '', '', '', '', '', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f10f2da9a238b746d2bac55759915f0d', NULL, NULL, NULL, NULL, NULL, 'ALLAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7212, '24500022104', '2021', '254711258188', '254711258151', 'rotichlaurence1@gmail.com', '', '30600', '284', 'KAPENGURIA', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e14e58299bc41e7fb10c701130c5cb27', NULL, NULL, NULL, NULL, NULL, 'PCHUMBA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7213, '26577124001', '2021', '254706316835', '254725089787', 'timothyowenga5@gmail.com', 'kefalilian6@gmail.com', '12345', '3284', 'ELDORET', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6213a8959a9a96589ca484dfd1e25053', NULL, NULL, NULL, NULL, NULL, 'KEFA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7214, '27537409097', '2021', '254768514843', '254713354168', 'g910693@gmail.com', '', '20116', '270', 'GILGIL', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cdbc9bca0a9fd93852571cced0089c4d', NULL, NULL, NULL, NULL, NULL, 'WANJIKU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7215, '27564101091', '2021', '254798571709', '254722692287', 'jamesmwas898@gmail.com', 'jamesmwas898@gmail.com', '20100', '40', 'NAKURU', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b17446af05919be6e83500be7f5df5c4', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7216, '28500005226', '2021', '254792170462', '254702511656', 'faithrugut75@gmail.com', 'dianarugut95@gmail.com', '00517', '908', 'NAIROBI', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '994d1cad9132e48c993d58b492f71fc1', NULL, NULL, NULL, NULL, NULL, 'RUGUT', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7217, '33517104034', '2021', '254768406501', '254722271078', 'sheilakemboi@gmail.com', 'simonrotich@gmail.com', '00100', '450', 'NAIROBI', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '29263a8cf61fb9addf5629769fac92b7', NULL, NULL, NULL, NULL, NULL, 'KEMBOI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7218, '35623104002', '2021', '254793606743', '254723421692', 'patrickwafulaanyona@gmail.com', 'nyingesaidimu@gmail.com', '50405', '20', 'BUMALA', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e7d6e2e80f0955c01f3e043ee79abbb6', NULL, NULL, NULL, NULL, NULL, 'ANYONA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7219, '36600002029', '2021', '254777294269', '254720862724', 'noelmwenge54@gmail.com', 'simonmwenge661@gmail.com', '30200', '2263', 'KITALE', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9c415bdd4dd66723ef7b38853ef35ddb', NULL, NULL, NULL, NULL, NULL, 'MWENGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7220, '37608005018', '2021', '254724494971', '254710640405', 'glorykeya@gmail.com', 'reubenchivini@gmail.com', '', '', '', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bd4828247647544af24a15ac79a1ef9f', NULL, NULL, NULL, NULL, NULL, 'GLORY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7221, '37616006010', '2021', '254741232288', '254723481779', 'jamesobutoyi@gmail.com', 'paulabwenje@gmail.com', '00100', '00', 'NAIROBI', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5ecf33fd9caf42c3bd39a3d9ee5f9ca3', NULL, NULL, NULL, NULL, NULL, 'OBUTOYI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7222, '39700001405', '2021', '254745413550', '254716307097', 'derrickdobaking@gmail.com', 'chesevegedion@gmail.com', '526', '100', 'WEBUYE', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3c5be6328b5f6a0a5980341230b8ac05', NULL, NULL, NULL, NULL, NULL, 'SINDANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7223, '39701007040', '2021', '254112018667', '254793977176', 'oumahillary@gmail.com', '', '40102', '89', 'KOMBEWA', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f3067d687ee39c3cbfa75573457e479d', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (134, '20400008030', '2021', '254784974330', '254725536148', 'idachirufus@gmail.com', 'rambasu@uonbi.ac.ke', '00208', '225', 'Ngong hills', '1263115', 'P15', '33116529', '33116529', 2, '', 'PRE-REGISTRATION', NULL, 2, 1, '$2y$13$05HIjaRkd56UKgTk/nKThOzXI4JhpnJdCLGjF9trdnwQ6Zvr8gumi', NULL, '$2y$13$oNB7CjvCUG//4/HQcwKXCOpMUQ35GV689fhJCPUCRbZ21.Rth/A8.', '$2y$13$PhdZrrUM/JEnWXUtid.lG.MohCq3xAHleRAtytDYTH1dY6HFvL.du', NULL, NULL, 'MUTHAMA', '', NULL, NULL, 'Kenya army', NULL, '33256', 'austrian', '2023-01-30', 't', 1, 'AB+');
INSERT INTO "smisportal"."sm_admitted_student" VALUES (236, '20400002347', '2021', '4693076571', '254722833668', 'chegembugua2003@gmail.com', '', '75006-6880', '3030303', 'Carrollton', '1263117', 'P15', '1111111111', '1111111111', 2, 'A1111111111', 'PRE-REGISTRATION', NULL, 2, 1, '$2y$13$1prjLheY4orm/t6p0JLTieQZufKwz3et7mYb9kruptoJckdbHlgH.', NULL, NULL, NULL, '2023-01-31', '2023-01-31', 'MARTIN', '', NULL, '2023-01-31', 'Kenya navy', NULL, '987654321', 'kenyan', '2000-01-03', 't', 1, 'A-');
INSERT INTO "smisportal"."sm_admitted_student" VALUES (245, '20404006028', '2021', '254745726432', '254757302928', 'rambasu@uonbi.ac.ke', 'idachirufus@gmail.com', '40101', '177', 'AHERO', '1263117', 'P15', '33116529', '33116529', 2, '', 'REGISTERED', NULL, 2, 1, '$2y$13$8AggF/TuwD0Nwr6onRFQ..TInoNismbfMFRXQFVmsTwHzQ33YNhba', 't', '$2y$13$hQhXfQqJ3Cgq0d.oB7f.n.KE0M1897uJ8rjuMqGIgSMnjX1jyb4py', '$2y$13$cLBKe5Drthk5AWNGDYR2NujGlVRTjOZ4AXOEQqn1ecjp4SKrbiQLK', '2023-02-15', '2023-02-15', 'MSHINDI', 'MSHINDI', 'CLEARED', '2023-02-07', 'Kenya army', 't', '33256', 'afghan', '2023-02-07', 't', 1, 'AB+');
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6001, '01100003001', '2022', '0714385059', '0739823669', 'rambasu@uonbi.ac.ke', 'idachirfus@gmail.com', '00209', '12367', 'NGONG HILLS', NULL, 'P15', '331165288', '123356788', 2, '123547898', 'REGISTERED', NULL, 1, 1, '$2y$13$YbDqiVZUIjQjQjV6lzvzwurwhlr/FwXGRyXCrxvzGXAlk61bx6W/y', 't', '$2y$13$UBzD5nOv5CJJdQARaIfrZubZqDhN0nuCcYgX5ECEyMxDwkekZZnsS', '$2y$13$HPbg/PAvKyQF1tld.7uWoO5H1TDhcEefKMX75ZuwSWyxuEnFGgwbu', '2022-12-15', '2022-12-15', 'JUMA', 'DAVID MASIGA', 'CLEARED', '2022-11-28', NULL, NULL, NULL, NULL, NULL, 't', NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7228, '42721101253', '2021', '254759572234', '254710498985', 'chrisoyoo@gmail.com', 'joseph.orukojo@gmail.com', '00200', '6863100622', 'NAIROBI', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'acd9bdac8824615154e7f1868f29acf6', NULL, NULL, NULL, NULL, NULL, 'OYOO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7229, '42721202327', '2021', '254718881124', '254713691602', 'calistuso18@gmail.com', 'otienowillis044@gmail.com', '00100', 'SHANKARDASS HOUSE, M', 'NAIROBI', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2d290e496d16c9dcaa9b4ded5cac10cc', NULL, NULL, NULL, NULL, NULL, 'OMONDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7230, '42725210152', '2021', '254702987295', '254715877848', 'mwitavincent86@gmail.com', 'nomondi254@gmail.com', '40634', '321', 'UGUNJA', '1263109', 'I09', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f0b76267fbe12b936bd65e203dc675c1', NULL, NULL, NULL, NULL, NULL, 'MWITA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7231, '8202001114', '2021', '254710920262', '254703166063', 'wextyk@gmail.com', 'annnyambura998@gmail.com', '01000', '6103', 'THIKA', '1263110', 'I10', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b522259710151f8cc7870b970b4e0930', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7232, '8202003035', '2021', '254718467693', '254718467693', 'www.justinnaiz.com@gmail.com', 'www.justinnaiz.com@gmail.com', '588', '591, 597', 'NAIROBI', '1263110', 'I10', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd8ea5f53c1b1eb087ac2e356253395d8', NULL, NULL, NULL, NULL, NULL, 'MBUGUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1721, '20400002389', '2021', '254721359386', '254722861103', 'badmanwakoo@gmail.com', 'rufowario@gmail.com', '2100', '2227', 'NAKURU', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '28dc6b0e1b33769b4b94685e4f4d1e5c', NULL, NULL, NULL, NULL, NULL, 'WAKO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7234, '20402015001', '2021', '254715963272', '254791003288', 'njnajka66@gmail.com', 'alimy818@gmail.com', '00610', '4520', 'KAMUKUNJI', '1263110', 'I10', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '44d47238d7d3e17aa176019eafac82af', NULL, NULL, NULL, NULL, NULL, 'AHMED', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7235, '20406004042', '2021', '254705944211', '254724010673', 'ngendokama@gmail.com', 'sarainyams@gmail.com', '00100', '53440-00100', 'NAIROBI', '1263110', 'I10', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cf2ee7de99895351d72dcc79d067b24b', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7236, '27500002290', '2021', '254717826700', '254727791866', 'keric5@gmail.com', 'smutai5@gmail.com', '00100', '30259', 'NAIROBI', '1263110', 'I10', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '58ae23d878a47004366189884c2f8440', NULL, NULL, NULL, NULL, NULL, 'ERIC', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7237, '41710305015', '2021', '254113798360', '254707406151', 'onyangodave097@gmail.com', '', '40100', '1335', 'KISUMU', '1263110', 'I10', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '742141ceda6b8f6786609d31c8ef129f', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7238, '43715105060', '2021', '254769843757', '254719101531', 'ruthm3300@gmail.com', 'nambegera@gmail.com', '40200', '2340', 'KEROKA', '1263110', 'I10', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '937ea3f7714dc0d01475da7bff33b596', NULL, NULL, NULL, NULL, NULL, 'RUTH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7239, '3100001101', '2021', '254741551579', '254728987865', 'mag4muthoni@gmail.com', '', '', '', '', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0d9756dc528560b61c85bfefba233aab', NULL, NULL, NULL, NULL, NULL, 'WANJOHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7240, '3126102052', '2021', '254700076022', '254729997608', 'hanaansherman@gmail.com', 'mufidashabibi@yahoo.com', '', '', 'MOMBASA', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f0d7053396e765bf52de12133cf1afe8', NULL, NULL, NULL, NULL, NULL, 'SHERMAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7241, '8202001289', '2021', '254769747629', '254721737528', 'billythunderbird101@gmail.com', 'catherinenjerimugure@gmail.com', '10100', '337', 'NYERI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '040d45ccc13c070fcec9d46ccd0cc543', NULL, NULL, NULL, NULL, NULL, 'KIMOTHO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7242, '8237009205', '2021', '254780007343', '254721966342', 'pfaith869@gmail.com', 'morrisndaat@gmail.com', '80200', '4', 'MALINDI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a09b68ada5e8b2060f380a53c02cf3d8', NULL, NULL, NULL, NULL, NULL, 'NDAA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7243, '10200013105', '2021', '254707719188', '254721647447', 'macleanlatifah@gmail.com', 'karolkarondo@gmail.com', '10306', '10306', 'KAGIO', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd768f8ec110b0207ba7a209f7975fbb1', NULL, NULL, NULL, NULL, NULL, 'LATIFAH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7244, '10226201124', '2021', '254798689662', '254726409682', 'wairimun001@gmail.com', 'godfreygichane0@gmail.com', '10300', '143-10300', 'KERUGOYA', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '464074179972cbbd75a39abc6954cd12', NULL, NULL, NULL, NULL, NULL, 'NJOROGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7245, '11200001302', '2021', '254111567002', '254716816036', 'makhandiachris@gmail.com', 'makhandiachrist@gmail.com', '', '', '', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8ca070cc474c02335277c16ce15a469b', NULL, NULL, NULL, NULL, NULL, 'BARASA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7246, '12315504227', '2021', '254719580664', '254719580664', 'rosesamuel093@gmail.com', '', '90207', '05', 'IKUTHA', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd240cb4a3e3d2ed5250ac2e1480422f0', NULL, NULL, NULL, NULL, NULL, 'ROSE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7247, '12330108064', '2021', '254772041722', '254712160199', 'soponjohn632@gmail.com', 'timkentours@yahoo.com', '80400', 'P.0 BOX 5398 DIANI', 'DIANI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cf5ff72ca35f112b361de3e312c088f4', NULL, NULL, NULL, NULL, NULL, 'JOHN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7248, '13353102018', '2021', '254708470723', '254708470723', 'damarismutua2004@gmail.com', 'damarismutua2004@gmail.com', '90214', '01', 'MBITINI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1f4183315762e30ea441d3caef5e64ad', NULL, NULL, NULL, NULL, NULL, 'MUTUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7249, '14303104048', '2021', '254114949208', '254722850290', 'virginiakinyamasyo002@gmail.com', '', '90200', '246', 'KITUI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'df5511886da327a5e2877c3cd733d9d7', NULL, NULL, NULL, NULL, NULL, 'KINYAMASYO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7250, '15300012159', '2021', '254799179447', '254729272867', 'prudencejebet63@gmail.com', 'prudencejebet63@gmail.com', '', '', 'NKUBU', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8ce6fc704072e351679ac97d4a985574', NULL, NULL, NULL, NULL, NULL, 'KITILIT', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7251, '15304102181', '2021', '254798658414', '254727994584', 'sandrakarimi70@gmail.com', 'janekaimuri50@gmail.com', '', '', '', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3472ab80b6dff70c54758fd6dfc800c2', NULL, NULL, NULL, NULL, NULL, 'SANDRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7252, '15319102045', '2021', '254759704626', '254724253088', 'millermagige@gmail.com', 'kavindumasyula@gmail.com', '', '', '', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '201d546992726352471cfea6b0df0a48', NULL, NULL, NULL, NULL, NULL, 'MONAGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7253, '18346106074', '2021', '254112281832', '254114793958', 'muanzadan@gmail.com', 'mutetijack@gmail.com', '90300', '511', 'KALAWA -MAKUENI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7900770abf7086faafd8c122981cc82f', NULL, NULL, NULL, NULL, NULL, 'PETER', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7254, '19300004322', '2021', '254738757147', '254722429809', 'tracey1wanjiru@gmail.com', 'kabuilucy@gmail.com', '10101', '982', 'KARATINA', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5248e5118c84beea359b6ea385393661', NULL, NULL, NULL, NULL, NULL, 'KIMETTO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7255, '19300010164', '2021', '254745099335', '254723653029', 'ianmunene390@gmail.com', '', '60204', '73', 'MITUNGUU', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fbefa505c8e8bf6d46f38f5277fed8d6', NULL, NULL, NULL, NULL, NULL, 'MUNENE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7256, '20400001304', '2021', '254114825550', '254727599876', 'collinsotwere9@gmail.com', 'collinsotwere9@gmail.com', '', '', 'KITALE', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9f8785c7f9b578bec2c09e616568d270', NULL, NULL, NULL, NULL, NULL, 'NGONO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7257, '20400002216', '2021', '254706165214', '254726818956', 'penjoki19528@gmail.com', 'stepkiki2009@yahoo.com', '20303', '701', 'NYAHURURU', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f77ad541b6b5bc48c47d814b95491fbd', NULL, NULL, NULL, NULL, NULL, 'KINUTHIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7258, '20400002248', '2021', '254795707677', '254723103114', 'peternjungeezzz@gmail.com', 'hillarynjunge@gmail.com', '', '', '', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '358c850b3836ae02b1d8b319d86d435f', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7259, '20400003212', '2021', '254797862081', '254728211900', 'ikramhish@gmail.com', 'ihishadan@gmail.com', '70100', '1678', 'GARISSA', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd04cb95ba2bea9fd2f0daa8945d70f11', NULL, NULL, NULL, NULL, NULL, 'IKRAM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7260, '20404003116', '2021', '254700528335', '254722227155', 'wambuabrian023@gmail.com', 'ngalawa2030@gmail.com', '00100', '50987', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c705112d1ec18b97acac7e2d63973424', NULL, NULL, NULL, NULL, NULL, 'WAMBUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7261, '20404006086', '2021', '254733926995', '254722475777', 'karanumac3@gmail.com', 'karanurose@gmail.com', '38175', '623', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8346db44a721fa863ca38180638bad3d', NULL, NULL, NULL, NULL, NULL, 'KARANU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7262, '20404013003', '2021', '254703903456', '254703903456', 'jullybinya@yahoo.com', 'jullybinya@yahoo.com', '00100', '5448', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9773d3352e206fef3df91b8757d63b67', NULL, NULL, NULL, NULL, NULL, 'SHAMIM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7263, '20405004075', '2021', '254717350185', '254727986338', 'habonkhalif2003@gmail.com', 'khalifhassan1968@gmail.com', '00200', '73592', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'feeef9bd04fac1765263893621bb4811', NULL, NULL, NULL, NULL, NULL, 'HASSAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7264, '20406001169', '2021', '254729783065', '254724247536', 'ruweidaabdii@gmail.com', 'aminagulie@gmail.com', '70300', '34567', '70300', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '89c86ad4bb118af4b7d49925b1b319e1', NULL, NULL, NULL, NULL, NULL, 'RUWEYDHA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7265, '20408032073', '2021', '254794374579', '254722896288', 'humblessing04@gmail.com', 'ishmamu74@gmail.com', '2089', 'PRIVATE BAG', 'BUSIA TOWNSHIP', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '07d5938693cc3903b261e1a3844590ed', NULL, NULL, NULL, NULL, NULL, 'HUMBLE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7266, '20410001285', '2021', '254708214767', '254708214767', 'jirumeymar@gmail.com', 'salatume@gmail.com', '00100', '43112', 'NRB', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '439fca360bc99c315c5882c4432ae7a4', NULL, NULL, NULL, NULL, NULL, 'HUKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7267, '23503135022', '2021', '254714501819', '254722273389', 'hezbonwafula911@gmail.com', 'hezbonwafula911@gmail.com', '50200', '743', 'BUNGOMA', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6528f3f143a1db743a30a3c4443d35c8', NULL, NULL, NULL, NULL, NULL, 'MASINDE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7268, '23574142002', '2021', '254790434688', '254702349262', 'ianwanyonyi2@gmail.com', 'jimmyrowling7@gmail.com', '30209', '822', 'KITALE', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '69cd21a0e0b7d5f05dc88a0be36950c7', NULL, NULL, NULL, NULL, NULL, 'WANYONYI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7269, '26500001103', '2021', '254768253424', '254706646893', 'mercyjeruto93@gmail.com', 'margaretyegon@gmail.com', '30400', '152', 'KABARNET', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '35d8f387d4934b6ee53ce5c9a1d8c1d7', NULL, NULL, NULL, NULL, NULL, 'MERCY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7270, '26576103005', '2021', '254722532154', '254721604371', 'josephkariuk41@gmail.com', 'raphaelnganga3@gmail.com', '047', '', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7f489f642a0ddb10272b5c31057f0663', NULL, NULL, NULL, NULL, NULL, 'NG''ANG''A', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7271, '27536239018', '2021', '254743941268', '254743941268', 'johnmwangi4302j@gmail.com', 'muthonimargaret463@gmail.com', '00518', '691', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '52fc2aee802efbad698503d28ebd3a1f', NULL, NULL, NULL, NULL, NULL, 'MAINGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7272, '27552049072', '2021', '254708596838', '254708596838', 'charitynduta@gmail.com', 'charitynduta@gmail.com', '20100', '3663', 'NAKURU', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd2a1e34d86293cb12f959f89dddf263e', NULL, NULL, NULL, NULL, NULL, 'WANGARI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7273, '28539202205', '2021', '254114344107', '254726416817', 'fkipkoech823@gmail.com', '', '20209', '20', 'FORT TERNAN', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9d702ffd99ad9c70ac37e506facc8c38', NULL, NULL, NULL, NULL, NULL, 'KIPKOECH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7274, '31500026205', '2021', '254795479176', '254726252773', 'dmuriuki221@gmail.com', 'dmuriuki221@gmail.com', '6966', 'NGONG', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1301962d8b7bd03fffaa27119aa7fc2b', NULL, NULL, NULL, NULL, NULL, 'MURIUKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7275, '31567221004', '2021', '254111802787', '254797338264', 'abdirahmangurhan68@gmail.com', 'gurhan.isse@gmail.com', '00100', 'N/A', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bb921944c8c4531826da3fa99b494c1a', NULL, NULL, NULL, NULL, NULL, 'ABDIRAHMAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7276, '36602102107', '2021', '254112710955', '254727692140', 'stefantrickyjones@gmail.com', 'nancy4muyila@gmail.com', '50200', '85', 'BUNGOMA', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f7b027d45fd7484f6d0833823b98907e', NULL, NULL, NULL, NULL, NULL, 'WAFULA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7277, '37600005436', '2021', '254741199859', '254722663704', 'odallohpreciousjoan@gmail.com', 'oderaevansodalloh@gmail.com', '40002', '', 'OYUGIS', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'dcacff2565700c8f88f59cf4a16f9dfc', NULL, NULL, NULL, NULL, NULL, 'ODALLOH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7278, '38622106002', '2021', '254799491753', '254707183824', 'billpeter776@gmail.com', '', '50313', '81', 'MBALE', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3332880692313818482a5a0286608ab6', NULL, NULL, NULL, NULL, NULL, 'PETER', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7279, '39713021095', '2021', '254700472332', '254720804211', 'owinoezra8@gmail.com', 'ojwangtobby1112@gmail.com', '00508', '76690 NAIROBI', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f75dddd1e79826a219cb0bec217dc096', NULL, NULL, NULL, NULL, NULL, 'EZRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7280, '39733212022', '2021', '', '', '', '', '', '', '', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b05b57f6add810d3b7490866d74c0053', NULL, NULL, NULL, NULL, NULL, 'OKOTH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7281, '39733212272', '2021', '254710211114', '254740113584', 'jkipkemoi797@gmail.com', '', '40109', '323', 'SONDU', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '701d804549a4a23d3cae801dac6c2c75', NULL, NULL, NULL, NULL, NULL, 'JOHN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7282, '39734408082', '2021', '254703773145', '254714474664', 'hillaryondoro@gmail.com', '', '40109', '12', 'SONDU', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '21c2c25487b9f30af6c4a9f6f10b09b2', NULL, NULL, NULL, NULL, NULL, 'HILLARY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7283, '40727101211', '2021', '254714435119', '254714435119', 'ronaldayuma1@gmail.com', 'ronaldayuma1@gmail.com', '40210', '3849', 'ITUMBE', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f82798ec8909d23e55679ee26bb26437', NULL, NULL, NULL, NULL, NULL, 'MACHUCHU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7284, '40727101387', '2021', '254110350261', '254729732346', 'samweltom258@gmail.com', '', 'Kisii', '3464', 'KISII', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ac450d10e166657ec8f93a1b65ca1b14', NULL, NULL, NULL, NULL, NULL, 'SAMWEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7285, '40745101080', '2021', '254114981942', '254714667981', 'nyarooisaac@gmail.com', 'mobknny@gmail.com', '80100', '90202', 'MOMBASA', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '78ccad7da4c2fc2646d1848e965794c5', NULL, NULL, NULL, NULL, NULL, 'NYAROO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7286, '41709102025', '2021', '254701498598', '254705357943', 'osamwel2010@gmail.com', 'jwintal64@gmail.com', '40405', '79 SARE AWENDO', 'AWENDO', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8e50baf642bd6685e593bf238aa27051', NULL, NULL, NULL, NULL, NULL, 'OCHIENG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7287, '41709108001', '2021', '254743257315', '254790998106', 'brianotieno74566@gmail.com', 'ebrahimhnry1986@gmail.com', '40303', '22', 'RANGWE', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c96e651946818e0787d6296f69549fe1', NULL, NULL, NULL, NULL, NULL, 'ODHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7288, '41709201033', '2021', '254719262352', '254719262352', 'ombiara@gmail.com', 'ombiara@gmail.com', '00100', '21710', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '986648642d1a68a3178f6869689cc260', NULL, NULL, NULL, NULL, NULL, 'OMBIARA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7289, '42726206164', '2021', '254706483676', '254716011938', 'damaclineagola@gmail.com', 'damaclineagola@gmail.com', '', '', '', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c4c42505a03f2e969b4c0a97ee9b34e7', NULL, NULL, NULL, NULL, NULL, 'JANE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7290, '42738101379', '2021', '254111890888', '254723864252', 'wendyakinyi004@gmail.com', 'nellyawuor0@gmail.com', '00200', '2850', 'NAIROBI', '1263111', 'I11', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c54d2118d6a3a2b06f07e9eeb240d741', NULL, NULL, NULL, NULL, NULL, 'OLENGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7291, '3126101094', '2021', '254722281825', '254722281825', 'mukhlisayasin17@yahoo.com', 'yasinhussein@hotmail.com', '00511', '186', 'ONGATA-RONGAI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3000311ca56a1cb93397bc676c0b7fff', NULL, NULL, NULL, NULL, NULL, 'MUKHLISA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7292, '4107141002', '2021', '254741173242', '254729877850', 'salmaomar2000@gmail.com', 'adanabsiya@yahoo.com', '70101', '70', 'HOLA', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f0e6be4ce76ccfa73c5a540d992d0756', NULL, NULL, NULL, NULL, NULL, 'SALMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7293, '9200012282', '2021', '254759663737', '254721646363', 'isgathoni35@gmail.com', '', '', '', 'DANDORA', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7d91786c01b3931e8d94baf248608979', NULL, NULL, NULL, NULL, NULL, 'GATHONI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7294, '9222201044', '2021', '254776265679', '254725608767', 'marquemwea@gmail.com', 'rmwea@kmtc.ac.ke', '200', '30195', 'NGONG', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bc047286b224b7bfa73d4cb02de1238d', NULL, NULL, NULL, NULL, NULL, 'NJENGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7295, '10234506006', '2021', '254721607213', '254723608717', 'wanguiwaweru02@gmail.com', 'mwihakiwaweru@gmail.com', '00100', 'NAIROBI', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f953ad57910572bd6803da3faaa6e92b', NULL, NULL, NULL, NULL, NULL, 'MICHELLE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7296, '10234506016', '2021', '254798935934', '254733572342', 'kaburaitimu6@gmail.com', 'torihiitimu@gmail.com', '00511', '566', 'ONGATA RONGAI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd7f14b4988c30cc40e5e7b7d157bc018', NULL, NULL, NULL, NULL, NULL, 'ITIMU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1722, '20400002417', '2021', '254110544466', '254722731827', 'konteechule@gmail.com', 'chuleabdi2040@gmail.com', '', '7056-00300 NAIROBI', '', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '404dcc91b2aeaa7caa47487d1483e48a', NULL, NULL, NULL, NULL, NULL, 'KONTE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7297, '11200001105', '2021', '254739347542', '254722606619', 'calebadrian1177@gmail.com', 'omolo.ed@gmail.com', '00100', '3891', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '142536b9b535b78e681c11b0195d962f', NULL, NULL, NULL, NULL, NULL, 'CALEB', 'omolo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7298, '11200002057', '2021', '254705589888', '254722827481', 'mutheumunene.j@gmail.com', 'lawmunene@gmail.com', '00506', '5160', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cc360b61d7eb072c77a4beddebb3c95b', NULL, NULL, NULL, NULL, NULL, 'MUNENE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7299, '11200002286', '2021', '254712220117', '254712220117', 'uchiimma4@gmail.com', 'lmmwaringa@tum.ac.ke', '80108', '547', 'KILIFI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '29e11dc359bad383e1243f730bdbe032', NULL, NULL, NULL, NULL, NULL, 'MURIMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7300, '11200002385', '2021', '254112484224', '254722316089', 'melsadegree@gmail.com', 'bettsysteves@gmail.com', '01020', '14- 01020', 'KENOL', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '220787ad7829c9cbc7e9953cb1c36fb3', NULL, NULL, NULL, NULL, NULL, 'GACHERU', 'wanjiru', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (14, '11200006127', '2021', '254722952990', '254722288121', 'cathykemunto55@gmail.com', 'divinahanyona1@gmail.com', '', '', '', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'aab3238922bcc25a6f606eb525ffdc56', NULL, NULL, NULL, NULL, NULL, 'NYANKIEYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (15, '11207112075', '2021', '254113674998', '254718918296', 'jchemoto@gmail.com', 'chemotosifunaj@gmail.com', '', '', '', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9bf31c7ff062936a96d3c8bd1f8f2ff3', NULL, NULL, NULL, NULL, NULL, 'CHEMOTO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (16, '11235125040', '2021', '254715187930', '254721455364', 'jonathannjorogey2004@gmail.com', 'jemimanashali@gmail.com', '00200', '54010', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c74d97b01eae257e44aa9d5bade97baf', NULL, NULL, NULL, NULL, NULL, 'JONATHAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (17, '11235127046', '2021', '254796261052', '254717656265', 'audreyketa2@gmail.com', 'patrickketa@yahoo.com', '00200', '73602', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '70efdf2ec9b086079795c442636b55fb', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (18, '18307301141', '2021', '254112859141', '254728440100', 'damahmumoh06@gmail.com', 'raphaelmwangangij@mail.com', '90108', '88', 'KOLA', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6f4922f45568161a8cdf4ad2299f6d23', NULL, NULL, NULL, NULL, NULL, 'MAUNDU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (19, '18325209075', '2021', '254768043232', '254720854338', 'kyengomarymumbi@gmail.com', 'abbymurage@gmail.com', '0200', '2768', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1f0e3dad99908345f7439f8ffabdffc4', NULL, NULL, NULL, NULL, NULL, 'KYENGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (20, '18336101028', '2021', '254112778950', '254710177000', 'perpetualshani32@gmail.com', 'aggykamesh@gmail.com', '90121', '22', 'EMALI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '98f13708210194c475687be6106a3b84', NULL, NULL, NULL, NULL, NULL, 'KAMENE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (21, '18336101039', '2021', '254115036196', '254723313625', 'margie.katanu@gmail.com', 'bonmutinda@yahoo.com', '00200', '54856', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3c59dc048e8850243be8079a5c74d079', NULL, NULL, NULL, NULL, NULL, 'MUTINDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (22, '20400002182', '2021', '254114197987', '254715619815', 'tedmaina3@gmail.com', 'lmgitonga2001@gmail.com', '', '', '', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b6d767d2f8ed5d21a44b0e5886680cb9', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (23, '20400002221', '2021', '254700656648', '254700656648', 'fmoseh@yaoo.com', 'fmoseh@yahoo.com', '00200', '75678', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '37693cfc748049e45d87b8c7d8b9aacd', NULL, NULL, NULL, NULL, NULL, 'MOSE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (24, '20400003162', '2021', '254722526412', '254722526412', 'graceg@invest.co.ke', 'graceg@invest.co.ke', '00200', '50638', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1ff1de774005f8da13f42943881c655f', NULL, NULL, NULL, NULL, NULL, 'NICOLE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (25, '20400003272', '2021', '254726986628', '254726986628', 'wokidi@gmail.com', 'wokidi@gmail.com', '40405', '107 SARE AWENDO', 'AWENDO', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8e296a067a37563370ded05f5a3bf3ec', NULL, NULL, NULL, NULL, NULL, 'MAUREEN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (26, '20403007013', '2021', '254711943721', '254722136409', 'hhoneyhussein@gmail.com', 'solidadazar@gmail.com', 'N/A', 'N/A', 'N/A', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4e732ced3463d06de0ca9a15b6153677', NULL, NULL, NULL, NULL, NULL, 'HUSSEIN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (27, '20403007021', '2021', '254769615419', '254722512313', 'mpsiham52@gmail.com', 'gediboss72@gmail.com', '00100', '43249', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '02e74f10e0327ad868d138f2b4fdd6f0', NULL, NULL, NULL, NULL, NULL, 'ADAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (28, '20404003190', '2021', '254727441469', '254722773978', 'denniswaruru93@gmail.com', 'muragupt@yahoo.com', '00200', '9859', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '33e75ff09dd601bbe69f351039152189', NULL, NULL, NULL, NULL, NULL, 'KINYANJUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (29, '20405004177', '2021', '254701364441', '254724801014', 'charmainengesa@gmail.com', 'marieatasha@gmail.com', '', '21283-00505', '', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6ea9ab1baa0efb9e19094440c317e21b', NULL, NULL, NULL, NULL, NULL, 'NGESA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (30, '23572205017', '2021', '254745238634', '254714951831', 'otienodavid050360@gmail.com', 'collinsomondi656@gmail.com', '30201', '4', 'ENDEBESS', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '34173cb38f07f89ddbebc2ac9128303f', NULL, NULL, NULL, NULL, NULL, 'ODHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (31, '27536202217', '2021', '254700780160', '254727525339', 'ikolotnelly@gmail.com', 'irenewanjiru@gmail.com', '20100', '1648', 'NAKURU', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c16a5320fa475530d9583c34fd356ef5', NULL, NULL, NULL, NULL, NULL, 'NELLY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (32, '27552001158', '2021', '254720049751', '254791421901', 'briannanduati@gmail.com', 'serahsonice596@gmail.com', '20117', '561', 'NAIVASHA', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6364d3f0f495b6ab9dcf8d3b5c6e0b01', NULL, NULL, NULL, NULL, NULL, 'NDUATI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (33, '27552001246', '2021', '254721788152', '254706123135', 'pmnmboya@gmail.com', 'pngaira@gmail.com', '50400', '313', 'BUSIA', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '182be0c5cdcd5072bb1864cdee4d3d6e', NULL, NULL, NULL, NULL, NULL, 'NGAIRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (34, '28500005210', '2021', '254723284488', '254723772159', 'kikayavallen@gmail.com', 'kikayavallen@gmail.com', '50300', '140', 'VIHIGA', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e369853df766fa44e1ed0ff613f563bd', NULL, NULL, NULL, NULL, NULL, 'MUSEDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (35, '30500020285', '2021', '254700262674', '254700262674', 'elizabethnasiekuk@gmail.com', 'kenedyparpai12@gmail.com', '00209', '153', 'LOITOKITOK', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1c383cd30b7c298ab50293adfecb7b18', NULL, NULL, NULL, NULL, NULL, 'ELIZABETH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (36, '34559212034', '2021', '254712450155', '254724483180', 'seretobecky@gmail.com', 'beatrice.melly@yahoo.com', '30200', '1552', 'KITALE', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '19ca14e7ea6328a42e0eb13d585e4c22', NULL, NULL, NULL, NULL, NULL, 'SERETO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (37, '39700001058', '2021', '254782151770', '254733745600', 'redian.nedscellian@gmail.com', 'migaelmwirikia@gmail.com', '', '62 KARURI', 'KIAMBU', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a5bfc9e07964f8dddeb95fc584cd965d', NULL, NULL, NULL, NULL, NULL, 'MWIRIKIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (38, '40735101011', '2021', '254703518868', '254703992059', 'okenyebrian64@gmail.com', 'mjackline173@gmail.com', '', '', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a5771bce93e200c36f7cd9dfd0e5deaa', NULL, NULL, NULL, NULL, NULL, 'BRIAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (39, '41731303018', '2021', '254725578020', '254717198286', 'osalavincent@gmail.com', 'osalabob@gmail.com', '00611', '77007', 'NAIROBI', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd67d8ab4f4c10bf22aa353e27879133c', NULL, NULL, NULL, NULL, NULL, 'JAGONGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (40, '42700005418', '2021', '254720055896', '254720055896', 'chrismoenga8@gmail.com', '', '40601', '120', 'BONDO', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd645920e395fedad7bbbed0eca3fe2e0', NULL, NULL, NULL, NULL, NULL, 'MOENGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (41, '42738101320', '2021', '254110410848', '254724781963', 'carrolineanyango04@gmail.com', '', '', '', '', '1263112', 'H12', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3416a75f4cea9109507cacd8e2f2aefc', NULL, NULL, NULL, NULL, NULL, 'OCHIENG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (42, '1100003085', '2021', '254779600222', '254729140619', 'nickhenry902@gmail.com', 'jmwamburi@hotmail.com', '40100', '1881', 'KISUMU', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a1d0c6e83f027327d8461063f4ac58a6', NULL, NULL, NULL, NULL, NULL, 'NICK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (43, '8237012045', '2021', '254722436610', '254720313521', 'nyamburakungu003@gmail.com', 'pkungu29@gmail.com', '00200', '72176', 'NAIROBI', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '17e62166fc8586dfa4d1bc0e1742c08b', NULL, NULL, NULL, NULL, NULL, 'KUNG''U', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (44, '11200003460', '2021', '254782556566', '254727219914', 'wonderwayne328@gmail.com', '', '', '', '', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f7177163c833dff4b38fc8d2872f1ec6', NULL, NULL, NULL, NULL, NULL, 'WAYNE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (45, '11200006284', '2021', '254791221180', '254711579385', 'bernicesalenoim@gmail.com', 'wanjericatherine256@gmail.comm', '20504', '6', 'NAIRAGIE ENKARE', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6c8349cc7260ae62e3b1396831a8398f', NULL, NULL, NULL, NULL, NULL, 'MEMUSI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (46, '12300001241', '2021', '254723880420', '254720338333', 'abdirizaksalat14@gmail.com', 'salaatkey@gmail.com', '70300', '320', 'MANDERA', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd9d4f495e875a2e075a1a4a6e1b9770f', NULL, NULL, NULL, NULL, NULL, 'ABDIRIZAK', 'ahmed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (47, '12315602038', '2021', '254757387744', '254711795316', 'paulmuambi59@gmail.com', 'justusmusyimi2018@gmail.com', '90119', 'BOX 312', 'MATUU', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '67c6a1e7ce56d3d6fa748ab6d9af3fd7', NULL, NULL, NULL, NULL, NULL, 'PAUL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (48, '14312302051', '2021', '254745987410', '254712407773', 'soniciousextinguisher@gm.com', 'josephnthiga23@gmail.com', '', '10', 'MARIMANTI', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '642e92efb79421734881b53e1e1b18b6', NULL, NULL, NULL, NULL, NULL, 'NTHIGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (49, '19313101034', '2021', '254112059199', '254798264761', 'allanjing65@gmail.com', 'gacongiteresa21@gmail.com', '60215', '120', 'MARIMANTI', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f457c545a9ded88f18ecee47145a72c0', NULL, NULL, NULL, NULL, NULL, 'JOSHUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (50, '20400002079', '2021', '254784082424', '254722102068', 'ellyrotino@gmail.com', 'jenniferkomen77@gmail.com', '30400', '510', 'KABARNET', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c0c7c76d30bd3dcaefc96f40275bdc0a', NULL, NULL, NULL, NULL, NULL, 'KOMEN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (51, '20402069003', '2021', '254799952640', '254796092406', 'thezubeirabdinasir242@gmail.com', '', '', '', '', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2838023a778dfaecdc212708f721b788', NULL, NULL, NULL, NULL, NULL, 'ZUBEIR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (52, '20403001205', '2021', '', '', '', '', '', '', '', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9a1158154dfa42caddbd0694a4e9bdc8', NULL, NULL, NULL, NULL, NULL, 'CHARANA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (53, '23500014502', '2021', '254721756161', '254722793062', 'sammykandagor92@gmail.com', 'marisoialice@gmail.com', '30300', '985', 'KAPSABET', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd82c8d1619ad8176d665453cfb2e55f0', NULL, NULL, NULL, NULL, NULL, 'KANDAGOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (54, '23528101004', '2021', '254713979984', '254713066778', 'iankipruto14@gmail.com', 'iankipruto14@gmail.com', '30200', '857', 'KITALE', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a684eceee76fc522773286a895bc8436', NULL, NULL, NULL, NULL, NULL, 'IAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (55, '25563102014', '2021', '254708866024', '254722633827', 'kipronoenock54@gmail.com', 'koskit15@gmail.com', '20406', '243', 'SOTIK', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b53b3a3d6ab90ce0268229151c9bde11', NULL, NULL, NULL, NULL, NULL, 'ENOCK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (56, '26509133059', '2021', '254728082651', '254726083464', 'hezronkimariny@gmail.com', '', '30400', '70', 'KABARNET', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9f61408e3afb633e50cdf1b20de6f466', NULL, NULL, NULL, NULL, NULL, 'KIMALINY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (57, '28512101101', '2021', '254711107042', '254722757533', 'royalekibii@gmail.com', 'richkoech@gmail.com', '', '', '', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '72b32a1f754ba1c09b3695e0cb6cde7f', NULL, NULL, NULL, NULL, NULL, 'KIBET', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (58, '34500010147', '2021', '254748441350', '254725371449', 'josephkarl7272@gmail.com', 'kennedyandende@gmail.com', '50239', '50239', 'NANGILI', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '66f041e16a60928b05a7e228a89c3799', NULL, NULL, NULL, NULL, NULL, 'JOSEPH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (59, '37631401099', '2021', '254795683315', '254720785055', 'mwangimaureen05@gmail.com', 'pmuthie@gmail.com', '10300', '476', 'KERUGOYA', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '093f65e080a295f8076b1c5722a46aa2', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (60, '38622203143', '2021', '254787759302', '254723153469', 'kmalembal@gmail.com', 'bkngoruse@yahoo.com', '50408', '87', 'KAMURIAI', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '072b030ba126b2f4b2374f342be9ed44', NULL, NULL, NULL, NULL, NULL, 'LILLIAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (61, '39737022010', '2021', '254103018905', '254720066963', 'shemfavour142@gmail.com', 'lydianjue19@gmail.com', '54', '24 PAWAKUCHE', 'KISUMU', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7f39f8317fbdb1988ef4c628eba02591', NULL, NULL, NULL, NULL, NULL, 'AJWANG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (62, '39741010008', '2021', '254768715473', '254700396539', 'emmapetite@gmail.com', 'theokunjpetite@gmail.com', '40100', '1171', 'KISUMU', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '44f683a84163b3523afe57c2e008bc8c', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (63, '39741010017', '2021', '254111394445', '254797599978', 'otarohillary@gmail.com', 'ruthmaritaoreni@gmail.com', '40100', '1307', 'KISUMU', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '03afdbd66e7929b125f8597834fa83a4', NULL, NULL, NULL, NULL, NULL, 'HILLARY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (64, '40700003004', '2021', '254758191983', '254719238859', 'marionmudeizi@gmail.com', 'sheilaadisa8@gmail.com', '00100', '6167', 'NAIROBI', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ea5d2f1c4608232e07d3aa3d998e5135', NULL, NULL, NULL, NULL, NULL, 'MARION', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (65, '42738105053', '2021', '254711811693', '254702596175', 'omondivictor223@gmail.com', '', '00', '00100', 'NAIROBI', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fc490ca45c00b1249bbe3554a4fdf6fb', NULL, NULL, NULL, NULL, NULL, 'OKAL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (66, '43700008515', '2021', '254724602971', '254710527902', 'billnyakundi@gmail.com', 'maxangassa@gmail.com', '', '', '', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3295c76acbf4caaed33c36b1b5fc2cb1', NULL, NULL, NULL, NULL, NULL, 'ORWOBA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (67, '44717101119', '2021', '254758409551', '254702314529', 'kenstarotirno@gmail.com', 'velmaatieno4009@gmail.com', '40300', '629', 'HOMABAY', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '735b90b4568125ed6c3f678819b6e058', NULL, NULL, NULL, NULL, NULL, 'KENSTAR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (68, '44739210806', '2021', '254110627648', '254721832165', 'claytonleroy9@gmail.com', 'muhonjao60@gmail.com', '20100', '13696', 'NAKURU', '1263113', 'I13', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a3f390d88e4c41f2747bfa2f1b5f87db', NULL, NULL, NULL, NULL, NULL, 'OGUTU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (69, '2110217033', '2021', '254718148055', '254793509775', 'mimuali314@gmail.com', 'fmwamsema@gmail.com', '80400', '50', 'UKUNDA', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '14bfa6bb14875e45bba028a21ed38046', NULL, NULL, NULL, NULL, NULL, 'MWAMSEMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (70, '3100001045', '2021', '254113104279', '254723736947', 'nimogish@gmail.com', 'wycemose01@gmail.com', '', '', '', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7cbbc409ec990f19c78c75bd1e06f215', NULL, NULL, NULL, NULL, NULL, 'NJOROGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (71, '7200014060', '2021', '254726169383', '254722859828', 'davidsonkamau098@gmail.com', 'kamausammy621@gmail.com', '', '283', 'GILGIL', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e2c420d928d4bf8ce0ff2ec19b371514', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (72, '7200014180', '2021', '254715823042', '254768474008', 'anthonykiama42@gmail.com', 'marynderitu019@gmail.com', '', '', '', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '32bb90e8976aab5298d5da10fe66f21d', NULL, NULL, NULL, NULL, NULL, 'WAGIITA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (73, '9200012147', '2021', '254777361392', '254721428438', 'josephkanyingi02@gmail.com', 'rwaijane@gmail.com', '', '', '', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd2ddea18f00665ce8623e36bd4e3c7c5', NULL, NULL, NULL, NULL, NULL, 'RWAI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1723, '20400002484', '2021', '254742237339', '254724043971', 'mwenchac@gmail.com', 'kimonib916@gmail.com', '00515', '485', 'NAIROBI', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8725fb777f25776ffa9076e44fcfd776', NULL, NULL, NULL, NULL, NULL, 'KIMONI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (74, '9203412164', '2021', '254740064767', '254721323188', 'frankwagura07@gmail.com', 'eunicewagura18@gmail.com', '', '', '', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ad61ab143223efbc24c7d2583be69251', NULL, NULL, NULL, NULL, NULL, 'NDIANGUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (75, '9222201046', '2021', '254768761491', '254797885738', 'winfredm485@gmail.com', 'winfredm485@gmail.com', '00200', '7529', 'NAIROBI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd09bf41544a3365a46c9077ebb5e35c3', NULL, NULL, NULL, NULL, NULL, 'MUTUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (76, '9222303160', '2021', '254115330114', '254791725408', 'johnmuulu50@gmail.com', 'natashamuricho@gmail.com', '', '', 'RUAI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fbd7939d674997cdb4692d34de8633c4', NULL, NULL, NULL, NULL, NULL, 'MUTHINI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (77, '10227301306', '2021', '254748333554', '254740402489', 'kingfierce318@gmail.com', 'shikoesther08@gmail.com', '00400', '22966', 'TOM MBOYA', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '28dd2c7955ce926456240b2ff0100bde', NULL, NULL, NULL, NULL, NULL, 'WANJIKU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (78, '11200003262', '2021', '254115756622', '254722794260', 'chriscoolnene@gmail.com', '', '', '00100', 'NAIROBI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '35f4a8d465e6e1edc05f3d8ab658c551', NULL, NULL, NULL, NULL, NULL, 'KIMANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (79, '11241001246', '2021', '254735208382', '254727981990', 'isabellagitahi24@gmail.com', 'mwithiga149@gmail.com', '', '', '', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd1fe173d08e959397adf34b1d77e88d7', NULL, NULL, NULL, NULL, NULL, 'GITAHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (80, '12301703219', '2021', '254700376617', '254700376617', 'alphonesmbithi@gmail.com', 'alphonesmbithi@gmail.com', '90100', '310', 'MACHAKOS', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f033ab37c30201f73f142449d037028d', NULL, NULL, NULL, NULL, NULL, 'NZIOKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (81, '12314208108', '2021', '254706146243', '254715537665', 'ngekidamaris@gmail.com', 'magdalenewayua015@gmail.com', '90200', '068', 'KITUI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '43ec517d68b6edd3015b3edc9a11367b', NULL, NULL, NULL, NULL, NULL, 'NGEKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (82, '13328101085', '2021', '254758610550', '254702403240', 'stephenmusyoki2005@gmail.com', 'n/a@gmail.com', '90401', '32', 'KYUSO', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9778d5d219c5080b9a6a17bef029331c', NULL, NULL, NULL, NULL, NULL, 'WAMBUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (83, '15300012288', '2021', '254799461776', '254715009830', 'gaturasusaan358@gmail.com', 'denekaiser5phpkenya@gmail.com', '60402', '133', 'IGOJI MERU', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fe9fc289c3ff0af142b6d3bead98a923', NULL, NULL, NULL, NULL, NULL, 'PATRICK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (84, '18300003025', '2021', '254787441959', '254707454310', 'kenyanoffer@gmail.com', 'danielmasila@gmail.com', '00100', '34567', 'NAIROBI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '68d30a9594728bc39aa24be94b319d21', NULL, NULL, NULL, NULL, NULL, 'DANIEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (85, '18323102266', '2021', '254769205401', '254713679341', 'onesmuskivala204@gmail.com', 'briomusyoka95@gmail.com', '90125', '50', 'KIKIMA', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3ef815416f775098fe977004015c6193', NULL, NULL, NULL, NULL, NULL, 'MUSYOKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (86, '18324301049', '2021', '254796809359', '254712946562', 'patrickndonyi22@gmail.com', 'patrickndonyi22@gmail.com', '90126', '41', 'KALAWA', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '93db85ed909c13838ff95ccfa94cebd9', NULL, NULL, NULL, NULL, NULL, 'MUTUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (87, '18324301050', '2021', '254750097202', '254725276073', 'davidkyl0777@gmail.com', 'carolynemaurice@gmail.com', '', '1165', 'KANGUNDO', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c7e1249ffc03eb9ded908c236bd1996d', NULL, NULL, NULL, NULL, NULL, 'MUTHENGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (88, '18348106001', '2021', '254718245921', '254714243916', 'samuelmulu52@gmail.com', 'ikuthamedicareclinic@yahoo.com', '90207', '83', 'IKUTHA', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2a38a4a9316c49e5a833517c45d31070', NULL, NULL, NULL, NULL, NULL, 'MUSEMBI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (89, '20406002313', '2021', '254759061192', '254722370216', 'nafisahkakawa@gmail.com', 'rahmamusa202@gmail.com', '52728', 'W82928', 'NAIROBI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7647966b7343c29048673252e490f736', NULL, NULL, NULL, NULL, NULL, 'KAKAWA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (90, '20410001112', '2021', '254770675544', '254705927507', 'kedogo1704@gmail.com', 'kedogo1704@gmail.com', '00100', 'NYAYO ESTATE, EMBAKA', 'NAIROBI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8613985ec49eb8f757ae6439e879bb2a', NULL, NULL, NULL, NULL, NULL, 'IMANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (91, '27500008295', '2021', '254748578122', '254725716450', 'koomekenneth896@gmail.com', 'koomekenneth896@gmail.com', '00519', '240', 'MLOLONGO', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '54229abfcfa5649e7003b83dd4755294', NULL, NULL, NULL, NULL, NULL, 'KOOME', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (92, '27511112126', '2021', '254707700444', '254723873471', 'isaacko@gmail.com', 'harrietombwara@gmail.com', '', '', 'NAKURU', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '92cc227532d17e56e07902b254dfad10', NULL, NULL, NULL, NULL, NULL, 'ISAACK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (93, '30556301301', '2021', '254740215322', '254724860243', 'flammerjossay14@gmail.com', '', '', '120-20300', 'NYAHURURU', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '98dce83da57b0395e163467c9dae521b', NULL, NULL, NULL, NULL, NULL, 'KIMANG''A', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (94, '31573206001', '2021', '254759874807', '254703586756', 'ambrosemute001@gmail.com', 'jmusya.m@gmail.com', '00200', '5940', 'NGONG', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f4b9ec30ad9f68f89b29639786cb62ef', NULL, NULL, NULL, NULL, NULL, 'DORCAS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (95, '35606101004', '2021', '254743289801', '254728792495', 'sakhwesebastian253@gmail.com', 'josephmusikoyo@gmail.com', '50409', '77', 'NAMBALE', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '812b4ba287f5ee0bc9d43bbf5bbe87fb', NULL, NULL, NULL, NULL, NULL, 'SAKHWE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (96, '36602102035', '2021', '254757911780', '254721402010', 'brightonekimtai12@gmail.com', 'mavelkot1@gmail.com', '50200', '85', 'BUNGOMA', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '26657d5ff9020d2abefe558796b99584', NULL, NULL, NULL, NULL, NULL, 'CHEREN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (97, '37600001290', '2021', '254743585423', '254711714872', 'eugeneokware9@gmail.com', 'shemokware276@mail.com', '50408', '239', 'KAMURIAI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e2ef524fbf3d9fe611d5a8e90fefdc9c', NULL, NULL, NULL, NULL, NULL, 'OKWARE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (98, '37600005013', '2021', '254794234558', '254790881492', 'matagaromercy17@gmail.com', 'linetwanjal@gmail.com', '00200', '62000', 'NAIROBI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ed3d2c21991e3bef5e069713af9fa6ca', NULL, NULL, NULL, NULL, NULL, 'MATAGARO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (99, '37608003226', '2021', '254115510355', '254799455358', 'dickenso8234@gmail.com', 'michatieno98@gmail.com', '80109', '337', 'MTWAPA', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ac627ab1ccbdb62ec96e702f07f6425b', NULL, NULL, NULL, NULL, NULL, 'OCHIENG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (100, '41743203097', '2021', '254704810421', '254704810421', 'mollyanneawuor@gmail.com', 'ngoyenick@gmail.com', '40300', '136', 'HOMA BAY', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f899139df5e1059396431415e770c6dd', NULL, NULL, NULL, NULL, NULL, 'AWUOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (101, '44707105032', '2021', '254720902125', '254720902125', 'patrickodira67@yahoo.com', 'patrickodira67@yahoo.com', '40400', '195-40400', 'MIGORI', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '38b3eff8baf56627478ec76a704e9b52', NULL, NULL, NULL, NULL, NULL, 'ODIRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (102, '44717103010', '2021', '254702358978', '254728513668', 'vincentwhite805@gmail.com', 'richardodum08@gmail.com', '40333', '59', 'RANGWE', '1263114', 'I14', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ec8956637a99787bd197eacd77acce5e', NULL, NULL, NULL, NULL, NULL, 'VINCENT', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (103, '7200009309', '2021', '254720882047', '254724656271', 'sallienonnie@gmail.com', 'rmutokaa@gmail.com', '20316', '94', 'NORTH KINANGOP', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6974ce5ac660610b44d9b9fed0ff9548', NULL, NULL, NULL, NULL, NULL, 'SALOME', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (104, '8200007183', '2021', '254753890305', '254790989342', 'morrisngumo7@gmail.com', 'evawangari80@gmail.com', '01034', '69', 'KIGANJO', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c9e1074f5b3f9fc8ea15d152add07294', NULL, NULL, NULL, NULL, NULL, 'WANGARI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (3818, '11200003195', '2021', '254110563165', '254722856166', 'riantoshawn@gmail.com', 'jamilanthenge@gmail.com', '', '314-01000', 'MACHAKOS', '1263177', 'K62', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b994697479c5716eda77e8e9713e5f0f', NULL, NULL, NULL, NULL, NULL, 'LETIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (105, '8200007219', '2021', '254716760680', '254715931683', 'muchiriandrew025@gmail.com', 'ruth1580@gmail.com', '00200', '68174', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '65b9eea6e1cc6bb9f0cd2a47751a186f', NULL, NULL, NULL, NULL, NULL, 'MUCHIRI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (106, '10200008016', '2021', '254791789499', '254722725273', 'michaelchege016@gmail.com', 'mwangichege63@gmail.com', '', '', '', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f0935e4cd5920aa6c7c996a5ee53a70f', NULL, NULL, NULL, NULL, NULL, 'CHEGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (107, '10200008261', '2021', '254734689841', '254722241870', 'mike5173tush@gmail.com', 'theresawairi@gmail.com', '', '', '', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a97da629b098b75c294dffdc3e463904', NULL, NULL, NULL, NULL, NULL, 'GATUNDU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (108, '11200001009', '2021', '254792830118', '254796167806', 'spicericky@protonmail.com', 'dominicoduge1962@gmail.com', '40606', '30', 'UGUNJA', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a3c65c2974270fd093ee8a9bf8ae7d0b', NULL, NULL, NULL, NULL, NULL, 'ODHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (109, '11200001043', '2021', '254700108512', '254722423336', 'machariamutua1@gmail.com', 'mbiliaugustus3@gmail.com', '00100', '101357', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2723d092b63885e0d7c260cc007e8b9d', NULL, NULL, NULL, NULL, NULL, 'MUTUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (110, '11200001399', '2021', '254710158562', '254715105040', 'petergathoga14@gmail.com', 'ruthgathoga2@gmail.com', '', '', '', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5f93f983524def3dca464469d2cf9f3e', NULL, NULL, NULL, NULL, NULL, 'KIMANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (111, '11200002105', '2021', '254731815995', '254722682437', 'godishkimberly@gmail.com', 'davidkirui69@gmail.com', '20200', '1496', 'KERICHO', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '698d51a19d8a121ce581499d7b701668', NULL, NULL, NULL, NULL, NULL, 'GODISH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (112, '11200002185', '2021', '254794121244', '254721863922', 'alakara11728@gmail.com', 'benomukaga@gmail.com', '20117', '130', 'NAIVASHA', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7f6ffaa6bb0b408017b62254211691b5', NULL, NULL, NULL, NULL, NULL, 'OMUSE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (113, '11200002309', '2021', '254707820282', '254724390313', 'kisilusherry@gmail.com', 'kisilukitainge@gmail.com', '30100', '1125', 'ELDORET', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '73278a4a86960eeb576a8fd4c9ec6997', NULL, NULL, NULL, NULL, NULL, 'KISILU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (114, '11200003047', '2021', '254742601374', '254722274257', 'stupendousjulius@gmail.com', 'mwakiomwagandi@gmail.com', '00100', '30820', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5fd0b37cd7dbbb00f97ba6ce92bf5add', NULL, NULL, NULL, NULL, NULL, 'MWAKIO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (115, '11200003210', '2021', '254713250148', '254721414437', 'irynwambaya@gmail.com', 'edwin.w.simiyu@gmail.com', '50204', '278 KIMILILILI', 'KIMILILI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2b44928ae11fb9384c4cf38708677c48', NULL, NULL, NULL, NULL, NULL, 'SIMIYU', 'wanjala', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (116, '11200003241', '2021', '254726108746', '254722919403', 'jaredmutua17@gmail.com', 'munyaodm@gmail.com', '00100', '', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c45147dee729311ef5b5c3003946c48f', NULL, NULL, NULL, NULL, NULL, 'MUNYAO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (117, '11200003286', '2021', '254712127990', '254721830012', 'skmephep@gmail.com', 'danmsupuko@gmail.com', '00623', '38999', 'PARKLANDS-NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eb160de1de89d9058fcb0b968dbbbd68', NULL, NULL, NULL, NULL, NULL, 'KEEPON', 'supuko', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (118, '11200003313', '2021', '254733659940', '254717720081', 'anthonynambiropeter@gmail.com', 'enambiro@gmail.com', '', 'N/A', '', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5ef059938ba799aaa845e1c2e8a762bd', NULL, NULL, NULL, NULL, NULL, 'MAMATI', 'nambiro', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (119, '11200003398', '2021', '254779446924', '254710370895', 'campnoch@gmail.com', 'haradaly@gmail.com', '', '', '', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '07e1cd7dca89a1678042477183b7ac3f', NULL, NULL, NULL, NULL, NULL, 'KAMBALE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (120, '11200003456', '2021', '254757654070', '254721365584', 'jasonbitega@gmail.com', 'bitegarich@gmail.com', '00300', '6537', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'da4fb5c6e93e74d3df8527599fa62642', NULL, NULL, NULL, NULL, NULL, 'NYAGESOA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (121, '11200006205', '2021', '254759626842', '254722400139', 'nyagakavera@gmail.com', 'monyoro2001@yahoo.com', '40200', '2611', 'KISII', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4c56ff4ce4aaf9573aa5dff913df997a', NULL, NULL, NULL, NULL, NULL, 'NYAGAKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (122, '11200006377', '2021', '254719234600', '254723250679', 'dianahamuli@gmail.com', 'dorahamuli@gmail.com', '00100', '53559', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a0a080f42e6f13b3a2df133f073095dd', NULL, NULL, NULL, NULL, NULL, 'AMULI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (123, '14300006014', '2021', '254716732614', '254728758105', 'murimimlvictor@gmail.com', 'safaricomcustomercareinfo@gmail.com', '0000', '88', 'CHOGORIA', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, 'NJAGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (124, '20400001361', '2021', '254729831752', '254724587171', 'koechbenjamin707@gmail.com', 'rotichesther65@gmail.com', '30100', '4606', 'ELDORET', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c8ffe9a587b126f152ed3d89a146b445', NULL, NULL, NULL, NULL, NULL, 'ROTICH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (125, '20400002077', '2021', '254114758055', '254710659263', 'muslimkigen891@gmail.com', 'everlynechebetkoech@gmail.com', '30300', '1144', 'KAPSABET', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3def184ad8f4755ff269862ea77393dd', NULL, NULL, NULL, NULL, NULL, 'KIGEN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (126, '20400003037', '2021', '254793409024', '254722879607', 'muendocicily@gmail.com', 'mutongajoel4@gmail.com', '00620', '6432800620 NAIROBI', 'ESSO PLAZA', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '069059b7ef840f0c74a814ec9237b6ec', NULL, NULL, NULL, NULL, NULL, 'MUENDO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (127, '20400003105', '2021', '254795097036', '254721273800', 'torienmwaura@gmail.com', 'swthiaka@yahoo.com', '00902', '1332', 'KIKUYU', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ec5decca5ed3d6b8079e2e7e7bacc9f2', NULL, NULL, NULL, NULL, NULL, 'MWAURA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (128, '20400003181', '2021', '254792141110', '254722803753', 'mwangangimuenipurity@gmail.com', 'sarahngesu@gmail.com', '00400', '14198', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '76dc611d6ebaafc66cc0879c71b5db5c', NULL, NULL, NULL, NULL, NULL, 'MWANGANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (129, '20400003215', '2021', '254790879301', '254726030544', 'joanck02@gmail.com', 'pkirui1973@gmail.com', '00100', '46429', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd1f491a404d6854880943e5c3cd9ca25', NULL, NULL, NULL, NULL, NULL, 'KIRUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (130, '20400003265', '2021', '254735475565', '254721283213', 'nelimamelani@gmail.com', 'ogato.moraa@gmail.com', '00200', '57007', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9b8619251a19057cff70779273e95aa6', NULL, NULL, NULL, NULL, NULL, 'ABIYAH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (131, '20400003296', '2021', '254759093164', '254722778495', 'mainamillicent68@gmail.com', 'esmatex44@gmail.com', '00100', '5102', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1afa34a7f984eeabdbb0a7d494132ee5', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (132, '20400006057', '2021', '254769953244', '254722988883', 'chaopurity104@gmail.com', 'munga.munyazi@gmail.com', '00100', '30152', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '65ded5353c5ee48d0b7d48c591b8f430', NULL, NULL, NULL, NULL, NULL, 'MSERI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (133, '20400008015', '2021', '', '', '', '', '', '', '', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9fc3d7152ba9336a670e36d0ed79bc43', NULL, NULL, NULL, NULL, NULL, 'ABDISHUKRI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (135, '20404006114', '2021', '254115003686', '254721588666', 'donovans064@gmail.com', 'vndeto@gmail.com', '00100', 'HOUSE NO. A6', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7f1de29e6da19d22b51c68001e7e0e54', NULL, NULL, NULL, NULL, NULL, 'DONOVAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (2169, '37607201214', '2021', '254717581342', '254794814900', 'wafulad6@gmail.com', '', '50200', '179', 'BUNGOMA', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bd0cc810b580b35884bd9df37c0e8b0f', NULL, NULL, NULL, NULL, NULL, 'WAFULA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1536, '12315501063', '2021', '254758423646', '254723483467', 'mutuacatheine@gmail.com', '', '90119', '64', 'MATUU', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b5a1fc2085986034e448d2ccc5bb9703', NULL, NULL, NULL, NULL, NULL, 'MUTUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (136, '20408006103', '2021', '254722271082', '254722790475', 'nyamburagmaingi@gmail.com', 'beatricemaingi@yahoo.com', '00505', '21389', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '42a0e188f5033bc65bf8d78622277c4e', NULL, NULL, NULL, NULL, NULL, 'MAINGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (137, '23574101008', '2021', '254742026633', '254726696568', 'mungaiaizak@gmail.com', 'mungaiaizak@gmail.com', '30200', '946', 'KITALE', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3988c7f88ebcb58c6ce932b957b6f332', NULL, NULL, NULL, NULL, NULL, 'MUNGAI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (138, '27552001041', '2021', '254716878171', '254722787271', 'ashleyadhiambo668@gmail.com', 'marilynokello@gmail.com', '00100', '30076', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '013d407166ec4fa56eb1e1f8cbe183b9', NULL, NULL, NULL, NULL, NULL, 'ADHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (139, '27552001256', '2021', '254731916931', '254726471489', 'samoeitab@gmail.com', 'ropjudy17@gmail.com', '30100', '5278', 'ELDORET', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e00da03b685a0dd18fb6a08af0923de0', NULL, NULL, NULL, NULL, NULL, 'SAMOEI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (140, '29500006068', '2021', '254745738345', '254722603418', 'wk.kathanzu@gmail.com', 'kenkkimuyu@gmail.com', '00100', '59741', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1385974ed5904a438616ff7bdb3f7439', NULL, NULL, NULL, NULL, NULL, 'KATHANZU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (141, '29500006317', '2021', '254703523377', '254758217588', 'machararogers@gmail.com', 'robertmachara@gmail.com', '20150', '603', 'NAKURU', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0f28b5d49b3020afeecd95b4009adf4c', NULL, NULL, NULL, NULL, NULL, 'MACHARA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (142, '29500006318', '2021', '254797683124', '254727883372', 'andatinigel@gmail.com', 'nekesaluvaha@gmail.com', '50100', '118', 'KAKAMEGA', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a8baa56554f96369ab93e4f3bb068c22', NULL, NULL, NULL, NULL, NULL, 'NIGEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (143, '31567216002', '2021', '254788316200', '254722259991', 'bigb10421@gmail.com', 'brian.b.simiyu@gmail.com', '00502', '412 KAREN', 'NAIROBI', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '903ce9225fca3e988c2af215d4e544d3', NULL, NULL, NULL, NULL, NULL, 'BUSIYILE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (144, '42700005370', '2021', '254758619393', '254715222203', 'kingoriantony636@gmail.com', 'phillisgithinji@gmail.com', '10104', '211', 'MWEIGA', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0a09c8844ba8f0936c20bd791130d6b6', NULL, NULL, NULL, NULL, NULL, 'GITHINJI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (145, '42700005553', '2021', '254791580885', '254713918727', 'amondepheny533@gmail.com', 'amondepheny533@gmail.com', '30100', '9626', 'ELDORET', '1263115', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2b24d495052a8ce66358eb576b8912c8', NULL, NULL, NULL, NULL, NULL, 'GEORGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (146, '3121206012', '2021', '793871578', '72583044', 'jemomburu12@gmail.com', 'emburu479@gmail.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a5e00132373a7031000fd987a3c9f87b', NULL, NULL, NULL, NULL, NULL, 'NJOROGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (147, '3126101073', '2021', '254706115088', '254706115088', 'ashumalucy@gmail.com', 'ashumalucy@gmail.com', '80100', '90419', 'MOMBASA', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8d5e957f297893487bd98fa830fa6413', NULL, NULL, NULL, NULL, NULL, 'ALWIYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (148, '3126101139', '2021', '254773470576', '254707550551', 'azharahmedtakoy4444@gmail.com', 'hilal990@gmail.com', '', '', 'RONGAI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '47d1e990583c9c67424d369f3414728e', NULL, NULL, NULL, NULL, NULL, 'TAKOY', 'sheikh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (149, '3126101183', '2021', '254740749084', '254722729219', 'misbanto2018@gmail.com', '', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f2217062e9a397a1dca429e7d70bc6ca', NULL, NULL, NULL, NULL, NULL, 'MOHAMED', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (150, '8200007164', '2021', '254794681354', '254726031372', 'johnsonkariuki789@gmail.com', 'prissykimani8@gmail.com', '20100', '69-10102', 'NAKURU', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7ef605fc8dba5425d6965fbd4c8fbe1f', NULL, NULL, NULL, NULL, NULL, 'KARIUKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (151, '8200007307', '2021', '254706869313', '254729143011', 'kerrymunene67@gmail.com', 'itinyaimukaba@gmail.com', '60600', '636', 'MAUA', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a8f15eda80c50adb0e71943adc8015cf', NULL, NULL, NULL, NULL, NULL, 'MUKABA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (152, '8200007339', '2021', '254700048277', '254722813076', 'colobenz9@gmail.com', 'njorogewachai@gmail.com', '00101', '102391', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '37a749d808e46495a8da1e5352d03cae', NULL, NULL, NULL, NULL, NULL, 'WACHAI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (153, '10200008155', '2021', '254779674850', '254722882739', 'jameskio10476@gmail.com', 'peterkamau.pk@gmail.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b3e3e393c77e35a4a3f3cbd1e429b5dc', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (154, '10208311059', '2021', '254115027193', '254722494276', 'nick.kmwangi@gmail.com', 'dominic.mkariuki@gmail.com', '00101', '103318', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1d7f7abc18fcb43975065399b0d1e48e', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (155, '11200001046', '2021', '254762723868', '254701834397', 'ernestmwathi3101@gmail.com', 'mwathikamau@gmail.com', '20303', '28-20300 NYAHURURU', 'NYAHURURU', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2a79ea27c279e471f4d180b08d62b00a', NULL, NULL, NULL, NULL, NULL, 'MWATHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (156, '11200001091', '2021', '254705078990', '254722456330', 'collinswangira17@gmail.com', 'snwangira@gmail.com', '00100', '24494', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1c9ac0159c94d8d0cbedc973445af2da', NULL, NULL, NULL, NULL, NULL, 'WANGIRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (157, '11200001168', '2021', '254742426037', '254721575332', 'hikemulei@gmail.com', 'cosnguta@gmail.com', '90100', '2256', 'MACHAKOS', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6c4b761a28b734fe93831e3fb400ce87', NULL, NULL, NULL, NULL, NULL, 'NGUTA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (158, '11200001192', '2021', '254795663603', '254721232697', 'milgoronald@gmail.com', 'joelmilgo885@gmail.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '06409663226af2f3114485aa4e0a23b4', NULL, NULL, NULL, NULL, NULL, 'MILGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (159, '11200001248', '2021', '254762381762', '254721484394', 'konradmaina@gmail.com', 'mainacharles956@gmail.com', '10105', '124', 'NAROMORU', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '140f6969d5213fd0ece03148e62e461e', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (160, '11200001395', '2021', '254725717419', '254722805007', 'krisskimanzi@gmail.com', 'lincoln.munyao@gmail.com', '00100', '35', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b73ce398c39f506af761d2277d853a92', NULL, NULL, NULL, NULL, NULL, 'KIMANZI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (161, '11200002116', '2021', '254797554472', '254715012323', 'cloysavannah002@gmail.com', 'naodera1@gmail.com', '40103', '1411', 'KISUMU', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bd4c9ab730f5513206b999ec0d90d1fb', NULL, NULL, NULL, NULL, NULL, 'AKUDO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (162, '11200002117', '2021', '254798870240', '254720343431', 'nicolemoraa69@gmail.com', 'nicolemoraa69@gmail.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '82aa4b0af34c2313a562076992e50aa3', NULL, NULL, NULL, NULL, NULL, 'MORARA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (163, '11200002183', '2021', '254741283717', '254718437620', 'terrymuseve3@gmail.com', 'kt200saginah@gmail.com', '30100', '3025', 'ELDORET', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0777d5c17d4066b82ab86dff8a46af6f', NULL, NULL, NULL, NULL, NULL, 'MUSEVE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (164, '11200002221', '2021', '254706367640', '254722540985', 'amyaao447@gmail.com', 'o.oduor@yahoo.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fa7cdfad1a5aaf8370ebeda47a1ff1c3', NULL, NULL, NULL, NULL, NULL, 'ODUOR', 'akinyi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (165, '11200002237', '2021', '254769060429', '254722987602', 'omotograce09@gmail.com', 'lnyabiage@yahoo.com', '40100', '3408', 'KISUMU', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9766527f2b5d3e95d4a733fcfb77bd7e', NULL, NULL, NULL, NULL, NULL, 'GRACE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (166, '11200003147', '2021', '254115607127', '254722257559', 'johnalvinkimani675@gmail.com', 'geraldkaranja67@gmail.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7e7757b1e12abcb736ab9a754ffb617a', NULL, NULL, NULL, NULL, NULL, 'JOHN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (167, '11200003180', '2021', '254799937799', '254728539291', 'somiimike7@gmail.com', '', '60200', '683', 'MERU', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5878a7ab84fb43402106c575658472fa', NULL, NULL, NULL, NULL, NULL, 'MURIUKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (169, '11200003305', '2021', '254713146052', '254722350981', 'alexgikandi05@gmail.com', 'wambuimwangi79@gmail.com', '', '314-01000', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3636638817772e42b59d74cff571fbb3', NULL, NULL, NULL, NULL, NULL, 'GIKANDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (170, '11200003378', '2021', '254758084869', '254722289623', 'njiirucaleb57@gmail.com', 'njiiru@gmail.com', '00100', '0727805888', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '149e9677a5989fd342ae44213df68868', NULL, NULL, NULL, NULL, NULL, 'NJIIRU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (171, '11200006140', '2021', '254762490692', '254722744332', 'cherisendungu35@gmail.com', 'bekisa2013@gmail.com', '00100', '297-00515', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a4a042cf4fd6bfb47701cbc8a1653ada', NULL, NULL, NULL, NULL, NULL, 'NDUNGU', 'nyaruai', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (172, '11200006370', '2021', '254780716202', '254722377104', 'akinyinicoe40@gmail.com', 'erickkawere@gmail.com', '', '9759', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1ff8a7b5dc7a7d1f0ed65aaa29c04b1e', NULL, NULL, NULL, NULL, NULL, 'NICOLE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (173, '11200006403', '2021', '254743495973', '254721467678', 'wanjiruxmaniii@gmail.com', 'enkimani70@gmail.com', '00200', '20026-00200', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f7e6c85504ce6e82442c770f7c8606f0', NULL, NULL, NULL, NULL, NULL, 'KIMANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (174, '11207102026', '2021', '254725583191', '254725583191', 'margaretchege97@gmail.com', 'margaretchege97@gmail.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bf8229696f7a3bb4700cfddef19fa23f', NULL, NULL, NULL, NULL, NULL, 'CHEGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (521, '44744114153', '2021', '254769567994', '254727643166', 'josphatotieno@gmail.com', '', '40400', '101', 'SUNA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '07563a3fe3bbe7e3ba84431ad9d055af', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (175, '11207112124', '2021', '254790717061', '254710285252', 'mfasendwa@gmail.com', 'sendwa.shisuvili@mpesafoundationacademy.ac.ke', '98', 'KAMURIAI', 'MALABA', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '82161242827b703e6acf9c726942a1e4', NULL, NULL, NULL, NULL, NULL, 'ROONY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (176, '11235127052', '2021', '', '', '', '', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '38af86134b65d0f10fe33d30dd76442e', NULL, NULL, NULL, NULL, NULL, 'KIPCHUMBA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (177, '14300006109', '2021', '254740087175', '254710254424', 'mwirigiian2352@gmail.com', 'karwithajane1977@gmail.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '96da2f590cd7246bbde0051047b0d6f7', NULL, NULL, NULL, NULL, NULL, 'KATHURIMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (178, '15300002037', '2021', '254715615671', '254723327606', 'mwaniki.laban@gmail.com', 'mwaniki.laban@gmail.com', '10104', '132', 'MWEIGA', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8f85517967795eeef66c225f7883bdcb', NULL, NULL, NULL, NULL, NULL, 'MWANIKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (179, '20400002190', '2021', '254791071437', '254722300881', 'mosesleon2004@gmail.com', 'gpndirangu@gmail.com', '00100', '51312', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8f53295a73878494e9bc8dd6c3c7104f', NULL, NULL, NULL, NULL, NULL, 'NDIRANGU', 'kimani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (180, '20400002314', '2021', '254785607335', '254718007445', 'maisibapeter11@gmail.com', 'schoma2013@gmail.com', '00100', '28566', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '045117b0e0a11a242b9765e79cbf113f', NULL, NULL, NULL, NULL, NULL, 'NYANCHOGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (181, '20400003018', '2021', '254790237082', '254722729489', 'patriciahmurori56@gmail.com', 'timothyngumi@yahoo.com', '01000', '1428', 'THIKA', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fc221309746013ac554571fbd180e1c8', NULL, NULL, NULL, NULL, NULL, 'MURORI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (182, '20400003027', '2021', '254715942099', '254721945717', 'nzomojoannendunge@gmail.com', 'nancylibenzomo@gmail.com', '327', '327-00204', 'ATHIRIVER', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4c5bde74a8f110656874902f07378009', NULL, NULL, NULL, NULL, NULL, 'NZOMO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (183, '20400003088', '2021', '254786678697', '254722343406', 'kam.anita.04@gmail.com', 'kjkimari@gmail.com', '01000', '7067', 'THIKA', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cedebb6e872f539bef8c3f919874e9d7', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (184, '20400003177', '2021', '254701195201', '254723984940', 'agosh736@gmail.com', 'mugwangaeu@yahoo.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6cdd60ea0045eb7a6ec44c54d29ed402', NULL, NULL, NULL, NULL, NULL, 'CECILIA', 'oduori', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (185, '20400003186', '2021', '254736400164', '254722698076', 'yunukenye@gmail.com', 'mkikuvi@gmail.com', '00200', '74216', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eecca5b6365d9607ee5a9d336962c534', NULL, NULL, NULL, NULL, NULL, 'OKENYE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (186, '20400003223', '2021', '254743607029', '254722477780', 'muna.abbie004@gmail.com', 'justin.muna2012@gmail.com', '00232', '1525', 'RUIRU', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9872ed9fc22fc182d371c3e9ed316094', NULL, NULL, NULL, NULL, NULL, 'MUNA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (187, '20400003252', '2021', '254774977031', '254727630168', 'maryannwambua302@gmail.com', 'ndukuwinny@gmail.com', '00200', '1779', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '31fefc0e570cb3860f2a6d4b38c6490d', NULL, NULL, NULL, NULL, NULL, 'WAMBUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (188, '20400004012', '2021', '254745525971', '254733635957', 'timothywanyama859@gmail.com', 'estherauma45@gmail.com', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9dcb88e0137649590b755372b040afad', NULL, NULL, NULL, NULL, NULL, 'WANYAMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (189, '20404006026', '2021', '254796172013', '254722126290', 'danxxgene@gmail.com', 'wamboyebetty@gmail.com', '20100', '1283', 'NAKURU', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a2557a7b2e94197ff767970b67041697', NULL, NULL, NULL, NULL, NULL, 'ANDETE', 'kakai', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (190, '20404006085', '2021', '', '', '', '', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cfecdb276f634854f3ef915e2e980c31', NULL, NULL, NULL, NULL, NULL, 'MONG''ARE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (191, '20404006100', '2021', '254746002952', '254720832072', 'noelmn32@gmail.com', 'twnganga@gmail.com', '00200', '62604-00200', 'NAIROBI KENYA', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0aa1883c6411f7873cb83dacb17b0afc', NULL, NULL, NULL, NULL, NULL, 'NOEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (192, '20406020015', '2021', '254728591347', '254722717179', 'kanyimwai35@gmail.com', 'tom.kanyi@yahoo.com', '00100', '9709', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '58a2fc6ed39fd083f55d4182bf88826d', NULL, NULL, NULL, NULL, NULL, 'MWAI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (193, '20406020053', '2021', '', '', '', '', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bd686fd640be98efaae0091fa301e613', NULL, NULL, NULL, NULL, NULL, 'MANJARI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (194, '20408006067', '2021', '254736657329', '254723952325', 'onerfiona@gmail.com', 'charityoner@gmail.com', '00505', '21389', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a597e50502f5ff68e3e25b9114205d4a', NULL, NULL, NULL, NULL, NULL, 'ONER', 'akinyi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (196, '20410001270', '2021', '254745357696', '254722802406', 'nyabandasandra@gmail.com', 'nyawuor@yahoo.com', '00202', '29728', 'NRB', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '084b6fbb10729ed4da8c3d3f5a3ae7c9', NULL, NULL, NULL, NULL, NULL, 'NYABANDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (197, '27500002140', '2021', '254792275468', '254715516284', 'munyasiafrank79@gmail.com', '', '', '', '', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '85d8ce590ad8981ca2c8286f79f59954', NULL, NULL, NULL, NULL, NULL, 'MUNYASIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (198, '27552001211', '2021', '254713899646', '254722289752', 'edster400@gmail.com', 'ronojohn7791@gmail.com', '30100', '6235 ELDORET', 'ELDORET', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0e65972dce68dad4d52d063967f0a705', NULL, NULL, NULL, NULL, NULL, 'EDWARD', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (199, '27552001290', '2021', '254722700333', '254722700333', 'henryivuti@gmail.com', '', '00507', '30', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '84d9ee44e457ddef7f2c4f25dc8fa865', NULL, NULL, NULL, NULL, NULL, 'NZIOKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (200, '27552001333', '2021', '254743130075', '254714263126', 'alvinmaseno@gmail.com', '', '00200', '9267', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3644a684f98ea8fe223c713b77189a77', NULL, NULL, NULL, NULL, NULL, 'NYANARO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (201, '29500006018', '2021', '254745948874', '254728809710', 'owuorgeorge93@gmail.com', 'florencica@gmail.com', '40323', '22 OGONGO', 'OGONGO', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '757b505cfd34c64c85ca5b5690ee5293', NULL, NULL, NULL, NULL, NULL, 'OKOTH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (202, '29500006184', '2021', '254110858964', '254720462225', 'kiruitonny00@gmail.com', '', '20200', '594', 'KERICHO', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '854d6fae5ee42911677c739ee1734486', NULL, NULL, NULL, NULL, NULL, 'KIRUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (203, '36600004054', '2021', '254711478822', '254723909018', 'murumba87@gmail.com', 'rosenabwala1@gmail.com', '30200', '4161', 'KITALE', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e2c0be24560d78c5e599c2a9c9d0bbd2', NULL, NULL, NULL, NULL, NULL, 'DENNIS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (204, '36600004217', '2021', '254745273807', '254723979912', 'phosaitzzy254@gmail.com', 'johnshiuka@gmail.com', '30205', '186', 'MATUNDA', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '274ad4786c3abca69fa097b85867d9a4', NULL, NULL, NULL, NULL, NULL, 'AITON', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (205, '36600004327', '2021', '254746585994', '254723102619', 'singiladerrick01@gmail.com', 'sylvianekesa@gmail.com', '000', '434', 'WEBUYE', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eae27d77ca20db309e056e3d2dcd7d69', NULL, NULL, NULL, NULL, NULL, 'MAKOKHA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (206, '41742105484', '2021', '254758726591', '254769414703', 'opapahezron@gmail.com', 'opapajefferson@gmail.com', '40300', '13', 'KANDIEGE', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7eabe3a1649ffa2b3ff8c02ebfd5659f', NULL, NULL, NULL, NULL, NULL, 'ANGIRO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (207, '42749107001', '2021', '254795816350', '254721541385', 'denisotieno287@gmail.com', 'benjaminowuor@yahoo.com', '00100', '00100', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '69adc1e107f7f7d035d7baf04342e1ca', NULL, NULL, NULL, NULL, NULL, 'ODONGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (208, '44700006018', '2021', '254722241540', '254722241540', 'hscovince@gmail.com', 'ipaulochie@gmail.com', '40326', '66', 'RODI-KOPANY', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '091d584fced301b442654dd8c23b3fc9', NULL, NULL, NULL, NULL, NULL, 'HASTINGS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (209, '7216101003', '2021', '254791326511', '254720260559', 'poarboy.c@gmail.com', 'andreawokabi@gmail.com', '', '', 'NAIVASHA', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b1d10e7bafa4421218a51b1e1f1b0ba2', NULL, NULL, NULL, NULL, NULL, 'CHEGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6940, '27538208164', '2021', '', '', '', '', '', '', '', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f75b757d3459c3e93e98ddab7b903938', NULL, NULL, NULL, NULL, NULL, 'KANG''ETHE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (210, '8200007098', '2021', '254793880379', '254722707655', 'ndoriawalter13@gmail.com', 'ziporahndoria21@gmail.com', '00609', '62014-00100', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6f3ef77ac0e3619e98159e9b6febf557', NULL, NULL, NULL, NULL, NULL, 'HIUKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (211, '8200007167', '2021', '254794906996', '254715735710', 'wachiradickson19@gmail.com', 'stephenngure99@gmail.com', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eb163727917cbba1eea208541a643e74', NULL, NULL, NULL, NULL, NULL, 'WACHIRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (212, '8200007239', '2021', '254707763422', '254721359960', 'sephkaloki@gmail.com', 'alicekaloki2@gmail.com', '00100', '4665', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1534b76d325a8f591b52d302e7181331', NULL, NULL, NULL, NULL, NULL, 'JOSEPH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (213, '8200010140', '2021', '254794720311', '254711256719', 'iamshiru18@gmail.com', 'migwimiss82@gmail.com', '00100', '00100', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '979d472a84804b9f647bc185a877a8b5', NULL, NULL, NULL, NULL, NULL, 'WINNIE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (214, '9200012200', '2021', '254778018885', '254722261128', 'njugunagatimu98@gmail.com', 'muirurianthony71@g.mail.com', '0100', '41584 NAIROBI', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ca46c1b9512a7a8315fa3c5a946e8265', NULL, NULL, NULL, NULL, NULL, 'NJUGUNA', 'gatimu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (215, '10227301055', '2021', '254722747664', '254722747664', 'edwinnjuru@outlook.com', 'edwinnjuru@outlook.com', '00000', '', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3b8a614226a953a8cd9526fca6fe9ba5', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (216, '11200001020', '2021', '254797519478', '254722984179', 'wanjohivictor35@gmail.com', 'james_wanjohi@yahoo.com', '00100', '12294', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '45fbc6d3e05ebd93369ce542e8f2322d', NULL, NULL, NULL, NULL, NULL, 'WANJOHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (217, '11200001115', '2021', '254719320587', '254725011531', 'allanodhiz254@gmail.com', 'jiokello@gmail.com', '00800', '66430', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '63dc7ed1010d3c3b8269faf0ba7491d4', NULL, NULL, NULL, NULL, NULL, 'OKELLO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (218, '11200001150', '2021', '254736944470', '254712595496', 'godwinsimiyu113@gmail.com', 'electinewanyonyi@gmail.com', '80105', '59', 'KALOLENI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e96ed478dab8595a7dbda4cbcbee168f', NULL, NULL, NULL, NULL, NULL, 'WAFULA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (219, '11200001394', '2021', '254799365556', '254722756380', 'hefty9471@gmail.com', 'asugaha@gmail.com', '00200', '57888', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c0e190d8267e36708f955d7ab048990d', NULL, NULL, NULL, NULL, NULL, 'ONGICHO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (220, '11200003037', '2021', '254781290457', '254720170913', 'emmanuelokoth21037@gmail.com', 'adoyohagaih@gmail.com', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ec8ce6abb3e952a85b8551ba726a1227', NULL, NULL, NULL, NULL, NULL, 'OWITI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (221, '11200003092', '2021', '254717205175', '254702161008', 'isaacgithinji003@gmail.com', 'hamisimrss@gmail.com', '00217', '363', 'LIMURU', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '060ad92489947d410d897474079c1477', NULL, NULL, NULL, NULL, NULL, 'GITHINJI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (222, '11200003279', '2021', '254774798452', '254714342261', 'brianosebe8@gmail.com', 'nmakorikelvin@gmail.com', '00300', '6311', 'NAIROBIBB', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bcbe3365e6ac95ea2c0343a2395834dd', NULL, NULL, NULL, NULL, NULL, 'MAKORI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (223, '11200003284', '2021', '254710425295', '254722255001', 'w.m.mwadime@gmail.com', 'esther.mwadime@gmail.com', '', '', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '115f89503138416a242f40fb7d7f338e', NULL, NULL, NULL, NULL, NULL, 'MWADIME', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (224, '11200003334', '2021', '254113539073', '254723314398', 'richardsimon093@gmail.com', 'stellasimon21@gmail.com', '0900', '204', 'KITUI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '13fe9d84310e77f13a6d184dbf1232f3', NULL, NULL, NULL, NULL, NULL, 'SIMON', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (225, '11207102180', '2021', '254757537141', '254717735638', 'jane1234kimani@gmail.com', 'jane1234kimani@gmail.com', '1030', '21', 'GATUNDU', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd1c38a09acc34845c6be3a127a5aacaf', NULL, NULL, NULL, NULL, NULL, 'KABURA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (226, '12300001093', '2021', '254114416334', '254724244597', 'brian.being.usefull@gmail.com', 'njirueric@gmail.com', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9cfdf10e8fc047a44b08ed031e1f0ed1', NULL, NULL, NULL, NULL, NULL, 'NJIRU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (227, '12300001154', '2021', '254726540912', '254726540912', 'machakosschool@yahoo.com', '', '', '235', 'KANGARU', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '705f2172834666788607efbfca35afb3', NULL, NULL, NULL, NULL, NULL, 'KINYANJUI', 'muchai', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (228, '14300006194', '2021', '254110444099', '254726553165', 'kellyjunior6386@gmail.com', 'kerubocashline@gmail.com', '00100', '30376', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '74db120f0a8e5646ef5a30154e9f6deb', NULL, NULL, NULL, NULL, NULL, 'OMENGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (229, '18346101026', '2021', '254757766095', '254706208719', 'samsonwisdom162@gmail.com', 'agnetamukuva@yahoo.com', '90207', '8-90207', 'IKUTHA', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '57aeee35c98205091e18d1140e9f38cf', NULL, NULL, NULL, NULL, NULL, 'WISDOM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (230, '19300004005', '2021', '254745525211', '254722503862', 'mumo.junep96@gmail.com', 'tripplepmumo@gmail.com', '00200', '67868', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6da9003b743b65f4c0ccd295cc484e57', NULL, NULL, NULL, NULL, NULL, 'MUMO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (231, '19308301009', '2021', '254743222488', '254792725156', 'solomumo04@gmail.com', 'mutisodaniel2018@g-mail.com', '90122', '91', 'KALAMBA', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9b04d152845ec0a378394003c96da594', NULL, NULL, NULL, NULL, NULL, 'MUTISO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1724, '20400004062', '2021', '254726323759', '254726323759', 'ritanyanzi31@gmail.com', 'ritanyanzi31@gmail.com', '0100', '1', 'NAIROBI', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '62889e73828c756c961c5a6d6c01a463', NULL, NULL, NULL, NULL, NULL, 'OJWANG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (232, '20400001124', '2021', '254784012750', '254727104143', 'ckimutai70@gmail.com', 'zeddychelangat@yahoo.com', '20100', '539', 'NAKURU', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'be83ab3ecd0db773eb2dc1b0a17836a1', NULL, NULL, NULL, NULL, NULL, 'KIMUTAI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (233, '20400001370', '2021', '254114144941', '254726392800', 'marymukai8@gmail.com', 'jacobmwololo1980@gmail.com', '80100', '40613', 'MOMBASA GPO', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e165421110ba03099a1c0393373c5b43', NULL, NULL, NULL, NULL, NULL, 'MWOLOLO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (234, '20400002160', '2021', '254769118545', '254720621905', 'ekamene22@gmail.com', 'ekamene22@gmail.com', '90207', '8', 'IKUTHA', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '289dff07669d7a23de0ef88d2f7129e7', NULL, NULL, NULL, NULL, NULL, 'SIMON', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (235, '20400002323', '2021', '254746539071', '254748255883', 'bradleyanjere001@gmail.com', 'bradforfawesom@gmail.com', '00505', '1339', 'BURUBURU', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '577ef1154f3240ad5b9b413aa7346a1e', NULL, NULL, NULL, NULL, NULL, 'ANJERE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (237, '20400002396', '2021', '254769958894', '254729979004', 'albanusademba@gmail.com', '', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '539fd53b59e3bb12d203f45a912eeaf2', NULL, NULL, NULL, NULL, NULL, 'ASWANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (238, '20400002511', '2021', '254750023364', '254722223668', 'jerryokuto713@gmail.com', 'allanokuto@gmail.com', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ac1dd209cbcc5e5d1c6e28598e8cbbe8', NULL, NULL, NULL, NULL, NULL, 'OKUTO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (239, '20400003233', '2021', '254782518153', '254720756124', 'waitherachebet@gmail.com', 'justchemosit@gmail.com', '00101', '101015', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '555d6702c950ecb729a966504af0a635', NULL, NULL, NULL, NULL, NULL, 'CHEMOSIT', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (240, '20400004018', '2021', '254774023636', '254700500245', 'ryanmwandishi@gmail.com', 'mwandiship@gmail.com', '', '', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '335f5352088d7d9bf74191e006d8e24c', NULL, NULL, NULL, NULL, NULL, 'MWANDISHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (241, '20400006082', '2021', '254713639816', '254721782619', 'birminghambitengo@gmail.com', 'christinemoraa59@gmail.com', '00100', '30152', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f340f1b1f65b6df5b5e3f94d95b11daf', NULL, NULL, NULL, NULL, NULL, 'BITENGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (242, '20400006102', '2021', '254748829808', '254727222354', 'carolinembevim0748829808@gmail.com', 'mariettandungem@gmail.com', '90102', '37', 'MWALA', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e4a6222cdb5b34375400904f03d8e6a5', NULL, NULL, NULL, NULL, NULL, 'MBEVI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (243, '20400006176', '2021', '254718573392', '254728814492', 'brijohtich@gmail.com', 'evachep1234@gmail.com', '', '30152-00100', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cb70ab375662576bd1ac5aaf16b3fca4', NULL, NULL, NULL, NULL, NULL, 'BRIGID', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (244, '20400008003', '2021', '254798678528', '254721317828', 'joshuafundi12@gmail.com', '', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9188905e74c28e489b44e954ec0b9bca', NULL, NULL, NULL, NULL, NULL, 'MAKAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (246, '20404006055', '2021', '254751333286', '254717040294', 'hekima360@gmail.com', 'jajawachu@gmail.com', '00242', '626', 'KITENGELA', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '38db3aed920cf82ab059bfccbd02be6a', NULL, NULL, NULL, NULL, NULL, 'HEKIMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (247, '20404006119', '2021', '254700869740', '254722983055', 'romanussimonangina@gmail.com', 'toyugi@yahoo.com', '00100', '2920', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3cec07e9ba5f5bb252d13f5f431e4bbb', NULL, NULL, NULL, NULL, NULL, 'ANG''INA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (248, '20406009016', '2021', '254715327470', '254725431391', 'emmanueljuma4531@gmail.com', 'gabrielosore@gmail.com', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '621bf66ddb7c962aa0d22ac97d69b793', NULL, NULL, NULL, NULL, NULL, 'JUMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (249, '20406014001', '2021', '', '', '', '', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '077e29b11be80ab57e1a2ecabb7da330', NULL, NULL, NULL, NULL, NULL, 'ADEN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (250, '26500001394', '2021', '254710266005', '254722687857', 'mitchelnafula8@gmail.com', 'wekesamw@yahoo.com', '00100', '2220', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6c9882bbac1c7093bd25041881277658', NULL, NULL, NULL, NULL, NULL, 'WANJALA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (251, '26500001402', '2021', '254745953338', '254724712319', 'velmaketrraa@gmail.com', 'cngwawe@gmail.com', '00200', '4786', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '19f3cd308f1455b3fa09a282e0d496f4', NULL, NULL, NULL, NULL, NULL, 'LUKAYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (252, '27552001084', '2021', '254707351617', '254714562830', 'eddyngetich5@gmail.com', '', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '03c6b06952c750899bb03d998e631860', NULL, NULL, NULL, NULL, NULL, 'NGETICH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (253, '27552001265', '2021', '254703555170', '254722915657', 'njerumarshall@gmail.com', 'evahwairmamuita@gmail.com', '00202', '19942', 'NBO', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c24cd76e1ce41366a4bbe8a49b02a028', NULL, NULL, NULL, NULL, NULL, 'NGARI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (254, '27552001293', '2021', '', '', '', '', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c52f1bd66cc19d05628bd8bf27af3ad6', NULL, NULL, NULL, NULL, NULL, 'OKETCH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (255, '28500005051', '2021', '254718152520', '254724909594', 'neemapeter877@gmail.com', 'lornabasanga@gmail.com', '40402', '32', 'MIGORI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fe131d7f5a6b38b23cc967316c13dae2', NULL, NULL, NULL, NULL, NULL, 'NEEMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (256, '28500006013', '2021', '254791935798', '254722281329', 'henrykubs@gmail.com', 'jkubesh@gmail.com', '', '', '', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f718499c1c8cef6730f9fd03c8125cab', NULL, NULL, NULL, NULL, NULL, 'KUBENDE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (257, '29500006175', '2021', '254792953779', '254721512447', 'ogillohenry@gmail.com', 'ogillodom@gmail.com', '40122', '14', 'AWASI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd96409bf894217686ba124d7356686c9', NULL, NULL, NULL, NULL, NULL, 'HENRY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (258, '29500006254', '2021', '254769664119', '254720861264', 'kibeteliud51@gmail.com', 'jimkib9@gmail.com', '20200', '223', 'KERICHO', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '502e4a16930e414107ee22b6198c578f', NULL, NULL, NULL, NULL, NULL, 'KIPTOO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (259, '29500006331', '2021', '254746626128', '254722908586', 'sudimwinyikai@gmail.com', 'mwinyikaichibako@gmail.com', '80404', '306', 'UKUNDA', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cfa0860e83a4c3a763a7e62d825349f7', NULL, NULL, NULL, NULL, NULL, 'SUDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (260, '29500006363', '2021', '254798000612', '254727511414', 'kerryobai713@gmail.com', 'smoguche@gmail.com', '00200', '10027', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a4f23670e1833f3fdb077ca70bbd5d66', NULL, NULL, NULL, NULL, NULL, 'SHEM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (261, '30500021142', '2021', '254798037355', '254725895035', 'barakaangelos1@gmail.com', 'kuwaso@gmail.com', '10400', '233-10400', 'NANYUKI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b1a59b315fc9a3002ce38bbe070ec3f5', NULL, NULL, NULL, NULL, NULL, 'NYUNDO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (262, '31500026021', '2021', '254706757579', '254729756131', 'kangarageraldkariuki@gmail.com', '', '00232', '1118', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '36660e59856b4de58a219bcf4e27eba3', NULL, NULL, NULL, NULL, NULL, 'KANG''ARA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (263, '33517102092', '2021', '254708546713', '254712205668', 'chelaltitus@gmail.com', '', '30400', '454', 'KABARNET', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8c19f571e251e61cb8dd3612f26d5ecf', NULL, NULL, NULL, NULL, NULL, 'CHELAL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (264, '34500010387', '2021', '254799895055', '254722826422', 'killioncheruiyot@gmail.com', '', '30200', '582', 'KITALE', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd6baf65e0b240ce177cf70da146c8dc8', NULL, NULL, NULL, NULL, NULL, 'KIBIWOT', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (3819, '11200003483', '2021', '254741038180', '254722575085', 'ngerujoe@gmail.com', 'engeru@yahoo.com', '00400', '22740', 'NAIROBI', '1263177', 'K62', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eb1848290d5a7de9c9ccabc67fefa211', NULL, NULL, NULL, NULL, NULL, 'NGERU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (265, '36600002102', '2021', '254720473541', '254734212461', 'echesamustafa15@gmail.com', 'echesamustafa15@gmail.com', '50109', '24', 'BULIMBO', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e56954b4f6347e897f954495eab16a88', NULL, NULL, NULL, NULL, NULL, 'NAJMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (266, '37600001268', '2021', '254724715828', '254711684578', 'careymbaraka@yahoo.com', 'sospeteramunga1@gmail.com', '00200', '7699', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f7664060cc52bc6f3d620bcedc94a4b6', NULL, NULL, NULL, NULL, NULL, 'MBARAKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (267, '37617211367', '2021', '254795170508', '254725119004', 'idrismatete367@gmail.com', 'magretwanga@gmail.com', '50102', '74', 'MUMIAS', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eda80a3d5b344bc40f3bc04f65b7a357', NULL, NULL, NULL, NULL, NULL, 'IDRIS', 'ramadhan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (268, '39700009058', '2021', '254721814979', '254721814979', 'mwebaotieno@gmail.com', 'khayekhe@yahoo.com', '50200', '752', 'BUNGOMA', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8f121ce07d74717e0b1f21d122e04521', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (269, '41700010064', '2021', '254796069950', '254710668878', 'zackbruno64@gmail.com', 'zackbruno64@gmail.com', '40100', '8059', 'KISUMU', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '06138bc5af6023646ede0e1f7c1eac75', NULL, NULL, NULL, NULL, NULL, 'OKOTH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (270, '41704001159', '2021', '254700701389', '254728816921', 'odhizrober254@gmail.com', 'cynthiakoth429@gmail.com', '40300', '22', 'HOMABAY', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '39059724f73a9969845dfe4146c5660e', NULL, NULL, NULL, NULL, NULL, 'ODHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (271, '41710301038', '2021', '254726034530', '254726034530', 'onyangonyakiti@gmail.com', 'alai@techmtaa.com', '00100', '10673', 'NAIROBI', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7f100b7b36092fb9b06dfb4fac360931', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', 'nyakiti', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (272, '42700005407', '2021', '254115339100', '254729602204', 'albertkch2@gmail.com', 'raelrotich0@gmail.com', '40601', '120', 'BONDO', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7a614fd06c325499f1680b9896beedeb', NULL, NULL, NULL, NULL, NULL, 'KIPCHIRCHIR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (273, '42700005615', '2021', '254721533450', '254727686101', 'kivuvabrian7@gmail.com', 'mwikalijosephine25@gmail.com', '40601', '120', 'BONDO', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4734ba6f3de83d861c3176a6273cac6d', NULL, NULL, NULL, NULL, NULL, 'KYALO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (274, '44700006001', '2021', '254114824933', '254728094081', 'ogekebrian@gmail.com', 'gracekwamboka@gmail.com', '5', '5', 'SONDU', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd947bf06a885db0d477d707121934ff8', NULL, NULL, NULL, NULL, NULL, 'OGEKE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (275, '44717102026', '2021', '254728659998', '254728659998', 'dicksonmalela03@gmail.com', 'maleladaniel89@gmail.com', '40300', '583', 'HOMA BAY', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '63923f49e5241343aa7acb6a06a751e7', NULL, NULL, NULL, NULL, NULL, 'WALUGU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (276, '44736101024', '2021', '254714753491', '254701018592', 'rodgersokello709@gmail.com', 'jsamy807@gmail.com', '40300', '77', 'HOMABAY', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'db8e1af0cb3aca1ae2d0018624204529', NULL, NULL, NULL, NULL, NULL, 'OKELLO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (277, '44736101086', '2021', '254757976741', '254725872886', 'patrickobamahz@gmail.com', 'bernokello@gmail.com', '40405', '11', 'SARE AWENDO', '1263117', 'F17', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '20f07591c6fcb220ffe637cda29bb3f6', NULL, NULL, NULL, NULL, NULL, 'PATRICK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (278, '1100003064', '2021', '254113520564', '254712899999', 'clementsigei30@gmail.com', '', '', 'P.O.BOX 64, BOMET', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '07cdfd23373b17c6b337251c22b7ea57', NULL, NULL, NULL, NULL, NULL, 'CLEMENT', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (279, '3100001014', '2021', '254740349073', '254721321923', 'melaniakeyo@gmail.com', 'luciakeyo@gmail.com', '', '', 'MOMBASA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd395771085aab05244a4fb8fd91bf4ee', NULL, NULL, NULL, NULL, NULL, 'AKEYO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (280, '7200009223', '2021', '254114885058', '254728051944', 'puritygachuma@gmail.com', 'perisgachuma@yahoo.com', '00219', '62', 'KARURI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '92c8c96e4c37100777c7190b76d28233', NULL, NULL, NULL, NULL, NULL, 'GACHUMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (281, '7200014018', '2021', '254720998249', '254720998249', 'njugunasamuel66@gmail.com', 'njugunasamuel66@gmail.com', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e3796ae838835da0b6f6ea37bcf8bcb7', NULL, NULL, NULL, NULL, NULL, 'NJUGUNA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (282, '7200014020', '2021', '254777380277', '254723380271', 'michaelmuriani78@gmail.com', 'rosemarymuriani108@gmail.com', '00100', '34253-00100GPO', 'GACHIE', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6a9aeddfc689c1d0e3b9ccc3ab651bc5', NULL, NULL, NULL, NULL, NULL, 'WANGUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (283, '7214101029', '2021', '254727528857', '254720815963', 'mwashjohnnie@gmail.com', 'macharialazarus843@gmail.com', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0f49c89d1e7298bb9930789c8ed59d48', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (284, '8200007111', '2021', '254790777952', '254724576710', 'muh0r0michael001@gmail.com', 'wacd2k@yahoo.com', '00506', '5499', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '46ba9f2a6976570b0353203ec4474217', NULL, NULL, NULL, NULL, NULL, 'MUHORO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (285, '8200007180', '2021', '254736594360', '254722448436', 'themaingiofficial@gmail.com', 'gics2000@gmail.com', '00100', '8372', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0e01938fc48a2cfb5f2217fbfb00722d', NULL, NULL, NULL, NULL, NULL, 'NGUNJIRI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (286, '8200007217', '2021', '254702979710', '254720021537', 'robotcarl12@gmail.com', 'carolineomwamba@gmail.com', '10102', '69', 'KIGANJO', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '16a5cdae362b8d27a1d8f8c7b78b4330', NULL, NULL, NULL, NULL, NULL, 'MBURU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (287, '8237004114', '2021', '254791812297', '254726851105', 'ndiritugerald273@gmail.com', 'muchangistephen25@gmail.com', '10106', '100', 'OTHAYA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '918317b57931b6b7a7d29490fe5ec9f9', NULL, NULL, NULL, NULL, NULL, 'NDIRITU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (288, '8237004193', '2021', '254728262315', '254728262315', 'patrickmwanikimapera2000@gmail.com', 'patrickmwanikimapera2000@gmail.com', '10300', '44', 'KERUGOYA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '48aedb8880cab8c45637abc7493ecddd', NULL, NULL, NULL, NULL, NULL, 'MWANIKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (289, '9200012014', '2021', '254757640474', '254726568193', 'briankamwere93@gmail.com', 'lucynderitu10@gmail.com', '00400', '11500', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '839ab46820b524afda05122893c2fe8e', NULL, NULL, NULL, NULL, NULL, 'JOEBRIAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (290, '9200012083', '2021', '254702741589', '254719507738', 'paulkwaweru870@gmail.com', 'h10rm315@yahoo.com', '00902', '404', 'KIKUYU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f90f2aca5c640289d0a29417bcb63a37', NULL, NULL, NULL, NULL, NULL, 'KINYANJUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (291, '10200008051', '2021', '', '', '', '', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9c838d2e45b2ad1094d42f4ef36764f6', NULL, NULL, NULL, NULL, NULL, 'MOMANYI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (292, '10208311012', '2021', '254790003370', '254720364143', 'lnjue100@gmail.com', '', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1700002963a49da13542e0726b7bb758', NULL, NULL, NULL, NULL, NULL, 'MUGUCIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (293, '10227301183', '2021', '254114693194', '254721856089', 'anjeri679@gmail.com', 'anjeri679@gmail.com', '10202', '186', 'KANGEMA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '53c3bce66e43be4f209556518c2fcb54', NULL, NULL, NULL, NULL, NULL, 'KARANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (294, '10227301272', '2021', '254792793867', '254704968718', 'muchirialex2019@gmail.com', 'mainabenjamin853@gmail.com', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6883966fd8f918a4aa29be29d2c386fb', NULL, NULL, NULL, NULL, NULL, 'MUCHIRI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (295, '11200001017', '2021', '254110587032', '254720673569', 'kiariepatrick003@gmail.com', 'kiarie22@gmail.com', '01001', '432', 'KALIMONI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '49182f81e6a13cf5eaa496d51fea6406', NULL, NULL, NULL, NULL, NULL, 'NGA''NG''A', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (296, '11200001095', '2021', '254741997184', '254732839852', 'morarameshackisaaka@gmail.com', 'petersonmorara@gmail.com', '40200', '730', 'KISII', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd296c101daa88a51f6ca8cfc1ac79b50', NULL, NULL, NULL, NULL, NULL, 'MORARA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1725, '20400006083', '2021', '254722959851', '254733368846', 'beverlyninah2@gmail.com', 'beverlyninah2@gmail.com', '00100', '30027', 'NAIROBI', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3e7e0224018ab3cf51abb96464d518cd', NULL, NULL, NULL, NULL, NULL, 'MORARA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (297, '11200001129', '2021', '254740120916', '254722367159', 'matetebrianfelix@gmail.com', 'kananido@gmail.com', '902', '362', 'KIKUYU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9fd81843ad7f202f26c1a174c7357585', NULL, NULL, NULL, NULL, NULL, 'MATETE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (298, '11200001389', '2021', '254733145747', '254722459984', 'cafukahunyo@gmail.com', 'lgaithi@gmail.com', '00100', '12380', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '26e359e83860db1d11b6acca57d8ea88', NULL, NULL, NULL, NULL, NULL, 'CAFU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (299, '11200002418', '2021', '254722808841', '254721611327', 'hadassahn601@gmail.com', 'lucynjuguna1485@gmail.com', '00200', '72950', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ef0d3930a7b6c95bd2b32ed45989c61f', NULL, NULL, NULL, NULL, NULL, 'NJUGUNA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (300, '11200003002', '2021', '254772868698', '254720746009', 'bradelyezra@gmail.com', 'judithwakhulo20@gmail.com', '00200', '1456', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '94f6d7e04a4d452035300f18b984988c', NULL, NULL, NULL, NULL, NULL, 'WANYONYI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (301, '11200003074', '2021', '254748686247', '254719776628', 'paulvic074@gmail.com', 'nunabronzo@gmail.com', '00200', '342', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '34ed066df378efacc9b924ec161e7639', NULL, NULL, NULL, NULL, NULL, 'WANGUI', 'maina', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (302, '11200003105', '2021', '254718500618', '254720978976', 'karenjuedi@gmail.com', 'twahiraone@gmail.com', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '577bcc914f9e55d5e4e4f82f9f00e7d4', NULL, NULL, NULL, NULL, NULL, 'EDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (303, '11200003219', '2021', '254711826948', '254710929767', 'mwendaroy4@gmail.com', 'japhetmutwiri@gmail.com', '23', '23', 'MITUNGUU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '11b9842e0a271ff252c1903e7132cd68', NULL, NULL, NULL, NULL, NULL, 'MUTWIRI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (304, '11200003374', '2021', '254115880434', '254770743716', 'kiiludennis45@gmail.com', '', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '37bc2f75bf1bcfe8450a1a41c200364c', NULL, NULL, NULL, NULL, NULL, 'KIILU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (305, '11200005067', '2021', '254715371091', '254725877559', 'fanishkerubo@gmail.com', 'joseph.onganyo@uonbi.ac.ke', '00217', 'PRIVATE BAG LIMURU', 'LIMURU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '496e05e1aea0a9c4655800e8a7b9ea28', NULL, NULL, NULL, NULL, NULL, 'KIBAGENDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (306, '11235125031', '2021', '254715384756', '254722265430', 'gitauawesome@gmail.com', 'njungeruth@gmail.com', '00100', 'P. O. BOX 35701, NAI', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b2eb7349035754953b57a32e2841bda5', NULL, NULL, NULL, NULL, NULL, 'NJUNG''E', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (307, '11235127028', '2021', '', '', '', '', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8e98d81f8217304975ccb23337bb5761', NULL, NULL, NULL, NULL, NULL, 'MAGADA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (308, '12301702185', '2021', '254702669904', '254718835269', 'marymuasa003@gmail.com', '', '90106', '21', 'MACHAKOS', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a8c88a0055f636e4a163a5e3d16adab7', NULL, NULL, NULL, NULL, NULL, 'MUASA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (309, '15300002065', '2021', '254780487097', '254721487097', 'stevemurithi907@gmail.com', 'barthomungiria@gmail.com', '60200', '1102', 'MERU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eddea82ad2755b24c4e168c5fc2ebd40', NULL, NULL, NULL, NULL, NULL, 'MUNGIRIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (310, '18336101011', '2021', '254791713157', '254725580230', 'ndindamaureen356@gmail.com', '', '90130', '122', 'KILUNGU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '06eb61b839a0cefee4967c67ccb099dc', NULL, NULL, NULL, NULL, NULL, 'NDINDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (311, '20400001047', '2021', '254700340234', '254721557397', 'nedwin753@gmail.com', 'janetwachera@yahoo.com', '10400', '997', 'NANYUKI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9dfcd5e558dfa04aaf37f137a1d9d3e5', NULL, NULL, NULL, NULL, NULL, 'WACHERA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (312, '20400001063', '2021', '254799588061', '254722337754', 'markalvin682@gmail.com', 'wanjikurachel1@gmail.com', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '950a4152c2b4aa3ad78bdd6b366cc179', NULL, NULL, NULL, NULL, NULL, 'ALVIN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (313, '20400002162', '2021', '254115215876', '254721359229', 'ndichukaranu@gmail.com', 'pewa2018g@gmail.com', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '158f3069a435b314a80bdcb024f8e422', NULL, NULL, NULL, NULL, NULL, 'NDICHU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (314, '20400002307', '2021', '254796801049', '254722663013', 'kaminajake@gmail.com', 'eunicelupia@gmail.com', '', '', 'UMOJA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '758874998f5bd0c393da094e1967a72b', NULL, NULL, NULL, NULL, NULL, 'KAMINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (315, '20400002384', '2021', '254700667982', '254717974990', 'gen.em.ius@gmail.com', 'greatcom.wycliffe@gmail.com', '40500', '846', 'NYAMIRA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ad13a2a07ca4b7642959dc0c4c740ab6', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (316, '20400004181', '2021', '254746608897', '254726496396', 'mathewlewis254@gmail.com', '', '00100', '30178', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3fe94a002317b5f9259f82690aeea4cd', NULL, NULL, NULL, NULL, NULL, 'AGANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (317, '20400008019', '2021', '254792036991', '254720804012', 'odakian76@gmail.com', 'ogutumercy86@gmail.com', '00611', '77155', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5b8add2a5d98b1a652ea7fd72d942dac', NULL, NULL, NULL, NULL, NULL, 'OGUTU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (318, '20400008025', '2021', '254746655857', '254726208709', 'omwaminelson86@gmail.com', 'ndombipamela@gmail.com', '67', '67', 'SHIANDA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '432aca3a1e345e339f35a30c8f65edce', NULL, NULL, NULL, NULL, NULL, 'MAERO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (319, '20400008052', '2021', '254706634242', '254721273550', 'wawerumichaelk@gmail.com', 'mbogo.caroline@gmail.com', '00503', '15682', 'MBAGATHI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8d3bba7425e7c98c50f52ca1b52d3735', NULL, NULL, NULL, NULL, NULL, 'MICHAEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (320, '20401002132', '2021', '254769988763', '254721671548', 'mrdelmarjavier@gmail.com', 'ekabunge@gmail.com', '00100', 'AIRPORT VIEW', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '320722549d1751cf3f247855f937b982', NULL, NULL, NULL, NULL, NULL, 'DELMAR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (321, '20404003037', '2021', '254740949946', '254725203883', 'nathankaberia9@gmail.com', 'steve@datacore.co.ke', '00100', '46318', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'caf1a3dfb505ffed0d024130f58c5cfa', NULL, NULL, NULL, NULL, NULL, 'KABERIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (322, '20404006081', '2021', '254701695327', '254722704962', 'leonowino110@gmail.com', 'akokooyugi@gmail.com', '00200', '73271', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5737c6ec2e0716f3d8a7a5c4e0de0d9a', NULL, NULL, NULL, NULL, NULL, 'OWINO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (323, '20404006109', '2021', '254718767073', '254722358324', 'richardotienocharles@gmail.com', 'marichworks@gmail.com', '00100', '45374-00100', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bc6dc48b743dc5d013b1abaebd2faed2', NULL, NULL, NULL, NULL, NULL, 'OPIKO', 'otieno', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (324, '20404006128', '2021', '254711511944', '254722304690', 'wandaikahare@gmail.com', 'vicky@tarakibu.co.ke', '00100', '15462', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f2fc990265c712c49d51a18a32b39f0c', NULL, NULL, NULL, NULL, NULL, 'KAHARE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (325, '20405004141', '2021', '254720560541', '254722213089', 'cynthiamuthonik@gmail.com', 'nicholas.kariuki@yahoo.com', '00100', '5846', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '89f0fd5c927d466d6ec9a21b9ac34ffa', NULL, NULL, NULL, NULL, NULL, 'KINYUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (326, '20406020029', '2021', '254711632897', '254722362251', 'will.wanje@gmail.com', 'williha254@gmail.com', '00200', '3652', 'NAIROBI', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a666587afda6e89aec274a3657558a27', NULL, NULL, NULL, NULL, NULL, 'WILL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (327, '20406020062', '2021', '', '', '', '', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b83aac23b9528732c23cc7352950e880', NULL, NULL, NULL, NULL, NULL, 'NIRAJ', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (328, '26500001244', '2021', '254727571139', '254725407277', 'raykalekye123@gmail.com', 'phyllis.muema1@yahoo.com', '80100', '42944', 'MOMBASA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cd00692c3bfe59267d5ecfac5310286c', NULL, NULL, NULL, NULL, NULL, 'RAYCHELLE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (329, '27500009232', '2021', '254701323572', '254722911973', 'karagrace72@gmail.com', 'shiku.karanja42@gmail.com', '20100', '1352', 'NAKURU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6faa8040da20ef399b63a72d0e4ab575', NULL, NULL, NULL, NULL, NULL, 'KARANJA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (330, '27537501033', '2021', '254769157306', '254796147942', 'kileludavid057@gmail.com', 'peterlerru@gmail.com', '20117', '1987', 'NAIVASHA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fe73f687e5bc5280214e0486b273a5f9', NULL, NULL, NULL, NULL, NULL, 'DAVID', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (331, '27552001132', '2021', '254707413953', '254721418855', 'kirui6623@gmail.com', 'ckiruic@yahoo.com', '20100', '3566', 'NAKURU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6da37dd3139aa4d9aa55b8d237ec5d4a', NULL, NULL, NULL, NULL, NULL, 'KIRUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (332, '29500006370', '2021', '254750165363', '254720249716', 'odiyoedgar@gmail.com', 'roselynodiyo11@gmail.com', '00208', '389', 'NGONG', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c042f4db68f23406c6cecf84a7ebb0fe', NULL, NULL, NULL, NULL, NULL, 'ODIYO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (333, '30500020314', '2021', '254111555303', '254715893445', 'wamahigahellen2@gmail.com', 'eunicekariuki819@gmail.com', '20100', '7364', 'NAKURU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '310dcbbf4cce62f762a2aaa148d556bd', NULL, NULL, NULL, NULL, NULL, 'NDEI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (334, '38600003166', '2021', '254743344786', '254725246569', 'bathshuamm@gmail.com', 'agitiasmiheso@yahoo.com', '50200', '609', 'BUNGOMA', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2f2b265625d76a6704b08093c652fd79', NULL, NULL, NULL, NULL, NULL, 'MIHESO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (335, '39700001016', '2021', '254113750454', '254715788624', 'derricares@gmail.com', 'sellyanyango2525@gmail.com', '40105', '120', 'KISUMU', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f9b902fc3289af4dd08de5d1de54f68f', NULL, NULL, NULL, NULL, NULL, 'ODHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (336, '39737014045', '2021', '254742189112', '254721732743', 'alexbomondi@gmail.com', '', '', '', '', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6855456e2fe46a9d49d3d3af4f57443d', NULL, NULL, NULL, NULL, NULL, 'OSIO', 'omondi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (337, '41710301031', '2021', '254702571180', '254702571180', 'okwachandrew@gmail.com', '', '40222', '39', 'OYUGIS', '1263118', 'F18', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '357a6fdf7642bf815a88822c447d9dc4', NULL, NULL, NULL, NULL, NULL, 'ANDREW', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (338, '7216108033', '2021', '254795403584', '254111674071', 'wachiraruth9540@gmail.com', 'wachiraruth9540@gmail.com', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '819f46e52c25763a55cc642422644317', NULL, NULL, NULL, NULL, NULL, 'WACHIRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (339, '8200010029', '2021', '254720595967', '254722702334', 'trinakinyanjui@gmail.com', 'alexkironde1@gmail.com', '10101', '182', 'KARATINA', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '04025959b191f8f9de3f924f0940515f', NULL, NULL, NULL, NULL, NULL, 'KINYANJUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (340, '8200010135', '2021', '254710659030', '254723782137', 'melissagathiaka@gmail.com', 'ekaruki8@gmail.com', '00100', '46907', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '40008b9a5380fcacce3976bf7c08af5b', NULL, NULL, NULL, NULL, NULL, 'GATHIAKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (341, '9200012035', '2021', '254762840106', '254710255098', 'wakandafrank@gmail.com', 'margywambui@gmail.com', '10303', '16 WANG''URU', 'WANG''URU', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3dd48ab31d016ffcbf3314df2b3cb9ce', NULL, NULL, NULL, NULL, NULL, 'MURIITHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (342, '10238114004', '2021', '254740134058', '254728655603', 'sammyleih6871@gmail.com', 'jedidahmuthoni@gmail.com', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '58238e9ae2dd305d79c2ebc8c1883422', NULL, NULL, NULL, NULL, NULL, 'CHEGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (343, '10238114010', '2021', '', '', '', '', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3ad7c2ebb96fcba7cda0cf54a2e802f5', NULL, NULL, NULL, NULL, NULL, 'MWANGI', 'njunu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (344, '11200001360', '2021', '254762475551', '254722496675', 'marclewiekariuki@gmail.com', 'wambuimungai15@gmail.com', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b3967a0e938dc2a6340e258630febd5a', NULL, NULL, NULL, NULL, NULL, 'CHARAGU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (345, '11200003332', '2021', '254794413499', '254721527803', 'hibikatsohiko@gmail.com', 'ogadatwo@yahoo.com', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd81f9c1be2e08964bf9f24b15f0e4900', NULL, NULL, NULL, NULL, NULL, 'HIBI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (346, '11200005264', '2021', '254719173731', '254721444628', 'jillwaithuki316@gmail.com', 'tnjeri4444@gmail.com', '00900', '536', 'KIAMBU', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '13f9896df61279c928f19721878fac41', NULL, NULL, NULL, NULL, NULL, 'WAITHUKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (347, '11205101043', '2021', '254796622056', '254727428068', 'alexandermuiru6@gmail.com', 'judymuthoni651@gmail.com', '00900', '804', 'KIAMBU', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c5ff2543b53f4cc0ad3819a36752467b', NULL, NULL, NULL, NULL, NULL, 'NJENGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (348, '11205204185', '2021', '254793790572', '254792149096', 'murigigathirwa@gmail.com', 'jamesnganga_wanjiku@yahoo.com', '01000', '3796', 'THIKA', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '01386bd6d8e091c2ab4c7c7de644d37b', NULL, NULL, NULL, NULL, NULL, 'GATHIRWA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (349, '11206110007', '2021', '254784123728', '254725660923', 'eleanorondeng@gmail.com', 'oolaclementine@gmail.com', '00100', '34041', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0bb4aec1710521c12ee76289d9440817', NULL, NULL, NULL, NULL, NULL, 'ONDENG''', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (350, '11207108028', '2021', '254784771106', '254738360235', 'g.gikonyo01@gmail.com', 'gikonyomwaniki1971@gmail.com', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9de6d14fff9806d4bcd1ef555be766cd', NULL, NULL, NULL, NULL, NULL, 'GIKONYO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (351, '12300013076', '2021', '254759879125', '254720890993', 'wambuaanne09@gmail.com', 'onesmuswambua92@gmail.com', '90200', '137', 'KITUI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'efe937780e95574250dabe07151bdc23', NULL, NULL, NULL, NULL, NULL, 'ANNE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (352, '13300007048', '2021', '254797710035', '254783049304', 'mukunzuchristopher@gmail.com', 'mukunzuchristopher@gmail.com', '90200', '39', 'KITUI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '371bce7dc83817b7893bcdeed13799b5', NULL, NULL, NULL, NULL, NULL, 'MUOKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (353, '13302102013', '2021', '254703503049', '254721252925', 'irvanmogire70@gmail.com', 'omaregideon@yahoo.com', '00100', '1846', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '138bb0696595b338afbab333c555292a', NULL, NULL, NULL, NULL, NULL, 'MOGIRE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (354, '13353201084', '2021', '254742386233', '254740710336', 'muthonisamuel@gmail.com', 'rasterkimsamuel@gmail.com', '90401', '182', 'KYUSO', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8dd48d6a2e2cad213179a3992c0be53c', NULL, NULL, NULL, NULL, NULL, 'MUTIE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (355, '13360101004', '2021', '254748907801', '254721427910', 'peterkitonga300@gmail.com', '', '', '890', 'KITUI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '82cec96096d4281b7c95cd7e74623496', NULL, NULL, NULL, NULL, NULL, 'MUSEMBI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (356, '15300002071', '2021', '254716267141', '254743438483', 'ltangresslesorogol@gmail.com', 'loperulesorogol@gmail.com', '', '451', 'MARALAL', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6c524f9d5d7027454a783c841250ba71', NULL, NULL, NULL, NULL, NULL, 'JOSEPH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (357, '15300002097', '2021', '254794299078', '254703697032', 'kagaip09@gmail.com', 'mwangi1@gmail.com', '00203', '47', 'KISERIAN', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fb7b9ffa5462084c5f4e7e85a093e6d7', NULL, NULL, NULL, NULL, NULL, 'NJOKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (358, '15319306179', '2021', '254110362312', '254716452263', 'dylabrian00@gmail.com', 'dylabrian00@gmail.com', '101', '101', 'IGOJI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'aa942ab2bfa6ebda4840e7360ce6e7ef', NULL, NULL, NULL, NULL, NULL, 'KINOTI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (359, '18300016012', '2021', '254686573797', '254722147720', 'mk._ashley16@icloud.com', 'kyalokimende@gmail.com', '00500', '19021', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c058f544c737782deacefa532d9add4c', NULL, NULL, NULL, NULL, NULL, 'KYALO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (360, '20404006107', '2021', '254775243561', '254720813079', 'raymondamboko@icloud.com', 'raypeterraypeter@gmail.com', '', '', 'KIAMBU', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e7b24b112a44fdd9ee93bdf998c6ca0e', NULL, NULL, NULL, NULL, NULL, 'AKHONYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1726, '20400006284', '2021', '254742975561', '254720385575', 'ahmeddadow@gmail.com', 'dadowjnr@gmail.com', '', '', '', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '74563ba21a90da13dacf2a73e3ddefa7', NULL, NULL, NULL, NULL, NULL, 'MARYAMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (361, '20406003009', '2021', '254715974004', '254722564165', 'bilalkadri2003@gmail.com', 'asmakadri98@gmail.com', '', '', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '52720e003547c70561bf5e03b95aa99f', NULL, NULL, NULL, NULL, NULL, 'KADRI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (362, '20406020004', '2021', '254719340607', '254722724593', 'ianwaruingikaranja@gmail.com', 'elizabethwaruingi@gmail.com', '00100', '5051', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c3e878e27f52e2a57ace4d9a76fd9acf', NULL, NULL, NULL, NULL, NULL, 'KARANJA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (364, '26500001303', '2021', '254110909845', '254721320908', 'auraernestine@gmail.com', 'ednaaura@gmail.com', '1258', '130', 'HOMABAY', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bac9162b47c56fc8a4d2a519803d51b3', NULL, NULL, NULL, NULL, NULL, 'ONDIJO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (365, '27552001039', '2021', '254764618808', '254720773858', 'eglasius10@gmail.com', 'jmwendag@gmail.com', '00100', '30586', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9be40cee5b0eee1462c82c6964087ff9', NULL, NULL, NULL, NULL, NULL, 'MWENDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (366, '27552001176', '2021', '254799158445', '254722255903', 'ccindyyyj@gmail.com', 'cjepkoech81@gmail.com', '00100', '48122', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5ef698cd9fe650923ea331c15af3b160', NULL, NULL, NULL, NULL, NULL, 'JEROTICH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (367, '27552001235', '2021', '254742535253', '254712852283', 'nathanmakau101@gmail.com', 'makaumaganga101@gmail.com', '00100', '30266', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '05049e90fa4f5039a8cadc6acbb4b2cc', NULL, NULL, NULL, NULL, NULL, 'MAKAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (368, '28512101084', '2021', '254786544298', '254714588271', 'kipkiruibonface@gmail.com', 'cheptoomaritim57@gmail.com', '64', 'LITEIN', 'LITEIN', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cf004fdc76fa1a4f25f62e0eb5261ca3', NULL, NULL, NULL, NULL, NULL, 'KIPKIRUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (369, '28512101140', '2021', '254792042581', '254792724995', 'kevookemb@gmail.com', '', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0c74b7f78409a4022a2c4c5a5ca3ee19', NULL, NULL, NULL, NULL, NULL, 'KELVIN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (370, '28512101148', '2021', '254769114080', '254711831187', 'ianbobby403@gmail.com', '', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd709f38ef758b5066ef31b18039b8ce5', NULL, NULL, NULL, NULL, NULL, 'IAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (371, '29500006239', '2021', '254733219194', '254726496566', 'teddycyril04@gmail.com', 'herimo150@yahoo.com', '40100', '3780', 'KISUMU', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '41f1f19176d383480afa65d325c06ed0', NULL, NULL, NULL, NULL, NULL, 'TEDDY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (372, '30500021026', '2021', '254745498481', '254701965598', 'deekolimba@gmail.com', 'fainijosephine@gmail.com', '80108', '954', 'KILIFI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '24b16fede9a67c9251d3e7c7161c83ac', NULL, NULL, NULL, NULL, NULL, 'KOLIMBA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (373, '35609104005', '2021', '254745176590', '254745482783', 'michaelalariachun@gmail.com', 'michaelalariachun@gmail.com', 'na', '2', 'KAMURIAI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ffd52f3c7e12435a724a8f30fddadd9c', NULL, NULL, NULL, NULL, NULL, 'ABRAHAM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (374, '36600004264', '2021', '254758202672', '254724146713', 'ngenolaban07@gmail.com', '', '50', '', 'NDANAI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ad972f10e0800b49d76fed33a21f6698', NULL, NULL, NULL, NULL, NULL, 'LABAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (375, '37617407107', '2021', '254708902264', '254708902264', 'okothdaniel038@gmail.com', 'okothdaniel038@gmail.com', '50102', '609', 'MUMIAS', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f61d6947467ccd3aa5af24db320235dd', NULL, NULL, NULL, NULL, NULL, 'ODUOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (376, '38600006041', '2021', '254797785541', '254714415749', 'moffatojiambon@gmail.com', 'anthonyosinya@gmail.com', '00518', '85', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '142949df56ea8ae0be8b5306971900a4', NULL, NULL, NULL, NULL, NULL, 'OJIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (377, '38600006129', '2021', '254114477347', '254721934491', 'muthomikobia314@gmail.com', '', '', '536 NJORO', 'NJORO', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd34ab169b70c9dcd35e62896010cd9ff', NULL, NULL, NULL, NULL, NULL, 'KOBIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (378, '39700001111', '2021', '254752174282', '254722373513', 'kevinlevrone078@gmail.com', 'risperdoreen024@gmail.com', '00100', '2456', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8bf1211fd4b7b94528899de0a43b9fb3', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (379, '41700004187', '2021', '254737643220', '254722643220', 'dalsymiranda2005@gmail.com', 'silasodero@yahoo.com', '00300', '9236', 'NAIROBI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a02ffd91ece5e7efeb46db8f10a74059', NULL, NULL, NULL, NULL, NULL, 'MIRANDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (380, '41700004300', '2021', '254756621993', '254726679584', 'firstkenyatta@gmai.com', 'hellenbonyo@gmail.com', '40100', '285', 'KISUMU', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bca82e41ee7b0833588399b1fcd177c7', NULL, NULL, NULL, NULL, NULL, 'MIRIAM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (382, '41731402001', '2021', '254791445931', '254702268562', 'jaredsupreme42@gmail.com', '', '40304', '74', 'KANDIEGE', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4f6ffe13a5d75b2d6a3923922b3922e5', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (383, '43700008485', '2021', '', '', '', '', '', '', '', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'beed13602b9b0e6ecb5b568ff5058f07', NULL, NULL, NULL, NULL, NULL, 'AMOS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (386, '3100007040', '2021', '254793929923', '254719285650', 'athumanlenzo158@gmail.com', 'athumanlenzo158@gmail.com', '80100', '96077', 'MOMBASA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '39461a19e9eddfb385ea76b26521ea48', NULL, NULL, NULL, NULL, NULL, 'LENZO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (387, '3126101163', '2021', '254712664217', '254722240477', 'hassanbigshaq@gmail.com', 'feisalh987@gmail.com', '00505', '21001', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8efb100a295c0c690931222ff4467bb8', NULL, NULL, NULL, NULL, NULL, 'HASSAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (388, '3126106076', '2021', '254745528570', '254707271707', 'mosesojajuyolo@gmail.com', 'mosesojaju@gmail.com', '80100', '1609', 'MOMBASA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd9fc5b73a8d78fad3d6dffe419384e70', NULL, NULL, NULL, NULL, NULL, 'MOSES', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (389, '4119101135', '2021', '254797848289', '254717063644', 'grahammlewa94@gmail.com', 'yvonneambetsa@gmail.com', '80200', '147', 'MALINDI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c86a7ee3d8ef0b551ed58e354a836f2b', NULL, NULL, NULL, NULL, NULL, 'JONAS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (390, '7201113001', '2021', '254798991702', '254704695135', 'elimithia@gmail.com', '', '1144', '1144', 'NYAHURURU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a01a0380ca3c61428c26a231f0e49a09', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (391, '8202007289', '2021', '719266163', '', 'ec0619617@gmail.com', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5a4b25aaed25c2ee1b74de72dc03c14e', NULL, NULL, NULL, NULL, NULL, 'WANJIRU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (392, '8237012109', '2021', '254110667384', '254710973372', 'rawairish@gmail.com', 'rawairish@gmail.com', '', '', 'NYERI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f73b76ce8949fe29bf2a537cfa420e8f', NULL, NULL, NULL, NULL, NULL, 'MURIITHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (3820, '11200004299', '2021', '254759227455', '254716748287', 'nzukiharrison309@gmail.com', '', '871', '', 'MACHAKOS', '1263177', 'K62', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '91e50fe1e39af2869d3336eaaeebdb43', NULL, NULL, NULL, NULL, NULL, 'NTHENYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1665, '18307201146', '2021', '254757587038', '254712193787', 'chrispusmutua2000@gmail.com', '', '90300', '130', 'MAKUENI', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e8d92f99edd25e2cef48eca48320a1a5', NULL, NULL, NULL, NULL, NULL, 'WAMBUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (393, '9200011238', '2021', '254754689673', '254728541328', 'florencemathu29@gmail.com', 'florencemathu29@gmail.com', '10300', '69', 'WANG''URU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '70c639df5e30bdee440e4cdf599fec2b', NULL, NULL, NULL, NULL, NULL, 'MATHU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (394, '10200013067', '2021', '254768180927', '254715516090', 'muthonicess01@gmail.com', '', '00208', '79', 'NGONG HILLS', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '28f0b864598a1291557bed248a998d4e', NULL, NULL, NULL, NULL, NULL, 'NDUNG''U', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (395, '10208311125', '2021', '254712386764', '254721986263', 'ian.g.mwangi.ig@gmail.com', 'joan.kirika@gmail.com', '00100', '30824', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1543843a4723ed2ab08e18053ae6dc5b', NULL, NULL, NULL, NULL, NULL, 'KIRIKA', 'mwangi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (396, '11200001351', '2021', '254717016815', '254713399976', 'ngunyimaxwell@gmail.com', 'ednawairimu@gmail.com', '60300', '42', 'ISIOLO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f8c1f23d6a8d8d7904fc0ea8e066b3bb', NULL, NULL, NULL, NULL, NULL, 'MUGAMBI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (397, '11200003036', '2021', '254721934722', '254720736907', 'stephenkinyanjui036@gmail.com', 'muthonim878@gmail.com', '00100', '54280', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e46de7e1bcaaced9a54f1e9d0d2f800d', NULL, NULL, NULL, NULL, NULL, 'KINYANJUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (398, '11200004370', '2021', '254746457433', '254716579689', 'lydiandisha@gmail.com', 'mwanziamutua2019@gmail.com', '80403', '84', 'KWALE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b7b16ecf8ca53723593894116071700c', NULL, NULL, NULL, NULL, NULL, 'MWANZIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (399, '11205201130', '2021', '254703324929', '254724957942', 'nyokabikamau100@gmail.com', 'judymarkens100@gmail.com', '00219', '365', 'KARURI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '352fe25daf686bdb4edca223c921acea', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (400, '11205204245', '2021', '254703579753', '254714244817', 'kibunjasammy8@gmail.com', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '18d8042386b79e2c279fd162df0205c8', NULL, NULL, NULL, NULL, NULL, 'SAMMY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (401, '11206213002', '2021', '254724058957', '254722911303', 'muthuicrystal@gmail.com', 'sambaem@gmail.com', '00200', '51765', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '816b112c6105b3ebd537828a39af4818', NULL, NULL, NULL, NULL, NULL, 'MUTHUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (402, '12301702130', '2021', '', '', '', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '69cb3ea317a32c4e6143e665fdb20b14', NULL, NULL, NULL, NULL, NULL, 'MBITHE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (403, '12301703122', '2021', '254796197877', '254717780914', 'maingilucas0@gmail.com', '', '90100', '310', 'MACHAKOS', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bbf94b34eb32268ada57a3be5062fe7d', NULL, NULL, NULL, NULL, NULL, 'MUTHINI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (404, '12301734037', '2021', '254101585225', '254727718927', 'lizziemm9@gmail.com', 'mmaitha@krb.go', '00200', '73718', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4f4adcbf8c6f66dcfc8a3282ac2bf10a', NULL, NULL, NULL, NULL, NULL, 'ELIZABETH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (405, '12314106165', '2021', '254794529282', '254794529282', 'estherngaya6@gmail.com', 'mwemamoses2000@gmail.com', '00300', '564', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bbcbff5c1f1ded46c25d28119a85c6c2', NULL, NULL, NULL, NULL, NULL, 'NGAYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (406, '12314215001', '2021', '254728032095', '254704438040', 'margyikahu@gmail.com', 'margyikahu@gmail.com', '200', '983', 'MURANGA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8cb22bdd0b7ba1ab13d742e22eed8da2', NULL, NULL, NULL, NULL, NULL, 'WAIRIMU', 'muthoni', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (407, '13310401001', '2021', '254741976352', '254700628129', 'kelvinkimolo254@gmail.com', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f4f6dce2f3a0f9dada0c2b5b66452017', NULL, NULL, NULL, NULL, NULL, 'KATINGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (408, '13328301005', '2021', '254742484020', '254796932058', 'josephmusyokamutemi1894@gmail.com', 'musyokam@gmail.com', '90401', '21', 'KYUSO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0d0fd7c6e093f7b804fa0150b875b868', NULL, NULL, NULL, NULL, NULL, 'MUTEMI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (409, '13339101076', '2021', '254719336282', '254705150754', 'ngangijoseph72@gmail.com', 'ngangijoseph72@gmail.com', '90402', 'MIGWANI', 'MIGWANI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a96b65a721e561e1e3de768ac819ffbb', NULL, NULL, NULL, NULL, NULL, 'KASISA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (410, '13339301051', '2021', '254719435026', '254719435026', 'ndekejulianah@gmail.com', 'ndekejulianah@gmail.com', '90400', '130', 'MWINGI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1068c6e4c8051cfd4e9ea8072e3189e2', NULL, NULL, NULL, NULL, NULL, 'MBANDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (411, '13344109004', '2021', '254790922043', '254795158953', 'wambuamwanzui@gmail.com', 'wambuamwanzui@yahoo.com', '90200', '810', 'KITUI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '17d63b1625c816c22647a73e1482372b', NULL, NULL, NULL, NULL, NULL, 'MWANZUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (412, '13360101122', '2021', '254791659661', '254711944090', 'stephenboniface63@gmail.com', 'bonifacekimeu75@gmail.com', '90200', '24970', 'KITUI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b9228e0962a78b84f3d5d92f4faa000b', NULL, NULL, NULL, NULL, NULL, 'BONIFACE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (413, '14333213014', '2021', '254768929629', '254721836196', 'gracekinyua2004@gmail.com', 'cnjagi2013@gmail.com', '60100', '665', 'EMBU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0deb1c54814305ca9ad266f53bc82511', NULL, NULL, NULL, NULL, NULL, 'KINYUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (381, '41710301061', '2021', '254796075465', '254745473881', 'odhiambotonny61@gmail.com', '', '', '', '', '1263119', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '00ec53c4682d36f5c4359f4ae7bd7ba1', NULL, NULL, NULL, NULL, NULL, 'ODHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (385, '2100006005', '2021', '254792450760', '254720455811', 'sofiamasha2002@gmail.com', 'sofiamasha2002@gmail.com', '80200', '34', 'MALINDI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '$2y$13$Z3iXQjKx3bdt6yclm85WseoO8d/sQ6yg9Z3ia4TNJdEJP9LO87QpS', NULL, NULL, NULL, NULL, NULL, 'MASHA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (414, '14355202169', '2021', '254712308281', '254712360428', 'stellamainakk@gmail.com', 'sammugendi254@gmail.com', '00611', '77093', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '66808e327dc79d135ba18e051673d906', NULL, NULL, NULL, NULL, NULL, 'STELLA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (415, '15300002274', '2021', '254796270657', '254710443283', 'marcdennis1703@gmail.com', 'glorykendi3297@gmail.com', '60600', '334', 'MAUA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '42e7aaa88b48137a16a1acd04ed91125', NULL, NULL, NULL, NULL, NULL, 'MUTEMBEI', 'munene', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (416, '15309212026', '2021', '254719286710', '254718642510', 'kaarialice20@gmail.com', 'kaarialice20@gmail.com', '60600', '134', 'MAUA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8fe0093bb30d6f8c31474bd0764e6ac0', NULL, NULL, NULL, NULL, NULL, 'GITAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (417, '15309301163', '2021', '254769059315', '254729011742', 'mutwiridenson@gmail.com', 'moseskimathi@gmail.com', '', '501', 'MAUA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '41ae36ecb9b3eee609d05b90c14222fb', NULL, NULL, NULL, NULL, NULL, 'KIMATHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (418, '15361118062', '2021', '254717967316', '254721935896', 'roykobia4@gmail.com', 'wechuli.moses@gmail.com', '60607', '114', 'MIKINDURI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd1f255a373a3cef72e03aa9d980c7eca', NULL, NULL, NULL, NULL, NULL, 'ROY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (419, '18307105036', '2021', '254757670208', '254718783367', 'nbamusau@gmail.com', 'nbamusau@gmail.com', '14', 'KITISE', 'KITISE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7eacb532570ff6858afd2723755ff790', NULL, NULL, NULL, NULL, NULL, 'MUSAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (420, '18307109050', '2021', '254746795490', '254706935166', 'jonmusyokimunyao@gmail.com', 'mekakimeu@gmail.com', '90300', '218', 'MAKUENI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b6f0479ae87d244975439c6124592772', NULL, NULL, NULL, NULL, NULL, 'MUSYOKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (421, '18323102229', '2021', '254796085859', '254722294093', 'alvinsoremo00@gmail.com', 'odhiambowere1@gmail.com', '90137', '71', 'KIBWEZI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e0c641195b27425bb056ac56f8953d24', NULL, NULL, NULL, NULL, NULL, 'OREMO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (422, '18324103054', '2021', '254700701391', '254723815583', 'marykavithe93@gmail.com', 'meshack.rakamba@yahoo.com', '00600', '31275', 'NGARA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f85454e8279be180185cac7d243c5eb3', NULL, NULL, NULL, NULL, NULL, 'MARY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (423, '18335202080', '2021', '254797487672', '254705176897', 'musyokililian612@gmail.com', 'brianumoa@gmail.com', '93200', '71', 'KATHONZWENI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'faa9afea49ef2ff029a833cccc778fd0', NULL, NULL, NULL, NULL, NULL, 'MUSYOKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (424, '20400002481', '2021', '254112758771', '254721606593', 'githinjilaban22@gmail.com', 'veskahagency@gmail.com', '254', '', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3c7781a36bcd6cf08c11a970fbe0e2a6', NULL, NULL, NULL, NULL, NULL, 'GITHINJI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1727, '20400006336', '2021', '791275775', '1115297640', 'hassanrooney444@gmail.com', 'hassanrooney444@gmail.com', '70200', '9', 'WAJIR', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '515ab26c135e92ed8bf3a594d67e4ade', NULL, NULL, NULL, NULL, NULL, 'NIMO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (425, '20400003267', '2021', '', '', '', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '25b2822c2f5a3230abfadd476e8b04c9', NULL, NULL, NULL, NULL, NULL, 'MWITHIRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (426, '20400003301', '2021', '254795542603', '254722700880', 'jazmukiri@gmail.com', 'geemukiri@gmail.com', '00100', '30650', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6ecbdd6ec859d284dc13885a37ce8d81', NULL, NULL, NULL, NULL, NULL, 'MUKIRI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (427, '20400009061', '2021', '254754098774', '254724947876', 'liztabby168@gmail.com', 'angelakitheka@gmail.com', '00200', '6847', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '18997733ec258a9fcaf239cc55d53363', NULL, NULL, NULL, NULL, NULL, 'KITHEKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (428, '20401001181', '2021', '254768440357', '254701174212', 'thomagrace3@gmail.com', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8d7d8ee069cb0cbbf816bbb65d56947e', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (429, '20401002146', '2021', '254700214162', '254798714800', 'qanchorosj@gmail.com', 'alikadiz307@gmail.com', '00100', 'KIRINYAGA ROAD', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '75fc093c0ee742f6dddaa13fff98f104', NULL, NULL, NULL, NULL, NULL, 'ABDUB', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (430, '20401101085', '2021', '254724295019', '254724444090', 'damadavid254@gmail.com', 'damadavid254@gmail.com', '102', '00518', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f74909ace68e51891440e4da0b65a70c', NULL, NULL, NULL, NULL, NULL, 'RACHEAL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (431, '20404003151', '2021', '254741272276', '254722247886', 'jossjam2@gmail.com', 'nyakapiyi0@yahoo.com', '00517', '22-00517', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '66368270ffd51418ec58bd793f2d9b1b', NULL, NULL, NULL, NULL, NULL, 'ODUOL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (432, '20404003187', '2021', '254708136579', '254722657511', 'tarvonengugi@gmail.com', 'ebonykadii@gmail.com', '00200', '57570', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '248e844336797ec98478f85e7626de4a', NULL, NULL, NULL, NULL, NULL, 'NGUGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (433, '20405001232', '2021', '254727811400', '254710692228', 'berylkimeu@gmail.com', 'michaelonyango13@yahoo.com', '00100', '15873', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '019d385eb67632a7e958e23f24bd07d7', NULL, NULL, NULL, NULL, NULL, 'MUASA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (434, '20406008041', '2021', '254722484655', '254710966281', 'chelimoalison0@gmail.com', 'chesiredominic77@gmail.com', '00500', '18737', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a49e9411d64ff53eccfdd09ad10a15b3', NULL, NULL, NULL, NULL, NULL, 'KIPYEGON', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (435, '20406008045', '2021', '', '', '', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ddb30680a691d157187ee1cf9e896d03', NULL, NULL, NULL, NULL, NULL, 'MUSAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (436, '20406010028', '2021', '254746058659', '254720753073', 'joshuawinston100@gmial.com', 'sautiyainjilireocrds@gmail.com', '90100', '151 MACHAKOS', 'MACHAKOS', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2421fcb1263b9530df88f7f002e78ea5', NULL, NULL, NULL, NULL, NULL, 'LOTAKAJAKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (437, '20406020009', '2021', '254791017256', '254722760162', 'henezi.matiko21@gmail.com', 'oliver.matiko@gmail.com', '00100', '4417', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fccb60fb512d13df5083790d64c4d5dd', NULL, NULL, NULL, NULL, NULL, 'MATIKO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (438, '20409073086', '2021', '254743520634', '254713365641', 'sandraalivitsa3@gmail.com', 'everlynkadiegu@gmail.com', '00200', '57365', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1651cf0d2f737d7adeab84d339dbabd3', NULL, NULL, NULL, NULL, NULL, 'SANDRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (439, '20410005181', '2021', '254114554454', '254713985175', 'navakwijohn@gmail.com', 'info@projectelimu.org', '00400', '22919', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eed5af6add95a9a6f1252739b1ad8c24', NULL, NULL, NULL, NULL, NULL, 'JOHN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (440, '21500021061', '2021', '254711188267', '254711652466', 'ebulonshemmy@gmail.com', 'mohammedloyanai@gmail.com', '30500', '246', 'LODWAR', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a8abb4bb284b5b27aa7cb790dc20f80b', NULL, NULL, NULL, NULL, NULL, 'LOYANAE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (441, '23528111029', '2021', '254706835087', '254703157694', 'travisjosh809@gmail.com', '', '30215', '42', 'KESOGON', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '15d4e891d784977cacbfcbb00c48f133', NULL, NULL, NULL, NULL, NULL, 'KOECH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (442, '23574110001', '2021', '254710134711', '254742154937', 'amoskaruma@gmail.com', 'characterdon@gmail.com', '30200', '2643', 'KITALE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c203d8a151612acf12457e4d67635a95', NULL, NULL, NULL, NULL, NULL, 'KUYONI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (443, '24500004011', '2021', '254754031352', '254754031352', 'valariewamukota12@gmail.com', 'valariewamukota12@gmail.com', '50200', '141', 'BUNGOMA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '13f3cf8c531952d72e5847c4183e6910', NULL, NULL, NULL, NULL, NULL, 'VALARIE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (444, '24585101163', '2021', '254710231969', '254719450777', 'garisemartin@gmail.com', 'hasango@gmail.com', '00505', '22281', 'NGONG ROAD NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '550a141f12de6341fba65b0ad0433500', NULL, NULL, NULL, NULL, NULL, 'SHEDRACK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (445, '25500019019', '2021', '254752259189', '254722961010', 'mercytalie5@gmail.com', 'miriamkomen972@gmail.com', '', '1957ELDORET', 'ELDORET', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '67f7fb873eaf29526a11a9b7ac33bfac', NULL, NULL, NULL, NULL, NULL, 'TALAAM', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (446, '25500023085', '2021', '254720938446', '254721487498', 'zerumdorney@gmail.com', '', '', '', 'NAROK TOWN', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1a5b1e4daae265b790965a275b53ae50', NULL, NULL, NULL, NULL, NULL, 'KONANA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (447, '25533103053', '2021', '254728496530', '254727703559', 'aroncheruiyot1922@gmail.com', 'nellylaboso@gmail.com', '20406', '410', 'SOTIK', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9a96876e2f8f3dc4f3cf45f02c61c0c1', NULL, NULL, NULL, NULL, NULL, 'ARON', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (448, '26509124021', '2021', '254706258421', '254725922823', 'kiplagatlaban01@gmail.com', 'perezkirwa@gmail.com', '30100', '94', 'ELDORET', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9b70e8fe62e40c570a322f1b0b659098', NULL, NULL, NULL, NULL, NULL, 'NG''ETICH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (449, '26577100171', '2021', '254715084129', '254715084129', 'kipropgidy55@gmail.com', '', '365', '365 ITEN', 'ITEN', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd61e4bbd6393c9111e6526ea173a7c8b', NULL, NULL, NULL, NULL, NULL, 'KIPRUTO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (450, '26577101132', '2021', '254113418056', '254726790109', 'sgitau388@gmail.com', '', '30100', '4296', 'ELDORET', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f5f8590cd58a54e94377e6ae2eded4d9', NULL, NULL, NULL, NULL, NULL, 'GITAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (451, '26578020070', '2021', '254705897176', '254790286689', 'daninyoike@gmail.com', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '941e1aaaba585b952b62c14a3a175a61', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (452, '27536129035', '2021', '254113767968', '254724300945', 'mwangithukuibrahim@gmail.com', '', '', '', 'NAKURU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9431c87f273e507e6040fcb07dcb4509', NULL, NULL, NULL, NULL, NULL, 'THUKU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (453, '27537314045', '2021', '254718079573', '254718079573', 'peterkinyanjui@yahoo.com', 'peterkinyanjui@yahoo.com', '20117', '391', 'NAIVASHA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '49ae49a23f67c759bf4fc791ba842aa2', NULL, NULL, NULL, NULL, NULL, 'WANJIRU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (454, '27538112097', '2021', '254748415255', '254713035033', 'samuelkamausena@gmail.com', 'dunkareithi@gmail.com', '20500', '93', 'NAROK', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e44fea3bec53bcea3b7513ccef5857ac', NULL, NULL, NULL, NULL, NULL, 'MBUGUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (455, '28500006054', '2021', '254115085921', '254721837513', 'oyavogerald@gmail.com', 'marc.ceddy@gmail.com', '50316', '112', 'TAMBUA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '821fa74b50ba3f7cba1e6c53e8fa6845', NULL, NULL, NULL, NULL, NULL, 'OYAVO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (456, '28522508055', '2021', '254705800537', '254710653950', 'sbett255@gmail.com', 'koskeigideon35@gmail.com', '734', '734 MOLO', 'MOLO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '250cf8b51c773f3f8dc8b4be867a9a02', NULL, NULL, NULL, NULL, NULL, 'SAMMY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (3821, '11200005172', '2021', '', '', '', '', '', '', '', '1263177', 'K62', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c04c19c2c2474dbf5f7ac4372c5b9af1', NULL, NULL, NULL, NULL, NULL, 'KETUYION', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (457, '28539202124', '2021', '254723031277', '254723031277', 'cheruiyotaron2022@gmail.com', 'dicksonkipkirui001@gmail.com', '20202', '144', 'KIPKELION', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '42998cf32d552343bc8e460416382dca', NULL, NULL, NULL, NULL, NULL, 'ARON', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (458, '29542102059', '2021', '254792616937', '254729862530', 'jepkosgeizeddy844@gmail.com', 'eliudkongai832@gmail.com', '30303', '2611', '30303', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd07e70efcfab08731a97e7b91be644de', NULL, NULL, NULL, NULL, NULL, 'JEPKOSGEI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (459, '30500020136', '2021', '254721899761', '254721899761', 'jacobmoochi@gmail.com', 'jacobmoochi@gmail.com', '00300', '8127', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7fe1f8abaad094e0b5cb1b01d712f708', NULL, NULL, NULL, NULL, NULL, 'ABERE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (460, '30500020149', '2021', '254797066134', '254726939140', 'mukeraracheal@gmail.com', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '98b297950041a42470269d56260243a1', NULL, NULL, NULL, NULL, NULL, 'RACHEAL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (461, '30500020203', '2021', '254719159624', '254712830031', 'ruthkuria62@gmail.com', 'jackymuthoni8jackymuthoni@gmail.com', '20117', '60', 'NAIVASHA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0353ab4cbed5beae847a7ff6e220b5cf', NULL, NULL, NULL, NULL, NULL, 'KURIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (462, '30544101181', '2021', '254112408431', '254718698421', 'gregoilikwel@gmail.com', 'gregoryilikwel@gmail.com', '331', '331', 'MARALAL', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '51d92be1c60d1db1d2e5e7a07da55b26', NULL, NULL, NULL, NULL, NULL, 'GREGORY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (463, '31500011001', '2021', '254708808108', '254701743403', 'ntikoisakipanua@gmail.com', 'ntikoisakipanua@gmail.com', '00206', '92', 'KISERIAN', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '428fca9bc1921c25c5121f9da7815cde', NULL, NULL, NULL, NULL, NULL, 'MURUNYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (464, '31500011002', '2021', '254745765842', '254723943382', 'danielmwangi62@gmail.com', 'danielmwangi62@gmail.com', 'N/A', 'N/A', 'DAGORETTI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f1b6f2857fb6d44dd73c7041e0aa0f19', NULL, NULL, NULL, NULL, NULL, 'IRUNGU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (465, '31567210005', '2021', '254725588567', '254722365890', 'calejons@gmail.com', 'abdilo@gmail.com', '00200', '2411', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '68ce199ec2c5517597ce0a4d89620f55', NULL, NULL, NULL, NULL, NULL, 'ADAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (466, '33547101007', '2021', '254748237626', '254723693751', 'gladysmursoi1@gmail.com', 'williamchepkonga@gmail.com', '30403', '155', 'MARIGAT', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e836d813fd184325132fca8edcdfb40e', NULL, NULL, NULL, NULL, NULL, 'GLADYS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (467, '33547201089', '2021', '254794173916', '254742213044', 'kipropchebon60@gmail.com', 'kipropchebon60@gmail.com', '30403', '100', 'MARIGAT', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ab817c9349cf9c4f6877e1894a1faa00', NULL, NULL, NULL, NULL, NULL, 'KIPROP', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (468, '34518403025', '2021', '254715193635', '254715193635', 'cynthiajepkemoi04@gmail.com', 'cynthiajepkemoi04@gmail.com', '30700', '339', 'ITEN', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '877a9ba7a98f75b90a9d49f53f15a858', NULL, NULL, NULL, NULL, NULL, 'KIPROTICH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (469, '34520104018', '2021', '254724748986', '254724301734', 'taiwanshila@gmail.com', 'bernadinechirchir@gmail.com', '30700', '226', 'ITEN', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'dc6a6489640ca02b0d42dabeb8e46bb7', NULL, NULL, NULL, NULL, NULL, 'CHIRCHIR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (470, '35623114072', '2021', '254714003147', '254714003147', 'ojwangfelixonyango@gmail.com', '', '50404', '20', 'BUMALA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '26337353b7962f533d78c762373b3318', NULL, NULL, NULL, NULL, NULL, 'FELIX', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (471, '36600004268', '2021', '254105375238', '254105375238', 'gideonbaraza119@gmail.com', '', '40405', '35', 'AWENDO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8e6b42f1644ecb1327dc03ab345e618b', NULL, NULL, NULL, NULL, NULL, 'GIDEON', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (472, '36602101013', '2021', '254791773309', '254716640720', 'kizitoelia4@gmail.com', '', '30200', '499', 'KITALE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ef575e8837d065a1683c022d2077d342', NULL, NULL, NULL, NULL, NULL, 'WANYONYI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (473, '36602102292', '2021', '254115456719', '254715334697', 'dapperfrank843@gmail.com', 'dapperfrank843@gmail.com', '50200', '85', 'BUNGOMA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2050e03ca119580f74cca14cc6e97462', NULL, NULL, NULL, NULL, NULL, 'WERE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (474, '36630304032', '2021', '254704149018', '254710343632', 'jayopaa2002@gmail.com', 'saidyomarbashir@gmail.com', '', '384', 'KITALE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '25ddc0f8c9d3e22e03d3076f98d83cb2', NULL, NULL, NULL, NULL, NULL, 'OPAA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (475, '36635003034', '2021', '254717511957', '254720115992', 'chanyifrancis@gmail.com', 'chanyifrancis@gmail.com', '03', '323', 'JOMVU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5ef0b4eba35ab2d6180b0bca7e46b6f9', NULL, NULL, NULL, NULL, NULL, 'KATAMBI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (476, '37600005480', '2021', '254745516172', '254723907844', 'sandrawere@gmail.com', 'johnstoneopesa@gmail.com', '50100', '31', 'SHIANDA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '598b3e71ec378bd83e0a727608b5db01', NULL, NULL, NULL, NULL, NULL, 'OPESA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (477, '37616027077', '2021', '254748408546', '254729885733', 'cedricklyula@gmail.com', 'cedricklyula@gmail.com', '0505', '21200', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '74071a673307ca7459bcf75fbd024e09', NULL, NULL, NULL, NULL, NULL, 'CEDRICK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (478, '37617201026', '2021', '254718112990', '254111524960', 'ombaledorcas@gmail.com', '', '50102', '2', 'MUMIAS', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cfee398643cbc3dc5eefc89334cacdc1', NULL, NULL, NULL, NULL, NULL, 'OMBALE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (479, '37627106131', '2021', '254703864713', '254727278976', 'amosleslie21@gmail.com', 'aggreymusebe@gmail.com', '50102', '52 SHIANDA', 'MUMIAS', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd18f655c3fce66ca401d5f38b48c89af', NULL, NULL, NULL, NULL, NULL, 'MAKUNDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (480, '37631504033', '2021', '254711454358', '254711454358', 'veronicahnak22@gmail.com', '', '30202', '240', 'MOI''S BRIDGE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6ea2ef7311b482724a9b7b0bc0dd85c6', NULL, NULL, NULL, NULL, NULL, 'VERONICAH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (481, '38600006207', '2021', '254726127238', '254723711758', 'boitemmanuel86@gmail.com', 'boitkipsang28@gmail.com', '30104', '28 KIPKABUS', 'ELDORET', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9461cce28ebe3e76fb4b931c35a169b0', NULL, NULL, NULL, NULL, NULL, 'BOIT', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (482, '38600006259', '2021', '254790115517', '254725731921', 'ruthcirus@gmail.com', 'kimeu.ndolo@gmail.com', '90132', '66', 'SULTAN HAMUD', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f770b62bc8f42a0b66751fe636fc6eb0', NULL, NULL, NULL, NULL, NULL, 'NDOLO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (483, '38600006333', '2021', '254701001992', '254707622003', 'samughbrian00@gmail.com', 'hellenatulia00@gmail.com', '', '', 'KITALE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e1e32e235eee1f970470a3a6658dfdd5', NULL, NULL, NULL, NULL, NULL, 'BRIAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (484, '38604102206', '2021', '254791525918', '254746350603', 'magdalynemuhonja4@gmail.com', 'imalipriscah364@gmail.com', '25', '35', 'MAJENGO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eba0dc302bcd9a273f8bbb72be3a687b', NULL, NULL, NULL, NULL, NULL, 'MUHONJA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (485, '38604103138', '2021', '254741142254', '254716305699', 'inambirichrispinus7@gmail.com', 'lugondot@gmail.com', '50100', '43', 'KAKAMEGA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '218a0aefd1d1a4be65601cc6ddc1520e', NULL, NULL, NULL, NULL, NULL, 'CHRISPINUS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (486, '38604120026', '2021', '254769900059', '254720307173', 'millicentamek123@gmail.com', 'atienomary2006@gmail.com', '00100', '9609', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7d04bbbe5494ae9d2f5a76aa1c00fa2f', NULL, NULL, NULL, NULL, NULL, 'OMONDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (487, '38619202116', '2021', '254737051086', '254728892317', 'adhiayamoses@gmail.com', 'adhiayamoses@gmail.com', '00100', '5040', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a516a87cfcaef229b342c437fe2b95f7', NULL, NULL, NULL, NULL, NULL, 'ATHIAYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1666, '18307201177', '2021', '254714080172', '254797051677', 'simonmutia254@gmail.com', '', '90300', '130', 'MAKUENI', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '86d7c8a08b4aaa1bc7c599473f5dddda', NULL, NULL, NULL, NULL, NULL, 'MUNYAO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (488, '39700001399', '2021', '254728814035', '254718677175', 'homeogola@gmail.com', 'ericogola2000@gmail.com', '40100', '3946', 'KISUMU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c3c59e5f8b3e9753913f4d435b53c308', NULL, NULL, NULL, NULL, NULL, 'OGOLA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (489, '39733104209', '2021', '254792599128', '254721112031', 'ochiengmanya22@gmail.com', '', '40122', '8', 'AWASI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '854d9fca60b4bd07f9bb215d59ef5561', NULL, NULL, NULL, NULL, NULL, 'KENNEDY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (490, '39733201414', '2021', '254793843920', '254741484396', 'gladysayodo@gmail.com', 'gladysayodo@gmail.com', '16', '16', 'KISUMU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c410003ef13d451727aeff9082c29a5c', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (491, '39733204022', '2021', '254734431025', '254114601482', 'okothlevis3@gmail.com', 'pjohnmurunga80@gmail.com', '40109', '216', 'SONDU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '559cb990c9dffd8675f6bc2186971dc2', NULL, NULL, NULL, NULL, NULL, 'OKOTH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (493, '39741005305', '2021', '254722487185', '254721259988', 'senseiilevinn@gmail.com', 'karihisbae@gmail.com', '00232', '1990 RUIRU', 'RUIRU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '2f55707d4193dc27118a0f19a1985716', NULL, NULL, NULL, NULL, NULL, 'NJUGUNA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (494, '40711112059', '2021', '254713518662', '254791984324', 'dalmasmose754@gmail.com', 'rosekembero1988@gmail.com', '40204', '52', 'OGEMBO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '1be3bc32e6564055d5ca3e5a354acbef', NULL, NULL, NULL, NULL, NULL, 'DALMAS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (495, '40727101013', '2021', '254718733459', '254706717947', 'jamesokindo2@gmail.com', 'morangawesley595@gmail.com', '40202', '171', 'KEROKA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '35051070e572e47d2c26c241ab88307f', NULL, NULL, NULL, NULL, NULL, 'JAMES', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (496, '40727101113', '2021', '254758310769', '254725897026', 'williamaminga36@gmail.com', '', '40200', '3796', 'KISII', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'b534ba68236ba543ae44b22bd110a1d6', NULL, NULL, NULL, NULL, NULL, 'AMINGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (497, '40727101388', '2021', '254113353880', '254708789169', 'brianogoro2@gmail.com', 'slvkmnt@gmail.com', '40202', '69', 'KEROKA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '7380ad8a673226ae47fce7bff88e9c33', NULL, NULL, NULL, NULL, NULL, 'OGORO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (498, '40727104068', '2021', '254781111798', '254723922960', 'maureenombaso44@gmail.com', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '05f971b5ec196b8c65b75d2ef8267331', NULL, NULL, NULL, NULL, NULL, 'NYATUKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (499, '40735101164', '2021', '254723341749', '254710647662', 'nichotom@gmail.com', 'oorotom@gmail.com', '00507', '78328', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '3cf166c6b73f030b4f67eeaeba301103', NULL, NULL, NULL, NULL, NULL, 'OORO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (501, '41704003074', '2021', '254704187768', '254716007674', 'yasminsydney3@gmail.com', 'walterdixon91@gmail.com', '60101', '828', 'MANYATTA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '5b69b9cb83065d403869739ae7f0995e', NULL, NULL, NULL, NULL, NULL, 'SYDNEY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (3822, '11200006128', '2021', '254799512026', '254723866595', 'rhodamurithi69@gmail.com', 'marynkirote01@gmail.com', '', '', 'MAUA', '1263177', 'K62', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0e57098d0318a954d1443e2974a38fac', NULL, NULL, NULL, NULL, NULL, 'MURITHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (502, '41704006034', '2021', '254705346843', '254712631190', 'pediwao4@gmail.com', 'iankwamboka2@gmail.com', '40303', '22', 'RANGWE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'b5b41fac0361d157d9673ecb926af5ae', NULL, NULL, NULL, NULL, NULL, 'OMONCHE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (503, '41710301243', '2021', '254710112280', '254710112280', 'benterakeyo@gmail.com', 'benterakeyo@gmail.com', '10', '10', 'KANDIEGE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '285e19f20beded7d215102b49d5c09a0', NULL, NULL, NULL, NULL, NULL, 'OCHIENG''', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (504, '41710312007', '2021', '254759542571', '254101692726', 'charlescalvine29@gmail.com', 'charlescalvine29@gmail.com', '40308', '56', 'SINDO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'b337e84de8752b27eda3a12363109e80', NULL, NULL, NULL, NULL, NULL, 'OGUTU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (505, '41731101085', '2021', '254740481061', '254725849180', 'mbogathomas2002@gmail.com', 'otomurose@gmail.com', '40301', '14', 'KENDU BAY', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'e8c0653fea13f91bf3c48159f7c24f78', NULL, NULL, NULL, NULL, NULL, 'THOMAS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (506, '41731303510', '2021', '254726620438', '254726620438', 'johnmorara10@gmail.com', 'johnmorara10@gmail.com', '40200', '1841- KISII', 'MAGENA/ KISII', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'ff4d5fbbafdf976cfdc032e3bde78de5', NULL, NULL, NULL, NULL, NULL, 'JOSHUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (507, '41742105221', '2021', '254717692815', '254729904823', 'collinceblair2022@gmail.com', '', '40303', '103', 'HOMABAY', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '2d6cc4b2d139a53512fb8cbb3086ae2e', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (508, '42700005254', '2021', '254705118981', '254745754934', 'ochiengmichael493@gmail.com', '', '40602', '2', 'NDORI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '389bc7bb1e1c2a5e7e147703232a88f6', NULL, NULL, NULL, NULL, NULL, 'OCHIENG''', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (509, '42700007124', '2021', '', '', '', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'e2230b853516e7b05d79744fbd4c9c13', NULL, NULL, NULL, NULL, NULL, 'SOPHIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (510, '42721101038', '2021', '254705013521', '254794840243', 'mickeykathode@gmail.com', 'vincentachola7@gmail.com', '40606', '66', 'UGUNJA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '087408522c31eeb1f982bc0eaf81d35f', NULL, NULL, NULL, NULL, NULL, 'ODHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (511, '42721101269', '2021', '254705055751', '254707771675', 'www@lywinrandy.co.ke', '', '', '', '', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'a760880003e7ddedfef56acb3b09697f', NULL, NULL, NULL, NULL, NULL, 'GODE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (512, '42749107023', '2021', '254721292545', '254702333885', 'sawagongohigh@yahoo.com', 'orondolawrance554@gmail.com', '40612', '120', 'SAWAGONGO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '10a7cdd970fe135cf4f7bb55c0e3b59f', NULL, NULL, NULL, NULL, NULL, 'ODUOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (513, '42749107053', '2021', '254721292573', '254745234600', 'sawagongohigh@yahoo.com', 'vogola126@gmail.com', '40612', '120', 'SAWAGONGO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '3dc4876f3f08201c7c76cb71fa1da439', NULL, NULL, NULL, NULL, NULL, 'OGOLA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (514, '43700012230', '2021', '254115271029', '254726080974', 'johnfaithboke@gmail.com', 'johnnyangwe111@gmail.com', '40417', '32', 'NTIMARU', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '59b90e1005a220e2ebc542eb9d950b1e', NULL, NULL, NULL, NULL, NULL, 'JOHN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (515, '43706115078', '2021', '254726806987', '254726806987', 'oyaromageto@gmail.com', 'oyaromageto@gmail.com', '40200', '3869', 'KISII', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '2b8a61594b1f4c4db0902a8a395ced93', NULL, NULL, NULL, NULL, NULL, 'OMARI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (516, '43720111063', '2021', '254702027275', '254110385767', 'alexochenge@gmail.com', 'erickochenge47@gmail.com', '498', 'KRK', 'KEROKA', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'f3f27a324736617f20abbf2ffd806f6d', NULL, NULL, NULL, NULL, NULL, 'OCHENGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (517, '43720111129', '2021', '254796291337', '254710182062', 'faithnyangena75@gmail.com', 'rsamwel2022@gmail.com', '40506', '88', 'KEBIRIGO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '38913e1d6a7b94cb0f55994f679f5956', NULL, NULL, NULL, NULL, NULL, 'NYANGENA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (518, '43728214069', '2021', '254113795484', '254713581846', 'kemuntodebra2003@gmail.com', 'norahgechemba14@gmail.com', '40501', '9', 'IKONGE', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'ebd9629fc3ae5e9f6611e2ee05a31cef', NULL, NULL, NULL, NULL, NULL, 'ONYINKWA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (519, '44717101107', '2021', '254711204264', '254728455773', 'ocholajoseph99@gmail.com', 'ocholajoseph99@gmail.com', '40400', '195-RONGO', 'RONGO', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '63538fe6ef330c13a05a3ed7e599d5f7', NULL, NULL, NULL, NULL, NULL, 'OJWANG''', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1728, '20400008151', '2021', '254746616756', '254722396281', 'rickyozib@gmail.com', 'mwashoh@gmail.com', '80100', '84355', 'MOMBASA', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7137debd45ae4d0ab9aa953017286b20', NULL, NULL, NULL, NULL, NULL, 'RICKY', 'mwasho', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (522, '47812101020', '2021', '254725677585', '254720518061', 'aminabass987@gmail.com', 'aminabass987@gmail.com', '00100', '19426', 'NAIROBI', '1263120', 'I20', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '53fde96fcc4b4ce72d7739202324cd49', NULL, NULL, NULL, NULL, NULL, 'MOHAMED', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (523, '3126101020', '2021', '254722260800', '254722260800', 'aftin101@gmail.com', 'aftin101@gmail.com', '00100', '15410', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '2bb232c0b13c774965ef8558f0fbd615', NULL, NULL, NULL, NULL, NULL, 'CHORICHA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (524, '7200009272', '2021', '254724514802', '254720533104', 'annki@gmail.com', 'annki@gmail.com', '232', 'NANYUKI', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'ba2fd310dcaa8781a9a652a31baf3c68', NULL, NULL, NULL, NULL, NULL, 'KINYUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (525, '8200007137', '2021', '254706431930', '254729789333', 'kennedymacharia007@gmail.com', 'njeri.wangai1973@gmail.com', '10103', '79', 'MUKURWEINI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '69421f032498c97020180038fddb8e24', NULL, NULL, NULL, NULL, NULL, 'KENNEDY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (526, '8217101209', '2021', '254755604837', '254715413144', 'roy.m.githaiga@gmail.com', 'fgmuraya11@gmail.com', '00100', '44720', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '85422afb467e9456013a2a51d4dff702', NULL, NULL, NULL, NULL, NULL, 'GITHAIGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (527, '10200008010', '2021', '254745289190', '254721741416', 'eskay3526@gmail.com', 'sussymemu996@gmail.com', '01000', '1656', 'THIKA', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '13f320e7b5ead1024ac95c3b208610db', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (528, '10227301036', '2021', '254707548837', '254725683419', 'aaronhathaway16@gmail.com', 'lucygitangu@uonbi.ac.ke', '', '', '', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'f4be00279ee2e0a53eafdaa94a151e2c', NULL, NULL, NULL, NULL, NULL, 'GITANGU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (529, '11200001087', '2021', '254114831705', '254721178324', 'marknyakweba6@gmail.com', 'nyakwebah63@gmail.com', '60100', '256', 'EMBU', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '37f0e884fbad9667e38940169d0a3c95', NULL, NULL, NULL, NULL, NULL, 'NYAKWEBA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (530, '11200002354', '2021', '254701405393', '254729874500', 'kabariavirginia@gmail.com', 'faamserve@gmail.com', '00100', '260', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'd64a340bcb633f536d56e51874281454', NULL, NULL, NULL, NULL, NULL, 'KABARIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (531, '11200003421', '2021', '254707027359', '254722431546', 'brianmurigi19@gmail.com', '', '', '', '', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '0fcbc61acd0479dc77e3cccc0f5ffca7', NULL, NULL, NULL, NULL, NULL, 'MURIGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (532, '11231207127', '2021', '254110040420', '254729302911', 'githumbidazly@gmail.com', 'siphiranjoki25@gmail.com', '01003', '1', 'GITUAMBA', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '298f95e1bf9136124592c8d4825a06fc', NULL, NULL, NULL, NULL, NULL, 'KOIGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (533, '12301703053', '2021', '254799041636', '254708958808', 'mutindasamuel665@gmail.com', 'theresiahmasavu@gmail.com', '90132', '160', 'SULTAN HAMUD', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'df877f3865752637daa540ea9cbc474f', NULL, NULL, NULL, NULL, NULL, 'SAMUEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (534, '13365101119', '2021', '254797258654', '254705221124', 'mwendwakennedy806@gmail.com', '', '00518', '453-00518', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'c399862d3b9d6b76c8436e924a68c45b', NULL, NULL, NULL, NULL, NULL, 'KENNEDY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (535, '14300006182', '2021', '254746369024', '254722904972', 'jameskalera6@gmail.com', 'festuskalera@gmail.com', '60602', '179', 'KIANJAI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '33e8075e9970de0cfea955afd4644bb2', NULL, NULL, NULL, NULL, NULL, 'JAMES', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (536, '14300011026', '2021', '254784905244', '254714088085', 'ngatiasabina@gmail.com', 'jngatia678@gmail.com', '00100', 'GITHURAI 45', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '65658fde58ab3c2b6e5132a39fae7cb9', NULL, NULL, NULL, NULL, NULL, 'NGATIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (537, '14341201091', '2021', '254705782384', '254725644658', 'muneneian190@gmail.com', '', '', '', '', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '5ea1649a31336092c05438df996a3e59', NULL, NULL, NULL, NULL, NULL, 'MURIITHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (538, '19300004021', '2021', '254113337129', '254712884459', 'loisenyamburakibue@gmail.com', 'davidmwangi759@gmail.com', '10303', '13', 'WANGURU', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '7bcdf75ad237b8e02e301f4091fb6bc8', NULL, NULL, NULL, NULL, NULL, 'KIBUE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (539, '20400002007', '2021', '254706947431', '254706947431', 'onsarezedekiah@gmail.com', 'onsarezedekiah@gmail.com', '40107', '2', 'MUHORONI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '5737034557ef5b8c02c0e46513b98f90', NULL, NULL, NULL, NULL, NULL, 'NYAGAKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (540, '20400002032', '2021', '254759554937', '254724707297', 'kibettony68@gmail.com', 'susanajemaiyo999@gmail.com', '50', '50 KAPSOWAR', 'KAPSOWAR', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '9b72e31dac81715466cd580a448cf823', NULL, NULL, NULL, NULL, NULL, 'KIBET', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (541, '20400002035', '2021', '254111705055', '254729916519', 'davyayiecha016@gmail.com', 'keruboroselidah855@gmail.com', '100', '037', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '16c222aa19898e5058938167c8ab6c57', NULL, NULL, NULL, NULL, NULL, 'OREKO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (542, '20400002442', '2021', '254706592710', '254722794709', 'shawnamunga.sa@gmail.com', '', '', '', '', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '7dcd340d84f762eba80aa538b0c527f7', NULL, NULL, NULL, NULL, NULL, 'AMUNGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (543, '20400006054', '2021', '254721343182', '254734343182', 'shish.mishan008@gmail.com', 'nafula.musungu@gmail.com', '00625', '23392', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '81448138f5f163ccdba4acc69819f280', NULL, NULL, NULL, NULL, NULL, 'MISHAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (544, '20403002089', '2021', '254795386424', '254729905659', 'sidsow1134@gmail.com', 'sidsow8237@gmail.com', '00100', '40420', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '97e8527feaf77a97fc38f34216141515', NULL, NULL, NULL, NULL, NULL, 'HASSAN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (545, '20404003178', '2021', '254745235993', '254722732727', 'malkam945@gmail.com', 'kamandamucheke@gmail.com', '00100', '6422', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '647bba344396e7c8170902bcf2e15551', NULL, NULL, NULL, NULL, NULL, 'KAMANDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (546, '20406005047', '2021', '254704418548', '254714229259', 'angelajoy444@gmail.com', 'vivianwadida@gmail.com', '', '', '', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'ed265bc903a5a097f61d3ec064d96d2e', NULL, NULL, NULL, NULL, NULL, 'AWUOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (547, '20410001113', '2021', '254722733300', '254712374388', 'moigirls@yahoo.com', 'angiesaura17@gmail.com', '00100', '43112', 'NRB', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'c75b6f114c23a4d7ea11331e7c00e73c', NULL, NULL, NULL, NULL, NULL, 'ANGELA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (548, '20410002199', '2021', '254769511665', '254728960752', 'frunk@gmail.com', 'hezronmomanyisitima@gmail.com', '00505', '21660', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '8d34201a5b85900908db6cae92723617', NULL, NULL, NULL, NULL, NULL, 'MOMANYI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (549, '21525301177', '2021', '254700050913', '254706234673', 'mannuperazim@gmail.com', 'faithajikon23@gmail.com', '30500', '218', 'LODWAR', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'ccb1d45fb76f7c5a0bf619f979c6cf36', NULL, NULL, NULL, NULL, NULL, 'LOKICHARI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (551, '23500014504', '2021', '254706412821', '254712380680', 'felixosoro718@gmail.com', '', '', '', '', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '7f24d240521d99071c93af3917215ef7', NULL, NULL, NULL, NULL, NULL, 'OSORO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (3823, '11207101225', '2021', '', '', '', '', '', '', '', '1263177', 'K62', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bca382c81484983f2d437f97d1e141f3', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (554, '27552001308', '2021', '254705573569', '254722744679', 'kaundaa75@gmail.com', 'maureenkaush@yahoo.com', '', '', '', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '5e388103a391daabe3de1d76a6739ccd', NULL, NULL, NULL, NULL, NULL, 'ALVIN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (555, '28500005138', '2021', '254795849555', '254725408010', 'camillapertet@gmail.com', 'simirenpertet@gmail.com', '20500', '223', 'NAROK', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '15de21c670ae7c3f6f3f1f37029303c9', NULL, NULL, NULL, NULL, NULL, 'PERTET', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (556, '28512101068', '2021', '254710452536', '254724332489', 'chemuletting@gmail.com', 'cheptuucaren@gmail.com', '20200', '1441', 'KERICHO', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '11b921ef080f7736089c757404650e40', NULL, NULL, NULL, NULL, NULL, 'BITOK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (557, '31500026006', '2021', '254759176433', '254710652883', 'sirerejacob5@gmail.com', 'sirerejacob5@gmail.com', '01100', '84', 'KAJIADO', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '6e2713a6efee97bacb63e52c54f0ada0', NULL, NULL, NULL, NULL, NULL, 'JACOB', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (558, '33500018101', '2021', '254703996008', '254722630759', 'tatmark@gmail.com', 'hellena.idda@gmail.com', '00200', '459', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '1bb91f73e9d31ea2830a5e73ce3ed328', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (560, '36600002257', '2021', '254701484401', '254718037855', 'blibwob26@gmail.com', 'blibwob26@gmail.com', '00200', 'P.O BOX', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'a9a6653e48976138166de32772b1bf40', NULL, NULL, NULL, NULL, NULL, 'FAITH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (561, '36600004134', '2021', '254794677700', '254722685153', 'g.nabiswa001@gmail.com', 'o.nabiswa@gmail.com', '', '36431-00200', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '58ae749f25eded36f486bc85feb3f0ab', NULL, NULL, NULL, NULL, NULL, 'RHYSGLEN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (562, '36602102225', '2021', '254792224281', '254715420247', 'innocentclare38@gmail.com', 'john.khamala@diversy.com', '50200', '85', 'BUNGOMA', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '4e4b5fbbbb602b6d35bea8460aa8f8e5', NULL, NULL, NULL, NULL, NULL, 'BUYABO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (563, '39734405006', '2021', '254721445063', '254722820216', 'wa.wendy2022@gmail.com', 'ochanda2002@gmail.com', '40123', '19441', 'KISUMU', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '8eefcfdf5990e441f0fb6f3fad709e21', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (564, '40719103379', '2021', '254759263423', '254722428598', 'mireguerick@gmail.com', 'mireguerick@gmail.com', '254', '3114', 'IKOBA', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '1728efbda81692282ba642aafd57be3a', NULL, NULL, NULL, NULL, NULL, 'ERICK', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (565, '41742131056', '2021', '254791634449', '254705715235', 'achiengcaren96@gmail.com', 'jacktonopiyo@gmail.com', '40615', '241', 'BONDO', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'cbcb58ac2e496207586df2854b17995f', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (566, '42700005306', '2021', '254703278503', '254723979587', 'sbob95065@gmail.com', 'okoyobenbog@gmail.com', '00100', '46523', 'GPO', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'db85e2590b6109813dafa101ceb2faeb', NULL, NULL, NULL, NULL, NULL, 'OKOYO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (567, '42721210046', '2021', '254705385320', '254710325140', 'obarafredrick12@gmail.com', 'obarafredrick12@gmail.com', '40611', '38,NYILIMA', 'BONDO', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '99c5e07b4d5de9d18c350cdf64c5aa3d', NULL, NULL, NULL, NULL, NULL, 'OKOTH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (568, '42725210126', '2021', '254706474538', '254720399436', 'hillaryomondi777@gmail.com', 'georgeogola21@gmail.com', '40606', '321-40606', 'UGUNJA', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'dd458505749b2941217ddd59394240e8', NULL, NULL, NULL, NULL, NULL, 'OGOLLA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (569, '42749105037', '2021', '254797530981', '254714902328', 'geoffrylabille@gmail.com', '', '', '', '', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '8b16ebc056e613024c057be590b542eb', NULL, NULL, NULL, NULL, NULL, 'ODERO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (570, '43700012004', '2021', '254706872923', '254706872923', 'marywanjirumage@gmail.com', 'mageroduncan@gmail.com', '40500', 'P.O. BOX 53', 'NYAMIRA', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'a86c450b76fb8c371afead6410d55534', NULL, NULL, NULL, NULL, NULL, 'NDUGUTI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (571, '43706117373', '2021', '254706607062', '254720482299', 'makariosnyarango56@gmail.com', 'joashnyarango12@gmail.com', '0100', '4403', 'NAIROBI', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'c9892a989183de32e976c6f04e700201', NULL, NULL, NULL, NULL, NULL, 'MAKARIOS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (572, '1113105031', '2021', '254729420336', '254716516033', 'tiffahomar02@gmail.com', 'aminahamdan95@gmail.com', '80302', '231', 'TAVETA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'e6b4b2a746ed40e1af829d1fa82daa10', NULL, NULL, NULL, NULL, NULL, 'LATIFA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (573, '8200007328', '2021', '254731797141', '254717435467', 'anelkatirike@gmail.com', '', '', '', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'e5f6ad6ce374177eef023bf5d0c018b6', NULL, NULL, NULL, NULL, NULL, 'TIRIKE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (574, '8200010120', '2021', '254719881536', '254736882696', 'mellnyambu21@gmail.com', '', '', '', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'f0e52b27a7a5d6a1a87373dffa53dbe5', NULL, NULL, NULL, NULL, NULL, 'NYAMBURA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (575, '8200010132', '2021', '254114959917', '254719733254', 'taniakesh17@gmail.com', '', '10100', '182', 'KARATINA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'ffeabd223de0d4eacb9a3e6e53e5448d', NULL, NULL, NULL, NULL, NULL, 'WAWIRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (576, '8217202091', '2021', '254706827375', '254719462702', 'mwangidennis895@gmail.com', 'brianmuthii91@gmail.com', '10101', '51', 'KARATINA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'a7aeed74714116f3b292a982238f83d2', NULL, NULL, NULL, NULL, NULL, 'WAHOME', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (577, '9200011193', '2021', '254775713829', '254712011990', 'kerubosandra4@gmail.com', 'kerubosandra4@gmail.com', '', '', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'fde9264cf376fffe2ee4ddf4a988880d', NULL, NULL, NULL, NULL, NULL, 'KIBWAGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (578, '9203401103', '2021', '254111391555', '254719157018', 'derymmifalcon@gmail.com', 'bensonnjogu571@gmail.com', '10303', '15', 'KIRINYAGA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'a8849b052492b5106526b2331e526138', NULL, NULL, NULL, NULL, NULL, 'NJOGU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (579, '9222204166', '2021', '254720924474', '254720924474', 'caromuthonih@gmail.com', 'caromuthonih@gmail.com', '60400', '151', 'SIAKAGO', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '258be18e31c8188555c2ff05b4d542c3', NULL, NULL, NULL, NULL, NULL, 'MUNENE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (580, '10200013177', '2021', '254738965553', '254721965553', 'dorcaskaranja2000@gmail.com', 'dorcaskaranja2000@gmail.com', '00900', '1215', 'KIAMBU', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '069d3bb002acd8d7dd095917f9efe4cb', NULL, NULL, NULL, NULL, NULL, 'WANGARI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (581, '10226235090', '2021', '254115724084', '254726429890', 'stenjoroge04@gmail.com', 'ndekeiruth@gmail.com', '01003', '12', 'GITUAMBA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'c6e19e830859f2cb9f7c8f8cacb8d2a6', NULL, NULL, NULL, NULL, NULL, 'NDEKEI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (583, '11200003391', '2021', '254728605626', '254724899404', 'kingkimenju3@gmail.com', 'kimenju2003@gmail.com', '00100', '6840', 'NAIROBI', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '9ad6aaed513b73148b7d49f70afcfb32', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (582, '10229202025', '2021', '254110077609', '254724024063', 'gladysmuthoni092@gmail.com', 'gladysmuthoni092@gmail.com', '00515', '1000', 'NAIROBI', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '46922a0880a8f11f8f69cbb52b1396be', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1729, '20400008224', '2021', '254701710757', '254701531631', 'abdirizakjarso80@gmail.com', 'farhiyajarso@gmail.com', '', '', '', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '25e2a30f44898b9f3e978b1786dcd85c', NULL, NULL, NULL, NULL, NULL, 'JARSO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (553, '26509109055', '2021', '254769132977', '254743286709', 'alloysjayv@gmail.com', 'charlesian471@gmail.com', '2247', '2247', '30100', '1263121', 'F21', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'f387624df552cea2f369918c5e1e12bc', NULL, NULL, NULL, NULL, NULL, 'NYARIAKO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (587, '11232201159', '2021', '254716360310', '254724314951', 'paul.gitau11@gmail.com', 'paul.gitau11@gmail.com', '', '', '', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '766ebcd59621e305170616ba3d3dac32', NULL, NULL, NULL, NULL, NULL, 'GITAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (588, '12330136056', '2021', '254740506169', '254777937839', 'mozzbarry2012akanga@gmail.com', 'mozzbarryakanga@yahoo.com', '00200', '790', 'NAIROBI', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'daca41214b39c5dc66674d09081940f0', NULL, NULL, NULL, NULL, NULL, 'ELIZABETH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (589, '13302102169', '2021', '254740025393', '254720716919', 'kizzittonicholas@gmail.com', 'kizzittonicholas@gmail.com', '90125', '23', 'KALAWA', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '30bb3825e8f631cc6075c0f87bb4978c', NULL, NULL, NULL, NULL, NULL, 'MUSYOKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (590, '13353301118', '2021', '254113824212', '254717754057', 'timothyopili6@gmail.com', 'timothyopili6@gmail.com', '90200', '384', 'KITUI', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '08b255a5d42b89b0585260b6f2360bdd', NULL, NULL, NULL, NULL, NULL, 'MUTINDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (591, '14300011176', '2021', '254795221410', '254714444718', 'ayshamoha167@gmail.com', 'mejumaajuma1965@gmail.com', '', '', 'MOMBASA', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '3493894fa4ea036cfc6433c3e2ee63b0', NULL, NULL, NULL, NULL, NULL, 'MOHAMED', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (592, '14312321064', '2021', '', '', '', '', '', '', '', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'dbe272bab69f8e13f14b405e038deb64', NULL, NULL, NULL, NULL, NULL, 'NGARI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (593, '14341201063', '2021', '254762557371', '254721680480', 'kimaniwaweufrancis@gmail.com', 'murimihillary2@gmail.com', '10303', '128', 'WANG`URU', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'acc3e0404646c57502b480dc052c4fe1', NULL, NULL, NULL, NULL, NULL, 'KIMANI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (594, '18307102242', '2021', '254742692436', '254712154021', 'carlysusanao@gmail.com', 'carlyokello@gmail.com', '', '', '', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '076a0c97d09cf1a0ec3e19c7f2529f2b', NULL, NULL, NULL, NULL, NULL, 'OKELLO', 'achieng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (595, '18324103021', '2021', '254721924143', '254722487211', 'mutua.ruth.mwikali@gmail.com', 'gloria.mulwa@gmail.com', '90130', '364', 'NUNGUNI', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '04ecb1fa28506ccb6f72b12c0245ddbc', NULL, NULL, NULL, NULL, NULL, 'MUTUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (596, '18346109064', '2021', '254793526733', '254712059769', 'munyivaassumpta@gmail.com', 'ntheuflorence@gmail.com', '', '381', 'MATUU', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'b2eeb7362ef83deff5c7813a67e14f0a', NULL, NULL, NULL, NULL, NULL, 'MULI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (597, '18348114001', '2021', '254708731339', '254708731339', 'piuskimuyu607@gmail.com', 'piuskimuyu607@gmail.com', '90138', '16', 'MAKINDU', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '08c5433a60135c32e34f46a71175850c', NULL, NULL, NULL, NULL, NULL, 'DAVID', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (598, '20400003120', '2021', '254791046362', '254723786525', 'makenatalia450@gmil.com', 'lmurithi70@gmail.com', '00618', '236', 'NAIROBI', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6aca97005c68f1206823815f66102863', NULL, NULL, NULL, NULL, NULL, 'TALIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (584, '11200003489', '2021', '254715075263', '254720872603', 'alitahun21506@gmail.com', '', '', '', '', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, 'f5deaeeae1538fb6c45901d524ee2f98', NULL, NULL, NULL, NULL, NULL, 'ALI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (599, '20403004282', '2021', '', '', '', '', '', '', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3435c378bb76d4357324dd7e69f3cd18', NULL, NULL, NULL, NULL, NULL, 'NAWAL', 'haibe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (600, '20403004292', '2021', '254721969976', '254721969976', 'lukhaloann@gmail.com', 'lukhaloann@gmail.com', '00300', '6414', 'NAIROBI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd490d7b4576290fa60eb31b5fc917ad1', NULL, NULL, NULL, NULL, NULL, 'LUKHALO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (601, '20408015208', '2021', '254729621805', '254727629829', 'seyban614@gmail.com', 'mohamedabdi514@gmail.com', '00100', '34567', 'NAIROBI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b2f627fff19fda463cb386442eac2b3d', NULL, NULL, NULL, NULL, NULL, 'ZEYNAB', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (602, '20411004032', '2021', '254724240536', '254728935460', 'snslastmin23@gmail.com', 'robertkamau460@gmail.com', '00100', '3540', 'NAIROBI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c3992e9a68c5ae12bd18488bc579b30d', NULL, NULL, NULL, NULL, NULL, 'KINUTHIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (603, '21500012148', '2021', '254707764841', '254718920333', 'victorereng@gmail.com', 'isaacachila@gmail.com', '30500', '319', 'LODWAR', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd86ea612dec96096c5e0fcc8dd42ab6d', NULL, NULL, NULL, NULL, NULL, 'ERENG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (604, '21549112005', '2021', '254113193563', '254769569333', 'melphinemen4@gmail.com', 'ebenyojohn154@gmail.com', '30501', '63', 'KAKUMA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9cf81d8026a9018052c429cc4e56739b', NULL, NULL, NULL, NULL, NULL, 'KOMEN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (605, '23500014250', '2021', '254785393640', '254717602124', 'sleepymax4@gmail.com', 'gekoedward@gmail.com', '40400', '669 SUNA MIGORI', 'MIGORI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c361bc7b2c033a83d663b8d9fb4be56e', NULL, NULL, NULL, NULL, NULL, 'OMONDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (606, '23503318034', '2021', '254719630985', '254719630985', 'joashmokua@gmail.com', 'evans.ogeto@uonbi.ac.ke', '30200', '1364', 'KITALE', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '44c4c17332cace2124a1a836d9fc4b6f', NULL, NULL, NULL, NULL, NULL, 'OTORA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (607, '25583102082', '2021', '254746224884', '254708614717', 'godfreymangoli2@gmail.com', '', '50205', '544', 'WEBUYE', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'dc82d632c9fcecb0778afbc7924494a6', NULL, NULL, NULL, NULL, NULL, 'MANG;OLI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (608, '26576101038', '2021', '254768135688', '254710420388', 'kosgeiandrew58@gmail.com', 'kangogobenson17@yahoo.com', '60100', '714', 'ELDORET', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '996a7fa078cc36c46d02f9af3bef918b', NULL, NULL, NULL, NULL, NULL, 'KOSGEI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (609, '27500009142', '2021', '254722484410', '254721515557', 'elizabethjebet719@gmail.com', 'kelvinkemboi2019@gmail.com', '30100', '2455', 'ELDORET', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd7a728a67d909e714c0774e22cb806f2', NULL, NULL, NULL, NULL, NULL, 'KEMBOI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (611, '27564101193', '2021', '254705486427', '254723586381', 'gathaiyasamuel8@gmail.com', '', '20107', '40', 'NJORO', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8ebda540cbcc4d7336496819a46a1b68', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (612, '27564104243', '2021', '254759206024', '254716545929', 'daviob007@gmail.com', '', '', '1', 'EGERTON', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f76a89f0cb91bc419542ce9fa43902dc', NULL, NULL, NULL, NULL, NULL, 'OBARE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (613, '29513101145', '2021', '254736586451', '254729643000', 'irahwambui@gmail.com', 'rahabwangari709@gmail.com', '30300', '595', 'KAPSABET', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f29c21d4897f78948b91f03172341b7b', NULL, NULL, NULL, NULL, NULL, 'MUGWIRA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (614, '29523308047', '2021', '254740482923', '254715219079', 'chepchirchir029@gmail.com', '', '29523', '086 KOBUJOI', 'KOBUJOI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '851ddf5058cf22df63d3344ad89919cf', NULL, NULL, NULL, NULL, NULL, 'CHEPCHIRCHIR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (615, '29542102034', '2021', '254746344974', '254713101659', 'ricksequalizer@mail.com', 'rosemaryopere@gmail.com', '170', 'LUANDA', '170', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '58d4d1e7b1e97b258c9ed0b37e02d087', NULL, NULL, NULL, NULL, NULL, 'JEROTICH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1730, '20400008266', '2021', '254751787556', '254736784552', 'benjaminkibui818@gmail.com', '', '', '', '', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '851300ee84c2b80ed40f51ed26d866fc', NULL, NULL, NULL, NULL, NULL, 'KIONGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (586, '11230304090', '2021', '254703354688', '254703354688', 'mmayieka68@gmail.com', 'mmayieka68@gmail.com', '00217', '86', 'LIMURU', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 1, 1, '605ff764c617d3cd28dbbdd72be8f9a2', NULL, NULL, NULL, NULL, NULL, 'KARANJA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (616, '32519204045', '2021', '254742066314', '254721927571', 'lydialemiso@gmail.com', 'lydialemiso@gmail.com', '40700', '245', 'KILGORIS', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7750ca3559e5b8e1f44210283368fc16', NULL, NULL, NULL, NULL, NULL, 'LEMISO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (617, '33517101125', '2021', '254712899831', '254727366040', 'judithwandahwa@gmail.com', 'wandahwa2002@yahoo.com', '20100', '12248', 'NAKURU', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5d44ee6f2c3f71b73125876103c8f6c4', NULL, NULL, NULL, NULL, NULL, 'AMBUCHE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (618, '33517104144', '2021', '254726590494', '254798328428', 'faithkosgei@gmail.com', 'kevinkipsang2002@gmail.com', '30105', '53', 'SOY', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eb6fdc36b281b7d5eabf33396c2683a2', NULL, NULL, NULL, NULL, NULL, 'KOSGEI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (619, '36635021015', '2021', '254726712950', '254727439725', 'upenyom@gmail.com', 'eliudwep@yahoo.com', '', '', 'WEBUYE', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'cdc0d6e63aa8e41c89689f54970bb35f', NULL, NULL, NULL, NULL, NULL, 'MIRACLE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (620, '37600005034', '2021', '254762761371', '254727220850', 'nellyvictor59@gmail.com', 'lilianwekesa04@gmail.com', '90138', '12', 'MAKINDU', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b73dfe25b4b8714c029b37a6ad3006fa', NULL, NULL, NULL, NULL, NULL, 'VICTOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (621, '37615009041', '2021', '254112856287', '254723846485', 'miimahenry@gmail.com', '', '', '146 BUKURA', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '85fc37b18c57097425b52fc7afbb6969', NULL, NULL, NULL, NULL, NULL, 'MIIMA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (622, '37615009170', '2021', '254768026529', '254720452630', 'ikhaoya2021@gmail.com', 'rodgersamakobe.ra@gmail.com', '135', '135', 'CHAVAKALI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3871bd64012152bfb53fdf04b401193f', NULL, NULL, NULL, NULL, NULL, 'KHAOYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (623, '37616006096', '2021', '254715235312', '254722992009', 'robinfisha4@gmail.com', 'winfridahalwala@gmail.com', '00511', '', 'ONGATA RONGAI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a733fa9b25f33689e2adbe72199f0e62', NULL, NULL, NULL, NULL, NULL, 'SHITANDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (624, '37617211316', '2021', '254769865921', '254725011199', 'ryanoyando717@gmail.com', 'prudencemihegwa@gmail.com', '', '', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '48ab2f9b45957ab574cf005eb8a76760', NULL, NULL, NULL, NULL, NULL, 'MUGERA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (625, '37632201043', '2021', '254796674332', '254705366496', 'wererashid@gmail.com', 'mother@g.email.com', '50108', '51', 'LUGARI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '233509073ed3432027d48b1a83f5fbd2', NULL, NULL, NULL, NULL, NULL, 'WEKULO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (626, '38618205062', '2021', '254719824360', '254719824360', 'esalwa1965@yahoo.com', '', '50307', '304', 'LUANDA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '45645a27c4f1adc8a7a835976064a86d', NULL, NULL, NULL, NULL, NULL, 'BRANDON', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (627, '38619102079', '2021', '254748859493', '254710754513', 'peterop607@gmail.com', 'catherineolesia@gmail.com', '', '', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '185c29dc24325934ee377cfda20e414c', NULL, NULL, NULL, NULL, NULL, 'ESENA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (628, '38619105001', '2021', '254741021473', '254721971633', 'mwalimuisaiah@gmail.com', '', '50318', '84', 'GAMBOGI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '42e77b63637ab381e8be5f8318cc28a2', NULL, NULL, NULL, NULL, NULL, 'MAAFA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (629, '39733110003', '2021', '254745938995', '254725447224', 'patrickainga@gmail.com', 'patrickainga@gmail.com', '40101', '102', 'AHERO', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '051e4e127b92f5d98d3c79b195f2b291', NULL, NULL, NULL, NULL, NULL, 'FIDEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (630, '39733216299', '2021', '254715201133', '254712732319', 'protichbran17.com@gmail.com', 'protichbran17.com@gmail.com', '20212', '1262', 'KERICHO', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9cc138f8dc04cbf16240daa92d8d50e2', NULL, NULL, NULL, NULL, NULL, 'CHEPKEMOI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (631, '39733216342', '2021', '254720375067', '254722319763', 'damaclinearasa203@gmail.com', '', '40501', '17', 'IKONGE', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b7bb35b9c6ca2aee2df08cf09d7016c2', NULL, NULL, NULL, NULL, NULL, 'DAMACLINE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (632, '39734301003', '2021', '254700068644', '254729964546', 'paulomondioyoo@gmail.com', 'paulomondioyoo@gmail.com', '', '', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'abd815286ba1007abfbb8415b83ae2cf', NULL, NULL, NULL, NULL, NULL, 'OLIECH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (633, '40700003085', '2021', '254745976328', '254711616463', 'clementinapetina@gmail.com', 'philowino254@gmail.com', '', '', '', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '26dd0dbc6e3f4c8043749885523d6a25', NULL, NULL, NULL, NULL, NULL, 'PETINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (634, '40727101076', '2021', '254757265680', '254724429065', 'kevinmayoga7@gmail.com', 'verinerbitengo@gmail.com', '40200', '36 KENYENYA', 'KENYENYA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6766aa2750c19aad2fa1b32f36ed4aee', NULL, NULL, NULL, NULL, NULL, 'JACOB', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (635, '40727201056', '2021', '254792637296', '254745986576', 'zakayodominic18@gmail.com', 'geoffreyombui1997@gmail.com', '40204', 'P.O BOX', 'OGEMBO', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6a10bbd480e4c5573d8f3af73ae0454b', NULL, NULL, NULL, NULL, NULL, 'MOMANYI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (636, '40732120003', '2021', '254798206060', '254798206060', 'cynercybercafe@gmail.com', '', '00100', '200', 'NAIROBI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c5ab0bc60ac7929182aadd08703f1ec6', NULL, NULL, NULL, NULL, NULL, 'PETWEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (637, '41704003114', '2021', '254707546484', '254719531166', 'olivehtinega@gmail.com', 'harrietonchari@gmail.com', '40300', '6', 'HOMABAY', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a532400ed62e772b9dc0b86f46e583ff', NULL, NULL, NULL, NULL, NULL, 'ONDIEKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (638, '41724119032', '2021', '254110715856', '254724512318', 'odengafelix@gmail.com', '', '40302', '363', 'NDHIWA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4c27cea8526af8cfee3be5e183ac9605', NULL, NULL, NULL, NULL, NULL, 'ADHIAMBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (639, '41731101077', '2021', '254709129439', '254728066660', 'mboryjerry@gmail.com', 'mboryjerry@gmail.com', '00100', 'NAIROBI-NAMANGA ROAD', 'NAIROBI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0f96613235062963ccde717b18f97592', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (640, '41731303350', '2021', '254714668360', '254724543423', 'codeogembalode@gmail.com', 'paulowaga1@gmail.com', '00200', '54594', 'NAIROBI', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4ffce04d92a4d6cb21c1494cdfcd6dc1', NULL, NULL, NULL, NULL, NULL, 'OGALO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (641, '41742105253', '2021', '254718797575', '254718797575', 'steveeddysone041@gmail.com', '', '40303', '91', 'RANGWE', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '67e103b0761e60683e83c559be18d40c', NULL, NULL, NULL, NULL, NULL, 'STEVE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (642, '41743103071', '2021', '254794014823', '254791591130', 'stacyatieno669@gmail.com', 'otienobonyango5@gmail.com', '147', '147 SARE', 'AWENDO', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '291597a100aadd814d197af4f4bab3a7', NULL, NULL, NULL, NULL, NULL, 'STECY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (643, '41743204139', '2021', '254793986220', '254728885344', 'okoderyan92@gmail.com', 'okoderyan92@gmail.com', '40222', '373', 'OYUGIS', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9b698eb3105bd82528f23d0c92dedfc0', NULL, NULL, NULL, NULL, NULL, 'RAWAGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (644, '42712102319', '2021', '254759256875', '254721687277', 'a74117591@gmail.com', 'sherryleakinyi27@gmail.com', '40610', '571', 'YALA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8c7bbbba95c1025975e548cee86dfadc', NULL, NULL, NULL, NULL, NULL, 'OCHIENG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (645, '42712103155', '2021', '254788532034', '254704907967', 'lagonda4555@gmail.com', 'adhiamboirine20@gmail.com', '40601', '65', 'BONDO', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5e9f92a01c986bafcabbafd145520b13', NULL, NULL, NULL, NULL, NULL, 'LEAKY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (646, '42749101305', '2021', '254725079895', '254722550434', 'omondielijah@outlook.com', 'phena88wash@gmail.com', '40600', '680', 'SIAYA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0ff39bbbf981ac0151d340c9aa40e63e', NULL, NULL, NULL, NULL, NULL, 'LINUS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (647, '43722107070', '2021', '254791727182', '254726202870', 'alphanyakundi@gmail.com', 'elepresidente@gmail.com', '40200', '4161', 'KISII', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '303ed4c69846ab36c2904d3ba8573050', NULL, NULL, NULL, NULL, NULL, 'ALFASI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1731, '20400008289', '2021', '254794110459', '254722289859', 'davidsomba46@gmail.com', 'kmsomba@yahoo.com', '', '', '', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b29eed44276144e4e8103a661f9a78b7', NULL, NULL, NULL, NULL, NULL, 'SOMBA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (648, '45801112080', '2021', '254112892192', '254721497184', 'feizaarab9@gmail.com', 'kheira883@gmail.com', '', '', '70_100', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '443cb001c138b2561a0d90720d6ce111', NULL, NULL, NULL, NULL, NULL, 'FEIZA', 'mohamed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (649, '46807101030', '2021', '254769948455', '254700574185', 'abdijabarali6554@gmail.com', 'abdijabarali6554@gmail.com', '70200', '245', 'WAJIR', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '55b37c5c270e5d84c793e486d798c01d', NULL, NULL, NULL, NULL, NULL, 'ABDIQAFAR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (650, '47810107003', '2021', '254723525326', '254704841471', 'avinashabdi9@gmail.com', 'jefkuria551@gmail.com', '60700', '31-60700', 'MOYALE', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '884d247c6f65a96a7da4d1105d584ddd', NULL, NULL, NULL, NULL, NULL, 'ABDI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (651, '47811105158', '2021', '254729268708', '254720237226', 'hudeifahassan288@gmail.com', 'takaba2015@gmail.com', '70300', '66', 'MANDERA', '1263122', 'A22', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '55743cc0393b1cb4b8b37d09ae48d097', NULL, NULL, NULL, NULL, NULL, 'HUDEIFA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (652, '8200007153', '2021', '254707645765', '254728420558', 'muitaamos@gmail.com', 'mrgrtkamb2@gmail.com', '10102', '69', 'KIGANJO', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '30ef30b64204a3088a26bc2e6ecf7602', NULL, NULL, NULL, NULL, NULL, 'GACHANJA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (653, '8200010257', '2021', '254793875557', '254713141243', 'maureenndutamwangi@gmail.com', '', '10101', '182', 'KARATINA', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eaae339c4d89fc102edd9dbdb6a28915', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (654, '9200011264', '2021', '254711641766', '254722786517', 'nancywagichui@gmail.com', 'nancywagichui@gmail.com', '00100', '9186', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ab233b682ec355648e7891e66c54191b', NULL, NULL, NULL, NULL, NULL, 'GICHUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (655, '10226217108', '2021', '254705291640', '254720113517', 'mariegacheri@gmail.com', 'smwangi0720@gmail.com', '00100', '25890', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3d2d8ccb37df977cb6d9da15b76c3f3a', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (656, '10238101064', '2021', '254795775482', '254723295763', 'lucydaisy105@gmail.com', 'paulinenyaga753@gmail.com', '', '', '', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '26408ffa703a72e8ac0117e74ad46f33', NULL, NULL, NULL, NULL, NULL, 'KIHARA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (657, '11200002166', '2021', '254700464479', '254728415306', 'wanjirumuiruri11709@gmail.com', 'antomuinjo@yahoo.com', '45', '45 SABA SABA', 'CHAKA', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b4288d9c0ec0a1841b3b3728321e7088', NULL, NULL, NULL, NULL, NULL, 'MUIRURI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (658, '11200006175', '2021', '254707970809', '254723370195', 'mawaki2004@gmail.com', '', '', '', '', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2f37d10131f2a483a8dd005b3d14b0d9', NULL, NULL, NULL, NULL, NULL, 'KARIUKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (659, '11211307044', '2021', '254705413842', '254716253994', 'jescicanjeri@gmail.com', 'priscillawamaitha82@gmail.com', '', '', '', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0ff8033cf9437c213ee13937b1c4c455', NULL, NULL, NULL, NULL, NULL, 'MBURU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (660, '11231102193', '2021', '254783878430', '254723278649', 'patricianjoki17@gmail.com', 'esther.mbuthi@yahoo.com', '00600', '488', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '68264bdb65b97eeae6788aa3348e553c', NULL, NULL, NULL, NULL, NULL, 'WANJIRU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (661, '11235127032', '2021', '254712507047', '254713867264', 'bakhitanjeri1@gmail.com', 'tilakhardware2017@gmail.com', '30500', '465', 'LODWAR', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3a066bda8c96b9478bb0512f0a43028c', NULL, NULL, NULL, NULL, NULL, 'NDUTA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (662, '13302103145', '2021', '254797153807', '254721812876', 'kanzakioko12@gmail.com', 'jacksonkioko14@gmail.com', '90201', '5 IKUTHA', 'IKUTHA', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'be3159ad04564bfb90db9e32851ebf9c', NULL, NULL, NULL, NULL, NULL, 'CHRISTINE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (663, '13352201007', '2021', '254794818623', '254795900016', 'mutheumbiti@gmail.com', 'mbiti39@gmail.com', '165', '165', 'ISHIARA', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8757150decbd89b0f5442ca3db4d0e0e', NULL, NULL, NULL, NULL, NULL, 'MBITI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (664, '14333201158', '2021', '254115020376', '254722116878', 'natashaj.pendo@gmail.com', 'hoseanjagi@gmail.com', '10100', '308', 'NYERI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2291d2ec3b3048d1a6f86c2c4591b7e0', NULL, NULL, NULL, NULL, NULL, 'MUCHANGI', 'pendo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (665, '15300012254', '2021', '254714160637', '254714160637', 'gracenish2022@gmail.com', 'khellen123@gmail.com', '00623', '38371', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '84117275be999ff55a987b9381e01f96', NULL, NULL, NULL, NULL, NULL, 'HELLEN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (666, '20400002522', '2021', '254745456383', '254718581358', 'abdishakuraden04@gmail.com', 'osmankatra2@gmail.com', '70100', 'GARISSA', 'GARISSA TOWN', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fae0b27c451c728867a567e8c1bb4e53', NULL, NULL, NULL, NULL, NULL, 'ABDISHAKUR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (667, '20400006108', '2021', '254775100083', '254720971078', 'wambugujoy2180@gmail.com', 'smkmugure@gmail.com', '00100', '24631', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b5dc4e5d9b495d0196f61d45b26ef33e', NULL, NULL, NULL, NULL, NULL, 'JOY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (668, '20400006193', '2021', '254743798744', '254726172666', 'faithnjuguna43@gmail.com', 'kashatanjuguna@gmail.com', '00100', '30152', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '192fc044e74dffea144f9ac5dc9f3395', NULL, NULL, NULL, NULL, NULL, 'NJUGUNA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (669, '20404003033', '2021', '254729143235', '254726720834', 'victorngava17@gmail.com', 'sngava@yahoo.co.uk', '00200', '57714', 'KITENGELA', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5c04925674920eb58467fb52ce4ef728', NULL, NULL, NULL, NULL, NULL, 'NZIOKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (670, '20404006116', '2021', '254738422291', '254722487753', 'shawnmwenda600@gmail.com', 'estherkathuri@gmail.com', '00200', '79950', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '17c276c8e723eb46aef576537e9d56d0', NULL, NULL, NULL, NULL, NULL, 'KATHURI', 'mwenda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (671, '20406008024', '2021', '254111767339', '254722742781', 'lynnmwangi064@gmail.com', 'lexarservices@gmail.com', '', '', '', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5dd9db5e033da9c6fb5ba83c7a7ebea9', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (672, '20406008054', '2021', '254707422384', '254702898412', 'maryjudysigadah@gmail.com', 'izzosigadah@gmail.com', '00608', '', '407', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2dea61eed4bceec564a00115c4d21334', NULL, NULL, NULL, NULL, NULL, 'SIGADAH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (673, '20410001044', '2021', '254703416205', '254711109494', 'mercysyombuanzuki@gmail.com', 'mercysyombuanzuki@gmail.com', '00100', '43112', 'MACHAKOS', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9f396fe44e7c05c16873b05ec425cbad', NULL, NULL, NULL, NULL, NULL, 'NZUKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (674, '20410001302', '2021', '254721302776', '254721302776', 'moigirls@yahoo.com', 'mpatsenga@gmail.com', '00100', '43112', 'NRB', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0d7de1aca9299fe63f3e0041f02638a3', NULL, NULL, NULL, NULL, NULL, 'ATSENGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (675, '25563101122', '2021', '254713599874', '254713599874', 'patooplumber@gmail.com', 'patooplumber@gmail.com', '20406', '416', 'SOTIK', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8fecb20817b3847419bb3de39a609afe', NULL, NULL, NULL, NULL, NULL, 'VICTORINE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (676, '26509114014', '2021', '254748942445', '254728349472', 'peterwaroekpw@gmail.com', '', '', '', '', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'dc6a70712a252123c40d2adba6a11d84', NULL, NULL, NULL, NULL, NULL, 'KINYANJUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (677, '26534104009', '2021', '254745311086', '254725585051', 'ianspinter@gmail.com', '', '30100', '00', 'ELDORET', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '71a3cb155f8dc89bf3d0365288219936', NULL, NULL, NULL, NULL, NULL, 'TOO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (678, '27500009129', '2021', '254751993518', '254724637964', 'nyaboemary9@gmail.com', 'amochoge76@gmail.com', '20100', '1352', 'NAKURU', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9fe8593a8a330607d76796b35c64c600', NULL, NULL, NULL, NULL, NULL, 'MOCHOGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (679, '27538204026', '2021', '254796293034', '254720587538', 'naomichebet113@gmail.com', 'mutaivivi@gmail.com', '20100', '113', 'NAKURU', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ca9c267dad0305d1a6308d2a0cf1c39c', NULL, NULL, NULL, NULL, NULL, 'NAOMI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (680, '28500005305', '2021', '254700790721', '254717777042', 'chepkoechmeda@gmail.com', '', '20200', '1739', 'KERICHO', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fccb3cdc9acc14a6e70a12f74560c026', NULL, NULL, NULL, NULL, NULL, 'MEDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (681, '28522520051', '2021', '254728924115', '254729678005', 'yegonfaith05@gmail.com', 'lilykoros@gmail.com', '20210', '125', 'LITEIN', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1595af6435015c77a7149e92a551338e', NULL, NULL, NULL, NULL, NULL, 'CHEPKIRUI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (682, '29500025130', '2021', '254703552497', '254722445719', 'mercyjeplerotich@gmail.com', 'dominicrotich2@gmail.com', '30305', '45', 'KOBUJOI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '08d98638c6fcd194a4b1e6992063e944', NULL, NULL, NULL, NULL, NULL, 'JEPLETING', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (683, '29513101167', '2021', '254797703739', '254712544585', 'jewelsabwami@gmail.com', 'rsabwami@gmail.com', '50200', '2525', 'BUNGOMA', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '24681928425f5a9133504de568f5f6df', NULL, NULL, NULL, NULL, NULL, 'SABWAMI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (684, '29513101179', '2021', '254794421539', '254720580122', 'bettfeyo@gmail.com', 'lilian.jbett@yahoo.com', '30300', '58', 'KAPSABET', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '556f391937dfd4398cbac35e050a2177', NULL, NULL, NULL, NULL, NULL, 'CHEPKORIR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (685, '30556317020', '2021', '254111797917', '254723657204', 'kamaudorcas04@gmail.com', 'annnganga301@gmail.com', '20107', '40', 'NJORO', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '3328bdf9a4b9504b9398284244fe97c2', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (686, '31500011084', '2021', '254704512507', '254724377004', 'lewinangel53@gmail.com', 'phylmuhati@gmail.com', '', '', '', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '109a0ca3bc27f3e96597370d5c8cf03d', NULL, NULL, NULL, NULL, NULL, 'ANGEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (687, '32519103001', '2021', '254723621584', '254723621584', 'seneyialeah4@gmail.com', 'nakuromoses@gmail.com', '40700', '19', 'KILGORIS', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7f5d04d189dfb634e6a85bb9d9adf21e', NULL, NULL, NULL, NULL, NULL, 'KINYAMAL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (688, '34518401074', '2021', '254700921591', '254115821950', 'darealtkay@gmail.com', 'emmanuelkipkoech637@gmail.com', '100', '30746', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f79921bbae40a577928b76d2fc3edc2a', NULL, NULL, NULL, NULL, NULL, 'ROTICH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (689, '37627109001', '2021', '254720405260', '254724648143', 'anyangobranis01@yahoo.com', 'amunemonica@gmail.com', '50505', '368', 'BUTERE', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '07a96b1f61097ccb54be14d6a47439b0', NULL, NULL, NULL, NULL, NULL, 'BRANIS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (690, '38604101101', '2021', '254791005836', '254702035713', 'maxwelllikhanga635@gmail.com', 'amagovelihono@gmail.com', '', '', '', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c06d06da9666a219db15cf575aff2824', NULL, NULL, NULL, NULL, NULL, 'MUHANJI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (691, '41700010193', '2021', '254711975214', '254726758022', 'wambasipeter79@gmail.com', 'isabelbiketi@yahoo.co.uk', '00100', '46579', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '10a5ab2db37feedfdeaab192ead4ac0e', NULL, NULL, NULL, NULL, NULL, 'PETER', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (692, '41724002092', '2021', '254727648596', '254723168899', 'levisjabuya@gmail.com', 'christopherjabuya2017e@gmail.com', '40404', '7 RONGO', 'RONGO', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e555ebe0ce426f7f9b2bef0706315e0c', NULL, NULL, NULL, NULL, NULL, 'LEVIS', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (693, '42721208130', '2021', '254111913852', '254725237445', 'www.leakeyochieng@gmail.com', 'www.moseswalker345@gmail.com', '16', '16', 'NDORI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '53e3a7161e428b65688f14b84d61c610', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (694, '42738101023', '2021', '254729454436', '254724816991', 'nickiszone5@gmail.com', 'latieno19@yahoo.com', '00100', '31624', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5487315b1286f907165907aa8fc96619', NULL, NULL, NULL, NULL, NULL, 'MONICAH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (695, '42738101256', '2021', '254794356733', '254724773348', 'stacyakinyi412@gmail.com', 'samodipo@gmail.com', '00100', '41573', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e4bb4c5173c2ce17fd8fcd40041c068f', NULL, NULL, NULL, NULL, NULL, 'ORIEWA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (696, '42749101269', '2021', '', '', '', '', '', '', '', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0cb929eae7a499e50248a3a78f7acfc7', NULL, NULL, NULL, NULL, NULL, 'ODUOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (697, '45806201184', '2021', '254703538935', '254743494851', 'shikukuherine@gmail.com', 'bisharshallow@gmail.com', '00610', '16271', 'NAIROBI', '1263124', 'A24', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8a0e1141fd37fa5b98d5bb769ba1a7cc', NULL, NULL, NULL, NULL, NULL, 'FARTUN', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (698, '1100003024', '2021', '254769067752', '254702766790', 'sammyshoka@gmail.com', 'safariremmy@gmail.com', '80108', '930', 'KILIFI', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '99bcfcd754a98ce89cb86f73acc04645', NULL, NULL, NULL, NULL, NULL, 'SHOKA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (699, '8200010281', '2021', '254713678399', '254700656056', 'janicekariithi@gmail.com', 'kfranciskariithi@gmail.com', '00101', '104031', 'NAIROBI', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'afd4836712c5e77550897e25711e1d96', NULL, NULL, NULL, NULL, NULL, 'JANICE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (700, '8237001138', '2021', '254700811414', '254723705806', 'mervynk020@gmail.com', 'pakmaina@gmail.com', '10106', '452', 'OTHAYA', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e5841df2166dd424a57127423d276bbe', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (701, '8237004074', '2021', '254758015188', '254729298837', 'lewismugo43@gmail.com', 'fronjema@gmail.com', '10101', '93', 'KARATINA', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b4a528955b84f584974e92d025a75d1f', NULL, NULL, NULL, NULL, NULL, 'NJERI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (702, '8237004098', '2021', '254794162732', '254711709766', 'earnestkihara7@gmail.com', 'earnestkihara7@gmail.com', '10106', '100', 'OTHAYA', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b1eec33c726a60554bc78518d5f9b32c', NULL, NULL, NULL, NULL, NULL, 'KIHARA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (703, '8237005253', '2021', '254793846750', '254711312866', 'mwangijohnndirangu03@gmail.com', '', '', '', '', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd6c651ddcd97183b2e40bc464231c962', NULL, NULL, NULL, NULL, NULL, 'NDIRANGU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (704, '10226202146', '2021', '254703694139', '254726179926', 'bachwaitherero@gmail.com', '', '', '', '', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f64eac11f2cd8f0efa196f8ad173178e', NULL, NULL, NULL, NULL, NULL, 'PETER', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (705, '11232201242', '2021', '254774242522', '254724394174', 'ryanjuguna2k2@gmail.com', 'musafasam@gmail.com', '00900', '601', 'KIAMBU', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4a47d2983c8bd392b120b627e0e1cab4', NULL, NULL, NULL, NULL, NULL, 'MUCHABA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (706, '12300001432', '2021', '254734732678', '254722350997', 'paulmartinowiti@gmail.com', 'marthawitts@gmail.com', '', '', '', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '9c82c7143c102b71c593d98d96093fde', NULL, NULL, NULL, NULL, NULL, 'OPANGA', 'owiti', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (707, '12300013112', '2021', '254757761396', '254702792889', 'vyonnevulimu@gmail.com', 'jaqyayuma@gmail.com', '', '', '', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '500e75a036dc2d7d2fec5da1b71d36cc', NULL, NULL, NULL, NULL, NULL, 'OKWIRI', 'vulimu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (708, '12301703019', '2021', '254737647044', '254725802653', 'jos_lid@yahoo.com', 'jkyalloh@gmail.com', '90100', 'N/A', 'MACHAKOS', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ae0eb3eed39d2bcef4622b2499a05fe6', NULL, NULL, NULL, NULL, NULL, 'KYALO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (709, '12301703121', '2021', '254743618706', '254724069071', 'kigondualvin@gmail.com', '', '', '', '', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '1ecfb463472ec9115b10c292ef8bc986', NULL, NULL, NULL, NULL, NULL, 'KIGONDU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (710, '12301719020', '2021', '', '', '', '', '', '', '', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e70611883d2760c8bbafb4acb29e3446', NULL, NULL, NULL, NULL, NULL, 'WAMBUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (711, '14300006238', '2021', '254739030423', '254721431368', 'corneliusmucheru139@gmail.com', 'lydianjoki557@gmail.com', '', '88', 'RUIRU', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6081594975a764c8e3a691fa2b3a321d', NULL, NULL, NULL, NULL, NULL, 'NJOKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (712, '19300004176', '2021', '254723438014', '254723539760', 'lauramakena04@gmail.com', 'marydickson412@gmail.com', '01000', '1483', 'THIKA', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '19bc916108fc6938f52cb96f7e087941', NULL, NULL, NULL, NULL, NULL, 'MURITHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (713, '20401101044', '2021', '254704542743', '254715680180', 'raymond.c.sang@gmail.com', 'raymond.c.sang@gmail.com', '00100', '35555', 'NAIROBI', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '07c5807d0d927dcd0980f86024e5208b', NULL, NULL, NULL, NULL, NULL, 'CHERUTO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6927, '20400009010', '2021', '254795414181', '254721596840', 'lnyambura@gmail.com', 'dkahuhi@gmail.com', '', '', '', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0ad5292c158f3924f8b480367fcbeb94', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6928, '20401001117', '2021', '254772496782', '254707090943', 'mwangiedwin170@gmail.com', '', '', '', '', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '20c1945eae4b9868cbbfd09675f7d76e', NULL, NULL, NULL, NULL, NULL, 'MACHARIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6929, '20405004082', '2021', '254715394262', '254720841726', 'caren.jeruto01@gmail.com', 'caroline.jemeli@gmail.com', '00100', '6102', 'NAIROBI', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e0308d73972d8dd5e2dd27853106386e', NULL, NULL, NULL, NULL, NULL, 'JERUTO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6930, '20405004085', '2021', '254792307281', '254722639776', 'mwendekelijn@gmail.com', 'kelieunicejs@gmail.com', '', '', '', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2f254e66097fd653a5ca4cfdb33be358', NULL, NULL, NULL, NULL, NULL, 'KELI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6931, '20405004238', '2021', '254725840262', '254722840950', 'consolata2505murimi@gmail.com', 'mtutom@gmail.com', '01000', '8112', 'THIKA', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c70341de2c112a6b3496aec1f631dddd', NULL, NULL, NULL, NULL, NULL, 'MURIMI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6932, '20405009002', '2021', '254700355376', '254759507479', 'archgeorgeotis@gmail.com', 'marthashiraku044@gmail.com', '00100', '34224', 'NAIROBI', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'caaa29eab72b231b0af62fbdff89bfce', NULL, NULL, NULL, NULL, NULL, 'GEORGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6933, '20406008028', '2021', '254733677509', '254738415663', 'tyramulisa123@gmail.com', 'elsamulisa@yahoo.com', '00100', '16899', 'NAIROBI', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ef2ee09ea9551de88bc11fd7eeea93b0', NULL, NULL, NULL, NULL, NULL, 'MULISA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6934, '20406010048', '2021', '254731638728', '254727100954', 'okelloenoch501@gmail.com', 'eucabeth.okello@gmail.com', '00100', '42276', 'NAIROBI', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '10ffbba2ec9025b945acc154f3403aec', NULL, NULL, NULL, NULL, NULL, 'OKELLO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6935, '20406020035', '2021', '70604458', '722407507', 'dani3lkamanthi@gmail.com', 'skamanthi@gmail.com', '00200', '154', 'NAIROBI', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ccce2fab7336b8bc8362d115dec2d5a2', NULL, NULL, NULL, NULL, NULL, 'KIMANTHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6936, '20406020058', '2021', '', '', '', '', '', '', '', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4a300d3a0ae99b58b0dfcd3fde526bf5', NULL, NULL, NULL, NULL, NULL, 'MUDIDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6937, '23500003150', '2021', '254711945497', '254721858773', 'dorothycheruto5@gmail.com', 'marustephen.sm@gmail.com', '', '', 'ONGATA RONAGAI', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8963920e8b402154316d8175fa189112', NULL, NULL, NULL, NULL, NULL, 'MARU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6938, '26509111134', '2021', '254790520335', '254725822194', 'baruchmogambi@gmail.com', 'baruchmogambi@gmail.com', '621', '621-40202, KEROKA', 'NAIROBI', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '33cf42b38bbcf1dd6ba6b0f0cd005328', NULL, NULL, NULL, NULL, NULL, 'ZIPPORAH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6939, '27500008221', '2021', '254799996269', '254721773199', 'ianlonduso@gmail.com', 'tonduso@gmail.com', '00208', '141', 'NGONG', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f056bfa71038e04a2400266027c169f9', NULL, NULL, NULL, NULL, NULL, 'ONDUSO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6941, '27552001251', '2021', '254708827602', '254724952369', 'joematebo75@gmail.com', 'matebokaan@gmail.com', '30200', '3418', 'KITALE', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'aad64398a969ec3186800d412fa7ab31', NULL, NULL, NULL, NULL, NULL, 'MATEBO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6942, '28500005106', '2021', '254776157410', '254728307503', 'monchichiyvette@gmail.com', 'maoberuth47@gmail.com', '00100', '18018', 'NAIROBI', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c236337b043acf93c7df397fdb9082b3', NULL, NULL, NULL, NULL, NULL, 'OGOTI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6943, '28500005307', '2021', '254791675965', '254720885948', 'ubahabdi938@gmail.com', 'seinabahmed593@gmail.com', '20100', '18158', 'NAKURU', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6a783b626a6d892a132dc195e5504272', NULL, NULL, NULL, NULL, NULL, 'SHEIKH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6944, '31500026098', '2021', '254705300142', '254743132885', 'kengeorgenjoroge@gmail.com', 'gakuoevans@gmail.com', '00502', '11', 'KAREN', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ba51e6158bcaf80fd0d834950251e693', NULL, NULL, NULL, NULL, NULL, 'MWANGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6945, '34559212043', '2021', '254798300533', '254720778055', 'sidneymasit@gmail.com', 'rkmasit@gmail.com', '30100', '5892', 'ELDORET', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0d98b597aa732aea606bde680c3b57d8', NULL, NULL, NULL, NULL, NULL, 'MASIT', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6946, '36600004226', '2021', '254753311415', '254753311415', 'charleskitre@gmail.com', 'fridahkitre@gmail.com', '', '', '', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6ea3f1874b188558fafbab78e8c3a968', NULL, NULL, NULL, NULL, NULL, 'WANJUSI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6947, '37615009017', '2021', '254743096630', '254115450208', 'benjaminngasi876@gmail.com', 'jumangasi@yahoo.com', '50105', '159', 'KAKAMEGA', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a424ded436368e3f9f10da14c23acc85', NULL, NULL, NULL, NULL, NULL, 'ANUNDA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6948, '38600006106', '2021', '254751425415', '254722839393', 'danstanjuma3@gmail.com', 'lucyagola27@gmail.com', '', '', '', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ff7a2112f8c3e3224ce8e3e26de1d932', NULL, NULL, NULL, NULL, NULL, 'ODWORI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6949, '39700009212', '2021', '254723491016', '254722444088', 'kemeivenessa@gmail.com', 'nmurei420@gmail.com', '30100', '2053', 'ELDORET', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f3be5eb7ff15f2013a3b65fbca4bf864', NULL, NULL, NULL, NULL, NULL, 'KEMEI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6950, '39741005040', '2021', '254743446063', '254724177918', 'odhiamtrevor@gmail.com', 'eunyedward@gmail.com', '40100', '2738-40100', 'KISUMU', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '99f0e91e4f90ecc1c3bdee598eadca30', NULL, NULL, NULL, NULL, NULL, 'TREVOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6951, '42705303041', '2021', '254112259210', '254707996335', 'okumuoumaferdinand808@gmail.com', 'jumajoseph808@gmail.com', '50410', '209', 'PORT VICTORIA', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f5cfbc876972bd0d031c8abc37344c28', NULL, NULL, NULL, NULL, NULL, 'OKUMU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6952, '43706110304', '2021', '254769070936', '254721851265', 'titusjohns702@gmail.com', '', '20100', '16057', 'NAKURU', '1263105', 'B05', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a61f27ab2165df0e18cc9433bd7f27c5', NULL, NULL, NULL, NULL, NULL, 'KIMONG''O', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6953, '3120102047', '2021', '254780806701', '254722382023', 'wardabdow@gmail.com', 'abdowhassan996@gmail.com', '', '', '', '1263106', 'N06', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5e34a2b4c23f4de585fb09a7f546f527', NULL, NULL, NULL, NULL, NULL, 'MOHAMUD', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6954, '4116302048', '2021', '254793004507', '254793004507', 'zackykhamis6@gmail.com', 'zackykhamis6@gmail.com', '80108', 'KILIFI', 'KILIFI', '1263106', 'N06', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '54ee290e80589a2a1225c338a71839f5', NULL, NULL, NULL, NULL, NULL, 'SAID', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6955, '7200014066', '2021', '254795107463', '254768108551', 'jeffiezjomo@gmail.com', 'jnnderitu@gmail.com', '57', '57', 'OLJOROROK', '1263106', 'N06', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '4fdaa19b1f22a4d926fce9bfc7c61fa5', NULL, NULL, NULL, NULL, NULL, 'NDUNG''U', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6956, '8202001140', '2021', '254722618007', '254722618007', 'orion1astrodeno@gmail.com', 'gladysmbiu54@gmail.com', '10100', '337', 'NYERI', '1263106', 'N06', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2377f9eb902f3c5855aca19197689b14', NULL, NULL, NULL, NULL, NULL, 'MUCHENE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6957, '8202003019', '2021', '254722233381', '254722233381', 'albertgitangu@gmail.com', 'jamesmbugua.74@gmail.com', '00217', '646', 'LIMURU', '1263106', 'N06', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6fd86e0ad726b778e37cf270fa0247d7', NULL, NULL, NULL, NULL, NULL, 'GITANGU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (6958, '8218102094', '2021', '254795762769', '254721723887', 'yvonnematu07@gmail.com', 'lilianmatu34@gmail.com', '10100', '100', 'NYERI', '1263106', 'N06', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '27e9661e033a73a6ad8cefcde965c54d', NULL, NULL, NULL, NULL, NULL, 'MATU', 'wairimu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (714, '20404003079', '2021', '254780540574', '254722839530', 'macharnelson505@gmail.com', 'rongoci@yahoo.com', '35360', '35360-00100', 'NAIROBI', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd14220ee66aeec73c49038385428ec4c', NULL, NULL, NULL, NULL, NULL, 'MACHARIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (715, '20404003163', '2021', '254113278452', '254702251025', 'nyandikoeliakim@gmail.com', 'nyandikoeliakim@gmail.com', '00200', '74913', 'NAIROBI', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8df707a948fac1b4a0f97aa554886ec8', NULL, NULL, NULL, NULL, NULL, 'JOSEPH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (716, '20406002041', '2021', '254721389291', '254721734430', 'karimikr@gmail.com', 'karimikr@gmail.com', '00200', '75671', 'NAIROBI', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e7f8a7fb0b77bcb3b283af5be021448f', NULL, NULL, NULL, NULL, NULL, 'KINYUA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (717, '27500009195', '2021', '254740362072', '254711412832', 'mutiriaesther9@gmail.com', 'muguregrace35@gmail.com', '20100', '1352', 'NAKURU', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '788d986905533aba051261497ecffcbb', NULL, NULL, NULL, NULL, NULL, 'MUTIRIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (718, '27538208006', '2021', '254792540178', '254720513522', 'namareaugustine68@gmail.com', 'jacintanamare@gmail.com', '20100', 'SHABAB 214', 'NAKURU', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '50c3d7614917b24303ee6a220679dab3', NULL, NULL, NULL, NULL, NULL, 'AUGUSTINE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (719, '33517102123', '2021', '254735716134', '254722866940', 'glorymurushkin@gmail.com', 'musamiab@yahoo.com', '00400', '22893', 'NAIROBI', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2afe4567e1bf64d32a5527244d104cea', NULL, NULL, NULL, NULL, NULL, 'MURUNGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (720, '34500010087', '2021', '254715121596', '254727117578', 'alusacyril@gmail.com', 'khahoyaleonida@gmail.com', '', '', '', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5f2c22cb4a5380af7ca75622a6426917', NULL, NULL, NULL, NULL, NULL, 'BUKHALA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (721, '37600001063', '2021', '254739901079', '254713697728', 'samramogi@gmail.com', 'linoxochieng10@gmail.com', '166', '40302', 'NDHIWA', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'aba3b6fd5d186d28e06ff97135cade7f', NULL, NULL, NULL, NULL, NULL, 'OTIENO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (722, '37600001419', '2021', '254768585118', '254713550771', 'carlwere.o@gmail.com', 'viogisa7@gmail.com', '700', '60', 'GARISSA', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'c8ed21db4f678f3b13b9d5ee16489088', NULL, NULL, NULL, NULL, NULL, 'CARL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (723, '37615001019', '2021', '254757707021', '254723660972', 'paul.balusi@gmail.com', '', '', '', '', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '08419be897405321542838d77f855226', NULL, NULL, NULL, NULL, NULL, 'MAGENI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (724, '41710326076', '2021', '254113696183', '254712296913', 'nancyamollo12@gmail.com', 'pascalomollo99@gmail.com', '40302', 'BOX 200', 'NDHIWA', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '7f1171a78ce0780a2142a6eb7bc4f3c8', NULL, NULL, NULL, NULL, NULL, 'NANCY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (725, '42749107027', '2021', '254797749926', '254726908922', 'derekmwendwa14@gmail.com', 'angelicamwau78@gmail.com', '40612', '120', 'SAWAGONGO', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '82f2b308c3b01637c607ce05f52a2fed', NULL, NULL, NULL, NULL, NULL, 'MUTISYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (726, '43700008054', '2021', '254104155371', '254724698230', 'kebandemorara54@gmail.com', 'veloh78@gmail.com', '40100', '2268', 'KISUMU', '1263126', 'I39', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '0d3180d672e08b4c5312dcdafdf6ef36', NULL, NULL, NULL, NULL, NULL, 'KEBANDE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (727, '10200008021', '2021', '254726485405', '254727830387', 'ahmednajiwarsame2@gmail.com', 'abdallawarsamedubow@gmail.com', '0100', '12TH STREET, EASTELI', 'GARISSA COUNTY', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fb89705ae6d743bf1e848c206e16a1d7', NULL, NULL, NULL, NULL, NULL, 'AHMEDNAJI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (728, '10208311079', '2021', '', '', '', '', '', '', '', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd4c2e4a3297fe25a71d030b67eb83bfc', NULL, NULL, NULL, NULL, NULL, 'KARURI', 'wanjama', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (729, '11200003052', '2021', '254792254629', '254722258967', 'kamaugilbert7@gmail.com', 'wakajim@gmail.com', '10200', '121', 'MURANG''A', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5751ec3e9a4feab575962e78e006250d', NULL, NULL, NULL, NULL, NULL, 'KAMAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (730, '11207101181', '2021', '254745038449', '254717301639', 'stevegitau9551@gmail.com', 'rosegitau096@gmail.com', '1800', '391', '0900', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd5cfead94f5350c12c322b5b664544c1', NULL, NULL, NULL, NULL, NULL, 'GITAU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (731, '11207121131', '2021', '254725481994', '254722444703', 'jm4011874@gmail.com', '', '', '', '', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '59c33016884a62116be975a9bb8257e3', NULL, NULL, NULL, NULL, NULL, 'MAKHANU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (732, '11232308049', '2021', '254794702519', '254710234556', 'alechopinchu8@gmail.com', 'alechopinchu8@gmail.com', '00232', '242', 'RUIRU', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ba3866600c3540f67c1e9575e213be0a', NULL, NULL, NULL, NULL, NULL, 'WANJIRU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (733, '12301703057', '2021', '254743614414', '254714777229', 'kimitijoseph22@gmail.com', 'gladmuia@gmail.com', '90100', '2064', 'MACHAKOS', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6c29793a140a811d0c45ce03c1c93a28', NULL, NULL, NULL, NULL, NULL, 'KIMITI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (734, '15327105035', '2021', '254757640356', '254719887901', 'phiniuskoome18@gmail.com', '', '60600', '614', 'MAUA', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e995f98d56967d946471af29d7bf99f1', NULL, NULL, NULL, NULL, NULL, 'NJAGI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (735, '20404003193', '2021', '254796916594', '254722689038', 'hudmohabah16@gmail.com', 'mohabahnur@gmail.com', '00202', '2008', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6cd67d9b6f0150c77bda2eda01ae484c', NULL, NULL, NULL, NULL, NULL, 'HUDHAIFAH', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (736, '20406005042', '2021', '254731070077', '254708081717', 'debra.mutemi@gmail.com', 'ceciliamutemi5@gmail.com', '', '', '', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6bc24fc1ab650b25b4114e93a98f1eba', NULL, NULL, NULL, NULL, NULL, 'MUTEMI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (737, '20406015012', '2021', '254777555171', '254706579933', 'noelaweramundi@gmail.com', 'merinedarman@gmail.com', '00100', 'KILELESHWA, NAIROBI', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a5cdd4aa0048b187f7182f1b9ce7a6a7', NULL, NULL, NULL, NULL, NULL, 'NOELA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (738, '20406020068', '2021', '254746615392', '254722486999', 'ryanmutunga63@gmail.com', 'rmutunga75@gmail.com', '', '', 'KIKUYU', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '217eedd1ba8c592db97d0dbe54c7adfc', NULL, NULL, NULL, NULL, NULL, 'MUTUNGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (739, '20407073027', '2021', '254776537823', '254721854832', 'gracndegwa999@gmail.com', 'lymwa188@gmail.com', '00620', '16529', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'df263d996281d984952c07998dc54358', NULL, NULL, NULL, NULL, NULL, 'NDEGWA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (740, '20408006007', '2021', '254722624065', '254722624065', 'christinewanjiru2003@gmail.com', 'rmaina2@gmail.com', '00505', '21389', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'edfbe1afcf9246bb0d40eb4d8027d90f', NULL, NULL, NULL, NULL, NULL, 'MAINA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (741, '20408006072', '2021', '720961170', '', 'hannahakinyi9@gmail.com', '', '00505', '21389', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2e65f2f2fdaf6c699b223c61b1b5ab89', NULL, NULL, NULL, NULL, NULL, 'KARIUKI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (742, '20410002337', '2021', '254110290909', '254726582110', 'maxwellmatu123@gmail.com', 'gracenyandia4@gmail.com', '', '', '', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e94550c93cd70fe748e6982b3439ad3b', NULL, NULL, NULL, NULL, NULL, 'MAXWELL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (743, '23574101046', '2021', '254701254963', '254711786460', 'dionisitika@gmail.com', 'levistika@gamail.com', '', '', '', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5c572eca050594c7bc3c36e7e8ab9550', NULL, NULL, NULL, NULL, NULL, 'INDIRE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (745, '28500006099', '2021', '254787034729', '254768671197', 'emmanuelrioba65@gmail.com', 'leroychristopher35@gmail.com', '00101', 'P.O BOX 182021-00101', 'RUIRU', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5f0f5e5f33945135b874349cfbed4fb9', NULL, NULL, NULL, NULL, NULL, 'EMMANUEL', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (1732, '20400009016', '2021', '254782600734', '254722819960', 'brenahanami@gmail.com', 'anamiluke@gmail.com', '50100', '1210', 'KAKAMEGA', '1263101', 'C01', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '270edd69788dce200a3b395a6da6fdb7', NULL, NULL, NULL, NULL, NULL, 'BENADETTE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (746, '36600002019', '2021', '254720706820', '254720706820', 'chebokipkenda@gmail.com', 'chebokipkenda@gmail.com', '30302', '222 LESSOS', 'LESSOS', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '185e65bc40581880c4f2c82958de8cfe', NULL, NULL, NULL, NULL, NULL, 'CHEMUTAI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (747, '37617201021', '2021', '254705517650', '254722395900', 'kingndegs@gmail.com', 'waudo66@gmail.com', '00400', '70180', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '8d317bdcf4aafcfc22149d77babee96d', NULL, NULL, NULL, NULL, NULL, 'SIGANGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (748, '38600006378', '2021', '254722842417', '254722477066', 'abdizma2003@gmail.com', 'fardosa1978@gmail.com', '', '', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'e49b8b4053df9505e1f48c3a701c0682', NULL, NULL, NULL, NULL, NULL, 'MOHAMED', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (749, '40700002384', '2021', '254755466256', '254733280816', 'marcus.murira@gmail.com', 'kezia.ndwiga@gmail.com', '00621', '1724', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b056eb1587586b71e2da9acfe4fbd19e', NULL, NULL, NULL, NULL, NULL, 'NDWIGA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (750, '40700002424', '2021', '254777396152', '254724319435', 'zamiyrwilliams@gmail.com', 'kiokoesther02@gmail.com', '00200', '52546', 'NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'b137fdd1f79d56c7edf3365fea7520f2', NULL, NULL, NULL, NULL, NULL, 'KIOKO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (751, '41742105057', '2021', '254790247652', '254721457843', 'onyangomwai@gmail.com', 'onyangomwai@gmail.com', '00519', '123', 'MLOLONGO', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '912d2b1c7b2826caf99687388d2e8f7c', NULL, NULL, NULL, NULL, NULL, 'MWAI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (752, '41743205051', '2021', '254742584115', '254727529602', 'stevensonhood105@gmail.com', 'stevensonhood105@gmail.com', '25135', '25135', 'KAHAWANGWARE,NAIROBI', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'a1d33d0dfec820b41b54430b50e96b5c', NULL, NULL, NULL, NULL, NULL, 'KAGWA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (753, '43706115160', '2021', '254795140267', '254728991278', 'oduorlinstone@gmail.com', 'josephineosese@gmail.com', '20100', '3060', 'NAKURU', '1263127', 'I44', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '6f2268bd1d3d3ebaabb04d6b5d099425', NULL, NULL, NULL, NULL, NULL, 'ODUOR', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (754, '1100003001', '2021', '254768445825', '254726551705', 'joshuakizwania@gmail.com', 'verohmwashighadi@gmail.com', '80304', '1044', 'WUNDANYI', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '872488f88d1b2db54d55bc8bba2fad1b', NULL, NULL, NULL, NULL, NULL, 'KIZWANIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (755, '8200007072', '2021', '254701179916', '254795739177', 'incamuels@gmail.com', 'njugunaben007@gmail.com', '20100', 'NAKURU 101', 'NAKURU', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'ccb0989662211f61edae2e26d58ea92f', NULL, NULL, NULL, NULL, NULL, 'MACHARIA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (756, '8200007155', '2021', '254725149010', '254721872641', 'antoniomaxwell746@gmail.com', 'davidmugo.adv@gmail.com', '00100', '16574', 'NAIROBI', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2823f4797102ce1a1aec05359cc16dd9', NULL, NULL, NULL, NULL, NULL, 'MWANGI', 'karimi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (757, '10204115032', '2021', '254745742787', '254720454081', 'mainadaniel977@gmail.com', 'margaretwangui99@gmail.com', '10106', '921', 'OTHAYA', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '470e7a4f017a5476afb7eeb3f8b96f9b', NULL, NULL, NULL, NULL, NULL, 'NG''ANG''A', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (758, '11200002176', '2021', '254790661742', '254725607073', 'belilikawira@gmail.com', 'kananuharriet@gmail.com', '60600', '552', 'MAUA', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'bf62768ca46b6c3b5bea9515d1a1fc45', NULL, NULL, NULL, NULL, NULL, 'GATOBU', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (759, '11200002277', '2021', '254743703382', '254722478371', 'okwayolucy@gmail.com', 'lmerabselfa@yahoo.com', '50300', '146', 'MARAGOLI', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'fa14d4fe2f19414de3ebd9f63d5c0169', NULL, NULL, NULL, NULL, NULL, 'LUCY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (760, '11200002435', '2021', '254727975322', '254727975322', 'alandosonia@gmail.com', 'd_osiro@yahoo.com', '40100', '416', 'KISUMU', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '2ca65f58e35d9ad45bf7f3ae5cfd08f1', NULL, NULL, NULL, NULL, NULL, 'ALANDO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (761, '11200003099', '2021', '254797480636', '254721256060', 'frankmbish968@gmail.com', 'matenge25@gmail.com', '10200', '106MURANGA', 'MURANGA', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '88ae6372cfdc5df69a976e893f4d554b', NULL, NULL, NULL, NULL, NULL, 'MATHENGE', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (762, '11200006306', '2021', '254717947521', '254711725224', 'onyangomaryann@email.com', 'pamokwany71@gmail.com', '40405', '107', 'AWENDO', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '06997f04a7db92466a2baa6ebc8b872d', NULL, NULL, NULL, NULL, NULL, 'ONYANGO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (763, '15300002169', '2021', '254768162572', '254721144714', 'cmukala03@gmail.com', 'dmukala@gmail.com', '00100', '34999', 'NAIROBI', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'eefc9e10ebdc4a2333b42b2dbb8f27b6', NULL, NULL, NULL, NULL, NULL, 'MUKALA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (764, '20400003136', '2021', '254792324932', '254724650829', 'marlynadisa@gmail.com', 'emufoyongo@gmail.com', '', '', '', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '5807a685d1a9ab3b599035bc566ce2b9', NULL, NULL, NULL, NULL, NULL, 'KAHI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (765, '20400003198', '2021', '254712499785', '254729371582', 'dottystan5@gmail.com', 'fridanjagi78@gmail.com', '00200', '51300', 'NAIROBI', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'd840cc5d906c3e9c84374c8919d2074e', NULL, NULL, NULL, NULL, NULL, 'NTHENYA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (766, '20400003204', '2021', '254704869581', '254722807841', 'natashaashimosi@gmail.com', 'skhamadi@gmail.com', '00200', '54840', 'NAIROBI', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '959a557f5f6beb411fd954f3f34b21c3', NULL, NULL, NULL, NULL, NULL, 'ASHIMOSI', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (767, '20400006077', '2021', '254708445604', '254726222557', 'mercywn.22@gmail.com', 'njeriann520@gmail.com', '', '', 'NAKURU', '1263128', 'V28', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, 'f2201f5191c4e92cc5af043eebfd0946', NULL, NULL, NULL, NULL, NULL, 'NJUGUNA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (7227, '41710301175', '2021', '254759759793', '254718336571', 'rambasu@uonbi.ac.ke', 'idachirufus@gmail.com', '704', '1236', 'DHIWA', '1263109', 'P15', '', '', 2, '', 'REGISTERED', NULL, 2, 1, '$2y$13$Tex9AWcBwFkl1.g0uMHb6ORqzQV4oXUpdcQvrq2U3Q5nwnr8N0LDm', 't', '$2y$13$YRESFSOB33XZKKIU9WHam..dFHi6jxCJVAtrqsqLWL1h9MNIR/Ar.', '$2y$13$v4cPKNR14tSV7b6KqZZN2OODPM2luZL7A2HTbldXpJxGAnF5NZC2q', '2023-01-24', '2023-01-24', 'DAVID', 'DAVID', 'CLEARED', '2023-01-24', NULL, 't', NULL, NULL, '2023-01-24', NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (610, '27552001190', '2021', '254746621737', '254746621737', 'avelboyi82@gmail.com', 'avelboyi82@gmail.com', '20157', 'PRIVATE BAG', 'KABARAK', '1263122', 'P15', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '00ac8ed3b4327bdd4ebbebcb2ba10a00', NULL, NULL, NULL, NULL, NULL, 'ABUKO', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (384, '44744104001', '2021', '254708256351', '254100529913', 'ocharomillicent38@gmail.com', 'ocharomillicent38@gmail.com', '40400', '1125', 'MIGORI', '1263119', 'F19', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '$2y$13$W1Dm6HwTuBZIUbjybFSB0Oy78Uwa8EvomA1fmeFSE4ExfVIYbcEwe', NULL, NULL, NULL, NULL, NULL, 'KENEDY', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 't', NULL, NULL);
INSERT INTO "smisportal"."sm_admitted_student" VALUES (550, '23500003083', '2021', '254707811245', '254725713925', 'rambasu@uonbi.ac.ke', 'idachirufus@gmail.com', '00200', '53480 00200 NAIROBI', 'NAIROBI', '1263121', 'P15', '33116529', '33116529', 2, '', 'PRE-REGISTRATION', NULL, 1, 1, '$2y$13$.9OQUelYiTdeHxy2XWTxw.5gggFxUDcPnrTpdBkLWCL1JKxkoTiS2', 't', '$2y$13$MgUhVinDo3fmP4XEW.A7CexlLebKZvo3uLgJPQ2yiVeppIVwm2Zlu', '$2y$13$qLzjDUnpIpJ.UNEdnqvjF.IZ.y4lC6jWnYCE3MzPtsvzaK4em/mwm', '2023-02-07', '2023-02-07', 'KIPTOO', 'KIPTOO', 'NOT_CLEARED', '2023-02-07', 'Kenya navy', 't', '123456789', 'kenyan', '2023-02-07', 't', 1, 'A+');
INSERT INTO "smisportal"."sm_admitted_student" VALUES (168, '11200003221', '2021', '254751943745', '254726730846', 'kinuthianj@uonbi.ac.ke', 'kinuthianj@gmail.com', '00101', 'MUHURI RD', 'NAIROBI', '1263116', 'P15', '22365874', '32254578', 2, '', 'PRE-REGISTRATION', NULL, 2, 1, '$2y$13$Hd6wscS4Bn62pdJVYLtEqO1teHjSxIfYKYWtQOQXJBvQq01btJGjS', NULL, '$2y$13$tbggRRJ7h7ahQNu25mxLpuM8a45ba/5Ca6x/LMSABmXdRz5BcLrrq', '$2y$13$P7wrvxLU1lN/klrQOeDncORD6IL6NE/WJePfDMW3Yeg9AmIORzx4u', NULL, NULL, 'YOBESIA', '', NULL, '2023-01-31', 'Kenya army', NULL, '322544', 'kenyan', '2000-01-18', 't', 1, '0+');
INSERT INTO "smisportal"."sm_admitted_student" VALUES (195, '20410001240', '2021', '254787090264', '254721598470', 'rambasu@uonbi.ac.ke', 'idachirufus@gmail.com', '00623', '39652', 'NAIROBI', '1263116', 'F16', NULL, NULL, 2, NULL, 'PRE-REGISTRATION', NULL, 2, 1, '$2y$13$f7Lv1LV491j477cBTkLqruEYfwUzyXxUYfo7k79D6icDlmO830yM.', NULL, '$2y$13$58dt.4AXroKKvtXubV8Eh.RDRNwXo3c7Bh7.5IyAu85TUYAo/9zhy', '$2y$13$wRtfTB37sJrdayHVU9fQG.uGe46QmIsx/E4fCvracmRiDLl6RTnNi', NULL, NULL, 'MAWALA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 't', NULL, NULL);

-- ----------------------------
-- Table structure for sm_approver
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_approver";
CREATE TABLE "smisportal"."sm_approver" (
  "approver_id" int4 NOT NULL,
  "approver" varchar COLLATE "pg_catalog"."default" NOT NULL,
  "level" int4 NOT NULL,
  "status" varchar COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_approver
-- ----------------------------

-- ----------------------------
-- Table structure for sm_id_request_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_id_request_status";
CREATE TABLE "smisportal"."sm_id_request_status" (
  "status_id" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "status_name" varchar(30) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_id_request_status" IS 'status of id processing eg pending, on process, printed etc';

-- ----------------------------
-- Records of sm_id_request_status
-- ----------------------------
INSERT INTO "smisportal"."sm_id_request_status" OVERRIDING SYSTEM VALUE VALUES (1, 'PENDING');

-- ----------------------------
-- Table structure for sm_id_request_type
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_id_request_type";
CREATE TABLE "smisportal"."sm_id_request_type" (
  "request_type_id" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "id_type_desc" varchar(30) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_id_request_type" IS 'ID request types';

-- ----------------------------
-- Records of sm_id_request_type
-- ----------------------------
INSERT INTO "smisportal"."sm_id_request_type" OVERRIDING SYSTEM VALUE VALUES (1, 'REPLACEMENT');

-- ----------------------------
-- Table structure for sm_intake_source
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_intake_source";
CREATE TABLE "smisportal"."sm_intake_source" (
  "source_id" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "source" varchar(30) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_intake_source
-- ----------------------------
INSERT INTO "smisportal"."sm_intake_source" OVERRIDING SYSTEM VALUE VALUES (2, 'kuccps');
INSERT INTO "smisportal"."sm_intake_source" OVERRIDING SYSTEM VALUE VALUES (3, 'online application');
INSERT INTO "smisportal"."sm_intake_source" OVERRIDING SYSTEM VALUE VALUES (5, 'inter-university transfer');

-- ----------------------------
-- Table structure for sm_intakes
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_intakes";
CREATE TABLE "smisportal"."sm_intakes" (
  "intake_code" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "intake_name" varchar COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_intakes" IS 'university intakes';

-- ----------------------------
-- Records of sm_intakes
-- ----------------------------
INSERT INTO "smisportal"."sm_intakes" OVERRIDING SYSTEM VALUE VALUES (1, 'SEPTEMBER-DECEMBER 2022');
INSERT INTO "smisportal"."sm_intakes" OVERRIDING SYSTEM VALUE VALUES (2, 'JANUARY-MARCH 2023');
INSERT INTO "smisportal"."sm_intakes" OVERRIDING SYSTEM VALUE VALUES (3, 'JANUARY-MARCH 2022');

-- ----------------------------
-- Table structure for sm_mentor_relationship
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_mentor_relationship";
CREATE TABLE "smisportal"."sm_mentor_relationship" (
  "mentor_relationship_id" int4 NOT NULL,
  "relationship_name" varchar COLLATE "pg_catalog"."default" NOT NULL,
  "description" varchar COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_mentor_relationship
-- ----------------------------

-- ----------------------------
-- Table structure for sm_name_change
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_name_change";
CREATE TABLE "smisportal"."sm_name_change" (
  "name_change_id" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "request_date" date NOT NULL,
  "student_id" int4 NOT NULL,
  "new_surname" varchar(20) COLLATE "pg_catalog"."default",
  "new_othernames" varchar(50) COLLATE "pg_catalog"."default",
  "reason" varchar(200) COLLATE "pg_catalog"."default" NOT NULL,
  "document_url" varchar(200) COLLATE "pg_catalog"."default",
  "current_surname" varchar(20) COLLATE "pg_catalog"."default",
  "current_othernames" varchar(50) COLLATE "pg_catalog"."default",
  "status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "sync_status" bool
)
TABLESPACE "tblsp_smis"
;
COMMENT ON TABLE "smisportal"."sm_name_change" IS 'Requests for student name change';

-- ----------------------------
-- Records of sm_name_change
-- ----------------------------
INSERT INTO "smisportal"."sm_name_change" OVERRIDING SYSTEM VALUE VALUES (11, '2022-11-28', 154, 'DAVID ', 'JUMA MASIGA Jr.', 'Wrong order', 'KMA401_0115_2022/11/acrobat-for-chrome_2.pdf', 'JUMA', 'DAVID MASIGA', 'REJECTED', NULL);
INSERT INTO "smisportal"."sm_name_change" OVERRIDING SYSTEM VALUE VALUES (13, '2022-11-28', 154, 'MASIGA ', 'JUMA KEVIN', 'Wrong name', 'KMA401_0115_2022/13/acrobat-for-chrome.pdf', 'JUMA', 'DAVID MASIGA', 'APPROVED', NULL);

-- ----------------------------
-- Table structure for sm_name_change_approval
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_name_change_approval";
CREATE TABLE "smisportal"."sm_name_change_approval" (
  "name_change_approval_id" int4 NOT NULL,
  "name_change_id" int4 NOT NULL,
  "approval_status" varchar(30) COLLATE "pg_catalog"."default" NOT NULL,
  "remarks" varchar(250) COLLATE "pg_catalog"."default",
  "approved_by" varchar(15) COLLATE "pg_catalog"."default" NOT NULL,
  "approval_date" timestamptz(6) NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON COLUMN "smisportal"."sm_name_change_approval"."approval_status" IS 'pending, review, closed';

-- ----------------------------
-- Records of sm_name_change_approval
-- ----------------------------

-- ----------------------------
-- Table structure for sm_promotion_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_promotion_status";
CREATE TABLE "smisportal"."sm_promotion_status" (
  "prom_status_id" int8 NOT NULL,
  "status_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of sm_promotion_status
-- ----------------------------

-- ----------------------------
-- Table structure for sm_readmission
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_readmission";
CREATE TABLE "smisportal"."sm_readmission" (
  "readmission_id" int4 NOT NULL,
  "stud_prog_curr_id" int4 NOT NULL,
  "entry_date" date NOT NULL,
  "reason" varchar(250) COLLATE "pg_catalog"."default",
  "registration_no" varchar(50) COLLATE "pg_catalog"."default",
  "approval_status" varchar(30) COLLATE "pg_catalog"."default",
  "entry_remarks" varchar(250) COLLATE "pg_catalog"."default",
  "approval_remarks" varchar(250) COLLATE "pg_catalog"."default",
  "entered_by" varchar(15) COLLATE "pg_catalog"."default",
  "approved_by" varchar(15) COLLATE "pg_catalog"."default",
  "approval_date" timestamptz(6)
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_readmission
-- ----------------------------

-- ----------------------------
-- Table structure for sm_reg_documents
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_reg_documents";
CREATE TABLE "smisportal"."sm_reg_documents" (
  "document_id" int4 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "document_name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL,
  "document_desc" varchar(250) COLLATE "pg_catalog"."default" NOT NULL,
  "required" bool NOT NULL DEFAULT true
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_reg_documents" IS 'all documents required for registration';

-- ----------------------------
-- Records of sm_reg_documents
-- ----------------------------
INSERT INTO "smisportal"."sm_reg_documents" VALUES (46, 'PERSONAL DETAILS JI 2', 'STUDENT PERSONAL DETAILS-JI/2', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (47, 'MEDICAL EXAMINATION REPORT JI 3', 'STUDENT MEDICAL EXAMINATION REPORT-JI/3', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (49, 'DECLARATION FOR ADMISSION RE ADMISSION STUDENTSHIP JI 5A', 'DECLARATION FOR ADMISSION/RE-ADMISSION/STUDENTSHIP-JI/5A', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (50, 'STUDENT BOND JI 5B', '<B><FONT COLOR=RED>A DULY EXECUTED </FONT></B>
STUDENT BOND-JI/5B', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (51, 'SPONSORSHIP FORM JI 6B', 'SPONSORSHIP FORM-JI/6B 

(PLEASE IGNORE IF IT IS NOT APPLICABLE TO YOU)', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (52, 'NON ACCEPTANCE DEFERMENT FORM', 'NON-ACCEPTANCE/DEFERMENT FORM -PP/1B', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (53, 'DECLARATION FOR ADMISSION PP 2', 'DECLARATION FOR ADMISSION-PP/2', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (54, 'STUDENT BOND PP 3', '<B><FONT COLOR=RED>A DULY EXECUTED </FONT></B>STUDENT BOND-PP/3', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (56, 'NON ACCEPTANCE OR DEFERMENT FORM PP 1B', 'NON-ACCEPTANCE/DEFERMENT FORM -PP/1B', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (57, 'DULY SIGNED DECLARATION ON RULES AND REGULATIONS JI 13B', 'DULY SIGNED DECLARATION ON RULES AND REGULATIONS- JI/13B', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (58, 'APPLICATION FORM USED FOR ADMISSION', 'DOWNLOADED APPLICATION FORM USED FOR ADMISSION', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (59, 'LETTER OF ADMISSION', 'LETTER OF ADMISSION', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (1, 'NATIONAL ID OR PASSPORT OR BIRTH CERTIFICATE', 'SCAN BOTH SIDES OF YOUR ID, PUT THEM IN ONE DOCUMENT OR PASSPORT AND  UPLOAD.
 FOR UNDER AGE COPY OF BIRTH CERTIFICATE AND COPIES OF PARENTS ID ', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (48, 'EMERGENCY OPERATION FORM JI 4', 'EMERGENCY OPERATION FORM-JI/4', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (55, 'SPONSORSHIP FORM PP 5', 'SPONSORSHIP FORM(WHERE APPLICABLE)-PP/5', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (7, 'BURSARY FORM', 'BURSARY FORMS TO APPLY FOR BURSARY FUNDS', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (44, 'FEE PAYMENT RECEIPT', ' FEES RECEIPT', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (45, 'BURSARY FORM', 'LETTER OF OFFER OF STUDENT BURSARY <BR> <FONT COLOR=RED>THIS IS NON-MANDATORY </FONT>
<B>(IGNORE IF IT DOES NOT APPLY TO YOU)</B>', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (2, 'UNDERGRADUATE DEGREE CERTIFICATE', '<B><FONT COLOR=RED>CERTIFIED</FONT></B>
 UNDERGRADUATE DEGREE CERTIFICATE ', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (3, 'MASTERS DEGREE CERTIFICATES', '<B><FONT COLOR=RED>CERTIFIED COPIES</FONT></B> OF MASTERâ€™S DEGREE CERTIFICATES (ONLY WHERE APPLICABLE) ', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (4, 'PASSPORT SIZE PHOTO', 'SCANNED PASSPORT PHOTO', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (5, 'NATIONAL ID OR PASSPORT', 'NATIONAL ID OR PASSPORT FOR INTERNATIONAL STUDENTS', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (6, 'SECONDARY LEVEL CERTIFICATE', 'A COPY OF SECONDARY LEVEL EDUCATION CERTIFICATE (E.G. KCSE CERTIFICATE) / AND DIPLOMA CERTIFICATES ', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (8, 'LEAVING CERTIFICATE', 'CERTIFICATE SHOWING YOUR CONDUCT IN SCHOOL', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (39, 'MEDICAL EXAMINATION REPORT JI 3', 'STUDENT ENTRANCE MEDICAL EXAMINATION', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (40, 'LETTER OF ACCEPTANCE JI A', 'LETTER OF ACCEPTANCE-JI/A', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (41, 'FOREIGNER ID', 'FOREIGNER ID', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (42, 'SIGNATURE', 'THIS WILL BE USED IN YOUR STUDENT ID. ENSURE IT IS IS VISIBLE ENOUGH', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (60, 'DULY FILLED AND SIGNED REGISTRATION FORM', 'DULY FILLED AND SIGNED REGISTRATION FORM', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (61, 'ADMISSION FORM', '<B><FONT COLOR=RED>DULY FILLED AND SIGNED</FONT></B>  ADMISSION FORM', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (62, 'STUDENT BOND', '<B><FONT COLOR=RED>DULY EXECUTED</FONT></B> STUDENT BOND', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (63, 'UNDERGRADUATE CERTIFIED TRANSCRIPTS', 'CERTIFIED UNDERGRADUATE ', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (64, 'DEGREE ACADEMIC TRANSCRIPTS', '<B><FONT COLOR=RED>CERTIFIED COPIES OF 
MASTERS DEGREE ACADEMIC TRANSCRIPTS </FONT></B> 
 (ONLY WHERE APPLICABLE, THIS RELATES TO PHD STUDENTS).', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (65, 'CERTIFIED UNDERGRADUATE DEGREE CERTIFICATE', 'CERTIFIED UNDERGRADUATE DEGREE CERTIFICATE', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (66, 'NHIF CARD', 'UPLOAD SCAN OF BOTH SIDES OF NHIF CARD. IF COVERED UNDER YOUR PARENT, UPLOAD THE PARENT NHIF CARD', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (67, 'KCSE RESULT SLIP', 'KCSE RESULT SLIP', 't');
INSERT INTO "smisportal"."sm_reg_documents" VALUES (68, 'LETTER OF EQUATION/RECOGNITION OF FOREIGN QUALIFICATION(S)', 'LETTER OF EQUATION/RECOGNITION OF FOREIGN QUALIFICATION(S)', 't');

-- ----------------------------
-- Table structure for sm_reg_required_document
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_reg_required_document";
CREATE TABLE "smisportal"."sm_reg_required_document" (
  "required_document_id" int8 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "fk_document_id" int4 NOT NULL,
  "fk_category_id" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_reg_required_document" IS 'Required documents per student category';

-- ----------------------------
-- Records of sm_reg_required_document
-- ----------------------------
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (1, 46, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (2, 40, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (3, 62, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (4, 4, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (5, 58, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (6, 59, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (7, 6, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (8, 63, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (9, 3, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (10, 64, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (11, 60, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (12, 61, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (13, 47, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (14, 48, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (15, 49, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (16, 50, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (17, 57, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (18, 4, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (19, 42, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (20, 44, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (21, 45, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (22, 55, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (23, 44, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (24, 45, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (25, 40, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (26, 52, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (27, 53, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (28, 54, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (29, 55, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (30, 39, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (31, 4, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (32, 42, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (33, 65, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (34, 1, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (35, 1, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (36, 5, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (37, 42, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (38, 44, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (39, 66, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (40, 66, 2);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (41, 66, 3);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (42, 66, 4);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (43, 67, 1);
INSERT INTO "smisportal"."sm_reg_required_document" OVERRIDING SYSTEM VALUE VALUES (44, 68, 4);

-- ----------------------------
-- Table structure for sm_reporting_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_reporting_status";
CREATE TABLE "smisportal"."sm_reporting_status" (
  "rep_status_id" int8 NOT NULL,
  "status_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of sm_reporting_status
-- ----------------------------

-- ----------------------------
-- Table structure for sm_stud_submitted_document
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_stud_submitted_document";
CREATE TABLE "smisportal"."sm_stud_submitted_document" (
  "student_document_id" int8 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1
),
  "required_document_id" int8 NOT NULL,
  "document_path" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "ip_address" varchar(60) COLLATE "pg_catalog"."default",
  "upload_date" timestamptz(6) NOT NULL,
  "verify_status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "doc_comments" varchar(100) COLLATE "pg_catalog"."default",
  "adm_refno" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_stud_submitted_document" IS 'student submitted documents';

-- ----------------------------
-- Records of sm_stud_submitted_document
-- ----------------------------
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (23, 17, '6001/document_57/acrobat-for-chrome.pdf', NULL, '2022-11-16 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (20, 2, '6001/document_40/acrobat-for-chrome.pdf', NULL, '2022-11-16 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (26, 16, '6001/document_50/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (34, 22, '6001/document_55/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (66, 1, '744/document_46/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (68, 13, '744/document_47/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (33, 21, '6001/document_45/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', '', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (62, 22, '7227/document_55/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (25, 14, '6001/document_48/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', '', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (63, 34, '7227/document_1/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (64, 39, '7227/document_66/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (65, 43, '7227/document_67/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (51, 1, '7227/document_46/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (52, 2, '7227/document_40/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (53, 13, '7227/document_47/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (54, 14, '7227/document_48/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (55, 15, '7227/document_49/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (56, 16, '7227/document_50/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (57, 17, '7227/document_57/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (29, 39, '6001/document_66/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (24, 34, '6001/document_1/acrobat-for-chrome.pdf', NULL, '2022-11-17 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (27, 18, '6001/document_4/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (28, 43, '6001/document_67/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (30, 15, '6001/document_49/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (35, 1, '6001/document_46/acrobat-for-chrome.pdf', NULL, '2022-11-28 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (32, 20, '6001/document_44/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (31, 19, '6001/document_42/acrobat-for-chrome.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (21, 13, '6001/document_47/acrobat-for-chrome_2.pdf', NULL, '2022-11-18 00:00:00+03', 'APPROVED', 'APPROVED', 7233);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (58, 18, '7227/document_4/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (59, 19, '7227/document_42/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (60, 20, '7227/document_44/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (69, 14, '744/document_48/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (70, 15, '744/document_49/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (90, 18, '550/document_4/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (91, 19, '550/document_42/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (93, 21, '550/document_45/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (61, 21, '7227/document_45/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 7227);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (94, 22, '550/document_55/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (83, 1, '550/document_46/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (92, 20, '550/document_44/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (71, 16, '744/document_50/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (72, 17, '744/document_57/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (81, 1, '236/document_46/sample.pdf', NULL, '2023-01-31 00:00:00+03', 'PENDING', '', 236);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (73, 18, '744/document_4/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (74, 19, '744/document_42/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (67, 2, '744/document_40/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (75, 20, '744/document_44/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (76, 21, '744/document_45/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (77, 22, '744/document_55/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (82, 2, '236/document_40/sample2.pdf', NULL, '2023-01-31 00:00:00+03', 'PENDING', '', 236);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (78, 34, '744/document_1/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (79, 39, '744/document_66/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (80, 43, '744/document_67/ooa96.pdf', NULL, '2023-01-24 00:00:00+03', 'APPROVED', '', 744);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (86, 14, '550/document_48/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (87, 15, '550/document_49/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (88, 16, '550/document_50/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (89, 17, '550/document_57/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (84, 2, '550/document_40/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (85, 13, '550/document_47/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (95, 34, '550/document_1/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (96, 39, '550/document_66/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (97, 43, '550/document_67/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 550);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (98, 1, '245/document_46/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (99, 2, '245/document_40/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (100, 13, '245/document_47/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (101, 14, '245/document_48/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (102, 15, '245/document_49/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (103, 16, '245/document_50/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (104, 17, '245/document_57/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (105, 18, '245/document_4/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (106, 19, '245/document_42/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (107, 20, '245/document_44/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (108, 21, '245/document_45/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (109, 22, '245/document_55/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (110, 34, '245/document_1/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (111, 39, '245/document_66/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);
INSERT INTO "smisportal"."sm_stud_submitted_document" OVERRIDING SYSTEM VALUE VALUES (112, 43, '245/document_67/ooa96.pdf', NULL, '2023-02-07 00:00:00+03', 'APPROVED', 'APPROVED', 245);

-- ----------------------------
-- Table structure for sm_student
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student";
CREATE TABLE "smisportal"."sm_student" (
  "student_id" int8 NOT NULL,
  "student_number" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "surname" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "other_names" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "gender" varchar(1) COLLATE "pg_catalog"."default" NOT NULL,
  "country_code" varchar(3) COLLATE "pg_catalog"."default" NOT NULL,
  "id_no" varchar(10) COLLATE "pg_catalog"."default",
  "passport_no" varchar(20) COLLATE "pg_catalog"."default",
  "service_number" varchar(30) COLLATE "pg_catalog"."default",
  "blood_group" varchar(5) COLLATE "pg_catalog"."default",
  "sponsor" int2,
  "registration_date" timestamp(6),
  "primary_phone_no" varchar(50) COLLATE "pg_catalog"."default",
  "alternative_phone_no" varchar(50) COLLATE "pg_catalog"."default",
  "primary_email" varchar(100) COLLATE "pg_catalog"."default",
  "alternative_email" varchar(100) COLLATE "pg_catalog"."default",
  "post_code" varchar(10) COLLATE "pg_catalog"."default",
  "post_address" varchar(20) COLLATE "pg_catalog"."default",
  "town" varchar(50) COLLATE "pg_catalog"."default",
  "service" varchar(20) COLLATE "pg_catalog"."default",
  "nationality" varchar(50) COLLATE "pg_catalog"."default",
  "date_of_birth" date
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student
-- ----------------------------
INSERT INTO "smisportal"."sm_student" VALUES (153, 'KMA401/0114/2022', 'KIPROTICH', 'HILLARY', 'M', 'KEN', '33346096', NULL, '113901', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (155, 'KMA401/0116/2022', 'MISIKO', 'MOSES WANYONYI', 'M', 'KEN', '35441558', NULL, '113904', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (156, 'KMA401/0117/2022', 'MWAURA', 'PAUL NYAGA', 'M', 'KEN', '36497068', NULL, '113905', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (157, 'KMA401/0118/2022', 'JAGERO', 'DONALD OMONDI', 'M', 'KEN', '36873696', NULL, '113906', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (158, 'KMA401/0119/2022', 'KAMAU', 'BRYAN MUTUKU', 'M', 'KEN', '36915053', NULL, '113907', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (159, 'KMA401/0120/2022', 'KIPNGETICH', 'BRIAN ', 'M', 'KEN', '36990453', NULL, '113908', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (160, 'KMA401/0121/2022', 'OLOO', 'TONY OMONDI', 'M', 'KEN', '37382733', NULL, '113909', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (161, 'KMA401/0122/2022', 'MAINA', 'JACKSON MWANGI', 'M', 'KEN', '37560669', NULL, '113910', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (162, 'KMA401/0123/2022', 'LAGAT', 'BENARD KIPNGENO', 'M', 'KEN', '37592653', NULL, '113911', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (163, 'KMA401/0124/2022', 'KIBUNJA', 'GLADYS WANJIKU', 'F', 'KEN', '37795980', NULL, '113912', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (164, 'KMA401/0125/2022', 'KHISA', 'FRESNEL BARASA', 'M', 'KEN', '37895076', NULL, '113913', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (165, 'KMA401/0126/2022', 'TOME', 'JOSPHAT', 'M', 'KEN', '38081324', NULL, '113914', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (166, 'KMA401/0127/2022', 'NGA''NGA''A', 'PETER MACHARIA', 'M', 'KEN', '38522178', NULL, '113915', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (167, 'KMA401/0128/2022', 'OTIENO', 'ROLEX OTIENO', 'M', 'KEN', '38814413', NULL, '113916', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (168, 'KMA401/0129/2022', 'MAINA', 'BRIAN KARIUKI', 'M', 'KEN', '38896118', NULL, '113917', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (169, 'KMA401/0130/2022', 'KIMANI', 'DAVID NJUNGO', 'M', 'KEN', '38932084', NULL, '113918', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (170, 'KMA401/0131/2022', 'KIIRU', 'MBARE PHIL DANCAN', 'M', 'KEN', '38958778', NULL, '113919', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (171, 'KMA401/0132/2022', 'NDUNGU', 'JUSTIN KURIA', 'M', 'KEN', '39110247', NULL, '113920', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (172, 'KMA401/0133/2022', 'LIONELL', 'LEMERIAN', 'M', 'KEN', '39127237', NULL, '113921', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (173, 'KMA401/0134/2022', 'WANJALA', 'PHASILA NAFULA', 'F', 'KEN', '39206012', NULL, '113922', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (174, 'KMA401/0135/2022', 'NDIRANGU', 'BENSON KAHIGA', 'M', 'KEN', '39328357', NULL, '113923', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (175, 'KMA401/0136/2022', 'NJOKI', 'KELVIN MBURU', 'M', 'KEN', '39404576', NULL, '113924', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (176, 'KMA401/0137/2022', 'ANDERSON', 'EMMACULATE REHEMA', 'F', 'KEN', '39498934', NULL, '113925', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (177, 'KMA401/0138/2022', 'GABOW', 'FEISAL BISHAR', 'M', 'KEN', '39635840', NULL, '113926', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (178, 'KMA401/0139/2022', 'WANJIRU', 'PAUL THUKU', 'M', 'KEN', '39779411', NULL, '113927', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (179, 'KMA401/0140/2022', 'JOEL', 'IAN MIENCHA', 'M', 'KEN', '39886546', NULL, '113928', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (180, 'KMA401/0141/2022', 'MUTUKU', 'STEPHEN MUTETI', 'M', 'KEN', '39916822', NULL, '113929', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (181, 'KMA401/0142/2022', 'OGOLLA', 'KYLE KISSACHE', 'M', 'KEN', '40004479', NULL, '113930', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (182, 'KMA401/0143/2022', 'IBRAHIM', 'YAHYA ALI IBRAHIM', 'M', 'KEN', '40073150', NULL, '113931', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (183, 'KMA401/0144/2022', 'NJIRUIBRAHIM', 'BREDA MUMANTHI', 'M', 'KEN', '40183062', NULL, '113932', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (184, 'KMA401/0145/2022', 'KIPNGENO', 'ANDERSON', 'M', 'KEN', '40328523', NULL, '113933', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (185, 'KMA401/0146/2022', 'ODEMBE', 'GORETY ACHIENG ', 'F', 'KEN', '33302494', NULL, '138457', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (186, 'KMA401/0147/2022', 'NJAGI', 'DENIS MURIMI', 'M', 'KEN', '33332784', NULL, '138458', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (187, 'KMA401/0148/2022', 'MATI', 'KAMENE', 'F', 'KEN', '33594776', NULL, '138459', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (188, 'KMA401/0149/2022', 'MUTURI', 'JOHN NJENGAH', 'M', 'KEN', '33708722', NULL, '138460', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (189, 'KMA401/0150/2022', 'MAKOKHA', 'SYDNEY BWONYA', 'M', 'KEN', '34737554', NULL, '138461', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (190, 'KMA401/0151/2022', 'KAMAU', 'FRANCIS MUGENDI', 'M', 'KEN', '34749952', NULL, '138462', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (191, 'KMA401/0152/2022', 'MBALUKA', 'WINFRED WAENI', 'F', 'KEN', '34919604', NULL, '138463', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (192, 'KMA401/0153/2022', 'KIRUI', 'VICTOR KIPROTICH ', 'M', 'KEN', '34956577', NULL, '138464', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (193, 'KMA401/0154/2022', 'BARASA', 'DEBORA MWABE', 'F', 'KEN', '35048617', NULL, '138465', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (194, 'KMA401/0155/2022', 'NYAGAKA', 'CLINTON OIGORO', 'M', 'KEN', '36165447', NULL, '138466', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (195, 'KMA401/0156/2022', 'KINUTHIA', 'DENNIS NJOROGE', 'M', 'KEN', '36675238', NULL, '138467', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (196, 'KMA401/0157/2022', 'MIDIKO', 'AMBUKA ASTONE', 'M', 'KEN', '37437406', NULL, '138468', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (197, 'KMA401/0158/2022', 'LAGAT', 'REINHARD KIPKOECH', 'M', 'KEN', '38176912', NULL, '138469', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (198, 'KMA401/0159/2022', 'KIPKIRUI', 'CALEB', 'M', 'KEN', '38446963', NULL, '138470', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (199, 'KMA401/0160/2022', 'MWANZIA', 'FANUEL NYAMAI', 'M', 'KEN', '38469904', NULL, '138471', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (200, 'KMA401/0161/2022', 'BOCHA', 'HARITH ABDI ', 'M', 'KEN', '38727995', NULL, '138472', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (201, 'KMA401/0162/2022', 'MUTUGI', 'KELVIN MBURU', 'M', 'KEN', '39219240', NULL, '138473', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (202, 'KMA401/0163/2022', 'SIMON', 'GRACE NASIEKU', 'F', 'KEN', '39284167', NULL, '138474', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (203, 'KMA401/0164/2022', 'KIBET', 'OLIVER KIPCHUMBA', 'M', 'KEN', '39541061', NULL, '138475', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (204, 'KMA401/0165/2022', 'OMAR', 'ISSA ABDI', 'M', 'KEN', '40116525', NULL, '138476', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (205, 'KMA401/0166/2022', 'CHERUIYOT', 'AMOS', 'M', 'KEN', '40159319', NULL, '138477', NULL, 3, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (206, 'KMA401/0167/2022', 'ABIZERA', 'JOLI LANDDRY', 'M', 'BDI', NULL, 'SP0215529', '87133', NULL, 4, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (207, 'KMA401/0168/2022', 'TWAGIRAEZU', 'ELYSE ', 'M', 'BDI', NULL, 'SP0215528', '87250', NULL, 4, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (208, 'KMA401/0169/2022', 'IAN', 'MUGISHA MZIZA', 'M', 'RWA', NULL, 'PS115253', '141750', NULL, 5, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (209, 'KMA401/0170/2022', 'SEMU', 'MFURANKUNDA HENRY KEVIN', 'M', 'RWA', NULL, 'PS115254', '141749', NULL, 5, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (210, 'KMA401/0171/2022', 'PAUL', 'KASULE JOVAN', 'M', 'UGA', NULL, NULL, 'RA/246610', NULL, 6, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (211, 'KMA401/0172/2022', 'RONALD', 'WASWA', 'M', 'UGA', NULL, NULL, 'RA/249348', NULL, 6, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (212, 'KMA401/0173/2022', 'MACHAWA', 'MOISES FERNANDO', 'M', 'MOZ', NULL, 'AB1023712', '62020350829', NULL, 8, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (213, 'KMA401/0174/2022', 'GUACHA', 'MANUEL CHEFE ALERTO', 'M', 'MOZ', NULL, 'AB1023715', '212020350830', NULL, 8, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (214, 'KMA401/0175/2022', 'VENTURA', 'HISIDIN JOAQUIM', 'M', 'MOZ', NULL, 'AB1023718', '92020350699', NULL, 8, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (215, 'KMA401/0176/2022', 'SURANE', 'LICINIA AUGUSTO', 'F', 'MOZ', NULL, 'AB1023716', '232020350732', NULL, 8, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (216, 'KMA401/0177/2022', 'ANTONIO', 'BEATRIZ', 'F', 'MOZ', NULL, 'AB1023719', '157894358', NULL, 8, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (217, 'KMA401/0178/2022', 'JEREDI', 'PASCAL ERIO', 'M', 'TZA', NULL, 'TAE459342', 'MT132405', NULL, 7, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (218, 'KMA401/0179/2022', 'WARIOBA', 'HAMISI ISSA', 'M', 'TZA', NULL, 'TAE459360', 'MT132360', NULL, 7, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (41, 'KMA401/0001/2022', 'MOKAYA', 'FAITH NYATICHI', 'F', 'KEN', '32535638', NULL, '166297', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (42, 'KMA401/0002/2022', 'MBUGUA', 'DAVIES KAMAU', 'M', 'KEN', '33070552', NULL, '166298', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (43, 'KMA401/0003/2022', 'KANYOTU', 'FRANCIS BUNDI', 'M', 'KEN', '33166132', NULL, '166299', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (44, 'KMA401/0004/2022', 'CHERUIYOT', 'HEZRA', 'M', 'KEN', '33187377', NULL, '166300', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (45, 'KMA401/0005/2022', 'WANYONYI', 'ENOCK WANJALA', 'M', 'KEN', '33280563', NULL, '166301', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (46, 'KMA401/0006/2022', 'OPIYO', 'DENIS KOLA', 'M', 'KEN', '33288832', NULL, '166302', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (47, 'KMA401/0007/2022', 'MUEMA', 'DENIS KIOKO', 'M', 'KEN', '33316718', NULL, '166303', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (48, 'KMA401/0008/2022', 'MUHOHO', 'NIMROD MUNGAI', 'M', 'KEN', '33428543', NULL, '166304', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (49, 'KMA401/0009/2022', 'ODIWUOR', 'TEDD EDWIN', 'M', 'KEN', '33519127', NULL, '166305', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (50, 'KMA401/0010/2022', 'MASAI', 'RODGERS CHEPKECH', 'M', 'KEN', '33582884', NULL, '166306', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (51, 'KMA401/0011/2022', 'IRERI', 'GIDEON KINYUA', 'M', 'KEN', '33723798', NULL, '166307', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (52, 'KMA401/0012/2022', 'MUCHIRI', 'GEOFREY MWANGI', 'M', 'KEN', '33895274', NULL, '166308', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (53, 'KMA401/0013/2022', 'AYIERA', 'DANCAN OCHIENG', 'M', 'KEN', '33927738', NULL, '166309', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (54, 'KMA401/0014/2022', 'WACHIRA', 'JOHN MWANGI', 'M', 'KEN', '33929987', NULL, '166310', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (55, 'KMA401/0015/2022', 'MUCHERU', 'ALFRED KABIRU', 'M', 'KEN', '33931223', NULL, '166311', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (56, 'KMA401/0016/2022', 'ODOUR', 'EVANS ODHIAMBO', 'M', 'KEN', '33945433', NULL, '166312', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (57, 'KMA401/0017/2022', 'ONDITI', 'FESTUS OUDIA', 'M', 'KEN', '33950553', NULL, '166313', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (58, 'KMA401/0018/2022', 'OJAKAA', 'TIMOTHY', 'M', 'KEN', '34081734', NULL, '166314', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (59, 'KMA401/0019/2022', 'KANYUA', 'KEN KIMEMIA', 'M', 'KEN', '34116270', NULL, '166315', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (60, 'KMA401/0020/2022', 'WAITHERERO', 'JEREMIAH GACHERU', 'M', 'KEN', '34171605', NULL, '166316', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (61, 'KMA401/0021/2022', 'NDUGULI', 'MORRIS', 'M', 'KEN', '34252635', NULL, '166317', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (62, 'KMA401/0022/2022', 'MOHAMED', 'ISSACK HASSAN', 'M', 'KEN', '34258160', NULL, '166318', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (63, 'KMA401/0023/2022', 'KIRIA', 'KENNEDY KINGARA', 'M', 'KEN', '34363108', NULL, '166319', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (64, 'KMA401/0024/2022', 'EFEDHA', 'ALFRED KABIRU', 'M', 'KEN', '34380492', NULL, '166320', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (65, 'KMA401/0025/2022', 'KIRUI', 'FESTUS KIPCHIRCHIR', 'M', 'KEN', '34445413', NULL, '166321', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (66, 'KMA401/0026/2022', 'KIPLETING', 'PASCALIS', 'M', 'KEN', '34564212', NULL, '166322', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (67, 'KMA401/0027/2022', 'AKEYO', 'OKINYI MOLLINE', 'F', 'KEN', '34647822', NULL, '166323', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (68, 'KMA401/0028/2022', 'OMORI', 'MORANGA IAN', 'M', 'KEN', '34924692', NULL, '166324', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (69, 'KMA401/0029/2022', 'KORIR', 'BRIAN KIPRUTO', 'M', 'KEN', '34931581', NULL, '166325', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (70, 'KMA401/0030/2022', 'SECHERE', 'JAMAL', 'M', 'KEN', '34959104', NULL, '166326', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (71, 'KMA401/0031/2022', 'KINYUA', 'GERRALD KIMENYI', 'M', 'KEN', '35001585', NULL, '166327', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (72, 'KMA401/0032/2022', 'MBURU', 'CLIFFORD', 'M', 'KEN', '35095595', NULL, '166328', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (73, 'KMA401/0033/2022', 'MUTISYA', 'DANNIEL KATUA', 'M', 'KEN', '35156182', NULL, '166329', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (74, 'KMA401/0034/2022', 'CHESEREK', 'JONAH KIBET', 'M', 'KEN', '35668696', NULL, '166330', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (75, 'KMA401/0035/2022', 'NJOROGE', 'LEON CHEGE', 'M', 'KEN', '35732366', NULL, '166331', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (76, 'KMA401/0036/2022', 'KIPNGENO', 'VICTOR', 'M', 'KEN', '35748471', NULL, '166332', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (77, 'KMA401/0037/2022', 'NYAGA', 'DENIS MUTHOMI', 'M', 'KEN', '35755187', NULL, '166333', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (78, 'KMA401/0038/2022', 'MUSEE', 'DOMINIC', 'M', 'KEN', '35827357', NULL, '166334', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (79, 'KMA401/0039/2022', 'KANDOLLE', 'PINKI ENGEFU', 'F', 'KEN', '35840625', NULL, '166335', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (80, 'KMA401/0040/2022', 'SIFUNA', 'JOSEPHAT', 'M', 'KEN', '36197646', NULL, '166336', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (81, 'KMA401/0041/2022', 'LENTIMALEI', 'DAVID LPIRIATO', 'M', 'KEN', '36251545', NULL, '166337', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (82, 'KMA401/0042/2022', 'RASHID', 'MOHAMED AMIN', 'M', 'KEN', '36376361', NULL, '166338', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (83, 'KMA401/0043/2022', 'AKWAM', 'MERCY', 'F', 'KEN', '36392624', NULL, '166339', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (84, 'KMA401/0044/2022', 'GODE', 'WESLEY PAUL OBUYA', 'M', 'KEN', '36421199', NULL, '166340', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (85, 'KMA401/0045/2022', 'MBUGUA', 'JANE WANJIRU', 'F', 'KEN', '36751715', NULL, '166341', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (86, 'KMA401/0046/2022', 'MUINDI', 'PATRICK MWENDWA', 'M', 'KEN', '36882212', NULL, '166342', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (87, 'KMA401/0047/2022', 'KIRUI', 'EMMANUEL KIPCHIRCHIR', 'M', 'KEN', '36943876', NULL, '166343', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (88, 'KMA401/0048/2022', 'JUMA', 'DANIEL MUKABI', 'M', 'KEN', '37000514', NULL, '166344', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (89, 'KMA401/0049/2022', 'MRABU', 'MARTIN  RAI', 'M', 'KEN', '37092779', NULL, '166345', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (90, 'KMA401/0050/2022', 'MALIMU', 'LAUGRACIOURS ', 'F', 'KEN', '37309761', NULL, '166346', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (91, 'KMA401/0051/2022', 'ACHIENG', 'WINNIE', 'F', 'KEN', '37340321', NULL, '166347', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (92, 'KMA401/0052/2022', 'MOHAMMED', 'MOHAMED ABDALLAH', 'M', 'KEN', '37500083', NULL, '166348', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (93, 'KMA401/0053/2022', 'MBOYA', 'SYNTHIA AWUOR', 'F', 'KEN', '37573004', NULL, '166349', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (94, 'KMA401/0054/2022', 'KIGEN', 'IAN KIPKOECH', 'M', 'KEN', '37610413', NULL, '166350', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (95, 'KMA401/0055/2022', 'MWANGI', 'CATHERINE NYAMBURA', 'F', 'KEN', '37745464', NULL, '166351', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (96, 'KMA401/0056/2022', 'JARSON', 'YUSUF MOHAMED', 'M', 'KEN', '37774212', NULL, '166352', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (97, 'KMA401/0057/2022', 'MWANIKI', 'WILSON MUTUGI', 'M', 'KEN', '37820610', NULL, '166353', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (98, 'KMA401/0058/2022', 'CHISHENGA', 'VINCENT ', 'M', 'KEN', '37938005', NULL, '166354', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (99, 'KMA401/0059/2022', 'GITAU', 'EDWARD', 'M', 'KEN', '37969761', NULL, '166355', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (100, 'KMA401/0060/2022', 'LEMAYAN', 'REUBEN LENGISHON', 'M', 'KEN', '37996332', NULL, '166356', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (101, 'KMA401/0061/2022', 'MUNGANIA', 'ROBERT MUTEMBEI', 'M', 'KEN', '38055917', NULL, '166357', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (102, 'KMA401/0062/2022', 'IMENYI', 'LOISE KATHURE', 'F', 'KEN', '38161560', NULL, '166358', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (103, 'KMA401/0063/2022', 'SHEMAKA', 'ABDULAZIZ ABDU', 'M', 'KEN', '38189575', NULL, '166359', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (104, 'KMA401/0064/2022', 'KORIR', 'ELIAS KIPKOECH', 'M', 'KEN', '38216309', NULL, '166360', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (105, 'KMA401/0065/2022', 'DANIEL', 'KELAI ESOKON', 'M', 'KEN', '38228673', NULL, '166361', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (106, 'KMA401/0066/2022', 'KIRETAI', 'LAWRENCE KAGUMBA', 'M', 'KEN', '38592881', NULL, '166362', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (107, 'KMA401/0067/2022', 'KARIUKI', 'ISAAC NGURI', 'M', 'KEN', '38593077', NULL, '166363', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (108, 'KMA401/0068/2022', 'NYALAMIA', 'JONAH KATEI', 'M', 'KEN', '38625482', NULL, '166364', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (109, 'KMA401/0069/2022', 'LIHANDA', 'VICTOR ', 'M', 'KEN', '38634822', NULL, '166365', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (110, 'KMA401/0070/2022', 'OLESAIGULU', 'LEONARD SUNKULI', 'M', 'KEN', '38700470', NULL, '166366', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (111, 'KMA401/0071/2022', 'GALGALLO', 'KUSHE', 'M', 'KEN', '38731402', NULL, '166367', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (112, 'KMA401/0072/2022', 'HADULO', 'KEVIN OSIR', 'M', 'KEN', '38813558', NULL, '166368', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (113, 'KMA401/0073/2022', 'MOSAREMO', 'ONESMUS OMBATI', 'M', 'KEN', '38880795', NULL, '166369', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (114, 'KMA401/0074/2022', 'TUITOEK', 'DICKSON KIPKOECH', 'M', 'KEN', '38993278', NULL, '166370', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (115, 'KMA401/0075/2022', 'LABAT', 'ANDREW KIPCHUMBA', 'M', 'KEN', '38996481', NULL, '166371', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (116, 'KMA401/0076/2022', 'KIPLAGAT', 'ERICK', 'M', 'KEN', '39027762', NULL, '166372', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (117, 'KMA401/0077/2022', 'GODANA', 'ABDULLAHI ABECHO', 'M', 'KEN', '39034984', NULL, '166373', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (118, 'KMA401/0078/2022', 'LENAPEER', 'PATRICK LTORIAS', 'M', 'KEN', '39119776', NULL, '166374', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (119, 'KMA401/0079/2022', 'WAKHUNGU', 'DORCUS NALIAKA', 'F', 'KEN', '39131918', NULL, '166375', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (120, 'KMA401/0080/2022', 'MWANYENGELA', 'DAVID NYAMBU', 'M', 'KEN', '39146308', NULL, '166376', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (121, 'KMA401/0081/2022', 'KARARI', 'TRACY MARGARET WANGARI', 'F', 'KEN', '39273199', NULL, '166377', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (122, 'KMA401/0082/2022', 'PERESIA', 'NKANINI', 'M', 'KEN', '39284885', NULL, '166378', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (123, 'KMA401/0083/2022', 'MUIRURI', 'GABRIEL NYAGA', 'M', 'KEN', '39323283', NULL, '166379', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (124, 'KMA401/0084/2022', 'NICKSON', 'LAGAT CHERUIYOT', 'M', 'KEN', '39323905', NULL, '166380', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (125, 'KMA401/0085/2022', 'PARTOIP', 'ALTON SEMPELE', 'M', 'KEN', '39357446', NULL, '166381', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (126, 'KMA401/0086/2022', 'MORARA', 'PURITY KEMUNTO', 'F', 'KEN', '39361618', NULL, '166382', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (127, 'KMA401/0087/2022', 'WANJOHI', 'DAVID MUGO', 'M', 'KEN', '39373532', NULL, '166383', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (128, 'KMA401/0088/2022', 'KIPROP', 'FILEX', 'M', 'KEN', '39416183', NULL, '166384', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (129, 'KMA401/0089/2022', 'ADAM', 'MOFFAT MORARA', 'M', 'KEN', '39416224', NULL, '166385', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (130, 'KMA401/0090/2022', 'MARWA', 'FERDINAND MANGITENI', 'M', 'KEN', '39435083', NULL, '166386', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (131, 'KMA401/0091/2022', 'KAMAU', 'ROBERT GITHAIGA', 'M', 'KEN', '39471302', NULL, '166387', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (132, 'KMA401/0092/2022', 'ROTIKEN', 'DENNIS KIYIAN', 'M', 'KEN', '39493716', NULL, '166388', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (133, 'KMA401/0093/2022', 'JOHN', 'BERNARD MULU', 'M', 'KEN', '39595587', NULL, '166389', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (134, 'KMA401/0094/2022', 'ISAM', 'JOSHUA MWAODA', 'M', 'KEN', '39608392', NULL, '166390', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (135, 'KMA401/0095/2022', 'MUIA', 'DENNIS KYAMA', 'M', 'KEN', '39611260', NULL, '166391', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (136, 'KMA401/0096/2022', 'KARIUKI', 'JOHN BOSCO NJUGUNA', 'M', 'KEN', '39638369', NULL, '166392', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (137, 'KMA401/0097/2022', 'NYAKWAMA', 'HENRY OMONDI', 'M', 'KEN', '39661961', NULL, '166393', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (138, 'KMA401/0098/2022', 'KEMBOI', 'BENJAMIN KIPYEGON', 'M', 'KEN', '39680280', NULL, '166394', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (139, 'KMA401/0099/2022', 'WASIKE', 'SHADDAD AYUB', 'M', 'KEN', '39681657', NULL, '166395', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (140, 'KMA401/0100/2022', 'DULLU', 'DENIS KOMORA', 'M', 'KEN', '39723743', NULL, '166396', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (141, 'KMA401/0101/2022', 'KIRUI', 'MOSES KIPKOECH', 'M', 'KEN', '39728485', NULL, '166397', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (142, 'KMA401/0102/2022', 'MASESE', 'VERONICAH MUTIO', 'F', 'KEN', '39763078', NULL, '166398', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (143, 'KMA401/0103/2022', 'KOSGEI', 'COLLINS KIMUTAI', 'M', 'KEN', '39866569', NULL, '166399', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (144, 'KMA401/0104/2022', 'ROTICH', 'FELIX KIPLANGAT', 'M', 'KEN', '39964228', NULL, '166400', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (145, 'KMA401/0105/2022', 'MAHABA', 'RAMADHAN OMAR', 'M', 'KEN', '39978630', NULL, '166401', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (146, 'KMA401/0106/2022', 'MULI', 'OSAAC KIM', 'M', 'KEN', '40110572', NULL, '166402', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (147, 'KMA401/0107/2022', 'ISSACK', 'ABDIKHAFAR MOHAMED', 'M', 'KEN', '40141482', NULL, '166403', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (148, 'KMA401/0108/2022', 'CHEPTOO', 'MERCY', 'F', 'KEN', '40144631', NULL, '166404', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (149, 'KMA401/0109/2022', 'AMANMAN', 'MARIO LOIU', 'M', 'KEN', '40181862', NULL, '166405', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (150, 'KMA401/0110/2022', 'MNANGAT', 'HILLARY', 'M', 'KEN', '40222213', NULL, '166406', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (151, 'KMA401/0111/2022', 'WANJUGU', 'BENHADAD NDUG''U', 'M', 'KEN', '40241594', NULL, '166407', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (152, 'KMA401/0112/2022', 'MOHAMED', 'ABDIMALIK AHMED', 'M', 'KEN', '40376308', NULL, '166408', NULL, 1, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (2, 'KMA401/0113/2022', 'CHEGE', 'JONANA MURAGU', 'M', 'KEN', '33331114', NULL, '113900', NULL, 2, '2022-03-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (154, 'KMA401/0115/2022', 'JUMA', 'DAVID MASIGA', 'M', 'KEN', '331165288', '123547898', '113903', NULL, 2, '2022-03-16 00:00:00', '0714385059', '0739823669', 'rambasu@uonbi.ac.ke', 'idachirfus@gmail.com', '00209', '12367', 'NGONG HILLS', NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (479, 'P15/7233/2023', 'JOHN', 'DOE', 'm', 'KEN', NULL, NULL, NULL, NULL, NULL, '2023-01-20 00:00:00', '254757542286', '254792550048', 'rambasu@uonbi.ac.ke', 'idachirufus@gmail.com', '90220', '8-90220', 'MUTOMO', NULL, NULL, '2023-01-20');
INSERT INTO "smisportal"."sm_student" VALUES (499, 'P15/7227/2023', 'DAVID', 'DAVID', 'm', 'KEN', NULL, NULL, NULL, NULL, NULL, '2023-01-24 00:00:00', '254759759793', '254718336571', 'rambasu@uonbi.ac.ke', 'idachirufus@gmail.com', '704', '', 'DHIWA', NULL, NULL, '2023-01-24');
INSERT INTO "smisportal"."sm_student" VALUES (519, 'P15/744/2023', 'ALLAN', 'ALLAN', 'm', 'KEN', NULL, NULL, NULL, NULL, NULL, '2023-01-24 00:00:00', '254704785506', '254721230308', 'rambasu@uonbi.ac.ke', 'loicemarindich@gmail.com', '30100', '8565', 'ELDORET', NULL, NULL, '2023-01-24');
INSERT INTO "smisportal"."sm_student" VALUES (639, 'P15/550/2023', 'KIPTOO', 'KIPTOO', 'f', 'KEN', NULL, NULL, NULL, NULL, NULL, '2023-02-07 00:00:00', '254707811245', '254725713925', 'rambasu@uonbi.ac.ke', 'idachirufus@gmail.com', '00200', '53480 00200 NAIROBI', 'NAIROBI', NULL, NULL, NULL);
INSERT INTO "smisportal"."sm_student" VALUES (659, 'P15/744/2023', 'ALLAN', 'ALLAN', 'm', 'KEN', NULL, NULL, NULL, NULL, NULL, '2023-02-07 00:00:00', '254704785506', '254721230308', 'rambasu@uonbi.ac.ke', 'loicemarindich@gmail.com', '30100', '8565', 'ELDORET', NULL, NULL, '2023-01-24');
INSERT INTO "smisportal"."sm_student" VALUES (819, 'P15/245/2023', 'MSHINDI', 'MSHINDI', 'm', 'KEN', NULL, NULL, NULL, NULL, NULL, '2023-02-07 00:00:00', '254745726432', '254757302928', 'rambasu@uonbi.ac.ke', 'idachirufus@gmail.com', '40102', '177', 'AHERO', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sm_student_category
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_category";
CREATE TABLE "smisportal"."sm_student_category" (
  "std_category_id" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "std_category_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_student_category" IS 'undergraduate module I, undergraduate module II etc';

-- ----------------------------
-- Records of sm_student_category
-- ----------------------------
INSERT INTO "smisportal"."sm_student_category" OVERRIDING SYSTEM VALUE VALUES (1, 'UNDERGRADUATE MODULE I');
INSERT INTO "smisportal"."sm_student_category" OVERRIDING SYSTEM VALUE VALUES (2, 'POSTGRADUATE MODULE I');
INSERT INTO "smisportal"."sm_student_category" OVERRIDING SYSTEM VALUE VALUES (3, 'UNDERGRADUATE MODULE II');
INSERT INTO "smisportal"."sm_student_category" OVERRIDING SYSTEM VALUE VALUES (4, 'POSTGRADUATE MODULE II');

-- ----------------------------
-- Table structure for sm_student_cohort_history
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_cohort_history";
CREATE TABLE "smisportal"."sm_student_cohort_history" (
  "stud_cohort_hist_id" int8 NOT NULL,
  "stud_id" int8 NOT NULL,
  "cohort_id" int8 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_cohort_history
-- ----------------------------
INSERT INTO "smisportal"."sm_student_cohort_history" VALUES (10, 479, 21);
INSERT INTO "smisportal"."sm_student_cohort_history" VALUES (11, 499, 21);
INSERT INTO "smisportal"."sm_student_cohort_history" VALUES (12, 519, 21);
INSERT INTO "smisportal"."sm_student_cohort_history" VALUES (16, 639, 161);
INSERT INTO "smisportal"."sm_student_cohort_history" VALUES (17, 659, 161);
INSERT INTO "smisportal"."sm_student_cohort_history" VALUES (20, 719, 161);
INSERT INTO "smisportal"."sm_student_cohort_history" VALUES (22, 759, 161);
INSERT INTO "smisportal"."sm_student_cohort_history" VALUES (25, 819, 161);

-- ----------------------------
-- Table structure for sm_student_contact_person
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_contact_person";
CREATE TABLE "smisportal"."sm_student_contact_person" (
  "contact_pid" int4 NOT NULL,
  "contact_name" varchar(150) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_contact_person
-- ----------------------------

-- ----------------------------
-- Table structure for sm_student_id
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_id";
CREATE TABLE "smisportal"."sm_student_id" (
  "student_id_serial_no" int4 NOT NULL,
  "student_prog_curr_id" int4 NOT NULL,
  "issuance_date" date NOT NULL,
  "valid_from" date NOT NULL,
  "valid_to" date NOT NULL,
  "barcode" int4 NOT NULL,
  "id_status" varchar(15) COLLATE "pg_catalog"."default",
  "printing_date" date DEFAULT CURRENT_TIMESTAMP
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_student_id" IS 'Student Identity card details';

-- ----------------------------
-- Records of sm_student_id
-- ----------------------------

-- ----------------------------
-- Table structure for sm_student_id_details
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_id_details";
CREATE TABLE "smisportal"."sm_student_id_details" (
  "stud_id_det_id" int4 NOT NULL,
  "student_id_serial_no" int4 NOT NULL,
  "student_id_status" varchar COLLATE "pg_catalog"."default" NOT NULL,
  "remarks" varchar COLLATE "pg_catalog"."default" NOT NULL,
  "status_date" date NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_id_details
-- ----------------------------

-- ----------------------------
-- Table structure for sm_student_id_request
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_id_request";
CREATE TABLE "smisportal"."sm_student_id_request" (
  "request_id" int4 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "request_type_id" int4 NOT NULL,
  "student_prog_curr_id" int4 NOT NULL,
  "request_date" timestamptz(6) NOT NULL,
  "status_id" int4 NOT NULL,
  "receipt_number" int4,
  "source" varchar(30) COLLATE "pg_catalog"."default" NOT NULL,
  "student_id_sync_status" bool
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_student_id_request" IS 'requests made by student for id';

-- ----------------------------
-- Records of sm_student_id_request
-- ----------------------------
INSERT INTO "smisportal"."sm_student_id_request" VALUES (4, 1, 5, '2023-01-30 00:00:00+03', 1, NULL, 'Lost id', NULL);
INSERT INTO "smisportal"."sm_student_id_request" VALUES (5, 1, 4, '2023-01-31 00:00:00+03', 1, NULL, 'lost ID', NULL);
INSERT INTO "smisportal"."sm_student_id_request" VALUES (6, 1, 1, '2023-02-03 00:00:00+03', 1, NULL, 'ID eaten by a dog', NULL);
INSERT INTO "smisportal"."sm_student_id_request" VALUES (7, 1, 1, '2023-02-03 00:00:00+03', 1, NULL, 'ID eaten by a dog', NULL);
INSERT INTO "smisportal"."sm_student_id_request" VALUES (8, 1, 1, '2023-02-03 00:00:00+03', 1, NULL, 'ID eaten by a dog', NULL);

-- ----------------------------
-- Table structure for sm_student_id_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_id_status";
CREATE TABLE "smisportal"."sm_student_id_status" (
  "id_status_no" int4 NOT NULL,
  "status_name" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "student_id_serial_no" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_id_status
-- ----------------------------

-- ----------------------------
-- Table structure for sm_student_prog_curr_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_prog_curr_status";
CREATE TABLE "smisportal"."sm_student_prog_curr_status" (
  "student_prog_curr_stat_id" int4 NOT NULL,
  "student_programme_curriculum_id" int4 NOT NULL,
  "student_status_id" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_prog_curr_status
-- ----------------------------

-- ----------------------------
-- Table structure for sm_student_programme_curriculum
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_programme_curriculum";
CREATE TABLE "smisportal"."sm_student_programme_curriculum" (
  "student_prog_curriculum_id" int8 NOT NULL,
  "student_id" int8 NOT NULL,
  "registration_number" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "prog_curriculum_id" int8 NOT NULL,
  "student_category_id" int4 NOT NULL,
  "adm_refno" int4 NOT NULL,
  "status_id" int4 NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_programme_curriculum
-- ----------------------------
INSERT INTO "smisportal"."sm_student_programme_curriculum" VALUES (1, 154, 'KMA401/0115/2022', 36, 1, 6001, 1);
INSERT INTO "smisportal"."sm_student_programme_curriculum" VALUES (3, 479, 'P15/7233/2023', 36, 1, 7233, 1);
INSERT INTO "smisportal"."sm_student_programme_curriculum" VALUES (4, 499, 'P15/7227/2023', 36, 1, 7227, 1);
INSERT INTO "smisportal"."sm_student_programme_curriculum" VALUES (5, 519, 'P15/744/2023', 36, 1, 744, 1);
INSERT INTO "smisportal"."sm_student_programme_curriculum" VALUES (8, 639, 'P15/550/2023', 36, 1, 550, 1);
INSERT INTO "smisportal"."sm_student_programme_curriculum" VALUES (240, 819, 'P15/245/2023', 36, 1, 245, 1);

-- ----------------------------
-- Table structure for sm_student_sem_session_progress
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_sem_session_progress";
CREATE TABLE "smisportal"."sm_student_sem_session_progress" (
  "student_semester_session_id" int8 NOT NULL,
  "semester_progress" int2,
  "registration_date" date,
  "academic_progress_id" int8 NOT NULL,
  "sem_progress_number" int4 NOT NULL,
  "billable" int4,
  "promotion_status" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "rep_status_id" int8 NOT NULL,
  "prom_status_id" int8 NOT NULL,
  "reporting_sync_status" bool
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON COLUMN "smisportal"."sm_student_sem_session_progress"."sem_progress_number" IS 'The student semester progression..ie 1,2,3';
COMMENT ON TABLE "smisportal"."sm_student_sem_session_progress" IS '1.billable field is retained.
2.semester progress no is the cumuluative no of semesters . 8,10,12,15
3. semester progress is the normal semester 1,2
4. promotion status is the automatic status given.How you landed to this semester(promoted, resumed)
5. reporting status is whether you pre-registration,reported, defered,skipped';

-- ----------------------------
-- Records of sm_student_sem_session_progress
-- ----------------------------
INSERT INTO "smisportal"."sm_student_sem_session_progress" VALUES (61, 1, '2023-02-10', 7, 1, NULL, 'REGISTERED', 2, 2, 't');

-- ----------------------------
-- Table structure for sm_student_semester_group
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_semester_group";
CREATE TABLE "smisportal"."sm_student_semester_group" (
  "student_semester_group_id" int8 NOT NULL,
  "prog_curriculum_semester_id" int8 NOT NULL,
  "student_semester_session_id" int8 NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_semester_group
-- ----------------------------

-- ----------------------------
-- Table structure for sm_student_semester_session_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_semester_session_status";
CREATE TABLE "smisportal"."sm_student_semester_session_status" (
  "status_id" int4 NOT NULL,
  "status_name" varchar COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_student_semester_session_status" IS 'semester status eg session, skipped, deferred, supplementary';

-- ----------------------------
-- Records of sm_student_semester_session_status
-- ----------------------------
INSERT INTO "smisportal"."sm_student_semester_session_status" VALUES (1, 'ACTIVE');
INSERT INTO "smisportal"."sm_student_semester_session_status" VALUES (2, 'REPORTED');

-- ----------------------------
-- Table structure for sm_student_session_details
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_session_details";
CREATE TABLE "smisportal"."sm_student_session_details" (
  "student_session_details_id" int4 NOT NULL,
  "student_semester_session_id" int4
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_session_details
-- ----------------------------

-- ----------------------------
-- Table structure for sm_student_sponsor
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_sponsor";
CREATE TABLE "smisportal"."sm_student_sponsor" (
  "sponsor_id" int8 NOT NULL,
  "sponsor_code" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "sponsor_name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_student_sponsor
-- ----------------------------
INSERT INTO "smisportal"."sm_student_sponsor" VALUES (1, 'KA', 'KENYA ARMY', 'ACTIVE');
INSERT INTO "smisportal"."sm_student_sponsor" VALUES (2, 'KAF', 'KENYA AIR FORCE', 'ACTIVE');
INSERT INTO "smisportal"."sm_student_sponsor" VALUES (3, 'KN', 'KENYA NAVY', 'ACTIVE');
INSERT INTO "smisportal"."sm_student_sponsor" VALUES (4, 'BU', 'GOVT OF BURUNDI', 'ACTIVE');
INSERT INTO "smisportal"."sm_student_sponsor" VALUES (5, 'RW', 'GOVT OF RWANDA', 'ACTIVE');
INSERT INTO "smisportal"."sm_student_sponsor" VALUES (6, 'UG', 'GOVT OF UGANDA', 'ACTIVE');
INSERT INTO "smisportal"."sm_student_sponsor" VALUES (7, 'TZ', 'GOVT OF TANZANIA', 'ACTIVE');
INSERT INTO "smisportal"."sm_student_sponsor" VALUES (8, 'MZ', 'GOVT OF MOZAMBIQUE', 'ACTIVE');

-- ----------------------------
-- Table structure for sm_student_status
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_student_status";
CREATE TABLE "smisportal"."sm_student_status" (
  "status_id" int4 NOT NULL,
  "status" varchar(12) COLLATE "pg_catalog"."default" NOT NULL,
  "current_status" bool NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_student_status" IS 'eg INTER-FACULTY TRANSFER';

-- ----------------------------
-- Records of sm_student_status
-- ----------------------------
INSERT INTO "smisportal"."sm_student_status" VALUES (1, 'ACTIVE', 't');

-- ----------------------------
-- Table structure for sm_withdrawal_approval
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_withdrawal_approval";
CREATE TABLE "smisportal"."sm_withdrawal_approval" (
  "withdrawal_approval_id " int4 NOT NULL,
  "withdrawal_request_id " int4 NOT NULL,
  "approver_id" int4 NOT NULL,
  "comments " text COLLATE "pg_catalog"."default",
  "approval_status" varchar(50) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of sm_withdrawal_approval
-- ----------------------------

-- ----------------------------
-- Table structure for sm_withdrawal_request
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_withdrawal_request";
CREATE TABLE "smisportal"."sm_withdrawal_request" (
  "withdrawal_request_id" int4 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "withdrawal_type_id" int4 NOT NULL,
  "request_date" timestamptz(6) NOT NULL,
  "reason" varchar(250) COLLATE "pg_catalog"."default" NOT NULL,
  "approval_status" varchar COLLATE "pg_catalog"."default" NOT NULL,
  "student_id" int4 NOT NULL,
  "supporting_doc_url" varchar(200) COLLATE "pg_catalog"."default",
  "sync_status" bool
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of sm_withdrawal_request
-- ----------------------------
INSERT INTO "smisportal"."sm_withdrawal_request" VALUES (12, 2, '2022-11-29 00:00:00+03', 'sssiiiiii', 'PENDING', 142, 'uploads\deferment\KMA401_0102_2022_1669714986.pdf', NULL);
INSERT INTO "smisportal"."sm_withdrawal_request" VALUES (11, 2, '2022-12-09 00:00:00+03', 'ssseeeeeeeeeeeeeeeeee', 'PENDING', 142, 'uploads\deferment\KMA401_0102_2022_1669715160.pdf', NULL);
INSERT INTO "smisportal"."sm_withdrawal_request" VALUES (5, 1, '2023-01-25 00:00:00+03', 'My employer has posted me outside Kenya for a period of one year so I will not be able to attend physical lectures.I also dont want to attend online ones. I will be busy', 'PENDING', 142, '', NULL);
INSERT INTO "smisportal"."sm_withdrawal_request" VALUES (10, 1, '2023-01-31 00:00:00+03', 'Financial reason', 'PENDING', 142, 'uploads\deferment\Sample_pdf.pdf', NULL);

-- ----------------------------
-- Table structure for sm_withdrawal_type
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."sm_withdrawal_type";
CREATE TABLE "smisportal"."sm_withdrawal_type" (
  "withdrawal_type_id" int4 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "withdrawal_type_name" varchar(60) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;
COMMENT ON TABLE "smisportal"."sm_withdrawal_type" IS 'Withdrawal type e.g. Temporary withdrawal,Deferement';

-- ----------------------------
-- Records of sm_withdrawal_type
-- ----------------------------
INSERT INTO "smisportal"."sm_withdrawal_type" VALUES (1, 'Temporary Withdrawal');
INSERT INTO "smisportal"."sm_withdrawal_type" VALUES (2, 'Deferment');

-- ----------------------------
-- Table structure for student_sponsor
-- ----------------------------
DROP TABLE IF EXISTS "smisportal"."student_sponsor";
CREATE TABLE "smisportal"."student_sponsor" (
  "sponsor_id" int4 NOT NULL,
  "sponsor_name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smisportal"
;

-- ----------------------------
-- Records of student_sponsor
-- ----------------------------

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_id_request_status_status_id_seq"
OWNED BY "smisportal"."sm_id_request_status"."status_id";
SELECT setval('"smisportal"."sm_id_request_status_status_id_seq"', 1, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_id_request_type_request_type_id_seq"
OWNED BY "smisportal"."sm_id_request_type"."request_type_id";
SELECT setval('"smisportal"."sm_id_request_type_request_type_id_seq"', 1, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_intake_source_source_id_seq"
OWNED BY "smisportal"."sm_intake_source"."source_id";
SELECT setval('"smisportal"."sm_intake_source_source_id_seq"', 5, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_intakes_intake_code_seq"
OWNED BY "smisportal"."sm_intakes"."intake_code";
SELECT setval('"smisportal"."sm_intakes_intake_code_seq"', 3, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_name_change_name_change_id_seq"
OWNED BY "smisportal"."sm_name_change"."name_change_id";
SELECT setval('"smisportal"."sm_name_change_name_change_id_seq"', 13, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_reg_documents_document_id_seq"
OWNED BY "smisportal"."sm_reg_documents"."document_id";
SELECT setval('"smisportal"."sm_reg_documents_document_id_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_reg_required_document_required_document_id_seq"
OWNED BY "smisportal"."sm_reg_required_document"."required_document_id";
SELECT setval('"smisportal"."sm_reg_required_document_required_document_id_seq"', 44, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_student_category_std_category_id_seq"
OWNED BY "smisportal"."sm_student_category"."std_category_id";
SELECT setval('"smisportal"."sm_student_category_std_category_id_seq"', 4, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_student_id_request_request_id_seq"
OWNED BY "smisportal"."sm_student_id_request"."request_id";
SELECT setval('"smisportal"."sm_student_id_request_request_id_seq"', 8, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_withdrawal_request_withdrawal_request_id_seq"
OWNED BY "smisportal"."sm_withdrawal_request"."withdrawal_request_id";
SELECT setval('"smisportal"."sm_withdrawal_request_withdrawal_request_id_seq"', 12, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."sm_withdrawal_type_withdrawal_type_id_seq"
OWNED BY "smisportal"."sm_withdrawal_type"."withdrawal_type_id";
SELECT setval('"smisportal"."sm_withdrawal_type_withdrawal_type_id_seq"', 2, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "smisportal"."student_submitted_documents_student_document_id_seq"
OWNED BY "smisportal"."sm_stud_submitted_document"."student_document_id";
SELECT setval('"smisportal"."student_submitted_documents_student_document_id_seq"', 112, true);

-- ----------------------------
-- Primary Key structure for table cr_course_category
-- ----------------------------
ALTER TABLE "smisportal"."cr_course_category" ADD CONSTRAINT "course_category_pkey" PRIMARY KEY ("category_id");

-- ----------------------------
-- Primary Key structure for table cr_course_reg_status
-- ----------------------------
ALTER TABLE "smisportal"."cr_course_reg_status" ADD CONSTRAINT "course_registration_types_pkey" PRIMARY KEY ("course_reg_status_id");

-- ----------------------------
-- Primary Key structure for table cr_course_reg_type
-- ----------------------------
ALTER TABLE "smisportal"."cr_course_reg_type" ADD CONSTRAINT "course_reg_type_id_pkey" PRIMARY KEY ("course_reg_type_id");

-- ----------------------------
-- Primary Key structure for table cr_course_registration
-- ----------------------------
ALTER TABLE "smisportal"."cr_course_registration" ADD CONSTRAINT "student_course_registration_pkey" PRIMARY KEY ("student_course_reg_id");

-- ----------------------------
-- Primary Key structure for table cr_prog_curr_timetable
-- ----------------------------
ALTER TABLE "smisportal"."cr_prog_curr_timetable" ADD CONSTRAINT "prog_curriculum_timetable_pkey" PRIMARY KEY ("timetable_id");

-- ----------------------------
-- Primary Key structure for table cr_programme_curr_lecture_timetable
-- ----------------------------
ALTER TABLE "smisportal"."cr_programme_curr_lecture_timetable" ADD CONSTRAINT "programme_curr_lecture_timetable_pkey" PRIMARY KEY ("lecture_timetable_id");

-- ----------------------------
-- Primary Key structure for table ex_grading_system
-- ----------------------------
ALTER TABLE "smisportal"."ex_grading_system" ADD CONSTRAINT "grading_system_pkey" PRIMARY KEY ("grading_system_id");

-- ----------------------------
-- Primary Key structure for table ex_grading_system_detail
-- ----------------------------
ALTER TABLE "smisportal"."ex_grading_system_detail" ADD CONSTRAINT "grading_system_details_pkey" PRIMARY KEY ("grading_system_detail_id");

-- ----------------------------
-- Primary Key structure for table ex_marksheet
-- ----------------------------
ALTER TABLE "smisportal"."ex_marksheet" ADD CONSTRAINT "marksheet_pkey" PRIMARY KEY ("marksheet_id");

-- ----------------------------
-- Primary Key structure for table ex_mode
-- ----------------------------
ALTER TABLE "smisportal"."ex_mode" ADD CONSTRAINT "exam_mode_pkey" PRIMARY KEY ("exam_mode_id");

-- ----------------------------
-- Primary Key structure for table org_acad_session_status
-- ----------------------------
ALTER TABLE "smisportal"."org_acad_session_status" ADD CONSTRAINT "acad_session_status_pkey" PRIMARY KEY ("acad_session_status_id");

-- ----------------------------
-- Primary Key structure for table org_academic_levels
-- ----------------------------
ALTER TABLE "smisportal"."org_academic_levels" ADD CONSTRAINT "academic_levels_pkey" PRIMARY KEY ("academic_level_id");

-- ----------------------------
-- Primary Key structure for table org_academic_session
-- ----------------------------
ALTER TABLE "smisportal"."org_academic_session" ADD CONSTRAINT "academic_session_pkey" PRIMARY KEY ("acad_session_id");

-- ----------------------------
-- Primary Key structure for table org_academic_session_semester
-- ----------------------------
ALTER TABLE "smisportal"."org_academic_session_semester" ADD CONSTRAINT "academic_session_semester_pkey" PRIMARY KEY ("acad_session_semester_id");

-- ----------------------------
-- Primary Key structure for table org_cohort
-- ----------------------------
ALTER TABLE "smisportal"."org_cohort" ADD CONSTRAINT "cohort_pkey" PRIMARY KEY ("cohort_id");

-- ----------------------------
-- Primary Key structure for table org_cohort_session
-- ----------------------------
ALTER TABLE "smisportal"."org_cohort_session" ADD CONSTRAINT "cohort_session_pkey" PRIMARY KEY ("cohort_session_id");

-- ----------------------------
-- Primary Key structure for table org_country
-- ----------------------------
ALTER TABLE "smisportal"."org_country" ADD CONSTRAINT "countries_pkey" PRIMARY KEY ("country_code");

-- ----------------------------
-- Primary Key structure for table org_courses
-- ----------------------------
ALTER TABLE "smisportal"."org_courses" ADD CONSTRAINT "courses_pkey" PRIMARY KEY ("course_id");

-- ----------------------------
-- Primary Key structure for table org_kuccps_prog_map
-- ----------------------------
ALTER TABLE "smisportal"."org_kuccps_prog_map" ADD CONSTRAINT "kuccps_prog_map_pkey" PRIMARY KEY ("prog_map_id") USING INDEX TABLESPACE "tblsp_smisportal";

-- ----------------------------
-- Primary Key structure for table org_prog_category
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_category" ADD CONSTRAINT "programme_category_pkey" PRIMARY KEY ("prog_cat_id");

-- ----------------------------
-- Primary Key structure for table org_prog_curr_course
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_course" ADD CONSTRAINT "prog_curriculum_courses_pkey" PRIMARY KEY ("prog_curriculum_course_id");

-- ----------------------------
-- Primary Key structure for table org_prog_curr_option
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_option" ADD CONSTRAINT "option_pkey" PRIMARY KEY ("option_id");

-- ----------------------------
-- Primary Key structure for table org_prog_curr_option_courses
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_option_courses" ADD CONSTRAINT "option_course_pkey" PRIMARY KEY ("option_course_id");

-- ----------------------------
-- Primary Key structure for table org_prog_curr_semester
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_semester" ADD CONSTRAINT "prog_curriculum_semester_pkey" PRIMARY KEY ("prog_curriculum_semester_id");

-- ----------------------------
-- Primary Key structure for table org_prog_curr_semester_group
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_semester_group" ADD CONSTRAINT "prog_curriculum_semester_group_pkey" PRIMARY KEY ("prog_curriculum_sem_group_id");

-- ----------------------------
-- Primary Key structure for table org_prog_curr_unit
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_unit" ADD CONSTRAINT "programme_curriculum_unit_pkey" PRIMARY KEY ("prog_curriculum_unit_id");

-- ----------------------------
-- Primary Key structure for table org_prog_level
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_level" ADD CONSTRAINT "programme_level_pkey" PRIMARY KEY ("programme_level_id");

-- ----------------------------
-- Primary Key structure for table org_prog_type
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_type" ADD CONSTRAINT "programme_type_pkey" PRIMARY KEY ("prog_type_id");

-- ----------------------------
-- Primary Key structure for table org_programme_curriculum
-- ----------------------------
ALTER TABLE "smisportal"."org_programme_curriculum" ADD CONSTRAINT "programme_curriculum_pkey" PRIMARY KEY ("prog_curriculum_id");

-- ----------------------------
-- Primary Key structure for table org_programmes
-- ----------------------------
ALTER TABLE "smisportal"."org_programmes" ADD CONSTRAINT "programmes_pkey" PRIMARY KEY ("prog_id");

-- ----------------------------
-- Primary Key structure for table org_semester_code
-- ----------------------------
ALTER TABLE "smisportal"."org_semester_code" ADD CONSTRAINT "semester_codes_pkey" PRIMARY KEY ("semester_code");

-- ----------------------------
-- Primary Key structure for table org_semester_type
-- ----------------------------
ALTER TABLE "smisportal"."org_semester_type" ADD CONSTRAINT "org_semester_type_pkey" PRIMARY KEY ("sem_type_id");

-- ----------------------------
-- Primary Key structure for table org_service
-- ----------------------------
ALTER TABLE "smisportal"."org_service" ADD CONSTRAINT "org_service_pkey" PRIMARY KEY ("service_id");

-- ----------------------------
-- Primary Key structure for table org_sponsor
-- ----------------------------
ALTER TABLE "smisportal"."org_sponsor" ADD CONSTRAINT "org_sponsor_pkey" PRIMARY KEY ("sponsor_id");

-- ----------------------------
-- Primary Key structure for table org_study_centre
-- ----------------------------
ALTER TABLE "smisportal"."org_study_centre" ADD CONSTRAINT "study_centre_pkey" PRIMARY KEY ("study_centre_id");

-- ----------------------------
-- Primary Key structure for table org_study_centre_group
-- ----------------------------
ALTER TABLE "smisportal"."org_study_centre_group" ADD CONSTRAINT "study_centre_group_pkey" PRIMARY KEY ("study_centre_group_id");

-- ----------------------------
-- Primary Key structure for table org_study_group
-- ----------------------------
ALTER TABLE "smisportal"."org_study_group" ADD CONSTRAINT "study_group_pkey" PRIMARY KEY ("study_group_id");

-- ----------------------------
-- Primary Key structure for table org_unit
-- ----------------------------
ALTER TABLE "smisportal"."org_unit" ADD CONSTRAINT "org_units_pkey" PRIMARY KEY ("unit_id");

-- ----------------------------
-- Primary Key structure for table org_unit_course
-- ----------------------------
ALTER TABLE "smisportal"."org_unit_course" ADD CONSTRAINT "org_unit_courses_pkey" PRIMARY KEY ("org_unit_course_id");

-- ----------------------------
-- Primary Key structure for table org_unit_head
-- ----------------------------
ALTER TABLE "smisportal"."org_unit_head" ADD CONSTRAINT "org_unit_heads_pkey" PRIMARY KEY ("unit_head_id");

-- ----------------------------
-- Primary Key structure for table org_unit_history
-- ----------------------------
ALTER TABLE "smisportal"."org_unit_history" ADD CONSTRAINT "org_unit_history_pkey" PRIMARY KEY ("org_unit_history_id");

-- ----------------------------
-- Primary Key structure for table org_unit_types
-- ----------------------------
ALTER TABLE "smisportal"."org_unit_types" ADD CONSTRAINT "org_unit_types_pkey" PRIMARY KEY ("unit_type_id");

-- ----------------------------
-- Primary Key structure for table sm_academic_progress
-- ----------------------------
ALTER TABLE "smisportal"."sm_academic_progress" ADD CONSTRAINT "student_academic_progress_pkey" PRIMARY KEY ("academic_progress_id");

-- ----------------------------
-- Primary Key structure for table sm_academic_progress_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_academic_progress_status" ADD CONSTRAINT "progress_status_pkey" PRIMARY KEY ("progress_status_id");

-- ----------------------------
-- Primary Key structure for table sm_admissions_approval
-- ----------------------------
ALTER TABLE "smisportal"."sm_admissions_approval" ADD CONSTRAINT "admissions_approval_pk" PRIMARY KEY ("adm_approval_id");

-- ----------------------------
-- Primary Key structure for table sm_admitted_student
-- ----------------------------
ALTER TABLE "smisportal"."sm_admitted_student" ADD CONSTRAINT "admitted_student_pkey" PRIMARY KEY ("adm_refno") USING INDEX TABLESPACE "tblsp_smisportal";

-- ----------------------------
-- Primary Key structure for table sm_approver
-- ----------------------------
ALTER TABLE "smisportal"."sm_approver" ADD CONSTRAINT "sm_approver_pkey" PRIMARY KEY ("approver_id") USING INDEX TABLESPACE "tblsp_smisportal";

-- ----------------------------
-- Auto increment value for sm_id_request_status
-- ----------------------------
SELECT setval('"smisportal"."sm_id_request_status_status_id_seq"', 1, true);

-- ----------------------------
-- Primary Key structure for table sm_id_request_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_id_request_status" ADD CONSTRAINT "id_request_status_pkey" PRIMARY KEY ("status_id");

-- ----------------------------
-- Auto increment value for sm_id_request_type
-- ----------------------------
SELECT setval('"smisportal"."sm_id_request_type_request_type_id_seq"', 1, true);

-- ----------------------------
-- Primary Key structure for table sm_id_request_type
-- ----------------------------
ALTER TABLE "smisportal"."sm_id_request_type" ADD CONSTRAINT "id_request_types_pkey" PRIMARY KEY ("request_type_id");

-- ----------------------------
-- Auto increment value for sm_intake_source
-- ----------------------------
SELECT setval('"smisportal"."sm_intake_source_source_id_seq"', 5, true);

-- ----------------------------
-- Primary Key structure for table sm_intake_source
-- ----------------------------
ALTER TABLE "smisportal"."sm_intake_source" ADD CONSTRAINT "intake_source_pkey" PRIMARY KEY ("source_id");

-- ----------------------------
-- Auto increment value for sm_intakes
-- ----------------------------
SELECT setval('"smisportal"."sm_intakes_intake_code_seq"', 3, true);

-- ----------------------------
-- Primary Key structure for table sm_intakes
-- ----------------------------
ALTER TABLE "smisportal"."sm_intakes" ADD CONSTRAINT "intakes_pkey" PRIMARY KEY ("intake_code");

-- ----------------------------
-- Primary Key structure for table sm_mentor_relationship
-- ----------------------------
ALTER TABLE "smisportal"."sm_mentor_relationship" ADD CONSTRAINT "mentor_relationship_pk" PRIMARY KEY ("mentor_relationship_id");

-- ----------------------------
-- Auto increment value for sm_name_change
-- ----------------------------
SELECT setval('"smisportal"."sm_name_change_name_change_id_seq"', 13, true);

-- ----------------------------
-- Primary Key structure for table sm_name_change
-- ----------------------------
ALTER TABLE "smisportal"."sm_name_change" ADD CONSTRAINT "sm_name_change_pkey" PRIMARY KEY ("name_change_id");

-- ----------------------------
-- Primary Key structure for table sm_name_change_approval
-- ----------------------------
ALTER TABLE "smisportal"."sm_name_change_approval" ADD CONSTRAINT "sm_name_change_approval_pkey" PRIMARY KEY ("name_change_approval_id");

-- ----------------------------
-- Primary Key structure for table sm_promotion_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_promotion_status" ADD CONSTRAINT "sm_promotion_status_pkey" PRIMARY KEY ("prom_status_id");

-- ----------------------------
-- Primary Key structure for table sm_readmission
-- ----------------------------
ALTER TABLE "smisportal"."sm_readmission" ADD CONSTRAINT "sm_readmission_pkey" PRIMARY KEY ("readmission_id") USING INDEX TABLESPACE "tblsp_smisportal";

-- ----------------------------
-- Auto increment value for sm_reg_documents
-- ----------------------------
SELECT setval('"smisportal"."sm_reg_documents_document_id_seq"', 1, false);

-- ----------------------------
-- Primary Key structure for table sm_reg_documents
-- ----------------------------
ALTER TABLE "smisportal"."sm_reg_documents" ADD CONSTRAINT "registration_documents_pkey" PRIMARY KEY ("document_id");

-- ----------------------------
-- Auto increment value for sm_reg_required_document
-- ----------------------------
SELECT setval('"smisportal"."sm_reg_required_document_required_document_id_seq"', 44, true);

-- ----------------------------
-- Primary Key structure for table sm_reg_required_document
-- ----------------------------
ALTER TABLE "smisportal"."sm_reg_required_document" ADD CONSTRAINT "registration_required_documents_pkey" PRIMARY KEY ("required_document_id");

-- ----------------------------
-- Primary Key structure for table sm_reporting_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_reporting_status" ADD CONSTRAINT "sm_reporting_status_pkey" PRIMARY KEY ("rep_status_id");

-- ----------------------------
-- Auto increment value for sm_stud_submitted_document
-- ----------------------------
SELECT setval('"smisportal"."student_submitted_documents_student_document_id_seq"', 112, true);

-- ----------------------------
-- Primary Key structure for table sm_stud_submitted_document
-- ----------------------------
ALTER TABLE "smisportal"."sm_stud_submitted_document" ADD CONSTRAINT "student_submitted_documents_pkey" PRIMARY KEY ("student_document_id");

-- ----------------------------
-- Primary Key structure for table sm_student
-- ----------------------------
ALTER TABLE "smisportal"."sm_student" ADD CONSTRAINT "students_pkey" PRIMARY KEY ("student_id");

-- ----------------------------
-- Auto increment value for sm_student_category
-- ----------------------------
SELECT setval('"smisportal"."sm_student_category_std_category_id_seq"', 4, true);

-- ----------------------------
-- Primary Key structure for table sm_student_category
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_category" ADD CONSTRAINT "student_category_pkey1" PRIMARY KEY ("std_category_id");

-- ----------------------------
-- Primary Key structure for table sm_student_cohort_history
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_cohort_history" ADD CONSTRAINT "student_cohort_history_pkey" PRIMARY KEY ("stud_cohort_hist_id");

-- ----------------------------
-- Primary Key structure for table sm_student_contact_person
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_contact_person" ADD CONSTRAINT "sm_student_contact_person_pkey" PRIMARY KEY ("contact_pid");

-- ----------------------------
-- Primary Key structure for table sm_student_id
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_id" ADD CONSTRAINT "student_id_pkey" PRIMARY KEY ("student_id_serial_no");

-- ----------------------------
-- Primary Key structure for table sm_student_id_details
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_id_details" ADD CONSTRAINT "student_id_details_pkey" PRIMARY KEY ("stud_id_det_id");

-- ----------------------------
-- Auto increment value for sm_student_id_request
-- ----------------------------
SELECT setval('"smisportal"."sm_student_id_request_request_id_seq"', 8, true);

-- ----------------------------
-- Primary Key structure for table sm_student_id_request
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_id_request" ADD CONSTRAINT "student_id_requests_pkey" PRIMARY KEY ("request_id");

-- ----------------------------
-- Primary Key structure for table sm_student_id_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_id_status" ADD CONSTRAINT "student_id_status_pkey" PRIMARY KEY ("id_status_no");

-- ----------------------------
-- Primary Key structure for table sm_student_prog_curr_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_prog_curr_status" ADD CONSTRAINT "student_prog_curr_status_pkey" PRIMARY KEY ("student_prog_curr_stat_id");

-- ----------------------------
-- Primary Key structure for table sm_student_programme_curriculum
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_programme_curriculum" ADD CONSTRAINT "student_programme_curriculum_pkey" PRIMARY KEY ("student_prog_curriculum_id");

-- ----------------------------
-- Primary Key structure for table sm_student_sem_session_progress
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_sem_session_progress" ADD CONSTRAINT "student_sem_session_progress_pkey" PRIMARY KEY ("student_semester_session_id");

-- ----------------------------
-- Primary Key structure for table sm_student_semester_group
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_semester_group" ADD CONSTRAINT "student_semester_group_pkey" PRIMARY KEY ("student_semester_group_id");

-- ----------------------------
-- Primary Key structure for table sm_student_semester_session_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_semester_session_status" ADD CONSTRAINT "student_semester_session_status_pkey" PRIMARY KEY ("status_id");

-- ----------------------------
-- Primary Key structure for table sm_student_session_details
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_session_details" ADD CONSTRAINT "student_session_details_pkey" PRIMARY KEY ("student_session_details_id");

-- ----------------------------
-- Primary Key structure for table sm_student_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_status" ADD CONSTRAINT "student_status_pkey" PRIMARY KEY ("status_id");

-- ----------------------------
-- Primary Key structure for table sm_withdrawal_approval
-- ----------------------------
ALTER TABLE "smisportal"."sm_withdrawal_approval" ADD CONSTRAINT "sm_withdrawal_approval_pkey" PRIMARY KEY ("withdrawal_approval_id ", "withdrawal_request_id ", "approver_id", "approval_status");

-- ----------------------------
-- Auto increment value for sm_withdrawal_request
-- ----------------------------
SELECT setval('"smisportal"."sm_withdrawal_request_withdrawal_request_id_seq"', 12, true);

-- ----------------------------
-- Primary Key structure for table sm_withdrawal_request
-- ----------------------------
ALTER TABLE "smisportal"."sm_withdrawal_request" ADD CONSTRAINT "sm_withdrawal_requests_pkey" PRIMARY KEY ("withdrawal_request_id");

-- ----------------------------
-- Auto increment value for sm_withdrawal_type
-- ----------------------------
SELECT setval('"smisportal"."sm_withdrawal_type_withdrawal_type_id_seq"', 2, true);

-- ----------------------------
-- Primary Key structure for table sm_withdrawal_type
-- ----------------------------
ALTER TABLE "smisportal"."sm_withdrawal_type" ADD CONSTRAINT "sm_withdraw_type_pkey" PRIMARY KEY ("withdrawal_type_id");

-- ----------------------------
-- Primary Key structure for table student_sponsor
-- ----------------------------
ALTER TABLE "smisportal"."student_sponsor" ADD CONSTRAINT "student_category_pkey" PRIMARY KEY ("sponsor_id");

-- ----------------------------
-- Foreign Keys structure for table cr_course_registration
-- ----------------------------
ALTER TABLE "smisportal"."cr_course_registration" ADD CONSTRAINT "fk_course_reg_status_id" FOREIGN KEY ("course_reg_status_id") REFERENCES "smisportal"."cr_course_reg_status" ("course_reg_status_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."cr_course_registration" ADD CONSTRAINT "fk_reg_type_id" FOREIGN KEY ("course_registration_type_id") REFERENCES "smisportal"."cr_course_reg_type" ("course_reg_type_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."cr_course_registration" ADD CONSTRAINT "fk_timetable_id" FOREIGN KEY ("timetable_id") REFERENCES "smisportal"."cr_prog_curr_timetable" ("timetable_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table cr_prog_curr_timetable
-- ----------------------------
ALTER TABLE "smisportal"."cr_prog_curr_timetable" ADD CONSTRAINT "fk_exam_mode" FOREIGN KEY ("exam_mode") REFERENCES "smisportal"."ex_mode" ("exam_mode_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."cr_prog_curr_timetable" ADD CONSTRAINT "fk_programme_curr_semester_group" FOREIGN KEY ("prog_curriculum_sem_group_id") REFERENCES "smisportal"."org_prog_curr_semester_group" ("prog_curriculum_sem_group_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table cr_programme_curr_lecture_timetable
-- ----------------------------
ALTER TABLE "smisportal"."cr_programme_curr_lecture_timetable" ADD CONSTRAINT "fk_timetable_id" FOREIGN KEY ("timetable_id") REFERENCES "smisportal"."cr_prog_curr_timetable" ("timetable_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table ex_grading_system_detail
-- ----------------------------
ALTER TABLE "smisportal"."ex_grading_system_detail" ADD CONSTRAINT "grading_system_details_fk1" FOREIGN KEY ("grading_system_id") REFERENCES "smisportal"."ex_grading_system" ("grading_system_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table ex_marksheet
-- ----------------------------
ALTER TABLE "smisportal"."ex_marksheet" ADD CONSTRAINT "fk_student_course_reg_id" FOREIGN KEY ("student_course_reg_id") REFERENCES "smisportal"."cr_course_registration" ("student_course_reg_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_academic_session_semester
-- ----------------------------
ALTER TABLE "smisportal"."org_academic_session_semester" ADD CONSTRAINT "academic_session_semester_fk1" FOREIGN KEY ("acad_session_id") REFERENCES "smisportal"."org_academic_session" ("acad_session_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_academic_session_semester" ADD CONSTRAINT "academic_session_semester_fk2" FOREIGN KEY ("semester_code") REFERENCES "smisportal"."org_semester_code" ("semester_code") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_cohort_session
-- ----------------------------
ALTER TABLE "smisportal"."org_cohort_session" ADD CONSTRAINT "cohort_session_fk1" FOREIGN KEY ("cohort_id") REFERENCES "smisportal"."org_cohort" ("cohort_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_cohort_session" ADD CONSTRAINT "cohort_session_fk2" FOREIGN KEY ("prog_curriculum_semester_id") REFERENCES "smisportal"."org_prog_curr_semester" ("prog_curriculum_semester_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_courses
-- ----------------------------
ALTER TABLE "smisportal"."org_courses" ADD CONSTRAINT "fk_category_id" FOREIGN KEY ("category_id") REFERENCES "smisportal"."cr_course_category" ("category_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_prog_curr_course
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_course" ADD CONSTRAINT "prog_curriculum_courses_fk1" FOREIGN KEY ("prog_curriculum_id") REFERENCES "smisportal"."org_programme_curriculum" ("prog_curriculum_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_prog_curr_course" ADD CONSTRAINT "prog_curriculum_courses_fk2" FOREIGN KEY ("course_id") REFERENCES "smisportal"."org_courses" ("course_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_prog_curr_option_courses
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_option_courses" ADD CONSTRAINT "option_course_fk" FOREIGN KEY ("option_id") REFERENCES "smisportal"."org_prog_curr_option" ("option_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_prog_curr_semester
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_semester" ADD CONSTRAINT "prog_curriculum_semester_fk1" FOREIGN KEY ("acad_session_semester_id") REFERENCES "smisportal"."org_academic_session_semester" ("acad_session_semester_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_prog_curr_semester" ADD CONSTRAINT "prog_curriculum_semester_fk2" FOREIGN KEY ("prog_curriculum_id") REFERENCES "smisportal"."org_programme_curriculum" ("prog_curriculum_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_prog_curr_semester_group
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_semester_group" ADD CONSTRAINT "fk_programme_level_id" FOREIGN KEY ("programme_level") REFERENCES "smisportal"."org_prog_level" ("programme_level_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_prog_curr_semester_group" ADD CONSTRAINT "prog_curriculum_semester_group_fk1" FOREIGN KEY ("prog_curriculum_semester_id") REFERENCES "smisportal"."org_prog_curr_semester" ("prog_curriculum_semester_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_prog_curr_semester_group" ADD CONSTRAINT "prog_curriculum_semester_group_fk2" FOREIGN KEY ("study_centre_group_id") REFERENCES "smisportal"."org_study_centre_group" ("study_centre_group_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_prog_curr_unit
-- ----------------------------
ALTER TABLE "smisportal"."org_prog_curr_unit" ADD CONSTRAINT "programme_curriculum_unit_fk1" FOREIGN KEY ("org_unit_history_id") REFERENCES "smisportal"."org_unit_history" ("org_unit_history_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_prog_curr_unit" ADD CONSTRAINT "programme_curriculum_unit_fk2" FOREIGN KEY ("prog_curriculum_id") REFERENCES "smisportal"."org_programme_curriculum" ("prog_curriculum_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_programme_curriculum
-- ----------------------------
ALTER TABLE "smisportal"."org_programme_curriculum" ADD CONSTRAINT "programme_curriculum_fk1" FOREIGN KEY ("prog_id") REFERENCES "smisportal"."org_programmes" ("prog_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_programme_curriculum" ADD CONSTRAINT "programme_curriculum_fk2" FOREIGN KEY ("grading_system_id") REFERENCES "smisportal"."ex_grading_system" ("grading_system_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_programmes
-- ----------------------------
ALTER TABLE "smisportal"."org_programmes" ADD CONSTRAINT "programmes_fk1" FOREIGN KEY ("prog_cat_id") REFERENCES "smisportal"."org_prog_category" ("prog_cat_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_programmes" ADD CONSTRAINT "programmes_fk2" FOREIGN KEY ("prog_type_id") REFERENCES "smisportal"."org_prog_type" ("prog_type_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_study_centre_group
-- ----------------------------
ALTER TABLE "smisportal"."org_study_centre_group" ADD CONSTRAINT "study_centre_group_fk1" FOREIGN KEY ("study_centre_id") REFERENCES "smisportal"."org_study_centre" ("study_centre_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_study_centre_group" ADD CONSTRAINT "study_centre_group_fk2" FOREIGN KEY ("study_group_id") REFERENCES "smisportal"."org_study_group" ("study_group_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_unit_course
-- ----------------------------
ALTER TABLE "smisportal"."org_unit_course" ADD CONSTRAINT "org_unit_courses_fk1" FOREIGN KEY ("course_id") REFERENCES "smisportal"."org_courses" ("course_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table org_unit_history
-- ----------------------------
ALTER TABLE "smisportal"."org_unit_history" ADD CONSTRAINT "org_unit_history_fk1" FOREIGN KEY ("unit_head_id") REFERENCES "smisportal"."org_unit_head" ("unit_head_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_unit_history" ADD CONSTRAINT "org_unit_history_fk2" FOREIGN KEY ("org_type_id") REFERENCES "smisportal"."org_unit_types" ("unit_type_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."org_unit_history" ADD CONSTRAINT "org_unit_history_fk3" FOREIGN KEY ("org_unit_id") REFERENCES "smisportal"."org_unit" ("unit_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_academic_progress
-- ----------------------------
ALTER TABLE "smisportal"."sm_academic_progress" ADD CONSTRAINT "fk_academic_level_id" FOREIGN KEY ("academic_level_id") REFERENCES "smisportal"."org_academic_levels" ("academic_level_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_academic_progress" ADD CONSTRAINT "fk_academic_session_id" FOREIGN KEY ("acad_session_id") REFERENCES "smisportal"."org_academic_session" ("acad_session_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_academic_progress" ADD CONSTRAINT "fk_progress_status_id" FOREIGN KEY ("progress_status_id") REFERENCES "smisportal"."sm_academic_progress_status" ("progress_status_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_academic_progress" ADD CONSTRAINT "fk_student_programme_curriculum" FOREIGN KEY ("student_prog_curriculum_id") REFERENCES "smisportal"."sm_student_programme_curriculum" ("student_prog_curriculum_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_admitted_student
-- ----------------------------
ALTER TABLE "smisportal"."sm_admitted_student" ADD CONSTRAINT "fk_intake_code" FOREIGN KEY ("intake_code") REFERENCES "smisportal"."sm_intakes" ("intake_code") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_admitted_student" ADD CONSTRAINT "source_fk" FOREIGN KEY ("source_id") REFERENCES "smisportal"."sm_intake_source" ("source_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_name_change
-- ----------------------------
ALTER TABLE "smisportal"."sm_name_change" ADD CONSTRAINT "student_fk_id" FOREIGN KEY ("student_id") REFERENCES "smisportal"."sm_student" ("student_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_name_change_approval
-- ----------------------------
ALTER TABLE "smisportal"."sm_name_change_approval" ADD CONSTRAINT "fk_name_change_id" FOREIGN KEY ("name_change_id") REFERENCES "smisportal"."sm_name_change" ("name_change_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_reg_required_document
-- ----------------------------
ALTER TABLE "smisportal"."sm_reg_required_document" ADD CONSTRAINT "fk_category_id" FOREIGN KEY ("fk_category_id") REFERENCES "smisportal"."sm_student_category" ("std_category_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_reg_required_document" ADD CONSTRAINT "fk_document_id" FOREIGN KEY ("fk_document_id") REFERENCES "smisportal"."sm_reg_documents" ("document_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_stud_submitted_document
-- ----------------------------
ALTER TABLE "smisportal"."sm_stud_submitted_document" ADD CONSTRAINT "fk_adm_refno" FOREIGN KEY ("adm_refno") REFERENCES "smisportal"."sm_admitted_student" ("adm_refno") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_stud_submitted_document" ADD CONSTRAINT "fk_required_document_id" FOREIGN KEY ("required_document_id") REFERENCES "smisportal"."sm_reg_required_document" ("required_document_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_student
-- ----------------------------
ALTER TABLE "smisportal"."sm_student" ADD CONSTRAINT "students_fk1" FOREIGN KEY ("country_code") REFERENCES "smisportal"."org_country" ("country_code") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_student_cohort_history
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_cohort_history" ADD CONSTRAINT "cohort_fk_key" FOREIGN KEY ("cohort_id") REFERENCES "smisportal"."org_cohort" ("cohort_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_student_id
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_id" ADD CONSTRAINT "student_id_fk1" FOREIGN KEY ("student_prog_curr_id") REFERENCES "smisportal"."sm_student_programme_curriculum" ("student_prog_curriculum_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_student_id_details
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_id_details" ADD CONSTRAINT "student_id_FK3" FOREIGN KEY ("student_id_serial_no") REFERENCES "smisportal"."sm_student_id" ("student_id_serial_no") ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMENT ON CONSTRAINT "student_id_FK3" ON "smisportal"."sm_student_id_details" IS 'This column connects with student_id Table';

-- ----------------------------
-- Foreign Keys structure for table sm_student_id_request
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_id_request" ADD CONSTRAINT "fk_request_type_id" FOREIGN KEY ("request_type_id") REFERENCES "smisportal"."sm_id_request_type" ("request_type_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_student_id_request" ADD CONSTRAINT "fk_status_id" FOREIGN KEY ("status_id") REFERENCES "smisportal"."sm_id_request_status" ("status_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_student_id_request" ADD CONSTRAINT "fk_student_prog_curr_id" FOREIGN KEY ("student_prog_curr_id") REFERENCES "smisportal"."sm_student_programme_curriculum" ("student_prog_curriculum_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_student_id_status
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_id_status" ADD CONSTRAINT "student_id_fk2" FOREIGN KEY ("student_id_serial_no") REFERENCES "smisportal"."sm_student_id" ("student_id_serial_no") ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMENT ON CONSTRAINT "student_id_fk2" ON "smisportal"."sm_student_id_status" IS 'This field connects to the student_id table';

-- ----------------------------
-- Foreign Keys structure for table sm_student_programme_curriculum
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_programme_curriculum" ADD CONSTRAINT "fk_status_id" FOREIGN KEY ("status_id") REFERENCES "smisportal"."sm_student_status" ("status_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_student_programme_curriculum" ADD CONSTRAINT "fk_student_category_id" FOREIGN KEY ("student_category_id") REFERENCES "smisportal"."sm_student_category" ("std_category_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_student_programme_curriculum" ADD CONSTRAINT "fk_student_programme_curriculum_fk1" FOREIGN KEY ("student_id") REFERENCES "smisportal"."sm_student" ("student_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_student_programme_curriculum" ADD CONSTRAINT "fk_student_programme_curriculum_fk2" FOREIGN KEY ("prog_curriculum_id") REFERENCES "smisportal"."org_programme_curriculum" ("prog_curriculum_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_student_sem_session_progress
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_sem_session_progress" ADD CONSTRAINT "fk_academic_progress_id" FOREIGN KEY ("academic_progress_id") REFERENCES "smisportal"."sm_academic_progress" ("academic_progress_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_student_sem_session_progress" ADD CONSTRAINT "fk_reporting_status_id" FOREIGN KEY ("rep_status_id") REFERENCES "smisportal"."sm_student_semester_session_status" ("status_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_student_semester_group
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_semester_group" ADD CONSTRAINT "student_semester_group_fk1" FOREIGN KEY ("prog_curriculum_semester_id") REFERENCES "smisportal"."org_prog_curr_semester" ("prog_curriculum_semester_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_student_session_details
-- ----------------------------
ALTER TABLE "smisportal"."sm_student_session_details" ADD CONSTRAINT "student_session_details_fk" FOREIGN KEY ("student_semester_session_id") REFERENCES "smisportal"."sm_student_sem_session_progress" ("student_semester_session_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMENT ON CONSTRAINT "student_session_details_fk" ON "smisportal"."sm_student_session_details" IS 'This column links to the student_sem_session_progress';

-- ----------------------------
-- Foreign Keys structure for table sm_withdrawal_approval
-- ----------------------------
ALTER TABLE "smisportal"."sm_withdrawal_approval" ADD CONSTRAINT "approver_fk_id" FOREIGN KEY ("approver_id") REFERENCES "smisportal"."sm_approver" ("approver_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_withdrawal_approval" ADD CONSTRAINT "request_fk_id" FOREIGN KEY ("withdrawal_request_id ") REFERENCES "smisportal"."sm_withdrawal_request" ("withdrawal_request_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sm_withdrawal_request
-- ----------------------------
ALTER TABLE "smisportal"."sm_withdrawal_request" ADD CONSTRAINT "fk_student_id" FOREIGN KEY ("student_id") REFERENCES "smisportal"."sm_student" ("student_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "smisportal"."sm_withdrawal_request" ADD CONSTRAINT "fk_withdrawal_type_id" FOREIGN KEY ("withdrawal_type_id") REFERENCES "smisportal"."sm_withdrawal_type" ("withdrawal_type_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
