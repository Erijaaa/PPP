--
-- PostgreSQL database dump
--

-- Dumped from database version 17.5
-- Dumped by pg_dump version 17.5

-- Started on 2025-05-28 11:31:11

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
-- TOC entry 220 (class 1259 OID 16434)
-- Name: T_demande; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."T_demande" (
    id_demande integer NOT NULL,
    date_demande date,
    num_recu integer,
    type_demande integer
);


ALTER TABLE public."T_demande" OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16453)
-- Name: T_gouv; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."T_gouv" (
    code_gouv text NOT NULL,
    libile_gouv text,
    ip_gouv text
);


ALTER TABLE public."T_gouv" OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16492)
-- Name: T_perception; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."T_perception" (
    id_perception integer,
    partie_extrait text,
    num_recu integer,
    montant_du text,
    montant_deduit text
);


ALTER TABLE public."T_perception" OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 16472)
-- Name: admin; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.admin (
    nom_admin text,
    prenom_admin text,
    cin_admin text,
    password text,
    post integer,
    email character varying(255)
);


ALTER TABLE public.admin OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16523)
-- Name: agent; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.agent (
    nom_agent text,
    prenom_agent text,
    cin_agent text,
    id_demande integer
);


ALTER TABLE public.agent OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16487)
-- Name: chapitres_juridiques; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.chapitres_juridiques (
    nom_chapitre text,
    contenue_chapitre text
);


ALTER TABLE public.chapitres_juridiques OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16460)
-- Name: contrat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contrat (
    id_contrat integer NOT NULL,
    date_contrat date,
    annee_contrat text,
    id_demande integer
);


ALTER TABLE public.contrat OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16389)
-- Name: deposant; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.deposant (
    nom_deposant text,
    prenom_deposant text,
    cin_deposant text,
    adresse_deposant text,
    telephone_deposant text,
    id_demande integer
);


ALTER TABLE public.deposant OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16504)
-- Name: participation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.participation (
    nom_participant text,
    prenom_participant text,
    id_participant integer NOT NULL,
    id_contrar integer,
    date_participation date,
    role_participant text,
    cin_personne text
);


ALTER TABLE public.participation OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 16511)
-- Name: personnels_contracteurs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personnels_contracteurs (
    prenom text,
    prenom_pere text,
    prenom_grand_pere text,
    nom text,
    date_naissance date,
    date_delivrance date,
    etat_delivrance text,
    cin_personne text NOT NULL,
    profession text,
    regime_financier text
);


ALTER TABLE public.personnels_contracteurs OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 16497)
-- Name: personnels_morales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personnels_morales (
    code_fiscale text NOT NULL,
    libile_societe text NOT NULL,
    adresse text,
    fax text
);


ALTER TABLE public.personnels_morales OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16411)
-- Name: pieces_jointes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pieces_jointes (
    code_pieces integer,
    libile_pieces text,
    date_document date,
    ref_document text,
    date_ref date,
    id_demande integer
);


ALTER TABLE public.pieces_jointes OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 16477)
-- Name: redacteur; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.redacteur (
    nom_redacteur text,
    prenom_redacteur text,
    cin_redacteur text,
    password text,
    post integer,
    email character varying(255),
    adresse character varying(255),
    telephone character varying(20),
    id_redacteur integer NOT NULL
);


ALTER TABLE public.redacteur OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16534)
-- Name: redacteur_id_redacteur_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.redacteur_id_redacteur_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.redacteur_id_redacteur_seq OWNER TO postgres;

--
-- TOC entry 5003 (class 0 OID 0)
-- Dependencies: 233
-- Name: redacteur_id_redacteur_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.redacteur_id_redacteur_seq OWNED BY public.redacteur.id_redacteur;


--
-- TOC entry 221 (class 1259 OID 16446)
-- Name: titres; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.titres (
    num_titre text NOT NULL,
    gouv_titre text,
    doub_titre text,
    etat_titre text
);


ALTER TABLE public.titres OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 16394)
-- Name: tls_pieces; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tls_pieces (
    code_pieces integer NOT NULL,
    libile_pieces text NOT NULL
);


