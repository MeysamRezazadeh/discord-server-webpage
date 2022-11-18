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

function closeBox(elementID)
{
    document.getElementById(elementID).innerHTML = "";
    document.getElementById(elementID).style.display = "none";
}
function openBox(elementID) {
    document.getElementById(elementID).style.display = "block";
}


function convertIDtoUnix(id) {
    var bin = (+id).toString(2);
    var unixbin = '';
    var unix = '';
    var m = 64 - bin.length;
    unixbin = bin.substring(0, 42-m);
    unix = parseInt(unixbin, 2) + 1420070400000;
    return unix;
}


function convert(id) {
    var unix = convertIDtoUnix(id.toString());
    var timestamp = moment.unix(unix/1000);
    document.getElementById('server_create').innerHTML = timestamp.format('YYYY-MM-DD, HH:mm:ss');
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
    document.getElementById('server_age').innerHTML = dDisplay + hDisplay + mDisplay + sDisplay;
}