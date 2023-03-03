/*
 Navicat Premium Data Transfer

 Source Server         : POSTGRESS_DEV_UON
 Source Server Type    : PostgreSQL
 Source Server Version : 140005
 Source Host           : 41.89.92.189:5432
 Source Catalog        : devdb1
 Source Schema         : smis

 Target Server Type    : PostgreSQL
 Target Server Version : 140005
 File Encoding         : 65001

 Date: 03/03/2023 13:54:42
*/


-- ----------------------------
-- Sequence structure for acad_session_status_acad_session_status_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."acad_session_status_acad_session_status_id_seq";
CREATE SEQUENCE "smis"."acad_session_status_acad_session_status_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for academic_levels_academic_level_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."academic_levels_academic_level_id_seq";
CREATE SEQUENCE "smis"."academic_levels_academic_level_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 1000
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for academic_session_acad_session_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."academic_session_acad_session_id_seq";
CREATE SEQUENCE "smis"."academic_session_acad_session_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for academic_session_semester_acad_session_semester_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."academic_session_semester_acad_session_semester_id_seq";
CREATE SEQUENCE "smis"."academic_session_semester_acad_session_semester_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for admitted_student_adm_refno_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."admitted_student_adm_refno_seq";
CREATE SEQUENCE "smis"."admitted_student_adm_refno_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for cohort_cohort_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."cohort_cohort_id_seq";
CREATE SEQUENCE "smis"."cohort_cohort_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for cohort_session_cohort_session_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."cohort_session_cohort_session_id_seq";
CREATE SEQUENCE "smis"."cohort_session_cohort_session_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for course_reg_type_id_course_reg_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."course_reg_type_id_course_reg_type_id_seq";
CREATE SEQUENCE "smis"."course_reg_type_id_course_reg_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for courses_course_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."courses_course_id_seq";
CREATE SEQUENCE "smis"."courses_course_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for cr_course_category_category_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."cr_course_category_category_id_seq";
CREATE SEQUENCE "smis"."cr_course_category_category_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 6
CACHE 1;

-- ----------------------------
-- Sequence structure for exam_mode_exam_mode_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."exam_mode_exam_mode_id_seq";
CREATE SEQUENCE "smis"."exam_mode_exam_mode_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for fs_student_invoice_detail_student_invoice_detail_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."fs_student_invoice_detail_student_invoice_detail_id_seq";
CREATE SEQUENCE "smis"."fs_student_invoice_detail_student_invoice_detail_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for grading_system_details_grading_system_detail_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."grading_system_details_grading_system_detail_id_seq";
CREATE SEQUENCE "smis"."grading_system_details_grading_system_detail_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for grading_system_grading_system_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."grading_system_grading_system_id_seq";
CREATE SEQUENCE "smis"."grading_system_grading_system_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for org_building_building_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_building_building_id_seq";
CREATE SEQUENCE "smis"."org_building_building_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for org_course_prerequisite_course_prerequisite_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_course_prerequisite_course_prerequisite_id_seq";
CREATE SEQUENCE "smis"."org_course_prerequisite_course_prerequisite_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for org_course_prerequisite_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_course_prerequisite_seq";
CREATE SEQUENCE "smis"."org_course_prerequisite_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for org_kuccps_prog_map_prog_map_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_kuccps_prog_map_prog_map_id_seq";
CREATE SEQUENCE "smis"."org_kuccps_prog_map_prog_map_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for org_prog_curr_option_option_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_prog_curr_option_option_id_seq";
CREATE SEQUENCE "smis"."org_prog_curr_option_option_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for org_prog_level_programme_level_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_prog_level_programme_level_id_seq";
CREATE SEQUENCE "smis"."org_prog_level_programme_level_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 6
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for org_room_type_room_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_room_type_room_type_id_seq";
CREATE SEQUENCE "smis"."org_room_type_room_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for org_semester_type_sem_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_semester_type_sem_type_id_seq";
CREATE SEQUENCE "smis"."org_semester_type_sem_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for org_sponsor_sponsor_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_sponsor_sponsor_id_seq";
CREATE SEQUENCE "smis"."org_sponsor_sponsor_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for org_unit_courses_org_unit_course_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_unit_courses_org_unit_course_id_seq";
CREATE SEQUENCE "smis"."org_unit_courses_org_unit_course_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for org_unit_heads_unit_head_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_unit_heads_unit_head_id_seq";
CREATE SEQUENCE "smis"."org_unit_heads_unit_head_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 1000
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for org_unit_history_org_unit_history_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_unit_history_org_unit_history_id_seq";
CREATE SEQUENCE "smis"."org_unit_history_org_unit_history_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for org_unit_types_unit_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_unit_types_unit_type_id_seq";
CREATE SEQUENCE "smis"."org_unit_types_unit_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 1000
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for org_units_unit_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."org_units_unit_id_seq";
CREATE SEQUENCE "smis"."org_units_unit_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for prog_curriculum_courses_prog_curriculum_course_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."prog_curriculum_courses_prog_curriculum_course_id_seq";
CREATE SEQUENCE "smis"."prog_curriculum_courses_prog_curriculum_course_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for prog_curriculum_semester_group_prog_curriculum_sem_group_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."prog_curriculum_semester_group_prog_curriculum_sem_group_id_seq";
CREATE SEQUENCE "smis"."prog_curriculum_semester_group_prog_curriculum_sem_group_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for prog_curriculum_semester_prog_curriculum_semester_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."prog_curriculum_semester_prog_curriculum_semester_id_seq";
CREATE SEQUENCE "smis"."prog_curriculum_semester_prog_curriculum_semester_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for prog_curriculum_timetable_timetable_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."prog_curriculum_timetable_timetable_id_seq";
CREATE SEQUENCE "smis"."prog_curriculum_timetable_timetable_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for programme_category_prog_cat_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."programme_category_prog_cat_id_seq";
CREATE SEQUENCE "smis"."programme_category_prog_cat_id_seq" 
INCREMENT 5
MINVALUE  5
MAXVALUE 1000
START 5
CACHE 20;

