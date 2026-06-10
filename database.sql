--
-- PostgreSQL database dump
-- Cleaned for Thesis Submission
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 222 (class 1259 OID 16421)
-- Name: atrakcje; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.atrakcje (
    id integer NOT NULL,
    nazwa character varying(255) NOT NULL,
    opis text,
    adres character varying(255),
    kategoria character varying(50),
    cena numeric(10,2),
    osm_id bigint,
    osm_type character varying(20)
);


ALTER TABLE public.atrakcje OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16420)
-- Name: atrakcje_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.atrakcje_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.atrakcje_id_seq OWNER TO postgres;

--
-- TOC entry 5006 (class 0 OID 0)
-- Dependencies: 221
-- Name: atrakcje_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.atrakcje_id_seq OWNED BY public.atrakcje.id;


--
-- TOC entry 232 (class 1259 OID 16501)
-- Name: komentarze_atrakcji; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.komentarze_atrakcji (
    id integer NOT NULL,
    atrakcja_id integer NOT NULL,
    uzytkownik_id integer NOT NULL,
    tresc text NOT NULL,
    data_dodania timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.komentarze_atrakcji OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 16500)
-- Name: komentarze_atrakcji_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.komentarze_atrakcji_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.komentarze_atrakcji_id_seq OWNER TO postgres;

--
-- TOC entry 5007 (class 0 OID 0)
-- Dependencies: 231
-- Name: komentarze_atrakcji_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.komentarze_atrakcji_id_seq OWNED BY public.komentarze_atrakcji.id;


--
-- TOC entry 234 (class 1259 OID 16525)
-- Name: komentarze_noclegi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.komentarze_noclegi (
    id integer NOT NULL,
    nocleg_id integer NOT NULL,
    uzytkownik_id integer NOT NULL,
    tresc text NOT NULL,
    data_dodania timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.komentarze_noclegi OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16524)
-- Name: komentarze_noclegi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.komentarze_noclegi_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.komentarze_noclegi_id_seq OWNER TO postgres;

--
-- TOC entry 5008 (class 0 OID 0)
-- Dependencies: 233
-- Name: komentarze_noclegi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.komentarze_noclegi_id_seq OWNED BY public.komentarze_noclegi.id;


--
-- TOC entry 224 (class 1259 OID 16432)
-- Name: noclegi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.noclegi (
    id integer NOT NULL,
    nazwa character varying(255) NOT NULL,
    opis text,
    adres character varying(255),
    typ character varying(50),
    cena numeric(10,2),
    osm_id bigint,
    osm_type character varying(20)
);


ALTER TABLE public.noclegi OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16431)
-- Name: noclegi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.noclegi_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.noclegi_id_seq OWNER TO postgres;

--
-- TOC entry 5009 (class 0 OID 0)
-- Dependencies: 223
-- Name: noclegi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.noclegi_id_seq OWNED BY public.noclegi.id;


--
-- TOC entry 228 (class 1259 OID 16459)
-- Name: plan_atrakcji; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.plan_atrakcji (
    id integer NOT NULL,
    plan_id integer NOT NULL,
    atrakcja_id integer NOT NULL,
    kolejnosc integer DEFAULT 1
);


ALTER TABLE public.plan_atrakcji OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16458)
-- Name: plan_atrakcji_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.plan_atrakcji_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.plan_atrakcji_id_seq OWNER TO postgres;

--
-- TOC entry 5010 (class 0 OID 0)
-- Dependencies: 227
-- Name: plan_atrakcji_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.plan_atrakcji_id_seq OWNED BY public.plan_atrakcji.id;


--
-- TOC entry 230 (class 1259 OID 16480)
-- Name: plan_noclegi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.plan_noclegi (
    id integer NOT NULL,
    plan_id integer NOT NULL,
    nocleg_id integer NOT NULL,
    kolejnosc integer DEFAULT 1
);


ALTER TABLE public.plan_noclegi OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 16479)
-- Name: plan_noclegi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.plan_noclegi_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.plan_noclegi_id_seq OWNER TO postgres;

