DROP TABLE Utilisateurs CASCADE ;
DROP TABLE Calendriers CASCADE;
DROP TABLE Sections CASCADE;
DROP TABLE Questions CASCADE;
DROP TABLE Propositions CASCADE;
DROP TABLE Votants CASCADE;
DROP TABLE responsables CASCADE ;

create table utilisateurs
(
    identifiant varchar(30) not null
        constraint pk_utilisateur
            primary key,
    nom         varchar(30),
    prenom      varchar(30),
    email       varchar(80)
        unique
        constraint chk_email
            check ((email)::text ~~ '%_@__%.__%'::text),
    mdp         varchar(64)
);

create table calendriers
(
    idcalendrier  serial
        primary key,
    debutecriture timestamp,
    finecriture   timestamp,
    debutvote     timestamp,
    finvote       timestamp
);

create table questions
(
    idquestion     serial
        primary key,
    titre          varchar(80),
    description    varchar(360),
    idorganisateur varchar(30),
    idcalendrier   integer
        references calendriers
            on delete cascade,
    creation       timestamp
);

create table sections
(
    idsection   serial,
    idquestion  integer not null
        references questions
            on delete cascade,
    titre       varchar(80),
    description varchar(360),
    primary key (idsection, idquestion)
);

create table propositions
(
    idquestion    integer     not null
        references questions,
    idutilisateur varchar(30) not null
        references utilisateurs,
    titre         varchar(30),
    contenu       varchar(1000),
    primary key (idquestion, idutilisateur)
);

create table responsables
(
    idquestion    integer     not null
        constraint auteurs_idquestion_fkey
            references questions
            on delete cascade,
    idutilisateur varchar(30) not null
        constraint auteurs_idutilisateur_fkey
            references utilisateurs,
    constraint auteurs_pkey
        primary key (idquestion, idutilisateur)
);

create table votants
(
    idquestion    integer     not null
        references questions
            on delete cascade,
    idutilisateur varchar(30) not null
        references utilisateurs,
    primary key (idquestion, idutilisateur)
);

create or replace view questions_termines (idquestion, titre, description, idorganisateur, idcalendrier, creation) as
SELECT q.*
FROM questions q
         JOIN calendriers c ON q.idcalendrier = c.idcalendrier
WHERE ((SELECT CURRENT_TIMESTAMP AS "current_timestamp")) > c.finvote;

create or replace view questions_ecriture (idquestion, titre, description, idorganisateur, idcalendrier, creation) as
SELECT q.*
FROM questions q
         JOIN calendriers c ON q.idcalendrier = c.idcalendrier
WHERE ((SELECT CURRENT_TIMESTAMP AS "current_timestamp")) > c.debutecriture
  AND ((SELECT CURRENT_TIMESTAMP AS "current_timestamp")) < c.finecriture;

create or replace view questions_vote (idquestion, titre, description, idorganisateur, idcalendrier, creation) as
SELECT q.*
FROM questions q
         JOIN calendriers c ON q.idcalendrier = c.idcalendrier
WHERE ((SELECT CURRENT_TIMESTAMP AS "current_timestamp")) > c.debutvote
  AND ((SELECT CURRENT_TIMESTAMP AS "current_timestamp")) < c.finvote;


