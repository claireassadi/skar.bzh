'use strict';

function myFunction(x) {
    x.classList.toggle("change");
}


// BACK TO TOP //
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// MODAL //

var captionText = document.getElementById("caption");

var modal = document.getElementById('myModal');
var modalImg = document.getElementById("img01");
var span = document.getElementsByClassName("close")[0];
var i;

span.onclick = function() { 
    modal.style.display = "none";
}

/********* 1ERE RANGEE ********/

var a = document.getElementById("rangee_photos_1");
var b = a.getElementsByTagName("*");

for (i = 0; i < b.length; i++) {

	    b[i].onclick = function(){
	    modal.style.display = "block";
	    modalImg.src = this.src;
	    captionText.innerHTML = this.alt;
	}
}

/********* 2EME RANGEE ********/

var c = document.getElementById("rangee_photos_2");
var d = c.getElementsByTagName("*");

for (i = 0; i < d.length; i++) {

	    d[i].onclick = function(){
	    modal.style.display = "block";
	    modalImg.src = this.src;
	    captionText.innerHTML = this.alt;
	}
}

/********* graphismes1 ********/

var e = document.getElementById("graphismes1");
var f = e.getElementsByTagName("*");

for (i = 0; i < f.length; i++) {

	    f[i].onclick = function(){
	    modal.style.display = "block";
	    modalImg.src = this.src;
	    captionText.innerHTML = this.alt;
	}
}

/********* graphismes2 ********/

var e = document.getElementById("graphismes2");
var f = e.getElementsByTagName("*");

for (i = 0; i < f.length; i++) {

	    f[i].onclick = function(){
	    modal.style.display = "block";
	    modalImg.src = this.src;
	    captionText.innerHTML = this.alt;
	}
}

/********* graphismes3 ********/

var e = document.getElementById("graphismes3");
var f = e.getElementsByTagName("*");

for (i = 0; i < f.length; i++) {

	    f[i].onclick = function(){
	    modal.style.display = "block";
	    modalImg.src = this.src;
	    captionText.innerHTML = this.alt;
	}
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
	if (event.target == modal) {
	  modal.style.display = "none";
	}
}