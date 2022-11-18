<!-- https://github.com/MeysamRezazadeh  -->
<?php

// PUT YOUR BOT TOKEN HERE
$TOKEN = "";

// PUT YOUR SERVER ID HERE
$SERVER_ID = '';

$urls = [
    'preview',
    'members?limit=1000',
    'widget.json',
];

$data= [];
$online_members = [];
$games = array(
    "VALORANT" => "https://cdn.discordapp.com/app-icons/700136079562375258/e55fc8259df1548328f977d302779ab7.webp?size=64&keep_aspect_ratio=false",
    "Dota 2" => "https://cdn.discordapp.com/app-icons/356875988589740042/6b4b3fa4c83555d3008de69d33a60588.webp?size=40&keep_aspect_ratio=false",
    "Rust" => "https://cdn.discordapp.com/app-icons/356888738724446208/9ab7e18473429b016307b867e6c924a4.webp?size=40&keep_aspect_ratio=false",
    "Counter-Strike: Global Offensive" => "https://cdn.discordapp.com/app-icons/356875057940791296/782a3bb612c6f1b3f7deed554935f361.webp?size=40&keep_aspect_ratio=false",
    "StepBrosGame" => "https://cdn.discordapp.com/app-icons/1010845730799362109/196e59a173c5ac90ff3ac451952d85dd.webp?size=64&keep_aspect_ratio=false",
    "Sunset RP" => "https://cdn.discordapp.com/app-assets/824643850865213441/832091733259976714.png",
    "Raft" => "https://cdn.discordapp.com/app-icons/449806905901056012/aa3b7a0ded0341cfbfd463d349e06add.webp?size=40&keep_aspect_ratio=false",
    "ELDEN RING" => "https://cdn.discordapp.com/app-icons/946609449680908348/0a0c0a0be069dddfc3f1fbede4e34bfd.webp?size=40&keep_aspect_ratio=false",
    "World of Warcraft" => "https://cdn.discordapp.com/app-icons/356875762940379136/fc92f820c44e72085dc6205e5e746850.webp?size=40&keep_aspect_ratio=false",
    "Apex Legends" => "https://cdn.discordapp.com/app-icons/542075586886107149/7564e6f23704870d70480f172f127677.webp?size=40&keep_aspect_ratio=false",
);

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
    array_push($data, json_decode($res));
}

$preview = $data[0];
$all_members = $data[1];
$widget = $data[2];

?>

<!DOCTYPE html>
<html lang="en" oncontextmenu="return false">
<head>
    <meta charset="UTF-8">
    <title><?php echo $preview->name ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="https://cdn.discordapp.com/icons/<?php echo $SERVER_ID ?>/<?php echo $preview->icon ?>.webp">

    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/moment-timezone-with-data-10-year-range.min.js"></script>
    <script src="js/main.js"></script>

</head>

<body onload='convert(<?php echo $SERVER_ID ?>)'>

<!-- POPOUT -->
<div id="popout"></div>

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

