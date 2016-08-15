/*==============================================================*/
/* Tabla: NUmeracion objetivo                                   */
/*==============================================================*/
create table numeracion_objetivo (
   id SERIAL PRIMARY KEY,
   id_objetivo INT NULL
);


/*==============================================================*/
/* Tabla: NUmeracion estrategias                                  */
/*==============================================================*/
create table numeracion_estrategias (
   id SERIAL PRIMARY KEY,
   id_objetivo int not null,
   id_estrategia INT NULL,
   numeracion INT NULL
);

/*==============================================================*/
/* Tabla: NUmeracion programas                                   */
/*==============================================================*/
create table numeracion_programas (
   id SERIAL PRIMARY KEY,
   id_estrategia INT not NULL,
   id_programa INT NULL,
   numeracion INT NULL
);

/*==============================================================*/
/* Tabla: NUmeracion proyectos                                   */
/*==============================================================*/
create table numeracion_proyectos (
   id SERIAL PRIMARY KEY,
   id_programa INT not NULL,
   id_proyecto INT NULL,
   numeracion INT NULL
);

/*==============================================================*/
/* Tabla: NUmeracion subproyectos                                  */
/*==============================================================*/
create table numeracion_subproyectos (
   id SERIAL PRIMARY KEY,
   id_proyecto INT not NULL,
   id_subproyecto INT NULL,
   numeracion INT NULL
);

ALTER TABLE numeracion_estrategias ADD FOREIGN KEY (id_objetivo) REFERENCES OBJETIVOS(ID) ON DELETE CASCADE ON UPDATE CASCADE;

/*==============================================================================================================================*/
/* 		      				         Triggers: Objetivos                                                    */
/*==============================================================================================================================*/
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


CREATE OR REPLACE FUNCTION actualizar_numeracion_objetivo_al_ingresar()
RETURNS TRIGGER AS $$
DECLARE idObj int;
BEGIN
	
	idObj:=(SELECT id FROM numeracion_objetivo WHERE id_objetivo is null order by id asc limit 1);
	if idObj is null then
	insert into numeracion_objetivo(id_objetivo) values(NEW.id);
	else
	UPDATE numeracion_objetivo SET id_objetivo =  NEW.id  WHERE id=idObj;
	end if;
 	RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_objetivo_al_ingresar AFTER INSERT
	ON objetivos FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_objetivo_al_ingresar();


/*==============================================================================================================================*/
/* 							 Triggers: Estrategias                                                  */
/*==============================================================================================================================*/
CREATE OR REPLACE FUNCTION actualizar_numeracion_estrategias_al_eliminar()
RETURNS TRIGGER AS $$
DECLARE idEst int;
 r numeracion_estrategias%rowtype;
aux int;
BEGIN
	
idEst:=(SELECT id FROM numeracion_estrategias WHERE id_estrategia = OLD.id);
aux:=0;
 FOR r IN SELECT * FROM numeracion_estrategias WHERE id > idEst and id_objetivo=OLD.id_objetivo order by id asc
    LOOP
    UPDATE numeracion_estrategias SET id_estrategia =  r.id_estrategia WHERE id=(r.id-1) and id_objetivo=OLD.id_objetivo;
    aux:=r.id-1;
    END LOOP;	
	UPDATE numeracion_estrategias SET id_estrategia = null WHERE id>aux and id_objetivo=OLD.id_objetivo;
	
	RETURN OLD;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_estrategias_al_eliminar AFTER DELETE
	ON estrategias FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_estrategias_al_eliminar();


CREATE OR REPLACE FUNCTION actualizar_numeracion_estrategias_al_ingresar()
RETURNS TRIGGER AS $$
DECLARE idEst int;
cont int;
BEGIN
	cont:=(SELECT count(*) FROM numeracion_estrategias WHERE id_estrategia is not null and id_objetivo=NEW.id_objetivo);
	idEst:=(SELECT id FROM numeracion_estrategias WHERE id_estrategia is null and id_objetivo=NEW.id_objetivo order by id asc limit 1);
	if idEst is null then
	insert into numeracion_estrategias(id_objetivo,id_estrategia,numeracion) values(NEW.id_objetivo,NEW.id, cont+1);
	else
	UPDATE numeracion_estrategias SET id_estrategia =  NEW.id  WHERE id=idEst and id_objetivo=NEW.id_objetivo;
	end if;
 	RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_estrategias_al_ingresar AFTER INSERT
	ON estrategias FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_estrategias_al_ingresar();

/*==============================================================================================================================*/
/* 							 Triggers: programas                                                  */
/*==============================================================================================================================*/
CREATE OR REPLACE FUNCTION actualizar_numeracion_programas_al_eliminar()
RETURNS TRIGGER AS $$
DECLARE idEst int;
 r numeracion_programas%rowtype;
aux int;
BEGIN
	
idEst:=(SELECT id FROM numeracion_programas WHERE id_programa = OLD.id);
aux:=0;
 FOR r IN SELECT * FROM numeracion_programas WHERE id > idEst and id_estrategia=OLD.id_estrategia order by id asc
    LOOP
    UPDATE numeracion_programas SET id_programa =  r.id_programa WHERE id=(r.id-1) and id_estrategia=OLD.id_estrategia;
    aux:=r.id-1;
    END LOOP;	
	UPDATE numeracion_programas SET id_programa = null WHERE id>aux and id_estrategia=OLD.id_estrategia;
	
	RETURN OLD;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_programas_al_eliminar AFTER DELETE
	ON programas FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_programas_al_eliminar();


