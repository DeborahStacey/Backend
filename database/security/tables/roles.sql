CREATE TABLE security.roles
(
    id uuid NOT NULL,
    name varchar(32) NOT NULL,
    CONSTRAINT pk_roles PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE security.roles
    OWNER to welcat;