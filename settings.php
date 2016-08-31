<html>
<head>
</head>
<body>

  <?php
    $colors["#d9534f"]="danger";
    $colors["#5bc0de"]="info";
    $colors["#5cb85c"]="success";
    $colors["#FFA500"]="warning";

    if ($_GET['id']!="none"&&(!isset($_GET['text'])))//delete plan
    {
      global $wpdb;
      $table_name = $wpdb->prefix . "notifications";
      $wpdb->query( "DELETE FROM `" . $table_name . "` WHERE id = '$_GET[id]';");

      unset($_GET); 
    }

    if (isset($_GET['text'])&&($_GET['id']!="none"))//edit plan
    {
      //file_put_contents(NOTIFICATION_PLUGIN_DIR."/test.txt",print_r((strpos('warning', '#')), true), FILE_APPEND);
      $text        =     $_GET['text'];
      $slug_name   =     $_GET['slug_name'];
      $color       =     $_GET['color'];
      $position_pop=     $_GET['position_pop'];
      if ($position_pop == "")
      {
        $position_pop = "top-right";
      }

      if (strpos($color, '#')== 'true')
      {
        $new_color = $colors[$color];
      }
        else
        {
          $new_color = $color;
        }

      global $wpdb;
      $table_name = $wpdb->prefix . "notifications";
      $wpdb->query( "UPDATE `" . $table_name . "` SET text = '$text', slug = '$slug_name', color = '$new_color', position = '$position_pop' WHERE id = '$_GET[id]'");
      unset($_GET); 
      die(json_encode(array("success"=>true)));
    }

    if ($_GET['id']=="none")//add new plan
    {
      //file_put_contents(NOTIFICATION_PLUGIN_DIR."/test.txt",print_r($_GET, true), FILE_APPEND);
      $text        =     $_GET['text'];
      $slug_name   =     $_GET['slug_name'];
      $color       =     $_GET['color'];
      $position_pop=     $_GET['position_pop'];
      if ($position_pop == "")
      {
        $position_pop = "top-right";
      }

      $new_color = $colors[$color];
      if ($color == "")
      {
        $new_color = "info";
      }

      global $wpdb;
      $table_name = $wpdb->prefix . "notifications";
      $wpdb->query( "INSERT INTO `" . $table_name . "` (text, slug, color, position) VALUES ('$text', '$slug_name', '$new_color', '$position_pop')");
      unset($_GET); 
      die(json_encode(array("success"=>true)));
    }

    ?>
    <div class="row">
      <div >
        <table>
          <tr>
            <th>
              <h2>Notifications</h2>
            </th>
            <th style="padding: 10px">
              <button type="button" class="btn btn-default " id="myBtn" ><span class="glyphicon glyphicon-plus"></span> </button>
            </th>
          </tr>
        </table>

        <div class="table-responsive">
          <table style = "width: 98%" class="table table-striped table-hover notification_table">
            <thead>
              <tr>
                <th>

                </th>
                <th>
                  Text
                </th>
                <th>
                  slug-name
                </th>
                <th>
                  Color
                </th>
                <th>
                  Position
                </th>
                <th style="border-style:hidden">
                </th>
                <th style="border-style:hidden">
                </th>
                <th style="display: none">
                </th>
              </tr>
            </thead>
            <tbody id = 'checkCont'> 
              <?php
              global $wpdb;
              $table_name = $wpdb->prefix . "notifications";
              $notifications = $wpdb->get_results( "SELECT * FROM  `" . $table_name . "`" );
              foreach ($notifications as $notification) 
              {
                echo "<tr class=$notification->color>";
                echo "
                <td>
                  <a class=\"btn\" href=\"admin.php?page=WP_Notification_plugin%2Fsettings.php&id=$notification->id\"><i class=\"glyphicon glyphicon-remove-circle\" style=\"color:maroon\"></i></a>
                </td>
                ";
                echo "
                <td id='notif_text'>                           $notification->text</td>
                <td id='notif_slug'>                           $notification->slug</td>
                <td id='notif_color'>                          $notification->color</td>
                <td id='notif_position'>                       $notification->position</td>
                ";
                echo "
                <td>
                  <a id = \"copy_slag\"  data-clipboard-text=\"test\"><i class=\"glyphicon glyphicon-copy\"></i></a>
                </td>
                <td>
                  <a id = \"edit\" class=\" href=\"admin.php?page=WP_Notification_plugin%2Fsettings.php&id=$notification->id&move=edit\"><i class=\"glyphicon glyphicon-pencil\"></i></a>
                </td>
                <td style=\"display: none\" id = 'notif_id' myurl = ";echo plugins_url('WP_Notification_plugin/positions.js');echo">
                  $notification->id
                </td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
      <html>

        <script>

  global_popover_val = "";
  icon_global_popover_val = "";
  angle_global_popover_val  = "";

  $(document).ready(function(){
    $("#myBtn").click(function(){
      $("#myModal").modal();
      $("#text").val("");
      $("#slug_name").val("");
      $("#color").val("");
      $('#popover').popover('hide');
      $('#popover span').attr('class', "glyphicon glyphicon-fullscreen");
      $('#popover span').css('transform', "rotate(0deg)");
      $('#myModal .modal-header h4').text('Add notification');
    });


    $('#checkCont').on('click', '#edit', function (event) {  
      $("#myModal").modal();
      var row = $(this).closest('tr');
      $("#text").val($.trim(row.find('#notif_text').text()));
      $("#slug_name").val($.trim(row.find('#notif_slug').text()));
      $("#color").val($.trim(row.find('#notif_color').text()));
      $("#id").val($.trim(row.find('#notif_id').text()));

      var full_position = $.trim(row.find('#notif_position').text());

      $('#popover').on('inserted.bs.popover', function () {
        $('#myModal .popover .popover-content .active').removeClass('active');
        var selector = "#myModal .popover .popover-content input[value = '" + full_position + "']";
        $(selector).parent().addClass("active");
      });

      $('#popover span').attr('class', full_positions[full_position]["class"]);
      $('#popover span').css('transform', full_positions[full_position]["transform"]);

        $('#myModal .modal-header h4').text('Edit notification');//текст для всего остального
      });

    $('#checkCont').on('click', '#copy_slag', function (event) {  
      var row =         $(this).closest('tr');
      var slag =        $.trim(row.find('#notif_slug').text());
      var full_position =$.trim(row.find('#notif_position').text());
      var fullSlag = '[pd-notif slag="' + slag + '"]';
      row.find('#copy_slag').attr('data-clipboard-text', fullSlag);
      new Clipboard('#copy_slag');

      $.notify({
        icon: 'glyphicon glyphicon-warning-sign',
        message: $.trim(row.find('#notif_text').text()),
      },{
        type: $.trim(row.find('#notif_color').text()),
        offset: {
          x: 50,
          y: 50
        },
        placement: {
          from: full_positions[full_position]["position_from"],
          align: full_positions[full_position]["position_align"]
        },
        z_index: 100000,
      });
    });

    $("#myModal form").submit(function(event){
        event.preventDefault();//disable the default behavior
        $.ajax({
          url: "admin.php?page=WP_Notification_plugin%2Fsettings.php",
          method: "GET",
          data: { text: $("#text").val(), slug_name: $("#slug_name").val(), color: $("#color").val(), id: $("#id").val(), position_pop: global_popover_val},
          success:function(response)
          {
            if (response.success)
            {
              console.log("data Saved");
              window.location.reload();
              $('#myModal').modal('hide');
              $("#text").val("");
              $("#slug_name").val("");
              $("#color").val("");
            }
            else
            {
              console.error("Validation Error");
            }
          },
          error:function()
          {
            window.location.reload();
            console.error("Server Error");
          },
          dataType: "json"
        });
      });

    $("#popover").popover({
      html : true,
      content: function() {
        return $('#popoverContent').html();
      }
    });

    $('#popover').on('inserted.bs.popover', function () {
     $('#myModal .popover .popover-content').change(function(){
      global_popover_val = $('#myModal .popover .popover-content').find('input:checked').val();
      icon_global_popover_val = $('#myModal .popover .popover-content input:checked').data('icon');
      angle_global_popover_val = $('#myModal .popover .popover-content input:checked').data('angle');
      $('#popover span').attr('class', icon_global_popover_val);
      $('#popover span').css('transform', angle_global_popover_val);
      $('#popover').popover('hide');
    });
   })
  });
</script>


<!--popoverContent-->
<div id = containerPop>
  <div id="popoverContent" style="display: none">
   <form>

    <div  data-toggle="buttons">
      <label id ="wer" style = "transform: rotate(-45deg); border: none; background: none" class="[ btn btn-orange ]">
        <input data-angle = "rotate(-45deg)" data-icon = "glyphicon glyphicon-arrow-up" type="radio" name="position" autocomplete="off" value="top-left"><i class="glyphicon glyphicon-arrow-up"></i>
      </label>
      <label  style = " border: none; background: none" class="[ btn ][ btn-orange ]">
        <input data-angle = "rotate(0deg)" data-icon = "glyphicon glyphicon-arrow-up" type="radio" name="position" autocomplete="off" value="top-center"><i class="glyphicon glyphicon-arrow-up"></i>
      </label>
      <label style = "transform: rotate(45deg); border: none; background: none" class="[ btn ][ btn-orange ] active">
        <input data-angle = "rotate(45deg)" data-icon = "glyphicon glyphicon-arrow-up" type="radio" name="position" autocomplete="off" value="top-right" ><i class="glyphicon glyphicon-arrow-up"></i>
      </label>
      <br>
      <label style = "transform: rotate(-315deg); border: none; background: none" class="[ btn ][ btn-orange ]">
        <input data-angle = "rotate(-315deg)" data-icon = "glyphicon glyphicon-arrow-down" type="radio" name="position" autocomplete="off" value="bottom-left"><i class="glyphicon glyphicon-arrow-down"></i>
      </label>
      <label style = " border: none; background: none"class="[ btn ][ btn-orange ]">
        <input data-angle = "rotate(0deg)" data-icon = "glyphicon glyphicon-arrow-down" type="radio" name="position" autocomplete="off" value="bottom-center"><i class="glyphicon glyphicon-arrow-down"></i>
      </label>
      <label style = "transform: rotate(315deg); border: none; background: none" class="[ btn ][ btn-orange ]">
        <input data-angle = "rotate(315deg)" data-icon = "glyphicon glyphicon-arrow-down" type="radio" name="position" autocomplete="off" value="bottom-right"><i class="glyphicon glyphicon-arrow-down"></i>
      </label>
    </div>

  </form>
</div>
</div>
</div>

<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  .modal-header, h4, .close {
    background-color: #5cb85c;
    color:white !important;
    text-align: center;
    font-size: 30px;
  }
  .modal-footer {
    background-color: #f9f9f9;
  }
</style>

<div class="container">
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Add new notification</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form">
            <input required class="form-control" id = "text" placeholder="Text"><br>
            <input required class="form-control" id = "slug_name" placeholder="Slug-name"><br>
            <input input type="hidden" class="form-control" id = "id" value = "none">
            <input class="form-control" input type="text" name="duplicated-name-2" data-palette='["#d9534f","#5bc0de","#5cb85c","#FFA500"]' id = "color" value="" style="margin-right:48px;">
            <br>
            <button id="popover" type="button" class="btn btn-default" data-placement="bottom"><span style = "transform: rotate(0deg)" class=" glyphicon glyphicon-fullscreen"></span></button>
            <br><br>
            <button type="submit" class="btn btn-success btn-block">Save</button>
            <div class="container">
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>