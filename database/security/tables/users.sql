CREATE TABLE security.users
(
    id uuid NOT NULL,
    password varchar(255) NOT NULL,
    username varchar(32) NOT NULL,
    firstName varchar(32) NOT NULL,
    lastName varchar(32) NOT NULL,
    email varchar(255) NOT NULL,
    CONSTRAINT pk_users PRIMARY KEY (id),
    CONSTRAINT uq_users_username UNIQUE (username),
    CONSTRAINT uq_users_email UNIQUE (email)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

CREATE UNIQUE INDEX ON security.users(username);

ALTER TABLE security.users
    OWNER to welcat;