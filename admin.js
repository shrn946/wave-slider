(function($){
  $(document).ready(function(){

    // Add Image Button
    $(document).on('click', '#wave-add-image, .wave-add-image', function(e){
      e.preventDefault();

      // always create a new frame
      var frame = wp.media({
        title: 'Select Images',
        button: { text: 'Add to Slider' },
        multiple: true // allow multiple selection
      });

      frame.on('select', function(){
        var selection = frame.state().get('selection');
        selection.each(function(att){
          var img = att.toJSON();

          // build list item
          var $li = $('<li/>', { class: 'wave-item' });
          var $thumb = $('<div/>', { class: 'thumb' }).css({
            'background-image': 'url(' + img.url + ')',
            'width': '90px',
            'height': '70px',
            'background-size': 'cover',
            'background-position': 'center',
            'border-radius': '6px',
            'border': '1px solid #ddd'
          });
          var $input = $('<input/>', {
            type: 'hidden',
            name: 'wave_slider_images[]',
            value: img.url
          }).css('flex', '1');
          var $remove = $('<button/>', {
            type: 'button',
            class: 'button wave-remove',
            text: 'Remove'
          });

          $li.append($thumb, $input, $remove);
          $('#wave-slider-list').append($li);
        });
      });

      frame.open();
    });

    // Remove Image
    $(document).on('click', '.wave-remove', function(e){
      e.preventDefault();
      $(this).closest('li').remove();
    });

    // Make list sortable
    if ( $.fn.sortable ) {
      $('#wave-slider-list').sortable({
        items: 'li',
        placeholder: 'wave-sortable-placeholder',
        tolerance: 'pointer',
        forcePlaceholderSize: true
      });
    }

  });
})(jQuery);
