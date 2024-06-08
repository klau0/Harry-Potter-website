# Harry Potter témájú webshop
### XAMPP-al localhost-on futtatva lehet megnyitni böngészőben
#### Ehhez a szükséges lépések:
1. Ha még nincs letöltve, akkor a XAMPP [letöltése](https://www.apachefriends.org/download.html)

2. A xampp mappájában a **htdocs** mappába leklónozni a repot

3. Az így létrejött Harry-Potter-website mappában a **classes/SqliteConnection.php** fájl `PATH_TO_DB` értékét átírni úgy, hogy az `sqlite:` utáni rész a saját mappaszerkezetet tükrözze, tehát a **resources/hp.db** elérési útvonalát **/**-jelekkel elválasztva

4. A XAMPP Control Panel-en az Apache szerver elindítása

5. Valamelyik böngésző címsorába beírni: http://localhost/Harry-Potter-website