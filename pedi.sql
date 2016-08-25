--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6beta1
-- Dumped by pg_dump version 9.6beta1

-- Started on 2016-08-25 11:58:46

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 12387)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2370 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 219 (class 1255 OID 107802)
-- Name: actualizar_estado_subproyectos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION actualizar_estado_subproyectos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
BEGIN
	UPDATE subproyectos set estado=NEW.estado where id_proyecto = NEW.id;
	RETURN NEW;
END;
$$;


ALTER FUNCTION public.actualizar_estado_subproyectos() OWNER TO postgres;

--
-- TOC entry 232 (class 1255 OID 99590)
-- Name: agregar_proyecto_a_poa(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION agregar_proyecto_a_poa() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
fecha int;
idPoa int;
BEGIN
	fecha:=(SELECT EXTRACT(YEAR FROM NEW.fecha_inicio));
	select id into idPoa from poa where fecha_ejecucion=to_date(concat(fecha,'-01-01'), 'yyyy-mm-dd');
	insert into poa_proyectos(id_poa,id_proyecto) values(idPoa,NEW.id);
	RETURN NEW;
END;
$$;


ALTER FUNCTION public.agregar_proyecto_a_poa() OWNER TO postgres;

--
-- TOC entry 234 (class 1255 OID 99537)
-- Name: crear_subproyectos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION crear_subproyectos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
anio_ini int;
anio_fin int;
anio_total int;
x int;
y int;
r subproyectos%rowtype;
id_sub int;
BEGIN
	anio_ini:= (SELECT EXTRACT(YEAR FROM NEW.fecha_inicio));
	anio_fin:= (SELECT EXTRACT(YEAR FROM NEW.fecha_fin));
	anio_total:=anio_fin-anio_ini;
	if TG_OP = 'UPDATE' then
		if anio_total = 0 then
		UPDATE subproyectos set fecha_inicio=NEW.fecha_inicio,fecha_fin=NEW.fecha_fin where id_proyecto= NEW.id;
		id_sub:=(SELECT id FROM subproyectos WHERE id_proyecto= NEW.id order by id asc limit 1);
		delete from subproyectos where id_proyecto=NEW.id and id > id_sub;
		RETURN NEW;
		end if;

			x:=-1;
			
			 FOR r IN SELECT * FROM subproyectos WHERE id_proyecto= NEW.id order by id asc LOOP
				 if x=-1 then
					 UPDATE subproyectos set fecha_inicio=NEW.fecha_inicio,fecha_fin=to_date(concat(anio_ini,'-12-31'), 'yyyy-mm-dd') where id_proyecto= NEW.id and id=r.id;
					 x:=x+1;
				 else
					 anio_ini:=anio_ini+1;
					 UPDATE subproyectos set fecha_inicio= to_date(concat(anio_ini,'-01-01'), 'yyyy-mm-dd'),fecha_fin=to_date(concat(anio_ini,'-12-31'), 'yyyy-mm-dd')  where id_proyecto= NEW.id and id=r.id;
					 x:=x+1;
				 end if;
			 END LOOP;
			 if (anio_total - x) > 0 then
				 y:=0;
				 FOR y IN 1..(anio_total - x)-1 LOOP
				    anio_ini:=anio_ini+1;
				    INSERT INTO subproyectos (id_proyecto,fecha_inicio,fecha_fin) values (NEW.id,to_date(concat(anio_ini,'-01-01'), 'yyyy-mm-dd'),to_date(concat(anio_ini,'-12-31'), 'yyyy-mm-dd'));
				END LOOP;
				anio_ini:=anio_ini+1;
				INSERT INTO subproyectos (id_proyecto,fecha_inicio,fecha_fin) values (NEW.id,to_date(concat(anio_ini,'-01-01'), 'yyyy-mm-dd'),NEW.fecha_fin);
				RETURN NEW;
			end if;
			id_sub:=(SELECT id FROM subproyectos WHERE id_proyecto= NEW.id and fecha_inicio=to_date(concat(anio_fin,'-01-01'), 'yyyy-mm-dd') order by id asc limit 1);
			UPDATE subproyectos set fecha_inicio=to_date(concat(anio_fin,'-01-01'), 'yyyy-mm-dd'),fecha_fin=NEW.fecha_fin where id_proyecto= NEW.id and id=id_sub;
			delete from subproyectos where id_proyecto=NEW.id and id > id_sub;
			RETURN NEW;
		
	end if;
	if TG_OP = 'INSERT' then
		if anio_total = 0 then
		INSERT INTO subproyectos (id_proyecto,fecha_inicio,fecha_fin) values (NEW.id,NEW.fecha_inicio,NEW.fecha_fin);
		RETURN NEW;
		end if;
		if anio_total = 1 then
			INSERT INTO subproyectos (id_proyecto,fecha_inicio,fecha_fin) values (NEW.id,NEW.fecha_inicio,to_date(concat(anio_ini,'-12-31'), 'yyyy-mm-dd'));
			INSERT INTO subproyectos (id_proyecto,fecha_inicio,fecha_fin) values (NEW.id,to_date(concat(anio_fin,'-01-01'), 'yyyy-mm-dd'),NEW.fecha_fin);
			RETURN NEW;
		else
			x:=0;
			INSERT INTO subproyectos (id_proyecto,fecha_inicio,fecha_fin) values (NEW.id,NEW.fecha_inicio,to_date(concat(anio_ini,'-12-31'), 'yyyy-mm-dd'));
			FOR x IN 1..anio_total-1 LOOP
			    anio_ini:=anio_ini+1;
			    INSERT INTO subproyectos (id_proyecto,fecha_inicio,fecha_fin) values (NEW.id,to_date(concat(anio_ini,'-01-01'), 'yyyy-mm-dd'),to_date(concat(anio_ini,'-12-31'), 'yyyy-mm-dd'));
			END LOOP;
			anio_ini:=anio_ini+1;
			INSERT INTO subproyectos (id_proyecto,fecha_inicio,fecha_fin) values (NEW.id,to_date(concat(anio_ini,'-01-01'), 'yyyy-mm-dd'),NEW.fecha_fin);
			RETURN NEW;
		end if;
	end if;
	
END;
$$;


ALTER FUNCTION public.crear_subproyectos() OWNER TO postgres;

--
-- TOC entry 233 (class 1255 OID 99612)
-- Name: estado_proyecto_desde_poa(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION estado_proyecto_desde_poa() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
r poa_proyectos%rowtype;
BEGIN
	IF NEW.estado=3 THEN
		FOR r IN SELECT * FROM poa_proyectos WHERE id_poa = NEW.id LOOP
			UPDATE proyectos set estado=4 where id=r.id_proyecto;
		END LOOP;
	END IF;
	IF NEW.estado=2 THEN
		FOR r IN SELECT * FROM poa_proyectos WHERE id_poa = NEW.id LOOP
			UPDATE proyectos set estado=3 where id=r.id_proyecto;
		END LOOP;
	END IF;
	IF NEW.estado=1 THEN
		FOR r IN SELECT * FROM poa_proyectos WHERE id_poa = NEW.id LOOP
			UPDATE proyectos set estado=2 where id=r.id_proyecto;
		END LOOP;
	END IF;
	RETURN NEW;
END;
$$;


ALTER FUNCTION public.estado_proyecto_desde_poa() OWNER TO postgres;

--
-- TOC entry 218 (class 1255 OID 99513)
-- Name: organigrama_activo(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION organigrama_activo() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
BEGIN
	IF NEW.activo=1 THEN
	UPDATE organigrama SET activo = 0  WHERE id!=NEW.id;
	END IF;
	RETURN NEW;
END;
$$;


ALTER FUNCTION public.organigrama_activo() OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 190 (class 1259 OID 17319)
-- Name: social_account; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE social_account (
    id integer NOT NULL,
    user_id integer,
    provider character varying(255) NOT NULL,
    client_id character varying(255) NOT NULL,
    data text,
    code character varying(32),
    created_at integer,
    email character varying(255),
    username character varying(255)
);


ALTER TABLE social_account OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 17317)
-- Name: account_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE account_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE account_id_seq OWNER TO postgres;

--
-- TOC entry 2371 (class 0 OID 0)
-- Dependencies: 189
-- Name: account_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE account_id_seq OWNED BY social_account.id;


--
-- TOC entry 211 (class 1259 OID 34266)
-- Name: actividades; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE actividades (
    id integer NOT NULL,
    id_subproyecto integer NOT NULL,
    descripcion character(500) NOT NULL,
    codigo_presupuestario character(10) NOT NULL,
    presupuesto numeric,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    validacion integer DEFAULT 1 NOT NULL
);


ALTER TABLE actividades OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 34264)
-- Name: actividades_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE actividades_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE actividades_id_seq OWNER TO postgres;

--
-- TOC entry 2372 (class 0 OID 0)
-- Dependencies: 210
-- Name: actividades_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE actividades_id_seq OWNED BY actividades.id;


--
-- TOC entry 195 (class 1259 OID 17410)
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer
);