-- ----------------------------
-- Sequence structure for programme_curr_lecture_timetable_lecture_timetable_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."programme_curr_lecture_timetable_lecture_timetable_id_seq";
CREATE SEQUENCE "smis"."programme_curr_lecture_timetable_lecture_timetable_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for programme_curriculum_prog_curriculum_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."programme_curriculum_prog_curriculum_id_seq";
CREATE SEQUENCE "smis"."programme_curriculum_prog_curriculum_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for programme_curriculum_unit_prog_curriculum_unit_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."programme_curriculum_unit_prog_curriculum_unit_id_seq";
CREATE SEQUENCE "smis"."programme_curriculum_unit_prog_curriculum_unit_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for programme_type_prog_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."programme_type_prog_type_id_seq";
CREATE SEQUENCE "smis"."programme_type_prog_type_id_seq" 
INCREMENT 10
MINVALUE  1
MAXVALUE 1000000
START 10
CACHE 20;

-- ----------------------------
-- Sequence structure for programmes_prog_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."programmes_prog_id_seq";
CREATE SEQUENCE "smis"."programmes_prog_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for registration_required_documents_required_document_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."registration_required_documents_required_document_id_seq";
CREATE SEQUENCE "smis"."registration_required_documents_required_document_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_academic_progress_status_progress_status_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_academic_progress_status_progress_status_id_seq";
CREATE SEQUENCE "smis"."sm_academic_progress_status_progress_status_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_approver_approver_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_approver_approver_id_seq";
CREATE SEQUENCE "smis"."sm_approver_approver_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_interfaculty_transfer_interfaculty_transfer_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_interfaculty_transfer_interfaculty_transfer_id_seq";
CREATE SEQUENCE "smis"."sm_interfaculty_transfer_interfaculty_transfer_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_name_change_approval_name_change_approval_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_name_change_approval_name_change_approval_id_seq";
CREATE SEQUENCE "smis"."sm_name_change_approval_name_change_approval_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_name_change_name_change_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_name_change_name_change_id_seq";
CREATE SEQUENCE "smis"."sm_name_change_name_change_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_student_cohort_history_stud_cohort_hist_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_student_cohort_history_stud_cohort_hist_id_seq";
CREATE SEQUENCE "smis"."sm_student_cohort_history_stud_cohort_hist_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_student_semester_session_status_status_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_student_semester_session_status_status_id_seq";
CREATE SEQUENCE "smis"."sm_student_semester_session_status_status_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_withdraw_type_withdrawal_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_withdraw_type_withdrawal_type_id_seq";
CREATE SEQUENCE "smis"."sm_withdraw_type_withdrawal_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sm_withdrawal_approval_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_withdrawal_approval_seq";
CREATE SEQUENCE "smis"."sm_withdrawal_approval_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for sm_withdrawal_approval_withdrawal_approval_id _seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sm_withdrawal_approval_withdrawal_approval_id _seq";
CREATE SEQUENCE "smis"."sm_withdrawal_approval_withdrawal_approval_id _seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for smis_sm_approver_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."smis_sm_approver_seq";
CREATE SEQUENCE "smis"."smis_sm_approver_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for sponsors_sponsor_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."sponsors_sponsor_id_seq";
CREATE SEQUENCE "smis"."sponsors_sponsor_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for student_academic_progress_academic_progress_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."student_academic_progress_academic_progress_id_seq";
CREATE SEQUENCE "smis"."student_academic_progress_academic_progress_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for student_course_registration_student_course_reg_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."student_course_registration_student_course_reg_id_seq";
CREATE SEQUENCE "smis"."student_course_registration_student_course_reg_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for student_programme_curriculum_student_prog_curriculum_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."student_programme_curriculum_student_prog_curriculum_id_seq";
CREATE SEQUENCE "smis"."student_programme_curriculum_student_prog_curriculum_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for student_sem_session_progress_student_semester_session_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."student_sem_session_progress_student_semester_session_id_seq";
CREATE SEQUENCE "smis"."student_sem_session_progress_student_semester_session_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for student_semester_group_student_semester_group_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."student_semester_group_student_semester_group_id_seq";
CREATE SEQUENCE "smis"."student_semester_group_student_semester_group_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for students_student_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."students_student_id_seq";
CREATE SEQUENCE "smis"."students_student_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for study_centre_group_study_centre_group_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."study_centre_group_study_centre_group_id_seq";
CREATE SEQUENCE "smis"."study_centre_group_study_centre_group_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 1000
START 1
CACHE 20;

