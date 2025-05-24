

CREATE TABLE public.urls (
	id int8 GENERATED ALWAYS AS IDENTITY NOT NULL,
	url varchar NOT NULL,
	short varchar NULL,
	date_create timestamp DEFAULT now() NOT NULL,
	CONSTRAINT urls_pk PRIMARY KEY (id)
);
CREATE UNIQUE INDEX urls_short_idx ON public.urls USING btree (short);