CREATE OR REPLACE FUNCTION actualizar_numeracion_programas_al_ingresar()
RETURNS TRIGGER AS $$
DECLARE idEst int;
cont int;
BEGIN
	cont:=(SELECT count(*) FROM numeracion_programas WHERE id_programa is not null and id_estrategia=NEW.id_estrategia);
	idEst:=(SELECT id FROM numeracion_programas WHERE id_programa is null and id_estrategia=NEW.id_estrategia order by id asc limit 1);
	if idEst is null then
	insert into numeracion_programas(id_estrategia,id_programa,numeracion) values(NEW.id_estrategia,NEW.id, cont+1);
	else
	UPDATE numeracion_programas SET id_programa =  NEW.id  WHERE id=idEst and id_estrategia=NEW.id_estrategia;
	end if;
 	RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_programas_al_ingresar AFTER INSERT
	ON programas FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_programas_al_ingresar();



/*==============================================================================================================================*/
/* 							 Triggers: proyectos                                                  */
/*==============================================================================================================================*/
CREATE OR REPLACE FUNCTION actualizar_numeracion_proyectos_al_eliminar()
RETURNS TRIGGER AS $$
DECLARE idEst int;
 r numeracion_proyectos%rowtype;
aux int;
BEGIN
	
idEst:=(SELECT id FROM numeracion_proyectos WHERE id_proyecto = OLD.id);
aux:=0;
 FOR r IN SELECT * FROM numeracion_proyectos WHERE id > idEst and id_programa=OLD.id_programa order by id asc
    LOOP
    UPDATE numeracion_proyectos SET id_proyecto =  r.id_proyecto WHERE id=(r.id-1) and id_programa=OLD.id_programa;
    aux:=r.id-1;
    END LOOP;	
	UPDATE numeracion_proyectos SET id_proyecto = null WHERE id>aux and id_programa=OLD.id_programa;
	
	RETURN OLD;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_proyectos_al_eliminar AFTER DELETE
	ON proyectos FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_proyectos_al_eliminar();


CREATE OR REPLACE FUNCTION actualizar_numeracion_proyectos_al_ingresar()
RETURNS TRIGGER AS $$
DECLARE idEst int;
cont int;
BEGIN
	cont:=(SELECT count(*) FROM numeracion_proyectos WHERE id_proyecto is not null and id_programa=NEW.id_programa);
	idEst:=(SELECT id FROM numeracion_proyectos WHERE id_proyecto is null and id_programa=NEW.id_programa order by id asc limit 1);
	if idEst is null then
	insert into numeracion_proyectos(id_programa,id_proyecto,numeracion) values(NEW.id_programa,NEW.id, cont+1);
	else
	UPDATE numeracion_proyectos SET id_proyecto =  NEW.id  WHERE id=idEst and id_programa=NEW.id_programa;
	end if;
 	RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_proyectos_al_ingresar AFTER INSERT
	ON proyectos FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_proyectos_al_ingresar();

/*==============================================================================================================================*/
/* 							 Triggers: subproyectos                                                  */
/*==============================================================================================================================*/
CREATE OR REPLACE FUNCTION actualizar_numeracion_subproyectos_al_eliminar()
RETURNS TRIGGER AS $$
DECLARE idEst int;
 r numeracion_subproyectos%rowtype;
aux int;
BEGIN
	
idEst:=(SELECT id FROM numeracion_subproyectos WHERE id_subproyecto = OLD.id);
aux:=0;
 FOR r IN SELECT * FROM numeracion_subproyectos WHERE id > idEst and id_subproyecto=OLD.id_subproyecto order by id asc
    LOOP
    UPDATE numeracion_subproyectos SET id_subproyecto =  r.id_subproyecto WHERE id=(r.id-1) and id_subproyecto=OLD.id_subproyecto;
    aux:=r.id-1;
    END LOOP;	
	UPDATE numeracion_subproyectos SET id_subproyecto = null WHERE id>aux and id_subproyecto=OLD.id_subproyecto;
	
	RETURN OLD;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_subproyectos_al_eliminar AFTER DELETE
	ON subproyectos FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_subproyectos_al_eliminar();


CREATE OR REPLACE FUNCTION actualizar_numeracion_subproyectos_al_ingresar()
RETURNS TRIGGER AS $$
DECLARE idEst int;
cont int;
BEGIN
	cont:=(SELECT count(*) FROM numeracion_subproyectos WHERE id_subproyecto is not null and id_subproyecto=NEW.id_subproyecto);
	idEst:=(SELECT id FROM numeracion_subproyectos WHERE id_subproyecto is null and id_subproyecto=NEW.id_subproyecto order by id asc limit 1);
	if idEst is null then
	insert into numeracion_subproyectos(id_subproyecto,id_subproyecto,numeracion) values(NEW.id_subproyecto,NEW.id, cont+1);
	else
	UPDATE numeracion_subproyectos SET id_subproyecto =  NEW.id  WHERE id=idEst and id_subproyecto=NEW.id_subproyecto;
	end if;
 	RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER actualizar_numeracion_subproyectos_al_ingresar AFTER INSERT
	ON subproyectos FOR EACH ROW
	EXECUTE PROCEDURE actualizar_numeracion_subproyectos_al_ingresar();