-- ----------------------------
-- Sequence structure for study_centre_study_centre_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."study_centre_study_centre_id_seq";
CREATE SEQUENCE "smis"."study_centre_study_centre_id_seq" 
INCREMENT 5
MINVALUE  5
MAXVALUE 1000
START 5
CACHE 20;

-- ----------------------------
-- Sequence structure for study_group_study_group_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "smis"."study_group_study_group_id_seq";
CREATE SEQUENCE "smis"."study_group_study_group_id_seq" 
INCREMENT 5
MINVALUE  5
MAXVALUE 1000
START 5
CACHE 20;

-- ----------------------------
-- Table structure for cr_course_category
-- ----------------------------
DROP TABLE IF EXISTS "smis"."cr_course_category";
CREATE TABLE "smis"."cr_course_category" (
  "category_id" int4 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 6
CACHE 1
),
  "category_name" varchar COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smis"
;
COMMENT ON TABLE "smis"."cr_course_category" IS 'eg  teaching practice, lab, project, ordinary course unit';

-- ----------------------------
-- Records of cr_course_category
-- ----------------------------
INSERT INTO "smis"."cr_course_category" VALUES (1, 'teaching practice');
INSERT INTO "smis"."cr_course_category" VALUES (2, 'lab');
INSERT INTO "smis"."cr_course_category" VALUES (4, 'ordinary course');
INSERT INTO "smis"."cr_course_category" VALUES (3, 'project');

