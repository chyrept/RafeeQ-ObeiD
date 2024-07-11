<?php
if(strstr($data,"next/")){
$username = explode("next/",$data)[1];
$idbot = explode(":",$token)[0];
$j = javan("getchatmember",[
"chat_id"=>$username,
"user_id"=>$idbot,
]);
if($j->result->status == "administrator"){
$json["step"] = "#sell2_".$username;
setH("users",$from_id2,json_encode($json));
$max = $json["points"];
$max = floor($max / 3);
$uss = getUsername($username);
if($uss == null){
$link = javan("getchat",[
"chat_id"=>$username
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$username
])->result;
}
$uss = "[".getName($username)."](".$link.")";
}else{
$uss = "[@".$uss."]";
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد نقاطك 💰: *".$json["points"]."*،

الطلب لـ ⭐: ".$uss."،
كل *3* نقطة 💰 = *1* مشترك 👤،
يمكنك شراء *".$max."* مشترك 👤،

أختر عدد المشتركين المطلوب شرائهم 🎖.",
"parse_mode"=>"markdown",
'disable_web_page_preview'=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[["text"=>"👤 130",'callback_data'=>'#javan_130'],['text'=>"👤 150",'callback_data'=>'#javan_150']],
[["text"=>"👤 200",'callback_data'=>'#javan_200'],['text'=>"👤 250",'callback_data'=>'#javan_250']],
[["text"=>"👤 300",'callback_data'=>'#javan_300'],['text'=>"👤 350",'callback_data'=>'#javan_350']],
[["text"=>"👤 400",'callback_data'=>'#javan_400'],['text'=>"👤 450",'callback_data'=>'#javan_450']],
[["text"=>"👤 500",'callback_data'=>'#javan_500'],["text"=>"👤 1000",'callback_data'=>'#javan_1000']],
[['text'=>"⏮ Back",'callback_data'=>'back/'.$username]],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
exit;
}else{
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
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
if(strstr($data,"back/")){
$username = explode("back/",$data)[1];
$idbot = explode(":",$token)[0];
$j = javan("getchatmember",[
"chat_id"=>$username,
"user_id"=>$idbot,
]);
if($j->result->status == "administrator"){
$json["step"] = "#sell2_".$username;
setH("users",$from_id2,json_encode($json));
$max = $json["points"];
$max = floor($max / 3);
$uss = getUsername($username);
if($uss == null){
$link = javan("getchat",[
"chat_id"=>$username
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$username
])->result;
}
$uss = "[".getName($username)."](".$link.")";
}else{
$uss = "[@".$uss."]";
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"عدد نقاطك 💰: *".$json["points"]."*،

الطلب ⭐: ".$uss."،
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
[['text'=>"Next ⏩",'callback_data'=>'next/'.$username]],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
exit;
}else{
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"ارفع البوت @OeeBot ادمن ب قناتك 😁",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"لماذا يجب رفع البوت ادمن في القناة 🤔⁉️",'url'=>'https://t.me/namkar1/144']],
[['text'=>"كيف رفع البوت ادمن 🤔 ⁉️",'url'=>'https://t.me/namkar1/166']],
[['text'=>"رجوع 🔙",'callback_data'=>'sell']],
]
])
]);
exit;
}
}
if($data == "sellc"){
$json["step"] = "sell";
setH("users",$from_id2,json_encode($json));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"لاضافه قناه ارفع هذا البوت @OeeBot [ادمن في القناة](https://t.me/namkar1/166)
ثم : أرسل معرف القناه 
أو : ارسل رابط القناه 😅
✅ارسل ( [اليوزرنيم او رابط القناة](https://telegra.ph/𝙰𝚍𝚍-𝚌𝚑-12-2) )
مثال :
@namkar1
@UUUUv
ملاحظة : سيتم حذف الطلب وتخسر نقاطك اذا كانت القناه منحرفة . جريئة او تحتوي سب وشتم",
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"لماذا يجب رفع البوت ادمن في القناة 🤔⁉️",'url'=>'https://t.me/namkar1/144']],
[['text'=>"كيف رفع البوت ادمن 🤔 ⁉️",'url'=>'https://t.me/namkar1/166']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
}
if($data == "sellg"){
$json["step"] = "sell";
setH("users",$from_id2,json_encode($json));
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>"لأضافه مجموعة :
اولاً : يجب ان تكون المجموعة خارقة, قم بتحويلها الى خارقة
ثانياً : ارفع هذا البوت @OeeBot [ادمن في القناة](https://t.me/namkar1/166)
ثالثاً : اضغط على اضافة وأرسل معرف او رابط المجموعه

✅ ارسل رابط  او معرف المجموعة ( [اليوزرنيم](https://telegra.ph/𝙰𝚍𝚍-𝚌𝚑-12-25-3) )
مثال :

@namkar1s

❌ : سيتم حذف الطلب وتخسر نقاطك اذا كانت مجموعتك منحرفة ، جريئه أو تحتوي على السب والشتم",
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"لماذا يجب رفع البوت ادمن في المجموعه 🤔⁉️",'url'=>'https://t.me/namkar1/144']],
[['text'=>"كيف رفع البوت ادمن 🤔 ⁉️",'url'=>'https://t.me/namkar1/166']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
}
if($data == "sell3"){
$gift = $json["sell"]["gift"];
$sp = $json["sell"]["members"];
$channel = $json["sell"]["channel"];
$max = $json["points"];
$points = floor($max / 3);
if($sp > $points){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "ليس لديك ما يكفي من النقاط 😿💰،
أنتبه كل 3 نقاط 💰،
تعطيك 1 عضو 👤.",
'show_alert' =>true
]);
}else{
$ch = json_decode(getNH("channels"),1);
$json["my_channels"] = array_unique($json["my_channels"]);
$json["my_channels"] = array_values($json["my_channels"]);
$json["points"] = $json["points"] - ($sp * 3);
$json["my_channels"][] = $channel;
$json["step"] = null;
setH("users",$from_id2,json_encode($json));
$chs = array();
$chs["points"] = $chs["points"] + (($sp + $gift) * 3);
$chs["points_o"] = $chs["points_o"] + (($sp + $gift) * 3);
$chs["admin"] = $from_id2;
$cr = javan("getchat",[
"chat_id"=>$channel
])->result;
$chs["type"] = $cr->type;
if(!isset($chs["time"])){
$chs["time"] = time();
}
$ch[$channel] = $chs;
setNH("channels",json_encode($ch));
$k = array_search($channel,array_keys($ch))+1;
$usert = getUsername($channel);
if($usert == null){
$link = $cr->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$channel
])->result;
}
$usert = "[".getName($channel)."](".$link.")";
}else{
$usert = "[@".$usert."]";
}
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
'disable_web_page_preview'=>true,
"text"=>"الحالة: جاري أضافة *".($sp+$gift)."* مستخدم 👤،
الى: ".$usert.".
----------------------------
يتم تنفيذ الطلبات بشكل متسلسل ⌛️،
تسلسل طلبك الأن: *".$k."* 💡.
----------------------------
⚠️: سيبدأ دخول المستخدمين لطلبك عندما يكون تسلسلهُ مقارب للرقم *50*، 

