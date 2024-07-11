<?php
@$admin = json_decode(file_get_contents("admin.json"),1);
$startAdmin = "*👋┇مرحباً عزيزي الادمن،\n⬇️┇اختار ماتريد من القائمه في الاسفل.*";
$seto = [
[["text"=>"عدد المستخدمين 🎖"]],
[["text"=>"معلومات مستخدم 👤"]],
[["text"=>"حذف نقاط 👤"],["text"=>"أضافة نقاط 👤"]],
[["text"=>"قائمة الهدايا 🎰"]],
[["text"=>"حذف جميع الهدايا 🎰"],["text"=>"حذف هدية 🎰"]],
[["text"=>"حذف قناة ⭐"]],
[["text"=>"وضع نقاط الدعوة 10 👤"],["text"=>"وضع نقاط الدعوة 5 👤"]],
[["text"=>"تعطيل طلب مشتركين 🕉"],["text"=>"تفعيل طلب مشتركين 🕉"]],
[["text"=>"تفعيل طلب مشتركين مساءً 🕉"]],
[["text"=>"قائمة البثوث 📺"],["text"=>"بدء بث جديد 📺"]],
[["text"=>"أذاعه 📣"]],
];
if(strtolower($text) == "/admin" or $text == "رجوع 🔙"){
$admin["step"] = null;
file_put_contents("admin.json",json_encode($admin));
javan('sendMessage',[
'chat_id'=>$from_id,
'text'=>$startAdmin,
'parse_mode'=> 'markdown',
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>$seto
])
]);
exit;
}
if($text == "معلومات مستخدم 👤"){
$admin["step"] = "info&";
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"قم بأرسال ID المستخدم 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($text == "وضع نقاط الدعوة 5 👤"){
$admin["linkp"] = 5;
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم وضع نقاط الدعوة 5 نقاط 👤",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($text == "وضع نقاط الدعوة 10 👤"){
$admin["linkp"] = 10;
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم وضع نقاط الدعوة 10 نقاط 👤",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($admin["step"] == "info&"){
$json = json_decode(getH("users",$text),1);
if($json == null){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"هذا الشخص غير موجود 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
}else{
$admin["step"] = null;
file_put_contents("admin.json",json_encode($admin));
if(isset($json["my_channels"])){
$mych = $json["my_channels"];
$channels = json_decode(getNH("channels"),1);
$l = "0";
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
$lt = "[@JAVAN](".$link.")";
}else{
$lt = "[@".$lt."]";
}
$channelss .= "➖ القناة: ".$lt." ⭐،
➖ الحالة: جاري أضافة الاعضاء ⏰ ،
➖ تسلسل طلبه: *".$cc."* 💡،
➖ عدد الدخول: *".$c."* 👤،
➖ عدد المتبقين: *".$z."* 👤،
➖ العدد المطلوب: *".($z+$c)."* 👤،
➖ وقت البدء: *".($ready)."* 🔥.
----------------------------
";
}
}
}
if($channelss == null){
$channelss = "ليس لديه آي قناة تحت التمويل.";
}
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاط المستخدم: *".$json["points"]."*,
قنوات المستخدم التي تحت التمويل 💰:

