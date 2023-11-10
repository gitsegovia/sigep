-- Function: null_cero(numeric)

-- DROP FUNCTION text_null_(text);

CREATE OR REPLACE FUNCTION text_null_(text)
  RETURNS text AS
$BODY$
DECLARE
t text;
BEGIN
IF $1 is null THEN
t = '';
else
t = $1;
end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION text_null_(text) OWNER TO sisap;
