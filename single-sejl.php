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
    <h1></h1>
    <p></p>
    <button clas="kontakt">Kontakt</button>
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
        document.querySelector("h1").textContent = sejl.overskrift; 
        document.querySelector("p").textContent = sejl.langtekst; 
    }  
    
    hentData(); 
</script>
</section> <!-- skal først lukkes efter scriptet  -->






<?php

get_footer();
