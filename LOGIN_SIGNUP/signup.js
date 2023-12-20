// function gia na mas emfanizei to password
function showpass() 
{
    var x = document.getElementById("password");
    if (x.type === "password") 
    {
      x.type = "text";
    } else
    {
      x.type = "password";
    }
}



//gia idio password elegxos 
function same_pass()
{
  var password = document.getElementById("password");
  var confirm_pass = document.getElementById("con_password"); 
  if(password.value != confirm_pass.value ) 
   {
     confirm_pass.setCustomValidity("Passwords don't match!")
   } else{
     confirm_pass.setCustomValidity('')
   }
  password.onchange = validatePassword; 
  confirm_pass.onkeyup = validatePassword; 
}