-- ----------------------------
-- Table structure for cr_course_reg_status
-- ----------------------------
DROP TABLE IF EXISTS "smis"."cr_course_reg_status";
CREATE TABLE "smis"."cr_course_reg_status" (
  "course_reg_status_id" int4 NOT NULL,
  "course_reg_status_name" varchar COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smis"
;
COMMENT ON TABLE "smis"."cr_course_reg_status" IS 'eg provisional, confirmed, transferred';

-- ----------------------------
-- Records of cr_course_reg_status
-- ----------------------------

-- ----------------------------
-- Table structure for cr_course_reg_type
-- ----------------------------
DROP TABLE IF EXISTS "smis"."cr_course_reg_type";
CREATE TABLE "smis"."cr_course_reg_type" (
  "course_reg_type_id" int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20
),
  "course_reg_type_code" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "course_reg_type_name" varchar(20) COLLATE "pg_catalog"."default",
  "course_reg_entry_type" varchar(20) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smis"
;
COMMENT ON COLUMN "smis"."cr_course_reg_type"."course_reg_type_code" IS 'FA,SUPP,RETAKE';
COMMENT ON COLUMN "smis"."cr_course_reg_type"."course_reg_type_name" IS 'FIRST ATTEMPT, SUPPLIMENTARY,RETAKE';
COMMENT ON COLUMN "smis"."cr_course_reg_type"."course_reg_entry_type" IS 'ORIGINAL,PASSMARK';
COMMENT ON TABLE "smis"."cr_course_reg_type" IS 'Course registration types';

-- ----------------------------
-- Records of cr_course_reg_type
-- ----------------------------

-- ----------------------------
-- Table structure for cr_course_registration
-- ----------------------------
DROP TABLE IF EXISTS "smis"."cr_course_registration";
CREATE TABLE "smis"."cr_course_registration" (
  "student_course_reg_id" int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1
),
  "timetable_id" int4 NOT NULL,
  "student_semester_session_id" int8 NOT NULL,
  "course_registration_type_id" int4 NOT NULL,
  "registration_date" timestamptz(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "course_reg_status_id" int4 NOT NULL,
  "source_ipaddress" varchar(100) COLLATE "pg_catalog"."default",
  "userid" varchar(30) COLLATE "pg_catalog"."default"
)
TABLESPACE "tblsp_smis"
;
COMMENT ON TABLE "smis"."cr_course_registration" IS 'Courses registered by student in a timetable';

-- ----------------------------
-- Records of cr_course_registration
-- ----------------------------

-- ----------------------------
-- Table structure for cr_prog_curr_timetable
-- ----------------------------
DROP TABLE IF EXISTS "smis"."cr_prog_curr_timetable";
CREATE TABLE "smis"."cr_prog_curr_timetable" (
  "timetable_id" int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1
),
  "prog_curriculum_course_id" int8 NOT NULL,
  "prog_curriculum_sem_group_id" int8 NOT NULL,
  "exam_date" timestamptz(6),
  "exam_venue" int4,
  "exam_mode" int4 NOT NULL
)
TABLESPACE "tblsp_smis"
;
COMMENT ON TABLE "smis"."cr_prog_curr_timetable" IS 'programme curriculum timetable';

-- ----------------------------
-- Records of cr_prog_curr_timetable
-- ----------------------------

-- ----------------------------
-- Table structure for cr_programme_curr_lecture_timetable
-- ----------------------------
DROP TABLE IF EXISTS "smis"."cr_programme_curr_lecture_timetable";
CREATE TABLE "smis"."cr_programme_curr_lecture_timetable" (
  "lecture_timetable_id" int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1
),
  "timetable_id" int8 NOT NULL,
  "lecture_room_id" int4,
  "day_id" int4,
  "start_time" timestamptz(6),
  "end_time" timestamptz(6),
  "class_code" int4
)
TABLESPACE "tblsp_smis"
;
COMMENT ON TABLE "smis"."cr_programme_curr_lecture_timetable" IS 'lecture tiemtable';

-- ----------------------------
-- Records of cr_programme_curr_lecture_timetable
-- ----------------------------

-- ----------------------------
-- Table structure for ex_grading_system
-- ----------------------------
DROP TABLE IF EXISTS "smis"."ex_grading_system";
CREATE TABLE "smis"."ex_grading_system" (
  "grading_system_id" int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20
),
  "grading_system_name" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "grading_system_desc" varchar(60) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smis"
;

-- ----------------------------
-- Records of ex_grading_system
-- ----------------------------
INSERT INTO "smis"."ex_grading_system" VALUES (1, 'MASTERS', 'MASTERS', 'ACTIVE');
INSERT INTO "smis"."ex_grading_system" VALUES (2, 'PGD', 'POSTGRADUATE DIPLOMA', 'ACTIVE');
INSERT INTO "smis"."ex_grading_system" VALUES (3, 'BACHELORS', 'BACHELORS', 'ACTIVE');
INSERT INTO "smis"."ex_grading_system" VALUES (4, 'HND', 'HIGHER NATIONAL DIPLOMAS', 'ACTIVE');
INSERT INTO "smis"."ex_grading_system" VALUES (5, 'DIPLOMA', 'ORDINARY DIPLOMA', 'ACTIVE');
INSERT INTO "smis"."ex_grading_system" VALUES (6, 'Percentage Grading', 'From 0 to 100 Percent', 'ACTIVE');
INSERT INTO "smis"."ex_grading_system" VALUES (7, 'Letter grading', 'From A Grade to F Grade', 'ACTIVE');

