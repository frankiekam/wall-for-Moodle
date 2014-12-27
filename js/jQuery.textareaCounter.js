/**
 * jQuery.textareaCounter
 * Version 1.0
 * Copyright (c) 2011 c.bavota - http://bavotasan.com
 * Dual licensed under MIT and GPL.
 * Date: 10/20/2011
 **/
(function($) {
    $.fn.textareaCounter = function(options) {
        // setting the defaults
        // $("textarea").textareaCounter({ limit: 100 });
        var defaults = {
            limit: 2000
        };
        var options = $.extend(defaults, options);

        // and the plugin begins
        return this.each(function() {
            var obj, text, wordcount, limited;

            obj = $(this);
            obj.after('<span style="font-size: 9px; text-align:left; clear: both; margin-top: 3px; display: block;" id="counter-text">Max. ' + options.limit + ' words</span>');

            obj.keyup(function() {
                text = obj.val();
                if (text === "") {
                    wordcount = 0;
                } else {
                    //wordcount = $.trim(text).split(" ").length; //Doesn't work since it doesn't count newlines.
                    wordcount = text.match(/\S+/g).length; //Now this works fine!
                }
                if (wordcount >= options.limit) {
                    $("#counter-text").html('<span style="font-size: 9px; color: #DD0000; text-align:left; ">0 words left</span>');
                    limited = $.trim(text).split(" ", options.limit);
                    limited = limited.join(" ");
                    $(this).val(limited);
                } else {
                    //This line displays the word count statistics
                    if (wordcount > 1)
                        $("#counter-text").html('<span style="font-size: 9px; color: darkgray;">' + '&nbsp;&nbsp;&nbsp' + wordcount + ' words. ' + (options.limit - wordcount) + ' left</span>');
                    else
                        $("#counter-text").html('<span style="font-size: 9px; color: darkgray;">' + '&nbsp;&nbsp;&nbsp' + wordcount + ' word. ' + (options.limit - wordcount) + ' left</span>');

                }
            });
        });
    };
})(jQuery);