--
-- TOC entry 5011 (class 0 OID 0)
-- Dependencies: 229
-- Name: plan_noclegi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.plan_noclegi_id_seq OWNED BY public.plan_noclegi.id;


--
-- TOC entry 226 (class 1259 OID 16443)
-- Name: plany_podrozy; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.plany_podrozy (
    id integer NOT NULL,
    uzytkownik_id integer NOT NULL,
    nazwa_planu character varying(255) NOT NULL,
    data_utworzenia timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.plany_podrozy OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 16442)
-- Name: plany_podrozy_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.plany_podrozy_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.plany_podrozy_id_seq OWNER TO postgres;

--
-- TOC entry 5012 (class 0 OID 0)
-- Dependencies: 225
-- Name: plany_podrozy_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.plany_podrozy_id_seq OWNED BY public.plany_podrozy.id;


--
-- TOC entry 220 (class 1259 OID 16406)
-- Name: uzytkownicy; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.uzytkownicy (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    haslo character varying(255) NOT NULL,
    imie character varying(50),
    nazwisko character varying(50),
    data_rejestracji timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.uzytkownicy OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16405)
-- Name: uzytkownicy_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.uzytkownicy_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.uzytkownicy_id_seq OWNER TO postgres;

--
-- TOC entry 5013 (class 0 OID 0)
-- Dependencies: 219
-- Name: uzytkownicy_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.uzytkownicy_id_seq OWNED BY public.uzytkownicy.id;


--
-- TOC entry 4792 (class 2604 OID 16424)
-- Name: atrakcje id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.atrakcje ALTER COLUMN id SET DEFAULT nextval('public.atrakcje_id_seq'::regclass);


--
-- TOC entry 4800 (class 2604 OID 16504)
-- Name: komentarze_atrakcji id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.komentarze_atrakcji ALTER COLUMN id SET DEFAULT nextval('public.komentarze_atrakcji_id_seq'::regclass);


--
-- TOC entry 4802 (class 2604 OID 16528)
-- Name: komentarze_noclegi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.komentarze_noclegi ALTER COLUMN id SET DEFAULT nextval('public.komentarze_noclegi_id_seq'::regclass);


--
-- TOC entry 4793 (class 2604 OID 16435)
-- Name: noclegi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.noclegi ALTER COLUMN id SET DEFAULT nextval('public.noclegi_id_seq'::regclass);


--
-- TOC entry 4796 (class 2604 OID 16462)
-- Name: plan_atrakcji id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plan_atrakcji ALTER COLUMN id SET DEFAULT nextval('public.plan_atrakcji_id_seq'::regclass);


--
-- TOC entry 4798 (class 2604 OID 16483)
-- Name: plan_noclegi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plan_noclegi ALTER COLUMN id SET DEFAULT nextval('public.plan_noclegi_id_seq'::regclass);


--
-- TOC entry 4794 (class 2604 OID 16446)
-- Name: plany_podrozy id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plany_podrozy ALTER COLUMN id SET DEFAULT nextval('public.plany_podrozy_id_seq'::regclass);


--
-- TOC entry 4790 (class 2604 OID 16409)
-- Name: uzytkownicy id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.uzytkownicy ALTER COLUMN id SET DEFAULT nextval('public.uzytkownicy_id_seq'::regclass);


--
-- TOC entry 4988 (class 0 OID 16421)
-- Dependencies: 222
-- Data for Name: atrakcje; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.atrakcje (id, nazwa, opis, adres, kategoria, cena, osm_id, osm_type) FROM stdin;
1	Muzeum Historii	Interesujące muzeum.	ul. Historyczna 1	muzeum	20.00	\N	\N
2	Muzeum Śląskie	\N	\N	\N	\N	\N	\N
3	Park Sląski	\N	\N	\N	\N	\N	\N
4	Park powstańców	\N	\N	\N	\N	\N	\N
5	Muzeum Historii Katowic Dział Etnologii Miasta	\N	\N	\N	\N	1260091901	node
6	Galeria Katowicka	Centrum handlowe	ul. 3 Maja 30	galeria	0.00	\N	\N
7	Teatr Śląski	Teatr im. St. Wyspiańskiego	Rynek 2	teatr	40.00	\N	\N
8	Spodek	Hala widowiskowo-sportowa	Al. Korfantego 35	hala	0.00	\N	\N
9	Galeria sztuki	\N	\N	\N	\N	\N	\N
\.


