BEGIN;

ALTER TABLE account_project ADD CONSTRAINT u_ids UNIQUE (id_account, id_project);

COMMIT;