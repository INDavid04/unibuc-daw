--
-- Sterge tabele 
--

drop table if exists bilet;
drop table if exists eveniment_locatie;
drop table if exists eveniment_istoric;
drop table if exists eveniment;
drop table if exists istoric;
drop table if exists spectator_locatie;
drop table if exists spectator;
drop table if exists organizator;
drop table if exists locatie;
drop table if exists judet;
drop table if exists tara;
drop table if exists utilizator;

--
-- Creeaza tabele
--

create table utilizator (
    id_utilizator int auto_increment primary key,
    nume varchar(80) not null unique,
    mail varchar(80),
    parola varchar(255) not null -- 255 pentru a incapea dupa hash in phpmyadmin
) engine=innodb;

create table organizator (
    id_utilizator int primary key,
    contact varchar (80),
    -- Mandatory: Vrem ca un utilizator sa aiba acelasi id in toate cele trei tabele
    constraint fk_utilizator_organizator 
        foreign key (id_utilizator) references utilizator(id_utilizator) on delete cascade
    -- Fun fact: On delete cascade face ca atunci cand sterg un utilizator sa se stearga si organizatorul
) engine=innodb;

create table spectator (
    id_utilizator int primary key,
    telefon varchar (20),
    constraint fk_utilizator_spectator
        foreign key (id_utilizator) references utilizator(id_utilizator) on delete cascade
) engine=innodb;

create table tara (
    id_tara int auto_increment primary key,
    denumire varchar(80) not null unique
) engine=innodb;

create table judet (
    id_judet int auto_increment primary key,
    denumire varchar(80) not null unique,
    id_tara int not null,
    constraint fk_tara_to_judet
        foreign key (id_tara)
        references tara(id_tara) 
        on delete cascade
) engine=innodb;

create table locatie (
    id_locatie int auto_increment primary key,
    denumire varchar(80) not null,
    id_judet int not null,
    constraint fk_judet_to_locatie
        foreign key (id_judet)
        references judet(id_judet)
        on delete cascade
) engine=innodb;

create table spectator_locatie (
    id_utilizator int not null,
    id_locatie int not null,
    primary key (id_utilizator, id_locatie),
    constraint fk_spectator_locatie_to_spectator
        foreign key (id_utilizator) references spectator(id_utilizator)
        on delete cascade,
    constraint fk_spectator_locatie_to_locatie
        foreign key (id_locatie) references locatie(id_locatie)
        on delete cascade
) engine=innodb;

create table istoric (
    id_istoric int auto_increment not null primary key,
    incepe date not null,
    termina date
) engine=innodb;

create table eveniment (
    id_eveniment int auto_increment not null primary key,
    denumire varchar(80) not null,
    id_utilizator int not null,
    constraint organizator_to_eveniment
        foreign key (id_utilizator)
        references organizator(id_utilizator)
        on delete cascade
) engine=innodb;

create table eveniment_istoric (
    id_eveniment int not null,
    id_istoric int not null,
    primary key (id_eveniment, id_istoric),
    constraint fk_eveniment_istoric_to_eveniment
        foreign key (id_eveniment)
        references eveniment(id_eveniment)
        on delete cascade,
    constraint fk_eveniment_istoric_to_istoric
        foreign key (id_istoric) 
        references istoric(id_istoric)
        on delete cascade
) engine=innodb;

create table eveniment_locatie (
    id_eveniment int not null,
    id_locatie int not null,
    primary key (id_eveniment, id_locatie),
    constraint fk_eveniment_locatie_to_eveniment
        foreign key (id_eveniment)
        references eveniment(id_eveniment)
        on delete cascade,
    constraint eveniment_locatie_to_locatie
        foreign key (id_locatie)
        references locatie(id_locatie)
        on delete cascade
) engine=innodb;

create table bilet (
    id_bilet int auto_increment not null primary key,
    pret int,
    stare varchar(20), -- rezervat, platit, folosit, expirat
    id_spectator int not null,
    id_eveniment int not null,
    constraint fk_spectator_to_bilet
        foreign key (id_spectator)
        references spectator(id_utilizator)
        on delete cascade,
    constraint fk_eveniment_to_bilet
        foreign key (id_eveniment)
        references eveniment(id_eveniment)
        on delete cascade
) engine=innodb;
