# WP Plugin Weekend Adventure

WordPress plugin a **Hétvégi Kalandmentő** programjainak megjelenítésére.

A plugin egy shortcode-on keresztül jeleníti meg a programokat, az adatokat pedig egy saját WordPress REST API endpoint szolgáltatja. A programok státuszát PHP oldalon számítja ki a megadott referenciaidő alapján.

## Követelmények

- WordPress 6.0+
- PHP 8.1+
- jQuery (WordPress által biztosított)

## Installáció

1. Másold a `wp-plugin-weekend-adventure` könyvtárat a WordPress `wp-content/plugins/` könyvtárába, vagy töltsd fel a ZIP fájlt a WordPress adminfelületén.
2. Aktiváld a **WP Plugin Weekend Adventure** plugint a WordPress adminfelületén.
3. Helyezd el a következő shortcode-ot egy oldal vagy bejegyzés tartalmában:

```text
[hetvegi_kalandmento]
```

A plugin a `data/programs.json` fájlból tölti be a programadatokat.

## REST API

A plugin saját REST API endpointon keresztül kommunikál:

```text
GET /wp-json/wa/v1/programs
```

Az endpoint a programadatokat és a PHP oldalon kiszámított státuszt adja vissza.

Példa:

```json
{
    "id": 1,
    "title": "Pilisi családi panorámatúra",
    "location": "Dobogókő",
    "start_at": "2026. október 10. 09:00",
    "capacity": 20,
    "booked": 11,
    "difficulty": "könnyű",
    "price_huf": "4 900 Ft",
    "cancelled": false,
    "status": {
        "key": "available",
        "label": "Elérhető",
        "bookable": true
    }
}
```

Az üzleti logika jelenleg a REST API controller és a hozzá kapcsolódó program státuszkezelő osztály között oszlik meg. Ez tudatos döntés volt a feladat kis mérete és tesztfeladat jellege miatt.

Hosszú távú bővítés, éles vagy nagyobb scope esetében inkább `Repository → Service → Controller` réteget alkalmaznék, ahol a felelősségek egyértelműbben szétválaszthatóak.

A frontend ebben az esetben csak adatmegjelenítésre szolgál, az adatok formázása backend szinten történik. Így frontenden nincs szükség az üzleti logika vagy az adatformázás megkettőzésére, a JavaScript réteg feladata pedig elsősorban a megjelenítés és a felhasználói interakciók kezelése.

### Rendezés

A programok státusz alapján kerülnek rendezésre:

1. Elérhető
2. Kevés hely
3. Betelt
4. Törölve
5. Lejárt
6. Nem elérhető

A sorrend célja, hogy a felhasználó először az aktuálisan foglalható programokat lássa, ezen belül pedig előrébb kerüljenek azok, ahol már kevés szabad hely maradt. A nem foglalható programok a lista végére kerülnek.

Azonos státusz esetén a programok kezdési időpont szerint növekvő sorrendben jelennek meg.

## Ismert korlátok

- A programadatok jelenleg statikus JSON fájlból érkeznek.
- A foglalási folyamat csak a megjelenítés szintjén van jelen, tényleges foglalás nem történik.
- A programokhoz jelenleg nincs WordPress adminisztrációs felület.

## Mivel folytatnám a munkát

1. Általános észrevételek
   - Plugin nyelvesítése
   - User általi szűrés és rendezés bővítése, valamint modernebb szűrő alkalmazása: több listaelem kiválasztása, kereshető lista
   - Szűrés-alapú URL paraméterezés: megosztható link az aktuális szűréssel, valamint szerveroldali szűrés nagyobb adatmennyiség esetén
   - UI összehangolása a weboldal design rendszerével
   - Kontextustól és projektmérettől függően jQuery helyett más frontend stack használata

2. Backend architektúra kidolgozása
   - A plugin adatbázis szinten kezelné a programokat és foglalásokat
   - A programok admin felületen feltölthetőek, törölhetőek és szerkeszthetőek lennének
   - Foglalások kezelése adminisztrációs szinten

3. A teljes foglalási folyamat kidolgozása publikus / frontend oldalon is
   - Jelentkezési folyamat kialakítása UX szinten
   - E-mail értesítések küldése

## Ráfordított idő

A feladat elkészítésére körülbelül **3 órát** szántam, a README fájl megírásával.

## AI használat

A fejlesztés során ChatGPT-t használtam a WordPress-specifikus szintaxisok gyorsabb felidézésére, a CSS megírásához és a README fájl formázásához. A javasolt megoldásokat minden esetben ellenőriztem, és a feladat igényei szerint építettem be.