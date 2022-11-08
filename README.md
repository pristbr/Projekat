# Muzicka Prodavnica

Jednostavna aplikacija za kupovinu muzickih instrumenata

---

# Tipovi korisnika 

## Admin

Moze dodati ili izbrisati menadzere

Dodati, izbrisati ili promeniti neki artikl

Videti svi korisnici i menadzeri, nakon klika na kartici vidi puni detalji o korisniku

Takodje moze videti istoriju kupovine nekog korisnika i da dobije izvestaj u obliku PDF ili Excel

---

## Menadzer

Dodati, izbrisati ili promeniti neki artikl

Videti svi korisnici nakon klika na kartici vidi puni detalji o korisniku

Takodje moze videti istoriju kupovine nekog korisnika i da potvrdi narudzbinu

---

## Korisnik 
Moze se **registrovati**

Poruciti neki artikl iz prodavnice ako je na stanju taj artikl

---

# Registracija

Registraciju korisnika ili menadzera se vrsi tako sto se sva polja moraju prvo uneti

U slucaju greske forma prikazi gde je bila greska

---

# Dodavanje novih artikla

Dodavanjem novih artikla moraju se uneti sva polja
Postoje razliciti tipovi instrumenata
- Gitara
- Pojacalo za gitaru
- Bass gitara
- Pojacalo za bass gitaru
- Klavijature 
- Bubnjevi
- Akusticne gitare

**Polje za sliku mora imati JPG ili PNG ekstenziju**

---

# Kratko tehnicko upustvo
- Koristi MVC dizajn
- Dodatne komponente 
    - Twig ( Za templating )
    - Monolog ( Za kreiranje log fajlova )

>Sve rute se nalaze u config/routes.json

>Podesavanja app-a u config/app.json

Dodavanjem slike proizvodjaca (npr. Fender) a u tim direktorijuma imamo sve artikle sto imaju (gitara, bassgitara, itd... ) i u njima se nalaze slike. 

U views se nalaze svi potrebni twig views.

Tokom dodavanje nove slike potrebne je uci u direktorijum proizvodjaca pa onda u tip instrumenata i tu dodati sliku.

---

## Upustvo

1. Kreirati bazu sa nazivom muzickaprodavnica
2. U fajlu database_dump.sql se nalaze sve potrebne tabele za inicijalni unos u bazu, po redu uneti
    - Korisnik
    - Nalog
    - Nalog_tip
nakon toga uneti vrednosti u Nalog_tip tabeli
3. Dodati admina
4. Dodati tabele
    - Narudzbina
    - Proizvod
    - Narudzbina_detalji
nakon cega mogu se dodati sve ostale tabele

## Pokretanje

U root ( MuzickaProdavnica/ ) pokrenuti komandu php -S localhost:8000


