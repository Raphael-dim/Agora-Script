create or replace table dimeckr.Calendriers
(
    idCalendrier  int auto_increment
    primary key,
    debutecriture timestamp default current_timestamp()   not null on update current_timestamp(),
    finecriture   timestamp default '0000-00-00 00:00:00' not null,
    debutvote     timestamp default '0000-00-00 00:00:00' not null,
    finvote       timestamp default '0000-00-00 00:00:00' not null,
    constraint date_check
    check (`debutecriture` < `finecriture` and `finecriture` < `debutvote` and `debutvote` < `finvote`)
);

create or replace table dimeckr.Questions
(
    idquestion     int auto_increment
    primary key,
    titre          varchar(80)                           null,
    description    varchar(360)                          null,
    idorganisateur varchar(30)                           null,
    idcalendrier   int                                   null,
    creation       timestamp default current_timestamp() not null on update current_timestamp(),
    constraint Questions_Calendriers_idCalendrier_fk
    foreign key (idcalendrier) references dimeckr.Calendriers (idCalendrier)
    on update cascade on delete cascade
);

create or replace definer = dimeckr@`%` trigger dimeckr.tr_maj_delete_calendrier
    before delete
           on dimeckr.Questions
               for each row
DELETE FROM Calendriers
WHERE idcalendrier = OLD.idCalendrier;

create or replace table dimeckr.Sections
(
    idsection   int auto_increment,
    idquestion  int          not null,
    titre       varchar(80)  null,
    description varchar(360) null,
    primary key (idsection, idquestion),
    constraint Sections_Questions_idquestion_fk
    foreign key (idquestion) references dimeckr.Questions (idquestion)
    on update cascade on delete cascade
);

create or replace table dimeckr.Utilisateurs
(
    identifiant varchar(30)          not null
    primary key,
    nom         varchar(30)          null,
    prenom      varchar(30)          null,
    mdp         varchar(256)         null,
    estAdmin    tinyint(1) default 0 null
);

create or replace table dimeckr.Responsables
(
    idquestion    int         not null,
    idutilisateur varchar(30) not null,
    primary key (idquestion, idutilisateur),
    constraint Responsables_Questions_idquestion_fk
    foreign key (idquestion) references dimeckr.Questions (idquestion)
    on update cascade on delete cascade,
    constraint Responsables_Utilisateurs_identifiant_fk
    foreign key (idutilisateur) references dimeckr.Utilisateurs (identifiant)
);

create or replace table dimeckr.Propositions
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
    foreign key (idquestion, idresponsable) references dimeckr.Responsables (idquestion, idutilisateur)
    on update cascade on delete cascade
);

create or replace table dimeckr.Coauteurs
(
    idauteur      varchar(30) not null,
    idproposition int         not null,
    primary key (idproposition, idauteur),
    constraint `Co-auteurs_Propositions_idproposition_fk`
    foreign key (idproposition) references dimeckr.Propositions (idproposition)
    on update cascade on delete cascade,
    constraint `Co-auteurs_Utilisateurs_identifiant_fk`
    foreign key (idauteur) references dimeckr.Utilisateurs (identifiant)
    on update cascade on delete cascade
);

create or replace table dimeckr.Proposition_section
(
    idsection     int           not null,
    contenu       varchar(1500) null,
    idproposition int           not null,
    primary key (idsection, idproposition),
    constraint Proposition_section_Propositions_idproposition_fk
    foreign key (idproposition) references dimeckr.Propositions (idproposition)
    on update cascade on delete cascade,
    constraint Proposition_section_Sections_idsection_fk
    foreign key (idsection) references dimeckr.Sections (idsection)
    on update cascade on delete cascade
);

create or replace index Propositions_Responsables_idutilisateur_fk
    on dimeckr.Propositions (idresponsable);

create or replace index idquestion
    on dimeckr.Propositions (idquestion);