ALTER TABLE public.tls_pieces OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16482)
-- Name: valideur; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.valideur (
    nom_valideur text,
    prenom_valideur text,
    cin_valideur text,
    password text,
    post integer,
    email character varying(255),
    adresse character varying(255),
    telephone character varying(20),
    id_valideur integer NOT NULL
);


ALTER TABLE public.valideur OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 16544)
-- Name: valideur_id_valideur_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.valideur_id_valideur_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.valideur_id_valideur_seq OWNER TO postgres;

--
-- TOC entry 5004 (class 0 OID 0)
-- Dependencies: 234
-- Name: valideur_id_valideur_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.valideur_id_valideur_seq OWNED BY public.valideur.id_valideur;


--
-- TOC entry 4803 (class 2604 OID 16535)
-- Name: redacteur id_redacteur; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.redacteur ALTER COLUMN id_redacteur SET DEFAULT nextval('public.redacteur_id_redacteur_seq'::regclass);


--
-- TOC entry 4804 (class 2604 OID 16545)
-- Name: valideur id_valideur; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.valideur ALTER COLUMN id_valideur SET DEFAULT nextval('public.valideur_id_valideur_seq'::regclass);


--
-- TOC entry 4983 (class 0 OID 16434)
-- Dependencies: 220
-- Data for Name: T_demande; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."T_demande" (id_demande, date_demande, num_recu, type_demande) FROM stdin;
10	2022-07-10	150	\N
22	2022-02-22	120	14
23	2023-01-23	125	14
24	2024-03-24	129	14
2	2020-08-02	50	14
14	2025-12-22	30	10
\.


--
-- TOC entry 4985 (class 0 OID 16453)
-- Dependencies: 222
-- Data for Name: T_gouv; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."T_gouv" (code_gouv, libile_gouv, ip_gouv) FROM stdin;
\.


--
-- TOC entry 4991 (class 0 OID 16492)
-- Dependencies: 228
-- Data for Name: T_perception; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."T_perception" (id_perception, partie_extrait, num_recu, montant_du, montant_deduit) FROM stdin;
\.


--
-- TOC entry 4987 (class 0 OID 16472)
-- Dependencies: 224
-- Data for Name: admin; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.admin (nom_admin, prenom_admin, cin_admin, password, post, email) FROM stdin;
dridi	arij	123	2222	0	erijedridi1@gmail.com
\.


--
-- TOC entry 4995 (class 0 OID 16523)
-- Dependencies: 232
-- Data for Name: agent; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.agent (nom_agent, prenom_agent, cin_agent, id_demande) FROM stdin;
الدريدي	آية	567	14
دريدي	آية	567	22
دريدي	آية	567	23
دريدي	آية	567	24
دريدي	آية	567	2
\.


--
-- TOC entry 4990 (class 0 OID 16487)
-- Dependencies: 227
-- Data for Name: chapitres_juridiques; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.chapitres_juridiques (nom_chapitre, contenue_chapitre) FROM stdin;
\.


--
-- TOC entry 4986 (class 0 OID 16460)
-- Dependencies: 223
-- Data for Name: contrat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contrat (id_contrat, date_contrat, annee_contrat, id_demande) FROM stdin;
\.


--
-- TOC entry 4980 (class 0 OID 16389)
-- Dependencies: 217
-- Data for Name: deposant; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.deposant (nom_deposant, prenom_deposant, cin_deposant, adresse_deposant, telephone_deposant, id_demande) FROM stdin;
دريدي	أحمد	123	باجة	29018774	22
دريدي	أحمد	123	باجة	29018774	23
دريدي	أحمد	123	باجة	29018774	24
دريدي	أحمد	123	باجة	29018774	2
\.


--
-- TOC entry 4993 (class 0 OID 16504)
-- Dependencies: 230
-- Data for Name: participation; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.participation (nom_participant, prenom_participant, id_participant, id_contrar, date_participation, role_participant, cin_personne) FROM stdin;
\.


--
-- TOC entry 4994 (class 0 OID 16511)
-- Dependencies: 231
-- Data for Name: personnels_contracteurs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personnels_contracteurs (prenom, prenom_pere, prenom_grand_pere, nom, date_naissance, date_delivrance, etat_delivrance, cin_personne, profession, regime_financier) FROM stdin;
\.


