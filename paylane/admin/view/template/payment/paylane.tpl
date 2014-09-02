<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_merchant_id; ?></td>
            <td><input type="text" name="paylane_merchant_id" value="<?php echo $paylane_merchant_id; ?>" />
              <?php if ($error_merchant_id) { ?>
              <span class="error"><?php echo $error_merchant_id; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
              <td><?php echo $entry_redirect_type; ?></td>
              <td><?php if ($paylane_redirect_type == 'GET') { ?>
              <input type="radio" name="paylane_redirect_type" value="POST" /> POST
              <input type="radio" name="paylane_redirect_type" value="GET" checked="checked" /> GET
              <?php } else { ?>
              <input type="radio" name="paylane_redirect_type" value="POST" checked="checked" /> POST
              <input type="radio" name="paylane_redirect_type" value="GET" /> GET
              <?php } ?></td>
          </tr>
          <tr>
              <td><?php echo $entry_hash; ?></td>
              <td><input type="text" name="paylane_hash" value="<?php echo $paylane_hash; ?>" />
          </tr>
          <tr>
              <td><?php echo $entry_interface_lang; ?></td>
              <td><?php if ($paylane_interface_lang == 'PL') { ?>
              <input type="radio" name="paylane_interface_lang" value="en" /> EN
              <input type="radio" name="paylane_interface_lang" value="pl" checked="checked" /> PL
              <?php } else { ?>
              <input type="radio" name="paylane_interface_lang" value="en" checked="checked" /> EN
              <input type="radio" name="paylane_interface_lang" value="PL" /> PL
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="paylane_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $paylane_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="paylane_status">
                <?php if ($paylane_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="paylane_sort_order" value="<?php echo $paylane_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 