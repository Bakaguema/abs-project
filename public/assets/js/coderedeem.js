let Comu = [];
let ValueMax = [];
let Usage = [];
let Color = [];
let table_name = document.getElementsByName("comu_name");
let table_valueMax = document.getElementsByName("MaxUsage");
let table_usage = document.getElementsByName("UsageCurrent");

function RandomColor(){
    let random = Math.floor(Math.random()*16777215).toString(16);
    let color;
    color = "#" + random;
    return color;
}

for (let x = 0; x < table_name.length; x++) {
    Color.push(RandomColor());
}

for (let x = 0; x < table_valueMax.length; x++) {
    ValueMax.push(table_valueMax[x].innerHTML)
}

for (let x = 0; x < table_name.length; x++) {
    Usage.push(table_usage[x].innerHTML);
}

for (let x = 0; x < table_name.length; x++) {
  Comu.push(table_name[x].innerHTML)
}

let xValues = Comu;
let yValues = ValueMax;
let barColors = Color;

let graphique = new Chart("myChart", {
    type: "doughnut",
    data: {
      labels: xValues,
      datasets: [{
        backgroundColor: barColors,
        data: Usage
      }]
    },
    options: {
      title: {
        display: true,
        text: "Nombre de Coupon restant par Comunnauté"
      },
      plugins: {
        display: false
      }
    }
});


function Datatable(e){
  let Data = [];
  let td = e.querySelectorAll('td');
  let label;
  let Colorv2 = [];
  let Code = "";
  for (let i = 0; i < td.length; i++) {
      Data.push(td[i].innerText);
  }
  Data.splice(4, 1); 
  Code = Data.splice(1,1);
  if (Code != null) {
    navigator.clipboard.writeText(Code);
    document.getElementById('copy').innerHTML = "Le code : " + Code + " A correctement été copier !"
  };

  graphique.data.datasets[0].data.splice(0, graphique.data.datasets[0].data.length);
  graphique.data.datasets[0].backgroundColor.splice(0, graphique.data.datasets[0].backgroundColor.length);
  graphique.data.labels.splice(0 , graphique.data.labels.length);
  label = Data.splice(0, 1);
  graphique.options.title.text = label;
  for (let x = 0; x < 2; x++) {
    Colorv2.push(RandomColor());
  }
  graphique.data.datasets[0].backgroundColor = Colorv2;
  graphique.data.labels = ["Utilisation restante", "Utilisation iniatial"];
  graphique.data.datasets[0].data = Data;
  graphique.update();


}