<div class="container">
    <!-- SEVER INFO -->
    <div class="row d-flex">
        <!-- SEVER INFO-->
        <div class="server">
            <!-- SERVER PROFILE PIC -->
            <img src="https://cdn.discordapp.com/icons/<?php echo $SERVER_ID ?>/<?php echo $preview->icon ?>.webp" alt="" class="logo">
            <!-- SERVER NAME -->
            <h1><?php echo $preview->name ?></h1>

            <!-- MEMBER COUNTER AND CREATION DATE -->
            <hr>
            <table class="table-resp">
                <?php $in_vc = [];
                foreach ($widget->members as $member) {
                    if (isset($member->channel_id)) {
                        array_push($in_vc, $member->channel_id);
                    }
                } ?>
                <tbody>
                <tr>
                    <td>All Members:</td>
                    <td><?php echo $preview->approximate_member_count ?></td>
                </tr>
                <tr>
                    <td>Online Members:</td>
                    <td><?php echo $preview->approximate_presence_count ?></td>
                </tr>
                <tr>
                    <td>Members In Vc:</td>
                    <td><?php echo count($in_vc) ?></td>
                </tr>
                <tr>
                    <td>Creation Date:</td>
                    <td id="server_create"></td>
                </tr>
                <tr>
                    <td>Server Age:</td>
                    <td id="server_age"></td>
                </tr>
                </tbody>
            </table>
            <hr>

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
                <img src="https://cdn.discordapp.com/emojis/<?php echo $emoji->id ?>.<?php echo $type ?>?size=48&quality=lossless" alt="<?php echo $emoji->name ?>" title="<?php echo $emoji->name ?>">
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
                        <img src="images/speaker.svg" alt="">
                        <div class="channel-title">
                            <span><?php echo $channel->name ?></span>
                        </div>
                    </div>
                    <div class="channel-members">
                        <?php foreach ($widget->members as $member) {
                            if (isset($member->channel_id) and ($member->channel_id == $channel->id)) {
                                foreach ($all_members as $my_member) {
                                    $user = $my_member->user;
                                    if ($my_member->nick == $member->username or $user->username == $member->username) {
                                        $id = $user->id;
                                        if (isset($user->bot)) {
                                            $bot = true;
                                        } else {
                                            $bot = false;
                                        }
                                    }
                                } ?>

                                <script type="text/javascript">
                                    $(function() {
                                        $('#SubmitForm<?php echo $id ?>InVc').submit(function( event ) {
                                            $.ajax({
                                                url: 'user.php',
                                                type: 'POST',
                                                dataType: 'html',
                                                data: $('#SubmitForm<?php echo $id ?>InVc').serialize(),
                                                success: function(content)
                                                {
                                                    $("#popout").html(content);
                                                }
                                            });
                                            event.preventDefault();
                                        });
                                    });
                                </script>

                                <form id="SubmitForm<?php echo $id ?>InVc" method="POST" class="card-small d-flex" style='background-image: url("<?php echo $member->avatar_url ?>");'>
                                    <button id="btn" type="submit" onclick="openBox('popout');" style="padding: 0;">
                                        <!-- STATUS -->
                                        <?php if ($member->status == "online") { ?>
                                            <div class="status" style="border-left:2px solid green;"></div>
                                        <?php } elseif ($member->status == "idle") { ?>
                                            <div class="status" style="border-left:2px solid yellow;"></div>
                                        <?php } elseif  ($member->status == "dnd") { ?>
                                            <div class="status" style="border-left:2px solid red;"></div>
                                        <?php } ?>
                                        <!-- NAME-->
                                        <div class="card-small-title d-flex">
                                            <div style="margin: auto 5px auto auto;">
                                                <span><?php echo $member->username ?></span>
                                            </div>
                                            <?php if ($bot == true) { ?>
                                                <img src="images/bot.png" alt="">
                                            <?php } ?>
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

                                        <!-- GAME ICONS-->
                                        <?php
                                        if (isset($member->game) and $bot == null) {
                                            foreach ($games as $key => $val) {
                                                if ($key == $member->game->name) {
                                                    $game_icon = $val?>
                                                    <?php break; ?>
                                                <?php } else {
                                                    $game_icon = "images/game-default.svg"; ?>
                                                <?php }
                                            }
                                        } else {
                                            $game_icon = null;
                                        } ?>
                                        <!-- GAME-->
                                        <?php if (isset($member->game)) {
                                            $game = $member->game->name;
                                        } else {
                                            $game = null;
                                        } ?>

                                        <!-- SEND TO USER.PHP -->
                                        <input type="hidden" name="id_<?php echo $id ?>_invc" value="<?php echo $id ?>">
                                        <input type="hidden" name="status_<?php echo $id ?>_invc" value="<?php echo $member->status ?>">
                                        <input type="hidden" name="game_<?php echo $id ?>_invc" value="<?php echo $game ?>">
                                        <input type="hidden" name="game_icon_<?php echo $id ?>_invc" value="<?php echo $game_icon ?>">

                                    </button>
                                </form>
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
        <?php foreach ($widget->members as $member) {
            foreach ($all_members as $my_member) {
                $user = $my_member->user;
                if ($my_member->nick == $member->username or $user->username == $member->username) {
                    $id = $user->id;
                    if (isset($user->bot)) {
                        $bot = true;
                    } else {
                        $bot = false;
                    }
                }
            }?>
            <script type="text/javascript">
                $(function() {
                    $('#SubmitForm<?php echo $id ?>').submit(function( event ) {
                        $.ajax({
                            url: 'user.php',
                            type: 'POST',
                            dataType: 'html',
                            data: $('#SubmitForm<?php echo $id ?>').serialize(),
                            success: function(content)
                            {
                                $("#popout").html(content);
                            }
                        });
                        event.preventDefault();
                    });
                });
            </script>

            <!-- MEMBER INFO-->
            <form id="SubmitForm<?php echo $id ?>" class="card d-flex" method="POST">
                <button id="btn" type="submit" onclick="openBox('popout')">
                    <!-- PROFILE PIC & STATUS-->
                    <div>
                        <!-- PROFILE PIC-->
                        <img class="img-profile" src="<?php echo $member->avatar_url ?>" alt="">
                        <!-- STATUS-->
                        <?php
                        $status = ["online", "idle", "dnd"];
                        foreach ($status as $x) {
                            if ($member->status == $x) {
                                $user_status = $member->status; ?>
                                <img class="img-status" src="images/<?php echo $user_status ?>.png" alt="">
                                <?php break;
                            }
                        }
                        ?>
                    </div>
                    <!-- NAME & GAME-->
                    <div class="card-center d-flex">
                        <div style="margin: auto 5px auto auto;">
                            <!-- NAME-->
                            <span><?php echo $member->username ?></span>
                            <!-- GAME-->
                            <?php if (isset($member->game)) {
                                $game = $member->game->name; ?>
                                <br><span><b>Playing:</b> <?php echo $game ?></span>
                            <?php } else {
                                $game = null;
                            }?>
                        </div>
                        <?php if ($bot == true) { ?>
                            <img src="images/bot.png" alt="" style="width: 32px">
                        <?php } ?>
                    </div>
                    <!-- GAME ICONS-->
                    <?php
                    if (isset($member->game)) {
                        foreach ($games as $key => $val) {
                            if ($key == $member->game->name) {
                                $game_icon = $val?>
                                <?php break; ?>
                            <?php } else {
                                $game_icon = "images/game-default.svg"; ?>
                            <?php }
                        } ?>
                        <div class="card-right">
                            <img src="<?php echo $game_icon ?>" alt="">
                        </div>
                    <?php } else {
                        $game_icon = null;
                    } ?>

                    <!-- SAVE ALL ONLINE MEMBERS NAME IN $online_members-->
                    <?php array_push($online_members, $member->username); ?>

                    <!-- SEND TO USER.PHP -->
                    <input type="hidden" name="id_<?php echo $id ?>" value="<?php echo $id ?>">
                    <input type="hidden" name="status_<?php echo $id ?>" value="<?php echo $user_status ?>">
                    <input type="hidden" name="game_<?php echo $id ?>" value="<?php echo $game ?>">
                    <input type="hidden" name="game_icon_<?php echo $id ?>" value="<?php echo $game_icon ?>">

                </button>
            </form>
        <?php } ?>
    </div>

    <!-- OFFLINE MEMBERS -->
    <div class="row d-block">
        <span>OFFLINE MEMBERS (<?php echo (intval($preview->approximate_member_count)-(count($online_members))) ?>)</span>
        <hr>
    </div>
    <div class="row d-flex">
        <?php foreach ($all_members as $member) {
            $id = $member->user->id;
            if ($member->nick == null) {
                $name = $member->user->username;
            } else {
                $name = $member->nick;
            }
            if (isset($member->user->bot)) {
                $bot = true;
            } else {
                $bot = false;
            }
            if (!in_array($name, $online_members)) { ?>
                <script type="text/javascript">
                    $(function() {
                        $('#SubmitForm<?php echo $id ?>').submit(function( event ) {
                            $.ajax({
                                url: 'user.php',
                                type: 'POST',
                                dataType: 'html',
                                data: $('#SubmitForm<?php echo $id ?>').serialize(),
                                success: function(content)
                                {
                                    $("#popout").html(content);
                                }
                            });
                            event.preventDefault();
                        });
                    });
                </script>
                <!-- MEMBER INFO-->
                <form id="SubmitForm<?php echo $id ?>" class="card d-flex" action="user.php" method="POST">
                    <button id="btn" type="submit" onclick="openBox('popout')">
                        <!-- PROFILE PIC -->
                        <div>
                            <?php if ($member->user->avatar != null) { ?>
                                <img class="img-profile" src="https://cdn.discordapp.com/avatars/<?php echo $id ?>/<?php echo $member->user->avatar ?>.webp?size=80" alt="">
                            <?php } else { ?>
                                <img class="img-profile" src="images/default.webp" alt="">
                            <?php } ?>
                        </div>
                        <!-- NAME -->
                        <div class="card-center d-flex">
                            <div style="margin: auto 5px auto auto;">
                                <span><?php echo $name ?></span>
                            </div>
                            <?php if ($bot == true) { ?>
                                <img src="images/bot.png" alt="" style="width: 32px">
                            <?php } ?>
                        </div>

                        <!-- SEND USER ID TO USER.PHP PAGE -->
                        <input type="hidden" name="id_<?php echo $id ?>" value="<?php echo $id ?>">

                    </button>
                </form>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- FOOTER -->
    <div class="footer d-grid">
        <a href="https://github.com/MeysamRezazadeh/Discord-Widget">
            Download source code
        </a>
        <span>Create by <a href="https://github.com/MeysamRezazadeh" target="_blank">Sambyte</a></span>
    </div>

</div>
</body>
</html>

<!-- https://github.com/MeysamRezazadeh  -->

