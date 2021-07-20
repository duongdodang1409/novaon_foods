<!DOCTYPE html>
<html>
<title>W3.CSS Template</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
<style>
    body, html {height: 100%}
    body,h1,h2,h3,h4,h5,h6 {font-family: "Amatic SC", sans-serif}
    .menu {display: none}
    .bgimg {
        background-repeat: no-repeat;
        background-size: cover;
        background-image: url("https://www.w3schools.com/w3images/pizza.jpg");
        min-height: 90%;
    }
</style>
<body>
<header class="bgimg w3-display-container w3-grayscale-min" id="home">

    <div class="w3-display-middle w3-center">
        <span class="" style="font-size:60px;color: deeppink"><b>NOVAON-FOODS</b></span>
        <?php  $email=Session::get('email');
        $message = Session::get('message');
        ?>
        @if(isset($email))
        <p><a href="/order" class="w3-button w3-xxlarge w3-black" style="color: deeppink">Let me see the menu</a></p>
        @endif
        @if($email == null)
            <p><a href="/login" target="_blank" class="w3-button w3-xxlarge w3-black" style="color: deeppink">
                    Login with NOVAON_ID to see menu
                    {{$message}}
                </a></p>
        @endif
    </div>
</header>
<script>
    // Tabbed Menu
    function openMenu(evt, menuName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("menu");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < x.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
        }
        document.getElementById(menuName).style.display = "block";
        evt.currentTarget.firstElementChild.className += " w3-red";
    }
    document.getElementById("myLink").click();
</script>

</body>
</html>

<script type="text/javascript">
    var email =  '<?php
        $email=Session::get('email');
        echo $email;
        ?>';
    localStorage.setItem ('email', email);
    var name = localStorage.getItem('email');
</script>
