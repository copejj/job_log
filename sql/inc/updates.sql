DROP TABLE IF EXISTS public.invites;

CREATE TABLE IF NOT EXISTS public.invites
(
    invite_id serial NOT NULL,
    created_date timestamp without time zone NOT NULL DEFAULT now(),
    key text COLLATE pg_catalog."default" NOT NULL DEFAULT "substring"(md5((random())::text), 1, 40),
    user_id integer,
    first_name text COLLATE pg_catalog."default",
    last_name text COLLATE pg_catalog."default",
    email text COLLATE pg_catalog."default",
    CONSTRAINT invites_pkey PRIMARY KEY (invite_id)
) TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.invites OWNER to dribbler;
GRANT ALL ON TABLE public.invites TO dribbler;
