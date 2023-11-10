-- Function: mayus_acentos(text)

-- DROP FUNCTION mayus_acentos(text);

CREATE OR REPLACE FUNCTION mayus_acentos(text)
  RETURNS text AS
$BODY$
DECLARE
t text;
BEGIN
IF character_length($1) > 0 THEN
t = replace($1,'á','Á');
t = replace(t,'é','É');
t = replace(t,'í','Í');
t = replace(t,'ó','Ó');
t = replace(t,'ú','Ú');
t = replace(t,'à','À');
t = replace(t,'è','È');
t = replace(t,'ì','Ì');
t = replace(t,'ò','Ò');
t = replace(t,'ù','Ù');
t = replace(t,'ñ','Ñ');
else
t = $1;
end if;

RETURN upper(t);
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION mayus_acentos(text) OWNER TO sisap;