<?php echo $header; ?>
<div class="container">
  <header>
      <div class="row">
        <div class="col-sm-6">
          <h1 class="pull-left">4<small>/4</small></h1>
          <h3><?php echo $heading_step_4; ?><br><small><?php echo $heading_step_4_small; ?></small></h3>
        </div>
        <div class="col-sm-6">
          <div id="logo" class="pull-right hidden-xs">
            <img src="_view/image/logo.png" alt="RNFengine" title="RNFengine" />
          </div>
        </div>
      </div>
  </header>
  <?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="alert alert-danger"><?php echo $text_forget; ?></div>
  <div class="visit">
    <div class="row">
      <div class="col-sm-5 col-sm-offset-1 text-center">
        <img src="_view/image/icon-store.png">
        <a class="btn btn-secondary" href="../"><?php echo $text_shop; ?></a>
      </div>
      <div class="col-sm-5 text-center">
        <img src="_view/image/icon-admin.png">
        <a class="btn btn-secondary" href="<?php echo$url_admin ?>"><?php echo $text_login; ?></a>
      </div>
    </div>
  </div>
  <div class="language" id="module-language" style="display:none;"></div>
  <div class="modules">
    <div class="row" id="modules-loading">
      <div class="col-sm-12">
        <h2 class="text-center"><i class="fa fa-circle-o-notch fa-spin"></i> <?php echo $text_loading; ?></h2>
      </div>
    </div>
    <div class="row" id="modules" style="display:none;"></div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <a class="btn btn-default" href="#" target="_BLANK"><?php echo $text_store; ?></a>
      </div>
    </div>
  </div>
  <div class="mailing-list">
    <div class="row">
      <div class="col-sm-12">
        <img src="_view/image/icon-mail.png">
        <h3><?php echo $text_mail_list; ?><br><small><?php echo $text_mail_list_small; ?></small></h3>
        <a class="btn btn-secondary" href="#" target="_BLANK"><?php echo $button_join; ?></a>
      </div>
    </div>
  </div>
  <div class="support text-center">
    <div class="row">
      <div class="col-sm-4">
        <a href="#" class="icon transition">
          <i class="fa fa-facebook fa-4x"></i>
        </a>
        <h3><?php echo $text_facebook; ?></h3>
        <p><?php echo $text_facebook_info; ?></p>
        <a href=""><?php echo $text_facebook_link; ?></a>
      </div>
      <div class="col-sm-4">
        <a href="#" class="icon transition">
          <i class="fa fa-comments fa-4x"></i>
        </a>
        <h3><?php echo $text_forum; ?></h3>
        <p><?php echo $text_forum_info; ?></p>
        <a href="#"><?php echo $text_forum_link; ?></a>
      </div>
      <div class="col-sm-4">
        <a href="#" class="icon transition">
          <i class="fa fa-user fa-4x"></i>
        </a>
        <h3><?php echo $text_commercial; ?></h3>
        <p><?php echo $text_commercial_info; ?></p>
        <a href="#" target="_BLANK"><?php echo $text_commercial_link; ?></a>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
function searchExtensions() {
  var html = '';

  $.ajax({
    url: 'index.php?route=step_4/extensions',
    type: 'post',
    dataType: 'json',
    beforeSend: function() {
      $('#modules-loading').show();
      $('#modules').empty().hide();
    },
    success: function(json) {
      $.each (json.extensions, function(key, val) {
        html = '<div class="col-sm-6 module">';
          html += '<a class="thumbnail pull-left" href="'+val.href+'"><img src="'+val.image+'" alt="'+val.name+'"></a>';
          html += '<h5>'+val.name+'</h5>';
          html += '<p>'+val.price+' <a target="_BLANK" href="'+val.href+'"><?php echo $text_view; ?></a></p>';
          html += '<div class="clearfix"></div>';
        html += '</div>';

        $('#modules').append(html);
      });

      $('#modules').fadeIn();
      $('#modules-loading').hide();
    },
    failure: function() {
      $('#modules-loading').hide();
    },
    error: function() {
      $('#modules-loading').hide();
    }
  });
}
function searchLanguages() {
  var html = '';

  $.ajax({
    url: 'index.php?route=step_4/language',
    type: 'post',
    data: {'language' : '<?php echo $language; ?>' },
    dataType: 'json',
    beforeSend: function() {
      $('#module-language').empty().hide();
    },
    success: function(json) {
      if (json.extension != '') {
        html = '<div class="row">';
          html += '<div class="col-sm-12">';
            html += '<img class="img-rounded" src="'+json.extension.image+'">';
            html += '<h3>'+json.extension.name+'<br><small><?php echo $text_downloads; ?>: '+json.extension.downloaded+', <?php echo $text_price; ?>: '+json.extension.price+'</small></h3>';
            html += '<a class="btn btn-primary" href="'+json.extension.href+'" target="_BLANK"><?php echo $text_download; ?></a>';
          html += '</div>';
        html += '</div>';

        $('#module-language').html(html).fadeIn();
      }
    },
    failure: function() { },
    error: function() { }
  });
}
$( document ).ready(function() {
  searchExtensions();
  searchLanguages();
});
//--></script>