create or replace table dimeckr.Votants
(
    idquestion    int         not null,
    idutilisateur varchar(30) not null,
    primary key (idquestion, idutilisateur),
    constraint Votants_Questions_idquestion_fk
    foreign key (idquestion) references dimeckr.Questions (idquestion)
    on update cascade on delete cascade,
    constraint Votants_Utilisateurs_identifiant_fk
    foreign key (idutilisateur) references dimeckr.Utilisateurs (identifiant)
    on update cascade on delete cascade
);

create or replace table dimeckr.Votes
(
    idvotant      varchar(30) not null,
    idproposition int         not null,
    valeurvote    int         null,
    idvote        int auto_increment,
    primary key (idvotant, idproposition),
    constraint idvote
    unique (idvote),
    constraint Votes_Propositions_idproposition_fk
    foreign key (idproposition) references dimeckr.Propositions (idproposition)
    on update cascade on delete cascade
);

create or replace definer = dimeckr@`%` trigger dimeckr.tr_maj_nbVotes_DELETE
    after delete
          on dimeckr.Votes
              for each row
UPDATE Propositions
SET nbvotes = nbvotes - OLD.valeurvote
WHERE idproposition = OLD.idproposition;

create or replace definer = dimeckr@`%` trigger dimeckr.tr_maj_nbVotes_INSERT
    after insert
          on dimeckr.Votes
              for each row
UPDATE Propositions
SET nbvotes = nbvotes + NEW.valeurvote
WHERE idproposition = NEW.idproposition;

create or replace definer = dimeckr@`%` trigger dimeckr.tr_maj_nbVotes_UPDATE
    after update
                                on dimeckr.Votes
                                for each row
UPDATE Propositions
SET nbvotes = nbvotes - (OLD.valeurvote - NEW.valeurvote)
WHERE idproposition = OLD.idproposition;

create or replace definer = dimeckr@`%` view dimeckr.questions_ecriture as
select `q`.`idquestion`     AS `idquestion`,
       `q`.`titre`          AS `titre`,
       `q`.`description`    AS `description`,
       `q`.`idorganisateur` AS `idorganisateur`,
       `q`.`idcalendrier`   AS `idcalendrier`,
       `q`.`creation`       AS `creation`
from (`dimeckr`.`Questions` `q` join `dimeckr`.`Calendriers` `c` on (`q`.`idcalendrier` = `c`.`idCalendrier`))
where (select current_timestamp() AS `current_timestamp`) > `c`.`debutecriture`
  and (select current_timestamp() AS `current_timestamp`) < `c`.`finecriture`;

create or replace definer = dimeckr@`%` view dimeckr.questions_termines as
select `q`.`idquestion`     AS `idquestion`,
       `q`.`titre`          AS `titre`,
       `q`.`description`    AS `description`,
       `q`.`idorganisateur` AS `idorganisateur`,
       `q`.`idcalendrier`   AS `idcalendrier`,
       `q`.`creation`       AS `creation`
from (`dimeckr`.`Questions` `q` join `dimeckr`.`Calendriers` `c` on (`q`.`idcalendrier` = `c`.`idCalendrier`))
where (select current_timestamp() AS `current_timestamp`) > `c`.`finvote`;

create or replace definer = dimeckr@`%` view dimeckr.questions_vote as
select `q`.`idquestion`     AS `idquestion`,
       `q`.`titre`          AS `titre`,
       `q`.`description`    AS `description`,
       `q`.`idorganisateur` AS `idorganisateur`,
       `q`.`idcalendrier`   AS `idcalendrier`,
       `q`.`creation`       AS `creation`
from (`dimeckr`.`Questions` `q` join `dimeckr`.`Calendriers` `c` on (`q`.`idcalendrier` = `c`.`idCalendrier`))
where (select current_timestamp() AS `current_timestamp`) > `c`.`debutvote`
  and (select current_timestamp() AS `current_timestamp`) < `c`.`finvote`;

