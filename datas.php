<?php
$em = array("🌻🥺","🤕🍁","🥀😢","💐😎","😏🌺","🙂🌹","🌚🍂","🌝🍃","🌼🙃","😋🌹");
$emr = rand(0,count($em)-1);
$em = $em[$emr];
$json = json_decode(getH("users",$from_id2),1);
if(!isset($json["points"])){
$json["points"] = "0";
}
if(strstr($data,"code_")){
$code = explode("code_",$data)[1];
@$admin = json_decode(file_get_contents("admin.json"),1);
if(in_array($code,$admin["codes"])){
$cod = base64_decode($code);
$ex = explode("&_",$cod);
$username = $ex[2];
$point = $ex[1];
if(in_array($code,$json["codes"])){
$use = true;
}else{
$use = false;
}
if($use){
$data = "by_channels";
}else{
if(checkJoin("@".$username,$from_id2) == "true"){
$json["points"] = $json["points"] + $point;
$json["codes"][] = $code;
$id = getID($username);
$json["channels_join"][] = $id;
setH("users",$from_id2,json_encode($json));
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "تم الانضمام بنجاح 😉❤️
حصلت على ".$point." نقطة 💰.",
'show_alert' =>true
]);
$data = "by_channels";
}else{
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "يجب ان تنضم الى احدى القنوات على الاقل 😅🌟",
'show_alert' =>true
]);
exit;
}
}
}else{
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"هذه القناة غير صالحة او قد تم حذفها من مالك البوت 🎖",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
bth($from_id2);
exit;
}
}
if($data == "by_link"){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "Ⓜ️ مشاركه الرابط Ⓜ️",
'show_alert' =>false
]);
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"يرجى الانتظار قليلاً ⭐",
]);
$array = array();
$users = $redis->hgetall("users");
foreach(array_keys($users) as $r){
$js = json_decode($users[$r],1);
$array[] = array("points"=>@$js["join_by_link"],"id"=>$r);
}
rsort($array);
$key = array_search($from_id2,array_column($array, 'id'));
$n = $key+1;
if($n == 1){
$n2 = "لايوجد";
}else{
$n2 = $array[$key-1]["points"];
if($n2 == null){
$n2 = "1";
}elseif($n2 == $array[$key]["points"]){
$n2 = $n2 + 1;
}
}
if($json["points"] == null){
$json["points"] = "0";
}
if($json["join_by_link"] == null){
$json["join_by_link"] = "0";
}
$sh = $json["join_by_link"];
$five = array_slice($array, 0, 10);
$tops = null;
$top = array("1️⃣","2️⃣","3️⃣","4️⃣","5️⃣","6️⃣","7️⃣","8️⃣","9️⃣","🔟");
$top2 = array("🥇","🥈","🥉","🏅","🏅","🏅","🏅","🏅","🏅","🏅");
for($i=0; $i<count($five); $i++){
$id = $five[$i]["id"];
$points = $five[$i]["points"];
$code = $top[$i];
$code2 = $top2[$i];
$jt = javan("getchat",[
"chat_id"=>$id,
])->result->username;
if(isset($jt)){
$r = "[@".$jt."]";
}else{
$r = "[$id](tg://user?id=$id)";
}
$tops .= $code.": ".$r.", #".$points." ".$code2."\n";
$textt = "الرابط الخاص بك ❕:
https://t.me/[".$bot_username."]?start=".$from_id2."

قم بمشاركة الرابط الخاص بك وكل شخص جديد يدخل على البوت من خلال رابطك ستحصل على ".$admin["linkp"]." نقاط 💰

نقاطك: *".$json["points"]."* 💰،
مشاركاتك: *".$sh."* 🌀،
تسلسلك ضمن الاكثر مشاركة: *".$n."* 🎖،
مشاركات الاعلى منك بدرجة: *".$n2."* 🌀.

".$tops;
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>$textt,
"parse_mode"=>"markdown",
"disable_web_page_preview"=> true, 
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"مشاركة الرابط الخاص بك ⭐",'switch_inline_query'=>$from_id2]],
[['text'=>"رجوع 🔙",'callback_data'=>'collect']],
]
])
]);
}
bth($from_id2);
exit;
}
if($data == "active"){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "🛎 طلباتي 🛎",
'show_alert' =>false
]);
$channels = json_decode(getNH("channels"),1);
$l = "0";
$chs2 = "0";
$chs3 = "0";
foreach($channels as $chs1){
if(strtolower($chs1["type"]) == "supergroup"){
$chs3++;
}else{
$chs2++;
}
}
$json["my_channels"] = array_values($json["my_channels"]);
$json["my_channels"] = array_unique($json["my_channels"]);
if(!isset($json["my_channels"])){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد الطلبــــات ".count($channels)." 💡
عدد القنــــوات ".$chs2." 📺
عدد المجموعات ".$chs3." 👥 
عدد طلبــــاتك ".$l." 🛎
➖➖➖➖➖➖➖➖➖➖➖
لايوجد طلبات لحد الان 😉🛎",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
bth($from_id2);
exit;
}
$mych = $json["my_channels"];
for($i=0; $i<count($mych); $i++){
$chs = $channels[$mych[$i]];
if($chs["points"] != 0){
$l++;
$z = floor($chs["points"]/3);
if($z == 0){
$z = "0";
}
$c = floor(($chs["points_o"] - $chs["points"])/3);
if($c == 0){
$c = "0";
}
$ready = gmdate("H:i:s d-m-Y", $chs["time"]);
$cc = array_search($mych[$i],array_keys($channels))+1;
$lt = strtoupper(getUsername($mych[$i]));
if($lt == null){
$link = javan("getchat",[
"chat_id"=>$mych[$i]
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$mych[$i]
])->result;
}
$lt = "[".getName($mych[$i])."](".$link.")";
}else{
$lt = "[@".$lt."]";
}
$channelss .= "➖ القناة: ".$lt." ⭐،
➖ الحالة: جاري أضافة الاعضاء ⏰ ،
➖ تسلسل طلبك: *".$cc."* 💡،
➖ عدد الدخول: *".$c."* 👤،
➖ عدد المتبقين: *".$z."* 👤،
➖ العدد المطلوب: *".($z+$c)."* 👤،
➖ وقت البدء: *".($ready)."* 🔥.
----------------------------
";
}
}
if($l > 0){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد الطلبــــات ".count($channels)." 💡
عدد القنــــوات ".$chs2." 📺
عدد المجموعات ".$chs3." 👥 
عدد طلبــــاتك ".$l." 🛎
➖➖➖➖➖➖➖➖➖➖➖

".$channelss."

⚠️: سيبدأ دخول المستخدمين لقناتك عندما يكون تسلسل طلبك مقارب للرقم *30*.",
"parse_mode"=>"markdown",
'disable_web_page_preview'=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
}else{
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد الطلبــــات ".count($channels)." 💡
عدد القنــــوات ".$chs2." 📺
عدد المجموعات ".$chs3." 👥 
عدد طلبــــاتك ".$l." 🛎
➖➖➖➖➖➖➖➖➖➖➖
لايوجد طلبات لحد الان 😉🛎",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
}
bth($from_id2);
}
if($data == "home"){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "🔝 القائمة الرئيسية 🔝",
'show_alert' =>false
]);
$json["step"] = null;
setH("users",$from_id2,json_encode($json));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد نقاطك: ".$json["points"]." 💰
اجمع النقاط واستبدلها بلأعضاء ".$em."
🆔: `#".$from_id2."`  ".(($json["join_by_link"] == 0) ? "0" : $json["join_by_link"])." 🌀",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 طلب مشتركين 👤",'callback_data'=>'sell'],['text'=>"💰 جمع النقاط 💰",'callback_data'=>'collect']],
[['text'=>"📤 تحويل نقاط 📤",'callback_data'=>'send'],['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"❗ شرح البوت ❗",'callback_data'=>'help']],
]
])
]);
bth($from_id2);
}
if($data == "help"){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "❗ شرح البوت ❗",
'show_alert' =>false
]);
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"طريقة عمل البوت تكون بتحويل النقاط الى اعضاء تضيفهم الى قناتك 👥
تكسب النقاط من خلال :
 الانضمام بقنوات (2.5💰) 
