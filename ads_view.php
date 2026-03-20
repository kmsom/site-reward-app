<?php $mid = $_GET['m']; ?>
<div id="timer">Assista por 20 segundos...</div>
<div id="ad-container"> </div>
<script>
    let tempo = 20;
    setInterval(() => {
        if(tempo > 0) { tempo--; document.getElementById('timer').innerText = "Aguarde "+tempo+"s"; }
        else { document.getElementById('timer').innerHTML = '<a href="postback.php?m=<?php echo $mid; ?>">COLETAR RECOMPENSA</a>'; }
    }, 1000);
</script>
