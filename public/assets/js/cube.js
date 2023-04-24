let currentClass = "";
let textMetier = document.getElementById('text-metier');
let textCommunication = document.getElementById('text-communication');
let textSavoir = document.getElementById('text-savoir');
let textImmobilier = document.getElementById('text-immobilier');
let textDesign = document.getElementById('text-design');
let textInnovation = document.getElementById('text-innovation');

document.querySelectorAll("div.detailsPole").forEach(function (link) {
  link.addEventListener("mouseenter", function (e) {
    let cube = document.querySelector(".home__cube-container");
    
    if (currentClass) {
      cube.classList.remove(currentClass);
      
      
    }
    
    let showClass = "show-" + e.target.id;
    cube.classList.add(showClass);
    currentClass = showClass;

    switch(showClass){

      case 'show-front' : textMetier.style.color='#2172B3',
                          textCommunication.style.color='#575756',
                          textSavoir.style.color='#575756',
                          textImmobilier.style.color='#575756',
                          textDesign.style.color='#575756',
                          textInnovation.style.color='#575756'
      break

      case 'show-back' : textCommunication.style.color='#FE8D25',
                          textMetier.style.color='#575756',
                          textSavoir.style.color='#575756',
                          textImmobilier.style.color='#575756',
                          textDesign.style.color='#575756',
                          textInnovation.style.color='#575756'
      break

      case 'show-right' : textSavoir.style.color='#FF5356',
                          textMetier.style.color='#575756',
                          textCommunication.style.color='#575756',
                          textImmobilier.style.color='#575756',
                          textDesign.style.color='#575756',
                          textInnovation.style.color='#575756'
      break

      case 'show-left' : textImmobilier.style.color='#61C855',
                          textMetier.style.color='#575756',
                          textCommunication.style.color='#575756',
                          textSavoir.style.color='#575756',
                          textDesign.style.color='#575756',
                          textInnovation.style.color='#575756'
      break

      case 'show-top' : textDesign.style.color='#7B5DA4',
                          textMetier.style.color='#575756',
                          textCommunication.style.color='#575756',
                          textSavoir.style.color='#575756',
                          textImmobilier.style.color='#575756',
                          textInnovation.style.color='#575756'
      break

      case 'show-bottom' : textInnovation.style.color='#FECC38',
                          textMetier.style.color='#575756',
                          textCommunication.style.color='#575756',
                          textSavoir.style.color='#575756',
                          textImmobilier.style.color='#575756',
                          textDesign.style.color='#575756'
      break;

      default:

    }

    
    
  });
});

