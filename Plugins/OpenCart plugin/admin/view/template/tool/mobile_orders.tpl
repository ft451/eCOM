<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($warning) { ?>
  <div class="warning"><?php echo $warning; ?></div>
  <?php } ?>
  <div id="messageContainer">
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/mobile.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="javascript://" onClick="onEditClicked();" class="button"><?php echo $text_modify_pin; ?></a></div>
	  <?php if(isset($pinCode)) echo '<div class="buttons"><a href="javascript://" class="button" onClick="confirmDeletion();">'.$text_remove_pin.'</a></div>';?>
	  
    </div>
    <div class="content">
		<center>
		<div id = "mContent">
			<table width="600" cellpadding="0" cellspacing="0" style="border:1px solid; border-radius:2px;">
			<tr bgcolor="FFFFCC" height="40" id="changepintr">
				<td width="100">
					<font size="+1"><?php echo $text_pin;?></font>
				</td>
				<?php if(isset($pinCode))
				{?>
				<td>
					<font size="+1"><?PHP echo $pinCode; ?></font>
				</td>
				<td align = "right" id="changepin"  width="40">
					<small><a href="javascript://" id="changepinurl" onClick="onEditClicked();"><img src="view/image/editpin.png" alt="Change Pin Code" title="Change Pin Code"/></a></small>
				</td>
				<?php } else { ?>
				<td>
					<i><?php echo $text_pin_not_set;?></i>
				</td>
				<td align = "right" width="40" id="changepin">
					<small><a href="javascript://" id="changepinurl" onClick="onEditClicked();"><img src="view/image/editpin.png" alt="Change Pin Code" title="Change Pin Code"/></a></small>
				</td>
				<?php } ?>
				
			</tr>
			</table>
		</div>	
		</center>
    </div>
	
	
  </div>
</div>
<script> <!--todo: add type:-->
	var changeToogle;
	var changed = false;
	var toogleStatus = false;
	
	function checkForm()
	{
		var newPin;
		var pattern = /[\w]{5}$/g;
		newPin = $("#newPinCodeID").val();
		if(newPin.length != 5 || !pattern.test(newPin))
		{
			$("#messageContainer").html('<div class="warning"><?php echo $text_invalid_pin;?></div>');
			$("#newPinCodeID").focus().select();
			return false;
		}
		else if(newPin == "<?php echo (isset($pinCode) ? $pinCode : ""); ?>")
		{
			$("#messageContainer").html('<div class="warning"><?php echo $text_pins_equal;?></div>');
			$("#newPinCodeID").focus().select();
			return false;
		}
		return true;
	}
		
	function onEditClicked()
	{
		if(!changed)
		{
			changeToogle = $("#mContent").html();
			changed=true;
		}
		
		if(!toogleStatus)
		{
		$("#mContent").html('	\
			<form method="post" action="<?php echo $modify;?>" onSubmit="return checkForm();">	\
			<table width="600" cellpadding="0" cellspacing="0" style="border:1px solid; border-radius:2px;">	\
			<tr bgcolor="FFFFCC" height="40">	\
				<td width="100">	\
					<font size="+1"><?php echo $text_pin;?></font>	\
				</td>	\
				<td>	\
					<input type="text" name="newPinCode" id="newPinCodeID" value = "<?php if(isset($pinCode)) echo($pinCode); ?>" />	\
					\
				</td>	\
				<td align = "right" id="changepin"  width="40">	\
					<table><tr><td><input type="image" src="view/image/submitpin.png" alt="Submit new pin code." title="Submit new pin code.">	</td><td><a href="javascript://" id="changepinurl" onClick="onEditClicked();"><img src="view/image/cancelpin.png" alt="Cancel a pin changing." title="Cancel a pin changing."/></a></td></tr></table>	\
				</td>		\
			</tr>	\
			</table>	\
			</form>	\
			\
			\
			');
			$("#newPinCodeID").focus().select();
		}
		else
			$("#mContent").html(changeToogle);
		toogleStatus = !toogleStatus;
	}

	function confirmDeletion() 
	{
		var answer = confirm("<?php echo $text_remove_pin_confirm;?>");
		if (answer)
			window.location = unescape('<?php echo $remove; ?>').replace("&amp;", "&");
	}
</script>
<?php echo $footer; ?>