CREATE TABLE security.userRoles
(
    userId uuid NOT NULL,
    roleId uuid NOT NULL,
    CONSTRAINT fk_users_userId FOREIGN KEY (userId) REFERENCES security.users(id),
    CONSTRAINT fk_roles_roleId FOREIGN KEY (roleId) REFERENCES security.roles(id)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE security.userRoles
    OWNER to welcat;