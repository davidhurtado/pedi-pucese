/*==============================================================*/
/* DB name: Pedi                                                */
/*==============================================================*/

/*==============================================================*/
/* Tabla: OBJETIVOS                                             */
/*==============================================================*/
create table OBJETIVOS (
   ID SERIAL PRIMARY KEY,
   DESCRIPCION CHAR(500) NOT NULL,
   RESPONSABLES CHAR(100) NOT NULL,
   FECHA_INICIO DATE NOT NULL,
   FECHA_FIN DATE NOT NULL
);

/*==============================================================*/
/* Tabla: ESTRATEGIAS                                           */
/*==============================================================*/
create table ESTRATEGIAS (
   ID SERIAL PRIMARY KEY,
   ID_OBJETIVO INT NOT NULL,
   DESCRIPCION CHAR(500)NOT NULL,
   RESPONSABLES CHAR(100) NOT NULL,
   FECHA_INICIO DATE NOT NULL,
   FECHA_FIN DATE NOT NULL,
   EVIDENCIAS TEXT NULL,
   PRESUPUESTO NUMERIC NULL
);

/*==============================================================*/
/* Tabla: PROGRAMAS                                             */
/*==============================================================*/
create table PROGRAMAS (
   ID SERIAL PRIMARY KEY,
   ID_ESTRATEGIA INT NOT NULL,
   DESCRIPCION CHAR(500)NOT NULL,
   RESPONSABLES CHAR(100)NOT NULL,
   FECHA_INICIO DATE NOT NULL,
   FECHA_FIN DATE NOT NULL,
   PRESUPUESTO NUMERIC NULL
);

/*==============================================================*/
/* Tabla: PROYECTOS                                             */
/*==============================================================*/
create table PROYECTOS (
   ID SERIAL PRIMARY KEY,
   ID_PROGRAMA INT NOT NULL,
   NOMBRE CHAR(200)NOT NULL,
   DESCRIPCION CHAR(500)NOT NULL,
   RESPONSABLES CHAR(100)NOT NULL,
   FECHA_INICIO DATE NOT NULL,
   FECHA_FIN DATE NOT NULL,
   PRESUPUESTO NUMERIC NULL
);

/*==============================================================*/
/* Tabla: SUBPROYECTOS                                          */
/*==============================================================*/
create table SUBPROYECTOS (
   ID SERIAL PRIMARY KEY,
   ID_PROYECTO INT NOT NULL,
   NOMBRE CHAR(200)NOT NULL,
   DESCRIPCION CHAR(500)NOT NULL,
   EVIDENCIAS TEXT NULL,
   FECHA_INICIO DATE NOT NULL,
   FECHA_FIN DATE NOT NULL
);

/*==============================================================*/
/* Tabla: ACTIVIDADES                                           */
/*==============================================================*/
create table ACTIVIDADES (
   ID SERIAL PRIMARY KEY,
   ID_SUBPROYECTO INT NOT NULL,
   DESCRIPCION CHAR(500)NOT NULL,
   CODIGO_PRESUPUESTARIO  CHAR(10)NOT NULL,
   PRESUPUESTO NUMERIC NULL,
   FECHA_INICIO DATE NOT NULL,
   FECHA_FIN DATE NOT NULL
);

--------------- Mod - Organigrama ----------------
CREATE TABLE organigrama (
    id SERIAL NOT NULL primary key,
    name text,
   created date not null default CURRENT_DATE,
   activo int 
);
COMMENT ON TABLE organigrama IS 'Organigrama General Pedi';

CREATE TABLE niveles (
    nid SERIAL NOT NULL primary key,
    title text,
    rid integer,
    org_id integer NOT NULL,
   constraint fk_levels_org foreign key (org_id) REFERENCES organigrama (id)ON DELETE CASCADE ON UPDATE CASCADE,
   constraint fk_levels_rid foreign key (rid) REFERENCES niveles (nid)ON DELETE CASCADE ON UPDATE CASCADE
);

COMMENT ON TABLE niveles IS 'Niveles para Organigrama';

ALTER TABLE ESTRATEGIAS ADD FOREIGN KEY (ID_OBJETIVO) REFERENCES OBJETIVOS(ID) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE PROGRAMAS ADD FOREIGN KEY (ID_ESTRATEGIA) REFERENCES ESTRATEGIAS(ID)  ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE PROYECTOS ADD FOREIGN KEY (ID_PROGRAMA) REFERENCES PROGRAMAS(ID)  ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE SUBPROYECTOS ADD FOREIGN KEY (ID_PROYECTO) REFERENCES PROYECTOS(ID)  ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ACTIVIDADES ADD FOREIGN KEY (ID_SUBPROYECTO) REFERENCES SUBPROYECTOS(ID)  ON DELETE CASCADE ON UPDATE CASCADE;

create table numeracion_objetivo (
   id SERIAL PRIMARY KEY,
   id_objetivo INT NULL
);
insert into numeracion_objetivo(id_objetivo) values(10);
select * from numeracion_objetivo;
CREATE OR REPLACE FUNCTION actualizar_numeracion_objetivo()
RETURNS TRIGGER AS $$
DECLARE idObj int;
 r numeracion_objetivo%rowtype;
aux int;
BEGIN
	
	idObj:=(SELECT id FROM numeracion_objetivo WHERE id_objetivo = OLD.id);
	aux:=0;
 FOR r IN SELECT * FROM numeracion_objetivo WHERE id > idObj order by id asc
    LOOP
    UPDATE numeracion_objetivo SET id_objetivo =  r.id_objetivo  WHERE id=(r.id-1);
    aux:=r.id-1;
    END LOOP;
	UPDATE numeracion_objetivo SET id_objetivo =  null  WHERE id>aux;
	RETURN OLD;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_objetivo AFTER DELETE
	ON objetivos FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_objetivo();


