-- CREATE SEQUENCE cursos_id_sequence
--     start 1
--     increment 4;

CREATE TABLE if not exists cursos (
--     CodiCicle integer NOT NULL DEFAULT NEXTVAL('cursos_id_sequence') PRIMARY KEY,
    CodiCicle serial NOT NULL PRIMARY KEY,
    NomCurs varchar(255) NOT NULL,
    Descripcio varchar(30) NOT NULL ,
    Abreviatura varchar(50) DEFAULT NULL,
    constraint CodiCicle_positive
       check ( CodiCicle >= 0 )
);

CREATE TABLE if not exists alumnes (
    DNI integer NOT NULL PRIMARY KEY,
    Lletra varchar(1) NOT NULL,
    Nom varchar(20) NOT NULL,
    Cognoms varchar(30) NOT NULL,
    Mail varchar(50) NOT NULL,
    CodiCicle integer NOT NULL,
    Foto varchar(100) NOT NULL

    constraint dni_len
        check ( length(DNI::text) <= 8 ),
    constraint codi_cicle_len
        check ( length(CodiCicle::text) = 1 ),
    constraint codi_cicle FOREIGN KEY (CodiCicle) REFERENCES cursos (CodiCicle) ON DELETE CASCADE
);
-- Per fer el autoincrement en postgres s'ha de crear una sequencia, pero no tenia gaire sentit crear la sequencia,
-- iniciar els valors, assignar 3 entrades i despres alterar la taula per a que l'index sigui +4, aixi que he fet les
-- comandes per la sequencencia i els he deixat comentats.
-- https://chartio.com/resources/tutorials/how-to-define-an-auto-increment-primary-key-in-postgresql/