ALTER TABLE auth_assignment OWNER TO postgres;

--
-- TOC entry 193 (class 1259 OID 17381)
-- Name: auth_item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE auth_item (
    name character varying(64) NOT NULL,
    type integer NOT NULL,
    description text,
    rule_name character varying(64),
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE auth_item OWNER TO postgres;

--
-- TOC entry 194 (class 1259 OID 17395)
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


ALTER TABLE auth_item_child OWNER TO postgres;

--
-- TOC entry 192 (class 1259 OID 17373)
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE auth_rule (
    name character varying(64) NOT NULL,
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE auth_rule OWNER TO postgres;

--
-- TOC entry 203 (class 1259 OID 34222)
-- Name: estrategias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE estrategias (
    id integer NOT NULL,
    id_objetivo integer NOT NULL,
    descripcion character(500) NOT NULL,
    colaboradores character(100) NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    presupuesto numeric,
    numeracion integer,
    responsable integer NOT NULL,
    validacion integer DEFAULT 1
);


ALTER TABLE estrategias OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 34220)
-- Name: estrategias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE estrategias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE estrategias_id_seq OWNER TO postgres;

--
-- TOC entry 2373 (class 0 OID 0)
-- Dependencies: 202
-- Name: estrategias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE estrategias_id_seq OWNED BY estrategias.id;


--
-- TOC entry 217 (class 1259 OID 107806)
-- Name: historial; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE historial (
    id integer NOT NULL,
    usuario integer,
    ruta character varying(30) NOT NULL,
    tabla text NOT NULL,
    fecha date DEFAULT ('now'::text)::date NOT NULL,
    id_objeto integer NOT NULL
);


ALTER TABLE historial OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 107804)
-- Name: historial_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE historial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE historial_id_seq OWNER TO postgres;

--
-- TOC entry 2374 (class 0 OID 0)
-- Dependencies: 216
-- Name: historial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE historial_id_seq OWNED BY historial.id;


--
-- TOC entry 215 (class 1259 OID 99599)
-- Name: id_sub; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE id_sub (
    id integer
);


ALTER TABLE id_sub OWNER TO postgres;

--
-- TOC entry 185 (class 1259 OID 17284)
-- Name: migration; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


ALTER TABLE migration OWNER TO postgres;

--
-- TOC entry 199 (class 1259 OID 34079)
-- Name: niveles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE niveles (
    nid integer NOT NULL,
    title text,
    rid integer,
    org_id integer NOT NULL
);


ALTER TABLE niveles OWNER TO postgres;

--
-- TOC entry 2375 (class 0 OID 0)
-- Dependencies: 199
-- Name: TABLE niveles; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE niveles IS 'Niveles para Organigrama';


--
-- TOC entry 198 (class 1259 OID 34077)
-- Name: niveles_nid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE niveles_nid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE niveles_nid_seq OWNER TO postgres;

--
-- TOC entry 2376 (class 0 OID 0)
-- Dependencies: 198
-- Name: niveles_nid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE niveles_nid_seq OWNED BY niveles.nid;


--
-- TOC entry 201 (class 1259 OID 34211)
-- Name: objetivos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE objetivos (
    id integer NOT NULL,
    descripcion character(500) NOT NULL,
    colaboradores character(100) NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    numeracion integer,
    responsable integer NOT NULL,
    validacion integer DEFAULT 1
);


ALTER TABLE objetivos OWNER TO postgres;

--
-- TOC entry 200 (class 1259 OID 34209)
-- Name: objetivos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE objetivos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE objetivos_id_seq OWNER TO postgres;

--
-- TOC entry 2377 (class 0 OID 0)
-- Dependencies: 200
-- Name: objetivos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE objetivos_id_seq OWNED BY objetivos.id;


--
-- TOC entry 197 (class 1259 OID 34067)
-- Name: organigrama; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE organigrama (
    id integer NOT NULL,
    name text,
    created date DEFAULT ('now'::text)::date NOT NULL,
    activo integer
);


ALTER TABLE organigrama OWNER TO postgres;

