$(document).ready(function() {

  // Style checkboxes & radio buttons 

  $('input[type="checkbox"], input[type="radio"]').each(function() {
    var $this = $(this),
      classes = this.className,
      element_type = ($this[0].tagName == 'SELECT')? 'select' : $this.attr('type'),
      $element = $('<span class="' + classes + ' styled-element styled-' + element_type + '"></span>'),
      $parent = $this.parent(),
      $labels = $(),      
      id = $this.attr('id'),
      name = $this.attr('name'),
      offset = $this.offset();
    
    if(element_type == 'select') {
      $element.text($this.val());           
    }
    else {
      if($this.is(':checked')) {
        $element.addClass('checked');
      }
    }

    if($this.is('[disabled]')) {
      $element.addClass('disabled');
    }

    $this.addClass('styled styled-hidden').before($element);    

    if(element_type == 'select') {
      $this.width($element.outerWidth());
      // outerWidth returns innner width in ie8
    } else if(element_type == 'radio') {
      // Find all radio buttons with the same name
      var $relative_radios = $('input[type="radio"][name="' + name + '"]').not($this);      
    }

    // Events

    $this.hover(function() {
      $element.addClass('hover').trigger('mouseover');
    }, function() {
      $element.removeClass('hover').trigger('mouseout');
    }).focus(function() {
      $element.addClass('focus');     
      // Triggering focus on element causes bugs in ie
    }).focusout(function() {
      $element.removeClass('focus active');
    }).change(function() { 

      if(element_type == 'select') {
        $element.text($this.val()); 
      } else {
        if($this.is(':checked')) {
          $element.addClass('checked');

          // Trigger change event on relative radio buttons so they become unchecked
          if($relative_radios) {
            $relative_radios.trigger('change');
          }
        } else {
          $element.removeClass('checked');
        }       
      }

      $element.trigger('change');
    })/*.click(function(e) {
      e.stopPropagation();
      $element.trigger('click');
    })*/.mousedown(function() {
      $element.addClass('active').trigger('mousedown');
    }).mouseup(function() {
      $element.trigger('mouseup');
    });

    // Labels
    
    if($parent[0].tagName == 'LABEL') {
      $labels = $parent;
    }

    if(id) {
      $labels = $labels.add('label[for="' + id + '"]');
    }

    if($labels) {
      $labels.hover(function() {
        $element.addClass('hover').trigger('mouseover');
      }, function() {
        $element.removeClass('hover').trigger('mouseout');
      });
    }   
  });
});