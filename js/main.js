//---------- LOGIN AREA ----------//

function login(){
    let invalid = "<p>Please fill up everything</p>";
    let username = document.getElementById('username');
    let password = document.getElementById('password');
    let loginbutton = document.getElementById('loginBtn');
    let formdata = new FormData();
    formdata.append("username", username.value);
    formdata.append("password", password.value);
    formdata.append("loginBtn", loginbutton.value);
    if(username.value == "" || password.value == ""){
        document.getElementById('log-message').innerHTML = invalid;
    } else {
        fetch('config.php', {
            method: 'POST',
            body: formdata
        })
        .then(Response => Response.text())
        .then(data => {
            if (data) {
                document.getElementById('log-message').innerHTML = data;
            } else {
                window.location.href = 'home.php';
            }   
        })
        .catch(error => alert("Error: " + error.message));
        return false;
    }
}

//-- SIGNUP AREA --//

function signup(){
    let invalid = "<p>Please fill up everything</p>"
    let username = document.getElementById('username_su');
    let password = document.getElementById('password_su');
    let firstname = document.getElementById('firstname');
    let lastname = document.getElementById('lastname');
    let signupbutton = document.getElementById('signupBtn');
    let formdata = new FormData();
    formdata.append("username", username.value);
    formdata.append("password", password.value);
    formdata.append("firstname", firstname.value);
    formdata.append("lastname", lastname.value);
    formdata.append("signupBtn", signupbutton.value);

    if(username.value == "" || password.value == "" || firstname.value == "" || lastname.value == ""){
        document.getElementById('message').innerHTML = invalid;
    } else {
        fetch('config.php', {
            method: 'POST',
            body: formdata
        })
        .then(Response => Response.text())
        .then(data => {
            if(data.error){
                document.getElementById('log-message').innerHTML = data;
            } else {
                window.location.href = 'index.php';
            }

        })
        .catch(error => alert("Error: " + error.message));
        return false;
    }
}

function showPreview(input) {
    const file = input.files[0];
    const preview = document.getElementById('img-display');

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result; // Set the image source to the file data
        };

        reader.readAsDataURL(file); // Read the file data as a data URL
    } else {
        preview.src = '../uploads/kuru.png'; // Reset to default if no file is selected
    }
}







