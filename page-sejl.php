<?php

get_header();
$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>
<!-- TEMPLATES HERUNDER -->
<template id="cat-temp"> <!-- CATEGORY TEMPLATE -->
    <div class="categories">
        <div class="tekst">
            <h1 class="cat-heading"></h1>
            <p class="cat-p"></p>
            <div id="knapper"><!-- <button data-sejl="alle produkter">ALLE PRODUKTER</button> --></div>
      <!--   </div>
        <img class="billede" src="" alt="kategori foto"></div> -->
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
<!-- Herunder har vi den øverste section på siden  -->
<section id="top-section">
    <div class="top-wrapper">
      <div class="left">
        <div class="left-wrapper">
          <div class="top-tekst">
        <h3 class="top-heading">Sejl</h3>
        <p class="top-p">Herunder finder du vores udvalg af sejl og canvas produkter, samt de services vi udbyder. Vi står altid klar til at vejlede, og du er mere end velkommen til at kontakte os for et uforpligtende tilbud.</p>
        <a href="http://perfpics.dk/kea/2_sem/sejlservice_wp/kontakt/"><button class="kontakt">Kontakt</button></a>
        </div>
        </div>
      </div>
      <div class="right">
        <div class="right-wrapper">
        <img class="top-pic" alt="topfoto" src="http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-content/uploads/2022/06/sejl-scaled.jpg"/>
        </div>
      </div>
    </div></section>

 <section id="cat-container"></section><!--her har vi den tomme section, som fungerer som beholder til de CATEGORIES data vi kloner -->
 <!-- Herunder har vi den section der ligger inden produkterne  -->
 <section id="service-section">
    <div class="service-wrapper">
        <h5 class="service-heading">Alle produkter</h5>
      <!--   <a href="http://perfpics.dk/kea/2_sem/sejlservice_wp/kontakt-v2/"><button class="kontakt">Kontakt</button></a> -->
    </div></section>

 <section id="sejl-container"></section><!--her har vi den tomme section, som fungerer som beholder til de SEJL data vi kloner -->
 </main><!--slut main -->

<!-- SLUT PÅ HTML PÅNÆR <SECTION "MAIN-CONTENT"> SOM LUKKES EFTER <SCRIPTET>  -->

<script>
  /*   Variable og konstanter herunder */
  let sejlene; 
  let categories; 
  let filterCat = ""; 
  const serviceHeading = document.querySelector(".service-heading");

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
     serviceHeading.textContent = this.dataset.heading; //her sørger vi for at vores 'service-heading' ændrer indhold alt efter hvilken kategori-knap man har trykket på
     visData(); //vi kalder vores vis funktion, så det rigtige indhold til den rigtige kategori kan vises
    }
/* HERUNDER ER DEN FUNKTION (visData) som kloner vores SEJL custom pods ind i vores DOM */
  function visData() {
 /* Som det første i denne funktion, sørger vi for at få oprettet to konstanter, vores data container, og vores sejl-template */
    const container = document.querySelector("#sejl-container");
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
      const catContainer = document.querySelector("#cat-container"); 
      const catTemp = document.querySelector("#cat-temp"); 
//herunder bruger vi igen 'forEach(cat)', så vi får en kloning for hver af de kategorier vi har oprettet
      categories.forEach(cat => {
          let klon = catTemp.cloneNode(true).content; 
          klon.querySelector(".categories").classList.add(cat.id); //her tilføjer vi kategoriernes ID som en class
          klon.querySelector(".cat-heading").textContent = cat.name; 
          klon.querySelector(".cat-p").textContent = cat.teasertekst; 
        
         //herunder hiver vi fat i vores div #knapper, og tilføjer knapperne med innerHTML, vi tilføjer data-sejl= kategoriernes ID (så filtreringen kan udføres) + data-heading, så vi kan bruge det til vores overskrift i filtreringsfunktionen
         klon.querySelector("#knapper").innerHTML += `<a href="#service-section"><button class="filter ${cat.id}" data-heading="${cat.name}" data-sejl="${cat.id}">${cat.knaptekst}</button></a>` 
          catContainer.appendChild(klon); 
      })
  }


  hentData(); //kald af vores funktion der henter dataerne ind 
</script>
</section> <!-- skal lukkes efter script  -->

<!-- HER STARTER STYLING  -->
 <style>
 * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}   

/* sejl prodkt styles herunder */  
#sejl-container {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
grid-gap: 8px;
/* background-color: #304950;  */
}

