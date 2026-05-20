// Register form validation
function validateRegistration(){
  var ok=true;
  document.querySelectorAll('.err').forEach(function(e){e.style.display='none';});
  var name=document.getElementById('name').value.trim();
  if(name.length<3){document.getElementById('name-error').style.display='block';ok=false;}
  var uid=document.getElementById('user_id').value.trim();
  if(!/^[0-9]{10}$/.test(uid)){document.getElementById('id-error').style.display='block';ok=false;}
  var mob=document.getElementById('mobile').value.trim();
  if(!/^05[0-9]{8}$/.test(mob)){document.getElementById('mobile-error').style.display='block';ok=false;}
  var em=document.getElementById('email').value.trim();
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)){document.getElementById('email-error').style.display='block';ok=false;}
  var pw=document.getElementById('password').value;
  if(pw.length<6){document.getElementById('password-error').style.display='block';ok=false;}
  var cp=document.getElementById('confirm_password').value;
  if(pw!==cp){document.getElementById('confirm-error').style.display='block';ok=false;}
  var dob=new Date(document.getElementById('dob').value);
  if(dob>=new Date()){alert('Date of birth must be in the past / تاريخ الميلاد يجب أن يكون في الماضي');ok=false;}
  return ok;
}
