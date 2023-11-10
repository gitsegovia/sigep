CREATE OR REPLACE FUNCTION mascara_9(integer)
  RETURNS text AS
$BODY$
DECLARE
t text;
c integer;
BEGIN
c = (SELECT length($1::text));
if  c=9 then
t = '' || $1;
elsif  c=8 then
t = '0' || $1;
elsif c=7 then
t = '00' || $1;
elsif  c=6 then
t = '000' || $1;
elsif  c=5 then
t = '0000' || $1;
elsif  c=4 then
t = '00000' || $1;
elsif  c=3 then
t = '000000' || $1;
elsif  c=2 then
t = '0000000' || $1;
elsif  c=1 then
t = '00000000' || $1;
end if;

RETURN t;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION mascara_9(integer) OWNER TO sisap;