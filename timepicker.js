// variables
const inputs = document.querySelectorAll("input"),
  error = document.querySelector("#error");

// functions
const validInput = (val) => {
  // prevent user from inserting non-digits
  return val.replace(/[^0-9]+/g, "");
};
const setMax = (input) => {
  let id = input.getAttribute("id"),
    max = 0;
  switch (id) {
    case "hours":
      max = 23;
      break;
    default:
      max = 60;
  }

  return max;
};
const throwErr = (mssg, input, invalid=false) => {
  // control display of error
  if(invalid === true) {
    input.classList.add("invalid");
    error.style.visibility = "visible";
    error.innerHTML = `<small>${mssg}</small>`;
  } else {
    input.classList.remove("invalid");
    error.style.visibility = "hidden";
    // error.innerHTML = "";
  }
}

// actions
inputs.forEach((input) => {
  let max = setMax(input),
      isInvalid = false,
      mssg;
  
  input.addEventListener("focus", (e) => {
    // Blank input on default values
    if(e.target.value === "0" || e.target.value === "00") {
      e.target.value = "";
    } 
    
    // Show error mssg for invalid inputs when not corrected
    if(e.target.classList.contains('invalid')) {
      isInvalid = true;
      throwErr(mssg, input, isInvalid);
    } else {
      isInvalid = false;
      throwErr(mssg, input);
    }
  });
  
  input.addEventListener("keyup", (e) => {
    let val = e.target.value;
    
    // only allow digits
    input.value = validInput(val);
    
    // restrict input characters
    if(e.target.value.length > 2) {
      input.value = e.target.value.substring(0,2);
    }
    
    // set max value allowed
    if (+input.value > max) {
      // handle error
      isInvalid = true;
      // e.target.classList.add('invalid');
      mssg = `It can't be more than ${max} ${input.getAttribute(
        "id"
      )}! Try again.`;
    
      throwErr(mssg, input, isInvalid);
    } else {
      isInvalid = false;
      throwErr(mssg, input);
    }
  });

});
