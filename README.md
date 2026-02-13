# Juuksuri broneerimine (PHP MVC)

Minimaalne broneerimissusteem avaliku broneerimisvaatega ja kaitstud admini vaatega. Tehtud PHP-ga lihtsas MVC struktuuris (kontrollerid, mudelid, vaated).

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

## Deploy (taida esitamiseks)

- Public URL: TODO
- Hosting environment: TODO
