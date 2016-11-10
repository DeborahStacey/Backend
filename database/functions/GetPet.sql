CREATE OR REPLACE FUNCTION get_pet(pet_id BIGINT, pet_cursor REFCURSOR)
RETURNS REFCURSOR AS $$
DECLARE
    breed_id SMALLINT;
BEGIN
    SELECT breedid
        INTO breed_id
    FROM pet
    WHERE petid = pet_id;

    -- Get pet cat data
    IF (1::SMALLINT = (SELECT animaltypeid FROM breed WHERE breedid = breed_id))
    THEN
        OPEN pet_cursor FOR
          SELECT name, breedid AS breedID, gender, dateofbirth AS dateOfBirth, weight, height, length, declawed, outdoor, fixed
          FROM pet_cat
          WHERE petid = pet_id;
        RETURN pet_cursor;
    -- Get generic pet data
    ELSE
        OPEN pet_cursor FOR
          SELECT name, breedid AS breedID, gender, dateofbirth AS dateOfBirth, weight, height, length
          FROM pet
          WHERE petid = pet_id;
        RETURN pet_cursor;
    END IF;
END
$$ LANGUAGE plpgsql;