--
-- TOC entry 4992 (class 0 OID 16497)
-- Dependencies: 229
-- Data for Name: personnels_morales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personnels_morales (code_fiscale, libile_societe, adresse, fax) FROM stdin;
\.


--
-- TOC entry 4982 (class 0 OID 16411)
-- Dependencies: 219
-- Data for Name: pieces_jointes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pieces_jointes (code_pieces, libile_pieces, date_document, ref_document, date_ref, id_demande) FROM stdin;
20	توكيل	2022-02-22	123654	2022-02-22	2
31	رخصة	1991-01-01	85214	1991-01-01	2
22	جدول إصلاح	2023-12-15	01235	2023-12-15	2
15	تفويض	2020-03-05	58693	2020-03-05	10
47	شهادة في رفع اليد	2006-06-06	475632	2006-06-06	10
17	تقرير توجه	1999-08-13	12453	1999-08-13	10
53	عقد زواج	2002-08-03	22145	2002-08-03	14
15	تفويض	2023-01-14	1256347	2023-01-14	14
20	توكيل	2022-02-22	123654	2022-02-22	22
31	رخصة	1991-01-01	85214	1991-01-01	22
22	جدول إصلاح	2023-12-15	01235	2023-12-15	22
15	تفويض	2020-03-05	58693	2020-03-05	23
47	شهادة في رفع اليد	2006-06-06	475632	2006-06-06	23
17	تقرير توجه	1999-08-13	12453	1999-08-13	23
53	عقد زواج	2002-08-03	22145	2002-08-03	24
15	تفويض	2023-01-14	1256347	2023-01-14	24
\.


--
-- TOC entry 4988 (class 0 OID 16477)
-- Dependencies: 225
-- Data for Name: redacteur; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.redacteur (nom_redacteur, prenom_redacteur, cin_redacteur, password, post, email, adresse, telephone, id_redacteur) FROM stdin;
oueslati	najeh	123	2222	0	najehoueslati@gmail.com	\N	\N	1
\.


--
-- TOC entry 4984 (class 0 OID 16446)
-- Dependencies: 221
-- Data for Name: titres; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.titres (num_titre, gouv_titre, doub_titre, etat_titre) FROM stdin;
\.