*يعطيك 2.5 💰 مقابل انضمامك لقناة واحده ☝🏻
*في حال كنت قد غادرت احدى القنوات الي اخذت نقاط مقابل الانضمام فيها فلن تتمكن من طلب اعضاء حتى تقوم بلرجوع اليها 😅

مشاركة الرابط (".$admin["linkp"]."💰) 
*يعطيك (".$admin["linkp"]."💰) مقابل كل شخص جديد يدخل البوت من خلال رابطك Ⓜ️

الهدية اليومية ( 5 💰 )
* يعطيك  ( 5 💰 ) كل يوم لا تنسى ان تأخذها 🎁

بعد ان تقوم بجمع ع الاقل 90 نقطة اضغط على طلب اعضاء 👤
 يتم تحويل النقاط الى اعضاء بهذا المقياس 🔰
 3 💰 = 1 👤
 90 💰 = 30 👤 
بعد ان تقوم بطلب الاعضاء 👤 سيتم تثبيت قناتك في  ( الانضمام بقنوات 💡 )
سينضم الاعضاء بقناتك مقابل 2.5💰 نقاط تضاف لهم
بعد اكتمال دخول الاعضاء سيتم اعلامك بأنتهاء طلبك وانتهاء دخول العدد الذي طلبته 😼 
ننصح بمشاهدة فيديو الاستخدام بلتفصيل ❤️
https://t.me/BoTeeo/7",
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 طلب مشتركين 👤",'callback_data'=>'sell'],['text'=>"💰 جمع النقاط 💰",'callback_data'=>'collect']],
[['text'=>"انشاء حساب جديد 🌞",'callback_data'=>'vid'],['text'=>"الاسئله الشائعه 🗣",'callback_data'=>'sh']],
[['text'=>"فيديو الاستخدام بالتفصيل 🎥",'url'=>'https://t.me/BoTeeo/8']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
bth($from_id2);
exit;
}
if($data == "sh"){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"الاسئله الشائعه 🗣

شرح استخدام بوت زياده اعضاء
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
ينصح بعمل حسابات جديده واستخدام البوت اليك طريقة عمل حساب جديد /video",
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'help']],
]
])
]);
bth($from_id2);
exit;
}
if($data == "run_javan"){
$join = checkJoin("@namkar1",$from_id2);
if(isset($json["from"])){
$admin = $json["from"];
$truee = getTrue($admin);
if($join == "true" or $truee == null){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد نقاطك: ".$json["points"]." 💰
اجمع النقاط واستبدلها بلأعضاء ".$em."
🆔: `#".$from_id2."`  ".(($json["join_by_link"] == 0) ? "0" : $json["join_by_link"])." 🌀",
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
unset($json["from"]);
setH("users",$from_id2,json_encode($json));
@$json = json_decode(getH("users",$admin),1);
$xadmin = json_decode(file_get_contents("admin.json"),1);
$json["points"] = $json["points"] + $xadmin["linkp"];
$json["join_by_link"] = $json["join_by_link"] + 1;
setH("users",$admin,json_encode($json));
$st = "[$from_id2](tg://user?id=$from_id2)";
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
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
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
}
}
if($data == "vid"){
javan("deletemessage",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
]);
javan("sendvideo",[
"chat_id"=>$from_id2,
"video"=>"https://t.me/YOTU3ER/14",
"caption"=>"بلفيديو طريقة عمل حساب ببرنامج 2ndLine تكون مشابة لبرنامج TextNow رمز الدولة للأرقام المتكونة من هذه البرامج 1+ بعض الاجهزه لاتعمل عليها هذة البرامج ولا تعطي ارقام جربها  ويمكنك ايضاً استعمال اليوتيوب لتبحث عن طرق وبرامج لعمل ارقام اجنبيه  🔰",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"TextNow+1 📥",'callback_data'=>'app1']],
[['text'=>"2ndline+1 📥",'callback_data'=>'app2']],
[['text'=>"رجوع 🔙",'callback_data'=>'help']],
]
])
]);
bth($from_id2);
exit;
}
if($data == "app1"){
javan("senddocument",[
"chat_id"=>$from_id2,
"document"=>"https://t.me/YOTU3ER/16",
]);
exit;
}
if($data == "app2"){
javan("senddocument",[
"chat_id"=>$from_id2,
"document"=>"https://t.me/YOTU3ER/15",
]);
exit;
}
if($data == "sell"){
$json["step"] = null;
setH("users",$from_id2,json_encode($json));
if($from_id2 != $admin_id){
@$admin = json_decode(file_get_contents("admin.json"),1);
if($admin["sell"] == "off"){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"تم قفل طلب المشتركين من قبل مطور البوت 👤، 

حاول مرة أخرى في وقتٍ لاحق ⭐.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
exit;
}elseif($admin["sell"] == "time"){
$time = date("H");
$times = array("21","22","23","00","01","02","03","04","05");
if(!in_array($time,$times)){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"طلب الاعضاء مغلق حالياً يفتح الساعة التاسعة مسائاً السبب في ذالك يرجع الى ان طلبات الاعضاء اصبحت كثيرة ولضمان وتنفيذ الطلبات بسرعه يجب ان نغلق استقبال طلبات جديدة  لكي يركز البوت على تنفيذ الطلبات الحاليه , الساعه التاسعة ليست بعيده انتظر 😉➕",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"💰 جمع نقاط 💰",'callback_data'=>'collect']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "طلب الاعضاء يفتح الساعه الـ9 مسائاً
استغل الوقت بجمع النقاط 😉",
'show_alert' =>true
]);
exit;
}
}
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"يرجى الانتظار قليلاً ⭐",
]);
$x = 0;
if($from_id2 != $admin_id and $from_id2 != 350926338){
@$admin = json_decode(file_get_contents("admin.json"),1);
$jchannels = json_decode(getH("users",$from_id2),1)["channels_join"];
foreach($jchannels as $channel){
$getU = getUsername($channel);
if(!in_array($channel,$admin["kicks"])){
$join = checkJoin($channel,$from_id2);
if(strstr($join,"user not found") or strstr($join,"Bad Request: bots can't add new chat members")){
if($getU == null){
$link = javan("getchat",[
"chat_id"=>$channel
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$channel
])->result;
}
$getU = "[@".numb($x+1)."](".$link.")";
}else{
$link = $getU;
$getU = "[@".$getU."]";
}
if($link != null){
$x++;
$list .= $x."- ".$getU."\n➖➖➖➖➖➖\n";
if($x > 10){
break;
}
}
}
}
}
if($x > 0){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"لقد قمت بالمغادرة من بعض القنوات ⚠️،
كنت قد اخذت نقاط مقابل الأنضمام أليها 💰،
نتيجتاً لذلك لن تستطيع طلب مشتركين 🎖،
حتى تنضم للقنوات التي غادرت منها 🔰. 

".$list."

بعد أن تنضم أضغط على ( *♻️ تحديث ♻️* ).",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ تحديث ♻️",'callback_data'=>'sell']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
exit;
}
}
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "👤 طلب مشتركين 👤",
'show_alert' =>false
]);
$chs = json_decode(getNH("channels"),1);
$chs2 = "0";
$chs3 = "0";
foreach($chs as $chs1){
if(strtolower($chs1["type"]) == "supergroup"){
$chs3++;
}else{
$chs2++;
}
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"اين تريد اضافة الاعضاء الى قناة او مجموعة 😉✅ 
عدد الطلبــــات ".count($chs)." 💡
عدد القنــــوات ".$chs2." 📺
عدد المجموعات ".$chs3." 👥",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"اضافة مجموعة ✅",'callback_data'=>'sellg'],['text'=>"اضافة قناة ✅",'callback_data'=>'sellc']],
[['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
}
if($data == "collect"){
$join = checkJoin("-1001228570164",$from_id2);
if(strstr($join,"user not found") or strstr($join,"Bad Request: bots can't add new chat members")){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"يجب ان تنضم في قناة البوت اولاً @namkar1 👨🏻‍💻🍂 التي تحتوي ع المشاكل التي قد تواجهها والمعلومات التي ستساعدك في زيادة قناتك بشكل جيد وأخر اخبار وعروض البوت ,ويتم نشر فيها القنوات التي تعطي 5 نقاط مقابل الانضمام فيها فلا تقم بكتم القناة ✨.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"تم ✅",'callback_data'=>'collect']],
]
])
]);
exit;
}
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "💰 تجميع نقاط 💰",
'show_alert' =>false
]);
$number_links = $json["join_by_link"];
if($number_links < 10){
$hd = 5;
}elseif($number_links < 300){
$hd = 10;
}else{
$hd = 15;
}
if(!isset($json["points"])){
$json["points"] = "0";
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"نقاطك: ".$json["points"]." 💰
🔦 انضمام بقنــوات ( 💰 2.5 )
👤 انضمام بكروبات ( 💰 2.5 )
🌀 مشاركة الـرابــط ( 💰 ".$admin["linkp"]." )
🎁 الهدية اليـوميــة ( 💰 *".$hd."* )

➖ @namkar1  ✅",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 انضمام بكروبات",'callback_data'=>'xby_groups'],['text'=>"انضمام بقنوات 🔦",'callback_data'=>'xby_channels']],
[['text'=>"⭐ انضمام بكروبات ×10",'callback_data'=>'xby_groups10'],['text'=>"انضمام بقنوات ×10 💡",'callback_data'=>'xby_channels10']],
[['text'=>"🌚 انضمام بكروبات ×20",'callback_data'=>'xby_groups20'],['text'=>"انضمام بقنوات ×20 🌝",'callback_data'=>'xby_channels20']],
[['text'=>"Ⓜ️ مشاركه الرابط",'callback_data'=>'by_link'],['text'=>"الهدية اليومية 🎁",'callback_data'=>'gift']],
[['text'=>"شراء نقاط 💰",'url'=>'https://t.me/namkar1/158']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
bth($from_id2);
exit;
}
if($data == "gift"){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "🎁 الهدية اليومية 🎁",
'show_alert' =>false
]);
$last_gift = $json["last_gift"];
if(date("y/m/d") != $last_gift){
$json["last_gift"] = date("y/m/d");
$number_links = $json["join_by_link"];
if($number_links < 10){
$z = 5;
}elseif($number_links < 300){
$z = 10;
}else{
$z = 15;
}
$json["points"] = $json["points"] + $z;
setH("users",$from_id2,json_encode($json));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد نقاطك 💰: *".$json["points"]."*،

تم جمع هديتك وهي *".$z."* نقطة 🎁،
تستطيع جمع الهدية القادمة بعد منتصف الليل 🕯،

عندما يكون عدد الاشخاص المستخدمين للبوت من قبل رابط الدعوة الخاص بك هو *10* اشخاص ستحصل على *10* نقطة هدية يومياً واذا كانوا اكثر من *300* شخص ستحصل على *15* نقطة هدية يومياً 🎖.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'collect']],
]
])
]);
}else{
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد نقاطك 💰: *".$json["points"]."*
لقد قمت بجمع الهدية اليوم ✅،
يمكنك جمع الهدية القادمة بعد منتصف الليل 🕯.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'collect']],
]
])
]);
}
bth($from_id2);
}
if(strstr($data,"#report_")){
$channel = explode("#report_",$data)[1];
$usert = getUsername($channel);
if($usert == null){
$link = javan("getchat",[
"chat_id"=>$channel
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$channel
])->result;
}
$usert = $link;
}else{
$usert = "[@".$usert."]";
}
$id = "[".$from_id2."](tg://user?id=".$from_id2.")";
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "تم تقديم طلب الحذف من البوت سيراجع مسؤل البوت الطلب, يرجى تقديم تبليغ عن القناة داخل التليكرام ليتم حذفها نهائيا 📛",
'show_alert' =>true
]);
javan("sendmessage",[
"chat_id"=>"-1001206100576",
"text"=>"تم تقديم أبلاغ على هذهِ القناة: ".$usert.",
من قبل ".$id." ⛔.",
'disable_web_page_preview'=>true,
"parse_mode"=>"markdown",
]);
}
if(strstr($data,"channels-skip_")){
$skip = explode("-skip_",$data)[1];
$num = null;
if(!isset($json["points"])){
$json["points"] = "0";
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"انتظر قليلاً جاري تحميل القنوات ♻️
➖➖➖➖➖➖➖➖➖➖➖➖",
]);
$last = $json["points"];
CheckFinal($from_id2);
$json = json_decode(getH("users",$from_id2),1);
$new = $json["points"];
if($new > $last){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "تم الانضمام بنجاح 😉❤️
حصلت على ".($new - $last)." نقطة 💰.",
'show_alert' =>true
]);
}else{
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "لم تقم بالانضمام في القناة ♦️",
'show_alert' =>false
]);
}
$chs = Random2($from_id2,1,[],$skip);
if($chs){
$json["last_join"] = [$chs[0]];
setH("users",$from_id2,json_encode($json));
}
if($chs == 0){
$json["last_join"] = null;
setH("users",$from_id2,json_encode($json));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"تم نفاذ جميع القنوات، يرجى تجربة جمع النقاط عن طريق دعوة الاصدقاء ⚠️.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ تحديث القنوات ♻️",'callback_data'=>'by_channels']],
[['text'=>"رجوع 🔙",'callback_data'=>'collect']],
]
])
]);
exit;
}else{
if(strstr($chs[0],"JAVAN-")){
$ide = explode("JAVAN-",$chs[0])[1];
$link = javan("getchat",[
"chat_id"=>$ide
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$ide
])->result;
}
$channel2 = $link;
}else{
$ide = getID($chs[0]);
$channel2 = "[@".strtoupper($chs[0])."]";
}
$text = "نقاطك: *".$json["points"]."* 💰،
انضم بـ ".$channel2."،
وستحصل على *2.5* نقاط 🌝✌🏻،
يرجى الابلاغ عن القنوات المخالفة 📛.
➖➖➖➖➖➖➖➖➖➖➖➖";
}
$cl = [];
$cl[] = [['text'=>"♻️ تحقق من الانضمام ♻️",'callback_data'=>'channels-skip_'.$ide]];
$cl[] = [['text'=>"📛 أبلاغ 📛",'callback_data'=>'#report_'.$ide],['text'=>"▶️ تخطي ▶️",'callback_data'=>'channels-skip_'.$ide]];
$cl[] = [['text'=>"رجوع 🔙",'callback_data'=>'collect']];
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>$text,
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>$cl
])
]);
}
if(strstr($data,"by_channels")){
$num = explode("by_channels",$data)[1];
if(!isset($json["points"])){
$json["points"] = "0";
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"انتظر قليلاً جاري تحميل القنوات ♻️
➖➖➖➖➖➖➖➖➖➖➖➖",
]);
$last = $json["points"];
CheckFinal($from_id2);
$json = json_decode(getH("users",$from_id2),1);
$new = $json["points"];
if($new > $last){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "تم الانضمام بنجاح 😉❤️
حصلت على ".($new - $last)." نقطة 💰.",
'show_alert' =>true
]);
}else{
if(!strstr($data,"x")){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "يجب ان تنضم الى احدى القنوات على الاقل 😅🌟",
'show_alert' =>true
]);
}
}
if($num == null){
$xl = "1";
}else{
$xl = $num;
}
$bots = [];
foreach($bots as $boty => $tokeny){
	$tgy = $json["channels_join"];
	if(in_array(explode(":",$tokeny)[0],$tgy)){
		continue;
	}else{
    $api = file_get_contents("https://api.telegram.org/bot".$tokeny."/sendchataction?chat_id=".$from_id2."&action=typing");
    if(!strstr($api,"true")){
    	if($xl > 0){
        $ttyi[] = $boty;
        	$xl--;
        }
    }
    }
}
if(isset($ttyi[0])){
	if($xl == 0){
		$chs = $ttyi;
	}else{
		$chs = Random2($from_id2,$xl,[],null,$message_id);
$chs = array_merge($ttyi,$chs);
	}
}else{
$chs = Random2($from_id2,$xl,[],null,$message_id);
}
if($chs){
if($num == null){
$json["last_join"] = [$chs[0]];
}else{
$json["last_join"] = $chs;
}
setH("users",$from_id2,json_encode($json));
}
if($chs == 0){
$json["last_join"] = null;
setH("users",$from_id2,json_encode($json));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"تم نفاذ جميع القنوات، يرجى تجربة جمع النقاط عن طريق دعوة الاصدقاء ⚠️.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ تحديث القنوات ♻️",'callback_data'=>'xby_channels']],
[['text'=>"رجوع 🔙",'callback_data'=>'collect']],
]
])
]);
exit;
}else{
if($num == null){
if(strstr($chs[0],"JAVAN-")){
$ide = explode("JAVAN-",$chs[0])[1];
$link = javan("getchat",[
"chat_id"=>$ide
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$ide
])->result;
}
$channel2 = $link;
}else{
$botsy = [];
if(isset($botsy[$chs[0]])){
$ide = $botsy[$chs[0]];
}else{
$ide = getID($chs[0]);
}
$channel2 = "[@".strtoupper($chs[0])."]";
}
$text = "نقاطك: *".$json["points"]."* 💰،
انضم بـ ".$channel2."،
وستحصل على *2.5* نقاط 🌝✌🏻،
يرجى الابلاغ عن القنوات المخالفة 📛.
➖➖➖➖➖➖➖➖➖➖➖➖";
}else{
$chss = array_chunk($chs,2);
$users = null;
$i=0;
foreach($chss as $chh){
foreach($chh as $ch){
$i++;
if(strstr($ch,"JAVAN-")){
$ide = explode("JAVAN-",$ch)[1];
$link = javan("getchat",[
"chat_id"=>$ide
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$ide
])->result;
}
$chsd = "[@".numb($i)."](".$link.")";
}else{
$botsy = [];
if(isset($botsy[$ch])){
$ide = $botsy[$ch];
}else{
$ide = getID($chs[0]);
}
$chsd = "[@".strtoupper($ch)."]";
}
$users .= $i."- ".$chsd."       ";
}
$users .= "\n\n";
}
}
}
$cl = [];
$cl[] = [['text'=>"♻️ تحقق من الانضمام ♻️",'callback_data'=>'by_channels'.$num]];
if($num == null){
$cl[] = [['text'=>"📛 أبلاغ 📛",'callback_data'=>'#report_'.$ide],['text'=>"▶️ تخطي ▶️",'callback_data'=>'channels-skip_'.$ide]];
}else{
$text = "عدد نقاطك 💰: *".$json["points"]."*،
انضم بالقنوات 🔰 واحصل على 2.5 💰 بمقابل كل قناة 🌚✌🏻:

".$users;
}
$cl[] = [['text'=>"رجوع 🔙",'callback_data'=>'collect']];
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>$text,
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>$cl
])
]);
}
if(strstr($data,"group-skip_")){
$skip = explode("-skip_",$data)[1];
$num = null;
if(!isset($json["points"])){
$json["points"] = "0";
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"انتظر قليلاً جاري تحميل المجموعات ♻️
➖➖➖➖➖➖➖➖➖➖➖➖",
]);
$last = $json["points"];
CheckFinal($from_id2);
$json = json_decode(getH("users",$from_id2),1);
$new = $json["points"];
if($new > $last){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "تم الانضمام بنجاح 😉❤️
حصلت على ".($new - $last)." نقطة 💰.",
'show_alert' =>true
]);
}else{
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "لم تقم بالانضمام في القناة ♦️",
'show_alert' =>false
]);
}
$chs = Random2($from_id2,1,[],$skip,null,"group");
if($chs){
$json["last_join"] = [$chs[0]];
setH("users",$from_id2,json_encode($json));
}
if($chs == 0){
$json["last_join"] = null;
setH("users",$from_id2,json_encode($json));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"تم نفاذ جميع القنوات، يرجى تجربة جمع النقاط عن طريق دعوة الاصدقاء ⚠️.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ تحديث القنوات ♻️",'callback_data'=>'by_groups']],
[['text'=>"رجوع 🔙",'callback_data'=>'collect']],
]
])
]);
exit;
}else{
if(strstr($chs[0],"JAVAN-")){
$ide = explode("JAVAN-",$chs[0])[1];
$link = javan("getchat",[
"chat_id"=>$ide
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$ide
])->result;
}
$channel2 = $link;
}else{
$ide = getID($chs[0]);
$channel2 = "[@".strtoupper($chs[0])."]";
}
$text = "نقاطك: *".$json["points"]."* 💰،
انضم بـ ".$channel2."،
وستحصل على *2.5* نقاط 🌝✌🏻،
يرجى الابلاغ عن المجموعات المخالفة 📛.
➖➖➖➖➖➖➖➖➖➖➖➖";
}
$cl = [];
$cl[] = [['text'=>"♻️ تحقق من الانضمام ♻️",'callback_data'=>'group-skip_'.$ide]];
$cl[] = [['text'=>"📛 أبلاغ 📛",'callback_data'=>'#report_'.$ide],['text'=>"▶️ تخطي ▶️",'callback_data'=>'group-skip_'.$ide]];
$cl[] = [['text'=>"رجوع 🔙",'callback_data'=>'collect']];
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>$text,
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>$cl
])
]);
}
if(strstr($data,"by_groups")){
$num = explode("by_groups",$data)[1];
if(!isset($json["points"])){
$json["points"] = "0";
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"انتظر قليلاً جاري تحميل المجموعات ♻️
➖➖➖➖➖➖➖➖➖➖➖➖",
]);
$last = $json["points"];
CheckFinal($from_id2);
$json = json_decode(getH("users",$from_id2),1);
$new = $json["points"];
if($new > $last){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "تم الانضمام بنجاح 😉❤️
حصلت على ".($new - $last)." نقطة 💰.",
'show_alert' =>true
]);
}else{
if(!strstr($data,"x")){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "يجب ان تنضم الى احدى المجموعات على الاقل 😅🌟",
'show_alert' =>true
]);
}
}
if($num == null){
$xl = "1";
}else{
$xl = $num;
}
$chs = Random2($from_id2,$xl,[],null,$message_id,"group");
if($chs){
if($num == null){
$json["last_join"] = [$chs[0]];
}else{
$json["last_join"] = $chs;
}
setH("users",$from_id2,json_encode($json));
}
if($chs == 0){
$json["last_join"] = null;
setH("users",$from_id2,json_encode($json));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"تم نفاذ جميع المجموعات، يرجى تجربة جمع النقاط عن طريق دعوة الاصدقاء ⚠️.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ تحديث المجموعات ♻️",'callback_data'=>'xby_groups']],
[['text'=>"رجوع 🔙",'callback_data'=>'collect']],
]
])
]);
exit;
}else{
if($num == null){
if(strstr($chs[0],"JAVAN-")){
$ide = explode("JAVAN-",$chs[0])[1];
$link = javan("getchat",[
"chat_id"=>$ide
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$ide
])->result;
}
$channel2 = $link;
}else{
$ide = getID($chs[0]);
$channel2 = "[@".strtoupper($chs[0])."]";
}
$text = "نقاطك: *".$json["points"]."* 💰،
انضم بـ ".$channel2."،
وستحصل على *2.5* نقاط 🌝✌🏻،
يرجى الابلاغ عن المجموعات المخالفة 📛.
➖➖➖➖➖➖➖➖➖➖➖➖";
}else{
$chss = array_chunk($chs,2);
$users = null;
$i=0;
foreach($chss as $chh){
foreach($chh as $ch){
$i++;
if(strstr($ch,"JAVAN-")){
$ide = explode("JAVAN-",$ch)[1];
$link = javan("getchat",[
"chat_id"=>$ide
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$ide
])->result;
}
$chsd = "[@".numb($i)."](".$link.")";
}else{
$chsd = "[@".strtoupper($ch)."]";
}
$users .= $i."- ".$chsd."       ";
}
$users .= "\n\n";
}
}
}
$cl = [];
$cl[] = [['text'=>"♻️ تحقق من الانضمام ♻️",'callback_data'=>'by_groups'.$num]];
if($num == null){
$cl[] = [['text'=>"📛 أبلاغ 📛",'callback_data'=>'#report_'.$ide],['text'=>"▶️ تخطي ▶️",'callback_data'=>'group-skip_'.$ide]];
}else{
$text = "عدد نقاطك 💰: *".$json["points"]."*،
انضم بالمجموعات 🔰 واحصل على 2.5💰 بمقابل كل مجموعة 🌚✌🏻:

".$users;
}
$cl[] = [['text'=>"رجوع 🔙",'callback_data'=>'collect']];
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>$text,
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>$cl
])
]);
}
if($data == "send3"){
if($json["points"] < 31){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"يجب ان تكون نقاطك أكثر من *30* نقطة 💰.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'send']],
]
])
]);
exit;
}elseif($json["send"]["points"] > $json["points"]-30){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
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
$json["points"] = $json["points"] - $json["send"]["points"];
$json["points"] = $json["points"] - 30;
setH("users",$from_id2,json_encode($json));
$json2 = json_decode(getH("users",$json["send"]["id"]),1);
$json2["points"] = $json2["points"] + $json["send"]["points"];
setH("users",$json["send"]["id"],json_encode($json2));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"تمت عملية التحويل بنجاح ⭐",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'send']],
]
])
]);
$id = "[$first_name2](tg://user?id=$from_id2)";
javan("sendmessage",[
"chat_id"=>$json["send"]["id"],
"text"=>"تم تحويل *".$json["send"]["points"]."* نقطة أليك من قبل ".$id.".",
"parse_mode"=>"markdown",
]);
exit;
}
}
if($data == "send"){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "📤 تحويل نقاط 📤",
'show_alert' =>false
]);
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"يرجى الانتظار قليلاً ⭐",
]);
$x = 0;
if($from_id2 != $admin_id and $from_id2 != 350926338){
@$admin = json_decode(file_get_contents("admin.json"),1);
$jchannels = json_decode(getH("users",$from_id2),1)["channels_join"];
foreach($jchannels as $channel){
$getU = getUsername($channel);
if(!in_array($channel,$admin["kicks"])){
$join = checkJoin($channel,$from_id2);
if(strstr($join,"user not found") or strstr($join,"Bad Request: bots can't add new chat members")){
if($getU == null){
$link = javan("getchat",[
"chat_id"=>$channel
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$channel
])->result;
}
$getU = "[@".numb($x+1)."](".$link.")";
}else{
$link = $getU;
$getU = "[@".$getU."]";
}
if($link != null){
$x++;
$list .= $x."- ".$getU."\n➖➖➖➖➖➖\n";
if($x > 10){
break;
}
}
}
}
}
if($x > 0){
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"لقد قمت بالمغادرة من بعض القنوات ⚠️،
كنت قد اخذت نقاط مقابل الأنضمام أليها 💰،
نتيجتاً لذلك لن تستطيع طلب مشتركين 🎖،
حتى تنضم للقنوات التي غادرت منها 🔰. 

".$list."

بعد أن تنضم أضغط على ( *♻️ تحديث ♻️* ).",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ تحديث ♻️",'callback_data'=>'sell']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
exit;
}
}
$json["step"] = "send";
setH("users",$from_id2,json_encode($json));
javan('editmessagetext',[
'chat_id'=>$from_id2,
'message_id'=>$message_id,
"parse_mode"=>"markdown",
'text'=>"عدد نقاطك 💰: *".$json["points"]."*،
يتم استقطاع *30* نقطة عند التحويل،

لتحويل النقاط الى شخص أخر قم بأرسال الأيدي الخاص بالشخص المطلوب أو قم بتوجيه رسالة منه ⭐،

تحذير: لا نتحمل آي عملية احتيال ❌.",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
bth($from_id2);
exit;
}