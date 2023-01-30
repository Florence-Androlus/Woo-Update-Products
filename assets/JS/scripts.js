var app = {
  init: function(event) {
    event.preventDefault();

if(action=="delete"){
  document.getElementById("li-add").classList.remove("active");
  document.getElementById("li-delete").classList.add("active");
  document.getElementById("add").classList.remove("active");
  document.getElementById("delete").classList.add("active");
}
else if(action=="add"){
  document.getElementById("li-add").classList.add("active");
  document.getElementById("li-delete").classList.remove("active");
  document.getElementById("add").classList.add("active");
  document.getElementById("delete").classList.remove("active");
}
  },
};


document.addEventListener('DOMContentLoaded', app.init);