--
-- TOC entry 4981 (class 0 OID 16394)
-- Dependencies: 218
-- Data for Name: tls_pieces; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tls_pieces (code_pieces, libile_pieces) FROM stdin;
25	حجة وفاة
53	عقد زواج
1	إذن حاكم التقاديم
23	حجة عادلة
2	إذن قضائي بتجديد قيد إحتياطي
24	حجة مغارسة
29	حكم تعقيبي
32	رخص إدارية
26	حكم إبتدائي
27	حكم إستئنافي
28	حكم بالترسيم
31	رخصة
36	رخصة وزير أملاك الدولة والشؤون العقارية
35	رخصة رئيس الجمهورية
22	جدول إصلاح
37	رخصة وزيري أملاك الدولة والشؤون العقارية والفلاحة
33	رخصة الوالي
34	رخصة الوزير الأول
15	تفويض
20	توكيل
47	شهادة في رفع اليد
50	شهادة في عدم الطعن بالتعقيب
49	شهادة في عدم الطعن بالإستئناف
48	شهادة في عدم إستخراج قطعة
46	شهادة في دفع المصاريف والأجور المسعرة
45	شهادة في ثبوت الجنسية
42	شهادة حوز
51	شهادة نشر
43	شهادة رفع اليد
13	تصريح قصد التسجيل بالسجل التجاري
3	إعلام بحكم
17	تقرير توجه
52	شهادة وفاة مقامة من قبل السلط الأجنبية مصادق عليها من قبل السلط الدبلوماسية  والقنصلية المعتمدة لديها
39	شهادة إشهار
40	شهادة بنكية
41	شهادة تطابق
44	شهادة عرفية
18	تقرير مراقب  الحسابات
8	بطاقة تعريف تاجر
4	إعلام بمطلب مراجعة حكم بالترسيم
5	إعلام بمطلب مراجعة قرار تحيين
12	تصريح بقيمة العقارات
19	تقرير مراقب المساهمات العينية
16	تقرير إختبار
14	تصريح لإجراء تقييد تنقيحي
7	بطاقة المعرف الجبائي
11	تصريح بالحالة المدنية
10	تصريح بالإكتتاب و الدفع
30	الرائد الرسمي
9	تأشيرة قانونية
60	كتب خطي
81	وصل خلاص
104	حكم تبتيت
90	عقد إداري
92	كتب تعريب
79	وصل إيداع
80	وصل تأمين
98	أمر إنتزاع
87	حجة مصادقة
86	كتب تكميلي
96	كتب مصادقة
84	حكم بالتسجيل
89	مطلب
74	نسخة من عريضة الدعوى
76	نظير من قائمة الأشخاص المكلفين بإدارة وتسيير الذات المعنوية
73	نسخة من الإشهار بالرائد الرسمي
61	كراس شروط
67	محضر جلسة
62	مثال هندسي
65	محضر تبتيت
66	محضر تحديد
93	محضر تلاوة
70	مقرر إداري
64	محضر إنذار قائم مقام عقلة عقارية
102	رخصة وزارة إسكان
100	جدول تقسيمي
97	جدول توضيحي
99	قرار توضيحي
78	ورقة الحضور
75	نظام الإشتراك في الملكية
103	شهادة
57	قانون
88	مذكرة
72	مكتوب
94	شهادة في عدم التعقيب
82	شهادة في عدم الإستئناف
55	قائمة في أسماء المستحقين
56	قائمة في أنصباء المستحقين
68	مضمون وفاة
58	قانون أساسي
85	مدونة تحرير
91	مدونة ترسيم
71	مدونة تلخيص
69	مضمون ولادة
77	نموذج إمضاء
63	محاضر إيداع لدى المصلحة التجارية بالمحكمة الإبتدائية
95	تقرير إختبار تكميلي
54	قائمة المكتتبين
0	
6	أمر
109	إذن
105	حكم
117	وصل خلاص معلوم نقل التركة
120	إذن قضائي
125	سند ملكية
38	سند تنفيذي: حكم، إذن بالدفع، بطاقة جبر أو إلزام، أحكام التحكيم، إذن على عريضة
59	قرار
108	وصية
121	نسخة من شيك
124	نسخة من جواز سفر
114	نسخة من بطاقة تعريف وطنية
122	نسخة من كمبيالة
113	محضر رفع إنذار يقوم مقام عقلة عقارية
21	جدول إحالة
106	قرار إصلاح
110	قرار ترسيم
111	مطلب ترسيم
126	مضمون من السجل التجاري
116	توكيل خطي
112	بطاقة تصفية
115	شهادة إبراء
118	أمثلة هندسية
119	توكيل بالحجة العادلة
123	شهادة موافقة مبدائية
107	إدماج بالإستعاب
128	بطاقة إلزام
129	محضر تبليغ
130	مطلب إشهار إمتياز
131	محضر إعلام بحكم
132	محضر تنفيذ عيني
133	محضر
134	شهادة في عدم إستئناف حكم مدني
135	شهادة في عدم إستئناف حكم إستئنافي
136	شهادة في عدم إستئناف حكم تعقيبي
137	إشهار بجريدة
138	إعلام
139	محضر تنبيه
140	قرار تحكيمي
127	محضر تبليغ بطاقة إلزام مع توجيه إنذار بالدفع
141	محضر معاينة
142	محضر إنتهاء أشغال
143	مضمون من دفتر
144	أمر بالدفع
145	بطاقة إقامة
146	رخصة البنك المركزي
\.


--
-- TOC entry 4989 (class 0 OID 16482)
-- Dependencies: 226
-- Data for Name: valideur; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.valideur (nom_valideur, prenom_valideur, cin_valideur, password, post, email, adresse, telephone, id_valideur) FROM stdin;
dridi	mohammed	456	0	2	mohammeddridi@gmail.com	boussalem	98614866	2
baccoushi	shaima	678	0	1	shaima@gmail.com	ben Arous	22354159	1
\.


--
-- TOC entry 5005 (class 0 OID 0)
-- Dependencies: 233
-- Name: redacteur_id_redacteur_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.redacteur_id_redacteur_seq', 1, true);


--
-- TOC entry 5006 (class 0 OID 0)
-- Dependencies: 234
-- Name: valideur_id_valideur_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.valideur_id_valideur_seq', 1, false);


