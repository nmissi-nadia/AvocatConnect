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
//////////////////////////////////////////////


const signup_btn = document.getElementById("button1")
signup_btn.addEventListener("click", (e) => {
  e.preventDefault()
  signup()
})

const signin_btn = document.getElementById("button2")
signin_btn.addEventListener("click", (e) => {
  e.preventDefault()
  login()
})
async function signup() {
  try {

    //console.log(1)
    let email = document.getElementById("email").value
    let Name = document.getElementById("name").value
    let password = document.getElementById("password").value;
    let Phone_No = document.getElementById("phone").value;
    let city = document.getElementById("city").value;
    let age = document.getElementById("age").value;
    let obj = {
      Name,
      email,
      password,
      Phone_No,
      city,
      age
    }
    console.log(obj)
    fetch(`${baseurl}/user/register`, {
      method: "POST",
      headers: { 'content-type': 'application/json' },
      body: JSON.stringify(obj)
    })
      .then(res => res.json())
      .then((res) => {
        localStorage.setItem('verify', res.token);
        alert(res.msg);
        if (res.msg === "user already exist please Login!") {

        } else {
          window.location.href = "./verifyOTP.html"
        }

      })
  }
  catch (error) {
    console.log(error)
    alert("Something going wrong")
  }
}
/////////////////


///////////////////

async function login() {
  try {
    console.log(document.getElementById("email-log").value);
    let email = document.getElementById("email-log").value;
    let password = document.getElementById("password-log").value;
    let role = document.getElementById("role").value;

    let obj = {
      email,
      password,
      role
    };

    const response = await fetch("login_avocat.php", {
      method: "POST",
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(obj)
    });

    const res = await response.json();
    alert(res.msg);

    if (res.status === 'success') {
      if (res.role === 'Client') {
        window.location.href = "client/dashboard.php";
      } else if (res.role === 'Avocat') {
        window.location.href = "avocat/dashboard.php";
      }
    } else {
      alert("Erreur de connexion : " + res.msg);
    }
  } catch (error) {
    alert("Une erreur est survenue lors de la connexion.");
    console.error(error);
  }
}

