// ============================================
// Bilingual AI Chatbot — rule-based knowledge
// ============================================
var KB = [
  {k:['hegra','madain','الحجر','مدائن'],
   ar:'🏛️ الحِجر (مدائن صالح) أول موقع سعودي في قائمة اليونسكو، فيه أكثر من 110 مقبرة نبطية محفوظة عمرها يتجاوز ألفي عام. الجولة 250 ريال وتستغرق 4 ساعات. أفضل وقت من أكتوبر إلى أبريل.',
   en:'🏛️ Hegra (Madain Saleh) is Saudi Arabia\'s first UNESCO site, with 110+ Nabataean tombs over 2,000 years old. Tour: 250 SAR, 4 hours. Best time: October–April.'},
  {k:['farid','فريد','قصر'],
   ar:'🪨 قصر الفريد أشهر مقابر الحِجر، منحوت في صخرة واحدة منفردة. أيقونة العُلا، وأجمل وقت لزيارته عند الغروب. الجولة 200 ريال.',
   en:'🪨 Qasr Al-Farid is the most famous tomb in Hegra, carved from a single rock. AlUla\'s icon, best at sunset. Tour: 200 SAR.'},
  {k:['old town','بلدة','قديمة'],
   ar:'🏘️ البلدة القديمة فيها نحو 900 منزل طيني عمرها مئات السنين. الجولة 120 ريال وتستغرق ساعتين، والمساء أجمل وقت.',
   en:'🏘️ AlUla Old Town has ~900 mud-brick houses centuries old. Tour: 120 SAR, 2 hours. Evenings are best.'},
  {k:['wildlife','nature','حياة','برية','غزلان','طبيعة'],
   ar:'🦌 محمية شرعان الطبيعية موطن للغزلان العربية والنمر العربي. رحلة السفاري البرية 180 ريال وتشمل مرشدًا متخصصًا.',
   en:'🦌 Sharaan Nature Reserve is home to Arabian gazelles and the Arabian leopard. Wildlife safari: 180 SAR with an expert guide.'},
  {k:['skydiv','قفز','مظلي','مغامر','adventure'],
   ar:'🪂 القفز المظلي فوق العُلا تجربة لا تُنسى! شاهد الصحراء والمقابر من السماء. السعر 900 ريال ويشمل التدريب والمعدات.',
   en:'🪂 Skydiving over AlUla is unforgettable! See the desert and tombs from the sky. 900 SAR including training and gear.'},
  {k:['best place','must','افضل اماكن','أفضل','recommend','رشح'],
   ar:'✨ أفضل توصياتي:\n1. 🏛️ الحِجر — المقابر النبطية\n2. 🪨 قصر الفريد — للتصوير وقت الغروب\n3. 🏘️ البلدة القديمة — للتراث\n4. 🦌 محمية شرعان — للطبيعة',
   en:'✨ My top picks:\n1. 🏛️ Hegra — Nabataean tombs\n2. 🪨 Qasr Al-Farid — sunset photos\n3. 🏘️ Old Town — heritage\n4. 🦌 Sharaan Reserve — nature'},
  {k:['best time','when','وقت','متى','موسم','weather','طقس'],
   ar:'🌤️ أفضل وقت لزيارة العُلا من أكتوبر إلى أبريل (15-28°م). تجنّب الصيف (يونيو-أغسطس) لشدة الحرارة.',
   en:'🌤️ Best time to visit AlUla: October–April (15–28°C). Avoid summer (June–August) — very hot.'},
  {k:['book','حجز','احجز','reserve','tour'],
   ar:'🎫 الحجز سهل! روح صفحة "احجز جولة"، اختر الوجهة والتاريخ والوقت. لازم تسجّل أولًا.',
   en:'🎫 Booking is easy! Go to "Book a Tour", pick destination, date, and time. You must register first.'},
  {k:['register','sign up','تسجيل','حساب'],
   ar:'📝 للتسجيل اضغط "حساب جديد" في القائمة، وأدخل بياناتك (الاسم، الهوية، الجوال، البريد).',
   en:'📝 To register, click "Register" in the menu and enter your details (name, ID, mobile, email).'},
  {k:['login','sign in','دخول'],
   ar:'🔑 اضغط "دخول" وأدخل بريدك وكلمة المرور. بعد الدخول تقدر تحجز الجولات.',
   en:'🔑 Click "Sign In" and enter your email and password. After login you can book tours.'},
  {k:['pack','wear','bring','ملابس','أجهز','أحضر'],
   ar:'🎒 نصائح: حذاء مريح، ملابس فاتحة محتشمة، واقي شمس ونظارة وقبعة، ماء، كاميرا، وجاكيت خفيف للمساء.',
   en:'🎒 Tips: comfortable shoes, light modest clothing, sunscreen, sunglasses, hat, water, camera, and a light jacket for evenings.'},
  {k:['price','cost','سعر','كم','تكلفة','fee'],
   ar:'💰 الأسعار:\n• الحِجر: 250 ريال\n• قصر الفريد: 200 ريال\n• البلدة القديمة: 120 ريال\n• السفاري البري: 180 ريال\n• القفز المظلي: 900 ريال',
   en:'💰 Prices:\n• Hegra: 250 SAR\n• Qasr Al-Farid: 200 SAR\n• Old Town: 120 SAR\n• Wildlife Safari: 180 SAR\n• Skydiving: 900 SAR'},
  {k:['vision','2030','رؤية'],
   ar:'🇸🇦 العُلا وجهة محورية في رؤية السعودية 2030 لدعم السياحة والحفاظ على التراث والتحول الرقمي، وتقودها الهيئة الملكية لمحافظة العُلا.',
   en:'🇸🇦 AlUla is a flagship Vision 2030 destination supporting tourism, heritage preservation, and digital transformation, led by the Royal Commission for AlUla.'},
  {k:['hello','hi','مرحبا','السلام','هلا','اهلا'],
   ar:'👋 أهلًا! أنا هنا أساعدك تخطط رحلة لا تُنسى للعُلا. وش تحب تعرف؟',
   en:'👋 Hello! I\'m here to help you plan an unforgettable AlUla trip. What would you like to know?'},
  {k:['thank','شكر','مشكور'],
   ar:'😊 العفو! أتمنى لك رحلة ممتعة في العُلا. لا تتردد تسألني أي شيء ثاني!',
   en:'😊 You\'re welcome! Have a great trip to AlUla. Feel free to ask anything else!'},
  {k:['contact','phone','اتصال','تواصل','هاتف'],
   ar:'📞 تواصل معنا:\n📧 info@alula-vision.sa\n📞 +966 11 258 9999',
   en:'📞 Contact us:\n📧 info@alula-vision.sa\n📞 +966 11 258 9999'}
];
function isAr(){ return document.body.getAttribute('dir')==='rtl'; }
var DEF = {ar:'🤔 ما فهمت سؤالك تمامًا. جرّب تسأل عن الأماكن، الحجز، الأسعار، أفضل وقت للزيارة، أو أي شيء عن العُلا.',
           en:'🤔 I didn\'t quite get that. Try asking about places, booking, prices, best time to visit, or anything about AlUla.'};
function add(text,who){
  var box=document.getElementById('chat-msgs');
  var d=document.createElement('div'); d.className='msg '+who;
  var b=document.createElement('div'); b.className='bubble'; b.textContent=text;
  d.appendChild(b); box.appendChild(d); box.scrollTop=box.scrollHeight;
}
function reply(msg){
  var m=msg.toLowerCase();
  for(var i=0;i<KB.length;i++)
    for(var j=0;j<KB[i].k.length;j++)
      if(m.indexOf(KB[i].k[j])!==-1) return isAr()?KB[i].ar:KB[i].en;
  return isAr()?DEF.ar:DEF.en;
}
function sendMessage(){
  var inp=document.getElementById('user-input');
  var t=inp.value.trim(); if(!t) return;
  add(t,'user'); inp.value='';
  setTimeout(function(){ add(reply(t),'bot'); },450);
}
function askQ(el){
  var span=isAr()?el.querySelector('.lang-ar'):el.querySelector('.lang-en');
  document.getElementById('user-input').value=span.textContent;
  sendMessage();
}
