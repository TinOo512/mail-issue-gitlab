CREATE ROLE gitlab LOGIN
  ENCRYPTED PASSWORD 'gitlab'
  NOSUPERUSER INHERIT CREATEDB CREATEROLE NOREPLICATION;

ALTER ROLE gitlab
  SET DateStyle = 'ISO, DMY';


CREATE DATABASE mail_issue_gitlab
  WITH OWNER = gitlab
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'en_US.UTF-8'
       LC_CTYPE = 'en_US.UTF-8'
       CONNECTION LIMIT = -1;