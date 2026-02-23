# Juuksuri broneerimine (PHP MVC)

Minimaalne broneerimissusteem avaliku broneerimisvaatega ja kaitstud admini vaatega. Tehtud PHP-ga lihtsas MVC struktuuris (kontrollerid, mudelid, vaated).

## Funktsioonid

### Avalik broneerimisvaade
- Kuupäeva ja juuksuri valik
- Teenuse valimine
- Ajaslottide kuvamine valitud kuupäevale ja juuksurile
- Klientandmete sisestamine ja broneeringu kinnitamine

### Kinnitusvaade
- Broneeringu kokkuvõte
- Juhendatud tagasi broneerimise vaatele

### Admini vaade
- Broneeringute loend filtreerituna kuupäeva järgi
- Iga broneeringu andmed (aeg, klient, juuksur, teenus, kontakt)
- Broneeringu tühistamise funktsioon
- Kaitstud HTTP Basic Auth-iga

## Paigaldus (kohalik)

1. Loo andmebaas ja tabelid:

```sql
CREATE DATABASE hairdresser_booking;
USE hairdresser_booking;
SOURCE db/schema.sql;
```

Kui kasutasid varem vana skeemi, loo andmebaas uuesti, et `stylist_id` ja `stylists` oleksid olemas.

2. Loo `.env` fail `.env.example` alusel ja seadista DB ning admini kasutaja.

3. Käivita PHP sisseehitatud server:

```bash
php -S localhost:3000 -t public
```

Ava http://localhost:3000 broneerimiseks ja http://localhost:3000/admin admini vaateks.

## Märkused

- Fikseeritud ajad: esmaspaevast reedeni, 09:00-17:00.
- Andmebaasis on kaks naidisjuuksurit.
- Kattuvad broneeringud on blokeeritud rakenduses ja andmebaasi tasemel (sama juuksur ja algusaeg).

## MVC struktuur

- Kontrollerid: paringute kaivitamine (nt [src/Controllers](src/Controllers))
- Mudelid: andmebaasiparigud (nt [src/Models](src/Models))
- Vaated: kasutajaliides (nt [src/Views](src/Views))

## Kliendi-serveri ylevaade

Brauseris tehtud tegevused saadavad HTTP paringud PHP serverile. Router seob paringu kontrolleriga, kontroller kasutab mudeleid lugemiseks/kirjutamiseks ja server tagastab vaate (HTML).

## Turvalisus

Peamised riskid ja lahendused:

- SQL injection: koik DB paringud on ettevalmistatud (prepared statements).
- XSS: vaadetes kasutatakse escape helperit.
- Admini ligipaas: admini route'id on kaitstud HTTP Basic Auth-iga `.env` parameetrite alusel.
- Vigane sisend: serveripoolel on kontroll kohustuslike valjade, kuupaeva ja e-posti formaadi jaoks.

## Koodistiil

Kood jargib PSR-12 stiili ideed: 4 taini, klass uhes failis, selge nahtavus, jarjepidev nimetus.

## Käivitamine (Arendusel)

Projektis on konfigureeritud PHP sisseehitatud server, mis jookseb localhost:3000 peal.

### Käivitamine VS Code kaudu

Vasakul küljes Activity Baris klõpsa "Run" ikoonile (või vajuta Ctrl+Shift+D) ja klõpsa "Run PHP Server" ülesande juures play-nupul.

Siis ava brauseris:
- Broneerimine: http://localhost:3000
- Admin: http://localhost:3000/admin (Kasutaja: `admin`, Parool: `Passw0rd`)

### Käivitamine terminalis

```bash
php -S localhost:3000 -t public
```

### Avalikel keskkondadele (Production)

Projekti juurutamiseks avalikule serverile:

1. Paigalda PHP 7.4+ ja MySQL 5.7+ toetuv veebimajutus
2. Laadi failid serverisse FTP/SFTP kaudu
3. Seadista `.env` fail serveris olevatele andmebaasi ning muude parameetritele
4. Konfigureeri veebiserveris URL rewriting `.htaccess` abil (kui kasutad Apache'd):

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*)$ /index.php?url=$1 [QSA,L]
</IfModule>
```

Näited turvaliste majutuskeskkondade kohta: Heroku, Railway, Vercel (PHP tugi), DreamHost, Bluehost jne.

**Märge:** Praegu on projekt ainult arenduskeskkonnale konfigureeritud. Tootmiskeskkonnal tuleks kasutada HTTPS-i ja turvalisemaid paroolisid `.env` failis.
