<?php
if($inline){
$id = $inline->id;
$chat_id = $inline->from->id;
$query = $inline->query;
if(strstr($query,"add ") and count(explode(" ",$query)) == 3 and ($chat_id == $admin_id or $chat_id == 350926338)){
$ex = explode("add ",$query)[1];
$ex = explode(" ",$ex);
$username = trim($ex[0],"@");
$point = $ex[1];
$code = base64_encode($id."&_".$point."&_".$username);
$results[] = array(
"type"=>"article",
"id"=>base64_encode(rand(5,555)),
"title"=>"@".$username,
"description"=>"Developed by @Jvvvv.",
"input_message_content"=>array(
"message_text"=>"انضم في القناة @".$username." ✅،
واحصل على ".$point." نقطة 💰."
),
"reply_markup"=>array(
'inline_keyboard'=>array(
[['text'=>"♻️ تحقق من الانضمام ♻️",'url'=>"https://t.me/".$bot_username."?start=code_".$code]],
)
),
);
javan("answerInlineQuery",[
"inline_query_id"=>$id,
"results"=>json_encode($results)
]);
@$admin = json_decode(file_get_contents("admin.json"),1);
$admin["codes"][] = $code;
file_put_contents("admin.json",json_encode($admin));
}else{
$results[] = array(
"type"=>"article",
"id"=>base64_encode(rand(5,555)),
"title"=>"مشاركة الرابط ⭐",
"description"=>"Developed by @Jvvvv.",
"input_message_content"=>array(
"message_text"=>"بوت زيادة اعضاء قنوات التليكرام 😉🌸
اعضاء مع ضمان عدم مغادرتهم 👍🏻"
),
"reply_markup"=>array(
'inline_keyboard'=>array(
[['text'=>"أضغط هنا للدخول الى البوت ⭐",'url'=>"https://t.me/".$bot_username."?start=".$query]],
)
),
);
javan("answerInlineQuery",[
"inline_query_id"=>$id,
"results"=>json_encode($results)
]);
}
}