--
-- TOC entry 2378 (class 0 OID 0)
-- Dependencies: 197
-- Name: TABLE organigrama; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE organigrama IS 'Organigrama General Pedi';


--
-- TOC entry 196 (class 1259 OID 34065)
-- Name: organigrama_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE organigrama_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE organigrama_id_seq OWNER TO postgres;

--
-- TOC entry 2379 (class 0 OID 0)
-- Dependencies: 196
-- Name: organigrama_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE organigrama_id_seq OWNED BY organigrama.id;


--
-- TOC entry 213 (class 1259 OID 99558)
-- Name: poa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE poa (
    id integer NOT NULL,
    fecha_creacion date DEFAULT ('now'::text)::date NOT NULL,
    fecha_ejecucion date,
    estado integer DEFAULT 1,
    validacion integer DEFAULT 1 NOT NULL
);


ALTER TABLE poa OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 99556)
-- Name: poa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE poa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE poa_id_seq OWNER TO postgres;

--
-- TOC entry 2380 (class 0 OID 0)
-- Dependencies: 212
-- Name: poa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE poa_id_seq OWNED BY poa.id;


--
-- TOC entry 214 (class 1259 OID 99568)
-- Name: poa_proyectos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE poa_proyectos (
    id_poa integer NOT NULL,
    id_proyecto integer NOT NULL
);


ALTER TABLE poa_proyectos OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 17304)
-- Name: profile; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE profile (
    user_id integer NOT NULL,
    name character varying(255),
    public_email character varying(255),
    gravatar_email character varying(255),
    gravatar_id character varying(32),
    location character varying(255),
    website character varying(255),
    bio text
);


ALTER TABLE profile OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 34233)
-- Name: programas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE programas (
    id integer NOT NULL,
    id_estrategia integer NOT NULL,
    descripcion character(500) NOT NULL,
    colaboradores character(100) NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    presupuesto numeric,
    numeracion integer,
    responsable integer NOT NULL,
    validacion integer DEFAULT 1
);


ALTER TABLE programas OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 34231)
-- Name: programas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE programas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE programas_id_seq OWNER TO postgres;

--
-- TOC entry 2381 (class 0 OID 0)
-- Dependencies: 204
-- Name: programas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE programas_id_seq OWNED BY programas.id;


--
-- TOC entry 207 (class 1259 OID 34244)
-- Name: proyectos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE proyectos (
    id integer NOT NULL,
    id_programa integer NOT NULL,
    nombre text NOT NULL,
    descripcion character(500) NOT NULL,
    colaboradores character(100),
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    presupuesto numeric,
    numeracion integer,
    estado integer DEFAULT 1,
    responsable integer NOT NULL,
    validacion integer DEFAULT 1,
    CONSTRAINT estado CHECK (((estado = 1) OR (estado = 2) OR (estado = 3) OR (estado = 4)))
);


ALTER TABLE proyectos OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 34242)
-- Name: proyectos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE proyectos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyectos_id_seq OWNER TO postgres;

--
-- TOC entry 2382 (class 0 OID 0)
-- Dependencies: 206
-- Name: proyectos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE proyectos_id_seq OWNED BY proyectos.id;


--
-- TOC entry 209 (class 1259 OID 34255)
-- Name: subproyectos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE subproyectos (
    id integer NOT NULL,
    id_proyecto integer NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    numeracion integer,
    estado integer
);


ALTER TABLE subproyectos OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 34253)
-- Name: subproyectos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE subproyectos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE subproyectos_id_seq OWNER TO postgres;

--
-- TOC entry 2383 (class 0 OID 0)
-- Dependencies: 208
-- Name: subproyectos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE subproyectos_id_seq OWNED BY subproyectos.id;


--
-- TOC entry 191 (class 1259 OID 17344)
-- Name: token; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE token (
    user_id integer NOT NULL,
    code character varying(32) NOT NULL,
    created_at integer NOT NULL,
    type smallint NOT NULL
);


ALTER TABLE token OWNER TO postgres;

