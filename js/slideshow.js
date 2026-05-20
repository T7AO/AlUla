// Slideshow — About page
var cur = 0;
var slides = document.querySelectorAll('.slide');
var dots = document.querySelectorAll('.dot');
function show(n){
  if(n>=slides.length) cur=0;
  if(n<0) cur=slides.length-1;
  slides.forEach(function(s){s.classList.remove('show');});
  dots.forEach(function(d){d.classList.remove('active');});
  slides[cur].classList.add('show');
  dots[cur].classList.add('active');
}
function changeSlide(d){ cur+=d; show(cur); }
function goToSlide(i){ cur=i; show(cur); }
setInterval(function(){ changeSlide(1); }, 5000);
