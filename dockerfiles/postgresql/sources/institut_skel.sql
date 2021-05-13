
CREATE TABLE if not exists cursos (
    CodiCicle serial NOT NULL PRIMARY KEY,
    NomCurs varchar(255) NOT NULL,
    Descripcio varchar(30) NOT NULL ,
    Abreviatura varchar(50) DEFAULT NULL,
    constraint CodiCicle_positive
       check ( CodiCicle >= 8 )
);

CREATE TABLE if not exists alumnes (
    DNI serial NOT NULL PRIMARY KEY,
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
--  CONSTRAINT fk_customer FOREIGN KEY(customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
--     CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE
); -- No hi ha primary key?