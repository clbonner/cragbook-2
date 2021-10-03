import { initApp } from './modules/app.js';
import { getResponseText } from './modules/helpers.js';
// import modules for below

initApp();

// check if user is logged in and display appropriate page
fetch("/api/authentication.php?id=isloggedin")
    .then((response) => {
        return getResponseText(response)
    .then((text) => {
        isLoggedIn = JSON.parse(text);

        if (isLoggedIn) viewDashboard();
        else viewLoginScreen();
    })
});