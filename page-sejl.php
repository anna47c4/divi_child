<?php

get_header();
?>
<!-- START PÅ HTML  -->
<!-- Nedenfor her har vi lavet en skabelon (template) indeholdene en article, som vores data fra pods' skal bruge til visningen  -->
 <template>
    <article class="sejl">
         <h1></h1>
      	 <img class="pic" src="" alt="sejl"/> 
    </article>
 </template>

<section id="main-content">
 <main id="main">
 <nav id="filtrering"><button class="alle" data-sejl="alle">Alle</button></nav>
 <section class="sejl-container"></section><!--her har vi den tomme section, som fungerer som beholder til de data vi kloner -->
 </main><!--slut main -->

<!-- SLUT PÅ HTML PÅNÆR SECTION "MAIN-CONTENT" SOM LUKKES EFTER SCRIPTET  -->

<!-- Herunder starter vi JS koden, som skal hjælpe os med at hente dataerne ind  -->
 <script>   
 //herunder har vi lavet nogle variable og konstanter, som vi bruger gennem scriptet
  let sejlene; 
  let categories; 
  let filterCat = "alle"; //default værdi vi har givet så alle vises inden klik på specifik
  const url = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/sejl?per_page=100"
  const catUrl = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/categories?per_page=100" //test
  
 //herunder kører vi funktionnen der henter data ind fra vores URL'er (de forskellige posts og kategorierne)
  async function hentData(){
    const respons = await fetch(url); //custom posts der hentes ind
    const catRespons = await fetch(catUrl); //kategorierne der hentes ind

    sejlene = await respons.json(); 
    categories = await catRespons.json(); 
    console.log(categories); 
    visData(); 
    opretKnapper(); 
  }
  //herunder kører vi funktionen der opretter / tilføjer kategorierne (vha. dets ID) som knapper i vores nav (#filtrering)
    function opretKnapper(){
        categories.forEach(cat => {
            document.querySelector("#filtrering").innerHTML += `<button class="filter ${cat.id}" data-sejl="${cat.id}">${cat.name}</button>`
        })
        kaldKnapper(); //her kalder vi knapperne, og med det menes der at vi 'aktiverer' knapperne, ved at tilføje eventlistener der gør dem klikbare
    }
   
    function kaldKnapper() {
        document.querySelectorAll("#filtrering button").forEach(elm =>{
            elm.addEventListener("click", filtrering) //ved brug af forEach og elm, gør vi alle knapperne klikbare. Efter der er klikket på en kategori, kalder vi funktionen filtrering
        })
    }
    //herunder kører vi den funktion der udfører filtreringen alt efter, hvad man klikker på
    function filtrering(){
     filterCat = this.dataset.sejl; //her gør vi det klart at der skal filtreres på det der er klikket på ved brug af 'this'
     console.log(filterCat); 
     visData(); //vi kalder vores vis funktion, så det rigtige indhold til den rigtige kategori kan vises
    }
  //herunder kører vi den funktion der sørger for at få vist vores data i DOM'en
  function visData() {
 /* Som det første i denne funktion, sørger vi for at få oprettet to konstanter, vores data container, og vores template */
    const container = document.querySelector(".sejl-container");
    const temp = document.querySelector("template");
    container.textContent = ""; //her sørger vi for at containeren tømmes hver gang der klikkes på ny, så det ikke hober sig op

    /* I det nedestående stykke kode der kloner vi vores data-felter ind i den article vi har gjort klar, og vi bruger de ord som vi har brugt da vi oprettede podsene */
    sejlene.forEach(sejl => {
        if (filterCat == "alle" || sejl.categories.includes(parseInt(filterCat))){
        let klon = temp.cloneNode(true).content; 
        klon.querySelector("h1").textContent = sejl.overskrift;
        klon.querySelector(".pic").src = sejl.foto.guid; //vi har skrevet 'guid' til sidst, fordi vi i console kunne se at billedet hed det
        container.appendChild(klon);}
    }); 
  }

 hentData(); //her kalder vi vores hentData funktion, der sætter gang i det hele 
 </script>
</section> <!-- skal lukkes efter script  -->
<style>
/* .pic {
    width: 50%; 
} */

.sejl-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* - størrelsen skal nok ændres men det er overskueligt imens der arbejdes  */
  grid-gap: 8px;
}
</style>
<?php

get_footer();
