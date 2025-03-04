DROP TABLE IF EXISTS DONNER;
DROP TABLE IF EXISTS CARACTERISER;
DROP TABLE IF EXISTS CUISINER;
DROP TABLE IF EXISTS AVIS;
DROP TABLE IF EXISTS CARACTERISTIQUE;
DROP TABLE IF EXISTS TYPE_CUISINE;
DROP TABLE IF EXISTS FAVORIS;
DROP TABLE IF EXISTS RESTAURANT;
DROP TABLE IF EXISTS USER;
DROP TABLE IF EXISTS ADMIN;

CREATE TABLE USER (
    idUser INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE,
    prenom TEXT,
    nom TEXT,
    password TEXT NOT NULL
);

CREATE TABLE ADMIN (
    idAdmin INTEGER NOT NULL,
    email TEXT UNIQUE,
    numTel INTEGER UNIQUE,
    PRIMARY KEY (idAdmin)
);

CREATE TABLE AVIS (
    idAvis INTEGER NOT NULL,
    note INTEGER NOT NULL,
    texteAvis TEXT NOT NULL,
    PRIMARY KEY (idAvis)
);

CREATE TABLE DONNER (
    idAvis INTEGER NOT NULL,
    idUser INTEGER NOT NULL,
    datePoste INTEGER NOT NULL,
    siret INTEGER NOT NULL,
    PRIMARY KEY (idAvis, idUser, datePoste, siret),
    FOREIGN KEY (idAvis) REFERENCES AVIS (idAvis),
    FOREIGN KEY (siret) REFERENCES RESTAURANT (siret),
    FOREIGN KEY (idUser) REFERENCES CLIENT (idUser)
);

CREATE TABLE RESTAURANT (
    id_restaurant INTEGER PRIMARY KEY AUTOINCREMENT,
    siret Text,
    type TEXT,
    name TEXT NOT NULL,
    brand TEXT,
    opening_hours TEXT,
    phone Text,
    code_commune INTEGER NOT NULL,
    commune TEXT NOT NULL,
    code_region INTEGER NOT NULL,
    region TEXT NOT NULL,
    code_departement INTEGER NOT NULL,
    departement TEXT NOT NULL,
    longitude TEXT,
    latitude TEXT,
    osm_id TEXT,
    wikidata TEXT,
    brand_wikidata TEXT,
    website TEXT,
    facebook TEXT,
    com_insee TEXT,
    osm_edit TEXT,
    operator TEXT
);

CREATE TABLE FAVORIS (
    siret INTEGER NOT NULL,
    idUser INTEGER NOT NULL,
    PRIMARY KEY (siret, idUser),
    FOREIGN KEY (siret) REFERENCES RESTAURANT (siret),
    FOREIGN KEY (idUser) REFERENCES USER (idUser)
);

CREATE TABLE CARACTERISTIQUE (
    idCarac INTEGER NOT NULL,
    carcteristiqueRestau TEXT NOT NULL,
    PRIMARY KEY (idCarac)
);

CREATE TABLE CARACTERISER ( 
    idCarac INTEGER NOT NULL,
    siret INTEGER NOT NULL,
    PRIMARY KEY(idCarac, siret),
    FOREIGN KEY (idCarac) REFERENCES CARACTERISTIQUE (idCarac),
    FOREIGN KEY (siret) REFERENCES RESTAURANT (siret)
);


CREATE TABLE TYPE_CUISINE (
    idTypeCuisine INTEGER NOT NULL,
    nomCuisine TEXT NOT NULL,
    PRIMARY KEY (idTypeCuisine)
);


CREATE TABLE CUISINER (
    idTypeCuisine INTEGER NOT NULL,
    siret INTEGER NOT NULL,
    PRIMARY KEY(idTypeCuisine, siret),
    FOREIGN KEY (idTypeCuisine) REFERENCES TYPE_CUISINE (idTypeCuisine),
    FOREIGN KEY (siret) REFERENCES RESTAURANT (siret)
);

