<?php

get_header();
?>
<!-- TEMPLATES HERUNDER -->
<template id="cat-temp"> <!-- KATEGORI TEMPLATE -->
    <div class="categories">
        <div class="tekst">
            <h1></h1>
            <p></p>
            <div id="knapper"><!-- <button data-sejl="alle produkter">ALLE PRODUKTER</button> --></div>
        </div>
        <img class="billede" src="" alt="kategori foto"></div>
    </div>
</template>

 <template id="sejl-temp"> <!-- SEJL TEMPLATE -->
    <article class="sejl">
         <h2></h2>
      	 <img class="pic" src="" alt="sejl"/> 
    </article>
 </template>
 
<!--  HTML STRUKTUR HERUNDER  -->
<section id="main-content">
 <main id="main">
<!--  <nav id="filtrering"><button class="alle" data-sejl="alle">Alle</button></nav> -->
 <section class="cat-container"></section><!--her har vi den tomme section, som fungerer som beholder til de KATEGORI data vi kloner -->
 <!--  <section class="cat-container"></section> --><!--her har vi den tomme section, som fungerer som beholder til de KATEGORI data vi kloner -->
 <section class="sejl-container"></section><!--her har vi den tomme section, som fungerer som beholder til de data vi kloner -->
 </main><!--slut main -->

<!-- SLUT PÅ HTML PÅNÆR SECTION "MAIN-CONTENT" SOM LUKKES EFTER SCRIPTET  -->

<script>
  /*   Variable og konstanter herunder */
  let sejlene; 
  let categories; 
  let filterCat = ""; 

  const url = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/sejl?per_page=100"
  const catUrl = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/categories?per_page=100" 

   //herunder kører vi funktionnen der henter data ind fra vores URL'er (de forskellige posts og kategorierne)
  async function hentData(){
    const respons = await fetch(url); //custom posts der hentes ind
    const catRespons = await fetch(catUrl); //kategorierne der hentes ind

    sejlene = await respons.json(); 
    categories = await catRespons.json(); 
    console.log(categories); 
    visData(); 
    visCat(); 
  /*   opretKnapper(); */
    kaldKnapper();
    catID(); 
  }
  
/*  function opretKnapper(){
      categories.forEach(cat => {
          document.querySelector("#knapper").innerHTML += `<button class="filter ${cat.id}" data-sejl="${cat.id}">${cat.knaptekst}</button>` 
      })
      kaldKnapper(); 
  }  */

 function kaldKnapper(){
      document.querySelectorAll("#knapper button").forEach(elm => {
          elm.addEventListener("click", filtrering)
      })
  }; 

  function catID(){
      document.querySelector(".categories").setAttribute('data-sejl', ${cat.id}); 
  }


   function filtrering(){
     filterCat = this.dataset.sejl; //her gør vi det klart at der skal filtreres på det der er klikket på ved brug af 'this'
     console.log(filterCat); 
     visData(); //vi kalder vores vis funktion, så det rigtige indhold til den rigtige kategori kan vises
   /*   visCat();  */
    }

  function visData() {
 /* Som det første i denne funktion, sørger vi for at få oprettet to konstanter, vores data container, og vores template */
    const container = document.querySelector(".sejl-container");
    const sejltemp = document.querySelector("#sejl-temp");
    container.textContent = ""; //her sørger vi for at containeren tømmes hver gang der klikkes på ny, så det ikke hober sig op

    /* I det nedestående stykke kode der kloner vi vores data-felter ind i den article vi har gjort klar, og vi bruger de ord som vi har brugt da vi oprettede podsene */
    sejlene.forEach(sejl => {
        if (filterCat == "" || sejl.categories.includes(parseInt(filterCat))){
        let klon = sejltemp.cloneNode(true).content; 
        klon.querySelector("h2").textContent = sejl.overskrift;
        klon.querySelector(".pic").src = sejl.foto.guid; //vi har skrevet 'guid' til sidst, fordi vi i console kunne se at billedet hed det
        klon.querySelector(".sejl").addEventListener("click", ()=> {location.href = sejl.link;})
        container.appendChild(klon);}
    }); 
  }
  
  function visCat(){
      const catContainer = document.querySelector(".cat-container");
      const catTemp = document.querySelector("#cat-temp"); 
     /*  catContainer.textContent = "";  */

      categories.forEach(cat => {
          let klon = catTemp.cloneNode(true).content; 
          klon.querySelector("h1").textContent = cat.name; 
          klon.querySelector("p").textContent = cat.teasertekst; 
          klon.querySelector(".billede").src = cat.billede.guid; 
         /*  klon.querySelector("#knapper button").textContent = cat.knaptekst;  */
         klon.querySelector("#knapper").innerHTML += `<button class="filter ${cat.id}" data-sejl="${cat.id}">${cat.knaptekst}</button>` 
          catContainer.appendChild(klon); 
      })
  }
  hentData(); 
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