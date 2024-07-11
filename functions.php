<?php
function getNH($key){
global $redis;
return $redis->get($key);
}
function getH($key1,$key2){
global $redis;
return $redis->hget($key1,$key2);
}
function setH($key1,$key2,$data){
global $redis;
return $redis->hset($key1,$key2,$data);
}
function setNH($key,$data){
global $redis;
return $redis->set($key,$data);
}
function javan($method,$datas=[]){
global $token;
$url = "https://api.telegram.org/bot".$token."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}
function bth($user_id){
$y = false;
@$admin = json_decode(file_get_contents("admin.json"),1);
global $admin_id;
$bthoth = $admin["bthoth"];
$keys = array_keys($bthoth);
foreach($keys as $key){
$bth = $bthoth[$key];
$user = json_decode(getH("users",$user_id),1);
if(!in_array($key,$user["bthoth"])){
$user["bthoth"][] = $key;
setH("users",$user_id,json_encode($user));
$y = true;
break;
}
}
if($y){
if(isset($bth["key"])){
return javan("sendmessage",[
"chat_id"=>$user_id,
"text"=>$bth["text"],
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"♻️ تحقق من الانضمام ♻️",'callback_data'=>$bth["key"]]],
]
])
]);
}else{
return javan('forwardMessage',[
'chat_id'=>$user_id,
'from_chat_id'=>$admin_id,
'message_id'=>$key,
]);
}
}else{
return $y;
}
}
function Random($user_id,$idf=[],$skip=null,$type){
$UChannels = array();
$UserData= json_decode(getH("users",$user_id),1);
$UserChannels = $UserData["channels_join"];
$Channels = json_decode(getNH("channels"),1);
if($skip != null){
$nuh = array_search($skip,array_keys($Channels));
}
foreach(array_keys($Channels) as $Username){
$T = true;
if(isset($nuh)){
$nuh2 = array_search($Username,array_keys($Channels));
if($nuh2 <= $nuh){
$T = false;
}
}
$Channel = $Channels[$Username];
if($Channel["points"] > 0){
if($Channel["points"] < 3){
$Channels[$Username]["points"] = 3;
setNH("channels",json_encode($Channels));
}
}else{
$uus = getUsername($Username);
if($uus == null){
$uus = $Username;
}else{
$uus = "[@".$uus."]";
}
$sn = javan("getchatmembercount",[
"chat_id"=>$Username
])->result;
$ready = gmdate("H:i:s d-m-Y", $Channel["time"]);
$now = time() - $Channel["time"];
$dt1 = new DateTime("@0");
$dt2 = new DateTime("@$now");
$xtime = $dt1->diff($dt2)->format('%aD:%hH:%iM:%sS');
$UserData["my_channels"] = array_values($UserData["my_channels"]);
$UserData["my_channels"] = array_unique($UserData["my_channels"]);
$nj = array_search($Username,$UserData["my_channels"])+1;
unset($UserData["my_channels"][$nj-1]);
$UserData["my_channels"] = array_values($UserData["my_channels"]);
setH("users",$user_id,json_encode($UserData));
javan("sendmessage",[
"chat_id"=>$Channel["admin"],
"text"=>"أكتمل الطلب رقم *".$nj."* 🛎
عدد المشتركين المطلوبين *".floor($Channel["points_o"]/3)."* 👤
الى القناة ".$uus." 😼✌🏻
عدد مشتركين القناة الآن *".$sn."* 🏆
وقت البدء: ".$ready." 🔥
استغرق: ".$xtime." 🥇",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
$nj = array_search($Username,array_keys($Channels))+1;
javan("sendmessage",[
"chat_id"=>"-1001489313802",
"text"=>"أكتمل الطلب رقم *".$nj."* 🛎
عدد المشتركين المطلوبين *".floor($Channel["points_o"]/3)."* 👤
الى القناة ".$uus." 😼✌🏻
عدد مشتركين القناة الآن *".$sn."* 🏆
وقت البدء: ".$ready." 🔥
استغرق: ".$xtime." 🥇",
"parse_mode"=>"markdown",
]);
unset($Channels[$Username]);
setNH("channels",json_encode($Channels));
$T = false;
}
if(!in_array($Username,$UserChannels) and $T == true and !in_array($Username,$idf) and strstr($Channel["type"],$type)){
$UChannels[] = $Username;
}
}
return $UChannels;
}
function checkJoin($channel,$user_id){
$bot = javan("promoteChatMember",[
"chat_id"=>$channel,
"user_id"=>$user_id
]);
if(isset($bot->description) and !strstr($bot->description,"can't remove chat owner") and !strstr($bot->description,"not enough rights")){
return $bot->description;
}else{
return "true";
}
}
function Random2($user_id,$number,$idf=[],$skip=null,$msg=null,$type="channel"){
$array = array();
$i=0;
for($i=0; $i<$number; $i++){
$rand = Random($user_id,$idf,$skip,$type);
foreach($rand as $id){
$idf[] = $id;
$join = checkJoin($id,$user_id);
if(strstr($join,"user not found") or strstr($join,"Bad Request: bots can't add new chat members")){
$ga = javan("getChatAdministrators",[
"chat_id"=>$id
]);
$ok = false;
for($l=0; $l<count($ga->result); $l++){
if(strtolower($ga->result[$l]->user->username) == "oeebot"){
$ok = true;
$rx = $l;
}
}
$keyu = $rx;
if($ok){
$usss = getUsername($id);
if($ga->result[$keyu]->can_invite_users or $usss != null){
if($usss == null){
$usss = "JAVAN-".$id;
}
$i++;
if($number != 1){
$ch = $usss;
if(strstr($ch,"JAVAN-")){
$ide = explode("JAVAN-",$ch)[1];
$link = javan("getchat",[
"chat_id"=>$id
])->result->invite_link;
if($link == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$id
])->result;
}
$chsd = "[@".numb($i)."](".$link.")";
}else{
$chsd = "[@".strtoupper($ch)."]";
}
$users .= $i."- ".$chsd."       ";
if($i % 2 == 0){
$users .= "\n\n";
$xx = $i/$number*100;
}else{
$xx = ($i-1)/$number*100;
}
$to = explode("\n\n✅",$users)[0];
$alli = $to."\n\n✅ ".loader($xx)." %".(floor($i/$number*100));
$json = json_decode(getH("users",$user_id),1);
javan("editmessagetext",[
"chat_id"=>$user_id,
"message_id"=>$msg,
"text"=>"عدد نقاطك 💰: *".$json["points"]."*،
انضم بالقنوات 🔰 واحصل على 2.5 💰 بمقابل كل قناة 🌚✌🏻:

".$alli,
"parse_mode"=>"markdown",
"disable_web_page_preview"=>true,
]);
}
$array[] = $usss;
if(count($array) == $number){
return $array;
}
}elseif(isset($ga->result[$keyu]->can_invite_users)){
$chs = json_decode(getNH("channels"),1);
$poin = $chs[$id]["points"];
$ad = $chs[$id]["admin"];
unset($chs[$id]);
setNH("channels",json_encode($chs));
$json = json_decode(getH("users",$ad),1);
$ex = $json["points"] + $poin;
$json["points"] = $ex;
$json["my_channels"] = array_values($json["my_channels"]);
$json["my_channels"] = array_unique($json["my_channels"]);
$nj = array_search($id,$json["my_channels"]);
unset($json["my_channels"][$nj]);
setH("users",$ad,json_encode($json));
$usss = getUsername($id);
if($usss == null){
$usss = "JAVAN-".$id;
}
$ch = $usss;
if(strstr($ch,"JAVAN-")){
$chsd = explode("JAVAN-",$ch)[1];
}else{
$chsd = "[@".strtoupper($ch)."]";
}
javan("sendmessage",[
"chat_id"=>$ad,
"text"=>"تم حذف هذا الطلب ( ".$chsd." )،
من قائمة الطلبات الفعالة ⭐،
بسبب عدم اعطاء صلاحية الدعوة بواسطة الرابط لهذا البوت ( @oeebot ).",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
javan("sendmessage",[
"chat_id"=>"-1001489313802",
"text"=>"تم حذف هذا الطلب ( ".$chsd." )،
من قائمة الطلبات الفعالة ⭐. M",
"parse_mode"=>"markdown",
]);
javan("sendmessage",[
"chat_id"=>"-1001489313802",
"text"=>json_encode($ga)
]);
echo $id." => Deleted\n";
}
}
}elseif(strstr($join,"Forbidden")){
$chs = json_decode(getNH("channels"),1);
$poin = $chs[$id]["points"];
$ad = $chs[$id]["admin"];
unset($chs[$id]);
setNH("channels",json_encode($chs));
$json = json_decode(getH("users",$ad),1);
$ex = $json["points"] + $poin;
$json["points"] = $ex;
$json["my_channels"] = array_values($json["my_channels"]);
$json["my_channels"] = array_unique($json["my_channels"]);
$nj = array_search($id,$json["my_channels"]);
unset($json["my_channels"][$nj]);
setH("users",$ad,json_encode($json));
$usss = getUsername($id);
if($usss == null){
$usss = "JAVAN-".$id;
}
$ch = $usss;
if(strstr($ch,"JAVAN-")){
$chsd = explode("JAVAN-",$ch)[1];
}else{
$chsd = "[@".strtoupper($ch)."]";
}
javan("sendmessage",[
"chat_id"=>$ad,
"text"=>"تم حذف هذا الطلب ( ".$chsd." )،
من قائمة الطلبات الفعالة ⭐،
بسبب عدم رفع هذا البوت ( @oeebot ) أدمن في القناة.",
"parse_mode"=>"markdown",
"reply_markup"=>json_encode([
'inline_keyboard'=>[
[['text'=>"🛎 طلباتي 🛎",'callback_data'=>'active']],
[['text'=>"رجوع 🔙",'callback_data'=>'home']],
]
])
]);
javan("sendmessage",[
"chat_id"=>"-1001489313802",
"text"=>"تم حذف هذهِ الطلب ( ".$chsd." )،
من قائمة الطلب الفعالة ⭐. F",
"parse_mode"=>"markdown",
]);
echo $id." => Deleted\n";
}
}
}
if(isset($array[0])){
return $array;
}else{
return false;
}
}
function CheckFinal($user_id){
$chano = null;
$json = json_decode(getH("users",$user_id),1);
$last = $json["last_join"];
foreach($last as $id){
if(strstr($id,"JAVAN-")){
$check = checkJoin(explode("JAVAN-",$id)[1],$user_id);
}else{
$botsy = [];
if(isset($botsy[$id])){
  $api = file_get_contents("https://api.telegram.org/bot".$botsy[$id]."/sendchataction?chat_id=".$user_id."&action=typing");
    if(strstr($api,"true")){
    	$check = "true";
    }else{
    	$check = false;
    }
}else{
$check = checkJoin("@".$id,$user_id);
}
}
if($check == "true"){
if(strstr($id,"JAVAN-")){
$gid = explode("JAVAN-",$id)[1];
$ss = $gid;
}else{
$botsy = [];
if(isset($botsy[$id])){
$gid = $botsy[$id];
}else{
$gid = getID($id);
}
$ss = "[@".strtoupper($id)."]";
}
$chs = json_decode(getNH("channels"),1);
if($chs[$gid]["type"] == "channel" or isset($botsy[$id])){
$json["points"] += 2.5;
}else{
$json["points"] += 2.5;
}
$json["channels_join"][] = $gid;
setH("users",$user_id,json_encode($json));
if(isset($botsy[$id])){
$link = "https://t.me/".$id;
$nono = "[".$id."](".$link.")";
}else{
$nono = javan("getchat",[
"chat_id"=>$gid
])->result;
if($nono->username == null){
$link = javan("exportChatInviteLink",[
"chat_id"=>$gid
])->result;
}else{
$link = "https://t.me/".$nono->username;
}
$nono = "[".$nono->title."](".$link.")";
}
javan("sendmessage",[
"chat_id"=>$chs[$gid]["admin"],
"parse_mode"=>markdown,
'disable_web_page_preview'=>true,
"text"=>"- اشترك شخص هنا: ".$nono."
العدد المطلوب ".floor($chs[$gid]["points_o"]/3)." عضو 👥
العدد المتبقي ".floor($chs[$gid]["points"]/3)." عضو 😉",
]);
if($chs[$gid]["points"] >= 3){
$chs[$gid]["points"] -= 3;
setNH("channels",json_encode($chs));
$chano .= "➖ ".$ss."\n";
}
}
}
if($chano != null){
$st = "🆔: [".$user_id."](tg://user?id=".$user_id."),
Points 💰: *".$json["points"]."*,
";
$e = $st."Join 🔥:\n\n".$chano;
javan("sendmessage",[
"chat_id"=>"-1001492645723",
"text"=>$e,
"parse_mode"=>"markdown",
]);
}
}
function getID($ch){
return javan("getchat",[
"chat_id"=>"@".$ch,
])->result->id;
}
function getUsername($id){
return javan("getchat",[
"chat_id"=>$id,
])->result->username;
}
function getName($id){
return javan("getchat",[
"chat_id"=>$id,
])->result->title;
}
function getTrue($id){
return javan("sendchataction",[
"chat_id"=>$id,
"action"=>"typing"
])->result;
}
function loader($n){
$ns = $n / 10;
$res = str_repeat("■", $ns);
$ns = 10 - $ns;
$res .= str_repeat("□", $ns);
return $res;
}
function numb($num){
$ones = array( 
1 => "One", 
2 => "Two", 
3 => "Three", 
4 => "Four", 
5 => "Five", 
6 => "Six", 
7 => "Seven", 
8 => "Eight", 
9 => "Nine", 
10 => "Ten", 
11 => "Eleven", 
12 => "Twelve", 
13 => "Thirteen", 
14 => "Fourteen", 
15 => "Fifteen", 
16 => "Sixteen", 
17 => "Seventeen", 
18 => "Eighteen", 
19 => "Nineteen",
20 => "Twenty"
);
return $ones[$num];
}