--
-- TOC entry 187 (class 1259 OID 17291)
-- Name: user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE "user" (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password_hash character varying(60) NOT NULL,
    auth_key character varying(32) NOT NULL,
    confirmed_at integer,
    unconfirmed_email character varying(255),
    blocked_at integer,
    registration_ip character varying(45),
    created_at integer NOT NULL,
    updated_at integer NOT NULL,
    flags integer DEFAULT 0 NOT NULL,
    status integer,
    password_reset_token character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE "user" OWNER TO postgres;

--
-- TOC entry 186 (class 1259 OID 17289)
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_id_seq OWNER TO postgres;

--
-- TOC entry 2384 (class 0 OID 0)
-- Dependencies: 186
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_id_seq OWNED BY "user".id;


--
-- TOC entry 2140 (class 2604 OID 34269)
-- Name: actividades id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actividades ALTER COLUMN id SET DEFAULT nextval('actividades_id_seq'::regclass);


--
-- TOC entry 2131 (class 2604 OID 34225)
-- Name: estrategias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estrategias ALTER COLUMN id SET DEFAULT nextval('estrategias_id_seq'::regclass);


--
-- TOC entry 2146 (class 2604 OID 107809)
-- Name: historial id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY historial ALTER COLUMN id SET DEFAULT nextval('historial_id_seq'::regclass);


--
-- TOC entry 2128 (class 2604 OID 34082)
-- Name: niveles nid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY niveles ALTER COLUMN nid SET DEFAULT nextval('niveles_nid_seq'::regclass);


--
-- TOC entry 2129 (class 2604 OID 34214)
-- Name: objetivos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY objetivos ALTER COLUMN id SET DEFAULT nextval('objetivos_id_seq'::regclass);


--
-- TOC entry 2126 (class 2604 OID 34070)
-- Name: organigrama id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY organigrama ALTER COLUMN id SET DEFAULT nextval('organigrama_id_seq'::regclass);


--
-- TOC entry 2142 (class 2604 OID 99561)
-- Name: poa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY poa ALTER COLUMN id SET DEFAULT nextval('poa_id_seq'::regclass);


--
-- TOC entry 2133 (class 2604 OID 34236)
-- Name: programas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY programas ALTER COLUMN id SET DEFAULT nextval('programas_id_seq'::regclass);


--
-- TOC entry 2135 (class 2604 OID 34247)
-- Name: proyectos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY proyectos ALTER COLUMN id SET DEFAULT nextval('proyectos_id_seq'::regclass);


--
-- TOC entry 2125 (class 2604 OID 17322)
-- Name: social_account id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY social_account ALTER COLUMN id SET DEFAULT nextval('account_id_seq'::regclass);


--
-- TOC entry 2139 (class 2604 OID 34258)
-- Name: subproyectos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY subproyectos ALTER COLUMN id SET DEFAULT nextval('subproyectos_id_seq'::regclass);


--
-- TOC entry 2122 (class 2604 OID 17294)
-- Name: user id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "user" ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- TOC entry 2385 (class 0 OID 0)
-- Dependencies: 189
-- Name: account_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('account_id_seq', 1, false);


--
-- TOC entry 2357 (class 0 OID 34266)
-- Dependencies: 211
-- Data for Name: actividades; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY actividades (id, id_subproyecto, descripcion, codigo_presupuestario, presupuesto, fecha_inicio, fecha_fin, validacion) FROM stdin;
\.


--
-- TOC entry 2386 (class 0 OID 0)
-- Dependencies: 210
-- Name: actividades_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('actividades_id_seq', 3, true);


--
-- TOC entry 2341 (class 0 OID 17410)
-- Dependencies: 195
-- Data for Name: auth_assignment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY auth_assignment (item_name, user_id, created_at) FROM stdin;
superadmin	1	1465708890
admin	1	1469326249
crear-proyecto	2	1469568589
actualizar-proyecto	2	1469568589
crear-objetivo	2	1471498413
actualizar-objetivo	2	1471498413
eliminar-objetivo	2	1471498413
eliminar-proyecto	2	1471498413
crear-programa	2	1471498413
actualizar-programa	2	1471498413
eliminar-programa	2	1471498413
crear-estrategia	2	1471498413
actualizar-estrategia	2	1471498413
eliminar-estrategia	2	1471498413
aprobar-poa	2	1471498413
crear-objetivo	1	1471498424
actualizar-objetivo	1	1471498424
eliminar-objetivo	1	1471498424
crear-proyecto	1	1471498424
actualizar-proyecto	1	1471498424
eliminar-proyecto	1	1471498424
crear-programa	1	1471498424
actualizar-programa	1	1471498424
eliminar-programa	1	1471498424
crear-estrategia	1	1471498424
actualizar-estrategia	1	1471498424
eliminar-estrategia	1	1471498424
aprobar-poa	1	1471498424
\.


--
-- TOC entry 2339 (class 0 OID 17381)
-- Dependencies: 193
-- Data for Name: auth_item; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY auth_item (name, type, description, rule_name, data, created_at, updated_at) FROM stdin;
superadmin	1	super administrador del sitio	\N	\N	1464934382	1465098305
admin	1	administra el back	\N	\N	1464934406	1465679810
crear-objetivo	2	CREA OBJETIVOS	\N	\N	1471497856	1471497856
actualizar-objetivo	2	ACTUALIZA OBJETIVOS	\N	\N	1471497893	1471497893
eliminar-objetivo	2	ELIMINAR OBJETIVOS	\N	\N	1471497947	1471497947
crear-proyecto	2	CREA PROYECTOS	\N	\N	1469568557	1471497994
actualizar-proyecto	2	ACTUALIZA PROYECTOS	\N	\N	1469568536	1471498058
eliminar-proyecto	2	ELIMINA PROYECTOS	\N	\N	1471498097	1471498097
crear-programa	2	CREA PROGRAMAS	\N	\N	1471498153	1471498153
actualizar-programa	2	ACTUALIZA PROGRAMAS	\N	\N	1471498171	1471498171
eliminar-programa	2	ELIMINA PROGRAMAS	\N	\N	1471498188	1471498188
crear-estrategia	2	CREA ESTRATEGIAS	\N	\N	1471498238	1471498238
actualizar-estrategia	2	ACTUALIZA ESTRATEGIAS	\N	\N	1471498265	1471498265
eliminar-estrategia	2	ELIMINA ESTRATEGIAS	\N	\N	1471498310	1471498310
aprobar-poa	2	APRUEBA LO PROYECTOS QUE ESTÁN EN UN ESTADO "OK" QUE SE EJECUTARAN	\N	\N	1471498376	1471498376
\.


--
-- TOC entry 2340 (class 0 OID 17395)
-- Dependencies: 194
-- Data for Name: auth_item_child; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY auth_item_child (parent, child) FROM stdin;
superadmin	admin
\.


--
-- TOC entry 2338 (class 0 OID 17373)
-- Dependencies: 192
-- Data for Name: auth_rule; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY auth_rule (name, data, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 2349 (class 0 OID 34222)
-- Dependencies: 203
-- Data for Name: estrategias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY estrategias (id, id_objetivo, descripcion, colaboradores, fecha_inicio, fecha_fin, presupuesto, numeracion, responsable, validacion) FROM stdin;
1	1	Fortalecer los mecanismos de control y evaluación del área académica y administrativa.                                                                                                                                                                                                                                                                                                                                                                                                                              	21                                                                                                  	2018-06-14	2019-11-04	1500000	3	1	1
2	1	Diseñar un nuevo campus universitario con visión de futuro                                                                                                                                                                                                                                                                                                                                                                                                                                                          	18                                                                                                  	2017-05-02	2018-10-24	\N	2	1	1
3	2	Diseñar y aplicar el sistema de admisión, nivelación para el ingreso de los estudiantes                                                                                                                                                                                                                                                                                                                                                                                                                             	24                                                                                                  	2018-10-24	2018-10-25	\N	1	1	1
4	2	Implementar un sistema de tutorías, acompañamiento y orientación a los estudiantes                                                                                                                                                                                                                                                                                                                                                                                                                                  	21                                                                                                  	2018-10-31	2020-06-24	\N	2	1	1
5	2	Diseñar y aplicar un plan de acciones que favorezcan el desarrollo del pensamiento y la lectoescritura                                                                                                                                                                                                                                                                                                                                                                                                              	27                                                                                                  	2018-10-24	2019-12-06	\N	3	1	1
6	2	Rediseñar los proyectos curriculares, las mallas y planes de estudio de las carreras que permitan alcanzar el perfil de egreso                                                                                                                                                                                                                                                                                                                                                                                      	27                                                                                                  	2018-10-24	2019-12-05	\N	4	1	1
7	2	Actualizar la evaluación a lo largo de los procesos de aprendizaje                                                                                                                                                                                                                                                                                                                                                                                                                                                  	27,9                                                                                                	2018-10-30	2019-11-29	\N	5	1	1
8	3	Redefinir líneas de investigación (básicas, formativas y de tesis de grado y postgrado) alineadas con las prioridades de desarrollo provincial, y aplicación de las mismas                                                                                                                                                                                                                                                                                                                                          	15,27                                                                                               	2017-09-26	2019-10-31	\N	1	1	1
9	1	Implementar un sistema de tutorías, acompañamiento y orientación a los estudiantes                                                                                                                                                                                                                                                                                                                                                                                                                                  	4                                                                                                   	2017-10-03	2017-10-03	\N	4	1	1
12	5	AAA                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 	3                                                                                                   	2017-09-25	2023-01-09	\N	1	1	1
10	1	Reestructurar la organización del área académica para el mejoramiento de los aspectos 2                                                                                                                                                                                                                                                                                                                                                                                                                             	8                                                                                                   	2017-10-24	2017-10-24	12	1	1	1
11	4	TEST ESTRATEGIA 2 OBJETIVO TEST 2                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   	24                                                                                                  	2017-02-01	2020-07-17	\N	1	1	1
\.


--
-- TOC entry 2387 (class 0 OID 0)
-- Dependencies: 202
-- Name: estrategias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('estrategias_id_seq', 64, true);


--
-- TOC entry 2363 (class 0 OID 107806)
-- Dependencies: 217
-- Data for Name: historial; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY historial (id, usuario, ruta, tabla, fecha, id_objeto) FROM stdin;
1	1	frontend	proyectos	2016-08-25	19
\.


--
-- TOC entry 2388 (class 0 OID 0)
-- Dependencies: 216
-- Name: historial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('historial_id_seq', 1, true);


--
-- TOC entry 2361 (class 0 OID 99599)
-- Dependencies: 215
-- Data for Name: id_sub; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY id_sub (id) FROM stdin;
7
\.


--
-- TOC entry 2331 (class 0 OID 17284)
-- Dependencies: 185
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY migration (version, apply_time) FROM stdin;
m000000_000000_base	1467653123
m140209_132017_init	1467653126
m140403_174025_create_account_table	1467653126
m140504_113157_update_tables	1467653126
m140504_130429_create_token_table	1467653126
m140830_171933_fix_ip_field	1467653126
m140830_172703_change_account_table_name	1467653126
m141222_110026_update_ip_field	1467653127
m141222_135246_alter_username_length	1467653127
m150614_103145_update_social_account_table	1467653127
m150623_212711_fix_username_notnull	1467653127
m140506_102106_rbac_init	1467653134
\.


--
-- TOC entry 2345 (class 0 OID 34079)
-- Dependencies: 199
-- Data for Name: niveles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY niveles (nid, title, rid, org_id) FROM stdin;
2	PRO-RECTOR	1	1
4	DEPARTAMENTO DE PLANEACIÓN, EVALUACIÓN Y ACREDITACIÓN	2	1
5	DEPARTAMENTO DE SISTEMAS	2	1
6	RELACIONES PÚBLICAS	2	1
3	SECRETARÍA GENERAL	2	1
7	CONSEJO ACADÉMICO	2	1
9	DIRECCIÓN ACADÉMICA	7	1
10	DIRECCIÓN ADMINISTRATIVA	7	1
11	DIRECCIÓN FINANCIERA	7	1
12	ADMISIONES Y ORIENTACIÓN UNIVERSITARIA	8	1
13	RÉGIMEN ECONÓMICO Y BECAS	8	1
14	BIENESTAR UNIVERSITARIO	8	1
15	INVESTIGACIÓN	9	1
16	POSTGRADOS Y FORMACIÓN CONTINUA	9	1
17	PLANIFICACIÓN Y COORDINACIÓN DEL CURRÍCULO	9	1
18	VINCULACIÓN CON LA COLECTIVIDAD	9	1
19	RECURSOS HUMANOS	10	1
20	NÓMINA	10	1
21	SERVICIOS	10	1
22	PLANTA FÍSICA	10	1
23	CONTABILIDAD	11	1
24	TESORERÍA	11	1
25	ADQUISICIONES	11	1
26	PRESUPUESTO	11	1
27	DIRECCIONES DE ESCUELAS	9	1
28	Nivel de prueba	1	2
29	COMEDOR	11	3
8	DIRECCIÓN DE ESTUDIANTES	1	1
1	CONSEJO DIRECTIVO	\N	1
\.


--
-- TOC entry 2389 (class 0 OID 0)
-- Dependencies: 198
-- Name: niveles_nid_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('niveles_nid_seq', 29, true);


--
-- TOC entry 2347 (class 0 OID 34211)
-- Dependencies: 201
-- Data for Name: objetivos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY objetivos (id, descripcion, colaboradores, fecha_inicio, fecha_fin, numeracion, responsable, validacion) FROM stdin;
1	FORTALECER LA ESTRUCTURA Y LOS PROCESOS ADMINISTRATIVOS Y ACADÉMICOS                                                                                                                                                                                                                                                                                                                                                                                                                                                	18                                                                                                  	2017-02-08	2022-07-08	1	1	1
2	ELEVAR EL NIVEL ACADEMICO DEL INGRESO Y EGRESO, Y LA PERMANENCIA EN LA UNIVERSIDAD                                                                                                                                                                                                                                                                                                                                                                                                                                  	3                                                                                                   	2018-01-31	2020-07-22	2	1	1
3	IMPULSAR LA INVESTIGACIÓN Y LA INNOVACIÓN                                                                                                                                                                                                                                                                                                                                                                                                                                                                           	24                                                                                                  	2017-05-16	2020-08-19	3	1	1
4	TEST OBJETIVO                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       	6                                                                                                   	2017-02-01	2020-07-24	4	1	1
5	test num                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            	24                                                                                                  	2017-09-25	2023-01-10	5	1	1
\.


--
-- TOC entry 2390 (class 0 OID 0)
-- Dependencies: 200
-- Name: objetivos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('objetivos_id_seq', 23, true);


--
-- TOC entry 2343 (class 0 OID 34067)
-- Dependencies: 197
-- Data for Name: organigrama; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY organigrama (id, name, created, activo) FROM stdin;
1	2016	2016-07-23	1
3	2018	2016-08-11	0
2	2017	2016-07-28	0
\.


--
-- TOC entry 2391 (class 0 OID 0)
-- Dependencies: 196
-- Name: organigrama_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('organigrama_id_seq', 3, true);


--
-- TOC entry 2359 (class 0 OID 99558)
-- Dependencies: 213
-- Data for Name: poa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY poa (id, fecha_creacion, fecha_ejecucion, estado, validacion) FROM stdin;
1	2016-08-19	2017-01-01	1	1
\.


--
-- TOC entry 2392 (class 0 OID 0)
-- Dependencies: 212
-- Name: poa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('poa_id_seq', 4, true);


--
-- TOC entry 2360 (class 0 OID 99568)
-- Dependencies: 214
-- Data for Name: poa_proyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY poa_proyectos (id_poa, id_proyecto) FROM stdin;
\.


--
-- TOC entry 2334 (class 0 OID 17304)
-- Dependencies: 188
-- Data for Name: profile; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY profile (user_id, name, public_email, gravatar_email, gravatar_id, location, website, bio) FROM stdin;
1	\N	\N	\N	\N	\N	\N	\N
2	\N	\N	\N	\N	\N	\N	\N
\.


--
-- TOC entry 2351 (class 0 OID 34233)
-- Dependencies: 205
-- Data for Name: programas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY programas (id, id_estrategia, descripcion, colaboradores, fecha_inicio, fecha_fin, presupuesto, numeracion, responsable, validacion) FROM stdin;
6	2	Concurso internacional para el diseño arquitectónico del futuro campus PUCESE                                                                                                                                                                                                                                                                                                                                                                                                                                       	18,16,17,15                                                                                         	2017-05-03	2018-02-01	\N	3	1	1
9	3	Implementar el curso nivelación                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     	9                                                                                                   	2018-10-24	2018-10-25	\N	2	1	1
12	4	Aplicar la plataforma virtual MOODLE para acompañamiento y orientación académica en todas las materias.                                                                                                                                                                                                                                                                                                                                                                                                             	27,5                                                                                                	2018-10-31	2019-06-11	\N	2	1	1
16	5	Elaborar un plan de edición de libros de novela y ensayo que promuevan el gusto por la lectura                                                                                                                                                                                                                                                                                                                                                                                                                      	7                                                                                                   	2018-10-24	2019-11-13	\N	2	1	1
17	8	INCORPORAR EN LA PUCESE INVESTIGADORES EXPERIMENTADOS QUE AYUDEN AL DESARROLLO DE LA CAPACIDAD DE TODOS LOS DOCENTES INVESTIGADORES                                                                                                                                                                                                                                                                                                                                                                                 	2                                                                                                   	2018-10-24	2018-11-30	1500	1	1	1
4	2	Compra de terreno para nuevo campus                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 	16,17                                                                                               	2017-05-09	2018-03-03	\N	1	1	1
7	2	Búsqueda de financiamiento                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          	16,17                                                                                               	2017-06-21	2018-05-14	\N	2	1	1
8	3	Diseñar y aplicar un sistema de ingreso, con carácter selectivo                                                                                                                                                                                                                                                                                                                                                                                                                                                     	9                                                                                                   	2018-10-24	2018-10-25	\N	1	1	1
1	1	Rediseñar y validar nuevos instrumentos de evaluación.                                                                                                                                                                                                                                                                                                                                                                                                                                                              	18,16,17                                                                                            	2018-06-20	2019-07-12	\N	1	1	1
11	4	Conformar un equipo de docentes-tutores por escuela que acompañen a los estudiantes                                                                                                                                                                                                                                                                                                                                                                                                                                 	27,9                                                                                                	2018-11-01	2019-11-20	\N	1	1	1
14	5	Mejorar la biblioteca con acceso abierto a los libros, incremento del fondo bibliográfico , acceso a bibliotecas virtuales, etc.                                                                                                                                                                                                                                                                                                                                                                                    	27                                                                                                  	2018-10-31	2019-11-29	\N	1	1	1
15	5	Conformar un grupo de docentes para diseñar y socializar iniciativas de promoción de la lectura y desarrollo del pensamiento                                                                                                                                                                                                                                                                                                                                                                                        	9                                                                                                   	2018-10-24	2019-11-28	\N	3	1	1
18	7	Redefinición y evaluación anual de líneas de investigación básica, formativa y de tesis de grado y postgrado en función del Plan de desarrollo provincial.                                                                                                                                                                                                                                                                                                                                                          	16,15,27                                                                                            	2018-11-01	2019-11-29	50000	2	1	1
21	10	Agregando programa para ver en lista                                                                                                                                                                                                                                                                                                                                                                                                                                                                                	6                                                                                                   	2017-10-24	2017-10-24	\N	1	1	1
20	6	test para nuemracion programa                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       	2,1                                                                                                 	2018-12-21	2019-08-23	\N	1	1	1
22	11	ABCDEFGHIJKLMNÑOPQRSTUVWXYZ                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         	3                                                                                                   	2017-09-26	2019-11-28	\N	1	1	1
23	12	CAMBIO                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              	24                                                                                                  	2017-09-25	2023-01-04	\N	1	1	1
10	3	Diseñar, socializar y ejecutar un plan de trabajo conjunto, con los planteles de nivel medio                                                                                                                                                                                                                                                                                                                                                                                                                        	9                                                                                                   	2018-10-24	2018-10-25	\N	3	1	1
2	1	Aplicación, seguimiento y meta-evaluación de los instrumentos de evaluación docente y elaboración de planes de mejora                                                                                                                                                                                                                                                                                                                                                                                               	17                                                                                                  	2018-06-14	2019-07-16	\N	2	1	1
3	1	Implementar el sistema de control y seguimiento microcurricular                                                                                                                                                                                                                                                                                                                                                                                                                                                     	18,16                                                                                               	2018-06-14	2019-03-01	\N	3	1	1
5	2	Diseño de los componentes de una universidad moderna ubicada en Esmeraldas. Necesidades, tipos de carreras, nº de alumnos, laboratorios, campos deportivos, etc.                                                                                                                                                                                                                                                                                                                                                    	16,17                                                                                               	2017-06-14	2018-07-05	\N	4	1	1
13	4	Realizar reuniones en cada semestre de docentes para hacer seguimiento de los estudiantes por niveles                                                                                                                                                                                                                                                                                                                                                                                                               	27                                                                                                  	2018-10-31	2018-10-31	\N	3	1	1
19	1	test programa                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       	10                                                                                                  	2018-07-20	2019-11-01	\N	4	1	1
\.


--
-- TOC entry 2393 (class 0 OID 0)
-- Dependencies: 204
-- Name: programas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('programas_id_seq', 56, true);


--
-- TOC entry 2353 (class 0 OID 34244)
-- Dependencies: 207
-- Data for Name: proyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY proyectos (id, id_programa, nombre, descripcion, colaboradores, fecha_inicio, fecha_fin, presupuesto, numeracion, estado, responsable, validacion) FROM stdin;
4	23	PROYECTO POA	PROYECTO POA ANEL                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   	3                                                                                                   	2017-10-25	2023-01-04	\N	3	2	1	1
5	23	1	1                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   	18                                                                                                  	2017-10-25	2021-01-09	\N	1	1	1	0
3	23	POR SI ACASO	POR SI ACASO                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        	3                                                                                                   	2017-10-25	2023-01-04	\N	2	2	1	1
1	18	Desarrollo de una revista de investigacion	organización de los diferentes articulos cientificos                                                                                                                                                                                                                                                                                                                                                                                                                                                                	15,27,11                                                                                            	2018-11-14	2019-11-29	50000	2	1	1	1
2	17	VARIAS DEPENDENCIAS	VARIAS DEPENDENCIAS                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 	6,5,23,7                                                                                            	2018-10-24	2018-11-30	14000	1	1	1	1
\.


--
-- TOC entry 2394 (class 0 OID 0)
-- Dependencies: 206
-- Name: proyectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('proyectos_id_seq', 19, true);


--
-- TOC entry 2336 (class 0 OID 17319)
-- Dependencies: 190
-- Data for Name: social_account; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY social_account (id, user_id, provider, client_id, data, code, created_at, email, username) FROM stdin;
\.


--
-- TOC entry 2355 (class 0 OID 34255)
-- Dependencies: 209
-- Data for Name: subproyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY subproyectos (id, id_proyecto, fecha_inicio, fecha_fin, numeracion, estado) FROM stdin;
9	1	2018-11-14	2018-12-31	\N	1
13	1	2019-01-01	2019-11-29	\N	1
2	3	2017-10-25	2017-12-31	\N	2
15	2	2018-10-24	2018-11-30	\N	1
3	3	2018-01-01	2018-12-31	\N	2
4	3	2019-01-01	2019-12-31	\N	2
5	3	2020-01-01	2020-12-31	\N	2
6	3	2021-01-01	2021-12-31	\N	2
7	3	2022-01-01	2022-12-31	\N	2
45	3	2023-01-01	2023-01-04	\N	\N
11	4	2017-10-25	2017-12-31	\N	2
31	4	2018-01-01	2018-12-31	\N	2
32	4	2019-01-01	2019-12-31	\N	2
36	4	2020-01-01	2020-12-31	\N	2
37	4	2021-01-01	2021-12-31	\N	2
38	4	2022-01-01	2022-12-31	\N	2
39	4	2023-01-01	2023-01-04	\N	2
40	5	2017-10-25	2017-12-31	\N	1
41	5	2018-01-01	2018-12-31	\N	1
42	5	2019-01-01	2019-12-31	\N	1
43	5	2020-01-01	2020-12-31	\N	1
44	5	2021-01-01	2021-01-09	\N	1
\.


--
-- TOC entry 2395 (class 0 OID 0)
-- Dependencies: 208
-- Name: subproyectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('subproyectos_id_seq', 45, true);


--
-- TOC entry 2337 (class 0 OID 17344)
-- Dependencies: 191
-- Data for Name: token; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY token (user_id, code, created_at, type) FROM stdin;
1	28SVI1odN9-SiXXPCoNXAeAFXomLd2yP	1465171341	1
1	LH3e2Tnwv9nsPzy3ub_O2K3xtomNU7V6	1464888385	0
\.


--
-- TOC entry 2333 (class 0 OID 17291)
-- Dependencies: 187
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY "user" (id, username, email, password_hash, auth_key, confirmed_at, unconfirmed_email, blocked_at, registration_ip, created_at, updated_at, flags, status, password_reset_token) FROM stdin;
1	superadmin	sfriesen@jenkins.info	$2y$12$jbfNVm9EqhSSYghCaR3swuMHFcHHb0c5uLTBVcmY8B5LpO5C2cOZu	gzFRgMGlc-n5xyVWuSD9GlC8yDfvZu3p	1468474118	\N	\N	\N	1392559490	1392559490	0	\N	RkD_Jw0_8HEedzLk7MM-ZKEFfYR7VbMr_1392559490
2	sistemas	sistemas@gmail.com	$2y$12$gLaLo7jti6sOtmFx2Y8hMOnSYZoz/PCzF1oOTNB7JCFgXpmXqaBBO	NUa4zpi6sbiA6S2MSE-98JMA06EKSazS	1469568492	\N	\N	::1	1469568492	1471369375	0	\N	\N
\.


--
-- TOC entry 2396 (class 0 OID 0)
-- Dependencies: 186
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_id_seq', 2, true);


--
-- TOC entry 2157 (class 2606 OID 17327)
-- Name: social_account account_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY social_account
    ADD CONSTRAINT account_pkey PRIMARY KEY (id);


--
-- TOC entry 2187 (class 2606 OID 34274)
-- Name: actividades actividades_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actividades
    ADD CONSTRAINT actividades_pkey PRIMARY KEY (id);


--
-- TOC entry 2169 (class 2606 OID 17414)
-- Name: auth_assignment auth_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id);


--
-- TOC entry 2167 (class 2606 OID 17399)
-- Name: auth_item_child auth_item_child_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child);


