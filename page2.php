<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Vantec Transactions Clark Brettell</title>
</head>
<body>
<h1>Transactions 2 Json</h1>

 
<?php
header( "refresh:60;url=index.php" );
$strJsonFileContents = file_get_contents("assets/json/transactions2.json");
$transactionArrayOne = json_decode($strJsonFileContents, true);
$today = date("Y-m-d H:i:s");
?>

<script>
var transactionsJson = <?php echo $strJsonFileContents; ?>
var received = [];
var putaway = [];
var lessoneday = [];
var morethanoneday = [];
var morethantwodays = [];
var received = [];
var putaway = [];

function toTimestamp(strDate){
 var datum = Date.parse(strDate);
 return datum/1000;
}


today = <?php echo strtotime($today)?>;
/* 24 hours in seconds to be used for timestamp comparison  CB */
oneday = 86400;
/* 48 hours in seconds to be used for timestamp comparison and sortation  CB */
twodays = oneday * 2;

/*testing purposes editing the multiplication value allows manipulation of the date used instead of today would remove when testing with up to date data CB*/
ninedays = oneday * 8.8;
ninedaysago = today - ninedays;



/*loops over the json object to get the short code */
transactionsJson.forEach(element => {
   if(element["short_code"].startsWith("R")){
    received.push(element);
   }else{
       putaway.push(element);
    }
    
})


transactionsJson.forEach(element => {
    if(ninedaysago - toTimestamp(element["date_created"]) < oneday){
        lessoneday.push(element);
    } else if (ninedaysago - toTimestamp(element["date_created"])  < twodays) {
        morethanoneday.push(element);
    } else {
        morethantwodays.push(element)
    }
    
})


console.log(lessoneday.length);
console.log(morethanoneday.length);
console.log(morethantwodays.length);
console.log(received.length);
console.log(putaway.length);


</script>
<canvas id="myChart" width="1500px" height="600px"></canvas>
<button type="button" id="casesBtnGreen" class="btn btn-success p-3 m-5 d-none"> Cases < 24 Hours</button>
<button type="button" id="casesBtnorange" class="btn btn-warning p-3 m-5 d-none"> Cases > 24 Hours < 48 Hours</button>
<button type="button" id="casesBtnRed" class="btn btn-danger p-3 m-5 d-none"> Cases > 48 Hours</button>
<script>
const ctx = document.getElementById('myChart');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Cases Putaway', 'RBTP'],
        datasets: [{
            label: 'Count of Short Code',
            data: [putaway.length, received.length],
            backgroundColor: [
                'rgba(50, 199, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)'
            ],
            borderColor: [
                'rgba(55, 199, 132, 1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 3
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: 500
            
            }
        }
    }
});
</script>
<style>
#myChart{
    height: 600px !important;
    width: 1500px !important;
}
</style>
<script>

var buttongreen = document.getElementById("casesBtnGreen");
var buttonorange = document.getElementById("casesBtnorange");
var buttonred = document.getElementById("casesBtnRed");
if(morethantwodays.length){
  buttonred.classList.remove("d-none");
    buttonred.prepend(morethantwodays.length);
    // buttongreen.classList.add("d-none");
    // buttonorange.classList.add("d-none");
}
if(lessoneday.length){  
    buttongreen.classList.remove("d-none");
    buttongreen.prepend(lessoneday.length);
    // buttonred.classList.add("d-none");
    // buttonorange.classList.add("d-none");
}
if(morethanoneday.length){
    buttonorange.classList.remove("d-none");
    buttonorange.prepend(morethanoneday.length);
    buttonred.classList.add("d-none");
    buttongreen.classList.add("d-none"); 
}

</script>
</body>
</html>