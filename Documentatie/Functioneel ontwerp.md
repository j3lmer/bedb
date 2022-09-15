# Functioneel ontwerp

### Algemeen belangrijke bestandsstructuur:

assets
├── static
│   └── images **// Hier worden statische afbeeldingen bewaard.**
├── styles
│   └── app.css **// Applicatiebrede opmaak.**
└── vue
    ├── common **// Hier leven algemeen gebruikte js bestanden (Vue).**
    ├── fonts **// Hier leven fonts.**
    ├── pages **// Hier leven controller unieke Vue SPA's.**

src
├── Business **// Helper bestanden voor de controllers**
│   └── Security 
│       ├── EmailVerifier.php
│       └── EntryHelper.php
├── Controller **// hier leven de controllers om .**
├── Entity **// Database representatieve entiteiten.**
└── Repository **// Database repositories voor speciafieke entiteiten.**

migrations **// Hier leven de database migraties.**

node_modules **// hier leven externe NPM pakketten.**

templates **// hier leven controller unieke twig templates, gebruikt om de respectieve Vue SPA's te renderen.**

### Pagina rendering:

Voor unieke secties is er een geisoleerde controller om de pagina te renderen. 

- Er zal een Front/Homepage controller zijn welke de '/' route beheerd.

- Er zal een Entry controller zijn die de '/entry' /login', '/register' en '/verify/email' routes beheren.

- Er zal een Userlanding controller zijn die de accountpagina beheerd.

Wanneer iemand landt op een correcte en bestaande route wordt er een twig bestand laten zien aan de browser, in deze twig pagina injecteerd Vue zichzelf. Vue gebruikt Vuetify voor de opmaak van de pagina.

Externe logica wat niet te maken heeft met het renderen van het twig bestand wordt afgehandeld door een helper bestand wat zich bevindt in ../Business/vanaf de respectieve controller.

Per controller word er een SPA gerendered d.m.v. de volgende code in webpack.config.js

```js
const glob   = require('glob');
const entries = {};

glob.sync('./assets/vue/pages/**/app.js').forEach((path) => {
    const name = path.split('./assets/vue/pages/')[1].split('/app.js')[0];
    entries[name] = path;
});

for (let i = 0; i < Object.entries(entries).length; i += 1) {
    const [name, path] = Object.entries(entries)[i];
    Encore.addEntry(name, path);
}
```

elke Encore entry wordt zijn eigen SPA.

### Entiteiten:

Hier volgt een lijst van entiteiten (databasetabellen) en hun properties

#### User

- id (UUID) (generated value) (uniek) (verplicht)

- username (string) (uniek) (180 karakters) (verplicht)

- email (niet leeg) (string) (uniek) (180 karakters) (verplicht)

- roles (array) 

- password (string) (niet leeg) (180 karakters) (verplicht)

- isVerified (boolean) (verplicht)

- reviews (iterable) (one to many -> Reviews) 

#### Review

- id (int) (uniek) (verplicht) **// steam id**

- text (string) (1000 karakters)

- rating (int) (range tussen 0 en 10) (verplicht)

- image (file) (niet groter dan 8mb)

- date (datetime) (verplicht) **// date geupload of voor het laatstgewijzigd**

- user (many to one -> User) (verplicht)

- game (many to one -> Game) (verplicht)

#### SteamReview

- id (int) (uniek)

- hours (float) (verplicht)

- game (many to one -> Game) (verplicht)

- recommended (bool) (verplicht)

- text (string) (1000 karakters)

- username (string) (180 karakters) (verplicht)

- date (datetime) (verplicht)

#### Game

- id (int) (uniek) (verplicht) **// van steam**

- name (string) (100 karakters) (verplicht)

- detailed description (string) (1000 karakters)

- about (string) (1000 karakters)

- short_description (string) (500 karakters)

- supported_languages (string) (1000 karakters)

- header_image (string) (200 karakters) (verplicht)

- website (string) (500 karakters)

- pc_requirements (PcRequirements) (one to one) 

- developers (stqring) (100 karakters) (verplicht)

- publishers (string) (100 karakters) (verplicht)

- platforms (Platforms) (one to one) (verplicht)

- metacritic (Metacritic) (one to one) (verplicht)

- categories (iterable) (one to many)

- genres (iterable) (one to many)

- recommendations_total (int)

- screenshot (iterable) (one to many -> Screenshot)

- notes (string) (100 karakters)

#### PcRequirement

- minimum (string) (1000 karakters)

- recommended (string) (1000 karakters)

#### Platform

- windows (bool) (verplicht)

- mac (bool) (verplicht)

- linux (bool) (verplicht)

- game (Game) (one to one )

#### Metacritic

- score (string) (verplicht)

- url (string) (500 karakters) (verplicht)

- game (Game) (one to one -> Game)

#### Category

- id (int) (verplicht)

- description (string) (verplicht)

- game (Game) (many to one -> Game)

#### Genres

- id (int) (verplicht)

- description (string) (50 karakters)

- game (Game) (many to one -> game)

#### Screenshot

- id (int) (verplicht)

- thumbnail (string) 

- full (string)

- game (Game) (many to one -> game)

Entiteiten bevatten ook getters en setters voor elke respectieve property

### Paginaflow

Wanneer de gebruiker op de hoofd URL komt wordt hij gegroet met een homepagina, waarop er willekeurige / featured spellen worden weergegeven.

Er is een balk bovenin een scherm waar rechtsbovenin een klein hoofdje staat, als de gebruiker hier op klikt, word hij gegroet met een inlogscherm. Op deze pagina is er een knop die de gebruiker leidt naar een pagina waar hij een account kan aanmaken.

Wanneer er een account word aangemaakt word er een email verstuurd om je account te verifieren.

In het midden van het scherm zijn rijen aan spellen gesorteerd op genre / populariteit / score.

Wanneer de gebruiker op een spel klikt, opent er een pagina over het aangeklikte spel. Er is een of meer afbeeldingen van het spel. De metacritic score wordt duidelijk weergegeven. Er zijn 2 rijen aan reviews; een van steamgebruikers, en een van de gebruikers van de website.

Een gebruikersreview bestaat uit een rating van een cijfer tussen 1 en 10, een mogelijke bijlage, de datum geupload / gewijzigd, en text.

Een steam review bestaat uit een ja/nee waarde over of die gebruiker het spel aanraadt, hoeveel uren deze gebruiker in het spel heeft, de datum geupload / gewijzigd, en text.

Een gebruikersreview kan door een moderator of de gebruiker zelf worden verwijderd.

Een gebruikersreview kan door de gebruiker worden gewijzigd (text/media).

Een gebruikersreview kan worden gerapporteerd door een gebruiker.

Een steamreview kan door een moderator worden verwijderd.

Wanneer een gebruiker is ingelogd, en hij op het hoofdje rechtsbovenin de balk klikt, word hij geleid naar een accountpagina, hier kan hij zijn wachtwoord of gebruikersnaam veranderen, of zijn account verwijderen.

Ook ziet de gebruiker hier zijn reviewgeschiedenis.

Een administrator heeft op een gamepagina per comment de optie om deze te verwijderen.

Een administrator heeft op zijn accountpagina een overzicht van alle gerapporteerde reviews.
