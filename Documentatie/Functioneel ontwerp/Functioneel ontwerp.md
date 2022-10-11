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

---

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

elke Encore entry is zijn eigen SPA.

---

### Entiteiten:

Hier volgt een lijst van entiteiten (database-tabellen) en hun properties

#### User

- id (int) (generated value) (uniek) (verplicht)

- username (string) (uniek) (255 karakters) (verplicht)

- email (niet leeg) (string) (uniek) (255 karakters) (verplicht)

- roles (array) 

- password (string) (niet leeg) (255 karakters) (verplicht)

- isVerified (boolean) (verplicht)

- reviews (iterable) (one to many -> Reviews) 

#### Review

- id (int) (uniek) (verplicht) 

- text (string) (8000 karakters)

- rating (int) (range tussen 1 en 10) (verplicht)

- image (file) (niet groter dan 8mb) (min 200 breed) (max 1080 breed) (min 200 hoog) (max 1920 hoog)

- date (datetime) (verplicht) **// date geupload of voor het laatstgewijzigd**

- owner (many to one -> User) (verplicht)

- game (many to one -> Game) (verplicht)

#### SteamReview

- id (int) (uniek) **// steam id**

- hours (float) (verplicht) (verplicht)

- game (many to one -> Game) (verplicht) (verplicht)

- recommended (bool) (verplicht)

- text (string) (8000 karakters)

- username (string) (255 karakters) (verplicht)

- date (datetime) (verplicht)

#### Game

- id (int) (uniek) (verplicht) **// van steam**

- developers (array)

- publishers (array)

- name (string) (255 karakters) (verplicht)

- detailed description (string) (8000 karakters)

- about (string) (8000 karakters)

- short_description (string) (500 karakters)

- supported_languages (string) (1000 karakters)

- header_image (string) (255 karakters)

- website (string) (255 karakters)

- recommendations_total (int)

- notes (string) (255 karakters)

- nsfw (bool) (verplicht)

Alle relaties zijn (cascade: persist, remove) (orphanremoval: true)

- pc_requirements (PcRequirements) (one to one -> PcRequirements)

- platforms (Platforms) (one to one -> Platform) (verplicht)

- metacritic (Metacritic) (one to one -> Metacritic) (verplicht)

- release_date (ReleaseDate) (one to one -> ReleaseDate)

- categories (iterable) (many to many -> Category)

- genres (iterable) (many to many -> Genre)

- screenshot (iterable) (one to many -> Screenshot) (niet null)
  
  #### PcRequirement

- minimum (string) (1000 karakters)

- recommended (string) (1000 karakters)

- game (one to one -> Game) (verplicht)

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

- description (string) (255 karakters)

- game (Game) (many to one -> game)

#### Screenshot

- id (int) (verplicht)

- thumbnail (string) 

- full (string)

- game (Game) (many to one -> game) (niet null)

### ReleaseDate

- coming_soon (bool) (verplicht)

- date (string) 

- game (Game) (one to one -> Game) (verplicht)

Entiteiten bevatten ook getters en setters, soms adders en removers voor elke respectieve property

---

### Paginaflow

Wanneer de gebruiker op de hoofd URL komt wordt hij gegroet met een homepagina, waarop er willekeurige / featured spellen worden weergegeven.

Er is een balk bovenin een scherm waar rechtsbovenin een klein hoofdje staat, als de gebruiker hier op klikt, word hij gegroet met een inlogscherm. Op deze pagina is er een knop die de gebruiker leidt naar een pagina waar hij een account kan aanmaken.

Wanneer er een account word aangemaakt word er een email verstuurd om je account te verifieren.

Wanneer het de gebruiker niet lukt om in te loggen / een account aan te maken, krijgt hij een kleine notificatie in het venster dat het niet is gelukt.

Wanneer een gebruiker een account aanmaakt, zal hij een email ontvangen, hierzin zal een link zitten waarmee het account word geverifieerd. Nadat dit is gebeurd kan de gebruiker inloggen.

---

Wanneer de gebruiker op een spel klikt, opent er een pagina over het aangeklikte spel. Er is een of meer afbeeldingen van het spel. De metacritic score wordt duidelijk weergegeven. Er zijn 2 rijen aan reviews; een van steamgebruikers, en een van de gebruikers van de website.

Een gebruikersreview bestaat uit een rating van een cijfer tussen 1 en 10, een mogelijke bijlage, de datum geupload / gewijzigd, en text.