-- ----------------------------
-- Table structure for ex_grading_system_detail
-- ----------------------------
DROP TABLE IF EXISTS "smis"."ex_grading_system_detail";
CREATE TABLE "smis"."ex_grading_system_detail" (
  "grading_system_detail_id" int8 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 20
),
  "grading_system_id" int2 NOT NULL,
  "lower_bound" float4 NOT NULL,
  "upper_bound" float4 NOT NULL,
  "grade" varchar(2) COLLATE "pg_catalog"."default" NOT NULL,
  "grade_point" float4,
  "is_active" varchar(10) COLLATE "pg_catalog"."default" NOT NULL DEFAULT 'ACTIVE'::character varying
)
TABLESPACE "tblsp_smis"
;

-- ----------------------------
-- Records of ex_grading_system_detail
-- ----------------------------

-- ----------------------------
-- Table structure for ex_marksheet
-- ----------------------------
DROP TABLE IF EXISTS "smis"."ex_marksheet";
CREATE TABLE "smis"."ex_marksheet" (
  "marksheet_id" int4 NOT NULL,
  "student_course_reg_id" int4 NOT NULL,
  "course_work_mark" float8,
  "exam_mark" float8,
  "final_mark" int4
)
TABLESPACE "tblsp_smis"
;
COMMENT ON TABLE "smis"."ex_marksheet" IS 'Stores student marks';

-- ----------------------------
-- Records of ex_marksheet
-- ----------------------------

-- ----------------------------
-- Table structure for ex_mode
-- ----------------------------
DROP TABLE IF EXISTS "smis"."ex_mode";
CREATE TABLE "smis"."ex_mode" (
  "exam_mode_id" int4 NOT NULL GENERATED BY DEFAULT AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1
),
  "exam_mode_name" varchar(30) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smis"
;
COMMENT ON TABLE "smis"."ex_mode" IS 'exam modes( Online, Physical)';

-- ----------------------------
-- Records of ex_mode
-- ----------------------------

-- ----------------------------
-- Table structure for fs_admin_fees
-- ----------------------------
DROP TABLE IF EXISTS "smis"."fs_admin_fees";
CREATE TABLE "smis"."fs_admin_fees" (
  "admin_fee_id" int4 NOT NULL,
  "name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL,
  "description" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(15) COLLATE "pg_catalog"."default" NOT NULL
)
TABLESPACE "tblsp_smis"
;

-- ----------------------------
-- Records of fs_admin_fees
-- ----------------------------

-- ----------------------------
-- Table structure for fs_billing_frequency
-- ----------------------------
DROP TABLE IF EXISTS "smis"."fs_billing_frequency";
CREATE TABLE "smis"."fs_billing_frequency" (
  "billing_frequency_id" int4 NOT NULL,
  "name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL,
  "description" varchar(255) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Records of fs_billing_frequency
-- ----------------------------

-- ----------------------------
-- Table structure for fs_fees_structure_types
-- ----------------------------
DROP TABLE IF EXISTS "smis"."fs_fees_structure_types";
CREATE TABLE "smis"."fs_fees_structure_types" (
  "fee_structure_type_id" int4 NOT NULL,
  "name" varchar(150) COLLATE "pg_catalog"."default" NOT NULL,
  "description" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "currency" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "status" varchar(15) COLLATE "pg_catalog"."default" NOT NULL,
  "date_entered" date NOT NULL,
  "exchange_rate" float8
)
TABLESPACE "tblsp_smis"
;

-- ----------------------------
-- Records of fs_fees_structure_types
-- ----------------------------

-- ----------------------------
-- Table structure for fs_invoice_details_source
-- ----------------------------
DROP TABLE IF EXISTS "smis"."fs_invoice_details_source";
CREATE TABLE "smis"."fs_invoice_details_source" (

)
TABLESPACE "tblsp_smis"
;