".$channelss,
'parse_mode'=>markdown,
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
}
exit;
}
if($text == "حذف نقاط 👤"){
$admin["step"] = "delu1&";
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"قم بأرسال ID المستخدم 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($admin["step"] == "delu1&"){
$json = json_decode(getH("users",$text),1);
if($json == null){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"هذا الشخص غير موجود 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
}else{
$admin["step"] = "del2&".$text;
file_put_contents("admin.json",json_encode($admin));
if(!isset($json["points"])){
$json["points"] = "0";
}
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاط المستخدم: *".$json["points"]."*،

أرسل عدد النقاط ألتي تريد استقطاعها منه 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
}
exit;
}
if(strstr($admin["step"],"del2&")){
$id = explode("del2&",$admin["step"])[1];
$json = json_decode(getH("users",$id),1);
if(!isset($json["points"])){
$ex = "0";
}else{
if($json["points"] < 1){
$ex = "0";
}else{
if($json["points"] - $text >= 0){
$ex = $json["points"] - $text;
}else{
$ex = "0";
}
}
}
$json["points"] = $ex;
setH("users",$id,json_encode($json));
$admin["step"] = null;
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم استقطاع النقاط بنجاح 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
javan("sendmessage",[
"chat_id"=>$id,
"text"=>"-".$text." 💰",
]);
exit;
}
if($text == "أضافة نقاط 👤"){
$admin["step"] = "sendu1&";
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"قم بأرسال ID المستخدم 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);;
exit;
}
if($admin["step"] == "sendu1&"){
$json = json_decode(getH("users",$text),1);
if($json == null){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"هذا الشخص غير موجود 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
}else{
$admin["step"] = "sendu2&".$text;
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"عدد نقاط المستخدم: *".$json["points"]."*،

أرسل عدد النقاط ألتي تريد أضافتها لهذا المستخدم 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
}
exit;
}
if(strstr($admin["step"],"sendu2&")){
$id = explode("sendu2&",$admin["step"])[1];
$json = json_decode(getH("users",$id),1);
$ex = $json["points"] + $text;
$json["points"] = $ex;
setH("users",$id,json_encode($json));
$admin["step"] = null;
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم أضافة النقاط للمستخدم بنجاح 👤.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
javan("sendmessage",[
"chat_id"=>$id,
"text"=>"+".$text." 💰",
]);
exit;
}
if($text == "حذف قناة ⭐"){
$admin["step"] = "delc&";
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"قم بأرسال معرف القناة 💰.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($admin["step"] == "delc&"){
if(file_get_contents($text) != null){
if(strstr($text,"/joinchat/")){
$link = end(explode("/",$text));
$username = "-100".unpack("J",base64_decode(strtr($link,"-_","+/")))[1];
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
$Channels = json_decode(getNH("channels"),1);
if(!isset($Channels[$idr])){
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"هذه القناة غير موجودة 💰.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
}else{
$admin["step"] = null;
file_put_contents("admin.json",json_encode($admin));
unset($Channels[$idr]);
setNH("channels",json_encode($Channels));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم حذف هذهِ القناة بنجاح 💰",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
}
exit;
}
if($text == "أذاعه 📣"){
javan('sendMessage',[
'chat_id'=>$from_id,
'text'=>"قم بتوجيه المراد أذاعته ألى هنا 📣.",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
$admin["step"] = "sendf";
file_put_contents("admin.json",json_encode($admin));
exit;
}
if($admin["step"] == "sendf"){ 
$admin["aza3a"] = $message->message_id; 
$admin["step"] = 'doneaz';
file_put_contents("admin.json",json_encode($admin)); 
javan('sendMessage',[ 
'chat_id'=>$from_id, 
'text'=>"هل أنت متأكد من الاذاعه ؟", 
'reply_markup'=>json_encode([ 
'resize_keyboard'=>true, 
'keyboard'=>[ 
[["text"=>"لا"],["text"=>"نعم"]], 
] 
]) 
]);
exit; 
}
if($admin["step"] == "doneaz"){
if($text == "لا"){
$admin["step"] = null;
file_put_contents("admin.json",json_encode($admin));
javan('sendMessage',[ 
'chat_id'=>$from_id, 
'text'=>"تم الغاء الأذاعه بنجاح.", 
'parse_mode'=> 'markdown', 
'reply_markup'=>json_encode([ 
'resize_keyboard'=>true, 
'keyboard'=>$seto 
]) 
]); 
exit; 
} 
if($text == "نعم"){ 
shell_exec("screen -dmS javan php all.php ".$from_id); 
$admin["step"] = null;
file_put_contents("admin.json",json_encode($admin));
exit; 
} 
}
if(strstr($text,"حظر ")){	
$text = explode("حظر ",$text)[1];
if(file_get_contents($text) != null){
if(strstr($text,"/joinchat/")){
$link = end(explode("/",$text));
$username = "-100".unpack("J",base64_decode(strtr($link,"-_","+/")))[1];
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
if(!in_array($idr,$admin["kicks"])){

$admin["kicks"][] = $idr;

file_put_contents("admin.json",json_encode($admin));

}

javan('sendMessage',[ 

'chat_id'=>$from_id, 

'text'=>"تم حظر القناة.",

'parse_mode'=> 'markdown', 

]); 

exit; 

}

if(strstr($text,"الغاء ")){

$text = explode("الغاء ",$text)[1];
if(file_get_contents($text) != null){
if(strstr($text,"/joinchat/")){
$link = end(explode("/",$text));
$username = "-100".unpack("J",base64_decode(strtr($link,"-_","+/")))[1];
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
$channels = array_flip($admin["kicks"]);

unset($channels[$idr]);

$admin["kicks"] = array_keys($channels);

file_put_contents("admin.json",json_encode($admin));

javan('sendMessage',[ 

'chat_id'=>$from_id, 

'text'=>"تم الغاء حظر القناة.",

'parse_mode'=> 'markdown', 

]); 

exit; 

}
// البث
if($text == "بدء بث جديد 📺"){
javan('sendMessage',[
'chat_id'=>$from_id,
'text'=>"قم بأرسال عنوان للبث  📺.",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>$seto
])
]);
$admin["step"] = "send_bth0";
file_put_contents("admin.json",json_encode($admin));
exit;
}
if($text == "قائمة البثوث 📺"){
$keys = array_keys($admin["bthoth"]);
for($i=0; $i<count($keys); $i++){
$key = $keys[$i];
$rep[] = [['text' => "#Jv-".$key." || ".$admin["bthoth"][$key]["name"]]];
}
$rep[] = [["text"=>"رجوع 🔙"]];
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"هذهِ قائمة البثوث الخاصة بك 📺:",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>$rep
])
]);
exit;
}
if(strstr($text,"||") and strstr($text,"#Jv-")){
$id = explode("||",$text)[0];
$id = explode("-",$id)[1];
$id = str_replace(" ","",$id);
$x = 0;
$users = $redis->hgetall("users");
foreach(array_keys($users) as $uid){
$user = json_decode($users[$uid],1);
if(in_array($id,$user["bthoth"])){
$x++;
}
}
$all = count(array_keys($users))+1;
$n = $all - $x;
javan('sendMessage',[
'chat_id'=>$from_id,
'text'=>$user."تم البث لـ *".$x."* مستخدم،
من اصل *".$n."* مستخدم.

رسالة البث:",
//'parse_mode'=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text' => "#Ri-".$id." || حذف"]],
[["text"=>"رجوع 🔙"]],
]
])
]);
javan('forwardMessage',[
 'chat_id'=>$from_id,
 'from_chat_id'=>$from_id,
'message_id'=>$id,
 ]);
