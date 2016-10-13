CREATE OR REPLACE FUNCTION security.doesUserHaveRole(userId UUID, roleId UUID)
    RETURNS BOOLEAN AS $$
BEGIN
    IF EXISTS (
        SELECT NULL 
        FROM security.userRoles AS UR 
        WHERE UR.userId = userId 
            AND UR.roleId = roleId
    ) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END; $$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION security.doesUserHaveRole(userId UUID, roleName varchar(32))
    RETURNS BOOLEAN AS $$
BEGIN
    IF EXISTS (
        SELECT NULL 
        FROM security.userRoles AS UR
            INNER JOIN security.roles AS R ON R.id = UR.roleId
        WHERE UR.userId = userId 
            AND role.name = roleName
    ) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END; $$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION security.doesUserHaveRole(username varchar(32), roleId uuid)
    RETURNS BOOLEAN AS $$
BEGIN
    IF EXISTS (
        SELECT NULL 
        FROM security.userRoles AS UR
            INNER JOIN security.users AS U ON U.id = UR.userId
        WHERE U.username = username
            AND UR.roleId = roleId
    ) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END; $$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION security.doesUserHaveRole(username varchar(32), roleName varchar(32))
    RETURNS BOOLEAN AS $$
BEGIN
    IF EXISTS (
        SELECT NULL 
        FROM security.userRoles AS UR
            INNER JOIN security.users AS U ON U.id = UR.userId
            INNER JOIN security.roles AS R ON R.id = UR.roleId
        WHERE U.username = username
            AND role.name = roleName
    ) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END; $$
LANGUAGE plpgsql;