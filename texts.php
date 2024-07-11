<?php
$json = json_decode(getH("users",$from_id),1);
$em = array("🌻🥺","🤕🍁","🥀😢","💐😎","😏🌺","🙂🌹","🌚🍂","🌝🍃","🌼🙃","😋🌹");
$emr = rand(0,count($em)-1);
$em = $em[$emr];
if($text == "/start"){
$json["step"] = null;
if(!isset($json["points"])){
javan("sendvideo",[
"chat_id"=>$from_id,
"video"=>"https://t.me/BoTeeo/7",
"caption"=>"شرح كيف استخدم بوت زياده الاعضاء",
]);
}
if(!isset($json["points"])){
$json["points"] = "1";
}
setH("users",$from_id,json_encode($json));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاطك: ".$json["points"]." 💰
اجمع النقاط واستبدلها بلأعضاء ".$em."
🆔: `#".$from_id."`  ".(($json["join_by_link"] == 0) ? "0" : $json["join_by_link"])." 🌀",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 طلب مشتركين 👤",'callback_data'=>'sell'],['text'=>"💰 جمع النقاط 💰",'callback_data'=>'collect']],
[['text'=>"📤 تحويل نقاط 📤",'callback_data'=>'send'],['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"❗ شرح البوت ❗",'callback_data'=>'help']],
]
])
]);
bth($from_id);
javan("setMyCommands",[
"commands"=>json_encode([
['command'=>"/start",'description'=>'ابدأ ✅'],
['command'=>"/help",'description'=>'شرح البوت ⁉️'],
['command'=>"/video",'description'=>'كيف صنع حساب جديد 🆔'],
])
]);
exit;
}
if($text == "/help"){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"شرح استخدام بوت زياده اعضاء
https://t.me/BoTeeo/7

تم طلب اعضاء ولم يدخلو للقناة ؟ 
https://t.me/namkar1/104

لماذا الاعضاء يغادرون قناتي ؟
https://t.me/namkar1/105

لماذا يجب رفع البوت مشرف في قناتي ؟
https://t.me/namkar1/144

لماذا لا استطيع الانضمام بقنوات ؟
https://t.me/namkar1/137

يجب ان لاتغادر القنوات بعد جمع النقاط منها لئنك سوف تجبر على الرجوع اليها فيما اذا حاولت طلب اعضاء والتليكرام لايسمح بأن تكون لديك اكثر من 500 قناة فأذا اجبرك البوت على الرجوع الى القنوات وانته بلفعل قد وصلت الى 500 قناة ستكون بمشكله ولن تستطيع انفاق نقاطك 😅
ينصح بعمل حسابات جديده واستخدام البوت اليك طريقة عمل حساب جديد /video.",
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
]);
exit;
}
if($text == "/video"){
javan("sendvideo",[
"chat_id"=>$from_id,
"video"=>"https://t.me/YOTU3ER/14",
"caption"=>"بلفيديو طريقة عمل حساب ببرنامج 2ndLine تكون مشابة لبرنامج TextNow رمز الدولة للأرقام المتكونة من هذه البرامج 1+ بعض الاجهزه لاتعمل عليها هذة البرامج ولا تعطي ارقام جربها  ويمكنك ايضاً استعمال اليوتيوب لتبحث عن طرق وبرامج لعمل ارقام اجنبيه  🔰",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"TextNow+1 📥",'callback_data'=>'app1']],
[['text'=>"2ndline+1 📥",'callback_data'=>'app2']],
[['text'=>"رجوع 🔙",'callback_data'=>'back']],
]
])
]);
exit;
}
if(strstr($text,"/start ")){
$code = explode("/start ",$text)[1];
if(strstr($code,"code_")){
$code = explode("code_",$code)[1];
$cod = base64_decode($code);
$ex = explode("&_",$cod);
$username = $ex[2];
$point = $ex[1];
if(in_array($code,$admin["codes"])){
if(in_array($code,$json["codes"])){
$use = true;
}else{
$use = false;
}
if($use){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"لقد قمت بالدخول الى هذهِ القناة مسبقاً 🎖.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
}else{
if(checkJoin("@".$username,$from_id) == "true"){
$json["points"] = $json["points"] + $point;
$json["codes"][] = $code;
setH("users",$from_id,json_encode($json));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم الانضمام بنجاح 😉❤️
حصلت على *".$point."* 💰",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"parse_mode"=>markdown,
"text"=>"انضم في القناة [@".$username."] ✅،
واحصل على ".$point." نقطة 💰.",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ تحقق من الانضمام ♻️",'url'=>"https://t.me/".$bot_username."?start=code_".$code]],
]
])
]);
}
}
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"هذه القناة غير صالحة او قد تم حذفها من مالك البوت 🎖",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
}
exit;
}
$get = $json;
$json["step"] = null;
if(!isset($json["points"])){
$json["points"] = "1";
$admin = explode("/start ",$text)[1];
$json["from"] = $admin;
setH("users",$from_id,json_encode($json));
$join = checkJoin("@namkar1",$from_id);
$truee = getTrue($admin);
if($join == "true" or $truee == null){
javan("sendvideo",[
"chat_id"=>$from_id,
"video"=>"https://t.me/BoTeeo/7",
"caption"=>"شرح كيف استخدم بوت زياده الاعضاء",
]);
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاطك: ".$json["points"]." 💰
اجمع النقاط واستبدلها بلأعضاء ".$em."
🆔: `#".$from_id."`  ".(($json["join_by_link"] == 0) ? "0" : $json["join_by_link"])." 🌀",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 طلب مشتركين 👤",'callback_data'=>'sell'],['text'=>"💰 جمع النقاط 💰",'callback_data'=>'collect']],
[['text'=>"📤 تحويل نقاط 📤",'callback_data'=>'send'],['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"❗ شرح البوت ❗",'callback_data'=>'help']],
]
])
]);
if($truee != null){
if(!$get and json_decode(getH("users",$admin)) != null){
@$json = json_decode(getH("users",$admin),1);
$xadmin = json_decode(file_get_contents("admin.json"),1);
$json["points"] = $json["points"] + $xadmin["linkp"];
$json["join_by_link"] = $json["join_by_link"] + 1;
setH("users",$admin,json_encode($json));
$st = "[$first_name](tg://user?id=$from_id)";
javan("sendmessage",[
"chat_id"=>$admin,
"text"=>"قام هذا الشخص بالدخول الى رابط الدعوة الخاص بك 🎖،

".$st."

عدد نقاطك 💰: *".$json["points"]."*.",
"parse_mode"=>"markdown",
]);
}
}
exit;
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"يجب ان تنضم في قناة البوت اولاً @namkar1 👨🏻‍💻🍂 التي تحتوي ع المشاكل التي قد تواجهها والمعلومات التي ستساعدك في زيادة قناتك بشكل جيد وأخر اخبار وعروض البوت ,ويتم نشر فيها القنوات التي تعطي 5 نقاط مقابل الانضمام فيها فلا تقم بكتم القناة ✨.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"تحقق ✅",'callback_data'=>'run_javan']],
]
])
]);
exit;
}
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاطك: ".$json["points"]." 💰
اجمع النقاط واستبدلها بلأعضاء ".$em."
🆔: `#".$from_id."`  ".(($json["join_by_link"] == 0) ? "0" : $json["join_by_link"])." 🌀",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 طلب مشتركين 👤",'callback_data'=>'sell'],['text'=>"💰 جمع النقاط 💰",'callback_data'=>'collect']],
[['text'=>"📤 تحويل نقاط 📤",'callback_data'=>'send'],['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"❗ شرح البوت ❗",'callback_data'=>'help']],
]
])
]);
}
}elseif($json["step"] == null){
if(!isset($json["points"])){
javan("sendvideo",[
"chat_id"=>$from_id,
"video"=>"https://t.me/BoTeeo/7",
"caption"=>"شرح كيف استخدم بوت زياده الاعضاء",
]);
}
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاطك: ".$json["points"]." 💰
اجمع النقاط واستبدلها بلأعضاء ".$em."
🆔: `#".$from_id."`  ".(($json["join_by_link"] == 0) ? "0" : $json["join_by_link"])." 🌀",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 طلب مشتركين 👤",'callback_data'=>'sell'],['text'=>"💰 جمع النقاط 💰",'callback_data'=>'collect']],
[['text'=>"📤 تحويل نقاط 📤",'callback_data'=>'send'],['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"❗ شرح البوت ❗",'callback_data'=>'help']],
]
])
]);
javan("setMyCommands",[
"commands"=>json_encode([
['command'=>"/start",'description'=>'تشغيل البوت'],
['command'=>"/help",'description'=>'المساعدة'],
])
]);
exit;
}
if($json["step"] == "sell"){
if(file_get_contents($text) != null){
if(strstr($text,"/joinchat/")){
$link = end(explode("/",$text));
$username = "-100".unpack("N",base64_decode(strtr($link,"-_","+/")))[1];
$idr = $username;
}else{
$ex = explode(".me/",$text)[1];
if(strstr($ex,"/")){
$username = explode("/",$ex)[0];
$idr = getID($username);
}else{
$username = $ex;
$idr = getID($username);
}
}
}else{
$username = strtolower(str_replace("@","",$text));
$idr = getID($username);
}
@$sdr = json_decode(file_get_contents("admin.json"),1);
if(in_array($idr,$sdr["kicks"])){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"هذهِ القناة محظورة من قبل صاحب البوت 🔕.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'sell']],
]
])
]);
exit;
}
$chy = json_decode(getNH("channels"),1);
$chs = $chy[$idr];
if($chs["points"] != 0){
if($chs["admin"] == $from_id){
$data = array_keys($chy);
$z = floor($chs["points"]/3);
if($z == 0){
$z = "0";
}
$c = floor(($chs["points_o"] - $chs["points"])/3);
if($c == 0){
$c = "0";
}
$ready = gmdate("H:i:s d-m-Y", $chs["time"]);
$cc = array_search($idr,$data)+1;
$uss = getUsername($idr);
if($uss == null){
$link = javan("getchat",[
"chat_id"=>$idr
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$idr
])->result;
if($link == null){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"يرجى ارسال رابط صحيح 🌿",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'sell']],
]
])
]);
exit;
}
}
$uss = "[".getName($idr)."](".$link.")";
}else{
$uss = "[@".$uss."]";
}
$infoch = "➖ الطلب: ".$uss." ⭐،
➖ الحالة: جاري أضافة الاعضاء ⏰ ،
➖ تسلسل طلبك: *".$cc."* 💡،
➖ عدد الدخول: *".$c."* 👤،
➖ عدد المتبقين: *".$z."* 👤،
➖ العدد المطلوب: *".($z+$c)."* 👤،
➖ وقت البدء: *".($ready)."* 🔥.
----------------------------";
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"يوجد لهذهِ القناة او المجموعه طلب قيد العمل 😉،
حاول مجدداً عند اكتمال طلبك 🛎.
➖➖➖➖➖➖➖➖➖➖
معلومات الطلب 😅:

".$infoch,
"parse_mode"=>"markdown",
'disable_web_page_preview'=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'sell']],
]
])
]);
exit;
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"يوجد لهذهِ القناة او المجموعه طلب قيد العمل 😉،
حاول مجدداً في وقتٍ لاحق 🛎.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'sell']],
]
])
]);
exit;
}
}
if($username == null or strstr($username," ") or strstr($username,"\n")){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"ارسل معرف او رابط القناة فقط
 بدون وصف وبدون كلام زايد
 معرف فقط 😅✅",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'sell']],
]
])
]);
exit;
}
$idbot = explode(":",$token)[0];
$j = javan("getchatmember",[
"chat_id"=>$idr,
"user_id"=>$idbot,
]);
if($j->result->status == "administrator"){
$json["step"] = "#sell2_".$idr;
setH("users",$from_id,json_encode($json));
$max = $json["points"];
$max = floor($max / 3);
if($idr == $username){
$username = "[".getName($idr)."](".$text.")";
}else{
$username = "@[".$username."]";
}
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاطك 💰: *".$json["points"]."*،

الطلب لـ ⭐: ".$username."،
كل *3* نقطة 💰 = *1* مشترك 👤،
يمكنك شراء *".$max."* مشترك 👤،

أختر عدد المشتركين المطلوب شرائهم 🎖.",
"parse_mode"=>"markdown",
'disable_web_page_preview'=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 30",'callback_data'=>'#javan_30']],
[["text"=>"👤 40",'callback_data'=>'#javan_40'],['text'=>"👤 50",'callback_data'=>'#javan_50']],
[["text"=>"👤 60",'callback_data'=>'#javan_60'],['text'=>"👤 70",'callback_data'=>'#javan_70']],
[["text"=>"👤 80",'callback_data'=>'#javan_80'],['text'=>"👤 90",'callback_data'=>'#javan_90']],
[["text"=>"👤 100",'callback_data'=>'#javan_100']],
[['text'=>"Next ⏩",'callback_data'=>'next/'.$idr]],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
exit;
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"يرجى رفع البوت @OeeBot ادمن اولاً 😁",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"لماذا يجب رفع البوت ادمن 🤔⁉️",'url'=>'https://t.me/namkar1/144']],
[['text'=>"كيف رفع البوت ادمن 🤔 ⁉️",'url'=>'https://t.me/namkar1/166']],
[['text'=>"رجوع 🔙",'callback_data'=>'sell']],
]
])
]);
exit;
}
}
if($json["step"] == "send"){
if(is_numeric($text)){
$json["send"]["id"] = $text;
$json["step"] = "send2";
setH("users",$from_id,json_encode($json));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاطك 💰: *".$json["points"]."*,

قم بأرسال عدد النقاط المطلوب تحويلها 🎖.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'send']],
]
])
]);
exit;
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"قم بأرسال آيدي شخص صحيح ⭐.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'send']],
]
])
]);
exit;
}
}elseif($json["step"] == "send2"){
if(is_numeric($text)){
if($json["points"] < 31){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"يجب ان تكون نقاطك أكثر من *30* نقطة 💰.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'send']],
]
])
]);
exit;
}elseif($text > $json["points"]-30){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"الحد الاقصى لعدد النقاط التي تستطيع تحويلها 👤: *".($json["points"]-30)."*.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'send']],
]
])
]);
exit;
}else{
$id = $json["send"]["id"];
$json["send"]["points"] = $text;
setH("users",$from_id,json_encode($json));
$po = $text;
$id = "[$id](tg://user?id=$id)";
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"هل انت متأكد من عملية تحويل *".$po."* نقطة الى : ".$id.".",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"لا",'callback_data'=>'send']],
[['text'=>"نعم",'callback_data'=>'send3']],
]
])
]);
exit;
}
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"قم بأرسال عدد نقاط صحيح ⭐.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'send']],
]
])
]);
exit;
}
}