Een steam review bestaat uit een ja/nee waarde over of die gebruiker het spel aanraadt, hoeveel uren deze gebruiker in het spel heeft, de datum geupload / gewijzigd, en text.

Een gebruikersreview kan door een moderator of de gebruiker zelf worden verwijderd.

    Notitie: Er is een "Weet je het zeker" type popup wanneer dit word geprobeerd voordat het daadwerkelijk verwijderd word.

Een gebruikersreview kan door de gebruiker worden gewijzigd (text/media).

Een gebruikersreview kan worden gerapporteerd door een gebruiker.

Een steamreview kan door een moderator worden verwijderd.

---

Een game kan nog niet uit zijn en wel al op de website zijn, wanneer de game nog niet uit is, kunnen gebruikers ook geen review plaatsen.

---

Wanneer een gebruiker is ingelogd, en hij op het hoofdje rechtsbovenin de balk klikt, word hij geleid naar een accountpagina, hier kan hij zijn wachtwoord of gebruikersnaam veranderen, of zijn account verwijderen.

Ook ziet de gebruiker hier zijn reviewgeschiedenis.

---

Een administrator heeft op een gamepagina per comment de optie om deze te verwijderen.

Een administrator heeft op zijn accountpagina een overzicht van alle gerapporteerde reviews.

Notitie: bij alle invoervelden word er een kleine notificatie weergegeven wanneer er verplichte velden niet worden ingevoerd.

---

## Steam schrapen

