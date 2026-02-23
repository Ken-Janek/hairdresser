# Juuksuri Broneerimine - Esitamise Juhend

## ✅ Aplikatsioon on valmis ja täielikult funktsioneeriv!

### Käivitamine lokaalselt

```bash
cd /home/student/Documents/hairdresser-booking
php -S localhost:3000 -t public/
```

Avage brauseris: **http://localhost:3000**

### Funktsioonid (testitud ja töötav)

#### 1. Broneerimisleht (avalik)
- ✅ Kuupäevade valik
- ✅ Juuksuri valimine (Adele, Greta)  
- ✅ Teenuse valimine (Classic Cut, Wash & Style, Color Refresh)
- ✅ Ajaslottide kuvamine (09:00-17:00, 30-minutilised intervallid)
- ✅ Kliendiandmete sisestamine
- ✅ Kattuvate broneeringute blokkimine

#### 2. Kinnitusleht
- ✅ Broneeringu kokkuvõte kuvamine
- ✅ Andmete salvestamine andmebaasi

#### 3. Admini leht
- **URL**: http://localhost:3000/admin
- **Kasutaja**: `admin`
- **Parool**: `Passw0rd`
- ✅ Kõikide broneeringute loend
- ✅ Broneeringu tühistamise funktsioon

### Andmebaas

Lokaalne MySQL:
- **Host**: localhost
- **Kasutaja**: ken-janek
- **Parool**: Passw0rd
- **Andmebaas**: hairdresser_booking
- **Tabelid**: stylists, services, bookings

### Projekt strukuur

```
src/
├── Controllers/        → Broneeringu ja admini loogika
├── Models/            → Andmebaasimudelid
├── Services/          → Aja-slotsid teenus
├── Views/             → HTML vaated
└── Core/              → Router, Db, Auth, Config, View
public/
├── index.php          → Rakenduse sissekäik
├── styles.css         → Kujundus
└── .htaccess          → URL rewriting
db/
└── schema.sql         → Andmebaasi skeem
```

### Turbe funktsioonid

- ✅ Ettevalmistatud SQL-laused (SQL injection kaitse)
- ✅ HTML escaping (XSS kaitse)
- ✅ HTTP Basic Auth (admin leht)
- ✅ Andmebaasi taseme UNIQUE piirang (kattuvad broneeringud)
- ✅ Sisendandmete valideerimine

### MVC arhitektuur

- **Model**: BookingModel, StylistModel, ServiceModel - andmebaasi operatsioonid
- **View**: booking.php, admin.php, confirmation.php - kasutajaliides
- **Controller**: BookingController, AdminController - äriloogika
- **Service**: AvailabilityService - ajaslottide arvutamine

## 📋 Kiire test

1. **Broneerimine**:
   - Ava http://localhost:3000
   - Vali kuupäev, juuksur, teenus
   - Vali aeg ja sisesta nimi + email
   - Klikk "Confirm Booking"

2. **Admin**:
   - Ava http://localhost:3000/admin
   - Sisesta: `admin` / `Passw0rd`
   - Näe kõiki broneeringuid
   - Tühista soovitud broneering

## 🚀 Pärast esitamist

Rakendus on täielikult funktsioneeriv ja valmis hindamiseks.
Kõik nõuded on täidetud:
- ✅ Broneerimine funktsioon
- ✅ Admin paneeli authentitsimine  
- ✅ Andmebaasi operatsioonid
- ✅ Double-booking kaitse
- ✅ MVC arhitektuur
- ✅ Dokumentatsioon (DOCUMENTATION.md)

---

**Viimane versioon**: 23. veebruar 2026
**Serverit johtis**: PHP 8.3 localhost:3000