❌: أذا قمت بأزالة البوت من قائمة المشرفين سيتم ألغاء طلبك،
❌: أذا قمت بحذف حسابك من التليكرام سيتم ألغاء طلبك،
❌: أذا قمت بحظر البوت سيتم ألغاء طلبك.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"رجوع 🔙",'callback_data'=>'sell']],
]
])
]);
$us = "[".$from_id2."](tg://user?id=".$from_id2.")";
javan("sendmessage",[
"chat_id"=>"-1001489313802",
"text"=>"تم طلب *".($sp+$gift)."* مستخدم 🔥،
الى هذهِ القناة ".$usert." 🎉،
من قبل ".$us." ♟،
وتسلسل الطلب *".$k."* 🏅.",
"parse_mode"=>"markdown",
]);
exit;
}
}
if(strstr($json["step"],"#sell2_") and strstr($data,"#javan_")){
$text1 = explode("#javan_",$data)[1];
$username = explode("#sell2_",$json["step"])[1];
$max = $json["points"];
$points = floor($max / 3);
$gift = 0;
if(strstr($text1,"+")){
$text1 = explode("+",$text1);
$gift = $text1[1];
$text = $text1[0];
}else{
$text = $text1;
}
if($text > $points){
javan('answercallbackquery', [
'callback_query_id' =>$membercall,
'text' => "ليس لديك ما يكفي من النقاط 😿💰،
أنتبه كل 3 نقاط 💰،
تعطيك 1 عضو 👤.",
'show_alert' =>true
]);
exit;
}else{
$json["sell"]["channel"] = $username;
$json["sell"]["members"] = $text;
$json["sell"]["gift"] = $gift;
setH("users",$from_id2,json_encode($json));
$tt = getUsername($username);
if($tt == null){
$link = javan("getchat",[
"chat_id"=>$username
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$username
])->result;
}
$tt = "[".getName($username)."](".$link.")";
}else{
$tt = "[@".$tt."]";
}
$tr = "هل انت متأكد من عملية الشراء لهذهِ القناة: ".$tt.".";
javan("editmessagetext",[
"chat_id"=>$from_id2,
"message_id"=>$message_id,
"text"=>$tr,
'disable_web_page_preview'=>true,
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"لا",'callback_data'=>'sellc']],
[['text'=>"نعم",'callback_data'=>'sell3']],
]
])
]);
exit;
}
}