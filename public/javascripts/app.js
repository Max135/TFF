function addEventListenerToButton(hotspotId)
{
    document.getElementById('switchBtn' + hotspotId).addEventListener("click", function () {
        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                var answer = JSON.parse(req.response);
                var switchOn = document.getElementById('switchIcon' + hotspotId).classList.contains("fa-toggle-on");
                if (switchOn) {
                    document.getElementById('switchIcon' + hotspotId).classList.remove("fa-toggle-on");
                    document.getElementById('switchIcon' + hotspotId).classList.add("fa-toggle-off");
                } else {
                    document.getElementById('switchIcon' + hotspotId).classList.remove("fa-toggle-off");
                    document.getElementById('switchIcon' + hotspotId).classList.add("fa-toggle-on");
                }
            }
        }
        var url = location.protocol + "//" + location.hostname + "/api/changePerm?hotspotId=" + hotspotId;
        req.open("GET", url, true);
        req.send();
    })
}