--
-- TOC entry 4998 (class 0 OID 16501)
-- Dependencies: 232
-- Data for Name: komentarze_atrakcji; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.komentarze_atrakcji (id, atrakcja_id, uzytkownik_id, tresc, data_dodania) FROM stdin;
4	5	16	Bardzo ciekawe muzeum	2025-12-08 18:38:54.15387
5	5	17	Super miejsce	2025-12-14 17:39:21.149501
\.


--
-- TOC entry 5000 (class 0 OID 16525)
-- Dependencies: 234
-- Data for Name: komentarze_noclegi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.komentarze_noclegi (id, nocleg_id, uzytkownik_id, tresc, data_dodania) FROM stdin;
5	6	16	voco super	2025-12-04 21:59:38
6	5	16	City center is ideal	2025-12-04 22:11:36
7	5	16	Nice	2025-12-10 21:39:35
\.


--
-- TOC entry 4990 (class 0 OID 16432)
-- Dependencies: 224
-- Data for Name: noclegi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.noclegi (id, nazwa, opis, adres, typ, cena, osm_id, osm_type) FROM stdin;
1	Hotel Centralny	Nowoczesny hotel w centrum.	ul. Centralna 5	hotel	150.00	\N	\N
3	Hostel Centrum	Tani nocleg	ul. Mariacka 10	hostel	50.00	\N	\N
4	Hotel Monopol	Hotel 5-gwiazdkowy	ul. Dworcowa 5	hotel	350.00	\N	\N
5	Courtyard Katowice City Center	\N	\N	\N	\N	661041832	node
6	Hotel voco Katowice	\N	\N	\N	\N	2122344634	node
7	Apartamenty Silesia	Komfortowe apartamenty	ul. Uniwersytecka 1	apartament	150.00	\N	\N
8	Motel Górniczy	Tani nocleg na obrzeżach	ul. Węglowa 3	motel	80.00	\N	\N
9	W domu	\N	\N	\N	\N	\N	\N
\.


--
-- TOC entry 4994 (class 0 OID 16459)
-- Dependencies: 228
-- Data for Name: plan_atrakcji; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_atrakcji (id, plan_id, atrakcja_id, kolejnosc) FROM stdin;
16	14	2	1
20	14	9	2
\.


--
-- TOC entry 4996 (class 0 OID 16480)
-- Dependencies: 230
-- Data for Name: plan_noclegi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plan_noclegi (id, plan_id, nocleg_id, kolejnosc) FROM stdin;
12	14	9	1
\.


--
-- TOC entry 4992 (class 0 OID 16443)
-- Dependencies: 226
-- Data for Name: plany_podrozy; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plany_podrozy (id, uzytkownik_id, nazwa_planu, data_utworzenia) FROM stdin;
13	16	Weekend w Mysłowicach	2025-11-21 10:33:35.78272
14	16	Wakacje w Katowicach	2025-11-27 12:47:38.296436
\.


--
-- TOC entry 4986 (class 0 OID 16406)
-- Dependencies: 220
-- Data for Name: uzytkownicy; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.uzytkownicy (id, email, haslo, imie, nazwisko, data_rejestracji) FROM stdin;
16	admin@example.com	$2y$10$V7StXwqxFNxUwMD2dmha/OxjwMzkGjhs8UjMwqAiFNDGPNwBO7qOa	Jan	Kowalski	2025-11-16 00:00:00
17	student@example.com	$2y$10$V7StXwqxFNxUwMD2dmha/OxjwMzkGjhs8UjMwqAiFNDGPNwBO7qOa	Adam	Nowak	2025-12-14 00:00:00
18	recenzent@example.com	$2y$10$V7StXwqxFNxUwMD2dmha/OxjwMzkGjhs8UjMwqAiFNDGPNwBO7qOa	Piotr	Recenzent	2025-12-18 00:00:00
\.


