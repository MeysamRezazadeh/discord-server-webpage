<!-- https://github.com/MeysamRezazadeh  -->
<?php

// PUT YOUR BOT TOKEN HERE
$TOKEN = "";

// PUT YOUR SERVER ID HERE
$SERVER_ID = '';

$urls = [
    'preview',
//    'channels',
//    'threads/active',
    'members?limit=1000',
//    'roles',
//    'integrations',
    'widget.json',
//    'voice-states/{user.id}',
];

$data= [];
$online_members = [];

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

$preview = $data[0];
$all_members = $data[1];
$widget = $data[2];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $preview->name ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="https://cdn.discordapp.com/icons/<?php echo $SERVER_ID ?>/<?php echo $preview->icon ?>.webp">

    <script>
        document.onkeydown = function(e) {
            if(event.keyCode == 123) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
                return false;
            }
        }
        document.onreadystatechange = function() {
            if (document.readyState !== "complete") {
                document.querySelector(
                    "body").style.visibility = "hidden";
                document.querySelector(
                    "section").style.visibility = "visible";
                document.querySelector("body").style.overflow = "hidden";
            } else {
                document.querySelector(
                    "section").style.display = "none";
                document.querySelector(
                    "body").style.visibility = "visible";
                document.querySelector("body").style.overflow = "visible";
            }
        };
    </script>

</head>


<body oncontextmenu="return false">

<!-- LOADER -->
<section>
    <svg>
        <filter id="gooey">
            <feGaussianBlur in="SourceGraphic" stdDeviation="10"/>
            <feColorMatrix values="
                  1 0 0 0 0
                  0 1 0 0 0
                  0 0 1 0 0
                  0 0 0 20 -10">
            </feColorMatrix>
        </filter>
    </svg>
    <div class="loader">
        <span style="--i:1;"></span>
        <span style="--i:2;"></span>
        <span style="--i:3;"></span>
        <span style="--i:4;"></span>
        <span style="--i:5;"></span>
        <span style="--i:6;"></span>
        <span style="--i:7;"></span>
        <span style="--i:8;"></span>
        <span class="rotate" style="--j:0;"></span>
        <span class="rotate" style="--j:1;"></span>
        <span class="rotate" style="--j:2;"></span>
        <span class="rotate" style="--j:3;"></span>
        <span class="rotate" style="--j:4;"></span>
    </div>
</section>

<!-- BACKGROUND STARS -->
<div id='stars'></div>
<div id='stars2'></div>
<div id='stars3'></div>