--
-- TOC entry 2164 (class 2606 OID 17388)
-- Name: auth_item auth_item_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_pkey PRIMARY KEY (name);


--
-- TOC entry 2162 (class 2606 OID 17380)
-- Name: auth_rule auth_rule_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auth_rule
    ADD CONSTRAINT auth_rule_pkey PRIMARY KEY (name);


--
-- TOC entry 2179 (class 2606 OID 34230)
-- Name: estrategias estrategias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estrategias
    ADD CONSTRAINT estrategias_pkey PRIMARY KEY (id);


--
-- TOC entry 2193 (class 2606 OID 107815)
-- Name: historial historial_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY historial
    ADD CONSTRAINT historial_pkey PRIMARY KEY (id);


--
-- TOC entry 2149 (class 2606 OID 17288)
-- Name: migration migration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- TOC entry 2173 (class 2606 OID 34087)
-- Name: niveles niveles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY niveles
    ADD CONSTRAINT niveles_pkey PRIMARY KEY (nid);


--
-- TOC entry 2175 (class 2606 OID 91324)
-- Name: objetivos objetivos_numeracion_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY objetivos
    ADD CONSTRAINT objetivos_numeracion_key UNIQUE (numeracion);


--
-- TOC entry 2177 (class 2606 OID 34219)
-- Name: objetivos objetivos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY objetivos
    ADD CONSTRAINT objetivos_pkey PRIMARY KEY (id);


