<?php //Lets register our shortcode
function sc_radio_player_cb($atts){
	extract( shortcode_atts( array(

		'id' => null,
		'url' => null,
		'background' => null,

	), $atts ) );
  $post_type = get_post_type($id);
  if($post_type != 'streamcast'){
    return false;
  }
?>
<?php ob_start(); ?>

<?php 
$player_type=get_post_meta( $id, 'opt-radio', true );
$stream_url=get_post_meta( $id, 'stream_url', true );
$player_postiion="";
?>
<div  class="sc_radio">
<div style="display:<?php if(empty($stream_url)){echo 'block;';}else{echo 'none;';}?>">
	<h2>Ooops ! You forgot to enter a stream url ! Please check Radio Player configuration </h2>
</div>

<?php



switch ($player_type) {
  case 'advanced' : ?>


<div style="width:100%; overflow:hidden;">
<div class="streamcast<?php echo $id; ?>">
  <ul class="playlist">

    <li data-cover="<?php echo  STP_PLUGIN_DIR."images/default.png";  ?>" data-artist="Station Name">
      <a href="<?php echo esc_url($stream_url);?>"> Welcome Message</a>
    </li>
  </ul>
</div>
</div>
<style type="text/css">
.streamcast<?php echo esc_html($id); ?>{
  margin:20px auto 0;
  width: 390px;
  padding-bottom: 30px;	
	
}
.player{background: <?php echo get_post_meta( $id, 'background', true ); ?>!important;}

</style>
<script type="text/javascript">

jQuery(".streamcast<?php echo esc_html($id); ?>").musicPlayer({
  autoPlay: "false",
  volume: 65, 
  loop: false, 
  timeSeparator: ' / ',
  playerAbovePlaylist: true,  
  infoElements: ['title' , 'artist'] , 
  elements: ['artwork','information', 'controls', 'time', 'volume'],  
  timeElements: ['current'],  
  controlElements: [ 'play', 'stop'], 
});

</script>






	<?php
    break;
  case 'standard': ?>

<!-- BEGINS:  RADIO PLAYER CODE -->
<script type="text/javascript">
MRP.insert({
'url':"<?php echo esc_url($stream_url);  ?>",
'lang':'en',
'codec':'mp3',
'volume':65,
'autoplay':'false',
'forceHTML5':true,
'jsevents':true,
'buffering':0,
'title':'Station Name',
'welcome':'Welcome Message',
'wmode':'transparent',
'skin':'mcclean',
'width':400,
'height':100
});
jQuery("#musesContextMenuAboutDiv, #musesContextMenuTitleDiv, #musesContextMenuVersionDiv").css("display", "none");
</script>
<!-- ENDS: RADIO PLAYER CODE -->


	<?php
    break;
  case 'plyr': ?>

<!-- BEGINS:  RADIO PLAYER CODE -->
<div style=" <?php if($player_postiion=="right"){echo "width:100%; overflow:hidden;"; } ?>">
<div style="width:200px; <?php if($player_postiion==""){echo "margin:0 auto;";} if($player_postiion=="right"){echo "float:right;";} ?>">
<audio class="player" crossorigin playsinline controls>
  
  <source src="<?php echo esc_url($stream_url);  ?>"  type="audio/mp3"  >
  
Your browser does not support the audio element.
</audio>
</div>
</div>
<style type="text/css">
.plyr__control{margin-right:0 !important;}

</style>
<script type="text/javascript">
const players<?php echo esc_html($id);?> = Plyr.setup('.player', {
	controls:['play', 'current-time', 'mute', 'volume'],
	displayDuration: true,
});
</script>
<!-- ENDS: RADIO PLAYER CODE -->
	<?php
    break;
  default:
    echo "<h2> You must choose a radio player type ! </h2>";
}
?>
</div>
<style type="text/css">


</style>
<?php $output=ob_get_clean(); return $output; ?>

<?php
}
add_shortcode('radio_player','sc_radio_player_cb');