$(function() {
  // switch login-registration page
  $('.register-link').on('click', function(e) {
    e.preventDefault();   
    $('.login-block').hide(); 
    $('.register-block').show();
  });
    $('.login-link').on('click', function(e) {    
    e.preventDefault();
    $('.register-block').hide();
    $('.login-block').show();         
  });

  var options = {};
  options.ui = {
    container: '#pwd-container',
    showVerdictsInsideProgressBar: true,    
    bootstrap4: true,
    viewports: {
      progress: '.pwstrength_viewport_progress'
    }
  }; 

  $('#password').pwstrength(options);

  var recaptchaCallback = function() {
    $('.btn-submit').removeAttr('disabled');
  };

  // show tooltip over form labels 
  $('[data-toggle="tooltip"]').tooltip();

  // user profile modal
  $('.dropdown-menu .up-prfl').click(function(e) {
    e.preventDefault();
    $('#profileModal').modal('show');
  });

  // change password modal
  $('.dropdown-menu .ch-pass').click(function(e) {
    e.preventDefault();
    $('#changePwdModal').modal('show');
  });

  if($('#media-manager').length) {
    var setLayoutHeight = function() {
      var height = $(window).height() - 177;    
      $('.media-layout').css('height', height);
    }

    var initMasonry = function() {        
      $('#masonry-layout').masonry({
        itemSelector: '.media-item'                          
      }); 
    }

    var rename_media = function(el) {
      var media = $(el).data('media');      
      var raw_name = $(el).data('raw-name');
      var path = $(el).data('path')

      bootbox.prompt({
        title: 'Rename '+media,
        value: raw_name,
        callback: function(name) {      
          if ((name !== null) && (name != raw_name)) {
            $.post(site_url + 'rename_media', {
              'path': path,
              'edited_name': name
            }).done(function(response) {                                      
              window.location.assign(site_url + 'index');
            });
          }
        }
      });
    } 

    setLayoutHeight();  

    $(window).resize(function(){
      setLayoutHeight();
    });

    // Safari Browser check for muliple file upload issue
    var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    if (isSafari) {
      $('#filedata').removeAttr('multiple')
    }

    oc = $('.btn-off-canvas');
    wrapper = $('.wrapper');
    
    oc.click(function() {
      oc.toggleClass('active');
      wrapper.toggleClass('active');      
    });

    // Load media of selected folder
    $('a.mediapath').click(function(e) {        
      e.preventDefault();    
      $('#path').val($(this).attr('href'));
      $('#media-form').submit();
    });

    var groups = {};
    $('.gallery-item').each(function() {
      var id = parseInt($(this).attr('data-group'), 10);      
      if(!groups[id]) {
        groups[id] = [];
      }       
      groups[id].push(this);
    });    

    $.each(groups, function() {      
      $(this).magnificPopup({
        closeBtnInside: false,
        gallery: { enabled:true },
        callbacks: {
          elementParse: function(item) { 
            var type = $(item.el).data('type');
            if(type == 'video') {
              item.src = '<div class="white-popup"><video src="'+item.src+'" style="width:100%;height:100%"></video></div>'
            } else if(type == 'audio') {
              item.src = '<div class="white-popup"><audio src="'+item.src+'" style="width:100%;height:100%"></audio></div>'
            }
          },
          open: function() {
            var type = jQuery(this.currItem.el).data('type');
            if(type == 'video' || type == 'audio') {
              initializeMediaElement();
            }
          },
          afterChange: function() {
            var type = jQuery(this.currItem.el).data('type');
            if(type == 'video' || type == 'audio') {
              initializeMediaElement();
            }
          }
        }
      })      
    });

    var initializeMediaElement = function() {
      $('video,audio').mediaelementplayer({
        features: ['playpause','progress','current','duration','tracks','volume','fullscreen']
      });
    }

    // Set default layout if cookie exists      
    var layout  = (Cookies.get('layout')) ? '#details' : '#thumbs';    
    $(layout).addClass('active');
    $(layout + '-layout').removeClass('hidden-xs-up');
    
    if(layout == '#thumbs'){
      $('#btn-group-select').removeClass('hidden-xs-up');       
      initMasonry();
    }  

    $('.btn-layout').click(function(){
      if(!jQuery(this).hasClass('active')) {
        var layout = jQuery(this).data('layout');

        // Set cookie for details view
        if (layout == 'details') {
          if (!Cookies.get('layout')) {
            Cookies.set('layout', 1, {
              expires: 7, // cookie expiration days
              path: '/'
            });
          }            
        } else {
          Cookies.remove('layout', { path: '/' });            
        }

        jQuery('.btn-layout').toggleClass('active');
        jQuery('.media-layout').toggleClass('hidden-xs-up');
        $('#btn-group-select').toggleClass('hidden-xs-up');

        if(layout == 'thumbs'){
          initMasonry();
        } 
      }
    });

    // Enable select button, disable media links
    $('#btn-group-select button').click(function(e) {                
      $(this).toggleClass('active');
      $('.cover').toggleClass('media-disabled');
      $('.media-item').removeClass('media-selected');
      $('#thumbs-layout input').prop('checked',false);
    }); 

    // Select media items
    $('.media-item').click(function() {  
      if($('#btn-group-select button').hasClass('active')){
        $(this).toggleClass('media-selected');            
        $(this).find('input').prop('checked',function(i,val) {
          return !val;
        });
      }    
    });

    $('.btn-rename').click(function(e){
      e.preventDefault();
      rename_media(this);
    });

    $('.btn-tb-rename').click(function(e){
      e.preventDefault();

      var checked = $('#media-form input:checkbox').is(':checked');
      if (checked === true) {       
        el = $('#media-form input:checkbox:checked:first');       
        rename_media(el);
      } else {
        bootbox.alert('Select atleast one media or folder.');
      }
    });

    // Delete file or folder
    $('.btn-delete').click(function(e) {
      e.preventDefault();    
      var dom = this;
      var media = $(dom).data('media');
      var msg;

      if (media == 'folder') {
        msg = 'This action will delete the selected folder and all its contents.'
      } else if (media == 'file') {
        msg = 'This action will delete the selected file.'
      }

      bootbox.confirm(msg, function(r) {
        if (r === true) {
          // ajax request to remove file/folder
          $.post(site_url + 'remove_media', {
            'rm[]': [$(dom).attr('href')]
          }).done(function() {                                                          
            window.location.assign(site_url + 'index');
          })
        }
      })
    });

    // Delete file or folder
    $('.btn-tb-delete').click(function(e) {
      var checked = $('#media-form input:checkbox').is(':checked');
      if (checked === true) {
        bootbox.confirm('This action will delete the selected media.', function(r) {
          if (r === true) {
            $.post(site_url + 'remove_media', $('#media-form').serialize()).done(function() {              
              window.location.assign(site_url + 'index');
            });
          }
        });
      } else {
        bootbox.alert('Select atleast one media or folder.');
      }
    });

    // Drag and drop support for files upload
    Dropzone.autoDiscover = false;
      
    $('#upload-form').dropzone({
      paramName: 'filedata', // The name that will be used to transfer the file
      uploadMultiple: true,
      maxFilesize: max_size, //MB 
      maxFiles: max_files,   
      parallelUploads: 1,
      addRemoveLinks: true,
      autoProcessQueue: false,      
      init: function() {          
        dz = this;          
        var submitButton = $('.btn-upload');

        // On adding file
        dz.on('addedfile', function(file) {            
          submitButton.css('display','block');            
        });

        // On removing file
        dz.on('removedfile', function(file) {            
          if(!dz.getQueuedFiles().length){
            submitButton.css('display','none');
          }
        });  

        // On clicking submit button start upload process
        submitButton.click(function(){
          dz.processQueue();
        }); 

        // process files queue if left to upload
        dz.on('success', function(file) {              
          if(dz.getQueuedFiles().length) {              
            dz.processQueue();
          }
        });

        // Send file starts
        dz.on('sending', function(file, xhr, formData) {             
          formData.append('dz',1); // set to create  
          formData.append('client',JSON.stringify(client));                          
          $('.meter').show();
        });

        // File upload Progress
        dz.on('totaluploadprogress', function(progress) {            
          $('.roller').width(progress + '%');
        });

        dz.on('queuecomplete', function(progress) {
          $('.meter').delay(999).slideUp(999);
          submitButton.css('display','none');
          window.location.assign(site_url + 'index');
        });            
      }
    });
  }
});