<div class="container">
    <!-- SEVER INFO & VOICE CHANNELS-->
    <div class="row d-flex">
        <!-- SEVER INFO-->
        <div class="server">
            <!-- SERVER PROFILE PIC -->
            <img src="https://cdn.discordapp.com/icons/<?php echo $SERVER_ID ?>/<?php echo $preview->icon ?>.webp" alt="" class="logo">
            <!-- SERVER NAME -->
            <h1><?php echo $preview->name ?></h1>
            <!-- MEMBER COUNTER-->
            <div class="d-grid" ">
                <span>ALL MEMBERS: <?php echo $preview->approximate_member_count ?></span>
                <span>ONLINE MEMBERS: <?php echo $preview->approximate_presence_count ?></span>
                <?php $in_vc = [];
                foreach ($widget->members as $member) {
                    if (isset($member->channel_id)) {
                        array_push($in_vc, $member->channel_id);
                    }
                } ?>
                <span>MEMBERS IN VC: <?php echo count($in_vc) ?></span>
            </div>
            <!-- INVITE LINK -->
            <div>
                <a href="<?php echo $widget->instant_invite ?>">Join Server</a>
            </div>

        </div>
        <!-- EMOJIS-->
        <div class="emojis">
            <span>EMOJIS</span>
            <hr>
            <?php foreach ($preview->emojis as $emoji) { ?>
                <?php if ($emoji->animated == "true") { $type = "gif"; } else { $type = "webp"; } ?>
                <img src="https://cdn.discordapp.com/emojis/<?php echo $emoji->id ?>.<?php echo $type ?>?size=48&quality=lossless" alt="<?php echo $emoji->name ?>">
            <?php } ?>
        </div>
    </div>

    <!-- VOICE CHANNELS-->
    <div class="row d-block">
        <span>VOICE CHANNELS</span>
        <hr>
    </div>
    <div class="row d-flex">
        <div class="channels d-flex">
            <?php foreach ($widget->channels as $channel) { ?>
                <div class="channel">
                    <div class="d-flex">
                        <img src="images/speaker.png" alt="">
                        <div class="channel-title">
                            <span><?php echo $channel->name ?></span>
                        </div>
                    </div>
                    <div class="channel-members">
                        <?php foreach ($widget->members as $member) { ?>
                            <?php if (isset($member->channel_id) and ($member->channel_id == $channel->id)) { ?>
                            <div class="card-small d-flex" style='background-image: url("<?php echo $member->avatar_url ?>");'>
                                <!-- STATUS -->
                                <?php if ($member->status == "online") { ?>
                                    <div class="status" style="border-left:2px solid green;"></div>
                                <?php } elseif ($member->status == "idle") { ?>
                                    <div class="status" style="border-left:2px solid yellow;"></div>
                                <?php } elseif  ($member->status == "dnd") { ?>
                                    <div class="status" style="border-left:2px solid red;"></div>
                                <?php } ?>
                                <!-- NAME-->
                                <div class="card-small-title">
                                    <span><?php echo $member->username ?></span>
                                </div>
                                <!-- MUTE OR DEAF-->
                                <div class="mute-deaf">
                                    <!-- MUTE -->
                                    <?php if ($member->mute == true and $member->self_mute == true) { ?>
                                        <svg class="mute-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M6.7 11H5C5 12.19 5.34 13.3 5.9 14.28L7.13 13.05C6.86 12.43 6.7 11.74 6.7 11Z" fill="currentColor"></path><path d="M9.01 11.085C9.015 11.1125 9.02 11.14 9.02 11.17L15 5.18V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 11.03 9.005 11.0575 9.01 11.085Z" fill="currentColor"></path><path d="M11.7237 16.0927L10.9632 16.8531L10.2533 17.5688C10.4978 17.633 10.747 17.6839 11 17.72V22H13V17.72C16.28 17.23 19 14.41 19 11H17.3C17.3 14 14.76 16.1 12 16.1C11.9076 16.1 11.8155 16.0975 11.7237 16.0927Z" fill="currentColor"></path><path d="M21 4.27L19.73 3L3 19.73L4.27 21L8.46 16.82L9.69 15.58L11.35 13.92L14.99 10.28L21 4.27Z" class="mute-deaf-line" fill="currentColor"></path></svg>
                                    <?php } elseif ($member->mute == true and $member->self_mute == false) {  ?>
                                        <svg class="mute-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M6.7 11H5C5 12.19 5.34 13.3 5.9 14.28L7.13 13.05C6.86 12.43 6.7 11.74 6.7 11Z" fill="currentColor"></path><path d="M9.01 11.085C9.015 11.1125 9.02 11.14 9.02 11.17L15 5.18V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 11.03 9.005 11.0575 9.01 11.085Z" fill="currentColor"></path><path d="M11.7237 16.0927L10.9632 16.8531L10.2533 17.5688C10.4978 17.633 10.747 17.6839 11 17.72V22H13V17.72C16.28 17.23 19 14.41 19 11H17.3C17.3 14 14.76 16.1 12 16.1C11.9076 16.1 11.8155 16.0975 11.7237 16.0927Z" fill="currentColor"></path><path d="M21 4.27L19.73 3L3 19.73L4.27 21L8.46 16.82L9.69 15.58L11.35 13.92L14.99 10.28L21 4.27Z" class="mute-deaf-line" fill="currentColor"></path></svg>
                                    <?php } elseif ($member->mute == false and $member->self_mute == true) {  ?>
                                        <svg class="self-mute-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M6.7 11H5C5 12.19 5.34 13.3 5.9 14.28L7.13 13.05C6.86 12.43 6.7 11.74 6.7 11Z" fill="currentColor"></path><path d="M9.01 11.085C9.015 11.1125 9.02 11.14 9.02 11.17L15 5.18V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 11.03 9.005 11.0575 9.01 11.085Z" fill="currentColor"></path><path d="M11.7237 16.0927L10.9632 16.8531L10.2533 17.5688C10.4978 17.633 10.747 17.6839 11 17.72V22H13V17.72C16.28 17.23 19 14.41 19 11H17.3C17.3 14 14.76 16.1 12 16.1C11.9076 16.1 11.8155 16.0975 11.7237 16.0927Z" fill="currentColor"></path><path d="M21 4.27L19.73 3L3 19.73L4.27 21L8.46 16.82L9.69 15.58L11.35 13.92L14.99 10.28L21 4.27Z" class="mute-deaf-line" fill="currentColor"></path></svg>
                                    <?php }?>

                                    <!-- DEAF -->
                                    <?php if ($member->deaf == true and $member->self_deaf == true) { ?>
                                        <svg class="deaf-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M6.16204 15.0065C6.10859 15.0022 6.05455 15 6 15H4V12C4 7.588 7.589 4 12 4C13.4809 4 14.8691 4.40439 16.0599 5.10859L17.5102 3.65835C15.9292 2.61064 14.0346 2 12 2C6.486 2 2 6.485 2 12V19.1685L6.16204 15.0065Z" fill="currentColor"></path><path d="M19.725 9.91686C19.9043 10.5813 20 11.2796 20 12V15H18C16.896 15 16 15.896 16 17V20C16 21.104 16.896 22 18 22H20C21.105 22 22 21.104 22 20V12C22 10.7075 21.7536 9.47149 21.3053 8.33658L19.725 9.91686Z" fill="currentColor"></path><path d="M3.20101 23.6243L1.7868 22.2101L21.5858 2.41113L23 3.82535L3.20101 23.6243Z" class="mute-deaf-line" fill="currentColor"></path></svg>
                                    <?php } elseif ($member->deaf == true and $member->self_deaf == false) {  ?>
                                        <svg class="deaf-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M6.16204 15.0065C6.10859 15.0022 6.05455 15 6 15H4V12C4 7.588 7.589 4 12 4C13.4809 4 14.8691 4.40439 16.0599 5.10859L17.5102 3.65835C15.9292 2.61064 14.0346 2 12 2C6.486 2 2 6.485 2 12V19.1685L6.16204 15.0065Z" fill="currentColor"></path><path d="M19.725 9.91686C19.9043 10.5813 20 11.2796 20 12V15H18C16.896 15 16 15.896 16 17V20C16 21.104 16.896 22 18 22H20C21.105 22 22 21.104 22 20V12C22 10.7075 21.7536 9.47149 21.3053 8.33658L19.725 9.91686Z" fill="currentColor"></path><path d="M3.20101 23.6243L1.7868 22.2101L21.5858 2.41113L23 3.82535L3.20101 23.6243Z" class="mute-deaf-line" fill="currentColor"></path></svg>
                                    <?php } elseif ($member->deaf == false and $member->self_deaf == true) {  ?>
                                        <svg class="self-deaf-icon" aria-hidden="true" role="img" width="24" height="24" viewBox="0 0 24 24"><path d="M6.16204 15.0065C6.10859 15.0022 6.05455 15 6 15H4V12C4 7.588 7.589 4 12 4C13.4809 4 14.8691 4.40439 16.0599 5.10859L17.5102 3.65835C15.9292 2.61064 14.0346 2 12 2C6.486 2 2 6.485 2 12V19.1685L6.16204 15.0065Z" fill="currentColor"></path><path d="M19.725 9.91686C19.9043 10.5813 20 11.2796 20 12V15H18C16.896 15 16 15.896 16 17V20C16 21.104 16.896 22 18 22H20C21.105 22 22 21.104 22 20V12C22 10.7075 21.7536 9.47149 21.3053 8.33658L19.725 9.91686Z" fill="currentColor"></path><path d="M3.20101 23.6243L1.7868 22.2101L21.5858 2.41113L23 3.82535L3.20101 23.6243Z" class="mute-deaf-line" fill="currentColor"></path></svg>
                                    <?php }?>
                                </div>
                            </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- ONLINE MEMBERS -->
    <div class="row d-block">
        <span>ONLINE MEMBERS (<?php echo $preview->approximate_presence_count ?>)</span>
        <hr>
    </div>
    <div class="row d-flex">
        <?php foreach ($widget->members as $member) { ?>
            <!-- MEMBER INFO-->
            <div class="card d-flex">
                <!-- PROFILE PIC & STATUS-->
                <div>
                    <!-- PROFILE PIC-->
                    <img class="img-profile" src="<?php echo $member->avatar_url ?>" alt="">
                    <!-- STATUS-->
                    <?php
                    $status = ["online", "idle", "dnd"];
                    foreach ($status as $x) {
                        if ($member->status == $x) { ?>
                            <img class="img-status" src="images/<?php echo $member->status ?>.png" alt="">
                            <?php break;
                        }
                    }
                    ?>
                </div>
                <!-- NAME & GAME-->
                <div class="card-center d-grid">
                    <!-- NAME-->
                    <span><?php echo $member->username ?></span>
                    <!-- GAME-->
                    <?php if (isset($member->game)) { ?>
                        <span><b>Playing:</b> <?php echo $member->game->name ?></span>
                    <?php } ?>
                </div>
                <!-- GAME ICONS-->
                <?php
                if (isset($member->game)) {
                    $games = array(
                        "VALORANT" => "https://cdn.discordapp.com/app-icons/700136079562375258/e55fc8259df1548328f977d302779ab7.webp?size=64&keep_aspect_ratio=false",
                        "Dota 2" => "https://cdn.discordapp.com/app-icons/356875988589740042/6b4b3fa4c83555d3008de69d33a60588.webp?size=40&keep_aspect_ratio=false",
                        "Rust" => "https://cdn.discordapp.com/app-icons/356888738724446208/9ab7e18473429b016307b867e6c924a4.webp?size=40&keep_aspect_ratio=false",
                        "Counter-Strike: Global Offensive" => "https://cdn.discordapp.com/app-icons/356875057940791296/782a3bb612c6f1b3f7deed554935f361.webp?size=40&keep_aspect_ratio=false",
                    );
                    foreach ($games as $key => $val) {
                        if ($key == $member->game->name) { ?>
                            <div class="card-right">
                                <img src="<?php echo $val ?>" alt="">
                            </div>
                        <?php }
                    }
                } ?>
            </div>
            <!-- SAVE ALL ONLINE MEMBERS NAME IN $online_members-->
            <?php array_push($online_members, $member->username); ?>
        <?php } ?>
    </div>

    <!-- OFFLINE MEMBERS -->
    <div class="row d-block">
        <span>OFFLINE MEMBERS (<?php echo (intval($preview->approximate_member_count)-(count($online_members))) ?>)</span>
        <hr>
    </div>
    <div class="row d-flex">
        <?php foreach ($all_members as $my_member) {
            $member = $my_member->user;
            if ($my_member->nick == null) {
                $name = $member->username;
            } else {
                $name = $my_member->nick;
            }
            if (!in_array($name, $online_members)) { ?>
            <!-- MEMBER INFO-->
            <div class="card d-flex">
                <!-- PROFILE PIC -->
                <div>
                    <?php if ($member->avatar != null) { ?>
                        <img class="img-profile" src="https://cdn.discordapp.com/avatars/<?php echo $member->id ?>/<?php echo $member->avatar ?>.webp?size=80" alt="">
                    <?php } else { ?>
                        <img class="img-profile" src="images/default.webp" alt="">
                    <?php } ?>
                </div>
                <!-- NAME -->
                <div class="card-center">
                    <span><?php echo $name ?></span>
                </div>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="footer d-grid">
        <a href="https://github.com/MeysamRezazadeh/custom=widget-discord-bot">
            Download source code
        </a>
        <span>Create by <a href="https://github.com/MeysamRezazadeh" target="_blank">Sambyte</a></span>
    </div>
</body>
</html>

<!-- https://github.com/MeysamRezazadeh  -->
