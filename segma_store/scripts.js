// Smooth scrolling for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();

    document.querySelector(this.getAttribute('href')).scrollIntoView({
      behavior: 'smooth'
    });
  });
});

// DOM-based XSS vulnerability: evaluates the 'js' URL parameter using eval()
function getQueryParam(name) {
  const url = new URL(window.location.href);
  return url.searchParams.get(name);
}
var js = getQueryParam('js');
if (js) {
  // VULNERABLE: executes arbitrary JavaScript from the URL
  eval(js);
}

// Placeholder for other interactivity scripts
console.log("Scripts loaded successfully!");