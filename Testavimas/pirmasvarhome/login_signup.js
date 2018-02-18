$(document).ready(function(){
  // Popups
  $(".open-login").click(function(){
    $(".login").fadeIn("slow");
  });
  $(".close-login").click(function(){
    $(".login").fadeOut("slow");
  });
  $(".open-signup").click(function(){
    $(".signup").fadeIn("slow");
  });
  $(".close-signup").click(function(){
    $(".signup").fadeOut("slow");
  });

  // Password validation

  var password = document.getElementById("pass")
  , password2 = document.getElementById("pass2");

  function validatePassword(){
    if(password.value != password2.value) {
      password2.setCustomValidity("Passwords Don't Match");
    } else {
      password2.setCustomValidity('');
    }
  }

  password2.onkeyup = validatePassword;

  // generating birthday data dropdown lists

  var min = 1980,
    max = 2018,
    select = document.getElementById('year');

  for (var i = min; i<=max; i++){
      var opt = document.createElement('option');
      opt.value = i;
      opt.innerHTML = i;
      select.appendChild(opt);
  }
  var min = 1,
      max = 31,
      select = document.getElementById('day');

  for (var i = min; i<=max; i++){
      var opt = document.createElement('option');
      opt.value = i;
      opt.innerHTML = i;
      select.appendChild(opt);
  }
}); 