Er komt een systeem waarbij [deze](https://api.steampowered.com/ISteamApps/GetAppList/v0002/?key=STEAMKEY&format=json) json wordt uitgelezen.

Deze json gaat doorheen gelooped worden, en voor elke appid wordt de app details api van steam bekeken, ([voorbeeld](https://store.steampowered.com/api/appdetails?appids=10)). Hier wordt gekeken of de software van type `"game"` is. Mocht dit niet zo zijn, dan wordt deze loop iteratie overgeslagen.

Voorbeeld:

```json
{
  "252950": {
    "success": true,
    "data": {
      "type": "game",
      "name": "Rocket League®",
      "steam_appid": 252950,
      "required_age": 0,
      "is_free": false,
      "controller_support": "full",
      "detailed_description": "Rocket League is a high-powered hybrid of arcade-style soccer and vehicular mayhem with easy-to-understand controls and fluid, physics-driven competition. Rocket League includes casual and competitive Online Matches, a fully-featured offline Season Mode, special “Mutators” that let you change the rules entirely, hockey and basketball-inspired Extra Modes, and more than 500 trillion possible cosmetic customization combinations.<br><br>Winner or nominee of more than 150 “Game of the Year” awards, Rocket League is one of the most critically-acclaimed sports games of all time. Boasting a community of more than 57 million players, Rocket League features ongoing free and paid updates, including new DLCs, content packs, features, modes and arenas.<br><br><strong>What's New:</strong><br><ul class=\"bb_ul\"><li>Rocket Pass - Purchase Rocket Pass Premium to get an initial 50% XP bonus and earn up to 70 unique rewards, including a new Battle-Car, Goal Explosion, Keys, and more!<br></li><li>Challenge System - Play Online Matches and complete Weekly Challenges to tier up and unlock unique rewards only found in Rocket Pass.<br></li><li>Esports Shop - Show off your team pride for your favorite teams in Rocket League Esports! The Rocket League Esports Shop pilot program brings new Decals, Wheels, and Player Banners that represent some of the best teams in the sport. of the best teams in the sport. <br><br><strong>SteamOS and Mac Beta Versions</strong><br>As we continue to upgrade <strong>Rocket League®</strong> with new technologies like DirectX 11 and a 64-bit client, it is no longer viable for us to maintain support for the macOS and Linux (SteamOS) platforms. As a result, the final patch for the macOS and Linux versions of Rocket League was released on March 10, 2020. This update disabled online functionality (such as Casual and Competitive Playlists) for players on macOS and Linux, but offline features including Local Matches, and splitscreen play are still accessible.<br><br>Please note that Rocket League® on SteamOS and macOS may have bugs and stability issues not seen in the Windows version of the game, and these issues may not be fixed in future updates.<br><br><i>NOTE: Because of agreements with our online service provider, there are certain regions that are unable to connect to Rocket League®’s online multiplayer component. As a result, server access is restricted in China, Crimea, Cuba, Iran, North Korea, Sudan, and Syria. Apologies to our customers in those regions.</i><br><br>Software and online features are subject to license, terms of use, and privacy policy ( <a href=\"https://steamcommunity.com/linkfilter/?url=http://rocketleague.com/eula\" target=\"_blank\" rel=\"noopener\"  >rocketleague.com/eula</a> , <a href=\"https://steamcommunity.com/linkfilter/?url=http://rocketleague.com/tou\" target=\"_blank\" rel=\"noopener\"  >rocketleague.com/tou</a> ,  and <a href=\"https://steamcommunity.com/linkfilter/?url=http://rocketleague.com/privacy\" target=\"_blank\" rel=\"noopener\"  >rocketleague.com/privacy</a> ).</li></ul>",
      "about_the_game": "Rocket League is a high-powered hybrid of arcade-style soccer and vehicular mayhem with easy-to-understand controls and fluid, physics-driven competition. Rocket League includes casual and competitive Online Matches, a fully-featured offline Season Mode, special “Mutators” that let you change the rules entirely, hockey and basketball-inspired Extra Modes, and more than 500 trillion possible cosmetic customization combinations.<br><br>Winner or nominee of more than 150 “Game of the Year” awards, Rocket League is one of the most critically-acclaimed sports games of all time. Boasting a community of more than 57 million players, Rocket League features ongoing free and paid updates, including new DLCs, content packs, features, modes and arenas.<br><br><strong>What's New:</strong><br><ul class=\"bb_ul\"><li>Rocket Pass - Purchase Rocket Pass Premium to get an initial 50% XP bonus and earn up to 70 unique rewards, including a new Battle-Car, Goal Explosion, Keys, and more!<br></li><li>Challenge System - Play Online Matches and complete Weekly Challenges to tier up and unlock unique rewards only found in Rocket Pass.<br></li><li>Esports Shop - Show off your team pride for your favorite teams in Rocket League Esports! The Rocket League Esports Shop pilot program brings new Decals, Wheels, and Player Banners that represent some of the best teams in the sport. of the best teams in the sport. <br><br><strong>SteamOS and Mac Beta Versions</strong><br>As we continue to upgrade <strong>Rocket League®</strong> with new technologies like DirectX 11 and a 64-bit client, it is no longer viable for us to maintain support for the macOS and Linux (SteamOS) platforms. As a result, the final patch for the macOS and Linux versions of Rocket League was released on March 10, 2020. This update disabled online functionality (such as Casual and Competitive Playlists) for players on macOS and Linux, but offline features including Local Matches, and splitscreen play are still accessible.<br><br>Please note that Rocket League® on SteamOS and macOS may have bugs and stability issues not seen in the Windows version of the game, and these issues may not be fixed in future updates.<br><br><i>NOTE: Because of agreements with our online service provider, there are certain regions that are unable to connect to Rocket League®’s online multiplayer component. As a result, server access is restricted in China, Crimea, Cuba, Iran, North Korea, Sudan, and Syria. Apologies to our customers in those regions.</i><br><br>Software and online features are subject to license, terms of use, and privacy policy ( <a href=\"https://steamcommunity.com/linkfilter/?url=http://rocketleague.com/eula\" target=\"_blank\" rel=\"noopener\"  >rocketleague.com/eula</a> , <a href=\"https://steamcommunity.com/linkfilter/?url=http://rocketleague.com/tou\" target=\"_blank\" rel=\"noopener\"  >rocketleague.com/tou</a> ,  and <a href=\"https://steamcommunity.com/linkfilter/?url=http://rocketleague.com/privacy\" target=\"_blank\" rel=\"noopener\"  >rocketleague.com/privacy</a> ).</li></ul>",
      "short_description": "Rocket League is a high-powered hybrid of arcade-style soccer and vehicular mayhem with easy-to-understand controls and fluid, physics-driven competition. Rocket League includes casual and competitive Online Matches, a fully-featured offline Season Mode, special “Mutators” that let you change the rules entirely, hockey and...",
      "supported_languages": "English, French, Italian, German, Spanish - Spain, Dutch, Portuguese - Portugal, Japanese, Korean, Russian, Turkish, Polish",
      "header_image": "https://cdn.akamai.steamstatic.com/steam/apps/252950/header.jpg?t=1662568128",
      "website": "http://www.rocketleague.com/",
      "pc_requirements": {
        "minimum": "<strong>Minimum:</strong><br><ul class=\"bb_ul\"><li><strong>OS:</strong> Windows 7 (64 bit) or Newer (64 bit) Windows OS<br></li><li><strong>Processor:</strong> 2.5 GHz Dual core<br></li><li><strong>Memory:</strong> 4 GB RAM<br></li><li><strong>Graphics:</strong> NVIDIA GeForce 760, AMD Radeon R7 270X, or better<br></li><li><strong>DirectX:</strong> Version 11<br></li><li><strong>Network:</strong> Broadband Internet connection<br></li><li><strong>Storage:</strong> 20 GB available space</li></ul>",
        "recommended": "<strong>Recommended:</strong><br><ul class=\"bb_ul\"><li><strong>OS:</strong> Windows 7 (64 bit) or Newer (64 bit) Windows OS<br></li><li><strong>Processor:</strong> 3.0+ GHz Quad core<br></li><li><strong>Memory:</strong> 8 GB RAM<br></li><li><strong>Graphics:</strong> NVIDIA GeForce GTX 1060, AMD Radeon RX 470, or better<br></li><li><strong>DirectX:</strong> Version 11<br></li><li><strong>Network:</strong> Broadband Internet connection<br></li><li><strong>Storage:</strong> 20 GB available space<br></li><li><strong>Additional Notes:</strong> Gamepad or Controller Recommended</li></ul>"
      },
      "mac_requirements": {
        "minimum": "<strong>Minimum:</strong><br><ul class=\"bb_ul\"><li><strong>OS:</strong> MacOS X 10.8.5<br></li><li><strong>Processor:</strong> Intel Core i5 2.4 GHz<br></li><li><strong>Memory:</strong> 8 GB RAM<br></li><li><strong>Graphics:</strong> OpenGL 4.1 - ATI Radeon HD 5670, NVIDIA GeForce GT 640M, Intel HD Graphics 4000 or Iris Pro Graphics<br></li><li><strong>Network:</strong> Broadband Internet connection<br></li><li><strong>Storage:</strong> 7 GB available space</li></ul>",
        "recommended": "<strong>Recommended:</strong><br><ul class=\"bb_ul\"><li><strong>OS:</strong> MacOS X 10.8.5 or Newer<br></li><li><strong>Processor:</strong> Intel Core i7 2.4 GHz+<br></li><li><strong>Memory:</strong> 8 GB RAM<br></li><li><strong>Graphics:</strong> OpenGL 4.1 - ATI Radeon HD 5670, NVIDIA GeForce GT 640M<br></li><li><strong>Network:</strong> Broadband Internet connection<br></li><li><strong>Storage:</strong> 7 GB available space</li></ul>"
      },
      "linux_requirements": {
        "minimum": "<strong>Minimum:</strong><br><ul class=\"bb_ul\"><li><strong>Processor:</strong> 2.4+ GHz Quad core<br></li><li><strong>Memory:</strong> 2 GB RAM<br></li><li><strong>Graphics:</strong> NVIDIA GTX 260 or ATI 4850<br></li><li><strong>Network:</strong> Broadband Internet connection<br></li><li><strong>Storage:</strong> 7 GB available space</li></ul>",
        "recommended": "<strong>Recommended:</strong><br><ul class=\"bb_ul\"><li><strong>Processor:</strong> 2.5+ GHz Quad core<br></li><li><strong>Memory:</strong> 4 GB RAM<br></li><li><strong>Graphics:</strong> NVIDIA GTX 660 or better, ATI 7950 or better<br></li><li><strong>Network:</strong> Broadband Internet connection<br></li><li><strong>Storage:</strong> 7 GB available space<br></li><li><strong>Additional Notes:</strong> Gamepad or Controller Recommended</li></ul>"
      },
      "legal_notice": "Copyright © 2015-2019 Psyonix Inc. Rocket League, Psyonix, and all related marks and logos are registered trademarks or trademarks of Psyonix Inc. All rights reserved. All other trademarks are property of their respective owners.",
      "developers": [
        "Psyonix LLC"
      ],
      "publishers": [
        "Psyonix LLC"
      ],
      "package_groups": [],
      "platforms": {
        "windows": true,
        "mac": false,
        "linux": false
      },
      "metacritic": {
        "score": 86,
        "url": "https://www.metacritic.com/game/pc/rocket-league?ftag=MCD-06-10aaa1f"
      },
      "categories": [
        {
          "id": 2,
          "description": "Single-player"
        },
        {
          "id": 1,
          "description": "Multi-player"
        },
        {
          "id": 49,
          "description": "PvP"
        },
        {
          "id": 36,
          "description": "Online PvP"
        },
        {
          "id": 37,
          "description": "Shared/Split Screen PvP"
        },
        {
          "id": 9,
          "description": "Co-op"
        },
        {
          "id": 38,
          "description": "Online Co-op"
        },
        {
          "id": 39,
          "description": "Shared/Split Screen Co-op"
        },
        {
          "id": 24,
          "description": "Shared/Split Screen"
        },
        {
          "id": 27,
          "description": "Cross-Platform Multiplayer"
        },
        {
          "id": 22,
          "description": "Steam Achievements"
        },
        {
          "id": 28,
          "description": "Full controller support"
        },
        {
          "id": 29,
          "description": "Steam Trading Cards"
        },
        {
          "id": 30,
          "description": "Steam Workshop"
        },
        {
          "id": 23,
          "description": "Steam Cloud"
        },
        {
          "id": 15,
          "description": "Stats"
        },
        {
          "id": 41,
          "description": "Remote Play on Phone"
        },
        {
          "id": 42,
          "description": "Remote Play on Tablet"
        },
        {
          "id": 43,
          "description": "Remote Play on TV"
        },
        {
          "id": 44,
          "description": "Remote Play Together"
        }
      ],
      "genres": [
        {
          "id": "1",
          "description": "Action"
        },
        {
          "id": "23",
          "description": "Indie"
        },
        {
          "id": "9",
          "description": "Racing"
        },
        {
          "id": "18",
          "description": "Sports"
        }
      ],
      "screenshots": [
        {
          "id": 0,
          "path_thumbnail": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_f2d159a1a974b2da6f0939d75b9cf5e20124ad6c.600x338.jpg?t=1662568128",
          "path_full": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_f2d159a1a974b2da6f0939d75b9cf5e20124ad6c.1920x1080.jpg?t=1662568128"
        },
        {
          "id": 1,
          "path_thumbnail": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_8660e839b88ee0a5d26d4f8f844628f031d024c5.600x338.jpg?t=1662568128",
          "path_full": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_8660e839b88ee0a5d26d4f8f844628f031d024c5.1920x1080.jpg?t=1662568128"
        },
        {
          "id": 2,
          "path_thumbnail": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_772ba3264a5ba1e1ad0413577ecc8d4e7c62d7cf.600x338.jpg?t=1662568128",
          "path_full": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_772ba3264a5ba1e1ad0413577ecc8d4e7c62d7cf.1920x1080.jpg?t=1662568128"
        },
        {
          "id": 3,
          "path_thumbnail": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_e2afa06294df420b0879a7c7962bb25344e66397.600x338.jpg?t=1662568128",
          "path_full": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_e2afa06294df420b0879a7c7962bb25344e66397.1920x1080.jpg?t=1662568128"
        },
        {
          "id": 4,
          "path_thumbnail": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_a3e6277df5fd5f2e73d1febb6eba6c5062a6cd11.600x338.jpg?t=1662568128",
          "path_full": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_a3e6277df5fd5f2e73d1febb6eba6c5062a6cd11.1920x1080.jpg?t=1662568128"
        },
        {
          "id": 5,
          "path_thumbnail": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_ca4d796c61d7b1041d658ed79b78d710b0ebce4d.600x338.jpg?t=1662568128",
          "path_full": "https://cdn.akamai.steamstatic.com/steam/apps/252950/ss_ca4d796c61d7b1041d658ed79b78d710b0ebce4d.1920x1080.jpg?t=1662568128"
        }
      ],
      "movies": [
        {
          "id": 256904802,
          "name": "Rocket League® - Season 8",
          "thumbnail": "https://cdn.akamai.steamstatic.com/steam/apps/256904802/movie.293x165.jpg?t=1662568121",
          "webm": {
            "480": "http://cdn.akamai.steamstatic.com/steam/apps/256904802/movie480_vp9.webm?t=1662568121",
            "max": "http://cdn.akamai.steamstatic.com/steam/apps/256904802/movie_max_vp9.webm?t=1662568121"
          },
          "mp4": {
            "480": "http://cdn.akamai.steamstatic.com/steam/apps/256904802/movie480.mp4?t=1662568121",
            "max": "http://cdn.akamai.steamstatic.com/steam/apps/256904802/movie_max.mp4?t=1662568121"
          },
          "highlight": true
        }
      ],
      "recommendations": {
        "total": 416214
      },
      "achievements": {
        "total": 88,
        "highlighted": [
          {
            "name": "Virtuoso",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/d63287a74492218ac97000af0147f6e40bd51f1d.jpg"
          },
          {
            "name": "Stocked",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/689d4370df62dab7b3f904d4f7b182310958efd2.jpg"
          },
          {
            "name": "Far, Far Away...",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/c3c40f439d804bbcf892229ed76b2bedb25058c7.jpg"
          },
          {
            "name": "Super Victorious",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/710fa884cc942b05b4ff2856eda4a1eebe909f21.jpg"
          },
          {
            "name": "Champion",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/ef9fc46837a6a31b2fedd4d227f27cd3af0a9867.jpg"
          },
          {
            "name": "The Streak",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/9974f05905881b45c7a2dbbd3c84d5e8c57fa01a.jpg"
          },
          {
            "name": "Helen's Pride",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/d25f78987a4f8a3500172ef5c8019c02684ae2ba.jpg"
          },
          {
            "name": "Car Collector",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/b69193ec416423e20757130026768b5c71bf3177.jpg"
          },
          {
            "name": "Drops in the Bucket",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/19c6744ebc45507a746469e1ba71e9654bf77a11.jpg"
          },
          {
            "name": "Rocketeer",
            "path": "https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/252950/90d781a6891727c11ddec87f6535bd74d22ef580.jpg"
          }
        ]
      },
      "release_date": {
        "coming_soon": false,
        "date": "7 Jul, 2015"
      },
      "support_info": {
        "url": "https://support.rocketleague.com/",
        "email": ""
      },
      "background": "https://cdn.akamai.steamstatic.com/steam/apps/252950/page_bg_generated_v6b.jpg?t=1662568128",
      "background_raw": "https://cdn.akamai.steamstatic.com/steam/apps/252950/page.bg.jpg?t=1662568128",
      "content_descriptors": {
        "ids": [],
        "notes": null
      }
    }
  }
}
```

Er zal een enum zijn voor game categorieen, en genres, elke keer als er waarde voor een van deze variabelen tegen word gekomen die nog niet in de enum staat, word deze er bij gezet.

Als het wel een `"game"` is, dan wordt alle essentiele data gezet in een `Game` object, als dit goed verloopt word het in de database opgeslagen.

---

#### (old) Reviews

Nadat alle games zijn opgeslagen, gaat er voor elke opgeslagen game gekeken worden naar de reviews, hiervoor zal ik [dit]([GitHub - prncc/steam-scraper: A pair of spiders for scraping product data and reviews from Steam.](https://github.com/prncc/steam-scraper)) pakketje gebruiken. 

Voorbeeld:

```json
{
  'date': '2017-06-04',
  'early_access': False,
  'found_funny': 5,
  'found_helpful': 0,
  'found_unhelpful': 1,
  'hours': 9.8,
  'page': 3,
  'page_order': 7,
  'product_id': '414700',
  'products': 179,
  'recommended': True,
  'text': '3 spooky 5 me',
  'user_id': '76561198116659822',
  'username': 'Fowler'
}
```

Wanneer alle reviews zijn geschraapt worden ze in een textbestand gezet, bijv: `15270.json`. Dit textbestand gaat hierna dan weer uitgelezen worden, en elke review gaat bekeken en gevalideerd worden. Als alles goed is wordt deze gezet in een `SteamReview` object en opgeslagen in de database.

Dit proces zal niet in een keer gebeuren. Een cron proces zal dit proces om de x tijd activeren, en eventuele errors of warnings loggen.

---

#### (updated) Reviews

Wanneer een user op een game pagina land, word de steam api gecalled om de reviews dynamisch op te halen, alle reviews die de gebruiker ophaalt, worden naar de backend gestuurd en opgeslagen in de database, zo hoef ik niet ontzettend veel data op te slaan, maar heb ik wel altijd alle reviews. de volgende keer dat dezelfde of een andere gebruiker op die game pagina land kunnen de reviews uit de database worden gehaald, en als ze meer reviews opvragen word de steam api weer gecalled, etc

---

## Nice to haves:

Mobile ui support
