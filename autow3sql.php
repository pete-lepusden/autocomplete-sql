<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
* {
  box-sizing: border-box;
}

body {
  font: 16px Arial;  
}
/* For forms */
.flexform {
 padding:0.01em 16px;
}

.flex-container {
  display: flex;
  flex-wrap: wrap;
	background-color: #fff;
  }
.flex-container .base {
	min-width: 3rem;
	width: 100%;
  font-size: 1rem;
  margin: 1px 2px;
}
/*the container must be positioned relative:*/
.autocomplete {
  position: relative;
  display: inline-block;
}

input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}

input[type=text] {
  background-color: lightblue;
  width: 100%;
}

input[type=submit] {
  background-color: DodgerBlue;
  color: #fff;
  cursor: pointer;
}

.autocomplete {
  position: relative;
  display: inline-block;
}
.autocomplete-items {
  border: 1px solid #d4d4d4;
  list-style-type: none;
  margin:inherit;
  padding-inline-start: 0;
}
.autocomplete-items li  {
  padding: .3rem;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items li:hover {
  background-color: #e9e9e9; 
}

.autocomplete-active {
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
</style>
</head>     
<body>

<h2>Autocomplete</h2>

<p>Start typing:</p>

<!--Make sure the form has the autocomplete function switched off:-->
<form class="flexform" autocomplete="off" action="/action_page.php">
  <section class="flex-container">
    <div class="baae" style="width:300px;">
      <input type="text" name="countryfilter" id="countryfilter" value="" placeholder="Country">
    </div>
    <div class="base" style="width:300px;">
      <input type="text" name="pagefilter" id="pagefilter" value="" placeholder="Page Filter">
    </div>
    <div class="base" style="width:300px;">
      <input type="text" name="columnfilter" id="columnfilter" value="" placeholder="Column Filter">
    </div>
  </section>

  <input type="submit">
</form>

<script>
function autocomplete(obj) {
  if (obj.inp == null) { return false; }
  var currentFocus;
  //execute a function when someone writes in the text field:
  obj.inp.addEventListener("input", function(e) {
    var i, val = this.value;
    //close any already open lists of autocompleted values
    closeAllLists();
    if (!val) { return false; }
    var timer = setTimeout(fetch(this,obj),200);

    function fetch(elm,obj){
      var xhr = new XMLHttpRequest();
      var data = new FormData();
      data.append('search', val);
      if (obj.data !== null) {
        for (let i in obj.data) {
          data.append(i, obj.data[i]);
        }
      }
      xhr.open('POST', obj.source);
      xhr.onload = function () { console.log(this.response); drawli(elm, JSON.parse(this.response)); };
      xhr.send(data);
    }
    function drawli(elm,arr) {
      currentFocus = -1;
      //create a UL element that will contain the items (values):
      var a = document.createElement("ul");
      a.setAttribute("id", elm.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      //append the UL element as a child of the autocomplete container:
      elm.parentNode.appendChild(a);
      //for each item in the array...
      for (i = 0; i < Object.keys(arr).length; i++) {
        //create a LI element for each matching element:
        var b = document.createElement("li");
        //make the matching letters bold:
        b.innerHTML = "<strong>" + arr[i].value.substr(0, val.length) + "</strong>";
        b.innerHTML +=arr[i].value.substr(val.length);
        //insert a input field that will hold the current array item's value:
        b.addEventListener("click", function(e) {
          obj.inp.value = e.target.textContent;
          closeAllLists();
        });
        a.appendChild(b);
      }
    }
  });
  //execute a function presses a key on the keyboard:
  obj.inp.addEventListener("keydown", function(e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      //If the arrow DOWN key is pressed, increase the currentFocus variable:
      currentFocus++;
      //and and make the current item more visible:
      addActive(x);
    } else if (e.keyCode == 38) { //up
      //If the arrow UP key is pressed, decrease the currentFocus variable:
      currentFocus--;
      //and and make the current item more visible:
      addActive(x);
    } else if (e.keyCode == 13) {
      //If the ENTER key is pressed, prevent the form from being submitted,
      e.preventDefault();
      if (currentFocus > -1) {
        //and simulate a click on the "active" item:
        if (x) x[currentFocus].click();
      }
    }
  });
  function addActive(x) {
    //a function to classify an item as "active":
    if (!x) return false;
    //start by removing the "active" class on all items:
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    //add class "autocomplete-active":
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    //a function to remove the "active" class from all autocomplete items:
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    //close all autocomplete lists in the document, except the one passed as an argument:
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != obj.inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  //execute a function when someone clicks in the document:
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
} 
autocomplete({
                inp: document.getElementById("countryfilter"),
                source: "//lepusden.com/blog/autocomplete/data/mapajaxdao.php",
                dataType: "json",
                data: {
                  method: "countryfilter",
                  name: "core"
                },
              });

              autocomplete({
                inp: document.getElementById("pagefilter"),
                source: "//lepusden.com/blog/autocomplete/data/mapajaxdao.php",
                dataType: "json",
                data: {
                          method: "pagefilter",
                          name: "core"
                      },
              },
              );
        
              autocomplete({
                inp: document.getElementById("columnfilter"),
                source: "//lepusden.com/blog/autocomplete/data/mapajaxdao.php",
                dataType: "json",
                data: {
                          method: "columnfilter",
                          name: "core"
                      },
              },
              );

</script>

</body>
</html>
