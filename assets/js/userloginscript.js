const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');
const leftPanel = document.querySelector('.left-panel');
const rightPanel = document.querySelector('.right-panel');

signUpButton.addEventListener('click', () => {
  container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
  container.classList.remove("right-panel-active");
});
/////////////

////////////////
container.style.backgroundImage = "url('../images/safari.png')";
container.style.backgroundRepeat = "no-repeat";
container.style.backgroundSize = "cover";
container.style.position = "relative";



