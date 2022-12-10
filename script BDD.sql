create or replace table Calendriers
(
    idCalendrier  int auto_increment
    primary key,
    debutecriture timestamp default current_timestamp()   not null on update current_timestamp(),
    finecriture   timestamp default '0000-00-00 00:00:00' not null,
    debutvote     timestamp default '0000-00-00 00:00:00' not null,
    finvote       timestamp default '0000-00-00 00:00:00' not null
);

create or replace table Questions
(
    idquestion     int auto_increment
    primary key,
    titre          varchar(80)                           null,
    description    varchar(360)                          null,
    idorganisateur varchar(30)                           null,
    idcalendrier   int                                   null,
    creation       timestamp default current_timestamp() not null on update current_timestamp(),
    constraint Questions_Calendriers_idCalendrier_fk
    foreign key (idcalendrier) references Calendriers (idCalendrier)
    on update cascade on delete cascade
);

create or replace table Propositions
(
    idquestion    int           not null,
    idresponsable varchar(30)   not null,
    titre         varchar(500)  null,
    idproposition int auto_increment,
    nbvotes       int default 0 null,
    primary key (idquestion, idresponsable),
    constraint Propositions_pk
    unique (idproposition),
    constraint Propositions_Questions_idquestion_fk
    foreign key (idquestion) references Questions (idquestion)
    on update cascade on delete cascade
);

create or replace index idquestion
    on Propositions (idquestion);

create or replace table Sections
(
    idsection   int auto_increment,
    idquestion  int          not null,
    titre       varchar(80)  null,
    description varchar(360) null,
    primary key (idsection, idquestion),
    constraint Sections_Questions_idquestion_fk
    foreign key (idquestion) references Questions (idquestion)
    on update cascade on delete cascade
);

create or replace table Proposition_section
(
    idsection     int           not null,
    contenu       varchar(1500) null,
    idproposition int           not null,
    primary key (idsection, idproposition),
    constraint Proposition_section_Propositions_idproposition_fk
    foreign key (idproposition) references Propositions (idproposition)
    on update cascade on delete cascade,
    constraint Proposition_section_Sections_idsection_fk
    foreign key (idsection) references Sections (idsection)
    on update cascade on delete cascade
);

create or replace table Utilisateurs
(
    identifiant varchar(30) not null
    primary key,
    nom         varchar(30) null,
    prenom      varchar(30) null,
    mdp         varchar(64) null
);

create or replace table Coauteurs
(
    idauteur      varchar(30) not null,
    idproposition int         not null,
    primary key (idproposition, idauteur),
    constraint `Co-auteurs_Propositions_idproposition_fk`
    foreign key (idproposition) references Propositions (idproposition)
    on update cascade on delete cascade,
    constraint `Co-auteurs_Utilisateurs_identifiant_fk`
    foreign key (idauteur) references Utilisateurs (identifiant)
    on update cascade on delete cascade
);

create or replace table Responsables
(
    idquestion    int         not null,
    idutilisateur varchar(30) not null,
    primary key (idquestion, idutilisateur),
    constraint Responsables_Questions_idquestion_fk
    foreign key (idquestion) references Questions (idquestion)
    on update cascade on delete cascade,
    constraint Responsables_Utilisateurs_identifiant_fk
    foreign key (idutilisateur) references Utilisateurs (identifiant)
);

create or replace table Votants
(
    idquestion    int         not null,
    idutilisateur varchar(30) not null,
    primary key (idquestion, idutilisateur),
    constraint Votants_Questions_idquestion_fk
    foreign key (idquestion) references Questions (idquestion)
    on update cascade on delete cascade,
    constraint Votants_Utilisateurs_identifiant_fk
    foreign key (idutilisateur) references Utilisateurs (identifiant)
    on update cascade on delete cascade
);

create or replace table Votes
(
    idvotant      varchar(30) not null,
    idproposition int         not null,
    valeurvote    int         null,
    idvote        int auto_increment,
    primary key (idvotant, idproposition),
    constraint idvote
    unique (idvote),
    constraint Votes_Propositions_idproposition_fk
    foreign key (idproposition) references Propositions (idproposition)
    on update cascade on delete cascade
);

create or replace definer = dimeckr@`%` trigger tr_maj_nbVotes_DELETE
    after delete
          on Votes
              for each row
UPDATE Propositions
SET nbvotes = nbvotes - 1
WHERE idproposition = OLD.idproposition;

create or replace definer = dimeckr@`%` trigger tr_maj_nbVotes_INSERT
    after insert
          on Votes
              for each row
UPDATE Propositions
SET nbvotes = nbvotes + 1
WHERE idproposition = NEW.idproposition;