--
-- TOC entry 2171 (class 2606 OID 34076)
-- Name: organigrama organigrama_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY organigrama
    ADD CONSTRAINT organigrama_pkey PRIMARY KEY (id);


--
-- TOC entry 2189 (class 2606 OID 99564)
-- Name: poa poa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY poa
    ADD CONSTRAINT poa_pkey PRIMARY KEY (id);


--
-- TOC entry 2191 (class 2606 OID 99572)
-- Name: poa_proyectos poa_proyectos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY poa_proyectos
    ADD CONSTRAINT poa_proyectos_pkey PRIMARY KEY (id_poa, id_proyecto);


--
-- TOC entry 2155 (class 2606 OID 17311)
-- Name: profile profile_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profile
    ADD CONSTRAINT profile_pkey PRIMARY KEY (user_id);


--
-- TOC entry 2181 (class 2606 OID 34241)
-- Name: programas programas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY programas
    ADD CONSTRAINT programas_pkey PRIMARY KEY (id);


--
-- TOC entry 2183 (class 2606 OID 34252)
-- Name: proyectos proyectos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY proyectos
    ADD CONSTRAINT proyectos_pkey PRIMARY KEY (id);


--
-- TOC entry 2185 (class 2606 OID 34263)
-- Name: subproyectos subproyectos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY subproyectos
    ADD CONSTRAINT subproyectos_pkey PRIMARY KEY (id);


