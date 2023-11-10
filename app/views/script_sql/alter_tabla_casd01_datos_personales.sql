AGREGAR ESTOS CAMPOS A ESTA TABLA

ALTER TABLE casd01_datos_personales ADD COLUMN cod_tenencia_vivienda int4;
ALTER TABLE casd01_datos_personales ADD COLUMN anos_residencia int4;
ALTER TABLE casd01_datos_personales ADD COLUMN monto_alquiler_hipoteca numeric(26, 2);
ALTER TABLE casd01_datos_personales ADD COLUMN cod_mision int4;
COMMENT ON COLUMN casd01_datos_personales.cod_tenencia_vivienda IS 'Tenencia de vivienda

1.- Ninguna
2.- Propia
3.- Alquilada
4.- De un familiar
5.- Al cuidado
6.- Hipotecada';
COMMENT ON COLUMN casd01_datos_personales.anos_residencia IS 'Años de residencia
';
COMMENT ON COLUMN casd01_datos_personales.monto_alquiler_hipoteca IS 'Monto del alquiler o Hipoteca
';
COMMENT ON COLUMN casd01_datos_personales.cod_mision IS 'Misiones
1.Ninguna
2. Robinsón I.
3.Robinsón II.
4. Ribas
5. Sucre.
6.Negra Hipolita.
7.Jose Gregório Hernández.
8.Barrio Adentro.
9.Mercal
10.Arbol
11.Ciência.
12.Miranda
13.Guaicapuro.
14.Piar
15.Vuelvan Cara.
16.Identidad.
17.Che Guevara.
18.Cultura.
19.Esperanza.
20.Habitad.
21.Madres del Barrio.
22.Milagro.
23.Niños y Niñas del Barrio.
24.Zamora.
';