/* kategori styling herunder  */
#cat-container{
  display: grid;
  margin: 30px 0px 30px 0px; 
}
.categories {
/* grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); - udkommenteret da vi ikke har brug for kolonner når vi ikke har billede i kategorien*/
margin: 12px; 
padding: 12px; 
border: 0.5px solid #304950; 
border-radius: 3px; 
background-color: #fafaff;
background-image: url(http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-content/uploads/2022/05/footer.png);
background-size: cover;
}
.cat-heading{
font-weight: 1000; 
font-size: 1rem;
}

/* top section MOBILE + service section herunder */
#top-section {
background-image: url(http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-content/uploads/2022/05/282830621_309950614687409_9105901920455162344_n.png); 
box-shadow: inset 0px 100px 0px 0px #ffffff;
padding-top: 3px; 
background-position: 50%; 
background-size: cover;
/* padding: 2% 0; */
/* height: 600px;  */
}
.top-wrapper{
padding-top: 140px;   
width: 100%;
max-width: 100%; 
/* background-position: 50%;
background-repeat: no-repeat;
float: left;
position: relative;
z-index: 2;
min-height: 1px;  */
}
.top-tekst{
padding-left: 10%;
padding-bottom: 30px; 
text-align: left;
word-wrap: break-word;
max-width: 75%;
color:  #fafaff; 
line-height: 1.9em;
}
.top-pic{
padding-bottom: 40px; 
}
.top-heading{
font-size: 2.3rem; 
color: #fafaff;   
text-align: left;
padding-bottom: 40px; 
}
/* .left-wrapper{
padding-bottom: 20px;
}
.right-wrapper{
padding-top: 0px;
}
.top-tekst{
padding-left: 10%;
width: 100%;
position: relative;
} */
/* .top-p{
line-height: 1.5em;
text-align: left;
padding-bottom: 30px; 
} */
.top-p{
  padding-bottom: 15px; 
}
.kontakt{
  margin-top: 20px; 
  text-transform: uppercase; 
}
.top-pic{
  width: 100%; 
}
.right-wrapper{
}
.service-wrapper {
margin: 8px;
padding: 8px; 
}
.service-heading {
color: #191919; 
text-transform: uppercase; 
font-weight: bold; 
font-size: 1.5rem; 
}
.service-heading{
color: #191919; 
}
.heading-two {
color: #e98b3d;
font-size: 0.5rem; 
padding-left: 8px; 
text-transform: uppercase; 
}

/* knap styling herunder (gælder alle knapper) */
button {
color: #304950; 
background-color: #e98b3d;
border: none; 
letter-spacing: 6px;
font-size: 0.8rem; 
/* padding: .3em 1em;  */
padding: 1em; 
border-radius: 3px; 
font-weight: 500;
margin-top: 12px; 
line-height: 1.7em; 
cursor: pointer; 
} 

button:hover{
color: #fafaff; 
background-color: #304950;
border: 1.5px solid; 
border-color: #e98b3d; 
} 
/* .kontakt {
margin: 12px;  
text-transform: uppercase; 
} */

/* article styles herunder */
.sejl {
border: 0.5px solid #304950;
border-radius: 3px; 
/* background-color: #304950;  */
padding: 12px; 
margin: 12px; 
cursor: pointer; 
}
h2 {
 color: black; 
 font-size: 1rem;
}
/* responsive indstillinger desktop herunder */
@media (min-width: 790px){
#cat-container {
display: grid;
grid-template-columns: 1fr 1fr 1fr; 
margin-right: 75px; 
margin-left: 75px; 
/* grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); */
}
#sejl-container{
  margin-right: 75px; 
  margin-left: 75px; 
}

#top-section{
padding: 2% 0;
}
.top-wrapper{
display: flex; 
}
.left, .right{
  width: 50%
}
/* .right-wrapper{
position: relative; 
padding-top: 0;  
z-index: 2;
} */
.right{
padding-top: 0px;  
float: left;
position: relative;
z-index: 2;
/* min-height: 1px;  */
margin-right: 0; 
}
.top-tekst{
width: 100%;
position: relative;
padding-left: 25%;
}
.top-heading{
  font-size: 3.3rem;
  padding-bottom: 60px; 
}
.top-p{
  font-size: 1rem; 
  padding-bottom: 50px; 
}
.kontakt{
  margin-top: 50px;  
}
.top-pic{
  max-width: 100%;
  height: auto; 
}
.service-heading{
font-size: 1.2rem;
margin-left: 75px; 
}

.cat-heading, h2{
font-size: 1.2rem;
}
#knapper {
display: flex;
align-items: baseline; 
}
button{
font-size: 0.9rem; 
}
.heading-two {
font-size: 1rem; 
}
}

}
@media (min-width: 980px){
.categories{
/*   margin: 50px;  */
  padding: 30px;  
}

}
@media (min-width: 1200px){
  .categories{
 padding: 45px; 
}
}

</style>

 <?php

get_footer();