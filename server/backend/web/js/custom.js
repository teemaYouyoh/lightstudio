function preloader() {
    $("body").append("<div class='preloader'><div class='loader'></div></div>");
    $(".preloader").css({"height":$(window).height()});
    $(".loader").css({"top":($(window).height() / 2 - 60), "left":($(window).width() / 2 - 60)});
}
function preloaderClose() {
    $("body").find(".preloader").remove();
}
 $(window).on('load', function () {
    $preloader = $('.preloader'),
      $loader = $preloader.find('.loader');
    $loader.fadeOut();

  });

$(document).ready(function () {

    $("body").on("click", ".tabs .tab-item", function () {
        $(".tab-item").removeClass("active")
        $(this).addClass("active");
        $(".tab-body").removeClass("active");
        $("."+$(this).data("item")).addClass("active");
    });
    $("body").on("click", ".lang-tab", function () {
        $(".lang-tab").removeClass("active")
        $(this).addClass("active");
        $(".descrtext .lang-tab-body").removeClass("active");
        $(".lang-"+$(this).data("lang")).addClass("active");
    });

    if($('#menu_result').length > 0){
        renderMenutree();
    }
    $('#button-menu').on('click',function(e){e.preventDefault();$('#column-left').toggleClass('active');});

    $('.add-menu-item').on('click', function (e) {
        e.preventDefault();
        $('#menu_items_editor').load($(this).attr('href'));

    });
    $('body').on('click', '#left ul.nav li span.edit a', function (e) {
        e.preventDefault();
        $('#menu_items_editor').load($(this).attr('href'));
    });

    $('body').on('click', '#left ul.nav li span.delete a', function (e) {
        e.preventDefault();
        $('#menu_items_editor').load($(this).attr('href'));
        renderMenutree();
    });
    $('#createnews').on('click', 'button[type="submit"]', function (e) {
        var check = true;
        $('#createnews .required').each(function (e) {
            if($(this).find().val() == ''){

                check = false;
            }
        });
        if(check == false){
            e.preventDefault();
            alert("Title required!");
        }
    });
    $(".helpwindow").on({mouseenter : function() {
        $(this).append("<div class='hellp'>"+$(this).data('helpwindow')+"</div>");
    }, mouseleave : function() {
        $(this).find(".hellp").remove();
    }
    });
    $('#createpage').on('click', 'button[type="submit"]', function (e) {
        var check = true;
        $('#createpage .required').each(function (e) {
            if($(this).find().val() == ''){

                check = false;
            }
        });
        if(check == false){
            e.preventDefault();
            alert("Title required!");
        }
    });

    $('#menu_items_editor').on('click', 'form button', function (e) {
        e.preventDefault();
        var url = $(this).parents('form').attr('action');
        var data = $(this).parents('form').serialize();
        var check = true;
        $('.required_item input').each(function () {
            if($(this).val() == ''){
                $(this).next('div').css({'color':'red'}).text("Required Field");
                check = false;
            } else {
                $(this).next('div').css({'color':'red'}).text("");
            }
        });
        if(check == false){
            alert('some error');
            return false;
        }
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',

            success: function(json) {
                if(!!json){
                    $('#menu_items_editor > *').remove();
                    renderMenutree();
                } else {
                    alert('some error');
                }
            }
        })
    });
    $(document).on("click","#left ul.nav li.parent > a > span.sign", function(){
        $(this).find('i:first').toggleClass("icon-minus");
    });

    $("#left ul.nav li.parent.active > a > span.sign").find('i:first').addClass("icon-minus");
    $("#left ul.nav li.current").parents('ul.children').addClass("in");
});

function renderMenutree() {
    var id = $('#menu_result').attr('data-menu-id');
    $('#menu_result').load('/admin/menu-site/menu-tree?menu_id='+id);
}


demo = {
    deleteItem: function(itemName){
        swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it',
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            buttonsStyling: false
        }).then(function() {
            swal({
                title: 'Deleted!',
                text: itemName + 'deleted.',
                type: 'success',
                confirmButtonClass: "btn btn-success",
                buttonsStyling: false
            }).catch(swal.noop)
        }, function(dismiss) {
            // dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
            if (dismiss === 'cancel') {
                swal({
                    title: 'Cancelled',
                    text: 'Your' + itemName + 'safe :)',
                    type: 'error',
                    confirmButtonClass: "btn btn-info",
                    buttonsStyling: false
                }).catch(swal.noop)
            }
        })
    },



  showDeleteNotification: function(from, align) {
    type = ['success'];
    // type = ['', 'info', 'danger', 'success', 'warning', 'rose', 'primary'];

    color = Math.floor((Math.random() * 6) + 1);

    $.notify({
      icon: "done",
      message: "Deleted!"

    }, {
      type: type[color],
      timer: 3000,
      placement: {
        from: from,
        align: align
      }
    });
  },

   showCustomNotification: function (from, align, message,icon,color) {
    // type = ['', 'info', 'danger', 'success', 'warning', 'rose', 'primary'];
    $.notify({
      icon:  icon,
      message: message

    }, {
      type: color,
      timer: 3000,
      placement: {
        from: from,
        align: align
      }
    });
  }
}


  $('.js-close-modal').on('click', function(){
       $('#modal').remove();
    });



$('.nav-link').on('click', function (e) {

    var id = $(this).data('menu');

    if(id) {
        $('.slide-nav').animate({
            opacity: 1,
        }, 300, "linear", function() {}).addClass('active-slide-menu').css('display','block');

        $('.nav-item').removeClass('active');
        $(this).parent().addClass('active');

        $('.slide-nav-items').css('display','none');
        $('#' + id).css('display','block');


        e.preventDefault();
        return false
    }


});
