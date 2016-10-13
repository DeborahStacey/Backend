CREATE SCHEMA security
    AUTHORIZATION welcat;

COMMENT ON SCHEMA security
    IS 'security schema for storing WellCat users and their roles';

GRANT ALL ON SCHEMA security TO welcat;