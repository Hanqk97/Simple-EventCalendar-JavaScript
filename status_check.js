function checkLogin() {
    const xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "checkLogin.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //console.log("year="+dateYear+"&month="+dateMonth+"&day="+dateDay);
    xmlHttp.addEventListener("load", function(event){
        const data = JSON.parse(event.target.responseText);
        if(data.login){
            token = data.token;
            $("fieldset.notLoggedIn").toggle();
            $(".loggedIn").toggle();
        }
    }, false);
    xmlHttp.send(null);
}

