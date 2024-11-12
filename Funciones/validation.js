
function validarRegistro(){
  let email = document.forms["login"]["email"].value;
  let password = document.forms["login"]["password"].value;
  let expression = "^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$";

  let test = email.match(expression);

  if(email == ""){
    alert("Por favor, introduzca un email.");
    return false;
  }

  if(password == ""){
    alert("Por favor, introduzca una contraseña.");
    return false;
  }


  return true;
}

function validarConcurso(){
  let name = document.forms["registroConcurso"]["name"].value;
  let hashtag = document.forms["registroConcurso"]["hashtag"].value;
  let description = document.forms["registroConcurso"]["description"].value;
  let end_date = document.forms["registroConcurso"]["end_date"].value;


  let nameSize = name.length;
  let hashtagSize = hashtag.length;
  let descriptionSize = description.length;

  if(name == ""){
    alert("Por favor, introduzca un nombre.");
    return false;
  }

  if(nameSize > 30){
    alert("El nombre del concurso es demasiado largo.");
    return false;
  }

  if(hashtag == ""){
    alert("Por favor, introduzca un hashtag.");
    return false;
  }

  if(hashtagSize > 30){
    alert("El hashtag es demasiado largo.")
    return false;
  }

  if(end_date == ""){
    alert("Por favor, introduzca una fecha de finalización.");
    return false;
  }

  if(description == ""){
    alert("Por favor, introduzca una descripción.")
    return false;
  }

  if(descriptionSize > 80){
    alert("La descripción es demasiado larga.")
    return false;
  }

  return true;

}

function validarFoto(){
  let name = document.forms["registroFoto"]["name"].value;
  let author = document.forms["registroFoto"]["author"].value;
  let description = document.forms["registroFoto"]["description"].value;

  let nameSize = name.length;
  let authorSize = author.length;
  let descriptionSize = description.length;

  if(name == ""){
    alert("Por favor, introduzca un nombre para la foto.");
    return false;
  }

  if(nameSize > 30){
    alert("El nombre de la foto es demasiado largo.");
    return false;
  }

  if(author == ""){
    alert("Por favor, introduzca un nombre para el autor.");
    return false;
  }

  if(authorSize > 30){
    alert("El nombre del autor es demasiado largo.")
    return false;
  }

  if(description == ""){
    alert("Por favor, introduzca una descripción.")
    return false;
  }

  if(descriptionSize > 80){
    alert("La descripción es demasiado larga.")
    return false;
  }

  return true;

}

function validarUsuario(){
  let name = document.forms["registroUsuario"]["name"].value;
  let email = document.forms["registroUsuario"]["email"].value;
  let password = document.forms["registroUsuario"]["password"].value;
  let description = document.forms["registroUsuario"]["description"].value;
  let expression = "^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$";

  let test = email.match(expression);

  let nameSize = name.length;
  let emailSize = email.length;
  let passwordSize = password.length;
  let descriptionSize = description.length;

  if(name == ""){
    alert("Por favor, introduzca un nombre.");
    return false;
  }

  if(nameSize > 30){
    alert("El nombre de usuario es demasiado largo.");
    return false;
  }

  if(email == ""){
    alert("Por favor, introduzca un email.");
    return false;
  }

  if(emailSize > 30){
    alert("El email es demasiado largo.")
    return false;
  }

  if(!test){
    alert("Por favor, introduzca un email valido.");
    return false;
  }

  if(password == ""){
    alert("Por favor, introduzca una contraseña.");
    return false;
  }

  if(passwordSize > 30){
    alert("La contraseña es demasiado larga.")
    return false;
  }

  if(descriptionSize > 80){
    alert("La descripción es demasiado larga.")
    return false;
  }

  return true;

}