--
-- TOC entry 5014 (class 0 OID 0)
-- Dependencies: 221
-- Name: atrakcje_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.atrakcje_id_seq', 9, true);


--
-- TOC entry 5015 (class 0 OID 0)
-- Dependencies: 231
-- Name: komentarze_atrakcji_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.komentarze_atrakcji_id_seq', 5, true);


--
-- TOC entry 5016 (class 0 OID 0)
-- Dependencies: 233
-- Name: komentarze_noclegi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.komentarze_noclegi_id_seq', 7, true);


--
-- TOC entry 5017 (class 0 OID 0)
-- Dependencies: 223
-- Name: noclegi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.noclegi_id_seq', 9, true);


--
-- TOC entry 5018 (class 0 OID 0)
-- Dependencies: 227
-- Name: plan_atrakcji_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.plan_atrakcji_id_seq', 20, true);


--
-- TOC entry 5019 (class 0 OID 0)
-- Dependencies: 229
-- Name: plan_noclegi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.plan_noclegi_id_seq', 12, true);


--
-- TOC entry 5020 (class 0 OID 0)
-- Dependencies: 225
-- Name: plany_podrozy_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.plany_podrozy_id_seq', 17, true);


--
-- TOC entry 5021 (class 0 OID 0)
-- Dependencies: 219
-- Name: uzytkownicy_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.uzytkownicy_id_seq', 18, true);


--
-- TOC entry 4809 (class 2606 OID 16430)
-- Name: atrakcje atrakcje_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.atrakcje
    ADD CONSTRAINT atrakcje_pkey PRIMARY KEY (id);


--
-- TOC entry 4826 (class 2606 OID 16513)
-- Name: komentarze_atrakcji komentarze_atrakcji_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.komentarze_atrakcji
    ADD CONSTRAINT komentarze_atrakcji_pkey PRIMARY KEY (id);


--
-- TOC entry 4828 (class 2606 OID 16537)
-- Name: komentarze_noclegi komentarze_noclegi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.komentarze_noclegi
    ADD CONSTRAINT komentarze_noclegi_pkey PRIMARY KEY (id);


--
-- TOC entry 4815 (class 2606 OID 16441)
-- Name: noclegi noclegi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.noclegi
    ADD CONSTRAINT noclegi_pkey PRIMARY KEY (id);


--
-- TOC entry 4822 (class 2606 OID 16468)
-- Name: plan_atrakcji plan_atrakcji_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plan_atrakcji
    ADD CONSTRAINT plan_atrakcji_pkey PRIMARY KEY (id);


--
-- TOC entry 4824 (class 2606 OID 16489)
-- Name: plan_noclegi plan_noclegi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plan_noclegi
    ADD CONSTRAINT plan_noclegi_pkey PRIMARY KEY (id);


--
-- TOC entry 4820 (class 2606 OID 16452)
-- Name: plany_podrozy plany_podrozy_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plany_podrozy
    ADD CONSTRAINT plany_podrozy_pkey PRIMARY KEY (id);


--
-- TOC entry 4812 (class 2606 OID 16561)
-- Name: atrakcje unique_osm_atrakcja; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.atrakcje
    ADD CONSTRAINT unique_osm_atrakcja UNIQUE (osm_id, osm_type);


--
-- TOC entry 4817 (class 2606 OID 16552)
-- Name: noclegi unique_osm_nocleg; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.noclegi
    ADD CONSTRAINT unique_osm_nocleg UNIQUE (osm_id, osm_type);


--
-- TOC entry 4805 (class 2606 OID 16419)
-- Name: uzytkownicy uzytkownicy_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.uzytkownicy
    ADD CONSTRAINT uzytkownicy_email_key UNIQUE (email);


