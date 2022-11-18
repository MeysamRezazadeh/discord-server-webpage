<!-- https://github.com/MeysamRezazadeh  -->
<?php

// PUT YOUR BOT TOKEN HERE
$TOKEN = "";

// PUT YOUR SERVER ID HERE
$SERVER_ID = '';

// GET USER INFO
$USER_INFO = [];
foreach ($_POST as $key => $value) {
    array_push($USER_INFO, $value);
}

if (count($USER_INFO) > 1) {
    $USER_ID = $USER_INFO[0];
    $status = $USER_INFO[1];
    $game = $USER_INFO[2];
    $game_icon = $USER_INFO[3];
} else {
    $USER_ID = $USER_INFO[0];
}

$urls = [
    'members/'.$USER_ID,
    'roles',
];

$data= [];

foreach ($urls as $url) {
    $curl= curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $link = 'https://discord.com/api/guilds/'.$SERVER_ID.'/'.$url;
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bot $TOKEN",
            'Content-Type: application/json',
        )
    );
    $res = curl_exec($curl);
    curl_close($curl);
    $olddata = json_decode($res);
    array_push($data, $olddata);
}

$member = $data[0];
$allroles = $data[1];
$user_roles = [];

$joined_at = $member->joined_at;
$nick = $member->nick;
$roles = $member->roles;
$id = $member->user->id;
$username = $member->user->username;
$avatar = $member->user->avatar;
$discriminator = $member->user->discriminator;
$public_flags = $member->user->public_flags;
if (isset($member->user->bot)) {
    $bot = $member->user->bot;
}

foreach ($roles as $role) {
    foreach ($allroles as $allrole) {
        if ($allrole->id == $role) {
            $role_name = $allrole->name;
            $role_color = $allrole->color;
            array_push($user_roles, [$role_name, $role_color]);
            break;
        }
    }
}

$hour = intval(substr($joined_at,11,2));
$minute = intval(substr($joined_at,14,4));
$second = intval(substr($joined_at,17,6));

$month = intval(substr($joined_at,5,7));
$day = intval(substr($joined_at,8,10));
$year = intval(substr($joined_at,0,4));

$joined = mktime($hour, $minute, $second, $month, $day, $year);


$secondsInAMinute = 60;
$secondsInAnHour  = 60 * $secondsInAMinute;
$secondsInADay    = 24 * $secondsInAnHour;

$inputSeconds = floor( time() - $joined );

// extract days
$days = floor($inputSeconds / $secondsInADay);

// extract hours
$hourSeconds = $inputSeconds % $secondsInADay;
$hours = floor($hourSeconds / $secondsInAnHour);

// extract minutes
$minuteSeconds = $hourSeconds % $secondsInAnHour;
$minutes = floor($minuteSeconds / $secondsInAMinute);

// extract the remaining seconds
$remainingSeconds = $minuteSeconds % $secondsInAMinute;
$seconds = ceil($remainingSeconds);

// return the final array
$obj = array(
    'd' => (int) $days,
    'h' => (int) $hours,
    'm' => (int) $minutes,
    's' => (int) $seconds,
);

$time_joined = $year ."-". $month ."-". $day .", ". $hour .":". $minute .":". $second;
$time_passed = $days ." days, ". $hours ." hours, ". $minutes ." minutes, ". $seconds ." seconds ";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/popout.css">

    <script>
        var bin = (+<?php echo $id ?>).toString(2);
        var unixbin = '';
        var unix = '';
        var m = 64 - bin.length;
        unixbin = bin.substring(0, 42-m);
        unix = parseInt(unixbin, 2) + 1420070400000;
        var timestamp = moment.unix(unix/1000);
        document.getElementById('createDate').innerHTML = timestamp.format('YYYY-MM-DD, HH:mm:ss');
        var passed_seconds = moment().diff(timestamp, 'seconds');


        seconds = Number(passed_seconds);
        var d = Math.floor(seconds / (3600*24));
        var h = Math.floor(seconds % (3600*24) / 3600);
        var m = Math.floor(seconds % 3600 / 60);
        var s = Math.floor(seconds % 60);

        var dDisplay = d > 0 ? d + (d == 1 ? " day, " : " days, ") : "";
        var hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
        var mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
        var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
        document.getElementById('difDate').innerHTML = dDisplay + hDisplay + mDisplay + sDisplay;
    </script>

</head>

<body oncontextmenu="return false">

<div class="profilecard">
    <div class="me">
        <a class="close" onclick="closeBox('popout')"></a>
        <div class="avatar">
            <?php if ($avatar != null) { ?>
                <img class="img-profile" src="https://cdn.discordapp.com/avatars/<?php echo $id ?>/<?php echo $avatar ?>.webp?size=128" alt="">
            <?php } else { ?>
                <img class="img-profile" src="images/default.webp" alt="">
            <?php } ?>
            <?php if (isset($status)) { ?>
                <img class="img-statusx" src="images/<?php echo $status; ?>.png" alt="">
            <?php } else { ?>
                <img class="img-statusx" src="images/offline.png" alt="">
            <?php } ?>
            <?php if (isset($bot)) { ?>
                <img class="img-statusx" style="margin: 55px -75px;" src="images/bot.png" alt="">
            <?php } ?>
        </div>
        <div class="username">
            <span><strong><?php echo $username; ?></strong>#<?php echo $discriminator ?></span>
        </div>
    </div>
    <div class="role">
        <span><strong>ROLES</strong></span>
        <div class="roles-list">
            <?php foreach ($user_roles as $role) { ?>
                <div class="rolex" style="display: inline-flex">
                    <div class="color" style="background-color: <?php echo "#".dechex((float) $role[1]) ?>"></div>
                    <div><?php echo $role[0] ?></div>
                </div>
            <?php } ?>
        </div>
    </div>
        <div class="note">
            <div class="noteheader">
                <span><strong>PLAYING</strong></span>
                <div style="margin: 10px 10px; display: flex;">
                    <?php if ($game_icon != null) { ?>
                        <div class="game_icon">
                            <img src="<?php echo $game_icon; ?>" alt="">
                        </div>
                    <?php } else { ?>
                        <div class="game_icon">
                            <img src="images/game-default.svg" alt="">
                        </div>
                    <?php } if ($game != null) { ?>
                        <div style="margin: auto 5px; font-size: 12pt">
                            <span><?php echo $game; ?></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    <div class="tip">
        <span><strong>Created at: </strong><span id="createDate"></span></span><br>
        <span><strong>Account age: </strong><span id="difDate"></span></span><br>
        <span><strong>Joined at: </strong><?php echo $time_joined ?></span><br>
        <span><strong>In server for: </strong><?php echo $time_passed; ?></span>
    </div>
</div>
</body>
</html>