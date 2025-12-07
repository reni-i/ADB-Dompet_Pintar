function cariData() {
    var keyword = document.getElementById("keyword").value;
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var tbody = document.getElementById("tabel-dompet").getElementsByTagName("tbody")[0];
            tbody.innerHTML = xhr.responseText;
        }
    };

    xhr.open("GET", "cari.php?q=" + keyword, true);
    xhr.send();
}