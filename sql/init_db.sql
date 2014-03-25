BEGIN;

CREATE TABLE account
(
  id_account serial NOT NULL,
  email text NOT NULL,
  CONSTRAINT pk_account PRIMARY KEY (id_account)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE account
  OWNER TO gitlab;
COMMENT ON TABLE account IS 'table contenant tous les utilisateurs autorise';
COMMENT ON COLUMN account.email IS 'email autorise';


CREATE TABLE project
(
  id_project serial NOT NULL,
  alias_name text NOT NULL,
  folder_name text NOT NULL,
  project_name text NOT NULL,
  id_gitlab_project int NOT NULL,
  CONSTRAINT pk_project PRIMARY KEY (id_project)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE project
  OWNER TO gitlab;
COMMENT ON TABLE project IS 'table contenant la liste des projet pris en compte par le service';
COMMENT ON COLUMN project.alias_name IS 'email alias';
COMMENT ON COLUMN project.folder_name IS 'nom du dossier dans la boite mail';
COMMENT ON COLUMN project.project_name IS 'nom du projet (pour l''utilisateur)';
COMMENT ON COLUMN project.id_gitlab_project IS 'id du projet sur gitlab';

CREATE TABLE account_project
(
  id_account int NOT NULL,
  id_project int NOT NULL,
  CONSTRAINT u_ids UNIQUE (id_account, id_project)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE account_project
  OWNER TO gitlab;
COMMENT ON TABLE account_project IS 'link un utilisateur a un projet l''autorisant ainsi a envoye des issues';

COMMIT;