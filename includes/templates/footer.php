<script src='<?php echo $js ?>jquery.min.js'></script>
<script src='<?php echo $js ?>popper.min.js'></script>
<script src='<?php echo $js ?>bootstrap.min.js'></script>
<script src='<?php echo $js ?>front.js'></script>

<script>
updateTime();
function updateTime(){
  $('#time').html(new Date().toLocaleString('en-GB',{ hour12: true }).toUpperCase());
}
$(function(){
  setInterval(updateTime, 1000);
});
</script>

</body>
</html>