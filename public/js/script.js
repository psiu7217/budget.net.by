//Nex element toggle (hide/show)
function nextToggle(element) {
    element.nextElementSibling.classList.toggle("none");
}

var a = document.querySelectorAll("a");
for(var i = 0; i < a.length; i++){
    a[i].setAttribute('onclick', ' clickAndDisable(this)');
}

function clickAndDisable(link) {
    // disable subsequent clicks
    link.onclick = function(event) {
        event.preventDefault();
    }
}