--
-- TOC entry 2151 (class 2606 OID 17299)
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- TOC entry 2158 (class 1259 OID 17328)
-- Name: account_unique; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX account_unique ON social_account USING btree (provider, client_id);


--
-- TOC entry 2159 (class 1259 OID 17372)
-- Name: account_unique_code; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX account_unique_code ON social_account USING btree (code);


--
-- TOC entry 2165 (class 1259 OID 17394)
-- Name: idx-auth_item-type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "idx-auth_item-type" ON auth_item USING btree (type);


--
-- TOC entry 2160 (class 1259 OID 17347)
-- Name: token_unique; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX token_unique ON token USING btree (user_id, code, type);


--
-- TOC entry 2152 (class 1259 OID 17301)
-- Name: user_unique_email; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX user_unique_email ON "user" USING btree (email);


--
-- TOC entry 2153 (class 1259 OID 17371)
-- Name: user_unique_username; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX user_unique_username ON "user" USING btree (username);


--
-- TOC entry 2211 (class 2620 OID 107803)
-- Name: proyectos actualizar_estado_subproyectos; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER actualizar_estado_subproyectos AFTER UPDATE ON proyectos FOR EACH ROW EXECUTE PROCEDURE actualizar_estado_subproyectos();


--
-- TOC entry 2212 (class 2620 OID 99539)
-- Name: proyectos crear_subproyectos; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER crear_subproyectos AFTER INSERT OR UPDATE ON proyectos FOR EACH ROW EXECUTE PROCEDURE crear_subproyectos();


