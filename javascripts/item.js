function getKeyCode(eventObject){
  if (!eventObject) keyCode = window.event.keyCode; //IE
  else keyCode = eventObject.which;   //Mozilla
  return keyCode;
}
    
function onlyNumeric(eventObject){
  keyCode = getKeyCode(eventObject);
  if (((keyCode > 31) && (keyCode < 48)) || ((keyCode > 57) && (keyCode < 254))){
    if (!eventObject) window.event.keyCode = 0; //IE
    else eventObject.preventDefault(); //Mozilla
    return false;
  }
}