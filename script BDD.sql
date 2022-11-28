create table Calendriers
(
    idCalendrier  int auto_increment
        primary key,
    debutecriture timestamp default current_timestamp()   not null on update current_timestamp(),
    finecriture   timestamp default '0000-00-00 00:00:00' not null,
    debutvote     timestamp default '0000-00-00 00:00:00' not null,
    finvote       timestamp default '0000-00-00 00:00:00' not null
);

create table Questions
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

create table Propositions
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

create table Proposition_section
(
    idsection     int           not null
        primary key,
    contenu       varchar(1500) null,
    idproposition int           null,
    constraint Proposition_section_Propositions_idproposition_fk
        foreign key (idproposition) references Propositions (idproposition)
);

create table Sections
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

create table Utilisateurs
(
    identifiant varchar(30) not null
        primary key,
    nom         varchar(30) null,
    prenom      varchar(30) null,
    email       varchar(60) null,
    mdp         varchar(64) null
);

create table Responsables
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

create table Votants
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

create table Votes
(
    idutilisateur varchar(30) not null,
    idproposition int         not null,
    primary key (idutilisateur, idproposition),
    constraint Votes_Propositions_idproposition_fk
        foreign key (idproposition) references Propositions (idproposition)
            on update cascade on delete cascade
);

create definer = dimeckr@`%` view questions_ecriture as
select `q`.`idquestion`     AS `idquestion`,
       `q`.`titre`          AS `titre`,
       `q`.`description`    AS `description`,
       `q`.`idorganisateur` AS `idorganisateur`,
       `q`.`idcalendrier`   AS `idcalendrier`,
       `q`.`creation`       AS `creation`
from (`dimeckr`.`Questions` `q` join `dimeckr`.`Calendriers` `c` on (`q`.`idcalendrier` = `c`.`idCalendrier`))
where (select current_timestamp() AS `current_timestamp`) > `c`.`debutecriture`
  and (select current_timestamp() AS `current_timestamp`) < `c`.`finecriture`;

create definer = dimeckr@`%` view questions_termines as
select `q`.`idquestion`     AS `idquestion`,
       `q`.`titre`          AS `titre`,
       `q`.`description`    AS `description`,
       `q`.`idorganisateur` AS `idorganisateur`,
       `q`.`idcalendrier`   AS `idcalendrier`,
       `q`.`creation`       AS `creation`
from (`dimeckr`.`Questions` `q` join `dimeckr`.`Calendriers` `c` on (`q`.`idcalendrier` = `c`.`idCalendrier`))
where (select current_timestamp() AS `current_timestamp`) > `c`.`finvote`;

create definer = dimeckr@`%` view questions_vote as
select `q`.`idquestion`     AS `idquestion`,
       `q`.`titre`          AS `titre`,
       `q`.`description`    AS `description`,
       `q`.`idorganisateur` AS `idorganisateur`,
       `q`.`idcalendrier`   AS `idcalendrier`,
       `q`.`creation`       AS `creation`
from (`dimeckr`.`Questions` `q` join `dimeckr`.`Calendriers` `c` on (`q`.`idcalendrier` = `c`.`idCalendrier`))
where (select current_timestamp() AS `current_timestamp`) > `c`.`debutvote`
  and (select current_timestamp() AS `current_timestamp`) < `c`.`finvote`;

