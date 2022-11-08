CREATE DATABASE IF NOT EXISTS muzicka_prodavnica;
USE muzicka_prodavnica;

CREATE TABLE IF NOT EXISTS korisnik(
    id INT AUTO_INCREMENT PRIMARY KEY,
    korisnik_ime VARCHAR(40) NOT NULL,
    korisnik_prezime VARCHAR(40) NOT NULL,
    korisnik_telefon VARCHAR(40) NOT NULL UNIQUE,
    korisnik_grad VARCHAR(40) NOT NULL,
    korisnik_datum_rodjenja DATE NOT NULL,
    korisnik_adresa VARCHAR(40) NOT NULL,
    korisnik_zip VARCHAR(6) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS nalog_tip(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nalog_tip_naziv VARCHAR(12) NOT NULL UNIQUE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS nalog(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nalog_email VARCHAR(40) NOT NULL UNIQUE,
    nalog_password VARCHAR(255) NOT NULL UNIQUE,
    nalog_korisnik_id INT NOT NULL UNIQUE,
    nalog_korisnik_tip_id INT NOT NULL,
    FOREIGN KEY(nalog_korisnik_id) REFERENCES korisnik(id),
    FOREIGN KEY(nalog_korisnik_tip_id) REFERENCES nalog_tip(id)
) ENGINE = InnoDB;


INSERT INTO nalog_tip(nalog_tip_naziv) VALUES ("Admin"), ("Moderator"), ("Korisnik");

INSERT INTO korisnik(korisnik_ime, 
                     korisnik_prezime, 
                     korisnik_telefon, 
                     korisnik_grad, 
                     korisnik_datum_rodjenja, 
                     korisnik_adresa, 
                     korisnik_zip) VALUES ("Pera", "Peric", 9878798979, "Nis", "2022-4-13", "abc", "123");

INSERT INTO nalog(nalog_email, nalog_password, nalog_korisnik_id, nalog_korisnik_tip_id) VALUES ("Admin", "Admin", 1, 1);

CREATE TABLE IF NOT EXISTS narudzbina(
    id INT AUTO_INCREMENT PRIMARY KEY,
    narudzbina_broj INT NOT NULL,
    narudzbina_datum DATETIME NOT NULL,
    narudzbina_datum_slanja DATETIME NOT NULL,
    nalog_id INT NOT NULL,
    FOREIGN KEY(nalog_id) REFERENCES nalog(id)
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS proizvod(
    id INT AUTO_INCREMENT PRIMARY KEY,
    proizvod_proizvodjac VARCHAR(40) NOT NULL,
    proizvod_ime VARCHAR(40) NOT NULL,
    proizvod_opis TEXT NOT NULL,
    proizvod_cena INT NOT NULL,
    proizvod_slika VARCHAR(40) NOT NULL,
    proizvod_tip VARCHAR(40) NOT NULL,
    proizvod_in_stock INT NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS narudzbina_detalji(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_proizvod INT NOT NULL,
    narudzbina_id INT NOT NULL,
    FOREIGN KEY(id_proizvod) REFERENCES proizvod(id),
    FOREIGN KEY(narudzbina_id) REFERENCES narudzbina(id)
) ENGINE = InnoDB;

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES ("Fender", "Fender Stratocaster Jimmie Vaughan", 
"Model Name:	Jimmie Vaughan Tex-Mex™ Strat®
Model Number:	013-9202-(Color #)
Series:	Artist Series
Colors:	(303) 2-Color Sunburst,
(305) Olympic White,
(306) Black,
(309) Candy Apple Red,
(Polyester Finish)
Body:	Alder
Neck:	1-Piece Maple, Soft 'V' Shape,
(Satin Urethane Finish)
Fingerboard:	Maple, 9.5 Radius (241 mm)
No. of Frets:	21 Medium Jumbo Frets
Pickups:	2 Tex-Mex™ Calibrated, Over-Wound, Single-Coil Strat® Pickups (Neck and Middle),
1 Tex-Mex Hot Bridge Single-Coil Strat® Pickup (Bridge)
Controls:	Master Volume,
Tone 1. (Neck Pickup),
Tone 2. (Bridge Pickup)
(Middle Pickup is Wide Open, No Tone Control)
Pickup Switching:	5-Position Blade:
Position 1. Bridge Pickup
Position 2. Bridge and Middle Pickup
Position 3. Middle Pickup
Position 4. Middle and Neck Pickup
Position 5. Neck Pickup
Bridge:	American Vintage Synchronized Tremolo
Machine Heads:	Fender®/Gotoh® Vintage Style Tuning Machines
Hardware:	Nickel/Chrome
Pickguard:	1-Ply White, (8 Hole)
Scale Length:	25.5 (648 mm)
Width at Nut:	1.650 (42 mm)
Unique Features:	Special Soft 'V' Shape Neck,
Fender®/Schaller® Straplock Strap Buttons,
Middle Pickup is 'Wide Open' With No Tone Control,
Jimmy Vaughan Signature on Back of Headstock,
U.S. Made Electronic Components
Strings:	Fender Super 250L, Nickel Plated Steel,
Gauges: (.009, .011, .016, .024, .032, .042),
P/N 073-0250-003
Accessories:	Deluxe Gig Bag
Case:	Deluxe Gig Bag, P/N 0991512000",
163000,
"fender_jimmi_vaughn.jpg",
"gitara",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES ("Fender", 
"Fender Stratocaster John Mayer", 
"Model Name:	John Mayer Stratocaster®
Model Number:	011-9700-(Color #),
Series:	Artist Series
Colors:	(800) 3-Color Sunburst,
(805) Olympic White,
(Urethane Finish)
Body:	Select Alder
Neck:	Maple, Thick C Shape,
(Satin Urethane Finish on Back of Neck, Gloss Finish on the Face of the Headstock)
Fingerboard:	African Rosewood, 9.5 Radius (241 mm)
No. of Frets:	21 6105 Narrow Jumbo Frets
Pickups:	3 Big Dipper™ Single-Coil Strat® Pickups with Special Scooped,
  Mid-range Voicing to Meet John Mayer's Specifcations
Controls:	Master Volume, 
Tone 1. (Neck Pickup), 
Tone 2. (Bridge/Middle Pickup)
Pickup Switching:	5-Position Blade:
Position 1. Bridge Pickup
Position 2. Bridge and Middle Pickup 
Position 3. Middle Pickup 
Position 4. Middle and Neck Pickup
Position 5. Neck Pickup
Bridge:	American Vintage Synchronized Tremolo
Machine Heads:	Fender®/Gotoh® Vintage Style Tuning Machines
Hardware:	Nickel/Chrome
Pickguard:	4-Ply Tortoise Shell
Scale Length:	25.5 (648 mm)
Width at Nut:	1.650 (42 mm)
Unique Features:	Thick C-Shape Neck, 
Vintage Hardware,
Five Tremolo Springs installed at Factory,
Per the Artist's Request, the Back Tremolo Cover Plate is Not Installed nor Included with the Guitar,
String Tree is Farther from the Nut,
3 Big Dipper™ Single-Coil Strat Pickups with Special Scooped,
  Mid-range Voicing to Meet John Mayer's Specifcations,
'50s Spaghetti Logo Decal,
Satin Urethane Finish on Back of Neck, Gloss Finish on the Face of the Headstock
Strings:	Fender Standard Tension™ ST250R, Nickel Plated Steel, 
Gauges: (.010, .013, .017, .026, .036, .046),
P/N 0730250206
Accessories:	INCASE® Gig Bag , Strap, Cable
Case:	INCASE® Gig Bag",
123000,
"fender_jimmi_john_mayer.jpg",
"gitara",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES ("Marshall", " Marshall DSL20CR", "
Model - DSL20C

Linija DSL

Tehnologija - lampaska

Kanali

2 (Split) „Ultra gain“ i „klasicni gain

ELEKTRONIKA

Izlazna snaga

20W (sa opcijom smanjenja snage, 10W)

Izlazi - Izlazi iz zvucnika: 3 x 1/4 uticnice (prikljucak 16 / opterećenje 8), 1 k 3,5 mm emulirani linijski izlaz

Ulazi 1 x 1/4 prikljucak za instrument, 1 k 3,5 mm prikljucak Aux ulaz, pedala

Kontrole

Klasicni gain kanal (gain, jacina zvuka), odabir kanala, ultra gain kanal (pojacavanje jacine zvuka),
ton shift taster, visoki tonovi, srednji, bas, prisustvo, rezonancija, reverb, opcija male snage (sa zadnje strane)

Efekti

Digitalni reverb

Loop efekata

Da, serija, send/return

ZVUČNICI

Konfiguracija zvucnika - 1x12 

Model zvucnika

Zvucnik Celestion Seventy 80 (16, 80v)

Jedinica impedanse - 16

LAMPE

Lampe  pretpojacala

3 x ECC83

Lampe pojacala

2 x EL34

OPREMA

Pedala

Ukljucen PEDL-90012

Kabl

Odvojivi kabl za napajanje ukljucen

DIMENZIJE 

Tezina 16,3 kg

Širina 500 mm

Visina 420 mm

Dubina 250 mm",
25000,
"dsl20cr.jpg",
"gitarapojacalo",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES ("Fender", "Fender Super Champ", 
"Karakteristike:
Output Power / Output: 15 Watts
Controls: Volume 1, Channel Select, Gain, Volume 2, Voice, Treble, Bass, F/X Adjust, F/X Select, Tap
Amp Type: Tube
Preamp Tubes: 1 x 12AX7
Power Tubes: 2 x 6V6
Speaker: One - 10 Fender Special Design
Unique Features: 
Voicing knob with 16 different amp types, 
two channels with channel-switching format (optional footswitch available, P/N 0071359000), 
15 effects with effects adjust control, T
AP tempo control for delay time/modulation rate adjustments, 
external speaker capability, 1/`` line output", 50000, "fender_pojacalo.jpg", "gitarapojacalo", 1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES ("Gibson", "Gibson LP signature T Gold",
"Karakteristike:
Ebony Finish

Maple Top

Traditional Weight Relief Mahogany Body

Mahogany Neck

Rosewood Fretboard

60's-Style Slim Taper Neck Profile

22 Medium Jumbo Frets

24-3/4 Scale Length

12 Fretboard Radius

Traditional Trapezoid Inlays

Open-Coil '57 Classic and '57 Classic Plus Humbuckers

Individual Volume and Tone Controls

Push/Pull Coil Tap Tone Controls

3-Way Selector Switch

Tune-O-Matic Bridge

Stopbar Tailpiece

Grover Locking Tuners

Gold Hardware",
200000,
"gibson_gitara.jpg",
"gitara",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES 
("Ibanez", 
 "Ibanez RG350DXZ-BK",
 "Karakteristike:
Body: Mahogany
3-Piece Wizard III maple neck
Fretboard: Rosewood
24 Jumbo frets
Nut width: 43 mm
Scale: 648 mm
Pickups: 2 Ibanez QM humbuckers and 1 thomann Ibanez QM single coil
Edge Zero II tremolo
Colour: Black",
63000,
"ibanez_gitara_prodaja.jpg",
"gitara",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES 
("Fender", 
 "Fender Geddy Lee Jazz",
 "Karakteristike:
Model Name:	Geddy Lee Jazz Bass®
Model Number:	025-7702-306
Series:	Artist Series
Colors:	(300) 3-Color Sunburst,
(306) Black,
(Urethane Finish)
Body:	Alder
Neck:	1-Piece Maple, Thin C Shape,
(Gloss Urethane Finish)
Fingerboard:	Maple, 9.5 Radius (241 mm)
No. of Frets:	20 Medium Jumbo Frets
Pickups:	2 Vintage Jazz Bass Single-Coil Pickups
Controls:	Volume 1. (Neck Pickup),
Volume 2. (Bridge Pickup),
Master Tone
Pickup Switching:	None
Bridge:	Leo Quan Badass® II
Machine Heads:	Vintage 70s 'Fender' Stamped Open Gear Tuning Machines
Hardware:	Chrome
Pickguard:	3-Ply White on (306) Black, 3-Ply Black on (300) 3-Color Sunburst
Scale Length:	34 (863.6 mm)
Width at Nut:	1.50 (38 mm)
Unique Features: Black Fingerboard Binding with Black Rectangular Shaped Position Markers,
Slim Neck Profile,
Leo Quan Badass® II Bridge
Strings:	Super 7250M, NPS,
Gauges: (.045, .065, .085, .105),
P/N 073-7250-006
Accessories:	Deluxe Gig Bag
Case:	Includes Deluxe Gig Bag P/N 0069882000
Introduced:	1998
Notice:	Product Prices, 
Features, Specifications and Availability Are Subject To Change Without Notice",
160000,
"fender_bass_gitara.jpg",
"bassgitara",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES 
("Fender", 
 "Fender RUMBLE™ STUDIO 40",
 "Karakteristike:
40-watt WiFi-equipped digital bass amplifier
One 10'' Fender Special Design speaker; compression tweeter
Free, exclusive Tone App for one-touch preset access, management and editing
New models and effects with “spillover”
Bluetooth audio streaming and control
Stereo send and return; AUX input; XLR line outputs; USB output; headphone output
Onboard 60-second looper; setlist support
Optional MGT-4 footswitch for control of presets and effects, tuner and looper access",
54000,
"fender_bass_pojacalo.jpg",
"basspojacalo",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES 
("Yamaha", 
 "Yamaha Rydeen Black Glitter",
 "Karakteristike:
22x17 Bass drum
10x07 Tom 
12x08 Tom 
16x15 Floor tom 
14x 5,5 Snare dru
dware Pack HW680W
FP7210A Bass drum pedal
HS650WA Hi hat pedal
SS650WA Snare stand
(x2) CS665A Stands de cymbale droit
hnical specifications : 
Poplar shells - 6 ply (7,2mm) 
Triple Flange hoops (Steel 1.5mm)
New Badge
Standard suspension system
Clamp CL940LB x 2
Lugs Separate lug
Head Top snare Drum : Coated
Head Tom tom and floor tom : Clear
Bottom head Snare drum : thin S-side clear 
Bottom head tom tom and Floor tom : Clear
Front Bass drum : Ebony w/ring mute +Yamaha logo
Batter Bass drum : Clear w/ring mute",
74000,
"yamaha_bubanj_set.jpg",
"bubnjevi",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES 
("Yamaha", 
 "Yamaha MX49II",
 "Karakteristike:
Lightweight 49-key (3.7 kg) standard synthesizer keyboard
Available in the colours black, blue, and white: 
Blue and black available from end of July 2016, 
white is a limited edition that is available from October 2016
Core-compliant advanced USB Audio/MIDI integration
 in computer music production systems and direct connection to iOS devices
Unlocks full version of the „FM Essential“ synth app (4-Operator FM synthesis)
Over 1,000 Voices using Waveforms from the MOTIF XS
128 note polyphony, 16 part multitimbral, VCM effects
4 part real-time knob controller with assignable function
WAV, AIFF, SMF playback from USB memory
Bundled with Steinberg Cubase AI DAW software",
64000,
"yamaha_klavijatura.jpg",
"klavijature",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES 
("Korg", 
 "Korg Krome EX",
 "Karakteristike:
Lightly weighted 'semi-weighted' keyboard with touch dynamics
Sound generation: EDS-X synthesis
Uncut and unlooped piano sounds and drum sounds on Kronos basis
8-Step velocity switching of the e-piano sounds
120 Voices polyphonic
Library with 4 GB PCM data
800 x 480 pixels 7'' TouchView colour display
80 Studio-quality drum kits
Drum track with realistic and inspiring grooves
896 Programs and 512 Combinations
Effects section with 5 inserts,
2 masters and 1 total FX plus EQ per track/timbre
Joystick
4 Knobs
16-Track MIDI sequencer
Arpeggiator
Housing with aluminium surface
USB port for computer plus SD card slot for data storage
Stereo line output (2x 6.3 mm jack)
Headphone output (3.5 mm stereo jack)
Damper pedal connection (6.3 mm jack, half damper supported)
Assignable switch (6.3 mm jack)
Assignable pedal (6.3 mm jack)
MIDI In / Out
USB
SD-Card Slot (SDXC Memory Card is not supported)
Dimensions: 1027 x 313 x 93 mm
Weight: 7.2 kg",
100000,
"korg_klavijatura.jpg",
"klavijature",
1);

INSERT INTO proizvod(proizvod_proizvodjac, proizvod_ime, proizvod_opis, proizvod_cena, proizvod_slika, proizvod_tip, proizvod_in_stock) VALUES 
("Yamaha", 
 "Yamaha A3M Natural",
 "Karakteristike:
 Top 	Solid Sitka Spruce
Back 	Solid Mahogany
Side 	Solid Mahogany
Neck 	Mahogany
Fingerboard 	Ebony
Bridge 	Ebony
Nut Width 	43mm
String Length 	650mm
Tuners 	Die-Cast Chrome
Preamp 	System 63 SRT
",
80000,
"yamaha_akusticna.jpg",
"akusticnagitara",
1);