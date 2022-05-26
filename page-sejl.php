<?php

get_header();
$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>
<!-- TEMPLATES HERUNDER -->
<template id="cat-temp"> <!-- CATEGORY TEMPLATE -->
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
<section id="top-section">
    <div class="top-wrapper">
        <h1 class="top-heading">Sejl</h1>
         <h2 class="heading-two">Something here</h2>
    </div></section>
 <section class="cat-container"></section><!--her har vi den tomme section, som fungerer som beholder til de CATEGORIES data vi kloner -->

 <section class="sejl-container"></section><!--her har vi den tomme section, som fungerer som beholder til de SEJL data vi kloner -->
 </main><!--slut main -->

<!-- SLUT PÅ HTML PÅNÆR <SECTION "MAIN-CONTENT"> SOM LUKKES EFTER <SCRIPTET>  -->

<script>
  /*   Variable og konstanter herunder */
  let sejlene; 
  let categories; 
  let filterCat = ""; 

  const url = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/sejl?per_page=100" //denne url går til vores SEJL data
  const catUrl = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/categories?per_page=100" //denne url går til vores KATEGORI data

 //herunder kører vi funktionnen der henter data ind fra vores URL'er (de forskellige sejl-posts og kategorierne)
  async function hentData(){
    const respons = await fetch(url); //sejl-data hentes ind
    const catRespons = await fetch(catUrl); //kategorierne hentes ind

    sejlene = await respons.json(); 
    categories = await catRespons.json(); 
    console.log(categories); 
    visData(); //'visData' er navnet for vores SEJL-custom pods 
    visCat(); //'visCat' er navnet for vores CATEGORIES-custom pods 
    kaldKnapper();
  }
  
//herunder kører vi den funktion der gør allle category-knapperne klikbare, og sender os ned til filtreringen ved klik
 function kaldKnapper(){
      document.querySelectorAll("#knapper button").forEach(elm => {
          elm.addEventListener("click", filtrering)
      })
  }; 
//herunder sker filtreringen, vi bruger vores variable 'filterCat' (category), og siger at den skal være lig det der lige er blevet klikket på
   function filtrering(){
     filterCat = this.dataset.sejl; //fordi vi bruger 'this', og vi i opbygningen af knapperne sætter data-sejl=cat.id som attribut, så får vi altså vist præcist den kategori som knappen gemmer på, da den hiver fat i kategoriens ID
     console.log(filterCat); 
     visData(); //vi kalder vores vis funktion, så det rigtige indhold til den rigtige kategori kan vises
    }
/* HERUNDER ER DEN FUNKTION (visData) som kloner vores SEJL custom pods ind i vores DOM */
  function visData() {
 /* Som det første i denne funktion, sørger vi for at få oprettet to konstanter, vores data container, og vores sejl-template */
    const container = document.querySelector(".sejl-container");
    const sejltemp = document.querySelector("#sejl-temp");
    container.textContent = ""; //her sørger vi for at containeren tømmes hver gang der klikkes på ny, så det ikke hober sig op

/*Herunder bruger vi 'forEach(sejl)' og sørger dermed for at hvert enkelt post vi har oprettet i vores SEJL-pods klones ind i vores template */
    sejlene.forEach(sejl => {
        if (filterCat == "" || sejl.categories.includes(parseInt(filterCat))){ //denne linje sørger dels for: at køre kloningen, hvis if sætningen opfyldes, og så sørger den for at analysere 'filterCat', og inkludere tal, hvis de forekommer 
        let klon = sejltemp.cloneNode(true).content; 
        klon.querySelector("h2").textContent = sejl.overskrift;
        klon.querySelector(".pic").src = sejl.foto.guid; //vi har skrevet 'guid' til sidst, fordi vi i console kunne se at billedet hed det
        klon.querySelector(".sejl").addEventListener("click", ()=> {location.href = sejl.link;}) //her sørger vi for at når der klikkes på en af vores articles, så kommer vi til vores single-sejl.php
        container.appendChild(klon);}
    }); 
  }
/*  Herunder er den funktion som kloner vores KATEGORI-data ind i vores kategori template   */
  function visCat(){
      const catContainer = document.querySelector(".cat-container"); 
      const catTemp = document.querySelector("#cat-temp"); 
//herunder bruger vi igen 'forEach(cat)', så vi får en kloning for hver af de kategorier vi har oprettet
      categories.forEach(cat => {
          let klon = catTemp.cloneNode(true).content; 
          klon.querySelector(".categories").classList.add(cat.id); //her tilføjer vi kategoriernes ID som en class
          klon.querySelector("h1").textContent = cat.name; 
          klon.querySelector("p").textContent = cat.teasertekst; 
          klon.querySelector(".billede").src = cat.billede.guid; 
          //herunder hiver vi fat i vores div #knapper, og tilføjer knapperne med innerHTML, vi tilføjer data-sejl= kategoriernes ID (så filtreringen kan udføres)
         klon.querySelector("#knapper").innerHTML += `<button class="filter ${cat.id}" data-sejl="${cat.id}">${cat.knaptekst}</button>` 
          catContainer.appendChild(klon); 
      })
  }
  hentData(); //kald af vores funktion der henter dataerne ind 
</script>
 </section> <!-- skal lukkes efter script  -->

<!-- HER STARTER STYLING  -->
 <style>
.sejl-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* - størrelsen skal nok ændres men det er overskueligt imens der arbejdes  */
  grid-gap: 8px;
    background-color: #304950; 
}

/* kategori styling herunder  */
.categories {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    margin: 8px; 
    padding: 8px; 
}
#top-section {
    background-image: url("http://perfpics.dk/kea/2_sem/placeholder/ph_top.png");
    background-size: cover; 
    height: 100px; 
}

.top-heading {
    color: #fafaff; 
    text-transform: uppercase; 
      padding: 8px; 
}

.heading-two {
color: #e98b3d;
font-size: 0.5rem; 
  padding: 8px; 
      text-transform: uppercase; 
}
button {
background-color: #e98b3d;
border-color: #e98b3d;
letter-spacing: 4px;
padding: .3em 1em; 
border: 2px solid;
border-radius: 3px; /* forsiens knap har 3px */
font-weight: 500;
margin: 8px;
padding: 8px; 
}

button:hover{
  color: #fafaff; 
background-color: #304950;
border-color: #e98b3d; 
}

/* article styles herunder */
.sejl {
    border: 0.5px solid #fafaff;
    background-color: #304950; 
    padding: 12px; 
    margin: 12px; 
}
h2 {
 color: #fafaff; 
}


 @media (min-width: 810px){
    .categories {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    margin: 20px; 
    padding: 20px; 
}

#top-section {
    height: 150px; 
}
.top-heading {
    padding: 12px; 
}
.heading-two {
font-size: 1rem; 
padding: 12px; 
}
 }
</style>

 <?php

get_footer();