/*==============================================================*/
/* DB name: Pedi                                                */
/*==============================================================*/
--ESTO ES PARA CUANDO ESTEN CREADAS LAS TABLAS DE LA ADMINISTRACION DE USUARIOS
--Y CUANDO EXISTA EL USARIO REGISTRADO
ALTER TABLE "user" ADD COLUMN status int null;

INSERT INTO "user" (id, username, email, password_hash, auth_key, confirmed_at, unconfirmed_email, blocked_at, registration_ip, created_at, updated_at, flags, status) VALUES
(1, 'superadmin', 'admin@correo.com', '$2y$12$jbfNVm9EqhSSYghCaR3swuMHFcHHb0c5uLTBVcmY8B5LpO5C2cOZu', 'gzFRgMGlc-n5xyVWuSD9GlC8yDfvZu3p', 1464889498, NULL, NULL, '127.0.0.1', 1464888385, 1465686615, 0, 0);

INSERT INTO auth_item ("name", "type", description, rule_name, "data", created_at, updated_at) VALUES
('admin', 1, 'administra el back', NULL, NULL, 1464934406, 1465679810),
('superadmin', 1, 'super administrador del sitio', NULL, NULL, 1464934382, 1465098305);

INSERT INTO auth_assignment (item_name, user_id, created_at) VALUES
('superadmin', '1', 1465708890),
('superadmin', '2', 1466440799);


INSERT INTO profile (user_id, "name", public_email, gravatar_email, gravatar_id, location, website, bio) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO token (user_id, code, created_at, "type") VALUES
(1, '28SVI1odN9-SiXXPCoNXAeAFXomLd2yP', 1465171341, 1),
(1, 'LH3e2Tnwv9nsPzy3ub_O2K3xtomNU7V6', 1464888385, 0);

INSERT INTO auth_item_child (parent, child) VALUES
('superadmin', 'admin');