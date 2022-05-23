<?php

get_header();
?>

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


<!-- Herunder starter vi JS koden, som skal hjælpe os med at hente dataerne ind  -->
 <script>   
  let sejlene; 
  let categories; //test
  let filterCat = "alle"; //default værdi vi har givet så alle vises inden klik på specifik
  const url = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/sejl?per_page=100"
  const catUrl = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/categories?per_page=100" //test
  

  async function hentData(){
    const respons = await fetch(url);
    const catRespons = await fetch(catUrl); //test
    sejlene = await respons.json(); 
    categories = await catRespons.json(); //test
    console.log(categories); 
    visData(); 
    opretKnapper(); 
  }
  
    function opretKnapper(){
        categories.forEach(cat => {
            document.querySelector("#filtrering").innerHTML += `<button class="filter ${cat.id}" data-sejl="${cat.id}">${cat.name}</button>`
        })
        kaldKnapper(); //her kalder vi knapperne, og med det menes der at vi 'aktiverer' knapperne, ved at tilføje eventlistener der gør dem klikbare
    }
   
      function kaldKnapper() {
        document.querySelectorAll("#filtrering button").forEach(elm =>{
            elm.addEventListener("click", filtrering) //efter der er klikket på en kategori, kalder vi funktionen filtrering
        })
    }

     function filtrering(){
     filterCat = this.dataset.sejl; //her gør vi det klart at der skal filtreres på det der er klikket på ved brug af 'this'
     console.log(filterCat); 
     visData(); //vi kalder vores vis funktion, så det rigtige indhold til den rigtige kategori kan vises
    }

  function visData() {
 /* Som det første i denne funktion, sørger vi for at få oprettet to konstanter, vores data container, og vores template */
    const container = document.querySelector(".sejl-container");
    const temp = document.querySelector("template");
    container.textContent = "";

    /* I det nedestående stykke kode der kloner vi vores data-felter ind i den article vi har gjort klar, og vi bruger de ord som vi har brugt da vi oprettede podsene */
    sejlene.forEach(sejl => {
        if (filterCat == "alle" || sejl.categories.includes(parseInt(filterCat))){
        let klon = temp.cloneNode(true).content; 
        klon.querySelector("h1").textContent = sejl.overskrift;
        klon.querySelector(".pic").src = sejl.foto.guid; //vi har skrevet 'guid' til sidst, fordi vi i console kunne se at billedet hed det
        container.appendChild(klon);}
    }); 
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
