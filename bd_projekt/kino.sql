DROP TABLE Zamowienie;
DROP TABLE Klient;
DROP TABLE Seans;
DROP TABLE Sala;
DROP TABLE Film;

CREATE TABLE Film
(
    id                 NUMBER(15) PRIMARY KEY,
    tytul              VARCHAR2(256)  NOT NULL,
    typ                VARCHAR2(256)  NOT NULL,
    rezyser            VARCHAR2(256)  NOT NULL,
    dlugosc_w_minutach NUMBER(15)     NOT NULL CHECK ( dlugosc_w_minutach > 0 ),
    rok                NUMBER(15)     NOT NULL,
    opis               VARCHAR2(1000) NOT NULL
);

CREATE TABLE Sala
(
    numer         NUMBER(15) PRIMARY KEY,
    liczba_miejsc NUMBER(15) NOT NULL CHECK ( liczba_miejsc > 0 )
);

CREATE TABLE Seans
(
    id                    NUMBER(15) PRIMARY KEY,
    czas_rozpoczecia      TIMESTAMP  NOT NULL,
    liczba_wolnych_miejsc NUMBER(15) NOT NULL CHECK ( liczba_wolnych_miejsc >= 0 ),
    film_id               NUMBER(15) NOT NULL REFERENCES Film,
    numer_sali            NUMBER(15) NOT NULL REFERENCES Sala
);

CREATE TABLE Klient
(
    id    NUMBER(15) PRIMARY KEY,
    email VARCHAR2(256) NOT NULL UNIQUE,
    haslo VARCHAR2(256) NOT NULL
);

CREATE TABLE Zamowienie
(
    id            NUMBER(15) PRIMARY KEY,
    liczba_miejsc NUMBER(15) NOT NULL CHECK ( liczba_miejsc > 0 ),
    klient_id     NUMBER(15) NOT NULL REFERENCES Klient,
    seans_id      NUMBER(15) NOT NULL REFERENCES Seans
);


INSERT INTO FILM
VALUES (1, 'JAK ZOSTAŁEM GANGSTEREM. HISTORIA PRAWDZIWA', 'Sensacyjny', 'Maciej Kawulski', 133, 2019,
        'Historia najniebezpieczniejszego gangstera w Polsce, dla którego władza, ' ||
        'bycie ponad stan i pieniądze stanowią priorytet.');

INSERT INTO FILM
VALUES (2, 'JUDY', 'Biograficzny / Dramat', 'Rupert Goold', 118, 2019,
        'Zima 1968 roku. Ciesząca się ogromną popularnością Judy Garland ' ||
        'przybywa do Londynu na serię koncertów.');

INSERT INTO FILM
VALUES (3, 'SOKÓŁ Z MASŁEM ORZECHOWYM', 'Dramat / Komedia / Przygodowy', 'Tyler Nilson / Michael Schwartz', 97, 2019,
        'Zak jest wyjątkowym chłopakiem i zrobi wszystko, by spełnić swoje niezwykłe marzenie - ' ||
        'chce podbić świat amerykańskiego wrestlingu. Na swojej drodze przypadkiem spotyka Tylera, ' ||
        'drobnego złodziejaszka o wielkim sercu, który decyduje się mu pomóc.');

INSERT INTO FILM
VALUES (4, 'DEERSKIN', 'Komedia', 'Quentin Dupieux', 77, 2019,
        'Właściciel kurtki z jeleniej skóry postanawia dla niej unicestwić wszystkie inne kurtki świata.');

INSERT INTO FILM
VALUES (5, 'KOTY', 'Dramat / Fantasy / Musical', 'Tom Hooper', 106, 2019,
        'Porzucona przez swoich właścicieli, Victoria musi odnaleźć się w tanecznym świecie kotów.');


INSERT INTO SALA
VALUES (1, 400);

INSERT INTO SALA
VALUES (2, 300);

INSERT INTO SALA
VALUES (3, 100);


ALTER SESSION SET TIME_ZONE = '+1:0';
ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MON-YYYY HH24:MI:SS';


INSERT INTO SEANS
VALUES (1, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM'), 400, 1, 1);

INSERT INTO SEANS
VALUES (2, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM') + interval '2' day, 322, 2, 1);

INSERT INTO SEANS
VALUES (3, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM') + interval '3' day, 300, 3, 2);

INSERT INTO SEANS
VALUES (4, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM') + interval '4' day, 100, 4, 3);

INSERT INTO SEANS
VALUES (5, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM') + interval '4' day, 400, 5, 1);

INSERT INTO SEANS
VALUES (6, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM'), 300, 1, 2);

INSERT INTO SEANS
VALUES (7, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM') + interval '2' day, 300, 2, 2);

INSERT INTO SEANS
VALUES (8, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM') + interval '3' day, 1, 3, 3);

INSERT INTO SEANS
VALUES (9, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM') + interval '4' day, 100, 4, 1);

INSERT INTO SEANS
VALUES (10, TO_TIMESTAMP_TZ(CURRENT_TIMESTAMP, 'DD-MON-RR HH.MI.SSXFF PM TZH:TZM') + interval '4' day, 100, 5, 2);


INSERT INTO KLIENT
VALUES (1, 'EmanuelCzarnecki@gmail.com', '12');

INSERT INTO KLIENT
VALUES (2, 'OlgierdMichalak@gmail.com', '1234');


INSERT INTO ZAMOWIENIE
VALUES (1, 1, 1, 1);

COMMIT;
