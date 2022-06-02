<?php

get_header();

?>

<section id="primary" class="content-area">
    <main id="main" class="site-main">
    <article class="sejl">
    <div id="left-column">
    <img class="pic" src="" alt="sejl"/> 
    </div>
    <div id="right-column">
    <div class="button-wrap">
    <button class="back"><img src="http://perfpics.dk/kea/2_sem/placeholder/cross.png"></button>
    </div>
    <h1 class="product-heading"></h1>
    <p class="product-p"></p>
   <a href="http://perfpics.dk/kea/2_sem/sejlservice_wp/kontakt/"><button class="kontakt">Kontakt</button></a>
    </div>
    </article>
    </main>

<!--     Her starter vores JavaScript  -->
<script>
    let sejl; //her laver vi en variabel, men denne gang i ental for at opbygge singleview 

    //her laver vi en konstant til vores url, som vi bruger til at fetche vores data ind, og den php snippet der står til slut, bruger vi for at få fat i ID'et for hver custom pod
    const url = "http://perfpics.dk/kea/2_sem/sejlservice_wp/wp-json/wp/v2/sejl/"+<?php echo get_the_ID()?>;

    async function hentData() {
        const respons = await fetch(url);
        sejl = await respons.json(); 
        console.log(sejl); 
        visData()
        }

    function visData(){
        document.querySelector(".pic").src = sejl.foto.guid; 
        document.querySelector(".product-heading").textContent = sejl.overskrift; 
        document.querySelector(".product-p").textContent = sejl.langtekst; 
        document.querySelector(".back").addEventListener("click", historyBack);
    }  
      function historyBack() {
        history.back();
      }
    hentData(); 
</script>
</section> <!-- skal først lukkes efter scriptet  -->

<style>
main {
max-width: 1800px; 
}    
.sejl {
/* display: grid;
grid-template-columns: repeat(auto-fill, minmax(500px, 1fr)); /* - størrelsen skal nok ændres men det er overskueligt imens der arbejdes  */
/* grid-gap: 8px; */ 
background-color: #304950; 
}   
#right-column{
margin: 30px 5px 15px 20px; 
padding: 8px; 
line-height: 2;  
}

button {
color: #304950; 
background-color: #e98b3d;
border: none; 
letter-spacing: 6px;
font-size: 0.7rem; 
line-height: 1.7em; 
padding: 1em; 
text-transform: uppercase; 
border-radius: 3px; 
font-weight: 500;
cursor: pointer; 
margin: 15px 0px 30px 0px;  
} 

button:hover{
color: #fafaff; 
background-color: #304950;
border: 1.5px solid; 
border-color: #e98b3d; 
} 
.button-wrap{
display: flex;
place-content: end; 
}
.back{
background-color: #304950;
cursor: pointer; 
margin-right: 12px; 
}
.product-heading{
color: #fafaff; 
margin-bottom: 12px; 
font-size: 2rem;
}
.product-p{
color: #E9E9EB; 
margin-right: 8px; 
}
.pic {
width: 100%; 
}

/* responsive indstillinger herunder  */
@media (min-width: 650px){
.sejl {
display: grid;
grid-template-columns: 1fr 1fr; 
}
#left-column{
grid-column: 1;  
}


}

</style>





<?php

get_footer();