--
-- TOC entry 4812 (class 2606 OID 16440)
-- Name: T_demande T_demande_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."T_demande"
    ADD CONSTRAINT "T_demande_pkey" PRIMARY KEY (id_demande);


--
-- TOC entry 4816 (class 2606 OID 16459)
-- Name: T_gouv T_gouv_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."T_gouv"
    ADD CONSTRAINT "T_gouv_pkey" PRIMARY KEY (code_gouv);


--
-- TOC entry 4818 (class 2606 OID 16466)
-- Name: contrat contrat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat
    ADD CONSTRAINT contrat_pkey PRIMARY KEY (id_contrat);


--
-- TOC entry 4826 (class 2606 OID 16510)
-- Name: participation participation_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participation
    ADD CONSTRAINT participation_pkey PRIMARY KEY (id_participant);


--
-- TOC entry 4824 (class 2606 OID 16503)
-- Name: personnels_morales personnels_morales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personnels_morales
    ADD CONSTRAINT personnels_morales_pkey PRIMARY KEY (code_fiscale, libile_societe);


--
-- TOC entry 4820 (class 2606 OID 16537)
-- Name: redacteur redacteur_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.redacteur
    ADD CONSTRAINT redacteur_pkey PRIMARY KEY (id_redacteur);


--
-- TOC entry 4814 (class 2606 OID 16452)
-- Name: titres titres_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.titres
    ADD CONSTRAINT titres_pkey PRIMARY KEY (num_titre);


--
-- TOC entry 4806 (class 2606 OID 16400)
-- Name: tls_pieces tls_pieces_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tls_pieces
    ADD CONSTRAINT tls_pieces_pkey PRIMARY KEY (code_pieces, libile_pieces);


--
-- TOC entry 4828 (class 2606 OID 16517)
-- Name: personnels_contracteurs unq_personnels_contracteurs_cin; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personnels_contracteurs
    ADD CONSTRAINT unq_personnels_contracteurs_cin UNIQUE (cin_personne);


--
-- TOC entry 4808 (class 2606 OID 16417)
-- Name: tls_pieces unq_tls_pieces_code; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tls_pieces
    ADD CONSTRAINT unq_tls_pieces_code UNIQUE (code_pieces);


--
-- TOC entry 4810 (class 2606 OID 16428)
-- Name: tls_pieces unq_tls_pieces_libile; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tls_pieces
    ADD CONSTRAINT unq_tls_pieces_libile UNIQUE (libile_pieces);


--
-- TOC entry 4822 (class 2606 OID 16547)
-- Name: valideur valideur_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.valideur
    ADD CONSTRAINT valideur_pkey PRIMARY KEY (id_valideur);


--
-- TOC entry 4832 (class 2606 OID 16467)
-- Name: contrat fc; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contrat
    ADD CONSTRAINT fc FOREIGN KEY (id_demande) REFERENCES public."T_demande"(id_demande);


--
-- TOC entry 4829 (class 2606 OID 16441)
-- Name: pieces_jointes fd; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pieces_jointes
    ADD CONSTRAINT fd FOREIGN KEY (id_demande) REFERENCES public."T_demande"(id_demande);


--
-- TOC entry 4830 (class 2606 OID 16429)
-- Name: pieces_jointes fg; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pieces_jointes
    ADD CONSTRAINT fg FOREIGN KEY (libile_pieces) REFERENCES public.tls_pieces(libile_pieces);


--
-- TOC entry 4831 (class 2606 OID 16420)
-- Name: pieces_jointes fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pieces_jointes
    ADD CONSTRAINT fk FOREIGN KEY (code_pieces) REFERENCES public.tls_pieces(code_pieces);


--
-- TOC entry 4833 (class 2606 OID 16518)
-- Name: participation fk_participation_cin; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participation
    ADD CONSTRAINT fk_participation_cin FOREIGN KEY (cin_personne) REFERENCES public.personnels_contracteurs(cin_personne);


--
-- TOC entry 4834 (class 2606 OID 16528)
-- Name: agent id_demande; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.agent
    ADD CONSTRAINT id_demande FOREIGN KEY (id_demande) REFERENCES public."T_demande"(id_demande);


-- Completed on 2025-05-28 11:31:12

--
-- PostgreSQL database dump complete
--

