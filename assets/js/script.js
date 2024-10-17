
//  my custom js.........................
// .................................................

let arr = [
    "Diploma in Full Stack Development With University Certificate",
    "Corporate Training",
    "On Job Training With Live Projects",
    "100% Placement with submission of live project"
  ];
  let i = 0;
  setInterval(function() {
    document.getElementById("detail-box").innerText = arr[i];
    i = (i + 1) % arr.length;
  }, 4000);