--
-- TOC entry 4807 (class 2606 OID 16417)
-- Name: uzytkownicy uzytkownicy_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.uzytkownicy
    ADD CONSTRAINT uzytkownicy_pkey PRIMARY KEY (id);


--
-- TOC entry 4810 (class 1259 OID 16548)
-- Name: idx_atrakcje_kategoria; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_atrakcje_kategoria ON public.atrakcje USING btree (kategoria);


--
-- TOC entry 4813 (class 1259 OID 16549)
-- Name: idx_noclegi_typ; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_noclegi_typ ON public.noclegi USING btree (typ);


--
-- TOC entry 4818 (class 1259 OID 16550)
-- Name: idx_plany_uzytkownik; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_plany_uzytkownik ON public.plany_podrozy USING btree (uzytkownik_id);


--
-- TOC entry 4834 (class 2606 OID 16514)
-- Name: komentarze_atrakcji komentarze_atrakcji_atrakcja_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.komentarze_atrakcji
    ADD CONSTRAINT komentarze_atrakcji_atrakcja_id_fkey FOREIGN KEY (atrakcja_id) REFERENCES public.atrakcje(id) ON DELETE CASCADE;


--
-- TOC entry 4835 (class 2606 OID 16519)
-- Name: komentarze_atrakcji komentarze_atrakcji_uzytkownik_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.komentarze_atrakcji
    ADD CONSTRAINT komentarze_atrakcji_uzytkownik_id_fkey FOREIGN KEY (uzytkownik_id) REFERENCES public.uzytkownicy(id) ON DELETE CASCADE;


--
-- TOC entry 4836 (class 2606 OID 16538)
-- Name: komentarze_noclegi komentarze_noclegi_nocleg_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.komentarze_noclegi
    ADD CONSTRAINT komentarze_noclegi_nocleg_id_fkey FOREIGN KEY (nocleg_id) REFERENCES public.noclegi(id) ON DELETE CASCADE;


--
-- TOC entry 4837 (class 2606 OID 16543)
-- Name: komentarze_noclegi komentarze_noclegi_uzytkownik_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.komentarze_noclegi
    ADD CONSTRAINT komentarze_noclegi_uzytkownik_id_fkey FOREIGN KEY (uzytkownik_id) REFERENCES public.uzytkownicy(id) ON DELETE CASCADE;


--
-- TOC entry 4830 (class 2606 OID 16474)
-- Name: plan_atrakcji plan_atrakcji_atrakcja_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plan_atrakcji
    ADD CONSTRAINT plan_atrakcji_atrakcja_id_fkey FOREIGN KEY (atrakcja_id) REFERENCES public.atrakcje(id) ON DELETE CASCADE;


--
-- TOC entry 4831 (class 2606 OID 16469)
-- Name: plan_atrakcji plan_atrakcji_plan_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plan_atrakcji
    ADD CONSTRAINT plan_atrakcji_plan_id_fkey FOREIGN KEY (plan_id) REFERENCES public.plany_podrozy(id) ON DELETE CASCADE;


--
-- TOC entry 4832 (class 2606 OID 16495)
-- Name: plan_noclegi plan_noclegi_nocleg_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plan_noclegi
    ADD CONSTRAINT plan_noclegi_nocleg_id_fkey FOREIGN KEY (nocleg_id) REFERENCES public.noclegi(id) ON DELETE CASCADE;


--
-- TOC entry 4833 (class 2606 OID 16490)
-- Name: plan_noclegi plan_noclegi_plan_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plan_noclegi
    ADD CONSTRAINT plan_noclegi_plan_id_fkey FOREIGN KEY (plan_id) REFERENCES public.plany_podrozy(id) ON DELETE CASCADE;


--
-- TOC entry 4829 (class 2606 OID 16453)
-- Name: plany_podrozy plany_podrozy_uzytkownik_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plany_podrozy
    ADD CONSTRAINT plany_podrozy_uzytkownik_id_fkey FOREIGN KEY (uzytkownik_id) REFERENCES public.uzytkownicy(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--