--
-- TOC entry 2213 (class 2620 OID 99613)
-- Name: poa estado_proyecto_desde_poa; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER estado_proyecto_desde_poa AFTER UPDATE ON poa FOR EACH ROW EXECUTE PROCEDURE estado_proyecto_desde_poa();


--
-- TOC entry 2210 (class 2620 OID 99514)
-- Name: organigrama organigrama_activo; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER organigrama_activo AFTER INSERT OR UPDATE ON organigrama FOR EACH ROW EXECUTE PROCEDURE organigrama_activo();


--
-- TOC entry 2207 (class 2606 OID 34295)
-- Name: actividades actividades_id_subproyecto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actividades
    ADD CONSTRAINT actividades_id_subproyecto_fkey FOREIGN KEY (id_subproyecto) REFERENCES subproyectos(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2200 (class 2606 OID 17415)
-- Name: auth_assignment auth_assignment_item_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2199 (class 2606 OID 17405)
-- Name: auth_item_child auth_item_child_child_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2198 (class 2606 OID 17400)
-- Name: auth_item_child auth_item_child_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2197 (class 2606 OID 17389)
-- Name: auth_item auth_item_rule_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name) REFERENCES auth_rule(name) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2203 (class 2606 OID 34275)
-- Name: estrategias estrategias_id_objetivo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estrategias
    ADD CONSTRAINT estrategias_id_objetivo_fkey FOREIGN KEY (id_objetivo) REFERENCES objetivos(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2201 (class 2606 OID 34088)
-- Name: niveles fk_levels_org; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY niveles
    ADD CONSTRAINT fk_levels_org FOREIGN KEY (org_id) REFERENCES organigrama(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2202 (class 2606 OID 34093)
-- Name: niveles fk_levels_rid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY niveles
    ADD CONSTRAINT fk_levels_rid FOREIGN KEY (rid) REFERENCES niveles(nid) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2195 (class 2606 OID 17329)
-- Name: social_account fk_user_account; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY social_account
    ADD CONSTRAINT fk_user_account FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 2194 (class 2606 OID 17312)
-- Name: profile fk_user_profile; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profile
    ADD CONSTRAINT fk_user_profile FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 2196 (class 2606 OID 17348)
-- Name: token fk_user_token; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY token
    ADD CONSTRAINT fk_user_token FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 2208 (class 2606 OID 99578)
-- Name: poa_proyectos poa_proyectos_id_poa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY poa_proyectos
    ADD CONSTRAINT poa_proyectos_id_poa_fkey FOREIGN KEY (id_poa) REFERENCES poa(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2209 (class 2606 OID 99583)
-- Name: poa_proyectos poa_proyectos_id_proyecto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY poa_proyectos
    ADD CONSTRAINT poa_proyectos_id_proyecto_fkey FOREIGN KEY (id_proyecto) REFERENCES proyectos(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2204 (class 2606 OID 34280)
-- Name: programas programas_id_estrategia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY programas
    ADD CONSTRAINT programas_id_estrategia_fkey FOREIGN KEY (id_estrategia) REFERENCES estrategias(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2205 (class 2606 OID 34285)
-- Name: proyectos proyectos_id_programa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY proyectos
    ADD CONSTRAINT proyectos_id_programa_fkey FOREIGN KEY (id_programa) REFERENCES programas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2206 (class 2606 OID 34290)
-- Name: subproyectos subproyectos_id_proyecto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY subproyectos
    ADD CONSTRAINT subproyectos_id_proyecto_fkey FOREIGN KEY (id_proyecto) REFERENCES proyectos(id) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2016-08-25 11:58:46

--
-- PostgreSQL database dump complete
--