exit;
}
if(strstr($text,"||") and strstr($text,"#Ri-")){
$id = explode("||",$text)[0];
$id = explode("-",$id)[1];
$id = str_replace(" ","",$id);
unset($admin["bthoth"][$id]);
file_put_contents("admin.json",json_encode($admin));
javan('sendMessage',[
'chat_id'=>$from_id,
'text'=>"تم الحذف بنجاح 🔥.",
'parse_mode'=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>$seto
])
]);
exit;
}
if($admin["step"] == "send_bth0" ){ 
$admin["bth_name"] = $text;
$admin["step"] = 'send_bth';
file_put_contents("admin.json",json_encode($admin)); 
javan('sendMessage',[
'chat_id'=>$from_id,
'text'=>"قم بأرسال الرسالة المطلوب بثها 📺.",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit; 
}
if($admin["step"] == "send_bth" ){ 
$key = $message->reply_markup;
if($key != null){
$key = json_decode(json_encode($key),1);
$admin["bth_key"] = explode("start=",$key["inline_keyboard"][0][0]["url"])[1];
$admin["bth_text"] = $text;
$admin["bth_id"] = $message->message_id; 
}else{
$admin["bth_id"] = $message->message_id; 
}
$admin["step"] = 'bth_done';
file_put_contents("admin.json",json_encode($admin)); 
javan('sendMessage',[ 
'chat_id'=>$from_id, 
'text'=>"هل أنت متأكد من البث ؟", 
'reply_markup'=>json_encode([ 
'resize_keyboard'=>true, 
'keyboard'=>[ 
[["text"=>"لا"],["text"=>"نعم"]], 
] 
]) 
]);
exit; 
}
if($admin["step"] == "bth_done"){
if($text == "لا"){
$admin["step"] = null;
$admin["bth_key"] = null;
file_put_contents("admin.json",json_encode($admin));
javan('sendMessage',[ 
'chat_id'=>$from_id, 
'text'=>"تم الغاء البث بنجاح.", 
'parse_mode'=> 'markdown', 
'reply_markup'=>json_encode([ 
'resize_keyboard'=>true, 
'keyboard'=>$seto 
]) 
]); 
exit; 
} 
if($text == "نعم"){ 
$users = count(array_keys($redis->hgetall("users")))+1;
javan('sendMessage',[ 
'chat_id'=>$from_id, 
'text'=>"تم البث بنجاح الى: *".$users."* مشترك.",
'parse_mode'=> 'markdown', 
'reply_markup'=>json_encode([ 
'resize_keyboard'=>true, 
'keyboard'=>$seto 
]) 
]); 
if($admin["bth_key"] != null){
$admin["bthoth"][$admin["bth_id"]]["key"] = $admin["bth_key"];
}
$admin["bthoth"][$admin["bth_id"]]["name"] = $admin["bth_name"];
$admin["bthoth"][$admin["bth_id"]]["text"] = $admin["bth_text"];
$admin["bth_key"] = null;
file_put_contents("admin.json",json_encode($admin));
exit; 
} 
}

// طلب مشتركين
if($text == "تعطيل طلب مشتركين 🕉"){
$admin["sell"] = "off";
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم تعطيل طلب المشتركين بنجاح 🕉.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($text == "تفعيل طلب مشتركين 🕉"){
$admin["sell"] = "on";
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم تفعيل طلب المشتركين بنجاح 🕉.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($text == "تفعيل طلب مشتركين مساءً 🕉"){
$admin["sell"] = "time";
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم تفعيل طلب مشتركين مساءً بنجاح 🕉.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}

// الهدايا
if($text == "حذف جميع الهدايا 🎰"){
$admin["codes"] = array();
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم حذف جميع الهدايا بنجاح 🎰.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($text == "حذف هدية 🎰"){
$admin["step"] = "delg1&";
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"قم بأرسال الكود المطلوب 🎰.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($admin["step"] == "delg1&"){
$admin["step"] = null;
if(in_array($text,$admin["codes"])){
$key = array_search($text,$admin["codes"]);
unset($admin["codes"][$key]);
$admin["codes"] = array_values($admin["codes"]);
}
file_put_contents("admin.json",json_encode($admin));
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"تم حذف الكود المطلوب بنجاح 🎰.",
"parse_mode"=>"markdown",
'reply_markup'=>json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[["text"=>"رجوع 🔙"]],
]
])
]);
exit;
}
if($text == "قائمة الهدايا 🎰"){
$keys = $admin["codes"];
if($keys[0] != null){
$done = array();
for($i=0; $i<count($keys); $i++){
if(!in_array($keys[$i],$done)){
$done[] = $keys[$i];
$l++;
$cod = base64_decode($keys[$i]);
$ex = explode("&_",$cod);
$username = "@[".$ex[2]."]";
$point = $ex[1];
$cc = $i+1;
$channels[] = "Code: `".$keys[$i]."`,\nNumber: *".$cc."*,\nChannel: ".$username.",\nPoint: *".$point."*.\n\n";
}
}
$s = array_chunk($channels,18);
foreach($s as $channel2){
$channels2 = null;
foreach($channel2 as $channel){
$channels2 .= $channel;
}
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>$channels2,
"parse_mode"=>markdown
]);
}
}else{
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"لا توجد هدايا في البوت 🎰.",
"parse_mode"=>markdown
]);
}
exit;
}

// عدد المشتركين
if($text == "عدد المستخدمين 🎖"){
$users = $redis->hgetall("users");
$users = count(array_keys($users))+1;
javan("sendmessage",[
"chat_id"=>$from_id,
"text"=>"Count Users: *".$users."* 👤.",
"parse_mode"=>"markdown",
]);
exit;
}